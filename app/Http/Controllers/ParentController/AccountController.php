<?php
namespace App\Http\Controllers\ParentController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ParentAuth;
use Validator;
use App\Model\Parents;
use App\Model\Child;
use App\Model\School;
use App\Model\Country;
use App\Model\State;
use App\Model\RequestSchool;
use App\Model\CommPlan;
use App\Model\Plan;
use Stripe;

class AccountController extends Controller
{
	public function __construct()
	{
		$this->Parents 	= new Parents;
		$this->Child 	= new Child;
		$this->School 	= new School;
		$this->Country 	= new Country;
		$this->RequestSchool = new RequestSchool;
		$this->State 	= new State;
		$this->CommPlan = new CommPlan;
		$this->Plan 	= new Plan;
		$this->middleware(ParentAuth::class);
	}

	public function IndexPage(Request $request)
	{
		$lPrntIdNo 	= session('USER_ID');
		$aPrntsDtl 	= $this->Parents->PrntsDtl($lPrntIdNo);
		$aCntryLst	= $this->Country->FrntLst();
		$aSchlLst	= $this->School->SchlAll();
		$aChldLst	= $this->Child->ChldLst($lPrntIdNo);
		$aStateLst	= $this->State->FrntLst($aPrntsDtl['lCntry_IdNo']);
		$sTitle 	= "Manage Account";
    	$aData 		= compact('sTitle','aPrntsDtl','aCntryLst','aStateLst','aSchlLst','aChldLst');
        return view('parent_panel.manage_account',$aData);	
	}

