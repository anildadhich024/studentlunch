<?php
namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SuperAdmin;
use App\Model\OrderHd;
use App\Model\OrderDetail;
use App\Model\School;
use App\Model\MilkBar;
use Excel;
 use App\Model\Company; 

class OrderController extends Controller
{
	public function __construct()
	{
		$this->Company 	= new Company; 
		$this->OrderHd 			= new OrderHd;
		$this->OrderDetail 		= new OrderDetail;
		$this->School 			= new School;
		$this->MilkBar 			= new MilkBar;
		$this->middleware(SuperAdmin::class);
	}

	public function ListPage(Request $request)
	{
		$sFrmDate  		= $request['sFrmDate'];
		$sToDate  		= $request['sToDate'];
		$lSchlIdNo  	= $request['lSchlIdNo'];
		$lMilkIdNo  	= $request['lMilkIdNo'];
		$aSchlLst		= $this->School->SchlOrdFltr();
		$aMilklLst		= $this->MilkBar->FltrMilkLst(config('constant.STATUS.UNBLOCK'));
		$oOrdLst		= $this->OrderHd->AdmnOrdLst($sFrmDate, $sToDate, $lMilkIdNo, $lSchlIdNo);
		$sTitle 		= "Manage Order";
    	$aData 			= compact('sTitle','oOrdLst','aSchlLst','aMilklLst','request');
        return view('admin_panel.order_list',$aData);	
	}
	
	public function ExprtRcrd(Request $request)
	{
		$sFrmDate  		= $request['sFrmDate'];
		$sToDate  		= $request['sToDate'];
		if(!empty($request['nRprtDur']))
		{
		    $nRprtDur       = $request['nRprtDur']-1;
			$sToDate  		= date('Y-m-d');
			$sFrmDate  		= date('Y-m-d', strtotime('-'.$nRprtDur.' days'));
		}
		$lSchlIdNo  	= $request['lSchlIdNo'];
		$lMilkIdNo  	= $request['lMilkIdNo'];
		$aOrdLst		= $this->OrderHd->ExlRcrdAdmn($sFrmDate, $sToDate, $lMilkIdNo);
		if(count($aOrdLst) > 0)
		{
			$FileName = 'Manage_Orders_'.date('Ymd').'_'.date('His');
	        Excel::create($FileName, function($excel) use ($aOrdLst) {
	            $excel->sheet('Sheet1', function($sheet)  use ($aOrdLst) {
	                $this->SetExlHeader($sheet, $lRaw);
	                $this->SetExlData($sheet, $lRaw, $aOrdLst);
	            });
	        })->download('xlsx');
	    }
	    else
	    {
        	return redirect()->back()->with('Failed', 'Record not found...');
	    }

	}

