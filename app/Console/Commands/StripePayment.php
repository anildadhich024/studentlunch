<?php
   
namespace App\Console\Commands;
   
use Illuminate\Console\Command;
use App\Model\Parents;
use App\Model\Plan;
use App\Model\CommPlan;
use Stripe;
use Mail;
   
class StripePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:payment';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monthly Subscription';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Parents      = new Parents;
        $this->CommPlan     = new CommPlan;
        $this->Plan         = new Plan;
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info("Cron is working fine!");
        $aGetPrnt = $this->Parents->StrpDtl();
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        foreach($aGetPrnt as $aRec) 
        {
            try
            {
                $aPlnDtl    = $this->CommPlan->AplyDtl($aRec['lCntry_IdNo'], $aRec['lState_IdNo']);
                $sPlnAmo    = $aPlnDtl['sPrnt_Amo'];    
                if(date('d') > 1)
                {
                    $sPlnAmo = number_format(($aPlnDtl['sPrnt_Amo'] / date('t', strtotime(date('Y-m-d')))) * (date('t', strtotime(date('Y-m-d'))) - date('d', strtotime(date('Y-m-d'))) + 1), 2);
                }

                $sFrstName = $aRec['sFrst_Name'];
                $sEmailId  = $aRec['sEmail_Id'];
                $oPayData   = \Stripe\PaymentIntent::create([
                                'amount'        => $sPlnAmo*100,
                                'currency'      => $aRec['sCurr_Code'],
                                'customer'      => $aRec['sStrp_CustId'],
                                'payment_method'=> $aRec['sStrp_CardId'],
                                'description'   => 'PAYMENT FOR '.strtoupper(date('F')).' SUBSCRIPTION ('.$aRec['sAcc_Id'].')',
                                'off_session'   => true,
                                'confirm'       => true,
                            ]);

                if(isset($oPayData) && $oPayData->status == 'succeeded')
                {
                    $sPlnArr = $this->PlanArr($aRec['lPrnt_IdNo'], $oPayData->id);
                    $this->Plan->InsrtRecrd($sPlnArr);
                    $aEmailData = ['sUserName' => $sFrstName, 'sTxnId' => $aChrdAmo->id];
                    $this->SendEmail($sEmailId, $sFrstName, 'success_payment_email', 'Payment success for MyLunchOrder '.date('M-Y'), $aEmailData);
                }
            }
            catch(\Exception $e)
            {
                $aEmailData = ['sUserName' => $sFrstName, 'dPlanAmo' => number_format($sPlnAmo, 2)];
            	$this->SendEmail($sEmailId, $sFrstName, 'failed_payment_email', 'URGENT ACTION REQUIRED  Subscription Amount Due, Payment Attempt', $aEmailData);
            }
        }
    }

    public function PlanArr($lPrntIdNo, $sPymntId)
    {
        $aConArr = array(
            'lPrnt_IdNo'    => $lPrntIdNo,
            'sStrp_Id'      => $sPymntId,
            'sStrt_Dt'      => date('Y-m-d'),
            'sEnd_Dt'       => date('Y-m-t'),
        );
        return $aConArr;
    }

    public function SendEmail($sEmailId, $sName, $sEmailTamplt, $sEmailSubject, $aEmailData)
    {
        $_REQUEST['sEmilId'] = $sEmailId;
        $_REQUEST['sName'] = $sName;
        $_REQUEST['sEmailSubject'] = $sEmailSubject;
        Mail::send('email_tamplates/'.$sEmailTamplt, $aEmailData, function($message) {
            $message->to($_REQUEST['sEmilId'], $_REQUEST['sName'])->subject($_REQUEST['sEmailSubject']);
            $message->from('studentlunch@i4dev.in','Student Lunch');
        });
    }
}