<?php
namespace App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Parents;
use App\Model\Plan;
use App\Model\CommPlan;
use App\Model\MilkBar;
use App\Model\OrderHd;
use App\Model\Wallet;
use App\Model\OrderDetail;
use App\Model\School;
use App\Model\Teacher;
use App\Model\Item;
use App\Model\Company; 
use App\Model\Variant; 
use App\Model\Holiday; 
use DB;
use Stripe;
use Session;

class CommonController extends Controller
{
	public function __construct()
	{
		$this->Company 		= new Company; 
		$this->MilkBar 		= new MilkBar;
		$this->Parents 		= new Parents;
		$this->OrderHd 		= new OrderHd;
		$this->Wallet 		= new Wallet;
		$this->Teacher 		= new Teacher;
		$this->OrderDetail 	= new OrderDetail;
		$this->School 		= new School;
		$this->Item 		= new Item;
		$this->CommPlan 	= new CommPlan;
		$this->Plan 		= new Plan;
		$this->Variant 		= new Variant;
		$this->Holiday 		= new Holiday;
	}

	public function ChngStatus(Request $request)
	{
		$lRecIdNo 	= base64_decode($request['lRecIdNo']);
		$sTblName 	= base64_decode($request['sTblName']);
		$sFldName 	= base64_decode($request['sFldName']);
		$nBlkUnBlk 	= base64_decode($request['nBlkUnBlk']);
		
		try
		{
			$IsRecExist = DB::table($sTblName)->Where($sFldName,$lRecIdNo)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
			if(!isset($IsRecExist))
			{
				return redirect()->back()->with('Failed', 'Record not found...');
			}
			else
			{
				$aStatusArr = $this->StatusArr($nBlkUnBlk); 
				$nRow = DB::table($sTblName)->Where($sFldName,$lRecIdNo)->update($aStatusArr);
				if($nRow > 0)
				{
					$sModule="";
					$sStatus="";
					if($sTblName=="mst_cntry"){
						$sModule="Country";
					}else if($sTblName=="mst_state"){
						$sModule="State";
					}else if($sTblName=="mst_milk_bar"){
						$sModule="MilkBar";
					}else if($sTblName=="mst_schl"){
						$sModule="School";
					}else if($sTblName=="mst_tchr"){
						$sModule="Teacher";
					}else if($sTblName=="mst_prnts"){
						$sModule="Parent";
					}

					if($nBlkUnBlk==config('constant.STATUS.BLOCK')){
						$sStatus="Blocked";
					}else if($nBlkUnBlk==config('constant.STATUS.UNBLOCK')){
						$sStatus="Unblocked";
					} 
					if(!empty($sModule))
					{
						Controller::writeFile($sModule.' '.$sStatus);
					}
					return redirect()->back()->with('Success', 'Status changed successfully...');		
				}
				else
				{
					return redirect()->back()->with('Alert', 'Status did not changed...');			
				}
			}
		}
		catch(\Exception $e)
		{
			return redirect()->back()->with('Failed', $e->getMessage());
		}
	}


	public function StatusArr($nBlkUnBlk)
	{
		$aConArr = array(
			'nBlk_UnBlk' 	=> $nBlkUnBlk,
		);
		return $aConArr;
	}

	public function DelRec(Request $request)
	{
		$lRecIdNo 	= base64_decode($request['lRecIdNo']);
		$sTblName 	= base64_decode($request['sTblName']);
		$sFldName 	= base64_decode($request['sFldName']); 
		try
		{
			$IsRecExist = DB::table($sTblName)->Where($sFldName,$lRecIdNo)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
			if(!isset($IsRecExist))
			{
				return redirect()->back()->with('Failed', 'Record not found...');
			}
			else
			{
				$DelArr = $this->DelArr();
				$nRow = DB::table($sTblName)->Where($sFldName,$lRecIdNo)->update($DelArr);
				if($nRow > 0)
				{
					$sModule=""; 
					if($sTblName=="mst_cntry"){
						$sModule="Country";
					}else if($sTblName=="mst_state"){
						$sModule="State";
					}else if($sTblName=="mst_milk_bar"){
						$sModule="MilkBar";
					}else if($sTblName=="mst_schl"){
						$sModule="School";
					}else if($sTblName=="mst_comm_pln"){
						$sModule="Plan";
					}else if($sTblName=="mst_holiday"){
						$sModule="Holiday";
					}
					if(!empty($sModule))
					{
						Controller::writeFile($sModule.' Deleted');
					}
					return redirect()->back()->with('Success', 'Record deleted successfully...');		
				}
				else
				{
					return redirect()->back()->with('Alert', 'Record did not deleted...');			
				}
			}
		}
		catch(\Exception $e)
		{
			return redirect()->back()->with('Failed', $e->getMessage());
		}
	}

