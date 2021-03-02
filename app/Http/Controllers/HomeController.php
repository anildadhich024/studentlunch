<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Model\Setting;


class HomeController extends Controller
{
	public function __construct()
	{
		$this->Setting 	= new Setting; 
	}

	public function IndexPage()
	{
		session_unset();
		$sTitle = "Welcome";
    	$aData 	= compact('sTitle');
        return view('welcome',$aData);	
	}

	public function PageTrms()
	{
		$sTitle = "Terms & Conditions";
		$StngDtl 	= $this->Setting->StngDtl();
    	$aData 	= compact('sTitle','StngDtl');
        return view('terms-conditions',$aData);	
	}

	public function PagePrvcy()
	{
		$sTitle = "Privacy Policy";
		$StngDtl 	= $this->Setting->StngDtl();
    	$aData 	= compact('sTitle','StngDtl');
        return view('privacy-policy',$aData);	
	}
}
?>