<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\MilkBar;
use App\Model\Parents;
use Session;

class PasswordController extends Controller
{	
	public function __construct()
	{
		$this->MilkBar = new MilkBar;
		$this->Parents = new Parents;
	}
	
	public function ForgotPassword(Request $request)
	{
		$sTitle = "Send OTP";
    	$aData 	= compact('sTitle');
		if(Session::has('user')){
			Session::forget('user');
		}
        return view('reset_email',$aData);
	}
	
	public function SendCode(Request $request)
	{
		$rules = [
	        'sEmailId' 		=> 'required|max:50|regex:^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^',
	    ];	
		
		$this->validate($request, $rules, config('constant.VLDT_MSG'));
		
		$lCode = rand(100001,999999);
		$nAfftd = $this->MilkBar->GetOtp($request['sEmailId'], $lCode);
		
		if(!$nAfftd)  
		{
			$nAfftd = $this->Parents->GetOtp($request['sEmailId'], $lCode);
		
			if(!$nAfftd)  
			{
				return redirect()->back()->with('Failed', 'Email Not found. Please check and try again');
			}
			else{
				$aEmailData = ['lCode' => $lCode];
            	Controller::SendEmail($request['sEmailId'], '', 'send_otp', 'OTP for Reset Password', $aEmailData);
              
				session(['email' => $request['sEmailId'], 'userType' => 'parent']);
				return redirect("user/verify");
			}
		}
		else {
			$aEmailData = ['lCode' => $lCode];
            Controller::SendEmail($request['sEmailId'], '', 'send_otp', 'OTP for Reset Password', $aEmailData);
			
			session(['email' => $request['sEmailId'], 'userType' => 'milkBar']);
			return redirect("user/verify");
		}
	}
	
	public function VerifyOtp(Request $request)
	{
		$sTitle = "Verify OTP";
    	$aData 	= compact('sTitle');
		if(Session::has('user')){
			Session::forget('user');
		}
        return view('verify_otp',$aData);
	}
	
	public function ResetPass(Request $request)
	{
		$rules = [
			'lOtp'			=> 'required|digits:6',
	        'sLgnPass'		=> 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'sCnfrmPass'	=> 'required|required_with:sLgnPass|same:sLgnPass',
	    ];	
		
		$this->validate($request, $rules, config('constant.VLDT_MSG'));
		
		$nAfftd = 0;
		if(Session::has('email'))
		{
			if(Session('userType') == 'milkBar')  
			{
				$nAfftd = $this->MilkBar->ResetPass(Session('email'), $request['lOtp'], md5($request['sLgnPass']));
			}
			else 
			{
				$nAfftd = $this->Parents->ResetPass(Session('email'), $request['lOtp'], md5($request['sLgnPass']));
			}
			
			if($nAfftd)
			{
				Session::forget('email');
				return redirect("user/login");
			}
			else
			{
				return redirect()->back()->with('Failed', 'Invalid OTP...');
			}
		}
		else
		{
			return redirect("user/forgot")->with('Failed', '...');
		}
	}
}
?>