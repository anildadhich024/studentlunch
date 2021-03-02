<?php

namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SuperAdmin;
use App\Model\Parents;
use App\Model\Child;
use Excel;
 use App\Model\Company; 

class ParentController extends Controller
{
	public function __construct()
	{
		$this->Company 	= new Company; 
		$this->Parents 	= new Parents;
		$this->Child 	= new Child;
		$this->middleware(SuperAdmin::class);
	}

	public function ListPage(Request $request)
	{
		$aPrntsLst = $this->Parents->PrntsLst($request['sPrntName'], $request['sMobileNo']);
		$sTitle 	= "Manage Parents";
    	$aData 		= compact('sTitle','aPrntsLst','request');
        return view('admin_panel.parent_list',$aData);	
	}

	public function DetailPage(Request $request)
	{
		if(isset($request['lRecIdNo']) || !empty($request['lRecIdNo']))
		{
			$lPrntIdNo = base64_decode($request['lRecIdNo']);
			$aPrntsDtl = $this->Parents->PrntsDtl($lPrntIdNo);
			if(empty($aPrntsDtl))
			{
				return redirect('admin_panel/manage_parent')->with('Failed', 'parents detail not found');
			}
			else
			{
				$aChldLst	= $this->Child->ChldLst($lPrntIdNo);
				$sTitle 	= $aPrntsDtl['sFrst_Name']." ".$aPrntsDtl['sLst_Name']." Details";
				Controller::writeFile('View Parent Details');
		    	$aData 		= compact('sTitle','aPrntsDtl','aChldLst');
		        return view('admin_panel.parent_detail',$aData);				
			}
		}
		else
		{
			return redirect('admin_panel/manage_parent')->with('Failed', 'unauthorized access');
		}
	}
	
	public function ExprtRcrd(Request $request)
	{
		$sPrntName = $request['sPrntName'];
		$sMobileNo = $request['sMobileNo'];
		$aPrntLst	= $this->Parents->ExlRcrd($sPrntName, $sMobileNo);
		if(count($aPrntLst) > 0)
		{
			$FileName = 'Parent_'.date('Ymd').'_'.date('His');
	        Excel::create($FileName, function($excel) use ($aPrntLst) {
	            $excel->sheet('Sheet1', function($sheet)  use ($aPrntLst) {
	                $this->SetExlHeader($sheet, $lRaw);
	                $this->SetExlData($sheet, $lRaw, $aPrntLst);
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
		Controller::SetCell(config('excel.XL_PRNT.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'right', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_PRNT.ACC_ID'), $lRaw, 'Account Id', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_PRNT.RLTN_NAME'), $lRaw, 'Relation', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_PRNT.PRNT_NAME'), $lRaw, 'Patent Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_PRNT.MOBILE_NO'), $lRaw, 'Mobile No', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_PRNT.EMAIL_ID'), $lRaw, 'Email Address', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_PRNT.PRNT_ADDR'), $lRaw, 'Address', $sheet, '', '#F2DDDC', 'left', True, '', True, 40, '', 10);
		Controller::SetCell(config('excel.XL_PRNT.ACC_STATUS'), $lRaw, 'Status', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_PRNT.SCHL_TYPE'), $lRaw, 'School Type', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_PRNT.SCHL_NAME'), $lRaw, 'School Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_PRNT.CHLD_NAME'), $lRaw, 'Child Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_PRNT.CLS_NAME'), $lRaw, 'Class', $sheet, '', '#F2DDDC', 'left', True, '', False, 8, '', 10);
	}

	public function SetExlData($sheet, $lRaw, $aPrntLst)
	{
		$i = 0;
		while(isset($aPrntLst) && count($aPrntLst) > 0 && $i<count($aPrntLst))
		{
			$lRaw = $lRaw + 1;
			$aChldLst = $this->Child->ChldLst($aPrntLst[$i]['lPrnt_IdNo']);
			$nMrgCell = count($aChldLst) > 1 ? count($aChldLst) - 1 : '';
			Controller::SetCell(config('excel.XL_PRNT.SR_NO'), $lRaw, $i+1, $sheet, config('excel.XL_PRNT.SR_NO'), '', 'right', False, '', False, 8, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_PRNT.ACC_ID'), $lRaw, $aPrntLst[$i]['sAcc_Id'], $sheet, config('excel.XL_PRNT.ACC_ID'), '', 'left', False, '', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_PRNT.RLTN_NAME'), $lRaw, array_search($aPrntLst[$i]['lRltn_IdNo'], config('constant.RLTN_IDNO')), $sheet, config('excel.XL_PRNT.RLTN_NAME'), '', 'left', False, '', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_PRNT.PRNT_NAME'), $lRaw, $aPrntLst[$i]['sFrst_Name']." ".$aPrntLst[$i]['sLst_Name'], $sheet, config('excel.XL_PRNT.PRNT_NAME'), '', 'left', False, '', False, 20, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_PRNT.MOBILE_NO'), $lRaw, $aPrntLst[$i]['sCntry_Code']." ".$aPrntLst[$i]['sMobile_No'], $sheet, config('excel.XL_PRNT.MOBILE_NO'), '', 'left', False, '', False, 15, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_PRNT.EMAIL_ID'), $lRaw, $aPrntLst[$i]['sEmail_Id'], $sheet, config('excel.XL_PRNT.EMAIL_ID'), '', 'left', False, '', False, 25, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_PRNT.PRNT_ADDR'), $lRaw, $aPrntLst[$i]['sSbrb_Name'].", ".$aPrntLst[$i]['sState_Name'].", ".$aPrntLst[$i]['sCntry_Name']." ".$aPrntLst[$i]['sPin_Code'], $sheet, config('excel.XL_PRNT.PRNT_ADDR'), '', 'left', False, '', False, 40, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_PRNT.ACC_STATUS'), $lRaw, $aPrntLst[$i]['nBlk_UnBlk'] == config('constant.STATUS.BLOCK') ? 'BLOCK' : 'UNBLOCK', $sheet, config('excel.XL_PRNT.ACC_STATUS'), '', 'center', False, '', False, 10, $nMrgCell, 10);

			$c = 0;
			while(isset($aChldLst) && count($aChldLst) > 0 && $c<count($aChldLst))
			{
				Controller::SetCell(config('excel.XL_PRNT.SCHL_TYPE'), $lRaw, array_search($aChldLst[$c]['nSchl_Type'], config('constant.SCHL_TYPE')), $sheet, '', '', 'left', False, '', False, 15, '', 10);
				Controller::SetCell(config('excel.XL_PRNT.SCHL_NAME'), $lRaw, $aChldLst[$c]['sSchl_Name'], $sheet, '', '', 'left', False, '', False, 20, '', 10);
				Controller::SetCell(config('excel.XL_PRNT.CHLD_NAME'), $lRaw, $aChldLst[$c]['sFrst_Name']." ".$aChldLst[$c]['sLst_Name'], $sheet, '', '', 'left', False, '', False, 20, '', 10);
				Controller::SetCell(config('excel.XL_PRNT.CLS_NAME'), $lRaw, $aChldLst[$c]['sCls_Name'], $sheet, '', '', 'left', False, '', False, 8, '', 10);

				$c++;
				$lRaw = $lRaw + 1;
				if($c==count($aChldLst)) 
				{
	        		break;	
	        		$lRaw = $lRaw - 1;
	        	}
        			
			}
			$i++;
		}
	}
}
?>