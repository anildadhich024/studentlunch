<?php
namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SuperAdmin;
use App\Model\Company; 
use App\Model\Wallet;
use App\Model\MilkBar;
use App\Model\OrderHd;
use Excel;

class CommissionController extends Controller
{
	public function __construct()
	{
		$this->Company 	= new Company; 
		$this->Wallet 	= new Wallet;
		$this->MilkBar 	= new MilkBar;
		$this->OrderHd 	= new OrderHd;
		$this->middleware(SuperAdmin::class);
	}

	public function SmryPage(Request $request)
	{
		$sFrmDate  		= !empty($request['sFrmDate']) ? $request['sFrmDate']." 00:00:00" : '';
		$sToDate  		= !empty($request['sToDate']) ? $request['sToDate']." 23:59:59" : '';
		$lMilkIdNo  	= $request['lMilkIdNo'];
		$aMilkLst		= $this->MilkBar->FltrMilkLst();
		$oComLst		= $this->OrderHd->CommSmry($sFrmDate, $sToDate, $lMilkIdNo);
		$sTitle 		= "Manage Commission Summary";
    	$aData 			= compact('sTitle','oComLst','aMilkLst','request','sFrmDate','sToDate');
        return view('admin_panel.commission_summary',$aData);	
	}

	public function ListPage(Request $request)
	{
		Controller::writeFile('View Commission Details');
		$sFrmDate  		= !empty($request['sFrmDate']) ? $request['sFrmDate']." 00:00:00" : '';
		$sToDate  		= !empty($request['sToDate']) ? $request['sToDate']." 23:59:59" : '';
		$lMilkIdNo		= $request['lMilkIdNo'];
		$aBussName		= $this->MilkBar->BussName($lMilkIdNo);
		$oComLst		= $this->OrderHd->CommLst($sFrmDate, $sToDate, $lMilkIdNo);
		$sTitle 		= "Manage Commission List";
    	$aData 			= compact('sTitle','oComLst','request','aBussName');
        return view('admin_panel.commission_list',$aData);	
	}
	