	public function DelArr()
	{
		$aConArr = array(
			'nDel_Status' 	=> config('constant.DEL_STATUS.DELETED'),
		);
		return $aConArr;
	}

	public function EmailVrfy(Request $request)
	{
		$sEmailId 	= base64_decode($request['sEmailId']);
		$lRecIdNo 	= base64_decode($request['lRecIdNo']);
		$nUserType 	= base64_decode($request['nUserType']);
		
		if(!empty($sEmailId) && !empty($lRecIdNo) && !empty($nUserType))
		{
			try
			{
				$aAdmnArr = $this->AdmnArr();
				\DB::beginTransaction();
					if($nUserType == config('constant.USER.PARENT'))
					{
						$yEmailExst = $this->Parents->IsEmailExist($sEmailId, $lRecIdNo);
						if(!$yEmailExst)
						{
							return redirect('user/login')->with('Failed', 'Unauthorized Access...');
						}
						else
						{
							$nRow = $this->Parents->UpDtRecrd($aAdmnArr, $lRecIdNo);
            				$aGetPrnt   = $this->Parents->ShrtDtl($sEmailId);
							$aEmailData = ['sUserName' => $aGetPrnt['sFrst_Name']];
            				Controller::SendEmail($aGetPrnt['sEmail_Id'], $aGetPrnt['sFrst_Name'], 'parents_welcome_email', 'Welcome to MyLunchOrder.Online', $aEmailData);
						}
					}
					else if($nUserType == config('constant.USER.MILK_BAR'))
					{
						$yEmailExst = $this->MilkBar->IsEmailExist($sEmailId, $lRecIdNo);
						if(!$yEmailExst)
						{
							return redirect('user/login')->with('Failed', 'Unauthorized Access...');
						}
						else
						{
							$nRow = $this->MilkBar->UpDtRecrd($aAdmnArr, $lRecIdNo);
							$aGetMilk   = $this->MilkBar->ShrtDtl($sEmailId);
							$aEmailData = ['sUserName' => $aGetMilk['sFrst_Name']];
            				Controller::SendEmail($aGetMilk['sEmail_Id'], $aGetMilk['sFrst_Name'], 'milkbar_welcome_email', 'Welcome to MyLunchOrder.Online', $aEmailData);
						}
					}
					else if($nUserType == config('constant.USER.TEACHER'))
					{
						$yEmailExst = $this->Teacher->IsEmailExist($sEmailId, $lRecIdNo);
						
						if(!$yEmailExst)
						{
							return redirect('user/login')->with('Failed', 'Unauthorized Access...');
						}
						else
						{
							$nRow 		= $this->Teacher->UpDtRecrd($aAdmnArr, $lRecIdNo); 
							$aGetTchr   = $this->Teacher->ShrtDtl($sEmailId);
							  
							$aEmailData = ['sUserName' => $aGetTchr['sFrst_Name']];
            				Controller::SendEmail($aGetTchr['sEmail_Id'], $aGetTchr['sFrst_Name'], 'teacher_welcome_email', 'Welcome to MyLunchOrder.Online', $aEmailData);
						}
					}
					else
					{
						return redirect('user/login')->with('Failed', 'Unauthorized Access...');
					}
				\DB::commit();
			}
			catch(\Exception $e)
			{
				return redirect('user/login')->with('Failed', $e->getMessage().' on Line '.$e->getLine());
			}
			return redirect('user/login')->with('Success', 'Email verified, Login Please...');
		}
		else
		{
			return redirect('user/login')->with('Failed', 'Unauthorized Access 4...');
		}
	}

