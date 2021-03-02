<?php

namespace App\Http\Controllers\ParentController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ParentAuth;
use App\Model\Parents;
use App\Model\OrderHd;
use App\Model\Child;
use App\Model\Plan;
use Charts;

class HomeController extends Controller
{
	public function __construct()
	{
		$this->Parents 	= new Parents;
		$this->OrderHd 	= new OrderHd;
		$this->Child 	= new Child;
		$this->Plan 	= new Plan;
		$this->middleware(ParentAuth::class);
	}
	public function IndexPage(Request $request)
	{
		$lPrntIdNo  = session('USER_ID');
		$aCntChld	= $this->Child->CntChld($lPrntIdNo);
		$aTtlOrd	= $this->OrderHd->CntOrdPrnt($lPrntIdNo,config('constant.USER.PARENT'));
		$aPndgOrd	= $this->OrderHd->CntOrdPrnt($lPrntIdNo,config('constant.USER.PARENT'),  config('constant.ORDER_STATUS.Pending'), 'Pending');
		$aOverOrd	= $this->OrderHd->CntOrdPrnt($lPrntIdNo,config('constant.USER.PARENT'),  config('constant.ORDER_STATUS.Pending'), 'Overdue');
		$aDlvrdOrd	= $this->OrderHd->CntOrdPrnt($lPrntIdNo,config('constant.USER.PARENT'),  config('constant.ORDER_STATUS.Delivered'));
		$aPrntsDtl 	= $this->Parents->PrntsDtl($lPrntIdNo);
		$oEarning	= $this->OrderHd->GetPrntCnt($lPrntIdNo, config('constant.USER.PARENT'));
		$oPlnDtl	= $this->Plan->PlnDtl($lPrntIdNo);
		
		$aLbl		= array();
		$aValue		=array();
		foreach($oEarning as $key => $item){
			array_push($aLbl, config('constant.MONTH.'.$key));
			array_push($aValue, $item);
		}
		$aClrs = array('#42adfe', '#f89900', '#294986', '#e6231e', '#3d64a3');
		$chart = Charts::create('pie', 'highcharts')
					->title("Monthly Orders")
					->labels($aLbl)
					->values($aValue)
					->dimensions(450, 450)
					->colors($aClrs)
					->responsive(false);
		$sTitle 	= "Welcome to Control Panel";
    	$aData 		= compact('sTitle','aPrntsDtl','chart', 'aLbl', 'aClrs', 'aValue','aTtlOrd','aPndgOrd','aDlvrdOrd','aCntChld','aOverOrd','oPlnDtl');
        return view('parent_panel.dashboard',$aData);	
	}
}
?>