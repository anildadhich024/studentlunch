<?php

namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SuperAdmin;
use App\Model\MilkBar;
use App\Model\School;
use App\Model\Country;
use App\Model\State;
use App\Model\Item;
use App\Model\AssociateSchool;
use Excel;
 use App\Model\Company; 
 
class MilkBarController extends Controller
{
	public function __construct()
	{
		$this->Company 	= new Company; 
		$this->MilkBar 			= new MilkBar;
		$this->School 			= new School;
		$this->Country 			= new Country;
		$this->State 			= new State;
		$this->Item 			= new Item;
		$this->AssociateSchool 	= new AssociateSchool;
		$this->middleware(SuperAdmin::class);
	}

	public function IndexPage(Request $request)
	{
		$aMilkDtl 	= NULL;
		$aStateLst 	= NULL;
		$aAccSchl	= NULL;
		if(isset($request['lRecIdNo']) && !empty($request['lRecIdNo']))
		{
			$lMilkIdNo 	= base64_decode($request['lRecIdNo']);
			$aMilkDtl	= $this->MilkBar->MilkDtl($lMilkIdNo);
			$aAccSchl 	= $this->AssociateSchool->AccSchlLst($lMilkIdNo);
			$aStateLst	= $this->State->FrntLst($aMilkDtl['lCntry_IdNo']);
			if(empty($aMilkDtl))  
			{
				return redirect('admin_panel/milk_bar/list')->with('Failed', 'unauthorized access...');
			}
		}
		$aCntryLst	= $this->Country->FrntLst();
		$aSchlLst	= $this->School->SchlAll();
		$sTitle 	= "Manage Service Provider";
    	$aData 		= compact('sTitle','aMilkDtl','aCntryLst','aStateLst','aSchlLst','aAccSchl');
        return view('admin_panel.manage_milk_bar',$aData);	
	}

