<?php
namespace App\Http\Controllers\ParentController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ParentAuth;
use Validator;
use Stripe;
use App\Model\Parents;
use App\Model\AssociateSchool;
use App\Model\Child;
use App\Model\School;
use App\Model\Country;
use App\Model\State;
use App\Model\Item;
use App\Model\Wallet;
use App\Model\MilkBar;
use App\Model\OrderHd;
use App\Model\CommPlan;
use App\Model\OrderDetail;
use App\Model\Holiday;
use Excel;
use Session;

class SelfOrderController extends Controller
{
	public function __construct()
	{
		$this->Parents 			= new Parents;
		$this->School 			= new School;
		$this->MilkBar 			= new MilkBar;
		$this->Country 			= new Country;
		$this->State 			= new State;
		$this->OrderHd 			= new OrderHd;
		$this->OrderDetail 		= new OrderDetail;
		$this->Item 			= new Item;
		$this->Wallet 			= new Wallet;
		$this->CommPlan 		= new CommPlan;
		$this->Holiday 			= new Holiday;
		$this->AssociateSchool 	= new AssociateSchool;
		$this->middleware(ParentAuth::class);
	}

	public function IndexPage()
	{
		date_default_timezone_set('Australia/Adelaide');
		$lPrntIdNo 	= session('USER_ID');
		$aCart		= NULL;
		if(session()->has('CART_DATA'))
		{
			$aCart		= Session::get('CART_DATA');
		} 
		else 
		{
			session()->forget('CART_ITEMS');
		}
		$sDtTm 		= session()->has('CART_DATA') ? $aCart['sDelvDate'] : date('Y-m-d');
		$aMilkLst 	= $this->AssociateSchool->PrntSchool($lPrntIdNo, $sDtTm);
		if(isset($aMilkLst))
		{
			$sTitle 	= "My Order";
	    	$aData 		= compact('sTitle','aMilkLst', 'aCart');
	        return view('parent_panel.self_place_order',$aData);	
	    }
	    else
	    {
	    	return redirect('parent_panel')->with('Failed', 'Currenly no school listed for your child...');
	    }
	}

	public function GetMlk(Request $request)
	{
		$lPrntIdNo 	= session('USER_ID');
		$sDtTm 		= empty($request['sDtTm']) ? date('Y-m-d') : $request['sDtTm'];
		$aMilkLst 	= $this->AssociateSchool->PrntSchool($lPrntIdNo, $sDtTm);
		return json_encode($aMilkLst);
	}

	public function SaveOrder(Request $request)
	{
		$lPrntIdNo = session('USER_ID');
		$rules = [
	        'lUserIdNo' 	=> 'required',
            'lMilkIdNo' 	=> 'required',
            'sDtTm'         => 'required',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));
		try
		{

			$aCart['lChldIdNo'] = $request['lUserIdNo'];
			$aCart['lMilkIdNo'] = $request['lMilkIdNo'];
			$aCart['sDelvDate'] = $request['sDtTm'];
			session()->put('CART_DATA', $aCart);
		    return redirect('parent_panel/self/review_order');
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect()->back()->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}
	
	public function RvwOrder(Request $request)
	{
		$sTitle 	= "My Order";
		if(!session()->has('CART_DATA')){
			return redirect("parent_panel/place_order");
		}
		$aCart		= session('CART_DATA');
		$aPrntDtl	= $this->Parents->PrntsDtl($aCart['lChldIdNo']);
		$aSchlDtl	= $this->AssociateSchool->SchlDtl(session('USER_ID'), $aCart['lMilkIdNo']);
		$aMlkDtl	= $this->MilkBar->MilkDtl($aCart['lMilkIdNo']);
		$aCntryDtl	= $this->Country->TaxDtl($aMlkDtl['lCntry_IdNo'], $aMlkDtl['lState_IdNo']);
		$aItemData	= session('CART_ITEMS');
    	$aData 		= compact('sTitle', 'aPrntDtl', 'aMlkDtl', 'aItemData', 'aCntryDtl', 'aSchlDtl');
        return view('parent_panel.self_review_order',$aData);
	}
	
