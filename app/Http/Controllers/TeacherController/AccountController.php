<?php
namespace App\Http\Controllers\TeacherController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TeacherAuth;
use Validator;
use App\Model\Teacher;
use App\Model\Child;
use App\Model\RequestSchool;
use App\Model\School;
use App\Model\Country;
use App\Model\State;
use App\Model\TeacherSchool;

class AccountController extends Controller
{
	public function __construct()
	{
		$this->Teacher 			= new Teacher;
		$this->Child 			= new Child;
		$this->School 			= new School;
		$this->RequestSchool 	= new RequestSchool;
		$this->Country 			= new Country;
		$this->State 			= new State;
		$this->TeacherSchool 	= new TeacherSchool;
		$this->middleware(TeacherAuth::class);
	}

	public function IndexPage(Request $request)
	{
		$lTchrIdNo 	= session('USER_ID');
		$aTchrDtl 	= $this->Teacher->TchrDtl($lTchrIdNo);
		$aCntryLst	= $this->Country->FrntLst();
		$aSchlLst	= $this->School->SchlAll();
		$aAssSchl	= $this->TeacherSchool->SchlLst($lTchrIdNo);
		$aStateLst	= $this->State->FrntLst($aTchrDtl['lCntry_IdNo']);
		$sTitle 	= "Manage Account";
    	$aData 		= compact('sTitle','aTchrDtl','aCntryLst','aStateLst','aSchlLst','aAssSchl');
        return view('teacher_panel.manage_account',$aData);	
	}

	public function SaveCntrl(Request $request)
	{ 
		 
		$lTchrIdNo = session('USER_ID');   
		$rules = [ 
			
	        'sFrstName' 	=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sLstName' 		=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sMobileNo' 	=> 'required',
            'lCntryIdNo'	=> 'required',
            'lStateIdNo'	=> 'required',
            'sSbrbName'		=> 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'sPinCode'		=> 'required|digits:4',
            'nSchlType1'	=> 'required',
            'lSchlIdNo1'	=> 'required',
            'sSbrbName1'	=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sPinCode1'		=> 'required|digits:4',
            'nRoleType1'		=> 'required',
	    ];
	    $this->validate($request, $rules, config('constant.VLDT_MSG')); 
		try
		{
			 $aHdArr 	= $this->HdArr($request); 
				$nRow		= $this->Teacher->UpDtRecrd($aHdArr, $lTchrIdNo);  
		    	if(!empty($lTchrIdNo) && $lTchrIdNo > 0)
		    	{ 
					 $i=1;
					for($i==1;$i<=$request['nTtlRec'];$i++)
					{
						$aDelArr 	= $this->DelArr(); 
						$this->TeacherSchool->DelRecrd($aDelArr, $lTchrIdNo);
						if(!empty($request['nSchlType'.$i]) && !empty($request['lSchlIdNo'.$i]) && !empty($request['sSbrbName'.$i]) && !empty($request['sPinCode'.$i]) && !empty($request['nRoleType'.$i]))
						{
							$aSchlArr = $this->SchlArr($lTchrIdNo, $request['nSchlType'.$i], $request['lSchlIdNo'.$i], $request['sSbrbName'.$i], $request['sPinCode'.$i], $request['nRoleType'.$i]);
							if(isset($request['lTchrSchlIdNo'.$i]) && !empty($request['lTchrSchlIdNo'.$i]))
							{
								$exist=$this->TeacherSchool->UpDtRecrd($aSchlArr, $request['lTchrSchlIdNo'.$i]);
							}
							else
							{
								$exist=$this->TeacherSchool->InsrtRecrd($aSchlArr);
							} 
						}
					}

					
		    	}	 
			return redirect()->back()->with('Success', 'Account updated successfully...');
				
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect()->back()->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function TchSchCntrl(Request $request)
	{
		$lTchrIdNo = session('USER_ID');
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
			$this->RequestSchool->DelRecrd($aDelArr, $lTchrIdNo,config('constant.USER.TEACHER'));                                
			$milkbarSchReq=$request->session()->get('request_school'); 
			foreach($milkbarSchReq as $key=>$se){
				$lSchlType=$se['lSchlTypes']; 
				// && !empty($se['sPinCode'])
				if(!empty($se['lSchlTypes']) && !empty($se['sSchlName']) && !empty($se['sSbrbName']))
				{
					$aReqSchlArr = $this->SchlReqArr($lTchrIdNo,$se['lSchlTypes'],$se['sSchlName'], $se['sSbrbName'], $se['sPinCode']);
					$insert=$this->RequestSchool->InsrtRecrd($aReqSchlArr);  
					$request->session()->forget('request_school');
				}
			}
		} 
		return redirect()->back()->with('Success', 'School Requested successfully...');
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

	public function HdArr($request)
	{
		$aConArr = array(
			'sFrst_Name' 	=> $request['sFrstName'],
			'sLst_Name' 	=> $request['sLstName'],
			'sCntry_Code' 	=> $request['sCntryCode'],
            'sMobile_No' 	=> $request['sMobileNo'],
            'lCntry_IdNo'	=> $request['lCntryIdNo'],
            'lState_IdNo'	=> $request['lStateIdNo'],
            'sSbrb_Name'	=> $request['sSbrbName'],
			'sPin_Code'		=> $request['sPinCode'], 
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
        return view('teacher_panel.manage_password',$aData);	
	}

	public function PswrdCntrl(Request $request)
	{
		$lTchrIdNo 	= session('USER_ID');
		$rules = [
	        'sCurrPass'		=> 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
	        'sLgnPass'		=> 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'sCnfrmPass'	=> 'required|required_with:sLgnPass|same:sLgnPass',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
		{
			$yPassExist = $this->Teacher->IsPassExist($request['sCurrPass'], $lTchrIdNo);
			if(!$yPassExist)
			{
				return redirect()->back()->with('Failed', 'Current password did not matched...');
			}
			else
			{
			    $aPassArr 	= $this->PassArr($request['sCnfrmPass']);
	    		$nRow		= $this->Teacher->UpDtRecrd($aPassArr, $lTchrIdNo);
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
}
?>