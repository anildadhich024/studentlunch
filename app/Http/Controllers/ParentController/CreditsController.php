<?php
namespace App\Http\Controllers\ParentController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ParentAuth;
use Validator;
use Stripe;
use Excel;
use App\Model\Wallet;
use App\Model\OrderHd;

class CreditsController extends Controller
{
	public function __construct()
	{
		$this->Wallet 	= new Wallet;
		$this->OrderHd 	= new OrderHd;
		$this->middleware(ParentAuth::class);
	}

	public function IndexPage(Request $request)
	{
		$lPrntIdNo 	= session('USER_ID');
		$sFrmDate	= !empty($request['sFrmDate']) ? $request['sFrmDate']." 00:00:00" : '';
		$sToDate	= !empty($request['sToDate']) ? $request['sToDate']." 23:59:59" : '';
		$lMilkIdNo	= $request['lMilkIdNo'];
		$oCrdtLst	= $this->Wallet->PrntCrdtsLst($lPrntIdNo, config('constant.USER.PARENT'), $sFrmDate, $sToDate, $lMilkIdNo);
		$aAccMilk 	= $this->OrderHd->AccMilkLst($lPrntIdNo, config('constant.USER.PARENT'));
		$aCrdtDtl	= $this->Wallet->PrntCrdt($lPrntIdNo, config('constant.USER.PARENT'), config('constant.TRANS.Credit'), $sFrmDate, $sToDate, $lMilkIdNo);
		$aDbtDtl	= $this->Wallet->PrntCrdt($lPrntIdNo, config('constant.USER.PARENT'), config('constant.TRANS.Debit'), $sFrmDate, $sToDate, $lMilkIdNo);
		$sTitle 	= "My Credits";
    	$aData 		= compact('sTitle','oCrdtLst','aAccMilk','aCrdtDtl','aDbtDtl','request');
        return view('parent_panel.credit_list',$aData);	
	}

	public function ExprtRcrd(Request $request)
	{
		$lPrntIdNo 	= session('USER_ID');
		$sFrmDate	= !empty($request['sFrmDate']) ? $request['sFrmDate']." 00:00:00" : '';
		$sToDate	= !empty($request['sToDate']) ? $request['sToDate']." 23:59:59" : '';
		$lMilkIdNo	= $request['lMilkIdNo'];
		$aCrdtDtl	= $this->Wallet->ExlRcrdPrnt($lPrntIdNo, config('constant.USER.PARENT'), $sFrmDate, $sToDate, $lMilkIdNo);
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
		Controller::SetCell(config('excel.XL_CREDIT_PRNT.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'left', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_CREDIT_PRNT.TRAN_DATE'), $lRaw, 'Transaction Date', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_CREDIT_PRNT.ORD_NO'), $lRaw, 'Order Number', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_CREDIT_PRNT.STDNT_NAME'), $lRaw, 'Student Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_CREDIT_PRNT.MILK_NAME'), $lRaw, 'Service Provider Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_CREDIT_PRNT.CRDT_AMO'), $lRaw, 'Credit', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_CREDIT_PRNT.DEBT_AMO'), $lRaw, 'Debit', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
	}

	public function SetExlData($sheet, &$lRaw, $aCrdtDtl)
	{
		$i = 0;
		while(isset($aCrdtDtl) && count($aCrdtDtl) > 0 && $i<count($aCrdtDtl))
		{
			$lRaw = $lRaw + 1;
			$sUserName 	= $aCrdtDtl[$i]['nUser_Type'] == config('constant.USER.CHILD') ? $aCrdtDtl[$i]['sChld_FName'].' '.$aCrdtDtl[$i]['sChld_LName'] : $aCrdtDtl[$i]['sPrnt_FName'].' '.$aCrdtDtl[$i]['sPrnt_LName'];
			Controller::SetCell(config('excel.XL_CREDIT_PRNT.SR_NO'), $lRaw, $i+1, $sheet, '', '', 'right', False, '', False, 8, '', 10);
			Controller::SetCell(config('excel.XL_CREDIT_PRNT.TRAN_DATE'), $lRaw, date('d M, Y h:i A', strtotime($aCrdtDtl[$i]['sCrt_DtTm'])), $sheet, '', '', 'left', False, '', False, 20, '', 10);
			Controller::SetCell(config('excel.XL_CREDIT_PRNT.ORD_NO'), $lRaw, $aCrdtDtl[$i]['sOrdr_Id'], $sheet, '', '', 'left', False, '', False, 15, '', 10);
			Controller::SetCell(config('excel.XL_CREDIT_PRNT.STDNT_NAME'), $lRaw, $sUserName, $sheet, '', '', 'left', False, '', False, 25, '', 10);
			Controller::SetCell(config('excel.XL_CREDIT_PRNT.MILK_NAME'), $lRaw, $aCrdtDtl[$i]['sBuss_Name'], $sheet, '', '', 'left', False, '', False, 25, '', 10);
			Controller::SetCell(config('excel.XL_CREDIT_PRNT.CRDT_AMO'), $lRaw, $aCrdtDtl[$i]['nTyp_Status'] == config('constant.TRANS.Credit') ? $aCrdtDtl[$i]['sTtl_Amo'] : '', $sheet, '', '', 'right', False, '#0.00', False, 10, '', 10);
			Controller::SetCell(config('excel.XL_CREDIT_PRNT.DEBT_AMO'), $lRaw, $aCrdtDtl[$i]['nTyp_Status'] == config('constant.TRANS.Debit') ? $aCrdtDtl[$i]['sTtl_Amo'] : '', $sheet, '', '', 'right', False, '#0.00', False, 10, '', 
				10);

			$i++;
		}
	}
}
?>