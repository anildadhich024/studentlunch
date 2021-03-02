<?php

namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SuperAdmin;
use App\Model\MilkBar;
use App\Model\School;
use App\Model\Country;
use App\Model\State;
use App\Model\SchoolContact;
use App\Model\SchoolSupplier;
use App\Model\RequestSchool;
use App\Model\AssociateSchool;
use Excel;
 use App\Model\Company; 

class SchoolController extends Controller
{
	public function __construct()
	{
		$this->Company 	= new Company; 
		$this->MilkBar 			= new MilkBar;
		$this->School 			= new School;
		$this->Country 			= new Country;
		$this->State 			= new State;
		$this->SchoolContact 	= new SchoolContact;
		$this->SchoolSupplier	= new SchoolSupplier;
		$this->RequestSchool	= new RequestSchool;
		$this->AssociateSchool	= new AssociateSchool;
		$this->middleware(SuperAdmin::class);
	}

	public function IndexPage(Request $request)
	{
		$aSchlDtl 		= array();
		$aStateLst 		= array();
		$aCntctDtl		= array();
		$aSchlCntctDtl 	= array();
		if(isset($request['lRecIdNo']) && !empty($request['lRecIdNo']))
		{
			$lSchlIdNo 	= base64_decode($request['lRecIdNo']);
			$aSchlDtl	= $this->School->SchlDtl($lSchlIdNo);
			if(empty($aSchlDtl['lSchl_IdNo']))  
			{
				return redirect('admin_panel/school/list')->with('Failed', 'unauthorized access...');
			} 
			else 
			{
				$aStateLst	= $this->State->FrntLst($aSchlDtl['lCntry_IdNo']);
				$aCntctDtl	= $this->SchoolContact->SchlCntctlLst($lSchlIdNo);
			}
		}
		$aCntryLst	= $this->Country->FrntLst();
		$sTitle 	= "Manage School";
    	$aData 		= compact('sTitle','aCntryLst','aSchlDtl','aCntctDtl','aStateLst');
        return view('admin_panel.manage_school',$aData);	
	}
 
	
	public function SaveCntrl(Request $request)
	{
		$lSchlIdNo 	= base64_decode($request['lSchlIdNo']);
		$rules = [
	        'lSchlType' 	=> 'required',
            'sSchlName' 	=> 'required|min:3|max:50|regex:/^[A-Za-z. ]+$/',
            'sEmailId' 		=> 'required|unique:mst_schl,sEmail_Id,'.$lSchlIdNo.',lSchl_IdNo|max:50|regex:^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^',
            'sPhoneNo' 		=> 'required',
            'sStrtNo'		=> 'required',
            'sStrtName'		=> 'required|min:5|max:50|regex:/^[a-zA-Z\s]*$/',
            'sSbrbName'		=> 'required|min:3|max:20|regex:/^[a-zA-Z\s]*$/',
            'lCntryIdNo'	=> 'required',
            'lStateIdNo'	=> 'required',
            'sPinCode'		=> 'required|digits:4',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));
	    
