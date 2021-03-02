<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Helper;
use App\Model\Teacher;
use App\Model\School;
use App\Model\Country;
use App\Model\TeacherSchool;
use App\Model\RequestSchool;

class TeacherController extends Controller
{
	public function __construct()
	{
		$this->Teacher 			= new Teacher;
		$this->School 			= new School;
		$this->Country 			= new Country;
		$this->TeacherSchool 	= new TeacherSchool;
		$this->RequestSchool	= new RequestSchool;
	}

	public function IndexPage(Request $request)
	{
		$request->session()->forget('register_parent');
		$request->session()->forget('register_milkBar'); 
		$aCntryLst	= $this->Country->FrntLst();
		$sTitle 	= "Teacher Registreation";
    	$aData 		= compact('sTitle','aCntryLst');
        return view('teacher_register',$aData);	
	}

	public function SaveCntrl(Request $request)
	{
		
		$rules = [
	        'sFrstName' 	=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sLstName' 		=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sEmailId' 		=> 'required|unique:mst_tchr,sEmail_Id|unique:mst_prnts,sEmail_Id|unique:mst_milk_bar,sEmail_Id|max:50|regex:^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^',
            'sMobileNo' 	=> 'required',
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
	    	$rules['nSchlType1'] 	= 'required';
	    	$rules['lSchlIdNo1'] 	= 'required';
	    	$rules['sSbrbName1'] 	= 'required|required|min:3|max:20|regex:/^[\pL\s]+$/u';
	    	$rules['sPinCode1'] 	= 'required|digits:4';
	    	$rules['nRoleType1'] 	= 'required';
	    }
		$this->validate($request, $rules, config('constant.VLDT_MSG'));

		try
		{
		    $aHdArr 	= $this->HdArr($request);
		    \DB::beginTransaction();
		    	$lTchrIdNo	= $this->Teacher->InsrtRecrd($aHdArr);
		    	if(!empty($lTchrIdNo) && $lTchrIdNo > 0)
		    	{ 
					 $i=1;
					for($i==1;$i<=$request['nTtlRec'];$i++)
					{ 
						if(!empty($request['nSchlType'.$i]) && !empty($request['lSchlIdNo'.$i]) && !empty($request['sSbrbName'.$i]) && !empty($request['sPinCode'.$i]) && !empty($request['nRoleType'.$i]))
						{
							$aSchlArr = $this->SchlArr($lTchrIdNo, $request['nSchlType'.$i], $request['lSchlIdNo'.$i], $request['sSbrbName'.$i], $request['sPinCode'.$i], $request['nRoleType'.$i]);
							$this->TeacherSchool->InsrtRecrd($aSchlArr); 
						}
					}
					if($request->session()->has('request_school'))
					{                
						$milkbarSchReq=$request->session()->get('request_school'); 
						foreach($milkbarSchReq as $key=>$se){
							$lSchlType=$se['lSchlTypes']; 
							if(!empty($se['lSchlTypes']) && !empty($se['sSchlName']) && !empty($se['sSbrbName']))
							{
								$aReqSchlArr = $this->SchlReqArr($lTchrIdNo,$se['lSchlTypes'],$se['sSchlName'], $se['sSbrbName'], $se['sPinCode']);
								$insert=$this->RequestSchool->InsrtRecrd($aReqSchlArr);  
								$request->session()->forget('request_school');
							}
						}
					}   
				}
			\DB::commit();
			$aEmailData = ['sUserName' => $request['sFrstName'], 'sEmailId' => $request['sEmailId'], 'lRecIdNo' => $lTchrIdNo, 'nUserType' => config('constant.USER.TEACHER')];
            Controller::SendEmail($request['sEmailId'], $request['sFrstName'], 'verification_email', 'Email Verification', $aEmailData);
			$request->session()->forget('request_school');
			return redirect('registration/teacher')->with('Success', 'Your account created successfully, Please verify your email...');
				
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
            'lCntry_IdNo'	=> $request['lCntryIdNo'],
            'lState_IdNo'	=> $request['lStateIdNo'],
            'sSbrb_Name'	=> $request['sSbrbName'],
            'sPin_Code'		=> $request['sPinCode'],
            'sLgn_Pass'		=> md5($request['sLgnPass']),
            'nBlk_UnBlk'	=> config('constant.STATUS.UNBLOCK'),
            'nDel_Status'	=> config('constant.DEL_STATUS.UNDELETED'),
            'nEmail_Status'	=> config('constant.MAIL_STATUS.UNVERIFIED'),
		);
		return $aConArr;
	}

	public function SchlArr($lTchrIdNo, $nSchlType, $lSchlIdNo, $sSbrbName, $sPinCode, $nRoleType)
	{
		$aConArr = array(
			"lTchr_IdNo"	=> $lTchrIdNo,
			"nSchl_Type"	=> $nSchlType,
			"lSchl_IdNo"	=> $lSchlIdNo,
			"sSbrb_Name"	=> $sSbrbName,
			"sPin_Code"		=> $sPinCode,
			"nRole_Type"	=> $nRoleType, 
			'nBlk_UnBlk'	=> config('constant.STATUS.UNBLOCK'),
			'nDel_Status'	=> config('constant.DEL_STATUS.UNDELETED'),
		);
		return $aConArr;
	}

	public function SchlReqArr($lTchrIdNo, $nSchlType, $sSchlName, $sSbrbName, $sPinCode)
	{
		$aConArr = array(
			"lUser_IdNo"  	=> $lTchrIdNo,
			"nSchl_Type"	=> $nSchlType,
			"sSchl_Name"	=> $sSchlName,
			"sSbrb_Name"	=> $sSbrbName,
			"sPin_Code"		=> $sPinCode,
			"nUser_Type"	=> config('constant.USER.TEACHER'),
			'nReq_Status'	=> config('constant.REQ_STATUS.Pending'),
			'nDel_Status'	=> config('constant.DEL_STATUS.UNDELETED'),
		);
		return $aConArr;
	}
}
?>