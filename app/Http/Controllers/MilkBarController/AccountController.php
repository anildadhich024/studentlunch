<?php
namespace App\Http\Controllers\MilkBarController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MilkBarAuth;
use App\Model\School;
use App\Model\Company;
use App\Model\Country;
use App\Model\State;
use App\Model\MilkBar;
use App\Model\RequestSchool;
use App\Model\AssociateSchool;

class AccountController extends Controller
{
	public function __construct()
	{
		$this->School 			= new School;
		$this->Company 			= new Company;
		$this->Country 			= new Country;
		$this->State 			= new State;
		$this->RequestSchool = new RequestSchool;
		$this->MilkBar 			= new MilkBar;
		$this->AssociateSchool 	= new AssociateSchool;
		$this->middleware(MilkBarAuth::class);
	}

	public function IndexPage()
	{
	    $lMilkIdNo 	= session('USER_ID');

		$aCntryLst	= $this->Country->FrntLst();
		$aMilkDtl	= $this->MilkBar->MilkDtl($lMilkIdNo);
		$aSchlLst	= $this->School->SchlAll();
		$aAccSchl 	= $this->AssociateSchool->AccSchlLst($lMilkIdNo);
		$aStateLst	= $this->State->FrntLst($aMilkDtl['lCntry_IdNo']);
		$sTitle 	= "Manage Account";
    	$aData 		= compact('sTitle','aCntryLst','aMilkDtl','aStateLst','aSchlLst','aAccSchl');
        return view('milkbar_panel.manage_account',$aData);	
	}

