<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Helper;
use App\Model\Parents;
use App\Model\Child;
use App\Model\Plan;
use App\Model\School;
use App\Model\Country;
use App\Model\RequestSchool;

class ParentController extends Controller
{
	public function __construct()
	{
		$this->Parents 	= new Parents;
		$this->Child 	= new Child;
		$this->Plan 	= new Plan;
		$this->School 	= new School;
		$this->Country 	= new Country;
		$this->RequestSchool	= new RequestSchool;
		//$this->Helper 	= new Helper;
	}

	public function IndexPage(Request $request)
	{ 
		$request->session()->forget('register_milkBar');
		$request->session()->forget('register_teacher');
		$aCntryLst	= $this->Country->FrntLst();
		$sTitle 	= "Parent Registreation";
		// $request->session()->forget('request_school');
    	$aData 		= compact('sTitle','aCntryLst');
        return view('parent_register',$aData);	
	}

	public function SaveCntrl(Request $request)
	{
		
		$rules = [
	        'sFrstName' 	=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sLstName' 		=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sEmailId' 		=> 'required|unique:mst_prnts,sEmail_Id|unique:mst_milk_bar,sEmail_Id|unique:mst_tchr,sEmail_Id|max:50|regex:^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^',
            'sMobileNo' 	=> 'required',
            'lRltnIdNo'		=> 'required',
            'lCntryIdNo'	=> 'required',
            'lStateIdNo'	=> 'required',
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
				$rules['sFrstName'.$i]	= 'required|min:2|max:15|regex:/^[\pL\s]+$/u';
 				$rules['sLstName'.$i]	= 'required|min:2|max:15|regex:/^[\pL\s]+$/u';
				$rules['sClsName'.$i] 	= 'required';
		    }
	    }
	    
		$this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
		{
		    $aHdArr 	= $this->HdArr($request);
		    \DB::beginTransaction();
		    	$lPrntIdNo	= $this->Parents->InsrtRecrd($aHdArr);
		    	if(!empty($lPrntIdNo) && $lPrntIdNo > 0)
		    	{ 
		    		$sPlnArr = $this->PlanArr($lPrntIdNo);
		    		$this->Plan->InsrtRecrd($sPlnArr);
		    		$i=1;
		    		for($i==1;$i<=$request['nTtlRec'];$i++)
		    		{
		    			if(!empty($request['nSchlType'.$i]) && !empty($request['lSchlIdNo'.$i]) && !empty($request['sFrstName'.$i]) && !empty($request['sLstName'.$i]) && !empty($request['sClsName'.$i]))
		    			{
		    				$aChldArr = $this->ChldArr($lPrntIdNo, $request['nSchlType'.$i], $request['lSchlIdNo'.$i], $request['sFrstName'.$i], $request['sLstName'.$i], $request['sClsName'.$i]);
							$this->Child->InsrtRecrd($aChldArr); 
		    			}
					}  
				
					if($request->session()->has('request_school'))
					{                                               
						$milkbarSchReq=$request->session()->get('request_school'); 
						foreach($milkbarSchReq as $key=>$se){
							$lSchlType=$se['lSchlTypes'];
							if(!empty($se['lSchlTypes']) && !empty($se['sSchlName']) && !empty($se['sSbrbName']) && !empty($se['sPinCode']))
							{
								$aReqSchlArr = $this->SchlReqArr($lPrntIdNo,$se['lSchlTypes'],$se['sSchlName'], $se['sSbrbName'], $se['sPinCode']);
								$this->RequestSchool->InsrtRecrd($aReqSchlArr);
								$request->session()->forget('request_school');
							}
						}
					}
				}
			\DB::commit();
			$aEmailData = ['sUserName' => $request['sFrstName'], 'sEmailId' => $request['sEmailId'], 'lRecIdNo' => $lPrntIdNo, 'nUserType' => config('constant.USER.PARENT')];
            Controller::SendEmail($request['sEmailId'], $request['sFrstName'], 'verification_email', 'Email Verification', $aEmailData);
			$request->session()->forget('request_school');
			return redirect('registration/parent')->with('Success', 'Your account created successfully, Please verify your email...');
				
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect()->back()->with('Failed', 'We have some technicial issue, please try again...');
		}
	}



	public function HdArr($request)
	{
		$aConArr = array(
			'sAcc_Id' 		=> strtoupper($request['sFrstName'][0].$request['sLstName'][0]."-".rand(1111,9999)),
			'sFrst_Name' 	=> $request['sFrstName'],
            'sLst_Name' 	=> $request['sLstName'],
            'sEmail_Id' 	=> $request['sEmailId'],
            'sCntry_Code'	=> $request['sCntryCode'],
            'sMobile_No' 	=> $request['sMobileNo'],
            'lRltn_IdNo'	=> $request['lRltnIdNo'],
            'lCntry_IdNo'	=> $request['lCntryIdNo'],
            'lState_IdNo'	=> $request['lStateIdNo'],
            'sSbrb_Name'	=> $request['sSbrbName'],
            'sPin_Code'		=> $request['sPinCode'],
            'sLgn_Pass'		=> md5($request['sLgnPass']),
            'nPln_Status'	=> config('constant.PRNT_PLN.FREE'),
            'nBlk_UnBlk'	=> config('constant.STATUS.UNBLOCK'),
            'nDel_Status'	=> config('constant.DEL_STATUS.UNDELETED'),
            'nEmail_Status'	=> config('constant.MAIL_STATUS.UNVERIFIED'),
		);
		return $aConArr;
	}

	public function PlanArr($lPrntIdNo)
	{
		$aConArr = array(
			'lPrnt_IdNo' 	=> $lPrntIdNo,
            'sStrt_Dt' 		=> date('Y-m-d'),
            'sEnd_Dt' 		=> date('Y-m-t'),
		);
		return $aConArr;
	}

	public function ChldArr($lPrntIdNo, $nSchlType, $lSchlIdNo, $sFrstName, $sLstName, $sClsName)
	{
		$aConArr = array(
			"sAcc_Id"		=> strtoupper($sFrstName[0].$sLstName[0]."-".rand(1111,9999)),
			"lPrnt_IdNo"	=> $lPrntIdNo,
			"nSchl_Type"	=> $nSchlType,
			"lSchl_IdNo"	=> $lSchlIdNo,
			"sFrst_Name"	=> $sFrstName,
			"sLst_Name"		=> $sLstName,
			"sCls_Name"		=> $sClsName, 
			'nBlk_UnBlk'	=> config('constant.STATUS.UNBLOCK'),
			'nDel_Status'	=> config('constant.DEL_STATUS.UNDELETED'),
		);
		return $aConArr;
	}

	public function SchlReqArr($lPrntIdNo, $nSchlType, $sSchlName, $sSbrbName, $sPinCode)
	{
		$aConArr = array(
			"lUser_IdNo"  	=> $lPrntIdNo,
			"nSchl_Type"	=> $nSchlType,
			"sSchl_Name"	=> $sSchlName,
			"sSbrb_Name"	=> $sSbrbName,
			"sPin_Code"		=> $sPinCode,
			"nUser_Type"	=> config('constant.USER.PARENT'),
			'nReq_Status'	=> config('constant.REQ_STATUS.Pending'),
			'nDel_Status'	=> config('constant.DEL_STATUS.UNDELETED'),
		);
		return $aConArr;
	}
}
?>