	public function AdmnArr()
	{
		$aComnArr = array(
			"nEmail_Status"	=> config('constant.MAIL_STATUS.VERIFIED'),
		);
		return $aComnArr;
	}
	
	public function OrderDtl(Request $request)
	{
		// Controller::writeFile('View Order Invoice');
		$oOrdrDtl 	= $this->OrderHd->GetOrderDtl(base64_decode($request['lOrdrHdIdNo']));
		$OrdrAmnt 	= $this->Wallet->OrdrIdGet($oOrdrDtl->lOrder_IdNo);
		$oOrdrItms 	= $this->OrderDetail->ItemLst($oOrdrDtl->lOrder_IdNo);
		$aSchlDtl 	= $this->School->SchlDtl($oOrdrDtl->lSchl_IdNo); 
		return json_encode(['oOrdrDtl' => $oOrdrDtl, 'aSchlDtl' => $aSchlDtl, 'oOrdrItms' => $oOrdrItms, 'OrdrAmnt' => $OrdrAmnt]);
	}

	public function SaveReqSch(Request $request)
	{  
		$request->session()->forget('request_school');
		$rules=array();
		$sarray=array();
		$i=1;
		for($i==1;$i<=$request['nTtlRecs'];$i++)
		{
			$rules['lSchlTypes'.$i]	= 'required';
			$rules['sSchlName'.$i]	= 'required';
			$rules['sSbrbName'.$i]	= 'required';
			$rules['sPinCode'.$i]	= 'required|digits:4';
			
			if(!empty($request['lSchlTypes'.$i]) && !empty($request['sSchlName'.$i]) && !empty($request['sSbrbName'.$i]) && !empty($request['sPinCode'.$i]))
			{
				$sarray[$i] = array( 
					'lSchlTypes'	=> $request['lSchlTypes'.$i],
					'sSchlName'		=> $request['sSchlName'.$i],
					'sSbrbName'		=> $request['sSbrbName'.$i],
					'sPinCode'		=> $request['sPinCode'.$i],
				);    
			}
		}
		if(!empty($sarray))
		{
		    $request->session()->put('request_school',$sarray); 
			$aRec = array(
				'Status' 	=> true,
				'Message' 	=> "THIS SCHOOL HAS BEEN REQUESTED FOR ADDITION TO THE PLATFORM...",
			);
		}
		else
		{
			$aRec = array(
				'Status' 	=> false,
				'Message' 	=> "No school requested...",
			);
		}
		return json_encode($aRec, JSON_PRETTY_PRINT);
	}

	public function GetMenu(Request $request)
	{
		if(session('CART_DATA')['lMilkIdNo'] != $request['milkbar'])
		{
			session()->forget('CART_ITEMS');
		}
		$aItems = $this->Item->ItemCtgryLst($request['milkbar']);

		if(!empty($request['milkbar']))
		{
			$sCrdDbt 	= $this->Wallet->CrdtSum(session('USER_ID'), $request['nUserType'], config('constant.TRANS.Debit'), $request['milkbar']);
			$sCrdCrd 	= $this->Wallet->CrdtSum(session('USER_ID'), $request['nUserType'], config('constant.TRANS.Credit'), $request['milkbar']);
		}

		$aItems['wallet'] = isset($sCrdCrd) ? $sCrdCrd - $sCrdDbt : 0;
		return json_encode($aItems);
	}

	public function SaveCart(Request $request)
	{
		$aCartItms = session::get('CART_ITEMS');
		$aCartData = array(
			            "lItemIdNo" 	=> $request['lItemIdNo'],
			            "nItmQty" 		=> $request['nItemQty'],
			            "sItmPrc" 		=> $request['sItemPrc'],
			            "sItmVrnt" 		=> !empty($request['aItmVrnt']) ? json_encode($request['aItmVrnt']) : NULL,
			        );
		session::push('CART_ITEMS',$aCartData);
		return;
	}

	public function RemoveCart(Request $request)
	{
		$nKey = base64_decode($request['nKey']);
		$aCartItms = session::get('CART_ITEMS');
		unset($aCartItms[$nKey]);
		session()->put('CART_ITEMS', $aCartItms);
		return;
	}

	public function CartData()
	{
		$aCartItms = session::get('CART_ITEMS');
		return view('ajax_page.cart_data',compact('aCartItms'));
	}

