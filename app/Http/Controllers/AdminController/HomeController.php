<?php

namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Company;
use App\Model\OrderHd;
use App\Model\MilkBar;
use App\Model\School;
use App\Model\Parents;
use App\Model\RequestSchool;
use Charts;
use Stripe;

class HomeController extends Controller
{
	public function __construct()
	{
		$this->Company 			= new Company;
		$this->OrderHd 			= new OrderHd;
		$this->MilkBar 			= new MilkBar;
		$this->School 			= new School;
		$this->Parents 			= new Parents; 
		$this->RequestSchool 	= new RequestSchool; 
	}

	public function IndexPage()
	{  
		
		if(empty(session('ADMIN_ID')))
		{
			$sTitle = "Admin Panel";
	    	$aData 	= compact('sTitle');
	        return view('admin_panel.login',$aData);	
	    }
	    else
	    {
	    	$sFrmDate		= date('Y-m-d');
	    	$sToDate		= date('Y-m-d', strtotime("+6 day"));;
			$oEarning		= $this->OrderHd->GetTtlRvnu();
			$aCntMilk		= $this->MilkBar->CntRec();
			$aCntSchl		= $this->School->CntRec();
			$aCntPrnt		= $this->Parents->CntRec();
			$aCntTdyOrd		= $this->OrderHd->CntAllOrd($sFrmDate,$sFrmDate);
			$aCntWkOrd		= $this->OrderHd->CntAllOrd($sFrmDate,$sToDate);
			$nCntReqSchl	= $this->RequestSchool->CntPndgSchl();
			$aMilkLst		= $this->MilkBar->FltrMilkLst();
			$aLbl		= array();
			$aValue		=array();
			foreach($oEarning as $key => $item){
				array_push($aLbl, config('constant.MONTH.'.$key));
				array_push($aValue, $item);
			}
			$aClrs = array('#42adfe', '#f89900', '#294986', '#e6231e', '#3d64a3');
			$chart = Charts::create('pie', 'highcharts')
						->title("Monthly Revenue")
						->labels($aLbl)
						->values($aValue)
						->colors($aClrs)
						->dimensions(550, 550)
						->responsive(false);
	    	$sTitle = "Dashboard";
	    	$aData 	= compact('sTitle', 'chart', 'aLbl', 'aClrs', 'aValue','aCntMilk','aCntSchl','aCntPrnt','aCntTdyOrd','aCntWkOrd','aMilkLst','nCntReqSchl');
	        return view('admin_panel.dashboard',$aData);	
	    }
	}

	public function LoginPage(Request $request)
	{
		$rules = [
            'sLgnEmail' 	=> 'required|max:50|regex:^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^',
            'sLgnPass'		=> 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
	    {
			$aAdminDtl = array();
			$IsEmailExst = $this->Company->IsEmailExst($request['sLgnEmail'], $aAdminDtl);
			if($IsEmailExst)
			{
				
				$IsAdminExst = $this->Company->IsAdminExst($request['sLgnEmail'], $request['sLgnPass'], $lCompIdNo); 
				if($IsAdminExst)
				{
					session(['ADMIN_ID' => $lCompIdNo]);
					Controller::writeFile('Logged In');
					return redirect('admin_panel');
				}
				else
				{
					return redirect()->back()->with('Failed', 'Invalid password. Try again or click on Forgot password to reset it.');
				}
			}
			else
			{
				return redirect()->back()->with('Failed', 'Invalid Email ID or Password.');
			}
	    }
	    catch(\Exception $e)
	    {
	    	return redirect()->back()->with('Failed', $e->getMessage());
	    }
	}

	public function FrgtPass()
	{
		$sTitle = "Forgot Password";
    	$aData 	= compact('sTitle');
        return view('admin_panel.forgot_password',$aData);	
	}

	public function FrgtEmail(Request $request)
	{
		$rules = [
            'sLgnEmail' 	=> 'required|max:50|regex:^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));	

	    try
	    {
	    	$yEmailExst = $this->Company->IsEmailExst($request['sLgnEmail'], $aCompDtl);
	    	if(!$yEmailExst)
	    	{
	    		return redirect()->back()->with('Failed', 'We could not found any account with that email...');
	    	}
	    	else
	    	{
	    		$sTkn 		= $this->RndmStrg(50);
	    		$aTknArr 	= $this->TknArr($sTkn);
	    		$nRow		= $this->Company->UpDtRecrd($aTknArr);
				$aEmailData = ['sUserName' => $aCompDtl['sComp_Name'], 'sToken' => $sTkn];
				// 
            	Controller::SendEmail($aCompDtl['sLgn_Email'], $aCompDtl['sComp_Name'], 'forgot_password_admin', 'Forgot Password', $aEmailData);
            	return redirect('admin_panel')->with('Success', 'Check your email to reset password...');
	    	}
	    }
	    catch(\Exception $e)
	    {
	    	return redirect()->back()->with('Failed', $e->getMessage());
	    }
	}

	public function RstPass(Request $request)
	{
	    $sRstToken 	= base64_decode($request['token']);
	    $yTknExst	= $this->Company->IsTokenExist($sRstToken);
	    if(!$yTknExst)
	    {
	    	return redirect('admin_panel')->with('Failed', 'Unauthorized Access...');
	    }
	    else
	    {
	    	$sTitle = "Reset Password";
	    	$aData 	= compact('sTitle');
	        return view('admin_panel.reset_password',$aData);	
	    }
	}

	public function SavePass(Request $request)
	{
		$rules = [
	        'sLgnPass'		=> 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'sCnfrmPass'	=> 'required|required_with:sLgnPass|same:sLgnPass',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    $aPassArr 	= $this->PassArr($request['sLgnPass']);
		$nRow		= $this->Company->UpDtRecrd($aPassArr); 
	    return redirect('admin_panel')->with('Success', 'Password updated successfuly...');
	}

	public function TknArr($sTkn)
	{
		$aCmnArr = array(
			"sRst_Token" => $sTkn,
		);
		return $aCmnArr;
	}

	public function PassArr($sLgnPass)
	{
		$aCmnArr = array(
			"sLgn_Pass" 	=> md5($sLgnPass),
			"sRst_Token" 	=> NULL,
		);
		return $aCmnArr;
	}

	function RndmStrg($nLen) 
	{ 
	    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
	    return substr(str_shuffle($str_result),  
	                       0, $nLen); 
	}

	public function Logout()
	{
		Controller::writeFile('Logged Out');
		session()->flush(); 
		return redirect('admin_panel');
	}
}
?>