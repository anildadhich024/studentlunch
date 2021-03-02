<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Model\MilkBar;
use App\Model\School;
use App\Model\AssociateSchool;
use App\Model\Country;
use App\Model\RequestSchool;

class MilkbarController extends Controller
{
	public function __construct()
	{
		$this->MilkBar 			= new MilkBar;
		$this->School 			= new School;
		$this->AssociateSchool 	= new AssociateSchool;
		$this->Country 			= new Country;
		$this->RequestSchool	= new RequestSchool;
	}

	public function IndexPage(Request $request)
	{		
		$aSchlLst	= $this->School->RegSchlLst();
		$aCntryLst	= $this->Country->FrntLst();
		$sTitle 	= "Milk Bar Registreation";
    	$aData 		= compact('sTitle','aSchlLst','aCntryLst');
        return view('milk_bar_register',$aData);	
	}

	
	public function SaveCntrl(Request $request)
	{
		$rules = [
	        'sBussName' 	=> 'required|min:5|max:50|regex:/^[\pL\s]+$/u',
            'nBussType' 	=> 'required',
            'sAbnNo' 		=> 'required|unique:mst_milk_bar,sAbn_No',
            'sFrstName' 	=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sLstName' 		=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sMobileNo' 	=> 'required',
            'sAreaCode' 	=> 'required|digits:1',
            'sPhoneNo' 		=> 'required',
            'sEmailId' 		=> 'required|unique:mst_milk_bar,sEmail_Id|unique:mst_prnts,sEmail_Id|unique:mst_tchr,sEmail_Id|max:50|regex:^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^',
            'sStrtNo'		=> 'required|max:5',
            'lCntryIdNo'	=> 'required',
            'lStateIdNo'	=> 'required',
            'sStrtName'		=> 'required|min:5|max:50|regex:/^[\pL\s]+$/u',
            'sSbrbName'		=> 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'sPinCode'		=> 'required|digits:4',
            'sLgnPass'		=> 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'sCnfrmPass'	=> 'required|required_with:sLgnPass|same:sLgnPass',
            'nTerms'		=> 'accepted',
	    ];

	    if(!$request->session()->has('request_school'))
	    {
	    	$i = 1;
	    	for($i==1;$i<=$request['nTtlRec'];$i++)
			{
		    	$rules['nSchlType'.$i]	= 'required';
				$rules['lSchlIdNo'.$i]	= 'required';
				$rules['dDistKm'.$i]	= 'required';
 				$rules['sSbrbName'.$i]	= 'required|min:2|max:15|regex:/^[\pL\s]+$/u';
				$rules['sPinCode'.$i] 	= 'required|digits:4';
				$rules['sCutTm'.$i] 	= 'required';
		    }
	    }
					
	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
		{
		    $aHdArr 	= $this->HdArr($request);
		    \DB::beginTransaction();
		    	$lMilkIdNo	= $this->MilkBar->InsrtRecrd($aHdArr);
		    	if(!empty($lMilkIdNo) && $lMilkIdNo > 0)
		    	{
		    		$i=1;
		    		for($i==1;$i<=$request['nTtlRec'];$i++)
		    		{
		    			if(!empty($request['nSchlType'.$i]) && !empty($request['lSchlIdNo'.$i]) && !empty($request['dDistKm'.$i]) && !empty($request['sSbrbName'.$i]) && !empty($request['sPinCode'.$i]) && !empty($request['sCutTm'.$i]))
		    			{
		    				$aSchlArr = $this->SchlArr($lMilkIdNo, $request['nSchlType'.$i], $request['lSchlIdNo'.$i], $request['dDistKm'.$i], $request['sSbrbName'.$i], $request['sPinCode'.$i], $request['sCutTm'.$i]);
		    				$this->AssociateSchool->InsrtRecrd($aSchlArr);
		    			}
		    		} 

					if($request->session()->has('request_school'))
					{                                               
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
				}
			\DB::commit();
			$aEmailData = ['sUserName' => $request['sFrstName'], 'sEmailId' => $request['sEmailId'], 'lRecIdNo' => $lMilkIdNo, 'nUserType' => config('constant.USER.MILK_BAR')];
            Controller::SendEmail($request['sEmailId'], $request['sFrstName'], 'verification_email', 'Email Verification', $aEmailData);
			$request->session()->forget('request_school');
			return redirect()->back()->with('Success', 'Your account created successfully, Please verify your email...');
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect()->back()->with('Failed', $e->getMessage());
		}
	}

	public function HdArr($request)
	{
		$aConArr = array(
			'sAcc_Id' 		=> strtoupper(substr($request['sBussName'], 0, 2)."-".rand(1111,9999)),
			'sBuss_Name' 	=> $request['sBussName'],
            'nBuss_Type' 	=> $request['nBussType'],
            'sAbn_No' 		=> $request['sAbnNo'],
            'sFrst_Name' 	=> $request['sFrstName'],
            'sLst_Name'		=> $request['sLstName'],
            'sCntry_Code'	=> $request['sCntryCode'],
            'sArea_Code'	=> $request['sAreaCode'],
            'sPhone_No'		=> $request['sPhoneNo'],
            'sMobile_No'	=> $request['sMobileNo'],
            'sEmail_Id'		=> $request['sEmailId'],
            'sStrt_No'		=> $request['sStrtNo'],
            'sStrt_Name'	=> $request['sStrtName'],
            'sSbrb_Name'	=> $request['sSbrbName'],
            'lCntry_IdNo'	=> $request['lCntryIdNo'],
            'lState_IdNo'	=> $request['lStateIdNo'],
            'sPin_Code'		=> $request['sPinCode'],
            'sLgn_Pass'		=> md5($request['sLgnPass']),
            'nBlk_UnBlk'	=> config('constant.STATUS.UNBLOCK'),
            'nDel_Status'	=> config('constant.DEL_STATUS.UNDELETED'),
            'nAdmin_Status'	=> config('constant.MLK_STATUS.UNACTIVE'),
            'nEmail_Status'	=> config('constant.MAIL_STATUS.UNVERIFIED'),
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
}
?>