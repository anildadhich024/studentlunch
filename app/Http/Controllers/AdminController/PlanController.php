<?php

namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SuperAdmin;
use App\Model\CommPlan;
use App\Model\Country;
use App\Model\State;
use App\Model\Plan;
 use App\Model\Company; 

class PlanController extends Controller
{
	public function __construct()
	{
		$this->Company 	= new Company; 
		$this->CommPlan = new CommPlan;
		$this->Plan 	= new Plan;
		$this->Country = new Country;
		$this->State = new State;
		$this->middleware(SuperAdmin::class);
	}

	public function ListPage(Request $request)
	{
		$sCntryName = $request['sCntryName'];
		$aPlanLst 	= $this->CommPlan->PlanLst();
		$aCntryLst	= $this->Country->FrntLst();
		$sTitle 	= "Manage Plan List";
    	$aData 		= compact('sTitle','aPlanLst','aCntryLst','request');
        return view('admin_panel.plan_list',$aData);	
	}

	public function SaveCntrl(Request $request)
	{
		$lCommPlnIdNo 	= base64_decode($request['lCommPlnIdNo']);
		$rules = [
            'lCntryIdNo' 	=> 'required',
            'lStateIdNo' 	=> 'required',
            'dComPer' 		=> 'required',
            'dCaclPer' 		=> 'required',
            'sPrntAmo' 		=> 'required',
            'sStrtDt' 		=> 'required',
	    ];
	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
		{
		    $aHdArr 	= $this->HdArr($request);
		    \DB::beginTransaction();
		    	if($lCommPlnIdNo == 0)
		    	{
		    		$this->InsrtArr($aHdArr);
					$lCntryIdNo	= $this->CommPlan->InsrtRecrd($aHdArr);
					Controller::writeFile('Plan Created');
		    		$sMessage	= "Plan created successfully...";
		    	}
		    	else
		    	{
					$nRow		= $this->CommPlan->UpDtRecrd($aHdArr, $lCommPlnIdNo);
					Controller::writeFile('Plan Updated');
		    		$sMessage	= "Plan update successfully...";
		    	}
			\DB::commit();
		    return redirect('admin_panel/plan/list')->with('Success', $sMessage);
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect('admin_panel/plan/list')->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function HdArr($request)
	{
		$aConArr = array(
			'lCntry_IdNo' 	=> $request['lCntryIdNo'],
            'lState_IdNo' 	=> $request['lStateIdNo'],
            'dCom_Per' 		=> number_format($request['dComPer'], 2),
            'dCacl_Per' 	=> number_format($request['dCaclPer'], 2),
            'sPrnt_Amo' 	=> $request['sPrntAmo'],
            'sStrt_Dt' 		=> $request['sStrtDt'],
		);
		return $aConArr;
	}

	public function InsrtArr(&$aHdArr)
	{
		$aHdArr['nAply_Status']		= config('constant.PLN_STATUS.NON_ACTIVE');
		$aHdArr['nDel_Status']		= config('constant.DEL_STATUS.UNDELETED');
	}

	public function ActvPlan(Request $request)
	{
		$lCommPlnIdNo = base64_decode($request['lRecIdNo']);
		try
		{
			$ActvArr 	= $this->ActvArr();
		    \DB::beginTransaction();
				$nRow		= $this->CommPlan->UpDtRecrd($ActvArr, $lCommPlnIdNo);
				Controller::writeFile('Plan Updated');
			\DB::commit();
			return redirect()->back()->with('Success', 'Plan activated successfully...');
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect()->back()->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function ActvArr()
	{
		$aConArr = array(
			'nAply_Status' 	=> config('constant.PLN_STATUS.ACTIVE'),
		);
		return $aConArr;
	}

	public function ListSub(Request $request)
	{
		$lStateIdNo = $request['lStateIdNo'];
		$sPlnDur = $request['sPlnDur'];  
		$aSubLst 	= $this->Plan->PlnLst($lStateIdNo,$sPlnDur); 
		$sTitle 	= "Manage Subscription Detail";
    	$aData 		= compact('sTitle','aSubLst','request');
        return view('admin_panel.subscription_list',$aData);	
	}

	public function SmryPage(Request $request)
	{   
		$sCntryName = $request['sCntryName'];
		$sStateName = $request['sStateName'];
		$oPlnLst		= $this->Plan->PlnSmry($sCntryName, $sStateName);
		$sTitle 		= "Manage Subscription Summary";
    	$aData 			= compact('sTitle','oPlnLst','request');
        return view('admin_panel.sub_summary_list',$aData);	
	}
}
?>