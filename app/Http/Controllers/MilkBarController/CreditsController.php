<?php
namespace App\Http\Controllers\MilkbarController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MilkBarAuth;
use App\Model\Wallet;
use App\Model\AssociateSchool;
use Excel;

class CreditsController extends Controller
{
	public function __construct()
	{
		$this->Wallet 			= new Wallet;
		$this->AssociateSchool 	= new AssociateSchool;
		$this->middleware(MilkBarAuth::class);
	}

	public function ListPage(Request $request)
	{
		$lMilkIdNo 	= session('USER_ID');
		$sFrmDate	= !empty($request['sFrmDate']) ? $request['sFrmDate']." 00:00:00" : '';
		$sToDate	= !empty($request['sToDate']) ? $request['sToDate']." 23:59:59" : '';
		$lSchlIdNo	= $request['lSchlIdNo'];
		$sOrdrId	= $request['sOrdrId'];
		$oCrdtDtl	= $this->Wallet->MilkCrdtsLst($lMilkIdNo, $sFrmDate, $sToDate, $lSchlIdNo, $sOrdrId);
		$aAccSchl 	= $this->AssociateSchool->AccSchlLst($lMilkIdNo);
		$aCrdtDtl	= $this->Wallet->MilkCrdt($lMilkIdNo, config('constant.TRANS.Credit'), $sFrmDate, $sToDate, $lSchlIdNo, $sOrdrId);
		$aDbtDtl	= $this->Wallet->MilkCrdt($lMilkIdNo, config('constant.TRANS.Debit'), $sFrmDate, $sToDate, $lSchlIdNo, $sOrdrId);
		$sTitle 	= "Manage Credits";
    	$aData 		= compact('sTitle','oCrdtDtl','aAccSchl','aCrdtDtl','aDbtDtl','request');
        return view('milkbar_panel.credit_list',$aData);	
	}

	public function ExprtRcrd(Request $request)
	{
		$lMilkIdNo 	= session('USER_ID');
		$sFrmDate	= !empty($request['sFrmDate']) ? $request['sFrmDate']." 00:00:00" : '';
		$sToDate	= !empty($request['sToDate']) ? $request['sToDate']." 23:59:59" : '';
		$lSchlIdNo	= $request['lSchlIdNo'];
		$sOrdrId	= $request['sOrdrId'];
		$aCrdtDtl	= $this->Wallet->ExlRcrd($lMilkIdNo, $sFrmDate, $sToDate, $lSchlIdNo, $sOrdrId);
		if(count($aCrdtDtl) > 0)
		{
			$FileName = 'My_Credit_'.date('Ymd').'_'.date('His');
	        Excel::create($FileName, function($excel) use ($aCrdtDtl) {
	            $excel->sheet('Sheet1', function($sheet)  use ($aCrdtDtl) {
	                $this->SetExlHeader($sheet, $lRaw);
	                $this->SetExlData($sheet, $lRaw, $aCrdtDtl);
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
		Controller::SetCell(config('excel.XL_CREDIT.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'right', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_CREDIT.TRAN_DATE'), $lRaw, 'Transaction Date', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_CREDIT.ORD_NO'), $lRaw, 'Order Number', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_CREDIT.STDNT_NAME'), $lRaw, 'Student Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_CREDIT.CRDT_AMO'), $lRaw, 'Credit', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_CREDIT.DEBT_AMO'), $lRaw, 'Debit', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
	}

	public function SetExlData($sheet, &$lRaw, $aCrdtDtl)
	{
		$i = 0;
		while(isset($aCrdtDtl) && count($aCrdtDtl) > 0 && $i<count($aCrdtDtl))
		{
			$lRaw = $lRaw + 1;
			if($aCrdtDtl[$i]['nUser_Type'] == config('constant.USER.TEACHER'))
            {
                $sUserName = $aCrdtDtl[$i]['sTchr_FName'].' '.$aCrdtDtl[$i]['sTchr_LName'];
            }
            else if($aCrdtDtl[$i]['nUser_Type'] == config('constant.USER.CHILD'))
            {
                $sUserName = $aCrdtDtl[$i]['sChld_FName'].' '.$aCrdtDtl[$i]['sChld_LName'];
            }
            else
            {
                $sUserName = $aCrdtDtl[$i]['sPrnt_FName'].' '.$aCrdtDtl[$i]['sPrnt_LName'];
            }
			Controller::SetCell(config('excel.XL_CREDIT.SR_NO'), $lRaw, $i+1, $sheet, '', '', 'right', False, '', False, 8, '', 10);
			Controller::SetCell(config('excel.XL_CREDIT.TRAN_DATE'), $lRaw, date('d M, Y h:i A', strtotime($aCrdtDtl[$i]['sCrt_DtTm'])), $sheet, '', '', 'left', False, '', False, 20, '', 10);
			Controller::SetCell(config('excel.XL_CREDIT.ORD_NO'), $lRaw, $aCrdtDtl[$i]['sOrdr_Id'], $sheet, '', '', 'left', False, '', False, 15, '', 10);
			Controller::SetCell(config('excel.XL_CREDIT.STDNT_NAME'), $lRaw, $sUserName, $sheet, '', '', 'left', False, '', False, 25, '', 10);
			Controller::SetCell(config('excel.XL_CREDIT.CRDT_AMO'), $lRaw, $aCrdtDtl[$i]['nTyp_Status'] == config('constant.TRANS.Credit') ? $aCrdtDtl[$i]['sTtl_Amo'] : '', $sheet, '', '', 'right', False, '#0.00', False, 10, '', 10);
			Controller::SetCell(config('excel.XL_CREDIT.DEBT_AMO'), $lRaw, $aCrdtDtl[$i]['nTyp_Status'] == config('constant.TRANS.Debit') ? $aCrdtDtl[$i]['sTtl_Amo'] : '', $sheet, '', '', 'right', False, '#0.00', False, 10, '', 
				10);

			$i++;
		}
	}
}
?>