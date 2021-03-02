<?php

namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SuperAdmin;
use App\Model\CommPlan;
use App\Model\Holiday;
use App\Model\Country;
use App\Model\State;
 use App\Model\Company; 

class HolidayController extends Controller
{
	public function __construct()
	{
		$this->Company 	= new Company; 
		$this->CommPlan = new CommPlan;
		$this->Holiday = new Holiday;
		$this->Country = new Country;
		$this->State = new State;
		$this->middleware(SuperAdmin::class);
	}

	public function ListPage(Request $request)
	{
		$lCntryId= "";
		if(isset($_GET['lCntryIdNo']) && $_GET['lCntryIdNo'] != ""){
			$lCntryId=base64_decode($_GET['lCntryIdNo']);
		}
		
		$sCntryName = $request['sCntryName'];
		$aHolidayLst 	= $this->Holiday->HolidayLst($lCntryId);
		$aCntryLst	= $this->Country->FrntLst();
		$sTitle 	= "Manage Holiday List";
    	$aData 		= compact('sTitle','aHolidayLst','aCntryLst','lCntryId','request');
        return view('admin_panel.holiday_list',$aData);	
	}

	public function SaveCntrl(Request $request)
	{
		$lHolidayIdNo 	= base64_decode($request['lHolidayIdNo']);
		$rules = [
            'lCntryIdNo' 	=> 'required',
            'lStateIdNo' 	=> 'required',
			'sHoldayName'  => 'required',
			'nHoldayType'  => 'required',
			'sStrtDt' 		=> 'required',
            'sEndDt' 		=> 'required',
	    ];
	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
		{
			$aHdArr 	= $this->HdArr($request);  
		    	if($lHolidayIdNo == 0)
		    	{
		    		$this->InsrtArr($aHdArr);
					$lHolidayIdNo	= $this->Holiday->InsrtRecrd($aHdArr);
					Controller::writeFile('Holiday Created');
		    		$sMessage	= "Holiday created successfully...";
		    	}
		    	else
		    	{
					$nRow		= $this->Holiday->UpDtRecrd($aHdArr, $lHolidayIdNo);
					Controller::writeFile('Holiday Updated');
		    		$sMessage	= "Holiday update successfully...";
		    	} 
		    return redirect('admin_panel/holiday/list')->with('Success', $sMessage);
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect('admin_panel/holiday/list')->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function HdArr($request)
	{
		$aConArr = array(
			'lCntry_IdNo' 	=> $request['lCntryIdNo'],
            'lState_IdNo' 	=> $request['lStateIdNo'],
			'sHolday_Name'  => $request['sHoldayName'],
			'nHolday_Type'  => $request['nHoldayType'],
			'sStrt_Dt' 		=> $request['sStrtDt'],
            'sEnd_Dt' 		=> $request['sEndDt'],
		);
		return $aConArr;
	}

	public function InsrtArr(&$aHdArr)
	{
		$aHdArr['nDel_Status']		= config('constant.DEL_STATUS.UNDELETED');
		$aHdArr['sCrt_DtTm']        = date('Y-m-d H:i:s');
		$aHdArr['sUpDt_DtTm']       = date('Y-m-d H:i:s');
	}
}
?>