	public function SaveCntrl(Request $request)
	{
		$lMilkIdNo 	= base64_decode($request['lMilkIdNo']);
		$rules = [
	        'sBussName' 	=> 'required|min:5|max:50|regex:/^[\pL\s]+$/u',
            'nBussType' 	=> 'required',
            'sAbnNo' 		=> 'required|unique:mst_milk_bar,sAbn_No,'.$lMilkIdNo.',lMilk_IdNo',
            'sFrstName' 	=> 'required|min:3|max:15|regex:/^[\pL\s]+$/u',
            'sLstName' 		=> 'required|min:3|max:15|regex:/^[\pL\s]+$/u',
            'sMobileNo' 	=> 'required',
            'sPhoneNo' 		=> 'required',
            'sEmailId' 		=> 'required|unique:mst_milk_bar,sEmail_Id,'.$lMilkIdNo.',lMilk_IdNo|unique:mst_prnts,sEmail_Id|unique:mst_tchr,sEmail_Id|max:50|regex:^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^',
            'sStrtNo'		=> 'required',
            'sStrtName'		=> 'required|min:5|max:50|regex:/^[\pL\s]+$/u',
            'lCntryIdNo'	=> 'required',
            'lStateIdNo'	=> 'required',
            'sSbrbName'		=> 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'sPinCode'		=> 'required|digits:4',
            'nSchlType1'	=> 'required',
            'lSchlIdNo1'	=> 'required',
            'dDistKm1'		=> 'required|between:0,7',
            'sSbrbName1'	=> 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'sPinCode1'		=> 'required|digits:4',
            'sCutTm1'		=> 'required',
	    ];

	    if($lMilkIdNo == 0) 
	    {
	    	$rules['sEmailId'] 		= 'required|unique:mst_prnts|max:50|regex:^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^';
            $rules['sLgnPass']		= 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/';
            $rules['sCnfrmPass']	= 'required|required_with:sLgnPass|same:sLgnPass';
		}

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
		{
		    $aHdArr 	= $this->HdArr($request);
		    \DB::beginTransaction();
		    	if($lMilkIdNo == 0)
		    	{
		    		$this->InsrtArr($aHdArr, $request['sLgnPass'], $request['sBussName']);
					$lMilkIdNo	= $this->MilkBar->InsrtRecrd($aHdArr);
					Controller::writeFile('MilkBar Created');
		    		$sMessage	= "Account created successfully...";
		    	}
		    	else
		    	{
		    		$nRow		= $this->MilkBar->UpDtRecrd($aHdArr, $lMilkIdNo);
		    		$sDelArr 	= $this->DelArr(); 
					$this->AssociateSchool->DelRecrd($sDelArr, $lMilkIdNo);
					Controller::writeFile('MilkBar Updated');
		    		$sMessage	= "Account update successfully...";
		    	}
		    	if((!empty($lMilkIdNo) && $lMilkIdNo > 0) || isset($nRow))
		    	{
		    		$i=1;
		    		for($i==1;$i<=$request['nTtlRec'];$i++)
		    		{
		    			if(!empty($request['nSchlType'.$i]) && !empty($request['lSchlIdNo'.$i]) && !empty($request['dDistKm'.$i]) && !empty($request['sSbrbName'.$i]) && !empty($request['sPinCode'.$i]) && !empty($request['sCutTm'.$i]))
		    			{
		    				$aSchlArr = $this->SchlArr($lMilkIdNo, $request['nSchlType'.$i], $request['lSchlIdNo'.$i], $request['dDistKm'.$i], $request['sSbrbName'.$i], $request['sPinCode'.$i], $request['sCutTm'.$i]);
		    				if(isset($request['lMilkSchlIdNo'.$i]) && !empty($request['lMilkSchlIdNo'.$i]))
		    				{
								Controller::writeFile('MilkBar School Created');
		    					$this->AssociateSchool->UpDtRecrd($aSchlArr, $request['lMilkSchlIdNo'.$i]);
							}
		    				else
		    				{
								Controller::writeFile('MilkBar School Updated');
								$this->AssociateSchool->InsrtRecrd($aSchlArr);	
		    				}
		    				
		    			}
		    		}
		    	}	
			\DB::commit();
		    return redirect('admin_panel/milk_bar/list')->with('Success', $sMessage);
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect('admin_panel/milk_bar/list')->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function HdArr($request)
	{
		$aConArr = array(
			'sBuss_Name' 	=> $request['sBussName'],
            'nBuss_Type' 	=> $request['nBussType'],
            'sAbn_No' 		=> $request['sAbnNo'],
            'sFrst_Name' 	=> $request['sFrstName'],
            'sLst_Name' 	=> $request['sLstName'],
            'sCntry_Code'	=> $request['sCntryCode'],
            'sArea_Code'	=> $request['sAreaCode'],
            'sPhone_No'		=> $request['sPhoneNo'],
            'sMobile_No'	=> $request['sMobileNo'],
            'sEmail_Id'		=> $request['sEmailId'],
            'sStrt_No'		=> $request['sStrtNo'],
            'sStrt_Name'	=> $request['sStrtName'],
            'sSbrb_Name'	=> $request['sSbrbName'],
            'lCntry_IdNo'	=> $request['lCntryIdNo'],
            'lState_IdNo'	=> $request['lStateIdNo'],
            'sPin_Code'		=> $request['sPinCode'],
		);
		return $aConArr;
	}

	public function InsrtArr(&$aHdArr, $sLgnPass, $sBussName)
	{
		$aHdArr['sAcc_Id'] 			= strtoupper(substr($sBussName, 0, 2)."-".rand(1111,9999));
        $aHdArr['sLgn_Pass']		= md5($sLgnPass);
		$aHdArr['nBlk_UnBlk']		= config('constant.STATUS.UNBLOCK');
		$aHdArr['nDel_Status']		= config('constant.DEL_STATUS.UNDELETED');
		$aHdArr['nAdmin_Status']	= config('constant.MLK_STATUS.UNACTIVE');
		$aHdArr['nEmail_Status']	= config('constant.MAIL_STATUS.VERIFIED');
	}

	public function SchlArr($lMilkIdNo, $nSchlType, $lSchlIdNo, $dDistKm, $sSbrbName, $sPinCode, $sCutTm)
	{
		$aConArr = array(
			"lMilk_IdNo"	=> $lMilkIdNo,
			"nSchl_Type"	=> $nSchlType,
			"lSchl_IdNo"	=> $lSchlIdNo,
			"dDist_Km"		=> $dDistKm,
			"sSbrb_Name"	=> $sSbrbName,
			"sPin_Code"		=> $sPinCode,
			"sCut_Tm"		=> $sCutTm,
			'nBlk_UnBlk'	=> config('constant.STATUS.UNBLOCK'),
			'nDel_Status' 	=> config('constant.DEL_STATUS.UNDELETED'),
		);
		return $aConArr;
	}

	public function DelArr()
	{
		$aDelArr = array(
			"nDel_Status"	=> config('constant.DEL_STATUS.DELETED'),
		);
		return $aDelArr;
	}

	public function ListPage(Request $request)
	{
		$aMlkBarLst = $this->MilkBar->MlkBarLst($request['sBussName'], $request['sMobileNo']);
		$sTitle 	= "Manage Service Provider List";
    	$aData 		= compact('sTitle','aMlkBarLst','request');
        return view('admin_panel.milk_bar_list',$aData);	
	}

	public function DetailPage(Request $request)
	{
		if(isset($request['lRecIdNo']) || !empty($request['lRecIdNo']))
		{
			$lMilkIdNo = base64_decode($request['lRecIdNo']);
			$aMilkDtl = $this->MilkBar->MilkDtl($lMilkIdNo);
			if(empty($aMilkDtl))
			{
				return redirect('admin_panel/manage_parent')->with('Failed', 'parents detail not found...');
			}
			else
			{
				$aSchlLst	= $this->AssociateSchool->AccSchlLst($lMilkIdNo);
				$CntItm 	= $this->Item->CntItm($lMilkIdNo);
				$sTitle 	= $aMilkDtl['sBuss_Name']." Details";
				Controller::writeFile('View MilkBar Details');
		    	$aData 		= compact('sTitle','aMilkDtl','aSchlLst','CntItm');
		        return view('admin_panel.milk_bar_detail',$aData);				
			}
		}
		else
		{
			return redirect('admin_panel/milk_bar/list')->with('Failed', 'unauthorized access...');
		}
	}

	public function ActvStatus(Request $request)
	{
		$lMilkIdNo = base64_decode($request['lRecIdNo']);
		if(!isset($lMilkIdNo) && empty($lRecIdNo))
		{
			return redirect('admin_panel/milk_bar/list')->with('Failed', 'unauthorized access...');	
		}
		else
		{
			try
			{
				$CntItm = $this->Item->CntItm($lMilkIdNo);
				$aGetDtl 	= $this->MilkBar->ShrtDtl($lMilkIdNo);
				if($CntItm->{'TtlRec'} > 0 && !empty($aGetDtl['sStrp_Acc_Id']))
				{
				    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));  
            		$oAccData = \Stripe\Account::retrieve(
            			$aGetDtl['sStrp_Acc_Id'],
            			[]
            		); 
            		if(count($oAccData->requirements->errors) > 0)
            		{
            		    return redirect()->back()->with('Failed', 'Stripe Account : '.$oAccData->requirements->errors[0]->reason);
            		}
            		else
            		{
            		    if($oAccData->capabilities->transfers != 'active')
            		    {
            		        return redirect()->back()->with('Failed', 'Payouts transfer pending in Stripe Account...');
            		    }
            		    else
            		    {
            		        $aActvArr = $this->ActvArr();
        					\DB::beginTransaction();
        						$nRow = $this->MilkBar->UpDtRecrd($aActvArr, $lMilkIdNo);
        						$aGetMilk   = $this->MilkBar->ShrtDtl($lMilkIdNo);
								$aEmailData = ['sUserName' => $aGetMilk['sFrst_Name']]; 
								Controller::writeFile('MilkBar Account Activated');
                				Controller::SendEmail($aGetMilk['sEmail_Id'], $aGetMilk['sFrst_Name'], 'account_activation_email', 'MyLunchOrder.Online Account Activation', $aEmailData);
        					\DB::commit();
        					return redirect()->back()->with('Success', 'Account activated successfully...');       
            		    }
            		}			
				}
				else
				{
					return redirect()->back()->with('Failed', 'Profile not completed...');				
				}
			}
			catch(\Exception $e)
			{
				\DB::rollback();
				return redirect()->back()->with('Failed', $e->getMessage().' on Line '.$e->getLine());
			}
		}
	}

