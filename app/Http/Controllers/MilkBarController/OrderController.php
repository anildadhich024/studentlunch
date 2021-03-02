<?php
namespace App\Http\Controllers\MilkbarController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MilkBarAuth;
use App\Model\OrderHd;
use App\Model\OrderDetail;
use Excel;

class OrderController extends Controller
{
	public function __construct()
	{
		$this->OrderHd 			= new OrderHd;
		$this->OrderDetail 		= new OrderDetail;
		$this->middleware(MilkBarAuth::class);
	}

	public function ListPage(Request $request)
	{
		$lMilkIdNo 		= session('USER_ID');
		$sDelvDate  	= $request['sDelvDate'];
		$lSchlIdNo  	= $request['lSchlIdNo'];
		$nOrdrStatus  	= $request['nOrdrStatus'];
		$aAccSchl 		= $this->OrderHd->AccSchlLst($lMilkIdNo);
		$oOrdLst		= $this->OrderHd->MilkOrdLst($lMilkIdNo, $sDelvDate, $lSchlIdNo, $nOrdrStatus);
		$sTitle 		= "Manage Order";
    	$aData 			= compact('sTitle','aAccSchl','oOrdLst','request');
        return view('milkbar_panel.order_list',$aData);	
	}
	
	public function ExprtRcrd(Request $request)
	{
		$lMilkIdNo 		= session('USER_ID');
		$sFrmDate = $sToDate = '';
		$sDelvDate  	= $request['sDelvDate'];
		if(!empty($sDelvDate))
		{
			$sFrmDate  		= $sDelvDate;
			$sToDate  		= $sDelvDate;
		}
		else
		{
			$sFrmDate  		= $request['sFrmDate'];
			$sToDate  		= $request['sToDate'];
			if(!empty($sFrmDate) && !empty($sToDate))
			{
    		    $sFrmDate  	= date('Y-m-d', strtotime($request['sFrmDate']));
    		    $sToDate  	= date('Y-m-d', strtotime($request['sToDate']));
			}
			
			if(!empty($request['nRprtDur']))
			{
			    $nRprtDur   = $request['nRprtDur']-1;
    		    $sToDate  	= date('Y-m-d');
    		    $sFrmDate  	= date('Y-m-d', strtotime('-'.$nRprtDur.' days'));
			}
		}
		$lSchlIdNo  	= $request['lSchlIdNo'];
		$nOrdrStatus  	= $request['nOrdrStatus'];
		$aOrdLst		= $this->OrderHd->ExlRcrd($lMilkIdNo, $sFrmDate, $sToDate, $lSchlIdNo, $nOrdrStatus);
		if(count($aOrdLst) > 0)
		{
			$FileName = 'My_Orders_'.date('Ymd').'_'.date('His');
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
		Controller::SetCell(config('excel.XL_ORD.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'left', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_ORD.DEL_DATE'), $lRaw, 'Delivery Date', $sheet, '', '#F2DDDC', 'left', True, '', False, 12, '', 10);
		Controller::SetCell(config('excel.XL_ORD.ORD_NO'), $lRaw, 'Order No', $sheet, '', '#F2DDDC', 'left', True, '', False, 12, '', 10);
		Controller::SetCell(config('excel.XL_ORD.PRNT_NAME'), $lRaw, 'Order Type', $sheet, '', '#F2DDDC', 'left', True, '', False, 12, '', 10);
		Controller::SetCell(config('excel.XL_ORD.STDNT_NAME'), $lRaw, 'Student Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_ORD.SCHL_NAME'), $lRaw, 'School Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_ORD.SUB_AMO'), $lRaw, 'Sub Total', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_ORD.GST_AMO'), $lRaw, 'GST', $sheet, '', '#F2DDDC', 'left', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_ORD.GRNT_AMO'), $lRaw, 'Grand Total', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_ORD.ORD_STATUS'), $lRaw, 'Status', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_ORD.ITM_NAME'), $lRaw, 'Item Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Controller::SetCell(config('excel.XL_ORD.ITM_PRC'), $lRaw, 'Item Price', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_ORD.ITM_QTY'), $lRaw, 'Quantity', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_ORD.TTL_PRC'), $lRaw, 'Item Total', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
	}

	public function SetExlData($sheet, $lRaw, $aOrdLst)
	{
		$i = 0;
		while(isset($aOrdLst) && count($aOrdLst) > 0 && $i<count($aOrdLst))
		{
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
			$lRaw = $lRaw + 1;
			$aItmLst = $this->OrderDetail->ExlRcrd($aOrdLst[$i]['lOrder_IdNo']);
			$nMrgCell = count($aItmLst) > 1 ? count($aItmLst) - 1 : '';
			Controller::SetCell(config('excel.XL_ORD.SR_NO'), $lRaw, $i+1, $sheet, config('excel.XL_ORD.SR_NO'), '', 'right', False, '', False, 8, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD.DEL_DATE'), $lRaw, date('d M, Y', strtotime($aOrdLst[$i]['sDelv_Date'])), $sheet, config('excel.XL_ORD.DEL_DATE'), '', 'left', True, '', False, 12, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD.ORD_NO'), $lRaw, $aOrdLst[$i]['sOrdr_Id'], $sheet, config('excel.XL_ORD.ORD_NO'), '', 'left', True, '', False, 12, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD.PRNT_NAME'), $lRaw, array_search($aOrdLst[$i]['nOrder_Type'], config('constant.ORD_TYPE')), $sheet, config('excel.XL_ORD.PRNT_NAME'), '', 'left', False, '', False, 12, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD.STDNT_NAME'), $lRaw, $sUserName, $sheet, config('excel.XL_ORD.STDNT_NAME'), '', 'left', False, '', False, 25, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD.SCHL_NAME'), $lRaw, $aOrdLst[$i]['sSchl_Name'], $sheet, config('excel.XL_ORD.SCHL_NAME'), '', 'left', False, '', False, 25, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD.SUB_AMO'), $lRaw, $aOrdLst[$i]['sSub_Ttl'], $sheet, config('excel.XL_ORD.SUB_AMO'), '', 'right', False, '#0.00', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD.GST_AMO'), $lRaw, $aOrdLst[$i]['sGst_Amo'], $sheet, config('excel.XL_ORD.GST_AMO'), '', 'right', False, '#0.00', False, 8, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD.GRNT_AMO'), $lRaw, $aOrdLst[$i]['sGrnd_Ttl'], $sheet, config('excel.XL_ORD.GRNT_AMO'), '', 'right', False, '#0.00', False, 10, $nMrgCell, 10);
			Controller::SetCell(config('excel.XL_ORD.ORD_STATUS'), $lRaw, strtoupper(array_search($aOrdLst[$i]['nOrdr_Status'], config('constant.ORDER_STATUS'))), $sheet, config('excel.XL_ORD.ORD_STATUS'), '', 'center', True, '', False, 10, $nMrgCell, 10);