	    try
	    {
		    $aHdArr 	= $this->HdArr($request);
		    \DB::beginTransaction();
			
		    	if($lSchlIdNo == 0)
		    	{ 
		    	    
					$exist=$this->School->SchoolExist($request['sSchlName'],$request['lSchlType']); 
		    		if(empty($exist)){
						$this->InsrtArr($aHdArr);
						$lSchlIdNo	= $this->School->InsrtRecrd($aHdArr); 
						Controller::writeFile('School Created');
						$sMessage	= "School created successfully...";
					}else{
						return redirect('admin_panel/school/list')->with('Failed', 'School Name Already Exist With Same School Type');
					}

		    	}
		    	else
		    	{
					$exist=$this->School->SchoolExistWhere($request['sSchlName'],$request['lSchlType'],$lSchlIdNo);
		    		if(empty($exist)){
						$sDelArr 	= $this->DelArr(); 
						$this->SchoolContact->DelSchl($sDelArr, $lSchlIdNo);
						$nRow	= $this->School->UpDtRecrd($aHdArr, $lSchlIdNo);
						Controller::writeFile('School Updated');
						$sMessage	= "Account update successfully...";
					}else{
						return redirect('admin_panel/school/list')->with('Failed', 'School Name Already Exist With Same School Type');
					}
		    		
		    	}
				if(!empty($lSchlIdNo) && $lSchlIdNo > 0)
				{
					$i=1;
					for($i==1;$i<=$request['nTtlRec'];$i++)
					{
						
						if(!empty($request['nCntctRole'.$i]) && !empty($request['nCntctTitle'.$i]) && !empty($request['sFrstName'.$i]) && !empty($request['sLstName'.$i]) && !empty($request['sEmailId'.$i]))
						{ 
							$aCntctArr = $this->SchlCntctArr($lSchlIdNo, $request['nCntctRole'.$i], $request['nCntctTitle'.$i], $request['sFrstName'.$i], $request['sLstName'.$i], $request['sPhoneNo'.$i], $request['sMobileNo'.$i], $request['sEmailId'.$i]);
							if(isset($request['lSchlCntctIdNo'.$i]) && !empty($request['lSchlCntctIdNo'.$i]))
		    				{
								Controller::writeFile('School Contact Details Updated');
		    					$this->SchoolContact->UpDtRecrd($aCntctArr, $request['lSchlCntctIdNo'.$i]);
		    				}
		    				else
		    				{
								Controller::writeFile('School Contact Details Created');
		    					$this->SchoolContact->InsrtRecrd($aCntctArr);
		    				}
						}
					}
				}
			\DB::commit();
		    return redirect('admin_panel/school/list')->with('Success', $sMessage);
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect('admin_panel/school/list')->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function HdArr($request)
	{
		$aConArr = array(
			'lAcc_Id' 		=> strtoupper($request['sSchlName'][0].$request['sSchlName'][1].'-'.rand(1111,9999)),
			'sSchl_Name' 	=> $request['sSchlName'],
            'lSchl_Type' 	=> $request['lSchlType'],
            'sEmail_Id' 	=> $request['sEmailId'],
            'sCntry_Code'	=> $request['sCntryCode'],
            'sArea_Code'	=> $request['sAreaCode'],
            'sPhone_No'		=> $request['sPhoneNo'],
            'sStrt_No'		=> $request['sStrtNo'],
            'sStrt_Name'	=> $request['sStrtName'],
            'sSbrb_Name'	=> $request['sSbrbName'],
            'lCntry_IdNo'	=> $request['lCntryIdNo'],
            'lState_IdNo'	=> $request['lStateIdNo'],
            'sPin_Code'		=> $request['sPinCode'],
		);
		return $aConArr;
	}

	public function InsrtArr(&$aHdArr)
	{
		$aHdArr['nBlk_UnBlk']	= config('constant.STATUS.UNBLOCK');
		$aHdArr['nDel_Status']	= config('constant.DEL_STATUS.UNDELETED');
	}

	public function SchlCntctArr($lSchlIdNo, $nCntctRole, $nCntctTitle, $sFrstName, $sLstName, $sPhoneNo, $sMobileNo, $sEmailId)
	{
		$aConArr = array(
			"lSchl_IdNo"	=> $lSchlIdNo,
			"nCntct_Role"	=> $nCntctRole,
			"nCntct_Title"	=> $nCntctTitle,
			"sFrst_Name"	=> $sFrstName,
			"sLst_Name"		=> $sLstName,
			"sPhone_No"		=> $sPhoneNo,
			"sMobile_No"	=> $sMobileNo,
			"sEmail_Id"		=> $sEmailId,
			'nDel_Status'	=> config('constant.DEL_STATUS.UNDELETED'),
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

	public function ReqList(Request $request)
	{
		$aReqSchlLst = $this->RequestSchool->ReqSchlLst($request['sSchlName'], $request['nSchlType']);
		$sTitle 	= "Manage Requested School List";
    	$aData 		= compact('sTitle','aReqSchlLst','request');
        return view('admin_panel.school_req_list',$aData);	
	}

	public function ChangeStatus(Request $request){
		$lSchlReqIdNo 	= base64_decode($request['lRecIdNo']);
		$aDelArr = array(
			"nReq_Status"	=> config('constant.REQ_STATUS.Listed'),
		);
		$aReqSchlLst = $this->RequestSchool->ChngRecrdStatus($aDelArr,$lSchlReqIdNo); 
		 if($aReqSchlLst){
			 Controller::writeFile('Change Requested School Status');
			 return redirect()->back()->with('Success', 'School status change successfully...');
		 }
		 else
		{
			return redirect()->back()->with('Failed', 'unauthorized access...');
		}
	}

	public function ListPage(Request $request)
	{
		$aSchlLst = $this->School->SchlLst($request['sSchlName'], $request['sMobileNo']);
		$sTitle 	= "Manage School List";
    	$aData 		= compact('sTitle','aSchlLst','request');
        return view('admin_panel.school_list',$aData);	
	}

	public function DetailPage(Request $request)
	{
		if(isset($request['lRecIdNo']) || !empty($request['lRecIdNo']))
		{
			$lSchlIdNo = base64_decode($request['lRecIdNo']);
			$aSchlDtl = $this->School->SchlDtl($lSchlIdNo);
			if(empty($aSchlDtl))
			{
				return redirect('admin_panel/manage_school')->with('Failed', 'parents detail not found...');
			}
			else
			{
				
				$aSchlCntctLst	= $this->SchoolContact->SchlCntctlLst($lSchlIdNo);
				$oAssSchl		= $this->AssociateSchool->SchlMlkLst($lSchlIdNo);
				Controller::writeFile('View School Details');
				$sTitle 	= $aSchlDtl['sSchl_Name']." Details";
		    	$aData 		= compact('sTitle','aSchlDtl','aSchlCntctLst','oAssSchl');
		        return view('admin_panel.school_detail',$aData);				
			}
		}
		else
		{
			return redirect('admin_panel/parent/list')->with('Failed', 'unauthorized access...');
		}
	}
	
	public function ExprtRcrd(Request $request)
	{
		$sSchlName = $request['sSchlName'];
		$sMobileNo = $request['sMobileNo'];
		$aSchlLst	= $this->School->ExlRcrd($sSchlName, $sMobileNo);
		if(count($aSchlLst) > 0)
		{
			$FileName = 'School_'.date('Ymd').'_'.date('His');
	        Excel::create($FileName, function($excel) use ($aSchlLst) {
	            $excel->sheet('Sheet1', function($sheet)  use ($aSchlLst) {
	                $this->SetExlHeader($sheet, $lRaw);
	                $this->SetExlData($sheet, $lRaw, $aSchlLst);
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
		Controller::SetCell(config('excel.XL_SCHL.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'right', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.ACC_ID'), $lRaw, 'Account Id', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.SCHL_TYPE'), $lRaw, 'School Type', $sheet, '', '#F2DDDC', 'left', True, '', False, 17, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.SCHL_NAME'), $lRaw, 'School Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.PHONE_NO'), $lRaw, 'Phone No', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.MOBILE_NO'), $lRaw, 'Mobile No', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.EMAIL_ID'), $lRaw, 'Email Address', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.SCHL_ADDR'), $lRaw, 'Address', $sheet, '', '#F2DDDC', 'left', True, '', True, 40, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.ACC_STATUS'), $lRaw, 'Status', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.CNCT_ROLE'), $lRaw, 'Role', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.CNCT_NAME'), $lRaw, 'Contact Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.CNCT_MOBILE'), $lRaw, 'Mobile No', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.CNCT_PHONE'), $lRaw, 'Phone No', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_SCHL.CNCT_EMAIL'), $lRaw, 'Email Address', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
	}

	public function SetExlData($sheet, $lRaw, $aSchlLst)
	{
		$i = 0;
		while(isset($aSchlLst) && count($aSchlLst) > 0 && $i<count($aSchlLst))
		{
			$lRaw = $lRaw + 1;
			$aCnctLst = $this->SchoolContact->SchlCntctlLst($aSchlLst[$i]['lSchl_IdNo']);
			$nMrgCell = count($aCnctLst) > 1 ? count($aCnctLst) - 1 : '';
			Controller::SetCell(config('excel.XL_SCHL.SR_NO'), $lRaw, $i+1, $sheet, config('excel.XL_SCHL.SR_NO'), '', 'right', False, '', False, 8, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_SCHL.ACC_ID'), $lRaw, $aSchlLst[$i]['lAcc_Id'], $sheet, config('excel.XL_SCHL.ACC_ID'), '', 'left', False, '', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_SCHL.SCHL_TYPE'), $lRaw, array_search($aSchlLst[$i]['lSchl_Type'], config('constant.SCHL_TYPE')), $sheet, config('excel.XL_SCHL.SCHL_TYPE'), '', 'left', False, '', False, 17, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_SCHL.SCHL_NAME'), $lRaw, $aSchlLst[$i]['sSchl_Name'], $sheet, config('excel.XL_SCHL.SCHL_NAME'), '', 'left', False, '', False, 25, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_SCHL.PHONE_NO'), $lRaw, $aSchlLst[$i]['sPhone_No'], $sheet, config('excel.XL_SCHL.PHONE_NO'), '', 'left', False, '', False, 15, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_SCHL.MOBILE_NO'), $lRaw, $aSchlLst[$i]['sMobile_No'], $sheet, config('excel.XL_SCHL.MOBILE_NO'), '', 'left', False, '', False, 15, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_SCHL.EMAIL_ID'), $lRaw, $aSchlLst[$i]['sEmail_Id'], $sheet, config('excel.XL_SCHL.EMAIL_ID'), '', 'left', False, '', False, 25, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_SCHL.SCHL_ADDR'), $lRaw, $aSchlLst[$i]['sStrt_No'].", ".$aSchlLst[$i]['sStrt_Name'].", ".$aSchlLst[$i]['sSbrb_Name'].", ".$aSchlLst[$i]['sState_Name']." ".$aSchlLst[$i]['sCntry_Name']." ".$aSchlLst[$i]['sPin_Code'], $sheet, config('excel.XL_SCHL.SCHL_ADDR'), '', 'left', False, '', True, 40, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_SCHL.ACC_STATUS'), $lRaw, $aSchlLst[$i]['nBlk_UnBlk'] == config('constant.STATUS.BLOCK') ? 'BLOCK' : 'UNBLOCK', $sheet, config('excel.XL_SCHL.ACC_STATUS'), '', 'center', False, '', False, 10, $nMrgCell, 10);

			$c = 0;
			while(isset($aCnctLst) && count($aCnctLst) > 0 && $c<count($aCnctLst))
			{
				Controller::SetCell(config('excel.XL_SCHL.CNCT_ROLE'), $lRaw, array_search($aCnctLst[$c]['nCntct_Role'], config('constant.SCHL_ROLE')), $sheet, '', '', 'left', False, '', False, 15, '', 10);
				Controller::SetCell(config('excel.XL_SCHL.CNCT_NAME'), $lRaw, array_search($aCnctLst[$c]['nCntct_Title'], config('constant.TITLE'))." ".$aCnctLst[$c]['sFrst_Name']." ".$aCnctLst[$c]['sLst_Name'], $sheet, '', '', 'left', False, '', False, 25, '', 10);
				Controller::SetCell(config('excel.XL_SCHL.CNCT_MOBILE'), $lRaw, $aCnctLst[$c]['sMobile_No'], $sheet, '', '', 'left', False, '', False, 15, '', 10);
				Controller::SetCell(config('excel.XL_SCHL.CNCT_PHONE'), $lRaw, $aCnctLst[$c]['sPhone_No'], $sheet, '', '', 'left', False, '', False, 15, '', 10);
				Controller::SetCell(config('excel.XL_SCHL.CNCT_EMAIL'), $lRaw, $aCnctLst[$c]['sEmail_Id'], $sheet, '', '', 'left', False, '', False, 25, '', 10);

				if($c==count($aCnctLst)) 
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