	public function SetExlHeader($sheet, &$lRaw)
	{
		$lRaw = 1;
		Controller::SetCell(config('excel.XL_ORD_ADMN.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'left', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.DEL_DATE'), $lRaw, 'Delivery Date', $sheet, '', '#F2DDDC', 'left', True, '', False, 12, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.ORD_NO'), $lRaw, 'Order No', $sheet, '', '#F2DDDC', 'left', True, '', False, 12, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.ORD_TYPE'), $lRaw, 'Order Type', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.STDNT_NAME'), $lRaw, 'User Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.SCHL_NAME'), $lRaw, 'School Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.MILK_NAME'), $lRaw, 'Service Provider Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.SUB_AMO'), $lRaw, 'Sub Total', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.GST_AMO'), $lRaw, 'GST', $sheet, '', '#F2DDDC', 'left', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.GRNT_AMO'), $lRaw, 'Grand Total', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.ORD_STATUS'), $lRaw, 'Status', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.TRAN_DATE'), $lRaw, 'Transaction Date', $sheet, '', '#F2DDDC', 'left', True, '', False, 15, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.ITM_NAME'), $lRaw, 'Item Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.ITM_PRC'), $lRaw, 'Item Price', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.ITM_QTY'), $lRaw, 'Quantity', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_ORD_ADMN.TTL_PRC'), $lRaw, 'Item Total', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
	}

	public function SetExlData($sheet, $lRaw, $aOrdLst)
	{
		$i = 0;
		while(isset($aOrdLst) && count($aOrdLst) > 0 && $i<count($aOrdLst))
		{
			$lRaw = $lRaw + 1;
			if($aOrdLst[$i]['nUser_Type'] == config('constant.USER.TEACHER'))
            {
                $sUserName = $aOrdLst[$i]['sTchr_FName'].' '.$aOrdLst[$i]['sTchr_LName'];
            }
            else if($aOrdLst[$i]['nUser_Type'] == config('constant.USER.CHILD'))
            {
                $sUserName = $aOrdLst[$i]['sChld_FName'].' '.$aOrdLst[$i]['sChld_LName'];
            }
            else
            {
                $sUserName = $aOrdLst[$i]['sPrnt_FName'].' '.$aOrdLst[$i]['sPrnt_LName'];
            }
			$aItmLst = $this->OrderDetail->ExlRcrd($aOrdLst[$i]['lOrder_IdNo']);
			$nMrgCell = count($aItmLst) > 1 ? count($aItmLst) - 1 : '';
			Controller::SetCell(config('excel.XL_ORD_ADMN.SR_NO'), $lRaw, $i+1, $sheet, config('excel.XL_ORD_ADMN.SR_NO'), '', 'right', False, '', False, 8, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD_ADMN.DEL_DATE'), $lRaw, date('d M, Y', strtotime($aOrdLst[$i]['sDelv_Date'])), $sheet, config('excel.XL_ORD_ADMN.DEL_DATE'), '', 'left', True, '', False, 12, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD_ADMN.ORD_NO'), $lRaw, $aOrdLst[$i]['sOrdr_Id'], $sheet, config('excel.XL_ORD_ADMN.ORD_NO'), '', 'left', True, '', False, 12, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD_ADMN.ORD_TYPE'), $lRaw, array_search($aOrdLst[$i]['nOrder_Type'], config('constant.ORD_TYPE')), $sheet, config('excel.XL_ORD_ADMN.ORD_TYPE'), '', 'left', False, '', False, 25, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD_ADMN.STDNT_NAME'), $lRaw, $sUserName, $sheet, config('excel.XL_ORD_ADMN.STDNT_NAME'), '', 'left', False, '', False, 25, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD_ADMN.SCHL_NAME'), $lRaw, $aOrdLst[$i]['sSchl_Name'], $sheet, config('excel.XL_ORD_ADMN.SCHL_NAME'), '', 'left', False, '', False, 25, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD_ADMN.MILK_NAME'), $lRaw, $aOrdLst[$i]['sBuss_Name'], $sheet, config('excel.XL_ORD_ADMN.MILK_NAME'), '', 'left', False, '', False, 25, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD_ADMN.SUB_AMO'), $lRaw, $aOrdLst[$i]['sSub_Ttl'], $sheet, config('excel.XL_ORD_ADMN.SUB_AMO'), '', 'right', False, '#0.00', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD_ADMN.GST_AMO'), $lRaw, $aOrdLst[$i]['sGst_Amo'], $sheet, config('excel.XL_ORD_ADMN.GST_AMO'), '', 'right', False, '#0.00', False, 8, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD_ADMN.GRNT_AMO'), $lRaw, $aOrdLst[$i]['sGrnd_Ttl'], $sheet, config('excel.XL_ORD_ADMN.GRNT_AMO'), '', 'right', False, '#0.00', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD_ADMN.ORD_STATUS'), $lRaw, strtoupper(array_search($aOrdLst[$i]['nOrdr_Status'], config('constant.ORDER_STATUS'))), $sheet, config('excel.XL_ORD_ADMN.ORD_STATUS'), '', 'center', True, '', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD_ADMN.TRAN_DATE'), $lRaw, date('d M, Y', strtotime($aOrdLst[$i]['sCrt_DtTm'])), $sheet, config('excel.XL_ORD_ADMN.TRAN_DATE'), '', 'left', False, '', False, 15, $nMrgCell, 10);

			$c = 0;
			while(isset($aItmLst) && count($aItmLst) > 0 && $c<count($aItmLst))
			{
				Controller::SetCell(config('excel.XL_ORD_ADMN.ITM_NAME'), $lRaw, $aItmLst[$c]['sItem_Name'], $sheet, '', '', 'left', False, '', False, 25, '', 10);
				Controller::SetCell(config('excel.XL_ORD_ADMN.ITM_PRC'), $lRaw, $aItmLst[$c]['sItem_Prc'], $sheet, '', '', 'right', False, '#0.00', False, 10, '', 10);
				Controller::SetCell(config('excel.XL_ORD_ADMN.ITM_QTY'), $lRaw, $aItmLst[$c]['nItm_Qty'], $sheet, '', '', 'right', False, '', False, 10, '', 10);
				Controller::SetCell(config('excel.XL_ORD_ADMN.TTL_PRC'), $lRaw, '='.Controller::GetColName(config('excel.XL_ORD_ADMN.ITM_PRC')).$lRaw.'*'.Controller::GetColName(config('excel.XL_ORD_ADMN.ITM_QTY')).$lRaw, $sheet, '', '', 'right', False, '#0.00', False, 10, '', 10);

				if($c==count($aItmLst)) 
				{
	        		break;	
	        	}
        		$c++;
				$lRaw = $lRaw + 1;	
			}
			$i++;
		}
	}
}
?>