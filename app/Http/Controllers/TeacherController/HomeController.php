<?php

namespace App\Http\Controllers\TeacherController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TeacherAuth;
use App\Model\Teacher;
use App\Model\OrderHd;
use Charts;

class HomeController extends Controller
{
	public function __construct()
	{
		$this->Teacher 	= new Teacher;
		$this->OrderHd 	= new OrderHd;
		$this->middleware(TeacherAuth::class);
	}

	public function IndexPage(Request $request)
	{
		$lTchrIdNo  = session('USER_ID');
		$aTtlOrd	= $this->OrderHd->CntOrdPrnt($lTchrIdNo, config('constant.USER.TEACHER'));
		$aPndgOrd	= $this->OrderHd->CntOrdPrnt($lTchrIdNo, config('constant.USER.TEACHER'), config('constant.ORDER_STATUS.Pending'), 'Pending');
		$aOverOrd	= $this->OrderHd->CntOrdPrnt($lTchrIdNo, config('constant.USER.TEACHER'), config('constant.ORDER_STATUS.Pending'), 'Overdue');
		$aDlvrdOrd	= $this->OrderHd->CntOrdPrnt($lTchrIdNo, config('constant.USER.TEACHER'), config('constant.ORDER_STATUS.Delivered'));
		$aTchrDtl 	= $this->Teacher->TchrDtl($lTchrIdNo);
		$oEarning	= $this->OrderHd->GetPrntCnt($lTchrIdNo, config('constant.USER.TEACHER'));
		
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
    	$aData 		= compact('sTitle','aTchrDtl','chart', 'aLbl', 'aClrs', 'aValue','aTtlOrd','aPndgOrd','aDlvrdOrd','aOverOrd');
        return view('teacher_panel.dashboard',$aData);	
	}
}
?>