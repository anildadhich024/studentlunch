<?php

namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SuperAdmin;
use App\Model\Teacher;
use App\Model\TeacherSchool;
use Excel;
use App\Model\Company; 

class TeacherController extends Controller
{
	public function __construct()
	{
		$this->Company 	= new Company;
		$this->Teacher 	= new Teacher;
		$this->TeacherSchool 	= new TeacherSchool;
		$this->middleware(SuperAdmin::class);
	}

	public function ListPage(Request $request)
	{
		$aTchrLst 	= $this->Teacher->TchrLst($request['sTchrName'], $request['sMobileNo']);
		$sTitle 	= "Manage Teacher";
    	$aData 		= compact('sTitle','aTchrLst','request');
        return view('admin_panel.teacher_list',$aData);	
	}

	public function DetailPage(Request $request)
	{
		if(isset($request['lRecIdNo']) || !empty($request['lRecIdNo']))
		{
			$lTchrIdNo = base64_decode($request['lRecIdNo']);
			$aTchrDtl = $this->Teacher->TchrDtl($lTchrIdNo);
			if(empty($aTchrDtl))
			{
				return redirect('admin_panel/manage_parent')->with('Failed', 'Teacher detail not found');
			}
			else
			{
				$aSchlLst	= $this->TeacherSchool->SchlLst($lTchrIdNo);  
				$sTitle 	= $aTchrDtl['sFrst_Name']." ".$aTchrDtl['sLst_Name']." Details";
				Controller::writeFile('View Teacher Details');
		    	$aData 		= compact('sTitle','aTchrDtl','aSchlLst');
		        return view('admin_panel.teacher_detail',$aData);				
			}
		}
		else
		{
			return redirect('admin_panel/teacher/list')->with('Failed', 'unauthorized access');
		}
	}
	
	public function ExprtRcrd(Request $request)
	{
		$sTchrName = $request['sTchrName'];
		$sMobileNo = $request['sMobileNo'];
		$aTchrLst	= $this->Teacher->ExlRcrd($sTchrName, $sMobileNo);
		if(count($aTchrLst) > 0)
		{
			$FileName = 'Teacher_'.date('Ymd').'_'.date('His');
	        Excel::create($FileName, function($excel) use ($aTchrLst) {
	            $excel->sheet('Sheet1', function($sheet)  use ($aTchrLst) {
	                $this->SetExlHeader($sheet, $lRaw);
	                $this->SetExlData($sheet, $lRaw, $aTchrLst);
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
		Controller::SetCell(config('excel.XL_TCHR.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'right', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_TCHR.ACC_ID'), $lRaw, 'Account Id', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_TCHR.TCHR_NAME'), $lRaw, 'Teacher Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_TCHR.MOBILE_NO'), $lRaw, 'Mobile No', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_TCHR.EMAIL_ID'), $lRaw, 'Email Address', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_TCHR.TCHR_ADDR'), $lRaw, 'Address', $sheet, '', '#F2DDDC', 'left', True, '', True, 40, '', 10);
		Controller::SetCell(config('excel.XL_TCHR.ACC_STATUS'), $lRaw, 'Status', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_TCHR.SCHL_TYPE'), $lRaw, 'School Type', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_TCHR.SCHL_NAME'), $lRaw, 'School Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_TCHR.SBRB_NAME'), $lRaw, 'Subrub Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_TCHR.PIN_CODE'), $lRaw, 'Pin Code', $sheet, '', '#F2DDDC', 'left', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_TCHR.ROL_NAME'), $lRaw, 'Role', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
	}

	public function SetExlData($sheet, $lRaw, $aTchrLst)
	{
		$i = 0;
		while(isset($aTchrLst) && count($aTchrLst) > 0 && $i<count($aTchrLst))
		{
			$lRaw = $lRaw + 1;
			$aSchlLst = $this->TeacherSchool->SchlLst($aTchrLst[$i]['lTchr_IdNo']);
			$nMrgCell = count($aSchlLst) > 1 ? count($aSchlLst) - 1 : '';
			Controller::SetCell(config('excel.XL_TCHR.SR_NO'), $lRaw, $i+1, $sheet, config('excel.XL_TCHR.SR_NO'), '', 'right', False, '', False, 8, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR.ACC_ID'), $lRaw, $aTchrLst[$i]['sAcc_Id'], $sheet, config('excel.XL_TCHR.ACC_ID'), '', 'left', False, '', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR.TCHR_NAME'), $lRaw, $aTchrLst[$i]['sFrst_Name']." ".$aTchrLst[$i]['sLst_Name'], $sheet, config('excel.XL_TCHR.TCHR_NAME'), '', 'left', False, '', False, 20, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR.MOBILE_NO'), $lRaw, $aTchrLst[$i]['sCntry_Code']." ".$aTchrLst[$i]['sMobile_No'], $sheet, config('excel.XL_TCHR.MOBILE_NO'), '', 'left', False, '', False, 15, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR.EMAIL_ID'), $lRaw, $aTchrLst[$i]['sEmail_Id'], $sheet, config('excel.XL_TCHR.EMAIL_ID'), '', 'left', False, '', False, 25, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR.TCHR_ADDR'), $lRaw, $aTchrLst[$i]['sSbrb_Name'].", ".$aTchrLst[$i]['sState_Name'].", ".$aTchrLst[$i]['sCntry_Name']." ".$aTchrLst[$i]['sPin_Code'], $sheet, config('excel.XL_TCHR.TCHR_ADDR'), '', 'left', False, '', False, 40, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_TCHR.ACC_STATUS'), $lRaw, $aTchrLst[$i]['nBlk_UnBlk'] == config('constant.STATUS.BLOCK') ? 'BLOCK' : 'UNBLOCK', $sheet, config('excel.XL_TCHR.ACC_STATUS'), '', 'center', False, '', False, 10, $nMrgCell, 10);

			$c = 0;
			while(isset($aSchlLst) && count($aSchlLst) > 0 && $c<count($aSchlLst))
			{
				Controller::SetCell(config('excel.XL_TCHR.SCHL_TYPE'), $lRaw, array_search($aSchlLst[$c]['nSchl_Type'], config('constant.SCHL_TYPE')), $sheet, '', '', 'left', False, '', False, 15, '', 10);
				Controller::SetCell(config('excel.XL_TCHR.SCHL_NAME'), $lRaw, $aSchlLst[$c]['sSchl_Name'], $sheet, '', '', 'left', False, '', False, 20, '', 10);
				Controller::SetCell(config('excel.XL_TCHR.SBRB_NAME'), $lRaw, $aSchlLst[$c]['sSbrb_Name'], $sheet, '', '', 'left', False, '', False, 20, '', 10);
				Controller::SetCell(config('excel.XL_TCHR.PIN_CODE'), $lRaw, $aSchlLst[$c]['sPin_Code'], $sheet, '', '', 'right', False, '', False, 8, '', 10);
				Controller::SetCell(config('excel.XL_TCHR.ROL_NAME'), $lRaw, array_search($aSchlLst[$c]['nRole_Type'], config('constant.SCHL_ROLE')), $sheet, '', '', 'left', False, '', False, 15, '', 10);

				if($c==count($aSchlLst)) 
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