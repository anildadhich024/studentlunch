<?php

namespace App\Http\Controllers\MilkBarController;
use App\Http\Controllers\Controller;
use MilkBarAuth;
use App\Model\OrderHd;
use App\Model\Wallet;
use App\Model\Item;
use Charts;

class HomeController extends Controller
{
	public function __construct()
	{
		$this->OrderHd 	= new OrderHd;
		$this->Wallet 	= new Wallet;
		$this->Item 	= new Item;
		$this->middleware(MilkBarAuth::class);
	}
	public function IndexPage()
	{
		$lMilkIdNo	= session('USER_ID');
		$aTtlOrd	= $this->OrderHd->CntOrd($lMilkIdNo);
		$aPndgOrd	= $this->OrderHd->CntOrd($lMilkIdNo, config('constant.ORDER_STATUS.Pending'), 'Pending');
		$aOverOrd	= $this->OrderHd->CntOrd($lMilkIdNo, config('constant.ORDER_STATUS.Pending'), 'Overdue');
		$aDlvrdOrd	= $this->OrderHd->CntOrd($lMilkIdNo, config('constant.ORDER_STATUS.Delivered'));
		$oEarning	= $this->OrderHd->GetMlkEarn($lMilkIdNo);
		$aAccSchl 	= $this->OrderHd->AccSchlLst($lMilkIdNo);
		$aItmLst 	= $this->Item->ItemLstTkt($lMilkIdNo);
		
		$aLbl		= array();
		$aValue		=array();
		foreach($oEarning as $key => $item){
			array_push($aLbl, config('constant.MONTH.'.$key));
			array_push($aValue, $item);
		}
		$aClrs = array('#42adfe', '#f89900', '#294986', '#e6231e', '#3d64a3');
		$chart = Charts::create('pie', 'highcharts')
					->title("Monthly Order Earning")
					->labels($aLbl)
					->colors($aClrs)
					->values($aValue)
					->dimensions(500, 500)
					->responsive(false);
		$aCrdtDtl	= $this->Wallet->MilkCrdt($lMilkIdNo, config('constant.TRANS.Credit'));
		$aDbtDtl	= $this->Wallet->MilkCrdt($lMilkIdNo, config('constant.TRANS.Debit'));
		$sTitle 	= "Welcome to Control Panel";
    	$aData 		= compact('sTitle','aTtlOrd','aPndgOrd','aDlvrdOrd','aCrdtDtl','aDbtDtl', 'chart', 'aLbl', 'aClrs', 'aValue','aAccSchl','aItmLst','aOverOrd');
        return view('milkbar_panel.dashboard',$aData);	
	}
}
?>