	public function Checkout()
	{
		$sTitle 	= "Checkout";
		if(!session()->has('CART_ITEMS')){
			return redirect("parent_panel/self/place_order");
		}
		
		$lPrntIdNo 	= session('USER_ID');
		$aCart 		= session('CART_DATA');
		$sCrdDbt 	= $this->Wallet->CrdtSum($lPrntIdNo, config('constant.USER.PARENT'), config('constant.TRANS.Debit'), $aCart['lMilkIdNo']);
		$sCrdCrd 	= $this->Wallet->CrdtSum($lPrntIdNo, config('constant.USER.PARENT'), config('constant.TRANS.Credit'), $aCart['lMilkIdNo']);
		$sTtlDbt 	= $this->Wallet->CrdtSum($lPrntIdNo, config('constant.USER.PARENT'), config('constant.TRANS.Debit'));
		$sTtlCrd 	= $this->Wallet->CrdtSum($lPrntIdNo, config('constant.USER.PARENT'), config('constant.TRANS.Credit'));

		$aPrntDtl 	= $this->Parents->PrntsDtl($lPrntIdNo);
		$aSchlDtl	= $this->AssociateSchool->SchlDtl(session('USER_ID'), $aCart['lMilkIdNo']);
		$aMlkDtl	= $this->MilkBar->MilkDtl($aCart['lMilkIdNo']);
		$aCntryDtl	= $this->Country->TaxDtl($aMlkDtl['lCntry_IdNo'], $aMlkDtl['lState_IdNo']);
		
		$total = 0;
		$qty = 0;

		foreach(session('CART_ITEMS') as $aRec)
		{
			$total 	+= $aRec['sItmPrc'] * $aRec['nItmQty'];
			$qty	+= 	$aRec['nItmQty'];
		}
		
		$nTtlCrdt 	= $sTtlCrd - $sTtlDbt;
		$sMlkCrdt 	= $sCrdCrd - $sCrdDbt;
		$aData 		= compact('sTitle', 'aSchlDtl', 'qty', 'total', 'aPrntDtl', 'nTtlCrdt', 'sMlkCrdt', 'aCntryDtl');
		return view('parent_panel.self_checkout',$aData);
	}
	
