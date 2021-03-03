<?php
namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SuperAdmin;
use Validator;
use App\Model\Company;
use App\Model\Country;
use App\Model\State; 

class AccountController extends Controller
{
	public function __construct()
	{ 
		$this->Company 	= new Company;
		$this->Country 	= new Country;
		$this->State 	= new State;		
		$this->middleware(SuperAdmin::class);

	}

	public function IndexPage()
	{ 
		$aCompDtl 	= $this->Company->CompDtl();
		$aCntryLst	= $this->Country->FrntLst();
		$aStateLst	= $this->State->FrntLst($aCompDtl['lCntry_IdNo']);
		$sTitle 	= "Manage Account";
    	$aData 		= compact('sTitle','aCompDtl','aCntryLst','aStateLst');
        return view('admin_panel.manage_account',$aData);	
	}

	public function SaveCntrl(Request $request)
	{
		$rules = [
	        'sCompName' 	=> 'required|min:5|max:50|regex:/^[\pL\s]+$/u',
	        'sAbnNo' 		=> 'required|unique:mst_milk_bar,sAbn_No|min:11',
	        'sFrstName' 	=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sLstName' 		=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sMobileNo' 	=> 'required',
            'sPhoneNo' 		=> 'required',
            'lCntryIdNo'	=> 'required',
            'lStateIdNo'	=> 'required',
            'sStrtNo'		=> 'required|max:5',
            'sStrtName'		=> 'required|min:5|max:50|regex:/^[\pL\s]+$/u',
            'sSbrbName'		=> 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'sPinCode'		=> 'required|digits:4',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));
		try
		{
		    \DB::beginTransaction();
		    	$aHdArr 	= $this->HdArr($request);
				$nRow		= $this->Company->UpDtRecrd($aHdArr);
				Controller::writeFile('Updated Account For Bussiness Name '. $request['sCompName']);
			\DB::commit();
		    return redirect()->back()->with('Success', 'Account updated successfully...');
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect()->back()->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function HdArr($request)
	{
		$aConArr = array(
			'sComp_Name' 	=> $request['sCompName'],
			'sAbn_No' 		=> $request['sAbnNo'],
			'sFrst_Name' 	=> $request['sFrstName'],
			'sLst_Name' 	=> $request['sLstName'],
			'sLgn_Email' 	=> $request['sLgnEmail'],
			'sCntry_Code'	=> $request['sCntryCode'],
            'sArea_Code'	=> $request['sAreaCode'],
            'sPhone_No'		=> $request['sPhoneNo'],
            'sMobile_No'	=> $request['sMobileNo'],
            'sStrt_No'		=> $request['sStrtNo'],
            'sStrt_Name'	=> $request['sStrtName'],
            'sSbrb_Name'	=> $request['sSbrbName'],
            'lCntry_IdNo'	=> $request['lCntryIdNo'],
            'lState_IdNo'	=> $request['lStateIdNo'],
            'sPin_Code'		=> $request['sPinCode'],
		);
		return $aConArr;
	}

	public function PswrdPage()
	{
		$sTitle 	= "Change Password";
    	$aData 		= compact('sTitle');
        return view('admin_panel.manage_password',$aData);	
	}

	public function PswrdCntrl(Request $request)
	{
		$lPrntIdNo 	= session('USER_ID');
		$rules = [
	        'sCurrPass'		=> 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
	        'sLgnPass'		=> 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'sCnfrmPass'	=> 'required|required_with:sLgnPass|same:sLgnPass',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
		{
			$yPassExist = $this->Company->IsPassExist($request['sCurrPass']);
			if(!$yPassExist)
			{
				return redirect()->back()->with('Failed', 'Current password did not matched...');
			}
			else
			{
			    $aPassArr 	= $this->PassArr($request['sCnfrmPass']);
	    		$nRow		= $this->Company->UpDtRecrd($aPassArr);
	    		if($nRow > 0)
	    		{
					Controller::writeFile('Password Changed');
	    			return redirect()->back()->with('Success', "Password updated successfully...");
	    		}
	    		else
	    		{
	    			return redirect()->back()->with('Alert', "No change found in password...");	
	    		}
	    	}
		}
		catch(\Exception $e)
		{
			return redirect()->back()->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function PassArr($sUserPass)
	{
		$aPassArr = array(
			"sLgn_Pass"	=> md5($sUserPass),
		);
		return $aPassArr;
	}
}
?>