			$c = 0;
			while(isset($aItmLst) && count($aItmLst) > 0 && $c<count($aItmLst))
			{
				Controller::SetCell(config('excel.XL_ORD.ITM_NAME'), $lRaw, $aItmLst[$c]['sItem_Name'], $sheet, '', '', 'left', False, '', False, 25, '', 10);
				Controller::SetCell(config('excel.XL_ORD.ITM_PRC'), $lRaw, $aItmLst[$c]['sItem_Prc'], $sheet, '', '', 'right', False, '#0.00', False, 10, '', 10);
				Controller::SetCell(config('excel.XL_ORD.ITM_QTY'), $lRaw, $aItmLst[$c]['nItm_Qty'], $sheet, '', '', 'right', False, '', False, 10, '', 10);
				Controller::SetCell(config('excel.XL_ORD.TTL_PRC'), $lRaw, '='.Controller::GetColName(config('excel.XL_ORD.ITM_PRC')).$lRaw.'*'.Controller::GetColName(config('excel.XL_ORD.ITM_QTY')).$lRaw, $sheet, '', '', 'right', False, '#0.00', False, 10, '', 10);

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
	
	public function DelvOrd(Request $request)
	{
		$lOrderIdNo = base64_decode($request['lRecIdNo']);
		if(empty($lOrderIdNo))
		{
			return redirect()->back()->with('Failed', 'Unauthorized access...');
		}
		else
		{
		    
			$oOrdDtl = $this->OrderHd->GetOrder($lOrderIdNo);
			if(!isset($oOrdDtl) && empty($oOrdDtl->lOrder_IdNo))
			{
				return redirect()->back()->with('Failed', 'Unauthorized access...');		
			}
			else
			{
			    if($oOrdDtl->nOrdr_Status == config('constant.ORDER_STATUS.Pending'))
		        {
		        	if($oOrdDtl->sDelv_Date <= date('Y-m-d'))
		        	{
	    				$aValue = array(
	    					"nOrdr_Status"  => config('constant.ORDER_STATUS.Delivered'),
	    					"sDelvrd_Date"  => date('Y-m-d H:i:s'),
	    				);
	    				$nRow = $this->OrderHd->UpDtRecrd($aValue, $lOrderIdNo);
	    				if($nRow > 0)
	    				{
	    					return redirect()->back()->with('Success', 'Order deliverded successfully...');				
	    				}
	    				else
	    				{
	    					return redirect()->back()->with('Failed', 'Unauthorized access...');		
	    				}
	    			}
	    			else
	    			{
	    				return redirect()->back()->with('Failed', 'Order is future dated ('.date('d M, Y', strtotime($oOrdDtl->sDelv_Date)).'), Status cannot be updated today');
	    			}
		        }
		        else
		        {
		            return redirect()->back()->with('Failed', "Order cancelled so you can not deliver now...");
		        }
			}
		}
	}

	public function TcktLst(Request $request)
	{
		$lMilkIdNo  = session('USER_ID');
		if($request['nTcktDur'] == 0)
		{
		    $sFrmDate  	= date('Y-m-d');
		    $sToDate  	= date('Y-m-d');
		}
		else if($request['nTcktDur'] == 1)
		{
		    $sFrmDate  	= date('Y-m-d', strtotime('+'.$request['nTcktDur'].' days'));
		    $sToDate  	= date('Y-m-d', strtotime('+'.$request['nTcktDur'].' days'));
		}
		else
		{
		    $nTcktDur   = $request['nTcktDur']-1;
		    $sFrmDate  	= date('Y-m-d', strtotime('+1 days'));
		    $sToDate  	= date('Y-m-d', strtotime('+'.$nTcktDur.' days'));
		}
		$lSchlIdNo  = $request['lSchlIdNo'];
		$sClsName   = $request['sClsName'];
		$lItemIdNo  = $request['lItemIdNo'];
		$aTcktDtl 	= $this->OrderHd->TcktLst($lMilkIdNo, $sFrmDate, $sToDate, $lSchlIdNo, $sClsName, $lItemIdNo);
		if(count($aTcktDtl) > 0)
		{
			$sTitle 	= "Manage Order Ticket";
	    	$aData 		= compact('sTitle','aTcktDtl');
	        return view('milkbar_panel.order_ticket',$aData);	
	    }
	    else
	    {
	    	return redirect()->back()->with('Failed', 'There is no order to print...');
	    }
	}
}
?>