	public function CheckoutPost(Request $request)
	{
		$rules = [
            'sPicTm'		=> 'required',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

		$yPlaceStatus 	= False;
		$lPrntIdNo 		= session('USER_ID');
		$aCart			= session('CART_DATA');
		$aTimArr 		= $this->AssociateSchool->SchlDtl($lPrntIdNo, $aCart['lMilkIdNo']);
		if(strtotime($aCart['sDelvDate']) > strtotime(date('Y-m-d')))
		{
			$yPlaceStatus = True;
		}
		else if(strtotime($aCart['sDelvDate']) < strtotime(date('Y-m-d')))
		{
			$yPlaceStatus = False;
		}
		else
		{
			if(strtotime($aTimArr['sCut_Tm']) >= strtotime(date('H:i:s')))
			{
				$yPlaceStatus = True;		
			}
		}
		if(!$yPlaceStatus)
		{
			return redirect('parent_panel/self/place_order')->with('Failed', 'Order could not be placed after cutoff time...');
		}
		else
		{

			Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
			
			$aPrntsDtl 	= $this->Parents->PrntsDtl($lPrntIdNo);
			
			$total = 0;
			$qty = 0;

			foreach(session('CART_ITEMS') as $aRec)
			{
				$total 	+= $aRec['sItmPrc'] * $aRec['nItmQty'];
				$qty	+= 	$aRec['nItmQty'];
			}

			$sCrdDbt 	= $this->Wallet->CrdtSum($lPrntIdNo, config('constant.USER.PARENT'), config('constant.TRANS.Debit'), $aCart['lMilkIdNo']);
			$sCrdCrd 	= $this->Wallet->CrdtSum($lPrntIdNo, config('constant.USER.PARENT'), config('constant.TRANS.Credit'), $aCart['lMilkIdNo']);
			$aMilkDtl	= $this->MilkBar->ShrtDtl($aCart['lMilkIdNo']);
			$aCntryDtl	= $this->Country->TaxDtl($aMilkDtl['lCntry_IdNo'], $aMilkDtl['lState_IdNo']);

			$sMlkCrdt 	= $sCrdCrd - $sCrdDbt;
			$sGrndTtl 	= $total;
			$total 		= number_format(($total + ($total  * $aCntryDtl['dTax_Per']) / 100), 2, '.', '');
			$total 		= $total - $sMlkCrdt;
					
			try
			{
				if(empty($aPrntsDtl['sStrp_CustId']))
				{
					$customer = \Stripe\Customer::create(array(
						'name' => $aPrntsDtl['sFrst_Name'].' '.$aPrntsDtl['sLst_Name'],
						'description' => $aPrntsDtl['sAcc_Id'],
						'email' => $aPrntsDtl['sEmail_Id'],
						"source" => $request->stripeToken,
						"address" => ["city" => $aPrntsDtl['sSbrb_Name'], "country" => $aPrntsDtl['sCntry_Name'], "line1" => $aPrntsDtl['sSbrb_Name'], "postal_code" => $aPrntsDtl['sPin_Code'], "state" => $aPrntsDtl['sState_Name']]
					));
					$sCustId 	= $customer->id;
					$this->Parents->UpDtRecrd(array('sStrp_CustId' => $sCustId), $lPrntIdNo);
				}
				else
				{
					$sCustId 	= $aPrntsDtl['sStrp_CustId'];
				}
				
				$orderID =  substr(str_shuffle('1234567890'),0, 8);
				
				$aOrder = Stripe\Charge::create ([
						"customer" => $sCustId,
						"amount" => number_format($total , 2, '.', '')* 100,
						"currency" => $aCntryDtl['sCurr_Code'],
						"description" => "Payment for Order Number: ".$orderID.".",
						"metadata" => array( 
									'order_id' => $orderID 
								)
				]);
				
				$aStngDtl	= $this->CommPlan->AplyDtl($aMilkDtl['lCntry_IdNo'], $aMilkDtl['lState_IdNo']);
				$sComAmo	= number_format($total * $aStngDtl['dCom_Per']/100 , 2, '.', '');
				$aOrderHdArr	= $this->HdArr($aCart['sDelvDate'], $orderID, $aCart['lMilkIdNo'], $aCart['lChldIdNo'], $aCart['lChldIdNo'], $aTimArr['lSchl_IdNo'], $sGrndTtl, $request['nOrderType'], config('constant.USER.PARENT'), $aCntryDtl['dTax_Per'], $request['sPicTm'], $request['nOTP'], $aStngDtl['dCom_Per'], $sComAmo, $aOrder->id);
				$lOrdrHdIdNo 	= $this->OrderHd->InsrtRecrd($aOrderHdArr);
				
				foreach(session('CART_ITEMS') as $aCrtItm)
				{
					$oItmDtl = $this->Item->DtlItm($aCrtItm['lItemIdNo']);
					$aOrderHdArr	= $this->DtlArr($lOrdrHdIdNo, $oItmDtl->lCatg_IdNo, $aCrtItm['lItemIdNo'], $aCrtItm['nItmQty'], $aCrtItm['sItmPrc'], $aCrtItm['sItmVrnt']);
					$this->OrderDetail->InsrtRecrd($aOrderHdArr);
				}
				
				$oOrdrDtl = $this->OrderHd->GetOrder($lOrdrHdIdNo);
				if($sMlkCrdt > 0 && $request['use_Crdt'] == 1)
				{
				    $aWlltArr = $this->WlltArr($oOrdrDtl, config('constant.TRANS.Debit'), $sMlkCrdt);
				    $this->Wallet->InsrtRecrd($aWlltArr);
				}

				$dTranPer 	= number_format((100-($aStngDtl['dCom_Per'] + config('constant.STRP_FEE')))/100 , 2, '.', '');
				$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

				$oTrnsctn = $stripe->transfers->create([
				  'amount' => number_format($total * $dTranPer , 2, '.', '') * 100, 
				  'currency' => 'aud',
				  'destination' => $aMilkDtl['sStrp_Acc_Id'],
				  "source_transaction" => $aOrder->id,
				  'transfer_group' => $lOrdrHdIdNo,
				]);
				
				$this->OrderHd->UpdateTrans($lOrdrHdIdNo, $oTrnsctn->id);
				
				session()->forget('CART_ITEMS');
				session()->forget('CART_DATA');
				return redirect("parent_panel/manage_order")->with('Success', 'Your Order has been successfully placed!');
			}
			catch(\Exception $e)
			{
				return redirect()->back()->with('Failed', $e->getMessage()." On Line ".$e->getLine());
			}
		}  
	}
	
	
	public function CheckoutCrPost(Request $request)
	{
		$rules = [
            'sPicTm'		=> 'required',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));
	    
		$lPrntIdNo 	= session('USER_ID');
		$aCart		= session('CART_DATA');
		$aTimArr 		= $this->AssociateSchool->SchlDtl($lPrntIdNo, $aCart['lMilkIdNo']);
		if(strtotime($aCart['sDelvDate']) > strtotime(date('Y-m-d')))
		{
			$yPlaceStatus = True;
		}
		else if(strtotime($aCart['sDelvDate']) < strtotime(date('Y-m-d')))
		{
			$yPlaceStatus = False;
		}
		else
		{
			if(strtotime($aTimArr['sCut_Tm']) >= strtotime(date('H:i:s')))
			{
				$yPlaceStatus = True;		
			}
		}

		if(!$yPlaceStatus)
		{
			return redirect('parent_panel/self/place_order')->with('Failed', 'Order could not be placed after cutoff time...');
		}
		else
		{	
			$total = 0;
			$qty = 0;

			foreach(session('CART_ITEMS') as $aRec)
			{
				$total 	+= $aRec['sItmPrc'] * $aRec['nItmQty'];
				$qty	+= 	$aRec['nItmQty'];
			}

			$aMilkDtl	= $this->MilkBar->ShrtDtl($aCart['lMilkIdNo']);
			$aCntryDtl	= $this->Country->TaxDtl($aMilkDtl['lCntry_IdNo'], $aMilkDtl['lState_IdNo']);
			$sSimpleTtl = $total;
			$total = number_format($total + ($total  * $aCntryDtl['dTax_Per'] / 100), 2, '.', '');
				
			try
			{
				$orderID =  substr(str_shuffle('1234567890'),0, 8);
				
				
				$aOrderHdArr	= $this->HdArr($aCart['sDelvDate'], $orderID, $aCart['lMilkIdNo'], $aCart['lChldIdNo'], $aCart['lChldIdNo'], $aTimArr['lSchl_IdNo'], $sSimpleTtl, $request['nOrderType'],  config('constant.USER.PARENT'), $aCntryDtl['dTax_Per'], $request['sPicTm'], $request['nOTP']);
				$lOrdrHdIdNo 	= $this->OrderHd->InsrtRecrd($aOrderHdArr);
				
				foreach(session('CART_ITEMS') as $aCrtItm)
				{
					$oItmDtl = $this->Item->DtlItm($aCrtItm['lItemIdNo']);
					$aOrderHdArr	= $this->DtlArr($lOrdrHdIdNo, $oItmDtl->lCatg_IdNo, $aCrtItm['lItemIdNo'], $aCrtItm['nItmQty'], $aCrtItm['sItmPrc'], $aCrtItm['sItmVrnt']);
					$this->OrderDetail->InsrtRecrd($aOrderHdArr);
				}
				
				$oOrdrDtl = $this->OrderHd->GetOrder($lOrdrHdIdNo);
				$aWlltArr = $this->WlltArr($oOrdrDtl, config('constant.TRANS.Debit'), $total);
				$this->Wallet->InsrtRecrd($aWlltArr);
				
				session()->forget('CART_ITEMS');
				session()->forget('CART_DATA');
				return redirect("parent_panel/manage_order")->with('Success', 'Your Order has been successfully placed!');
			}
			catch(\Exception $e)
			{
				return redirect()->back()->with('Failed', "Unable to process payment this time. Please try again later");
			}
		} 
	}
	
