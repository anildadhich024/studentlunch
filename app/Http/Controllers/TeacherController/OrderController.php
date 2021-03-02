<?php
namespace App\Http\Controllers\TeacherController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TeacherAuth;
use Validator;
use Stripe;
use App\Model\Teacher;
use App\Model\TeacherSchool;
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

class OrderController extends Controller
{
	public function __construct()
	{
		$this->Teacher 			= new Teacher;
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
		$this->TeacherSchool 	= new TeacherSchool;
		$this->middleware(TeacherAuth::class);
	}

	public function IndexPage()
	{
		$lTchrIdNo 	= session('USER_ID');
		$aAssSchl	= $this->TeacherSchool->SchlLst($lTchrIdNo);
		if(isset($aAssSchl))
		{
			$lTchrIdNo 	= session('USER_ID');
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
			$aMilkLst 	= $this->TeacherSchool->AssMilkBar($lTchrIdNo, $sDtTm, $lSchlIdNo, $sSchlName);		
			$sTitle 	= "My Order";
	    	$aData 		= compact('sTitle','aMilkLst', 'aCart', 'lSchlIdNo', 'sSchlName');
	        return view('teacher_panel.place_order',$aData);	
	    }
	    else
	    {
	    	return redirect('teacher_panel')->with('Failed', 'Currenly no school listed for you...');
	    }
	}

	public function GetMlk(Request $request)
	{
		$lTchrIdNo 	= session('USER_ID');
		$sDtTm 		= empty($request['sDtTm']) ? date('Y-m-d') : $request['sDtTm'];
		$aMilkLst 	= $this->TeacherSchool->AssMilkBar($lTchrIdNo, $sDtTm, $lSchlIdNo, $sSchlName);
		return json_encode($aMilkLst->toArray());
	}

	public function SaveOrder(Request $request)
	{
		$lTchrIdNo = session('USER_ID');
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
			$aCart['nOrdType'] 	= $request['nOrdType'];
			session()->put('CART_DATA', $aCart);
		    return redirect('teacher_panel/review_order');
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
		if(!session()->has('CART_ITEMS')){
			return redirect("teacher_panel/place_order");
		}
		$aCart		= session('CART_DATA');
		$aTchrDtl	= $this->Teacher->TchrRevw($aCart['lChldIdNo']);
		$aMlkDtl	= $this->MilkBar->MilkDtl($aCart['lMilkIdNo']);
		$aCntryDtl	= $this->Country->TaxDtl($aMlkDtl['lCntry_IdNo'], $aMlkDtl['lState_IdNo']);
		$aItemData	= session('CART_ITEMS');
    	$aData 		= compact('sTitle', 'aTchrDtl', 'aMlkDtl', 'aItemData', 'aCntryDtl');
        return view('teacher_panel.review_order',$aData);
	}
	
	public function Checkout()
	{
		$sTitle 	= "Checkout";
		if(!session()->has('CART_ITEMS')){
			return redirect("teacher_panel/place_order");
		}
		
		$lTchrIdNo 	= session('USER_ID');
		$aCart 		= session('CART_DATA');
		$nDelvStatus = date('D', strtotime($aCart['sDelvDate'])) == 'Sat' || date('D', strtotime($aCart['sDelvDate'])) == 'Sun' ? 1 : 0;
		$aHldy		= $this->Holiday->HldyCnt($aCart['sDelvDate']);
		$sCrdDbt 	= $this->Wallet->CrdtSum($lTchrIdNo, config('constant.USER.TEACHER'), config('constant.TRANS.Debit'), $aCart['lMilkIdNo']);
		$sCrdCrd 	= $this->Wallet->CrdtSum($lTchrIdNo, config('constant.USER.TEACHER'), config('constant.TRANS.Credit'), $aCart['lMilkIdNo']);
		$sTtlDbt 	= $this->Wallet->CrdtSum($lTchrIdNo, config('constant.USER.TEACHER'), config('constant.TRANS.Debit'));
		$sTtlCrd 	= $this->Wallet->CrdtSum($lTchrIdNo, config('constant.USER.TEACHER'), config('constant.TRANS.Credit'));

		$aTchrDtl 	= $this->Teacher->TchrRevw($lTchrIdNo);
		$aSchlDtl	= $this->School->SchlDtl($aTchrDtl['lSchl_IdNo']);
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
		$aData 		= compact('sTitle', 'aSchlDtl', 'qty', 'total', 'aTchrDtl', 'nTtlCrdt', 'sMlkCrdt', 'aCntryDtl','aHldy','nDelvStatus');
		return view('teacher_panel.checkout',$aData);
	}
	