	public function ActvArr()
	{
		$aComnArr = array(
			"nAdmin_Status" => config('constant.MLK_STATUS.ACTIVE'),
		);
		return $aComnArr;
	}
	
	public function ExprtRcrd(Request $request)
	{
		$sBussName = $request['sBussName'];
		$sMobileNo = $request['sMobileNo'];
		$aMilkLst	= $this->MilkBar->ExlRcrd($sBussName, $sMobileNo);
		if(count($aMilkLst) > 0)
		{
			$FileName = 'Milk_Bar_'.date('Ymd').'_'.date('His');
	        Excel::create($FileName, function($excel) use ($aMilkLst) {
	            $excel->sheet('Sheet1', function($sheet)  use ($aMilkLst) {
	                $this->SetExlHeader($sheet, $lRaw);
	                $this->SetExlData($sheet, $lRaw, $aMilkLst);
	            });
	        })->download('xlsx');
	    }
	    else
	    {
        	return redirect()->back()->with('Success', 'Record not found...');
	    }

	}

	public function SetExlHeader($sheet, &$lRaw)
	{
		$lRaw = 1;
		Controller::SetCell(config('excel.XL_MILK.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'right', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_MILK.ACC_ID'), $lRaw, 'Account Id', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_MILK.BUSS_TYPE'), $lRaw, 'Business Type', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_MILK.BUSS_NAME'), $lRaw, 'Business Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_MILK.ABN_NO'), $lRaw, 'ABN No', $sheet, '', '#F2DDDC', 'right', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_MILK.USER_NAME'), $lRaw, 'Contact Person', $sheet, '', '#F2DDDC', 'left', True, '', False, 18, '', 10);
		Controller::SetCell(config('excel.XL_MILK.PHONE_NO'), $lRaw, 'Phone No', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_MILK.MOBILE_NO'), $lRaw, 'Mobile No', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_MILK.EMAIL_ID'), $lRaw, 'Email Address', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_MILK.BUSS_ADDR'), $lRaw, 'Address', $sheet, '', '#F2DDDC', 'left', True, '', True, 40, '', 10);
		Controller::SetCell(config('excel.XL_MILK.STRP_ACC'), $lRaw, 'Payment Status', $sheet, '', '#F2DDDC', 'left', True, '', False, 13, '', 10);
		Controller::SetCell(config('excel.XL_MILK.ACC_STATUS'), $lRaw, 'Status', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_MILK.SCHL_TYPE'), $lRaw, 'School Type', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_MILK.SCHL_NAME'), $lRaw, 'School Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_MILK.SCHL_DIST'), $lRaw, 'Distance (KM)', $sheet, '', '#F2DDDC', 'right', True, '', False, 12, '', 10);
		Controller::SetCell(config('excel.XL_MILK.PIN_CODE'), $lRaw, 'Pin Code', $sheet, '', '#F2DDDC', 'right', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_MILK.SBRB_NAME'), $lRaw, 'Subrb', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_MILK.CUT_OFF'), $lRaw, 'Cut Off Time', $sheet, '', '#F2DDDC', 'left', True, '', False, 11, '', 10);
	}

	public function SetExlData($sheet, $lRaw, $aMilkLst)
	{
		$i = 0;
		while(isset($aMilkLst) && count($aMilkLst) > 0 && $i<count($aMilkLst))
		{
			$lRaw = $lRaw + 1;
			$aAccShcl = $this->AssociateSchool->AccSchlLst($aMilkLst[$i]['lMilk_IdNo']);
			$nMrgCell = count($aAccShcl) > 1 ? count($aAccShcl) - 1 : '';
			Controller::SetCell(config('excel.XL_MILK.SR_NO'), $lRaw, $i+1, $sheet, config('excel.XL_MILK.SR_NO'), '', 'right', False, '', False, 8, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_MILK.ACC_ID'), $lRaw, $aMilkLst[$i]['sAcc_Id'], $sheet, config('excel.XL_MILK.ACC_ID'), '', 'left', False, '', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_MILK.BUSS_TYPE'), $lRaw, array_search($aMilkLst[$i]['nBuss_Type'], config('constant.BUSS_TYPE')), $sheet, config('excel.XL_MILK.BUSS_TYPE'), '', 'left', False, '', False, 20, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_MILK.BUSS_NAME'), $lRaw, $aMilkLst[$i]['sBuss_Name'], $sheet, config('excel.XL_MILK.BUSS_NAME'), '', 'left', False, '', False, 20, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_MILK.ABN_NO'), $lRaw, $aMilkLst[$i]['sAbn_No'], $sheet, config('excel.XL_MILK.ABN_NO'), '', 'right', False, '', False, 15, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_MILK.USER_NAME'), $lRaw, $aMilkLst[$i]['sFrst_Name']." ".$aMilkLst[$i]['sLst_Name'], $sheet, config('excel.XL_MILK.USER_NAME'), '', 'left', False, '', False, 18, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_MILK.PHONE_NO'), $lRaw, $aMilkLst[$i]['sCntry_Code']." ".$aMilkLst[$i]['sArea_Code']." ".$aMilkLst[$i]['sPhone_No'], $sheet, config('excel.XL_MILK.PHONE_NO'), '', 'left', False, '', False, 15, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_MILK.MOBILE_NO'), $lRaw, $aMilkLst[$i]['sCntry_Code']." ".$aMilkLst[$i]['sMobile_No'], $sheet, config('excel.XL_MILK.MOBILE_NO'), '', 'left', False, '', False, 15, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_MILK.EMAIL_ID'), $lRaw, $aMilkLst[$i]['sEmail_Id'], $sheet, config('excel.XL_MILK.EMAIL_ID'), '', 'left', False, '', False, 25, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_MILK.BUSS_ADDR'), $lRaw, $aMilkLst[$i]['sStrt_No'].", ".$aMilkLst[$i]['sStrt_Name'].", ".$aMilkLst[$i]['sSbrb_Name'].", ".$aMilkLst[$i]['sState_Name'].", ".$aMilkLst[$i]['sCntry_Name']." ".$aMilkLst[$i]['sPin_Code'], $sheet, config('excel.XL_MILK.BUSS_ADDR'), '', 'left', False, '', True, 40, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_MILK.STRP_ACC'), $lRaw, !empty($aMilkLst[$i]['sStrp_Acc_Id']) ? 'COMPLETED' : 'PENDING', $sheet, config('excel.XL_MILK.STRP_ACC'), '', 'center', False, '', False, 13, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_MILK.ACC_STATUS'), $lRaw, $aMilkLst[$i]['nBlk_UnBlk'] == config('constant.STATUS.BLOCK') ? 'BLOCK' : 'UNBLOCK', $sheet, config('excel.XL_MILK.ACC_STATUS'), '', 'center', False, '', False, 10, $nMrgCell, 10);

			$c = 0;
			while(isset($aAccShcl) && count($aAccShcl) > 0 && $c<count($aAccShcl))
			{
				Controller::SetCell(config('excel.XL_MILK.SCHL_TYPE'), $lRaw, array_search($aAccShcl[$c]['nSchl_Type'], config('constant.SCHL_TYPE')), $sheet, '', '', 'left', False, '', False, 15, '', 10);
				Controller::SetCell(config('excel.XL_MILK.SCHL_NAME'), $lRaw, $aAccShcl[$c]['sSchl_Name'], $sheet, '', '', 'left', False, '', False, 20, '', 10);
				Controller::SetCell(config('excel.XL_MILK.SCHL_DIST'), $lRaw, $aAccShcl[$c]['dDist_Km'], $sheet, '', '', 'right', False, '', False, 12, '', 10);
				Controller::SetCell(config('excel.XL_MILK.PIN_CODE'), $lRaw, $aAccShcl[$c]['sPin_Code'], $sheet, '', '', 'right', False, '', False, 8, '', 10);
				Controller::SetCell(config('excel.XL_MILK.SBRB_NAME'), $lRaw, $aAccShcl[$c]['sSbrb_Name'], $sheet, '', '', 'left', False, '', False, 15, '', 10);
				Controller::SetCell(config('excel.XL_MILK.CUT_OFF'), $lRaw, date('h:i A', strtotime($aAccShcl[$c]['sCut_Tm'])), $sheet, '', '', 'left', False, '', False, 11, '', 10);

				if($c==count($aAccShcl)) 
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