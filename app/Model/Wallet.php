<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Wallet extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_wallet';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $nRow	= Wallet::insert($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

	public function WlltLst($lPrntIdNo, $sFrmDate = '', $sToDate = '', $lMlkIdNo = '', $lOrdrIdNo = '', $nCredit = '', $nDebit = '')
    {
    	try
    	{
	        $oWlltLst	= 	Wallet::select('mst_wallet.*', 'mst_chld.sFrst_Name', 'mst_chld.sLst_Name', 'mst_milk_bar.sBuss_Name')
							->Where(function($query) use ($lMlkIdNo) {
	                            if (isset($lMlkIdNo) && !empty($lMlkIdNo)) {
	                                $query->where('lMilk_IdNo', $lMlkIdNo);
	                            }
	                        })
							->where(function($query) use ($lOrdrIdNo) {
	                            if (isset($lOrdrIdNo) && !empty($lOrdrIdNo)) {
	                                $query->where('lOrder_IdNo', $lOrdrIdNo);
	                            }
	                        })
							->where(function($query) use ($sFrmDate) {
	                            if (isset($sFrmDate) && !empty($sFrmDate)) {
	                                $query->where('mst_wallet.sCrt_DtTm', '>=', $sFrmDate);
	                            }
	                        })
							->where(function($query) use ($sToDate) {
	                            if (isset($sToDate) && !empty($sToDate)) {
	                                $query->where('mst_wallet.sCrt_DtTm', '<=', $sToDate);
	                            }
	                        })
							->where(function($query) use ($nCredit) {
	                            if (isset($nCredit) && !empty($nCredit)) {
	                                $query->where('nTyp_Status', config('constant.TRANS.Credit'));
	                            }
	                        })
							->where(function($query) use ($nDebit) {
	                            if (isset($nDebit) && !empty($nDebit)) {
	                                $query->where('nTyp_Status', config('constant.TRANS.Debit'));
	                            }
	                        })->leftjoin('mst_chld', 'mst_wallet.lChld_IdNo', '=', 'mst_chld.lChld_IdNo')
							->leftjoin('mst_milk_bar', 'mst_wallet.lMilk_IdNo', '=', 'mst_milk_bar.lMilk_IdNo')->where('mst_wallet.lPrnt_IdNo',$lPrntIdNo)
	        				->OrderBy('mst_wallet.lWllt_IdNo','desc')->paginate(20);
	        return $oWlltLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
    public function OrdrIdGet($lOrdrIdNo)
    {
    	try
    	{
	        $oOrdrAmnt	= 	Wallet::where('lOrder_IdNo',$lOrdrIdNo)->where('nTyp_Status', config('constant.TRANS.Debit'))->first();
	        return $oOrdrAmnt;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
	
	public function MlkIdLst($lPrntIdNo)
    {
    	try
    	{
	        $aMlkIdLst	= 	Wallet::Select('mst_wallet.lMilk_IdNo', 'mst_milk_bar.sBuss_Name')
			->leftjoin('mst_milk_bar', 'mst_wallet.lMilk_IdNo', '=', 'mst_milk_bar.lMilk_IdNo')
			->where('lPrnt_IdNo',$lPrntIdNo)->get()->toArray();
	        return $aMlkIdLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
	public function GetCrdts($lUserIdNo, $nUserType)
    {
    	try
    	{
	        $aCrdts	= 	Wallet::Select('mst_wallet.sTtl_Amo', 'mst_wallet.lMilk_IdNo', 'mst_wallet.nTyp_Status')->leftjoin('mst_ordr_hd','mst_ordr_hd.lOrder_IdNo','=','mst_wallet.lOrder_IdNo')
			->where('mst_wallet.lPrnt_IdNo',$lUserIdNo)->where('nUser_Type',$nUserType)->get()->toArray();
	        return $aCrdts;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function CrdtSum($lUserIdNo, $nUserType, $nTranType, $lMilkIdNo = '')
    {
        try
        {
            $aCrdts =   Wallet::Select(DB::raw('SUM(sTtl_Amo) As sTtlAmo'))->leftjoin('mst_ordr_hd','mst_ordr_hd.lOrder_IdNo','=','mst_wallet.lOrder_IdNo')
            ->where('mst_wallet.lPrnt_IdNo',$lUserIdNo)->Where('nTyp_Status',$nTranType)
            ->where(function($query) use ($lMilkIdNo, $nUserType) {
                if (isset($lMilkIdNo) && !empty($lMilkIdNo)) {
                    $query->where('mst_wallet.lMilk_IdNo', $lMilkIdNo);
                }
                if($nUserType == config('constant.USER.TEACHER')) {
                    $query->where('nUser_Type',$nUserType);
                } else {
                    $query->where('nUser_Type','!=',config('constant.USER.TEACHER'));
                }
            })->first()->toArray();

            return empty($aCrdts) ? 0 : $aCrdts['sTtlAmo'];
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
    }
    
    public function MilkCrdt($lMilkIdNo, $nTypStatus = '', $sFrmDate = '', $sToDate = '', $lSchlIdNo = '', $sOrdrId = '')
    {
    	try
    	{
	        $aWaltAmo	= Wallet::Select(DB::raw('SUM(sTtl_Amo) As sTtlAmo'))->leftjoin('mst_ordr_hd','mst_ordr_hd.lOrder_IdNo','=','mst_wallet.lOrder_IdNo')->where('mst_wallet.lMilk_IdNo',$lMilkIdNo)
	        			->where(function($query) use ($sFrmDate, $sToDate) {
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                $query->whereBetween('mst_wallet.sCrt_DtTm', array($sFrmDate,$sToDate));
                            }
                        })
                        ->where(function($query) use ($nTypStatus) {
                            if (isset($nTypStatus) && !empty($nTypStatus)) {
                                $query->where('nTyp_Status', $nTypStatus);
                            }
                        })
                        ->where(function($query) use ($lSchlIdNo) {
                            if (isset($lSchlIdNo) && !empty($lSchlIdNo)) {
                                $query->where('mst_ordr_hd.lSchl_IdNo', $lSchlIdNo);
                            }
                        })
                        ->where(function($query) use ($sOrdrId) {
                            if (isset($sOrdrId) && !empty($sOrdrId)) {
                                $query->where('mst_ordr_hd.sOrdr_Id','LIKE', $sOrdrId."%");
                            }
                        })->first()->toArray();
	        return $aWaltAmo;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function MilkCrdtsLst($lMilkIdNo, $sFrmDate = '', $sToDate = '', $lSchlIdNo = '', $sOrdrId = '')
    {
    	try
    	{
    		$oCrdtDtl = Wallet::Select('mst_wallet.*','mst_ordr_hd.sOrdr_Id','nUser_Type','mst_chld.sFrst_Name As sChld_FName','mst_chld.sLst_Name As sChld_LName','mst_prnts.sFrst_Name As sPrnt_FName','mst_prnts.sLst_Name As sPrnt_LName','mst_tchr.sFrst_Name As sTchr_FName','mst_tchr.sLst_Name As sTchr_LName')->leftjoin('mst_tchr','mst_tchr.lTchr_IdNo','=','mst_wallet.lChld_IdNo')->leftjoin('mst_prnts','mst_prnts.lPrnt_IdNo','=','mst_wallet.lChld_IdNo')->leftjoin('mst_chld','mst_chld.lChld_IdNo','=','mst_wallet.lChld_IdNo')->leftjoin('mst_ordr_hd','mst_ordr_hd.lOrder_IdNo','=','mst_wallet.lOrder_IdNo')
    					->where(function($query) use ($sFrmDate, $sToDate) {
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                $query->whereBetween('mst_wallet.sCrt_DtTm', array($sFrmDate,$sToDate));
                            }
                        })
                        ->where(function($query) use ($lSchlIdNo) {
                            if (isset($lSchlIdNo) && !empty($lSchlIdNo)) {
                                $query->where('mst_ordr_hd.lSchl_IdNo', $lSchlIdNo);
                            }
                        })
                        ->where(function($query) use ($sOrdrId) {
                            if (isset($sOrdrId) && !empty($sOrdrId)) {
                                $query->where('mst_ordr_hd.sOrdr_Id','LIKE', $sOrdrId."%");
                            }
                        })->OrderBy('mst_wallet.lWllt_IdNo','desc')->Where('mst_wallet.lMilk_IdNo',$lMilkIdNo)->paginate(20);
    		return $oCrdtDtl;
    	}
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function ExlRcrd($lMilkIdNo, $sFrmDate = '', $sToDate = '', $lSchlIdNo = '', $sOrdrId = '')
    {
    	try
    	{
    		$aCrdtDtl = Wallet::Select('mst_wallet.*','mst_ordr_hd.sOrdr_Id','nUser_Type','mst_chld.sFrst_Name As sChld_FName','mst_chld.sLst_Name As sChld_LName','mst_prnts.sFrst_Name As sPrnt_FName','mst_prnts.sLst_Name As sPrnt_LName','mst_tchr.sFrst_Name As sTchr_FName','mst_tchr.sLst_Name As sTchr_LName')
                ->leftjoin('mst_chld','mst_chld.lChld_IdNo','=','mst_wallet.lChld_IdNo')
                ->leftjoin('mst_prnts','mst_prnts.lPrnt_IdNo','=','mst_wallet.lChld_IdNo')
                ->leftjoin('mst_tchr','mst_tchr.lTchr_IdNo','=','mst_wallet.lChld_IdNo')
                ->leftjoin('mst_ordr_hd','mst_ordr_hd.lOrder_IdNo','=','mst_wallet.lOrder_IdNo')
    					->where(function($query) use ($sFrmDate, $sToDate) {
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                $query->whereBetween('mst_wallet.sCrt_DtTm', array($sFrmDate,$sToDate));
                            }
                        })
                        ->where(function($query) use ($lSchlIdNo) {
                            if (isset($lSchlIdNo) && !empty($lSchlIdNo)) {
                                $query->where('mst_ordr_hd.lSchl_IdNo', $lSchlIdNo);
                            }
                        })
                        ->where(function($query) use ($sOrdrId) {
                            if (isset($sOrdrId) && !empty($sOrdrId)) {
                                $query->where('mst_ordr_hd.sOrdr_Id','LIKE', $sOrdrId."%");
                            }
                        })->Where('mst_wallet.lMilk_IdNo',$lMilkIdNo)->get()->toArray();
    		return $aCrdtDtl;
    	}
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function PrntCrdt($lPrntIdNo, $nUserType, $nTypStatus = '', $sFrmDate = '', $sToDate = '', $lMilkIdNo = '')
    {
        try
        {
            $aWaltAmo   = Wallet::Select(DB::raw('SUM(sTtl_Amo) As sTtlAmo'))->leftjoin('mst_ordr_hd','mst_ordr_hd.lOrder_IdNo','=','mst_wallet.lOrder_IdNo')->where('mst_ordr_hd.lPrnt_IdNo',$lPrntIdNo)
                        ->where(function($query) use ($sFrmDate, $sToDate, $nUserType, $nTypStatus, $lMilkIdNo) {
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                $query->whereBetween('mst_wallet.sCrt_DtTm', array($sFrmDate,$sToDate));
                            }
                            if (isset($nTypStatus) && !empty($nTypStatus)) {
                                $query->where('nTyp_Status', $nTypStatus);
                            }
                            if (isset($lMilkIdNo) && !empty($lMilkIdNo)) {
                                $query->where('mst_ordr_hd.lMilk_IdNo', $lMilkIdNo);
                            }
                            if($nUserType == config('constant.USER.TEACHER')) {
                                $query->where('nUser_Type',$nUserType);
                            } else {
                                $query->where('nUser_Type','!=',config('constant.USER.TEACHER'));
                            }
                        })->first()->toArray();
            return $aWaltAmo;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
    }

    public function PrntCrdtsLst($lUserIdNo, $nUserType, $sFrmDate = '', $sToDate = '', $lMilkIdNo = '')
    {
        try
        {
            $oCrdtLst = Wallet::Select('mst_wallet.*','mst_ordr_hd.sOrdr_Id','mst_chld.sFrst_Name As sChld_FName','mst_chld.sLst_Name As sChld_LName','mst_prnts.sFrst_Name As sPrnt_FName','mst_prnts.sLst_Name As sPrnt_LName','sBuss_Name','nUser_Type')->leftjoin('mst_chld','mst_chld.lChld_IdNo','=','mst_wallet.lChld_IdNo')->leftjoin('mst_ordr_hd','mst_ordr_hd.lOrder_IdNo','=','mst_wallet.lOrder_IdNo')->leftjoin('mst_milk_bar','mst_milk_bar.lMilk_IdNo','=','mst_wallet.lMilk_IdNo')
                ->leftjoin('mst_prnts', 'mst_prnts.lPrnt_IdNo', '=', 'mst_ordr_hd.lPrnt_IdNo')
                        ->where(function($query) use ($sFrmDate, $sToDate, $lMilkIdNo, $nUserType) {
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                $query->whereBetween('mst_wallet.sCrt_DtTm', array($sFrmDate,$sToDate));
                            }
                            if($nUserType == config('constant.USER.TEACHER')) {
                                $query->where('nUser_Type',$nUserType);
                            } else {
                                $query->where('nUser_Type','!=',config('constant.USER.TEACHER'));
                            }
                            if (isset($lMilkIdNo) && !empty($lMilkIdNo)) {
                                $query->where('mst_ordr_hd.lMilk_IdNo', $lMilkIdNo);
                            }
                        })->OrderBy('mst_wallet.sCrt_DtTm')->Where('mst_wallet.lPrnt_IdNo',$lUserIdNo)->paginate(20);
            return $oCrdtLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }
    }

    public function ExlRcrdPrnt($lUserIdNo, $nUserType, $sFrmDate = '', $sToDate = '', $lMilkIdNo = '')
    {
        try
        {
            $aCrdtDtl = Wallet::Select('mst_wallet.*','mst_ordr_hd.sOrdr_Id','mst_chld.sFrst_Name As sChld_FName','mst_chld.sLst_Name As sChld_LName','mst_prnts.sFrst_Name As sPrnt_FName','mst_prnts.sLst_Name As sPrnt_LName','sBuss_Name','nUser_Type')->leftjoin('mst_chld','mst_chld.lChld_IdNo','=','mst_wallet.lChld_IdNo')->leftjoin('mst_ordr_hd','mst_ordr_hd.lOrder_IdNo','=','mst_wallet.lOrder_IdNo')->leftjoin('mst_milk_bar','mst_milk_bar.lMilk_IdNo','=','mst_wallet.lMilk_IdNo')
                ->leftjoin('mst_prnts', 'mst_prnts.lPrnt_IdNo', '=', 'mst_ordr_hd.lPrnt_IdNo')
                        ->where(function($query) use ($sFrmDate, $sToDate, $lMilkIdNo, $nUserType) {
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                $query->whereBetween('mst_wallet.sCrt_DtTm', array($sFrmDate,$sToDate));
                            }
                            if (isset($lMilkIdNo) && !empty($lMilkIdNo)) {
                                $query->where('mst_ordr_hd.lMilk_IdNo', $lMilkIdNo);
                            }
                            if($nUserType == config('constant.USER.TEACHER')) {
                                $query->where('nUser_Type',$nUserType);
                            } else {
                                $query->where('nUser_Type','!=',config('constant.USER.TEACHER'));
                            }
                        })
                        ->Where('mst_wallet.lPrnt_IdNo',$lUserIdNo)->get()->toArray();
            return $aCrdtDtl;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }
    }

    public function PrntAvlbCrdt($lPrntIdNo)
    {
        try
        {
            $sTtlCrdt = Wallet::Select(DB::raw('SUM(*) As nTtlRec'))->Where('lPrnt_IdNo',$lPrntIdNo)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first()->toArray();
            return $aCntChld;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
    } 

    public function WltTtl($lMilkIdNo = '', $sFrmDate = '', $sToDate = '')
    {
        try
        {
            $aWtlRec = Wallet::Select(DB::raw('SUM(sTtl_Amo) as nTtlWlt'))
                        ->where(function($query) use ($sFrmDate, $sToDate) {
                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                $query->whereBetween('mst_wallet.sCrt_DtTm', array($sFrmDate,$sToDate));
                            }
                        })->where('lMilk_IdNo', $lMilkIdNo)->Where('nTyp_Status',config('constant.TRANS.Debit'))->first();
            return $aWtlRec;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }
    }

    public function CrdtUse($lOrdrIdNo)
    {
        try
        {
            $aWtlRec = Wallet::Select('sTtl_Amo')->Where('nTyp_Status',config('constant.TRANS.Debit'))->Where('lOrder_IdNo',$lOrdrIdNo)->first();
            return $aWtlRec;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }
    }
}