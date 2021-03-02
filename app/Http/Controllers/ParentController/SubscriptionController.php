<?php
namespace App\Http\Controllers\ParentController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ParentAuth;
use Validator;
use App\Model\Parents;
use App\Model\CommPlan;
use App\Model\Plan;
use Stripe;

class SubscriptionController extends Controller
{
	public function __construct()
	{
		$this->Parents 	= new Parents;
		$this->CommPlan = new CommPlan;
		$this->Plan 	= new Plan;
		$this->middleware(ParentAuth::class);
	}

	public function IndexPage(Request $request)
	{
		$lPrntIdNo 	= session('USER_ID');
		$aPrntsDtl	= $this->Parents->LocDtl($lPrntIdNo);
		/*Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
		$oCardLst = \Stripe\Customer::allSources(
					  	$aPrntsDtl['sStrp_CustId'],
					  	['object' => 'card', 'limit' => 20]
					);*/
		$sTitle 	= "Manage Account";
    	$aData 		= compact('sTitle','aPrntsDtl');
        return view('parent_panel.subscription_view',$aData);	
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
				return redirect()->back()->with('Success', 'Your account will continue with free services..');
			}
			else
			{
				return redirect()->back()->with('Failed', 'We have some technicial issues, Please try again...');
			}
		}
		catch(Exception $e)
		{
			return redirect()->back()->with('Failed', $e->getMessage());
		}
	}

	public function PaidPln(Request $request)
	{
		try
		{
			$lPrntIdNo	= session('USER_ID');
			Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
			$aPrntsDtl	= $this->Parents->LocDtl($lPrntIdNo);
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
			if(empty($aPrntsDtl['sStrp_CustId']))
			{
				$oCustObj = \Stripe\Customer::create(
					array(
						'name' 	=> $aPrntsDtl['sFrst_Name']." ".$aPrntsDtl['sLst_Name'], 
						'email' => strip_tags(trim($aPrntsDtl['sEmail_Id'])),
						'description' => $aPrntsDtl['sAcc_Id'],
						"address" => ["city" => $aPrntsDtl['sSbrb_Name'], "country" => $aPrntsDtl['sCntry_Name'], "line1" => $aPrntsDtl['sSbrb_Name'], "postal_code" => $aPrntsDtl['sPin_Code'], "state" => $aPrntsDtl['sState_Name']]
					)
				);
				$sCustId 	= $oCustObj->id;
			}
			else
			{
				$sCustId 	= $aPrntsDtl['sStrp_CustId'];
			}

			$oCardObj = \Stripe\Customer::createSource(
							$sCustId,
							array(
								'source' 	=> $oTknObj->id,
							)
						);

			\Stripe\Customer::update($sCustId, [
		        'default_source' => $oCardObj->id
		    ]);

			if(!empty($sCustId) || isset($oCardObj->id))
			{	
				$aStrpArr	= $this->aStrpArr($sCustId, $oCardObj->id);
				$nRow		= $this->Parents->UpDtRecrd($aStrpArr, $lPrntIdNo);
				$aRec = array(
					"Status"	=> True,
					"Message"	=> 'Thank you for support...'
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