	public function SaveCntrl(Request $request)
	{
		$lPrntIdNo = session('USER_ID');
		$rules = [
	        'sFrstName' 	=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sLstName' 		=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sMobileNo' 	=> 'required',
            'lRltnIdNo'		=> 'required',
            'lCntryIdNo'	=> 'required',
            'lStateIdNo'	=> 'required',
            'sSbrbName'		=> 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'sPinCode'		=> 'required|digits:4',
            'nSchlType1'	=> 'required',
            'lSchlIdNo1'	=> 'required',
            'sFrstName1'	=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sLstName1'		=> 'required|min:2|max:15|regex:/^[\pL\s]+$/u',
            'sClsName1'		=> 'required',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));
		try
		{
		    \DB::beginTransaction();
		    	$aHdArr 	= $this->HdArr($request);
		    	$nRow		= $this->Parents->UpDtRecrd($aHdArr, $lPrntIdNo);
		    	$aDelArr 	= $this->DelArr(); 
		    	$this->Child->DelRecrd($aDelArr, $lPrntIdNo);
		    	if((!empty($lPrntIdNo) && $lPrntIdNo > 0))
		    	{
		    		$i=1;
		    		for($i==1;$i<=$request['nTtlRec'];$i++)
		    		{
		    			if(!empty($request['nSchlType'.$i]) && !empty($request['lSchlIdNo'.$i]) && !empty($request['sFrstName'.$i]) && !empty($request['sLstName'.$i]) && !empty($request['sClsName'.$i]))
		    			{
		    				$aChldArr = $this->ChldArr($lPrntIdNo, $request['nSchlType'.$i], $request['lSchlIdNo'.$i], $request['sFrstName'.$i], $request['sLstName'.$i], $request['sClsName'.$i]);
		    				if(isset($request['lChldIdNo'.$i]) && !empty($request['lChldIdNo'.$i]))
		    				{
								$this->Child->UpDtRecrd($aChldArr, $request['lChldIdNo'.$i]);
		    				}
		    				else
		    				{
								$this->Child->InsrtRecrd($aChldArr);
		    				}
		    			}
					}
		    	}	
			\DB::commit();
		    return redirect()->back()->with('Success', 'Account updated successfully...');
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect()->back()->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function ParentSchCntrl(Request $request)
	{
		$lPrntIdNo = session('USER_ID');
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
			$this->RequestSchool->DelRecrd($aDelArr, $lPrntIdNo,config('constant.USER.PARENT'));                                                             
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
		return redirect()->back()->with('Success', 'School Requested successfully...');
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

	public function HdArr($request)
	{
		$aConArr = array(
			'sFrst_Name' 	=> $request['sFrstName'],
			'sLst_Name' 	=> $request['sLstName'],
			'sCntry_Code' 	=> $request['sCntryCode'],
            'sMobile_No' 	=> $request['sMobileNo'],
            'lRltn_IdNo'	=> $request['lRltnIdNo'],
            'lCntry_IdNo'	=> $request['lCntryIdNo'],
            'lState_IdNo'	=> $request['lStateIdNo'],
            'sSbrb_Name'	=> $request['sSbrbName'],
            'sPin_Code'		=> $request['sPinCode'],
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
			'nDel_Status' 	=> config('constant.DEL_STATUS.UNDELETED'),
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
        return view('parent_panel.manage_password',$aData);	
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
			$yPassExist = $this->Parents->IsPassExist($request['sCurrPass'], $lPrntIdNo);
			if(!$yPassExist)
			{
				return redirect()->back()->with('Failed', 'Current password did not matched...');
			}
			else
			{
			    $aPassArr 	= $this->PassArr($request['sCnfrmPass']);
	    		$nRow		= $this->Parents->UpDtRecrd($aPassArr, $lPrntIdNo);
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

	public function PayPlan(Request $request)
	{
		$lPrntIdNo 	= session('USER_ID');
		$aLocDtl 	= $this->Parents->LocDtl($lPrntIdNo);
		$aStngDtl 	= $this->CommPlan->AplyDtl($aLocDtl['lCntry_IdNo'], $aLocDtl['lState_IdNo']);
		$aPlnDtl 	= $this->Plan->PlnDtl($lPrntIdNo);
		$sStrtDt = date('Y-m-d');
		if(isset($aPlnDtl))
		{
			if($aPlnDtl->sEnd_Dt >= date('Y-m-d') && isset($aPlnDtl))
			{
				$sStrtDt	= date('Y-m-d', strtotime($aPlnDtl->sEnd_Dt. ' +1 day'));
			}
		}
		
		Stripe\Stripe::setApiKey(env('STRIPE_SECRET')); 

		$aPlnAmo = number_format(($aStngDtl['sPrnt_Amo'] / date('t', strtotime($sStrtDt))) * (date('t', strtotime($sStrtDt)) - date('d', strtotime($sStrtDt)) + 1), 2);

		if(!empty($request->checkoutSession))
		{ 
    		// Create new Checkout Session for the order 
		    try 
		    { 
		        $session = \Stripe\Checkout\Session::create([ 
		            'payment_method_types' => ['card'], 
		            'line_items' => [[ 
		                'price_data' => [ 
		                    'product_data' => [ 
		                        'name' => 'Monthly subscription charge', 
		                    ], 
		                    'unit_amount' => $aPlnAmo * 100, 
		                    'currency' => 'AUD', 
		                ], 
		                'quantity' => 1, 
		                'description' => 'Monthly subscription charge', 
		            ]], 
		            'mode' => 'payment', 
		            'success_url' => url('parent_panel/purchases/confirm').'?sChkId={CHECKOUT_SESSION_ID}', 
		            'cancel_url' => url('parent_panel/purchases/confirm'), 
		        ]); 
		    }
		    catch(Exception $e) 
		    {  
		        $api_error = $e->getMessage();  
		    } 
		     
		    if(empty($api_error) && $session)
		    { 
		        $response = array( 
		            'status' => 1, 
		            'message' => 'Checkout Session created successfully!', 
		            'sessionId' => $session['id'] 
		        ); 
		    }
		    else
		    { 
		        $response = array( 
		            'status' => 0, 
		            'error' => array( 
		                'message' => 'Checkout Session creation failed! '.$api_error    
		            ) 
		        ); 
		    } 
		} 
		 
		// Return response 
		echo json_encode($response);
	}

	public function PayConfirm(Request $request)
	{
		$sSessnId 		= $request['sChkId'];
		$sPayStatus 	= NULL;
		try
		{
			if(isset($sSessnId) && !empty($sSessnId))
			{
				Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
				$oChkData 		= \Stripe\Checkout\Session::retrieve($sSessnId);
				$oPymntData 	= \Stripe\PaymentIntent::retrieve($oChkData->payment_intent);
				$sPayStatus 	= $oPymntData->status;
				if($sPayStatus == 'succeeded') 
				{
					$lPrntIdNo 	= session('USER_ID');
					$aPlnDtl 	= $this->Plan->PlnDtl($lPrntIdNo);
					$sStrtDt = date('Y-m-d');
					if(isset($aPlnDtl))
					{
						if($aPlnDtl->sEnd_Dt >= date('Y-m-d') && isset($aPlnDtl))
						{
							$sStrtDt	= date('Y-m-d', strtotime($aPlnDtl->sEnd_Dt. ' +1 day'));
						}
					}
					$sPlnArr = $this->PlanArr($lPrntIdNo, $oChkData->payment_intent, $sStrtDt);
		    		$this->Plan->InsrtRecrd($sPlnArr);
				}
			}
			$sTitle  	       = "Payment Confirmation";
	        $aData  	       = compact('sTitle','sPayStatus');
	        return view('parent_panel.payment_confirm',$aData);
		}
		catch(Exception $e)
		{
			return redirect('parent_panel')->with('Failed', $e->getMessage());
		}
	}

	public function PlanArr($lPrntIdNo, $sPymntId, $sStrtTm)
	{
		$aConArr = array(
			'lPrnt_IdNo' 	=> $lPrntIdNo,
			'sStrp_Id' 		=> $sPymntId,
            'sStrt_Dt' 		=> $sStrtTm,
            'sEnd_Dt' 		=> date('Y-m-t', strtotime($sStrtTm)),
		);
		return $aConArr;
	}

	public function FreePln(Request $request)
	{
		try
		{
			$lPrntIdNo 		= session('USER_ID');
			$aPlnStatusArr 	= $this->aPlnStatusArr();
			$nRow			= $this->Parents->UpDtRecrd($aPlnStatusArr, $lPrntIdNo);
			if($nRow > 0)
			{
				return redirect('parent_panel')->with('Success', 'Your account will continue with free services..');
			}
			else
			{
				return redirect('parent_panel')->with('Failed', 'We have some technicial issues, Please try again...');
			}
		}
		catch(Exception $e)
		{
			return redirect('parent_panel')->with('Failed', $e->getMessage());
		}
	}

	public function PaidPln(Request $request)
	{
		try
		{
			Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
			$oTknObj = \Stripe\Token::create([
			  'card' => [
			    'name' 		=> $request['sCardHolder'],
			    'number' 	=> $request['sCardNumber'],
			    'exp_month' => $request['sExpMnth'],
			    'exp_year' 	=> $request['sExpYear'],
			    'cvc' 		=> $request['sCvcCode'],
			  ],
			]);

			$lPrntIdNo 	= session('USER_ID');
			$aPrntsDtl	= $this->Parents->PrntsDtl($lPrntIdNo);

			$oCustObj = \Stripe\Customer::create(
				array(
					'card'	=> $oTknObj->id,
					'name' 	=> $aPrntsDtl['sFrst_Name']." ".$aPrntsDtl['sLst_Name'], 
					'email' => strip_tags(trim($aPrntsDtl['sEmail_Id'])),
				)
			);

			if(isset($oCustObj) && !empty($oCustObj->default_source))
			{
				$aStrpArr	= $this->aStrpArr($oCustObj->id, $oCustObj->default_source);
				$nRow		= $this->Parents->UpDtRecrd($aStrpArr, $lPrntIdNo);
				$aRec = array(
					"Status"	=> True,
					"Message"	=> 'We will auto debit your account on 1st of eveymonth...'
				);
			}
			else
			{
				$aRec = array(
					"Status"	=> False,
					"Message"	=> 'Could not found card details, Please try again...'
				);
			}
		}
		catch(\Exception $e)
		{
			$aRec = array(
				"Status"	=> False,
				"Message"	=> $e->getMessage()
			);
		}
		return json_encode($aRec, JSON_PRETTY_PRINT);
	}

	public function aPlnStatusArr()
	{
		$aConArr = array(
			'nPln_Status' 	=> config('constant.PRNT_PLN.FREE'),
		);
		return $aConArr;
	}

	public function aStrpArr($sStrpCustId, $sStrpCardId)
	{
		$aConArr = array(
			'sStrp_CustId'	=> $sStrpCustId,
			'sStrp_CardId'	=> $sStrpCardId,
			'nPln_Status' 	=> config('constant.PRNT_PLN.PAID'),
		);
		return $aConArr;
	}
}
?>