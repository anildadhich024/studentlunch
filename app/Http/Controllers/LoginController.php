<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\MilkBar;
use App\Model\Parents;
use App\Model\Teacher;
use Session;

class LoginController extends Controller
{	
	public function __construct()
	{
		$this->MilkBar = new MilkBar;
		$this->Parents = new Parents;
		$this->Teacher = new Teacher;
	}
	
	public function IndexPage()
	{
		session_unset();
		$sTitle = "Login Panel";
    	$aData 	= compact('sTitle');
		if(Session::has('user')){
			return view('login',$aData);
		}
        return view('login',$aData);	
	}
	
	public function Login(Request $request)
	{
		
		$rules = [
	        'sEmailId' 		=> 'required|max:50|regex:^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^',
            'sLgnPass'		=> 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
	    ];	
		
		$this->validate($request, $rules, config('constant.VLDT_MSG'));
		
		$IsMlkEmailExst 	= $this->MilkBar->IsMailExist($request['sEmailId']);
		$IsPrntEmailExst 	= $this->Parents->IsMailExist($request['sEmailId']);
		$IsTchrEmailExst 	= $this->Teacher->IsMailExist($request['sEmailId']);

		if($IsMlkEmailExst || $IsPrntEmailExst || $IsTchrEmailExst)
		{
			if($IsMlkEmailExst && !$IsPrntEmailExst && !$IsTchrEmailExst)
			{
				$yMilkExist = $this->MilkBar->IsUserExist($request['sEmailId'], $request['sLgnPass'], $aMilkDtl);
				if($aMilkDtl['nEmail_Status'] == config('constant.MAIL_STATUS.VERIFIED'))
				{
				    if($aMilkDtl['nBlk_UnBlk'] == config('constant.STATUS.UNBLOCK'))
					{
						if($aMilkDtl['nDel_Status'] == config('constant.DEL_STATUS.UNDELETED'))
						{
							session(['USER_ID' => $aMilkDtl['lMilk_IdNo']]);
							session(['USER_TYPE' => 'M']);
							session(['USER_NAME' => $aMilkDtl['sFrst_Name']." ".$aMilkDtl['sLst_Name']]);
							return redirect('milkbar_panel');
						}
						else
						{
							return redirect()->back()->with('Failed', 'Your account was permanently closed...');
						}
					}	
					else
					{
						return redirect()->back()->with('Failed', 'Your account was temporary closed...');
					}
				}
				else
				{
					return redirect()->back()->with('Failed', 'Please verify your email and then login...');		
				}
			}
			else if(!$IsMlkEmailExst && $IsPrntEmailExst && !$IsTchrEmailExst)
			{
				$yPrntExist = $this->Parents->IsUserExist($request['sEmailId'], $request['sLgnPass'], $aParentsDtl);
				if(!$yPrntExist)
				{
					return redirect()->back()->with('Failed', 'Invalid password. Try again or click on Forgot password to reset it');
				}
				else
				{
					if($aParentsDtl['nEmail_Status'] == config('constant.MAIL_STATUS.VERIFIED'))
					{
						if($aParentsDtl['nBlk_UnBlk'] == config('constant.STATUS.UNBLOCK'))
						{
							if($aParentsDtl['nDel_Status'] == config('constant.DEL_STATUS.UNDELETED'))
							{
								session(['USER_ID' => $aParentsDtl['lPrnt_IdNo']]);
								session(['USER_TYPE' => 'P']);
								session(['USER_NAME' => $aParentsDtl['sFrst_Name']." ".$aParentsDtl['sLst_Name']]);
								return redirect('parent_panel');
							}
							else
							{
								return redirect()->back()->with('Failed', 'Your account was permanently closed...');
							}
						}	
						else
						{
							return redirect()->back()->with('Failed', 'Your account was temporary closed...');
						}
					}
					else
					{
						return redirect()->back()->with('Failed', 'Please verify your email and then login...');		
					}
				}
			}
			else
			{
				$yTchrExist = $this->Teacher->IsUserExist($request['sEmailId'], $request['sLgnPass'], $aTchrDtl);
				if(!$yTchrExist)
				{
					return redirect()->back()->with('Failed', 'Invalid password. Try again or click on Forgot password to reset it');
				}
				else
				{
					if($aTchrDtl['nEmail_Status'] == config('constant.MAIL_STATUS.VERIFIED'))
					{
						if($aTchrDtl['nBlk_UnBlk'] == config('constant.STATUS.UNBLOCK'))
						{
							if($aTchrDtl['nDel_Status'] == config('constant.DEL_STATUS.UNDELETED'))
							{
								session(['USER_ID' => $aTchrDtl['lTchr_IdNo']]);
								session(['USER_TYPE' => 'T']);
								session(['USER_NAME' => $aTchrDtl['sFrst_Name']." ".$aTchrDtl['sLst_Name']]);
								return redirect('teacher_panel');
							}
							else
							{
								return redirect()->back()->with('Failed', 'Your account was permanently closed...');
							}
						}	
						else
						{
							return redirect()->back()->with('Failed', 'Your account was temporary closed...');
						}
					}
					else
					{
						return redirect()->back()->with('Failed', 'Please verify your email and then login...');		
					}
				}
			}
		}
		else
		{
			return redirect()->back()->with('Failed', 'Couldn’t find your Account');
		}
	}

	public function Logout()
	{
		session()->flush();
		return redirect("");
	}
}
?>