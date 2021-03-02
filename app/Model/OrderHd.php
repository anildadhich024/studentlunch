<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class OrderHd extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_ordr_hd';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $lOrderId	= OrderHd::insertGetId($aHdArr);
			return $lOrderId;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
    
    public function UpDtRecrd($aHdArr, $lOrderIdNo)
    {
    	try
    	{
	        $nRow	= OrderHd::Where('lOrder_IdNo',$lOrderIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function ChldSntOrd($lChldIdNo, $sDate)
    {
        try
        {
            $aCntOrd = OrderHd::Where('sDelv_Date',$sDate)->Where('lChld_IdNo',$lChldIdNo)->count();
            return $aCntOrd;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }
    }

    public function OrderLst($lUserIdNo, $nUserType, $sCrtDtTm = '', $nOrdrStatus = '')
    {
    	try
    	{
	        $oOrderLst	= OrderHd::Select('mst_ordr_hd.*','mst_chld.sFrst_Name As sChld_FName','mst_chld.sLst_Name As sChld_LName','mst_prnts.sFrst_Name As sPrnt_FName','mst_prnts.sLst_Name As sPrnt_LName','mst_milk_bar.sBuss_Name')->leftjoin('mst_chld', 'mst_chld.lChld_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
						->leftjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo', '=', 'mst_ordr_hd.lMilk_IdNo')
						->leftjoin('mst_prnts', 'mst_prnts.lPrnt_IdNo', '=', 'mst_ordr_hd.lPrnt_IdNo')
	        			->Where(function($query) use ($sCrtDtTm, $nOrdrStatus, $nUserType) {
	        				if (isset($nOrdrStatus)) {
                                $query->where('mst_ordr_hd.nOrdr_Status',$nOrdrStatus);
                            }
                            if (isset($sCrtDtTm) && !empty($sCrtDtTm)) {
                                $query->where('mst_ordr_hd.sCrt_DtTm', 'LIKE', $sCrtDtTm."%");
                            }
                            if($nUserType == config('constant.USER.TEACHER')) {
                            	$query->where('nUser_Type',$nUserType);
                            } else {
                            	$query->where('nUser_Type','!=',config('constant.USER.TEACHER'));
                            }
                        })->where('mst_ordr_hd.lPrnt_IdNo', $lUserIdNo)->OrderBy('sDelv_Date','DESC')->paginate(15);
	        return $oOrderLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function Cancel($lOrdrHdIdNo,$sCnclReason,$sCnclNote)
    {
    	try
    	{
	        OrderHd::where(['lOrder_IdNo' => $lOrdrHdIdNo])
			->update(['nOrdr_Status' => config('constant.ORDER_STATUS.Cancelled'), "sCncl_Date"  => date('Y-m-d H:i:s'),'sCncl_Reason' => $sCnclReason,'sCncl_Note' => $sCnclNote]);
			$row = OrderHd::where(['lOrder_IdNo' => $lOrdrHdIdNo])->first();
			
	        return $row;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
	
	
	public function GetOrder($lOrdrHdIdNo)
    {
    	try
    	{
			$oRow = OrderHd::where(['lOrder_IdNo' => $lOrdrHdIdNo])->first();
	        return $oRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
	
	public function UpdateTrans($lOrdrHdIdNo, $sTrnsctnId)
	{
		try
    	{
			$nRow = OrderHd::where(['lOrder_IdNo' => $lOrdrHdIdNo])->update(['sStrp_Trnf_Id' => $sTrnsctnId]);
			
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
	}
	
	public function CntOrd($lMilkIdNo, $nOrdrStatus = '', $Overdue = '')
    {
    	try
    	{
	        $aCntOrd = OrderHd::Select(DB::raw('COUNT(*) As nTtlRec'))->Where('lMilk_IdNo',$lMilkIdNo)
	        			->Where(function($query) use ($nOrdrStatus) {
                            if (isset($nOrdrStatus) && !empty($nOrdrStatus)) {
                                $query->where('nOrdr_Status', '=',$nOrdrStatus);
                            }
                        })
                        ->Where(function($query) use ($Overdue) {
                            if (isset($Overdue) && !empty($Overdue)) 
                            {
                            	if($Overdue == 'Pending') :
                                	$query->where('sDelv_Date', '>=',date('Y-m-d'));
                                else :
                                	$query->where('sDelv_Date', '<',date('Y-m-d'));
                                endif;
                            }
                        })->first()->toArray();
	        return $aCntOrd;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function CntOrdPrnt($lPrntIdNo, $nUserType, $nOrdrStatus = '', $Overdue = '')
    {
    	try
    	{
    		date_default_timezone_set('Australia/Adelaide');
	        $aCntOrd = OrderHd::Select(DB::raw('COUNT(*) As nTtlRec'))->Where('lPrnt_IdNo',$lPrntIdNo)
	        			->Where(function($query) use ($nOrdrStatus, $nUserType, $Overdue) {
                            if (isset($nOrdrStatus) && !empty($nOrdrStatus)) {
                                $query->where('nOrdr_Status', '=',$nOrdrStatus);
                            }
                            if($nUserType == config('constant.USER.TEACHER')) {
                                $query->where('nUser_Type',$nUserType);
                            } else {
                                $query->where('nUser_Type','!=',config('constant.USER.TEACHER'));
                            }
                            if (isset($Overdue) && !empty($Overdue)) 
                            {
                                if($Overdue == 'Pending') :
                                    $query->where('sDelv_Date', '>=',date('Y-m-d'));
                                else :
                                    $query->where('sDelv_Date', '<',date('Y-m-d'));
                                endif;
                            }
                        })->first()->toArray();
	        return $aCntOrd;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
    public function MilkOrdLst($lMilkIdNo, $sDelvDate = '', $lSchlIdNo = '', $nOrdrStatus = '')
    {
    	try
    	{
	        $oOrderLst	= OrderHd::Select('lOrder_IdNo','nOrd_Otp','nOrder_Type','nUser_Type','sOrdr_Id','sDelv_Date','sGrnd_Ttl','nOrdr_Status','mst_chld.sFrst_Name As sChld_FName','mst_chld.sLst_Name As sChld_LName','mst_prnts.sFrst_Name As sPrnt_FName','mst_prnts.sLst_Name As sPrnt_LName','mst_tchr.sFrst_Name as sTchr_FName','mst_tchr.sLst_Name as sTchr_LName','sSchl_Name','sCls_Name','sSchl_Name')
                        ->leftjoin('mst_chld', 'mst_chld.lChld_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
                        ->leftjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo', '=', 'mst_ordr_hd.lMilk_IdNo')
                        ->leftjoin('mst_prnts', 'mst_prnts.lPrnt_IdNo', '=', 'mst_ordr_hd.lPrnt_IdNo')
                        ->leftjoin('mst_tchr', 'mst_tchr.lTchr_IdNo', '=', 'mst_ordr_hd.lPrnt_IdNo')
						->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo', '=', 'mst_ordr_hd.lSchl_IdNo')
	        			->Where(function($query) use ($sDelvDate, $lSchlIdNo, $nOrdrStatus) {
                            if (isset($sDelvDate) && !empty($sDelvDate)) {
                                $query->where('sDelv_Date',$sDelvDate);
                            }
                            if (isset($lSchlIdNo) && !empty($lSchlIdNo)) {
                                $query->where('mst_ordr_hd.lSchl_IdNo',$lSchlIdNo);
                            }
                            if (isset($nOrdrStatus) && !empty($nOrdrStatus)) {
                                $query->where('nOrdr_Status',$nOrdrStatus);
                            }
                        })->where('mst_ordr_hd.lMilk_IdNo', $lMilkIdNo)->OrderBy('sDelv_Date','DESC')->paginate(15);
	        return $oOrderLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function ExlRcrd($lMilkIdNo, $sFrmDate = '', $sToDate = '', $lSchlIdNo = '', $nOrdrStatus = '')
    {
    	try
    	{
	        $oOrderLst	= OrderHd::Select('lOrder_IdNo','sOrdr_Id','nOrder_Type','nUser_Type','sDelv_Date','sSub_Ttl','sGst_Amo','sGrnd_Ttl','nOrdr_Status','mst_chld.sFrst_Name as sChld_FName','mst_chld.sLst_Name as sChld_LName', 'mst_prnts.sFrst_Name as sPrnt_FName','mst_prnts.sLst_Name as sPrnt_LName', 'mst_tchr.sFrst_Name as sTchr_FName','mst_tchr.sLst_Name as sTchr_LName', 'sCls_Name','sSchl_Name')->leftjoin('mst_chld', 'mst_chld.lChld_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
                        ->leftjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo', '=', 'mst_ordr_hd.lMilk_IdNo')
                        ->leftjoin('mst_prnts', 'mst_prnts.lPrnt_IdNo', '=', 'mst_ordr_hd.lPrnt_IdNo')
                        ->leftjoin('mst_tchr', 'mst_tchr.lTchr_IdNo', '=', 'mst_ordr_hd.lPrnt_IdNo')
						->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo', '=', 'mst_ordr_hd.lSchl_IdNo')
	        			->Where(function($query) use ($sFrmDate, $sToDate, $lSchlIdNo, $nOrdrStatus) {
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sFrmDate)) {
                                $query->whereBetween('sDelv_Date',array($sFrmDate,$sToDate));
                            }
                            if (isset($lSchlIdNo) && !empty($lSchlIdNo)) {
                                $query->where('mst_ordr_hd.lSchl_IdNo',$lSchlIdNo);
                            }
                            if (isset($nOrdrStatus) && !empty($nOrdrStatus)) {
                                $query->where('nOrdr_Status',$nOrdrStatus);
                            }
                        })->where('mst_ordr_hd.lMilk_IdNo', $lMilkIdNo)->OrderBy('sDelv_Date','DESC')->get()->toArray();
	        return $oOrderLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
    public function ExlRcrdPrnt($lUserIdNo, $nUserType, $sFrmDate = '', $sToDate = '', $nOrdrStatus = '')
    {
    	try
    	{
	        $oOrderLst	= OrderHd::Select('lOrder_IdNo','nUser_Type','sOrdr_Id','sDelv_Date','sSub_Ttl','sGst_Amo','sGrnd_Ttl','nOrdr_Status','nOrder_Type','mst_ordr_hd.sCrt_DtTm','mst_chld.sFrst_Name As sChld_FName','mst_chld.sLst_Name As sChld_LName','mst_prnts.sFrst_Name As sPrnt_FName','mst_prnts.sLst_Name As sPrnt_LName','sCls_Name','sBuss_Name')->leftjoin('mst_chld', 'mst_chld.lChld_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
                        ->leftjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo', '=', 'mst_ordr_hd.lMilk_IdNo')
                        ->leftjoin('mst_prnts', 'mst_prnts.lPrnt_IdNo', '=', 'mst_ordr_hd.lPrnt_IdNo')
	        			->Where(function($query) use ($sFrmDate, $sToDate, $nOrdrStatus, $nUserType) {
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                $query->whereBetween('mst_ordr_hd.sCrt_DtTm',array($sFrmDate,$sToDate));
                            }
                            if (isset($nOrdrStatus) && !empty($nOrdrStatus)) {
                                $query->where('nOrdr_Status',$nOrdrStatus);
                            }
                            if($nUserType == config('constant.USER.TEACHER')) {
                            	$query->where('nUser_Type',$nUserType);
                            } else {
                            	$query->where('nUser_Type','!=',config('constant.USER.TEACHER'));
                            }
                        })->where('mst_ordr_hd.lPrnt_IdNo', $lUserIdNo)->OrderBy('sDelv_Date','DESC')->get()->toArray();
            return $oOrderLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function AccMilkLst($lUserIdNo, $nUserType)
    {
        try
        {
            $aMilkLst = OrderHd::Select('mst_ordr_hd.lMilk_IdNo','mst_milk_bar.sBuss_Name')->leftjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo', '=', 'mst_ordr_hd.lMilk_IdNo')->Where('nUser_Type',$nUserType)->Where('mst_ordr_hd.lPrnt_IdNo',$lUserIdNo)->groupBy('mst_ordr_hd.lMilk_IdNo','mst_milk_bar.sBuss_Name')->get()->toArray();
            return $aMilkLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }
    }

    public function GetOrderDtl($lOrdrHdIdNo)
    {
    	try
    	{
    		$aUserDt = OrderHd::select('nUser_Type','lOrder_IdNo')->where(['mst_ordr_hd.sOrdr_Id' => $lOrdrHdIdNo])->first()->toArray();
    		
    		if($aUserDt['nUser_Type'] == config('constant.USER.CHILD'))
    		{
				$oRow = OrderHd::select('mst_ordr_hd.*', 'mst_chld.sFrst_Name as sFrst_Name', 'mst_chld.sLst_Name as sLst_Name', 'sCls_Name', 'mst_milk_bar.sBuss_Name', 'mst_milk_bar.sPhone_No', 'mst_milk_bar.sEmail_Id', 'mst_milk_bar.sStrt_No', 'mst_milk_bar.sStrt_Name', 'mst_milk_bar.sSbrb_Name', 'mst_milk_bar.sPin_Code', 'mst_cntry.sCntry_Name', 'mst_state.sState_Name', 'mst_prnts.sEmail_Id' , 'mst_prnts.sCntry_Code', 'mst_prnts.sMobile_No','nUser_Type')
				->leftjoin('mst_milk_bar','mst_ordr_hd.lMilk_IdNo', '=', 'mst_milk_bar.lMilk_IdNo')
				->leftjoin('mst_chld','mst_ordr_hd.lChld_IdNo', '=', 'mst_chld.lChld_IdNo')
				->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_milk_bar.lState_IdNo')
				->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_milk_bar.lCntry_IdNo')
				->leftjoin('mst_prnts','mst_prnts.lPrnt_IdNo','=','mst_ordr_hd.lPrnt_IdNo')
				->where('mst_ordr_hd.lOrder_IdNo', $aUserDt['lOrder_IdNo'])
				->first();
			}
			else if($aUserDt['nUser_Type'] == config('constant.USER.TEACHER'))
			{	
				$oRow = OrderHd::select('mst_ordr_hd.*', 'mst_tchr.sFrst_Name as sFrst_Name', 'mst_tchr.sLst_Name as sLst_Name', 'mst_milk_bar.sBuss_Name', 'mst_milk_bar.sPhone_No', 'mst_milk_bar.sEmail_Id', 'mst_milk_bar.sStrt_No', 'mst_milk_bar.sStrt_Name', 'mst_milk_bar.sSbrb_Name', 'mst_milk_bar.sPin_Code', 'mst_cntry.sCntry_Name', 'mst_state.sState_Name', 'mst_tchr.sEmail_Id' , 'mst_tchr.sCntry_Code', 'mst_tchr.sMobile_No','nUser_Type')
				->leftjoin('mst_milk_bar','mst_ordr_hd.lMilk_IdNo', '=', 'mst_milk_bar.lMilk_IdNo')
				->leftjoin('mst_tchr','mst_ordr_hd.lChld_IdNo', '=', 'mst_tchr.lTchr_IdNo')
				->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_milk_bar.lState_IdNo')
				->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_milk_bar.lCntry_IdNo')
				->where('mst_ordr_hd.lOrder_IdNo', $aUserDt['lOrder_IdNo'])
				->first();
			}
            else
            {
                $oRow = OrderHd::select('mst_ordr_hd.*', 'mst_prnts.sFrst_Name as sFrst_Name', 'mst_prnts.sLst_Name as sLst_Name', 'mst_milk_bar.sBuss_Name', 'mst_milk_bar.sPhone_No', 'mst_milk_bar.sEmail_Id', 'mst_milk_bar.sStrt_No', 'mst_milk_bar.sStrt_Name', 'mst_milk_bar.sSbrb_Name', 'mst_milk_bar.sPin_Code', 'mst_cntry.sCntry_Name', 'mst_state.sState_Name', 'mst_prnts.sEmail_Id' , 'mst_prnts.sCntry_Code', 'mst_prnts.sMobile_No','nUser_Type')
                ->leftjoin('mst_milk_bar','mst_ordr_hd.lMilk_IdNo', '=', 'mst_milk_bar.lMilk_IdNo')
                ->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_milk_bar.lState_IdNo')
                ->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_milk_bar.lCntry_IdNo')
                ->leftjoin('mst_prnts','mst_prnts.lPrnt_IdNo','=','mst_ordr_hd.lPrnt_IdNo')
                ->where('mst_ordr_hd.lOrder_IdNo', $aUserDt['lOrder_IdNo'])
                ->first();
            }
			
	        return $oRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
    
    public function AdmnOrdLst($sFrmDate = '', $sToDate = '', $lSchlIdNo = '', $lMilkIdNo = '')
    {
    	try
    	{
	        $oOrderLst	= OrderHd::Select('sOrdr_Id','sDelv_Date','sGrnd_Ttl','nOrdr_Status','nUser_Type','nOrder_Type','mst_chld.sFrst_Name as sChld_FName','mst_chld.sLst_Name as sChld_LName','mst_prnts.sFrst_Name as sPrnt_FName','mst_prnts.sLst_Name as sPrnt_LName','mst_tchr.sFrst_Name as sTchr_FName','mst_tchr.sLst_Name as sTchr_LName','sSchl_Name','sBuss_Name')
	        			->leftjoin('mst_chld', 'mst_chld.lChld_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
						->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo', '=', 'mst_ordr_hd.lSchl_IdNo')
						->leftjoin('mst_prnts', 'mst_prnts.lPrnt_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
						->leftjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo', '=', 'mst_ordr_hd.lMilk_IdNo')
                        ->leftjoin('mst_tchr', 'mst_tchr.lTchr_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
	        			->Where(function($query) use ($sFrmDate, $sToDate, $lSchlIdNo, $lMilkIdNo) {
                            if (isset($sFrmDate) && !empty($sFrmDate) && isset($sToDate) && !empty($sToDate)) {
                                $query->whereBetween('sDelv_Date',array($sFrmDate, $sToDate));
                            }
                            if (isset($lSchlIdNo) && !empty($lSchlIdNo)) {
                                $query->where('mst_ordr_hd.lSchl_IdNo',$lSchlIdNo);
                            }
                            if (isset($lMilkIdNo) && !empty($lMilkIdNo)) {
                                $query->where('mst_ordr_hd.lMilk_IdNo',$lMilkIdNo);
                            }
                        })->OrderBy('sDelv_Date','DESC')->paginate(15);
	        return $oOrderLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function ExlRcrdAdmn($sFrmDate = '', $sToDate = '', $lMilkIdNo = '', $lSchlIdNo = '')
    {
    	try
    	{
	        $aOrderLst	= OrderHd::Select('lOrder_IdNo','nUser_Type','nOrder_Type','sOrdr_Id','sDelv_Date','sSub_Ttl','sGst_Amo','sGrnd_Ttl','nOrdr_Status','mst_ordr_hd.sCrt_DtTm','mst_chld.sFrst_Name as sChld_FName','mst_chld.sLst_Name as sChld_LName','mst_prnts.sFrst_Name as sPrnt_FName','mst_prnts.sLst_Name as sPrnt_LName','mst_tchr.sFrst_Name as sTchr_FName','mst_tchr.sLst_Name as sTchr_LName','sSchl_Name','sBuss_Name')
	        			->leftjoin('mst_chld', 'mst_chld.lChld_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
						->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo', '=', 'mst_ordr_hd.lSchl_IdNo')
						->leftjoin('mst_prnts', 'mst_prnts.lPrnt_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
						->leftjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo', '=', 'mst_ordr_hd.lMilk_IdNo')
                        ->leftjoin('mst_tchr', 'mst_tchr.lTchr_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
	        			->Where(function($query) use ($sFrmDate, $sToDate, $lSchlIdNo, $lMilkIdNo) {
                            if (isset($sFrmDate) && !empty($sFrmDate) && isset($sToDate) && !empty($sToDate)) {
                                $query->whereBetween('sDelv_Date',array($sFrmDate, $sToDate));
                            }
                            if (isset($lSchlIdNo) && !empty($lSchlIdNo)) {
                                $query->where('mst_ordr_hd.lSchl_IdNo',$lSchlIdNo);
                            }
                            if (isset($lMilkIdNo) && !empty($lMilkIdNo)) {
                                $query->where('mst_ordr_hd.lMilk_IdNo',$lMilkIdNo);
                            }
                        })->get()->toArray();
	        return $aOrderLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
    public function GetMlkEarn($lMilkIdNo)
    {
    	try
    	{
			return OrderHd::groupBy('date')
			->orderBy('date', 'desc')
			->take(5)
			->where('lMilk_IdNo',$lMilkIdNo)
			->where('nOrdr_Status', '!=', config('constant.ORDER_STATUS.Cancelled'))
			->get([
				DB::raw('MONTH(sCrt_DtTm) as date'),
				DB::raw('SUM(sGrnd_Ttl) as total')
			])
			->pluck('total', 'date');
			
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
	
	public function GetPrntCnt($lPrntIdNo, $nUserType)
    {
    	try
    	{
			return OrderHd::Where(function($query) use ($nUserType) {
                            if($nUserType == config('constant.USER.TEACHER')) {
                                $query->where('nUser_Type',$nUserType);
                            } else {
                                $query->where('nUser_Type','!=',config('constant.USER.TEACHER'));
                            }
                        })->groupBy('date')
			->orderBy('date', 'desc')
			->take(5)
			->where('lPrnt_IdNo',$lPrntIdNo)
			->where('nOrdr_Status', '!=', config('constant.ORDER_STATUS.Cancelled'))
			->get([
				DB::raw('MONTH(sCrt_DtTm) as date'),
				DB::raw('COUNT(*) as total')
			])
			->pluck('total', 'date');
			
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
	
	public function GetTtlRvnu()
    {
    	try
    	{
			return OrderHd::groupBy('date')
			->orderBy('date', 'desc')
			->take(5)
			->where('nOrdr_Status', '!=', config('constant.ORDER_STATUS.Cancelled'))
			->get([
				DB::raw('MONTH(sCrt_DtTm) as date'),
				DB::raw('SUM(sGrnd_Ttl) as total')
			])
			->pluck('total', 'date');
			
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function CntAllOrd($sFrmDate, $sToDate)
    {
    	try
    	{
	        $aCntRec = OrderHd::Select(DB::raw('COUNT(*) As nTtlRec'))->whereBetween('sDelv_Date',array($sFrmDate,$sToDate))->first()->toArray();
	        return $aCntRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function AccSchlLst($lMilkIdNo)
    {
    	try
    	{
	        $aSchlLst	= OrderHd::Select('mst_schl.lSchl_IdNo','mst_schl.sSchl_Name')->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo', '=', 'mst_ordr_hd.lSchl_IdNo')->Where('mst_ordr_hd.lMilk_IdNo',$lMilkIdNo)->groupby('mst_schl.lSchl_IdNo','mst_schl.sSchl_Name')->orderby('mst_schl.sSchl_Name')->get()->toArray();
	        return $aSchlLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function TcktLst($lMilkIdNo, $sFrmDate, $sToDate, $lSchlIdNo = '', $sClsName = '', $lItmIdNo = '')
    {
    	try
    	{
	    	$aOrdLst = OrderHd::Select('lOrder_IdNo','sOrdr_Id','lSchl_Type','sSchl_Name','mst_chld.sFrst_Name as sChld_FName','mst_chld.sLst_Name as sChld_LName','mst_prnts.sFrst_Name as sPrnt_FName','mst_prnts.sLst_Name as sPrnt_LName','mst_tchr.sFrst_Name as sTchr_FName','mst_tchr.sLst_Name as sTchr_LName','sCls_Name','lOrdr_Hd_IdNo','sPic_Tm','nOrder_Type','nUser_Type','nOrd_Otp','sDelv_Date')
	    				->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo', '=', 'mst_ordr_hd.lSchl_IdNo')
	    				->leftjoin('mst_chld', 'mst_chld.lChld_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
                        ->leftjoin('mst_prnts', 'mst_prnts.lPrnt_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
                        ->leftjoin('mst_tchr', 'mst_tchr.lTchr_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
	    				->leftjoin('mst_ordr_dt', 'mst_ordr_dt.lOrdr_Hd_IdNo', '=', 'mst_ordr_hd.lOrder_IdNo')
	    				->where('lMilk_IdNo',$lMilkIdNo)
	    				->Where(function($query) use ($sFrmDate, $sToDate, $lSchlIdNo, $sClsName, $lItmIdNo) {
	                            if(isset($sFrmDate) && !empty($sFrmDate) && isset($sToDate) && !empty($sToDate)) {
	                                $query->whereBetween('sDelv_Date',array($sFrmDate, $sToDate));
	                            }
	                            if(isset($lSchlIdNo) && !empty($lSchlIdNo)) {
	                                $query->where('mst_ordr_hd.lSchl_IdNo',$lSchlIdNo);
	                            }
	                            if(isset($sClsName) && !empty($sClsName)) {
	                                $query->where('sCls_Name', 'LIKE', $sClsName."%");
	                            }
	                            if(isset($lMilkIdNo) && !empty($lMilkIdNo)) {
	                                $query->where('mst_ordr_hd.lMilk_IdNo',$lMilkIdNo);
	                            }
	                            if(isset($lItmIdNo) && !empty($lItmIdNo)) {
	                                $query->where('lItm_IdNo',$lItmIdNo);
	                            }
	                        })->GroupBy('lOrder_IdNo','sOrdr_Id','lSchl_Type','sSchl_Name','sCls_Name','lOrdr_Hd_IdNo','sPic_Tm','nOrder_Type','nUser_Type','nOrd_Otp','sChld_FName','sChld_LName','sPrnt_FName','sPrnt_LName','sTchr_FName','sTchr_LName','sDelv_Date')->get()->toArray();
	    	return $aOrdLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function CommSmry($sFrmDate = '', $sToDate = '', $lMilkIdNo = '')
    {
        try
        {
            $oComLst = OrderHd::Select('mst_ordr_hd.lMilk_IdNo','sBuss_Name',DB::raw('COUNT(lOrder_IdNo) as nTtlOrd'),DB::raw('SUM(sGrnd_Ttl) as sTtlAmt'), DB::raw('SUM(sCom_Amo) as sTtlCom'))->leftjoin('mst_milk_bar','mst_milk_bar.lMilk_IdNo','=','mst_ordr_hd.lMilk_IdNo')
                        ->where(function($query) use ($sFrmDate, $sToDate, $lMilkIdNo) {
                            if (isset($lMilkIdNo) && !empty($lMilkIdNo)) {
                                $query->where('mst_ordr_hd.lMilk_IdNo', $lMilkIdNo);
                            }
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                $query->whereBetween('mst_ordr_hd.sCrt_DtTm', array($sFrmDate,$sToDate));
                            }
                        })->GroupBy('mst_ordr_hd.lMilk_IdNo','sBuss_Name')->paginate(15);
            return $oComLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }
    }

    public function ExprtCommSmry($sFrmDate = '', $sToDate = '', $lMilkIdNo = '')
    {
        try
        {
            $oComLst = OrderHd::Select('mst_ordr_hd.lMilk_IdNo','sBuss_Name',DB::raw('COUNT(lOrder_IdNo) as nTtlOrd'),DB::raw('SUM(sGrnd_Ttl) as sTtlAmt'), DB::raw('SUM(sCom_Amo) as sTtlCom'))->leftjoin('mst_milk_bar','mst_milk_bar.lMilk_IdNo','=','mst_ordr_hd.lMilk_IdNo')
                        ->where(function($query) use ($sFrmDate, $sToDate, $lMilkIdNo) {
                            if (isset($lMilkIdNo) && !empty($lMilkIdNo)) {
                                $query->where('mst_ordr_hd.lMilk_IdNo', $lMilkIdNo);
                            }
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                $query->whereBetween('mst_ordr_hd.sCrt_DtTm', array($sFrmDate,$sToDate));
                            }
                        })->GroupBy('mst_ordr_hd.lMilk_IdNo','sBuss_Name')->get()->toArray();
            return $oComLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }
    }

    public function CommLst($sFrmDate = '', $sToDate = '', $lMilkIdNo = '')
    {
        try
        {
            $oComLst = OrderHd::Select('lOrder_IdNo','sOrdr_Id','nUser_Type','sGrnd_Ttl','mst_ordr_hd.sCrt_DtTm','sCom_Amo','mst_chld.sFrst_Name as sChld_FName','mst_chld.sLst_Name as sChld_LName','mst_prnts.sFrst_Name as sPrnt_FName','mst_prnts.sLst_Name as sPrnt_LName','mst_tchr.sFrst_Name as sTchr_FName','mst_tchr.sLst_Name as sTchr_LName')
            			->leftjoin('mst_chld', 'mst_chld.lChld_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
                        ->leftjoin('mst_prnts', 'mst_prnts.lPrnt_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
                        ->leftjoin('mst_tchr', 'mst_tchr.lTchr_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
                        ->where(function($query) use ($sFrmDate, $sToDate, $lMilkIdNo) {
                            if (isset($lMilkIdNo) && !empty($lMilkIdNo)) {
                                $query->where('lMilk_IdNo', $lMilkIdNo);
                            }
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                $query->whereBetween('mst_ordr_hd.sCrt_DtTm', array($sFrmDate,$sToDate));
                            }
                        })->paginate(15);
            return $oComLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }
    }

    public function ExprtCommLst($sFrmDate = '', $sToDate = '', $lMilkIdNo = '')
    {
        try
        {
            $oComLst = OrderHd::Select('lOrder_IdNo','sOrdr_Id','nUser_Type','sGrnd_Ttl','mst_ordr_hd.sCrt_DtTm','sCom_Amo','mst_chld.sFrst_Name as sChld_FName','mst_chld.sLst_Name as sChld_LName','mst_prnts.sFrst_Name as sPrnt_FName','mst_prnts.sLst_Name as sPrnt_LName','mst_tchr.sFrst_Name as sTchr_FName','mst_tchr.sLst_Name as sTchr_LName')
            			->leftjoin('mst_chld', 'mst_chld.lChld_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
                        ->leftjoin('mst_prnts', 'mst_prnts.lPrnt_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
                        ->leftjoin('mst_tchr', 'mst_tchr.lTchr_IdNo', '=', 'mst_ordr_hd.lChld_IdNo')
                        ->where(function($query) use ($sFrmDate, $sToDate, $lMilkIdNo) {
                            if (isset($lMilkIdNo) && !empty($lMilkIdNo)) {
                                $query->where('lMilk_IdNo', $lMilkIdNo);
                            }
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                $query->whereBetween('mst_ordr_hd.sCrt_DtTm', array($sFrmDate,$sToDate));
                            }
                        })->get()->toArray();
            return $oComLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }
    }
}