	public function CheckoutPost(Request $request)
	{
		if($request['nOrderType'] == config('constant.ORD_TYPE.PICKUP'))
		{
			$rules = [
	            'sPicTm'		=> 'required',
		    ];

		    $this->validate($request, $rules, config('constant.VLDT_MSG'));
		}
		$yPlaceStatus 	= False;
		$lTchrIdNo 		= session('USER_ID');
		$aCart			= session('CART_DATA');
		$aTimArr 		= $this->TeacherSchool->GetCutTime($lTchrIdNo, $aCart['lMilkIdNo'], $lSchlIdNo);
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
			return redirect('teacher_panel/place_order')->with('Failed', 'Order could not be placed after cutoff time...');
		}
		else
		{

			Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
			
			$aTchrDtl 	= $this->Teacher->TchrDtl($lTchrIdNo);
			$aCntryLst	= $this->Country->FrntLst();
			$aStateLst	= $this->State->FrntLst($aTchrDtl['lCntry_IdNo']);
			
			$total = 0;
			$qty = 0;

			foreach(session('CART_ITEMS') as $aRec)
			{
				$total 	+= $aRec['sItmPrc'] * $aRec['nItmQty'];
				$qty	+= 	$aRec['nItmQty'];
			}

			$sCrdDbt 	= $this->Wallet->CrdtSum($lTchrIdNo, config('constant.USER.TEACHER'), config('constant.TRANS.Debit'), $aCart['lMilkIdNo']);
			$sCrdCrd 	= $this->Wallet->CrdtSum($lTchrIdNo, config('constant.USER.TEACHER'), config('constant.TRANS.Credit'), $aCart['lMilkIdNo']);
			$aMilkDtl	= $this->MilkBar->ShrtDtl($aCart['lMilkIdNo']);
			$aCntryDtl	= $this->Country->TaxDtl($aMilkDtl['lCntry_IdNo'], $aMilkDtl['lState_IdNo']);

			$sMlkCrdt 	= $sCrdCrd - $sCrdDbt;
			$sGrndTtl 	= $total;
			$total 		= number_format(($total + ($total  * $aCntryDtl['dTax_Per']) / 100), 2, '.', '');
			$total 		= $total - $sMlkCrdt;
					
			try
			{
				$customer = \Stripe\Customer::create(array(
					'name' => $aTchrDtl['sFrst_Name'].' '.$aTchrDtl['sLst_Name'],
					'description' => $aTchrDtl['sAcc_Id'],
					'email' => $aTchrDtl['sEmail_Id'],
					"source" => $request->stripeToken,
					"address" => ["city" => $aTchrDtl['sSbrb_Name'], "country" => $aTchrDtl['sCntry_Name'], "line1" => $aTchrDtl['sSbrb_Name'], "postal_code" => $aTchrDtl['sPin_Code'], "state" => $aTchrDtl['sState_Name']]
				));
				
				$orderID =  substr(str_shuffle('1234567890'),0, 8);
				
				$aOrder = Stripe\Charge::create ([
						"customer" => $customer->id,
						"amount" => number_format($total , 2, '.', '')* 100,
						"currency" => $aCntryDtl['sCurr_Code'],
						"description" => "Payment for Order Number: ".$orderID.".",
						"metadata" => array( 
									'order_id' => $orderID 
								)
				]);
				
				$aStngDtl	= $this->CommPlan->AplyDtl($aMilkDtl['lCntry_IdNo'], $aMilkDtl['lState_IdNo']);
				$sComAmo	= number_format($total * $aStngDtl['dCom_Per']/100 , 2, '.', '');
				$aOrderHdArr	= $this->HdArr($aCart['sDelvDate'], $orderID, $aCart['lMilkIdNo'], $aTchrDtl['lTchr_IdNo'], $aCart['lChldIdNo'], $lSchlIdNo, $sGrndTtl, $request['nOrderType'], config('constant.USER.TEACHER'), $aCntryDtl['dTax_Per'], $request['sPicTm'], $aStngDtl['dCom_Per'], $sComAmo, $aOrder->id);
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
				return redirect("teacher_panel/manage_order")->with('Success', 'Your Order has been successfully placed!');
			}
			catch(\Exception $e)
			{
				return redirect()->back()->with('Failed', $e->getMessage()." On Line ".$e->getLine());
			}
		}  
	}
	
	
	public function CheckoutCrPost(Request $request)
	{
		if($request['nOrderType'] == config('constant.ORD_TYPE.PICKUP'))
		{
			$rules = [
	            'sPicTm'		=> 'required',
		    ];

		    $this->validate($request, $rules, config('constant.VLDT_MSG'));
		}
		$lTchrIdNo 	= session('USER_ID');
		$aCart		= session('CART_DATA');
		$aTimArr 	= $this->TeacherSchool->GetCutTime($lTchrIdNo, $aCart['lMilkIdNo'], $lSchlIdNo);
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
			return redirect('teacher_panel/place_order')->with('Failed', 'Order could not be placed after cutoff time...');
		}
		else
		{
			$aTchrDtl 	= $this->Teacher->TchrDtl($lTchrIdNo);
			$aCntryLst	= $this->Country->FrntLst();
			$aStateLst	= $this->State->FrntLst($aTchrDtl['lCntry_IdNo']);
			
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
				
				
				$aOrderHdArr	= $this->HdArr($aCart['sDelvDate'], $orderID, $aCart['lMilkIdNo'], $aTchrDtl['lTchr_IdNo'], $aCart['lChldIdNo'], $lSchlIdNo, $sSimpleTtl, $request['nOrderType'],  config('constant.USER.TEACHER'), $aCntryDtl['dTax_Per'], $request['sPicTm']);
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
				return redirect("teacher_panel/manage_order")->with('Success', 'Your Order has been successfully placed!');
			}
			catch(\Exception $e)
			{
				return redirect()->back()->with('Failed', "Unable to process payment this time. Please try again later");
			}
		} 
	}
	
	public function HdArr($sDelvDate, $sOrdrId, $lMilkIdNo, $lTchrIdNo, $lChldIdNo, $lSchlIdNo, $sSubTtl, $nOrderType, $nUserType, $sTaxPer, $sPicTm, $dComPer = NULL, $sComAmo = NULL, $sStrpTrnsId = NULL)
	{
		$aConArr = array(
		    'sOrdr_Id'      => $sOrdrId,
			'lMilk_IdNo' 	=> $lMilkIdNo,
			'lPrnt_IdNo' 	=> $lTchrIdNo,
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
            'nUser_Type'	=> config('constant.USER.TEACHER'),
            'nOrder_Type'	=> $nOrderType,
            'nOrd_Otp'		=> rand(1001,9999),
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
	
	public function ListOrder(Request $request)
	{
		$lTchrIdNo 		= session('USER_ID');
		$sCrtDtTm 		= $request['sCrtDtTm'];
		$nOrdrStatus 	= $request['nOrdrStatus'];
		$oOrderLst 		= $this->OrderHd->OrderLst($lTchrIdNo, config('constant.USER.TEACHER'), $sCrtDtTm, $nOrdrStatus);
		$sTitle 		= "Manage Order";
    	$aData 			= compact('sTitle','oOrderLst','request');
        return view('teacher_panel.order_list',$aData);
	}
	
	public function CancelOrder(Request $request)
	{
	    $lOrderIdNo = base64_decode($request['lRecIdNo']);
		$oOrdrDtl = $this->OrderHd->GetOrder($lOrderIdNo);
		if($oOrdrDtl->nOrdr_Status == config('constant.ORDER_STATUS.Pending'))
		{
			$oSchl 		= $this->TeacherSchool->GetCutTime($oOrdrDtl->lChld_IdNo, $oOrdrDtl->lMilk_IdNo, $lSchlIdNo);
			$oOrdrDtl 	= $this->OrderHd->Cancel($lOrderIdNo,$request['sCnclReason'],$request['sCnclNote']);
	 
			$aMilkDtl	= $this->MilkBar->ShrtDtl($oOrdrDtl->lMilk_IdNo);
			$aStngDtl	= $this->CommPlan->AplyDtl($aMilkDtl['lCntry_IdNo'], $aMilkDtl['lState_IdNo']);
			if(strtotime($oOrdrDtl->sDelv_Date) > strtotime(date('Y-m-d')))
			{
				$sRfndAmo 	= $oOrdrDtl->sGrnd_Ttl;
			}
			else if(strtotime($oOrdrDtl->sDelv_Date) < strtotime(date('Y-m-d')))
			{
				$sRfndAmo 	= number_format(($oOrdrDtl->sGrnd_Ttl * (100 - $aStngDtl['dCacl_Per'])) / 100, 2);
			}
			else
			{
				if(strtotime($oSchl['sCut_Tm']) >= strtotime(date('H:i:s')))
				{
					$sRfndAmo 	= $oOrdrDtl->sGrnd_Ttl;
				}
				else
				{
					$sRfndAmo 	= number_format(($oOrdrDtl->sGrnd_Ttl * (100 - $aStngDtl['dCacl_Per'])) / 100, 2);
				}
			}

			$aWlltArr 	= $this->WlltArr($oOrdrDtl, config('constant.TRANS.Credit'), $sRfndAmo);
			$nRow 		= $this->Wallet->InsrtRecrd($aWlltArr);
			return redirect()->back()->with('Success', "Order cancelled successfully. Amount is added to your wallet.");
		}
		else
		{
			return redirect()->back()->with('Failed', "Order delivered so you can not deliver now...");
		}
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
	
	public function ExprtRcrd(Request $request)
	{
		$lTchrIdNo 		= session('USER_ID');
		$sCrtDtTm 		= $request['sCrtDtTm'];
		$nOrdrStatus 	= $request['nOrdrStatus'];
		$sFrmDate = $sToDate = '';
		if(!empty($sCrtDtTm))
		{
			$sFrmDate = $sCrtDtTm." 00:00:00";
			$sToDate = $sCrtDtTm." 23:59:59";
		}
		else
		{
			$sFrmDate 	= !empty($request['sFrmDate']) ? $request['sFrmDate']." 00:00:00" : '';
			$sToDate 	= !empty($request['sToDate']) ? $request['sToDate']." 23:59:59" : '';
			if(!empty($request['sFrmDate']) && !empty($request['sToDate']))
			{
				$sToDate  		= date('Y-m-d 23:59:59', strtotime($sToDate));
				$sFrmDate  		= date('Y-m-d 00:00:00', strtotime($sFrmDate));
			}

			if(!empty($request['nRprtDur']))
			{
			    $nRprtDur       = $request['nRprtDur']-1;
				$sToDate  		= date('Y-m-d 23:59:59');
				$sFrmDate  		= date('Y-m-d 00:00:00', strtotime('-'.$nRprtDur.' days'));
			}
		}
		$aOrdLst		= $this->OrderHd->ExlRcrdPrnt($lTchrIdNo, config('constant.USER.TEACHER'), $sFrmDate, $sToDate, $nOrdrStatus);
		if(count($aOrdLst) > 0)
		{
			$FileName = 'My_Orders_'.date('Ymd').'_'.date('His');
	        Excel::create($FileName, function($excel) use ($aOrdLst) {
	            $excel->sheet('Sheet1', function($sheet)  use ($aOrdLst) {
	                $this->SetExlHeader($sheet, $lRaw);
	                $this->SetExlData($sheet, $lRaw, $aOrdLst);
	            });
	        })->download('xlsx');
	    }
	    else
	    {
        	return redirect()->back()->with('Failed', 'Record not found...');
	    }

	}

	public function SetExlHeader($sheet, &$lRaw)
	{
		$lRaw = 1;
		Controller::SetCell(config('excel.XL_TCHR_ORD.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'left', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_TCHR_ORD.DEL_DATE'), $lRaw, 'Delivery Date', $sheet, '', '#F2DDDC', 'left', True, '', False, 12, '', 10);
		Controller::SetCell(config('excel.XL_TCHR_ORD.ORD_NO'), $lRaw, 'Order No', $sheet, '', '#F2DDDC', 'left', True, '', False, 12, '', 10);
		Controller::SetCell(config('excel.XL_TCHR_ORD.MILK_NAME'), $lRaw, 'Service Provider Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_TCHR_ORD.SUB_AMO'), $lRaw, 'Sub Total', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_TCHR_ORD.GST_AMO'), $lRaw, 'GST', $sheet, '', '#F2DDDC', 'left', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_TCHR_ORD.GRNT_AMO'), $lRaw, 'Grand Total', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_TCHR_ORD.TRAN_DATE'), $lRaw, 'Transaction Date', $sheet, '', '#F2DDDC', 'left', True, '', False, 18, '', 10);
		Controller::SetCell(config('excel.XL_TCHR_ORD.ORD_STATUS'), $lRaw, 'Status', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_TCHR_ORD.ITM_NAME'), $lRaw, 'Item Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_TCHR_ORD.ITM_PRC'), $lRaw, 'Item Price', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_TCHR_ORD.ITM_QTY'), $lRaw, 'Quantity', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_TCHR_ORD.TTL_PRC'), $lRaw, 'Item Total', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
	}

	public function SetExlData($sheet, $lRaw, $aOrdLst)
	{
		$i = 0;
		date_default_timezone_set('Australia/Adelaide');
		while(isset($aOrdLst) && count($aOrdLst) > 0 && $i<count($aOrdLst))
		{
			$lRaw = $lRaw + 1;
			$aItmLst = $this->OrderDetail->ExlRcrd($aOrdLst[$i]['lOrder_IdNo']);
			$nMrgCell = count($aItmLst) > 1 ? count($aItmLst) - 1 : '';
			Controller::SetCell(config('excel.XL_TCHR_ORD.SR_NO'), $lRaw, $i+1, $sheet, config('excel.XL_TCHR_ORD.SR_NO'), '', 'right', False, '', False, 8, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR_ORD.DEL_DATE'), $lRaw, date('d M, Y', strtotime($aOrdLst[$i]['sDelv_Date'])), $sheet, config('excel.XL_TCHR_ORD.DEL_DATE'), '', 'left', True, '', False, 12, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR_ORD.ORD_NO'), $lRaw, $aOrdLst[$i]['sOrdr_Id'], $sheet, config('excel.XL_TCHR_ORD.ORD_NO'), '', 'left', True, '', False, 12, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR_ORD.MILK_NAME'), $lRaw, $aOrdLst[$i]['sBuss_Name'], $sheet, config('excel.XL_TCHR_ORD.MILK_NAME'), '', 'left', False, '', False, 25, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR_ORD.SUB_AMO'), $lRaw, $aOrdLst[$i]['sSub_Ttl'], $sheet, config('excel.XL_TCHR_ORD.SUB_AMO'), '', 'right', False, '#0.00', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR_ORD.GST_AMO'), $lRaw, $aOrdLst[$i]['sGst_Amo'], $sheet, config('excel.XL_TCHR_ORD.GST_AMO'), '', 'right', False, '#0.00', False, 8, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR_ORD.GRNT_AMO'), $lRaw, $aOrdLst[$i]['sGrnd_Ttl'], $sheet, config('excel.XL_TCHR_ORD.GRNT_AMO'), '', 'right', False, '#0.00', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR_ORD.ORD_STATUS'), $lRaw, $aOrdLst[$i]['sDelv_Date'] < date('Y-m-d') && $aOrdLst[$i]['nOrdr_Status'] == config('constant.ORDER_STATUS.Pending') ? 'OVERDUE' : strtoupper(array_search($aOrdLst[$i]['nOrdr_Status'], config('constant.ORDER_STATUS'))), $sheet, config('excel.XL_TCHR_ORD.ORD_STATUS'), '', 'center', True, '', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR_ORD.TRAN_DATE'), $lRaw, date('d M, Y h:i A', strtotime($aOrdLst[$i]['sCrt_DtTm'])), $sheet, config('excel.XL_TCHR_ORD.TRAN_DATE'), '', 'left', False, '', False, 18, $nMrgCell, 10);

			$c = 0;
			while(isset($aItmLst) && count($aItmLst) > 0 && $c<count($aItmLst))
			{
				Controller::SetCell(config('excel.XL_TCHR_ORD.ITM_NAME'), $lRaw, $aItmLst[$c]['sItem_Name'], $sheet, '', '', 'left', False, '', False, 25, '', 10);
				Controller::SetCell(config('excel.XL_TCHR_ORD.ITM_PRC'), $lRaw, $aItmLst[$c]['sItem_Prc'], $sheet, '', '', 'right', False, '#0.00', False, 10, '', 10);
				Controller::SetCell(config('excel.XL_TCHR_ORD.ITM_QTY'), $lRaw, $aItmLst[$c]['nItm_Qty'], $sheet, '', '', 'right', False, '', False, 10, '', 10);
				Controller::SetCell(config('excel.XL_TCHR_ORD.TTL_PRC'), $lRaw, '='.Controller::GetColName(config('excel.XL_TCHR_ORD.ITM_PRC')).$lRaw.'*'.Controller::GetColName(config('excel.XL_TCHR_ORD.ITM_QTY')).$lRaw, $sheet, '', '', 'right', False, '#0.00', False, 10, '', 10);

				if($c==count($aItmLst)) 
				{
	        		break;	
	        	}
        		$c++;
				$lRaw = $lRaw + 1;	
			}
			$i++;
		}
	}
}
?>