	public function HdArr($sDelvDate, $sOrdrId, $lMilkIdNo, $lPrntIdNo, $lChldIdNo, $lSchlIdNo, $sSubTtl, $nOrderType, $nUserType, $sTaxPer, $sPicTm, $nOTP, $dComPer = NULL, $sComAmo = NULL, $sStrpTrnsId = NULL)
	{
		$aConArr = array(
		    'sOrdr_Id'      => $sOrdrId,
			'lMilk_IdNo' 	=> $lMilkIdNo,
			'lPrnt_IdNo' 	=> $lPrntIdNo,
            'lChld_IdNo' 	=> $lChldIdNo,
            'lSchl_IdNo' 	=> $lSchlIdNo,
			'sDelv_Date'	=> $sDelvDate,
            'sSub_Ttl' 		=> number_format($sSubTtl, 2, '.', ''),
            'sGst_Amo'		=> number_format($sSubTtl  * $sTaxPer / 100, 2, '.', ''),
            'sGrnd_Ttl'		=> number_format($sSubTtl + ($sSubTtl  * $sTaxPer / 100), 2, '.', ''),
            'nOrdr_Status' 	=> config('constant.ORDER_STATUS.Pending'),
            'sStrp_Trns_Id'	=> $sStrpTrnsId,
            'dCom_Per'		=> $dComPer,
            'sCom_Amo'		=> $sComAmo,
            'nUser_Type'	=> config('constant.USER.PARENT'),
            'nOrder_Type'	=> $nOrderType,
            'nOrd_Otp'		=> $nOTP,
            'sPic_Tm'		=> $sPicTm,
		);
		return $aConArr;
	}
	
	public function DtlArr($lOrdrHdIdNo, $lCtgryIdNo, $lItmIdNo, $nItmQty, $sItemPrc, $sItmVrnt)
	{
		$aConArr = array(
			'lOrdr_Hd_IdNo' => $lOrdrHdIdNo,
			'lCtgry_IdNo' 	=> $lCtgryIdNo,
            'lItm_IdNo' 	=> $lItmIdNo,
            'nItm_Qty' 		=> $nItmQty,
            'sItem_Prc' 	=> $sItemPrc,
            'sItem_Vrnt' 	=> $sItmVrnt,
		);
		return $aConArr;
	}

	public function WlltArr($oOrdrDtl, $nTrans, $sAmnt)
	{
		$aWlltArr = array(
			'lOrder_IdNo' 	=> $oOrdrDtl->lOrder_IdNo,
            'lMilk_IdNo' 	=> $oOrdrDtl->lMilk_IdNo,
            'lPrnt_IdNo' 	=> $oOrdrDtl->lPrnt_IdNo,
            'lChld_IdNo' 	=> $oOrdrDtl->lChld_IdNo,
			'sTtl_Amo'		=> $sAmnt,
			'nTyp_Status'	=> $nTrans,
		);
		return $aWlltArr;
	}
}
?>