	public function SaveCntrl(Request $request)
	{
		$lMilkIdNo 	= session('USER_ID');
		$rules = [
	        'sBussName' 	=> 'required|min:5|max:50|regex:/^[\pL\s]+$/u',
            'nBussType' 	=> 'required',
            'sAbnNo' 		=> 'required|unique:mst_milk_bar,sAbn_No,'.$lMilkIdNo.',lMilk_IdNo',
            'sFrstName' 	=> 'required|min:3|max:15|regex:/^[\pL\s]+$/u',
            'sLstName' 		=> 'required|min:3|max:15|regex:/^[\pL\s]+$/u',
            'sMobileNo' 	=> 'required',
            'sPhoneNo' 		=> 'required',
            'sStrtNo'		=> 'required',
            'sStrtName'		=> 'required|min:5|max:50|regex:/^[\pL\s]+$/u',
            'lCntryIdNo'	=> 'required',
            'lStateIdNo'	=> 'required',
            'sSbrbName'		=> 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'sPinCode'		=> 'required|digits:4',
            'nSchlType1'	=> 'required',
            'lSchlIdNo1'	=> 'required',
            'dDistKm1'		=> 'required|between:0,7',
            'sSbrbName1'	=> 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'sPinCode1'		=> 'required|digits:4',
            'sCutTm1'		=> 'required',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
		{
		    $aHdArr 	= $this->HdArr($request);
		    \DB::beginTransaction();
	    		$nRow		= $this->MilkBar->UpDtRecrd($aHdArr, $lMilkIdNo);
	    		$aDelArr 	= $this->DelArr(); 
	    		$this->AssociateSchool->DelRecrd($aDelArr, $lMilkIdNo);

		    	if((!empty($lMilkIdNo) && $lMilkIdNo > 0))
		    	{
		    		session(['USER_NAME' => $request['sFrst_Name']." ".$request['sLst_Name']]);
		    		$i=1;
		    		for($i==1;$i<=$request['nTtlRec'];$i++)
		    		{
		    			if(!empty($request['nSchlType'.$i]) && !empty($request['lSchlIdNo'.$i]) && !empty($request['dDistKm'.$i]) && !empty($request['sSbrbName'.$i]) && !empty($request['sPinCode'.$i]) && !empty($request['sCutTm'.$i]))
		    			{
		    				$aSchlArr = $this->SchlArr($lMilkIdNo, $request['nSchlType'.$i], $request['lSchlIdNo'.$i], $request['dDistKm'.$i], $request['sSbrbName'.$i], $request['sPinCode'.$i], $request['sCutTm'.$i]);
		    				if(isset($request['lMilkSchlIdNo'.$i]) && !empty($request['lMilkSchlIdNo'.$i]))
		    				{
		    					$this->AssociateSchool->UpDtRecrd($aSchlArr, $request['lMilkSchlIdNo'.$i]);
							}
		    				else
		    				{
								$this->AssociateSchool->InsrtRecrd($aSchlArr);	
		    				}
		    				
		    			}
					} 
		    	}	
			\DB::commit();
		    return redirect()->back()->with('Success', "Account updated successfully...");
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect()->back()->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function MilkSchCntrl(Request $request)
	{
		$lMilkIdNo = session('USER_ID');
		$sarray=array();
		$i=1;
		for($i==1;$i<=$request['nTtlRecs'];$i++)
		{ 
			$sarray[$i]=array( 
				'lSchlTypes'	=> $request['lSchlTypes'.$i],
				'sSchlName'		=> $request['sSchlName'.$i],
				'sSbrbName'		=> $request['sSbrbName'.$i],
				'sPinCode'		=> $request['sPinCode'.$i],
			);    
		}
		$request->session()->put('request_school',$sarray);
		if($request->session()->has('request_school'))
		{                 
			$aDelArr 	= $this->DelArr(); 
			$this->RequestSchool->DelRecrd($aDelArr, $lMilkIdNo,config('constant.USER.MILK_BAR'));                                                              
			$milkbarSchReq=$request->session()->get('request_school'); 
			foreach($milkbarSchReq as $key=>$se){
				$lSchlType=$se['lSchlTypes'];
				if(!empty($se['lSchlTypes']) && !empty($se['sSchlName']) && !empty($se['sSbrbName']) && !empty($se['sPinCode']))
				{
					$aReqSchlArr = $this->SchlReqArr($lMilkIdNo, $se['lSchlTypes'], $se['sSchlName'], $se['sSbrbName'], $se['sPinCode']);
					$this->RequestSchool->InsrtRecrd($aReqSchlArr);
					$request->session()->forget('request_school');
				}
			}
		}
		return redirect()->back()->with('Success', 'School Requested successfully...');
	}

	public function SchlReqArr($lMilkIdNo, $nSchlType, $sSchlName, $sSbrbName, $sPinCode)
	{
		$aConArr = array(
			"lUser_IdNo"  	=> $lMilkIdNo,
			"nSchl_Type"	=> $nSchlType,
			"sSchl_Name"	=> $sSchlName,
			"sSbrb_Name"	=> $sSbrbName,
			"sPin_Code"		=> $sPinCode,
			"nUser_Type"	=> config('constant.USER.MILK_BAR'),
			'nReq_Status'	=> config('constant.REQ_STATUS.Pending'),
			'nDel_Status'	=> config('constant.DEL_STATUS.UNDELETED'),
		);
		return $aConArr;
	}

	public function HdArr($request)
	{
		$aConArr = array(
			'sBuss_Name' 	=> $request['sBussName'],
            'nBuss_Type' 	=> $request['nBussType'],
            'sAbn_No' 		=> $request['sAbnNo'],
            'sFrst_Name' 	=> $request['sFrstName'],
            'sLst_Name' 	=> $request['sLstName'],
            'sCntry_Code' 	=> $request['sCntryCode'],
            'sArea_Code' 	=> $request['sAreaCode'],
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

	public function SchlArr($lMilkIdNo, $nSchlType, $lSchlIdNo, $dDistKm, $sSbrbName, $sPinCode, $sCutTm)
	{
		$aConArr = array(
			"lMilk_IdNo"	=> $lMilkIdNo,
			"nSchl_Type"	=> $nSchlType,
			"lSchl_IdNo"	=> $lSchlIdNo,
			"dDist_Km"		=> $dDistKm,
			"sSbrb_Name"	=> $sSbrbName,
			"sPin_Code"		=> $sPinCode,
			"sCut_Tm"		=> $sCutTm,
			'nBlk_UnBlk'	=> config('constant.STATUS.UNBLOCK'),
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

	public function PswrdPage()
	{
		$sTitle 	= "Change Password";
    	$aData 		= compact('sTitle');
        return view('milkbar_panel.manage_password',$aData);	
	}

	public function PswrdCntrl(Request $request)
	{
		$lMilkIdNo 	= session('USER_ID');
		$rules = [
	        'sCurrPass'		=> 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
	        'sLgnPass'		=> 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'sCnfrmPass'	=> 'required|required_with:sLgnPass|same:sLgnPass',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
		{
			$yPassExist = $this->MilkBar->IsPassExist($request['sCurrPass'], $lMilkIdNo);
			if(!$yPassExist)
			{
				return redirect()->back()->with('Failed', 'Current password did not matched...');
			}
			else
			{
			    $aPassArr 	= $this->PassArr($request['sCnfrmPass']);
	    		$nRow		= $this->MilkBar->UpDtRecrd($aPassArr, $lMilkIdNo);
	    		if($nRow > 0)
	    		{
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
	
	public function StripePage(Request $request)
	{
		$sAccLink = NULL;
		$aStrpAcc = $this->MilkBar->StrpAccDtl(session('USER_ID'));
		if(!empty($aStrpAcc['sStrp_Acc_Id']))
		{
			\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
			$aAccDtl = \Stripe\Account::createLoginLink(
				$aStrpAcc['sStrp_Acc_Id'],
			    ['redirect_url' => $request->getUri()]
			);
			$sAccLink = $aAccDtl->url;
		}
		$aGetDtl    = $this->MilkBar->ShrtDtl(session('USER_ID'));
		$sTitle 	= "Stripe Payment Setup";
    	$aData 		= compact('sTitle','sAccLink','aGetDtl');
        return view('milkbar_panel.stripe_view',$aData);		
	}

	public function StripeAcc(Request $request)
	{
		try
		{
			$lMilkIdNo = session('USER_ID');
			\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
			$aStrpResponse = \Stripe\OAuth::token([
		     	'grant_type' => 'authorization_code',
		     	'code' => $request['code'],
		    ]);
			$aAccArr 	= $this->AccArr($aStrpResponse->stripe_user_id);
			$nRow 		= $this->MilkBar->UpDtRecrd($aAccArr,$lMilkIdNo);
			return redirect('milkbar_panel/stripe')->with('Success', 'Stripe account setup successfully...');
		}
		catch(\Exception $e)
		{
			return redirect('milkbar_panel/stripe')->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function AccArr($sStrpAccId)
	{
		$aComnArr = array(
			"sStrp_Acc_Id" => $sStrpAccId,
		);
		return $aComnArr;
	}
}
?>