	public function ExprtRcrd(Request $request)
	{
		$sFrmDate  		= !empty($request['sFrmDate']) ? $request['sFrmDate']." 00:00:00" : '';
		$sToDate  		= !empty($request['sToDate']) ? $request['sToDate']." 23:59:59" : '';
		$lMilkIdNo  	= $request['lMilkIdNo'];
		$aComLst		= $this->OrderHd->ExprtCommSmry($sFrmDate, $sToDate, $lMilkIdNo);
		if(count($aComLst) > 0)
		{
			$FileName = 'Manage_Commission_'.date('Ymd').'_'.date('His');
	        Excel::create($FileName, function($excel) use ($aComLst, $sFrmDate, $sToDate) {
	            $excel->sheet('Sheet1', function($sheet)  use ($aComLst, $sFrmDate, $sToDate) {
	                $this->SetExlHeader($sheet, $lRaw);
	                $this->SetExlData($sheet, $lRaw, $aComLst, $sFrmDate, $sToDate);
	                $this->SetExlTotlSmry($sheet, $lRaw);
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
		Controller::SetCell(config('excel.XL_COMM_CMRY.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'left', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_COMM_CMRY.MILK_NAME'), $lRaw, 'Service Provider Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_COMM_CMRY.ORD_CNT'), $lRaw, 'Total Order', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_COMM_CMRY.SALE_AMO'), $lRaw, 'Sale Amount', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_COMM_CMRY.CRDT_AMO'), $lRaw, 'Credit Applied', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_COMM_CMRY.PAY_AMO'), $lRaw, 'Processed Payment', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_COMM_CMRY.COMM_AMO'), $lRaw, 'Commission', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
	}

	public function SetExlData($sheet, &$lRaw, $aComLst, $sFrmDate, $sToDate)
	{
		$i = 0;
		while(isset($aComLst) && count($aComLst) > 0 && $i<count($aComLst))
		{
			$lRaw = $lRaw + 1;
			$oGetWlt = $this->Wallet->WltTtl($aComLst[$i]['lMilk_IdNo'], $sFrmDate, $sToDate);
			Controller::SetCell(config('excel.XL_COMM_CMRY.SR_NO'), $lRaw, $i+1, $sheet, '', '', 'right', False, '', False, 8, '', 10);
			Controller::SetCell(config('excel.XL_COMM_CMRY.MILK_NAME'), $lRaw, $aComLst[$i]['sBuss_Name'], $sheet, '', '', 'left', False, '', True, 30, '', 10);
			Controller::SetCell(config('excel.XL_COMM_CMRY.ORD_CNT'), $lRaw, $aComLst[$i]['nTtlOrd'], $sheet, '', '', 'right', False, '', False, 11, '', 10);
			Controller::SetCell(config('excel.XL_COMM_CMRY.SALE_AMO'), $lRaw, $aComLst[$i]['sTtlAmt'], $sheet, '', '', 'right', False, '#,##0.00', False, 12, '', 10);
			Controller::SetCell(config('excel.XL_COMM_CMRY.CRDT_AMO'), $lRaw, empty($oGetWlt->{'nTtlWlt'}) ? 0 : $oGetWlt->{'nTtlWlt'}, $sheet, '', '', 'right', False, '#,##0.00', False, 13, '', 10);
			Controller::SetCell(config('excel.XL_COMM_CMRY.PAY_AMO'), $lRaw, '=('.Controller::GetColName(config('excel.XL_COMM_CMRY.SALE_AMO')).$lRaw.'-'.Controller::GetColName(config('excel.XL_COMM_CMRY.CRDT_AMO')).$lRaw.')', $sheet, '', '', 'right', False, '#,##0.00', False, 17, '', 10);
			Controller::SetCell(config('excel.XL_COMM_CMRY.COMM_AMO'), $lRaw, empty($aComLst[$i]['sTtlCom']) ? 0 : $aComLst[$i]['sTtlCom'], $sheet, '', '', 'right', False, '#,##0.00', False, 12, '', 10);
			$i++;
		}
	}

	public function SetExlTotlSmry($sheet, $lRaw)
	{
		$lLstRaw = $lRaw;
		$lRaw = $lRaw + 1;
		Controller::SetCell(config('excel.XL_COMM_CMRY.SR_NO'), $lRaw, 'Total', $sheet, config('excel.XL_COMM_CMRY.MILK_NAME'), '#F2DDDC', 'center', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_COMM_CMRY.ORD_CNT'), $lRaw, '=SUM('.Controller::GetColName(config('excel.XL_COMM_CMRY.ORD_CNT')).'2:'.Controller::GetColName(config('excel.XL_COMM_CMRY.ORD_CNT')).$lLstRaw.')', $sheet, '', '#F2DDDC', 'right', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_COMM_CMRY.SALE_AMO'), $lRaw, '=SUM('.Controller::GetColName(config('excel.XL_COMM_CMRY.SALE_AMO')).'2:'.Controller::GetColName(config('excel.XL_COMM_CMRY.SALE_AMO')).$lLstRaw.')', $sheet, '', '#F2DDDC', 'right', True, '$ #,##0.00', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_COMM_CMRY.CRDT_AMO'), $lRaw, '=SUM('.Controller::GetColName(config('excel.XL_COMM_CMRY.CRDT_AMO')).'2:'.Controller::GetColName(config('excel.XL_COMM_CMRY.CRDT_AMO')).$lLstRaw.')', $sheet, '', '#F2DDDC', 'right', True, '$ #,##0.00', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_COMM_CMRY.PAY_AMO'), $lRaw, '=SUM('.Controller::GetColName(config('excel.XL_COMM_CMRY.PAY_AMO')).'2:'.Controller::GetColName(config('excel.XL_COMM_CMRY.PAY_AMO')).$lLstRaw.')', $sheet, '', '#F2DDDC', 'right', True, '$ #,##0.00', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_COMM_CMRY.COMM_AMO'), $lRaw, '=SUM('.Controller::GetColName(config('excel.XL_COMM_CMRY.COMM_AMO')).'2:'.Controller::GetColName(config('excel.XL_COMM_CMRY.COMM_AMO')).$lLstRaw.')', $sheet, '', '#F2DDDC', 'right', True, '$ #,##0.00', False, 15, '', 10);
	}

	public function ExprtLst(Request $request)
	{
		$sFrmDate  		= !empty($request['sFrmDate']) ? $request['sFrmDate']." 00:00:00" : '';
		$sToDate  		= !empty($request['sToDate']) ? $request['sToDate']." 23:59:59" : '';
		$lMilkIdNo  	= $request['lMilkIdNo'];
		$aBussName		= $this->MilkBar->BussName($lMilkIdNo);
		$aComLst		= $this->OrderHd->ExprtCommLst($sFrmDate, $sToDate, $lMilkIdNo);
		if(count($aComLst) > 0)
		{
			$FileName = 'Manage_Commission_'.date('Ymd').'_'.date('His');
	        Excel::create($FileName, function($excel) use ($aComLst, $sFrmDate, $sToDate, $aBussName) {
	            $excel->sheet('Sheet1', function($sheet)  use ($aComLst, $sFrmDate, $sToDate, $aBussName) {
	                $this->SetExlHeaderList($sheet, $lRaw, $sFrmDate, $sToDate, $aBussName);
	                $this->SetExlDataList($sheet, $lRaw, $aComLst, $sFrmDate, $sToDate);
	                $this->SetExlTotl($sheet, $lRaw);
	            });
	        })->download('xlsx');
	    }
	    else
	    {
        	return redirect()->back()->with('Success', 'Record not found...');
	    }
	}

	public function SetExlHeaderList($sheet, &$lRaw, $sFrmDate, $sToDate, $aBussName)
	{
		$lRaw = 1;
		$sHedding = $aBussName['sBuss_Name']." Sale Report";
		$sHedding .= !empty($sFrmDate) && !empty($sToDate) ? ' '.date('d M, Y', strtotime($sFrmDate)).' - '.date('d M, Y', strtotime($sToDate)) : '';
		Controller::SetCell(config('excel.XL_ORD_COMM.SR_NO'), $lRaw, $sHedding, $sheet, config('excel.XL_ORD_COMM.COMM_AMO'), '', 'center', True, '', False, 8, '', 13);

		$lRaw = $lRaw + 1;

		Controller::SetCell(config('excel.XL_ORD_COMM.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'right', False, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_ORD_COMM.TRAN_DATE'), $lRaw, 'Transaction Date', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_ORD_COMM.ORD_NO'), $lRaw, 'Order No', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_ORD_COMM.STDNT_NAME'), $lRaw, 'User Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_ORD_COMM.ORD_AMO'), $lRaw, 'Sale Amount', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_ORD_COMM.CRDT_AMO'), $lRaw, 'Credit Applied', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_ORD_COMM.PAY_AMO'), $lRaw, 'Processed Payment', $sheet, '', '#F2DDDC', 'left', True, '', False, 17, '', 10);
		Controller::SetCell(config('excel.XL_ORD_COMM.COMM_AMO'), $lRaw, 'Commission', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
	}

	public function SetExlDataList($sheet, &$lRaw, $aComLst, $sFrmDate, $sToDate)
	{
		$i = 0;
		while(isset($aComLst) && count($aComLst) > 0 && $i<count($aComLst))
		{
			$lRaw = $lRaw + 1;
			if($aComLst[$i]['nUser_Type'] == config('constant.USER.TEACHER'))
            {
                $sUserName = $aComLst[$i]['sTchr_FName'].' '.$aComLst[$i]['sTchr_LName'];
            }
            else if($aComLst[$i]['nUser_Type'] == config('constant.USER.CHILD'))
            {
                $sUserName = $aComLst[$i]['sChld_FName'].' '.$aComLst[$i]['sChld_LName'];
            }
            else
            {
                $sUserName = $aComLst[$i]['sPrnt_FName'].' '.$aComLst[$i]['sPrnt_LName'];
            }
			$oGetWlt = $this->Wallet->CrdtUse($aComLst[$i]['lOrder_IdNo']);
			Controller::SetCell(config('excel.XL_ORD_COMM.SR_NO'), $lRaw, $i+1, $sheet, '', '', 'right', False, '', False, 8, '', 10);
			Controller::SetCell(config('excel.XL_ORD_COMM.TRAN_DATE'), $lRaw, date("d M, Y h:i A", strtotime($aComLst[$i]['sCrt_DtTm'])), $sheet, '', '', 'left', False, '', False, 20, '', 10);
			Controller::SetCell(config('excel.XL_ORD_COMM.ORD_NO'), $lRaw, $aComLst[$i]['sOrdr_Id'], $sheet, '', '', 'right', False, '', False, 10, '', 10);
			Controller::SetCell(config('excel.XL_ORD_COMM.STDNT_NAME'), $lRaw, $sUserName, $sheet, '', '', 'left', False, '', False, 25, '', 10);
			Controller::SetCell(config('excel.XL_ORD_COMM.ORD_AMO'), $lRaw, $aComLst[$i]['sGrnd_Ttl'], $sheet, '', '', 'right', False, '#,##0.00', False, 15, '', 10);
			Controller::SetCell(config('excel.XL_ORD_COMM.CRDT_AMO'), $lRaw, empty($oGetWlt['sTtl_Amo']) ? 0 : $oGetWlt['sTtl_Amo'], $sheet, '', '', 'right', False, '#,##0.00', False, 15, '', 10);
			Controller::SetCell(config('excel.XL_ORD_COMM.PAY_AMO'), $lRaw, '=('.Controller::GetColName(config('excel.XL_ORD_COMM.ORD_AMO')).$lRaw.'-'.Controller::GetColName(config('excel.XL_ORD_COMM.CRDT_AMO')).$lRaw.')', $sheet, '', '', 'right', False, '#,##0.00', False, 17, '', 10);
			Controller::SetCell(config('excel.XL_ORD_COMM.COMM_AMO'), $lRaw, empty($aComLst[$i]['sCom_Amo']) ? 0 : $aComLst[$i]['sCom_Amo'], $sheet, '', '', 'right', False, '#,##0.00', False, 15, '', 10);
			$i++;
		}
	}

	public function SetExlTotl($sheet, $lRaw)
	{
		$lLstRaw = $lRaw;
		$lRaw = $lRaw + 1;
		Controller::SetCell(config('excel.XL_ORD_COMM.SR_NO'), $lRaw, 'Total Amount', $sheet, config('excel.XL_ORD_COMM.STDNT_NAME'), '#F2DDDC', 'center', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_ORD_COMM.ORD_AMO'), $lRaw, '=SUM('.Controller::GetColName(config('excel.XL_ORD_COMM.ORD_AMO')).'3:'.Controller::GetColName(config('excel.XL_ORD_COMM.ORD_AMO')).$lLstRaw.')', $sheet, '', '#F2DDDC', 'right', True, '$ #,##0.00', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_ORD_COMM.CRDT_AMO'), $lRaw, '=SUM('.Controller::GetColName(config('excel.XL_ORD_COMM.CRDT_AMO')).'3:'.Controller::GetColName(config('excel.XL_ORD_COMM.CRDT_AMO')).$lLstRaw.')', $sheet, '', '#F2DDDC', 'right', True, '$ #,##0.00', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_ORD_COMM.PAY_AMO'), $lRaw, '=SUM('.Controller::GetColName(config('excel.XL_ORD_COMM.PAY_AMO')).'3:'.Controller::GetColName(config('excel.XL_ORD_COMM.PAY_AMO')).$lLstRaw.')', $sheet, '', '#F2DDDC', 'right', True, '$ #,##0.00', False, 17, '', 10);
		Controller::SetCell(config('excel.XL_ORD_COMM.COMM_AMO'), $lRaw, '=SUM('.Controller::GetColName(config('excel.XL_ORD_COMM.COMM_AMO')).'3:'.Controller::GetColName(config('excel.XL_ORD_COMM.COMM_AMO')).$lLstRaw.')', $sheet, '', '#F2DDDC', 'right', True, '$ #,##0.00', False, 15, '', 10);
	}
}
?>