	public function GetVarnt(Request $request)
	{
		$lItemIdNo 	= base64_decode($request['lItemIdNo']);
		$aGetItm 	= $this->Item->ItmVrnt($lItemIdNo);
		return view('ajax_page.item_option',compact('aGetItm'));
	}

	public function AutoDebit()
	{
		$aGetPrnt = $this->Parents->StrpDtl();
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        foreach($aGetPrnt as $aRec) 
        {
            try
            {
                $aPlnDtl    = $this->CommPlan->AplyDtl($aRec['lCntry_IdNo'], $aRec['lState_IdNo']);
                $sPlnAmo    = $aPlnDtl['sPrnt_Amo'];    
                if(date('d') > 1)
                {
                    $sPlnAmo = number_format(($aPlnDtl['sPrnt_Amo'] / date('t', strtotime(date('Y-m-d')))) * (date('t', strtotime(date('Y-m-d'))) - date('d', strtotime(date('Y-m-d'))) + 1), 2);
                }

                $oCustData  = \Stripe\PaymentMethod::all([
                                'customer'  => $aRec['sStrp_CustId'],
                                'type'      => 'card',
                            ]);

                $oPayData   = \Stripe\PaymentIntent::create([
                                'amount'        => $sPlnAmo*100,
                                'currency'      => $aRec['sCurr_Code'],
                                'customer'      => $aRec['sStrp_CustId'],
                                'payment_method'=> $oCustData->id,
                                'description'   => 'PAYMENT FOR '.strtoupper(date('F')).' SUBSCRIPTION ('.$aRec['sFrst_Name'].' '.$aRec['sLst_Name'].')',
                                'off_session'   => true,
                                'confirm'       => true,
                            ]);

                if(isset($oPayData) && $oPayData->status == 'succeeded')
                {
                    $sPlnArr = $this->PlanArr($aRec['lPrnt_IdNo'], $sPlnAmo, $oPayData->id);
                    $this->Plan->InsrtRecrd($sPlnArr);
                    $aEmailData = ['sUserName' => $aRec['sFrst_Name']." ".$aRec['sLst_Name'], 'sEmailId' => $aRec['sEmail_Id'], 'sPayStatus' => $oPayData->status, 'sPlnAmo' => number_format($sPlnAmo, 2), 'sTxnId' => $oPayData->id];
                }
            }
            catch(\Exception $e)
            {
                $aEmailData = ['sUserName' => $aRec['sFrst_Name']." ".$aRec['sLst_Name'], 'sEmailId' => $aRec['sEmail_Id'], 'sPayStatus' => 'failed', 'sPlnAmo' => number_format($sPlnAmo, 2), 'sReason'  => $e->getMessage()];
            }
            Controller::SendEmail($aRec['sEmail_Id'], $aRec['sFrst_Name']." ".$aRec['sLst_Name'], 'payment_email', strtoupper(date('F'))." SUBSCRIPTION", $aEmailData);
        }
	}

	public function PlanArr($lPrntIdNo, $sPlnAmo, $sPymntId)
    {
        $aConArr = array(
            'lPrnt_IdNo'    => $lPrntIdNo,
            'sStrp_Id'      => $sPymntId,
            'sPln_Amo'      => number_format($sPlnAmo, 2),
            'sPln_Dur'      => date('Y-m'),
            'sStrt_Dt'      => date('Y-m-d'),
            'sEnd_Dt'       => date('Y-m-t'),
        );
        return $aConArr;
    }

	public function HolidayDtl(Request $request)
	{
		$sDtTm 		= $request['sDtTm'];
		$oHldyDtl	= $this->Holiday->HldyDtl($sDtTm);
		if(isset($oHldyDtl))
		{
			$aRec = array(
				"Status"	=> True,
				"Message"	=> date("d F, Y", strtotime($sDtTm))." have ".array_search($oHldyDtl->nHolday_Type, config('constant.HOLIDAY_TYPE')).", So delivery not avilable. Are you sure to continue ?"
			);
		}
		else
		{
			$aRec = array(
				"Status"	=> False,
			);
		}
		return json_encode($aRec, JSON_PRETTY_PRINT);
	}
}
?>