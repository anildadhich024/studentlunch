<?php

namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SuperAdmin;
use App\Model\Country;
use App\Model\State;
use Excel;
 use App\Model\Company;
		

class StateController extends Controller
{
	public function __construct()
	{
		$this->Company 	= new Company; 
		$this->Country 			= new Country;
		$this->State 			= new State;
		$this->middleware(SuperAdmin::class);
	}

	public function SaveCntrl(Request $request)
	{
		$lStateIdNo 	= base64_decode($request['lStateIdNo']);
		$rules = [
	        'lCntryIdNo' 	=> 'required',
	        'sStateName' 	=> 'required',
            'nAreaCode' 	=> 'required',
            'dTaxPer' 		=> 'required',
	    ];
	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
		{
		    $aHdArr 	= $this->HdArr($request);
		    \DB::beginTransaction();
		    	if($lStateIdNo == 0)
		    	{
		    		$this->InsrtArr($aHdArr);
					$lStateIdNo	= $this->State->InsrtRecrd($aHdArr);
					Controller::writeFile('State Created');
		    		$sMessage	= "State created successfully...";
		    	}
		    	else
		    	{
					$nRow		= $this->State->UpDtRecrd($aHdArr, $lStateIdNo);
					Controller::writeFile('State Updated');
		    		$sMessage	= "State update successfully...";
		    	}
			\DB::commit();
		    return redirect('admin_panel/state/list')->with('Success', $sMessage);
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect('admin_panel/state/list')->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function HdArr($request)
	{
		$aConArr = array(
			'lCntry_IdNo' 	=> $request['lCntryIdNo'],
            'sState_Name' 	=> $request['sStateName'],
            'nArea_Code' 	=> $request['nAreaCode'],
            'dTax_Per' 		=> $request['dTaxPer'],
		);
		return $aConArr;
	}

	public function InsrtArr(&$aHdArr)
	{
		$aHdArr['nBlk_UnBlk']		= config('constant.STATUS.UNBLOCK');
		$aHdArr['nDel_Status']		= config('constant.DEL_STATUS.UNDELETED');
	}

	public function ListPage(Request $request)
	{
		$sStateName = $request['sStateName'];
		$oStateLst 	= $this->State->StateLst($request['sStateName']);
		$aCntryLst 	= $this->Country->FrntLst();
		$sTitle 	= "Manage State List";
    	$aData 		= compact('sTitle','oStateLst','aCntryLst','request');
        return view('admin_panel.state_list',$aData);	
	}
}
?>