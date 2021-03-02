<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class School extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_schl';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $lSchlIdNo	= School::insertGetId($aHdArr);
	        return $lSchlIdNo;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
	
	
	public function UpDtRecrd($aHdArr, $lSchlIdNo)
    {
    	try
    	{
	        $nRow	= School::Where('lSchl_IdNo',$lSchlIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

	public function SchoolExist($sSchlName,$lSchlType)
    {
    	try
    	{
	        $aGetRec = School::Select('lSchl_IdNo')->Where('lSchl_Type',$lSchlType)->Where('sSchl_Name',$sSchlName)->Where('nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
	        return $aGetRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
	}
	
	public function SchoolExistWhere($sSchlName,$lSchlType,$lSchlIdNo)
    {
    	try
    	{
	        $aGetRec = School::Select('lSchl_IdNo')->Where('lSchl_Type',$lSchlType)->Where('sSchl_Name',$sSchlName)->Where('lSchl_IdNo','!=',$lSchlIdNo)->Where('nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
	        return $aGetRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
	}

    public function SchlLst($sSchlName = '', $sMobileNo = '')
    {
    	try
    	{
	        $oSchlLst	= School::Select('mst_schl.*', 'mst_cntry.sCntry_Name', 'mst_state.sState_Name')->leftjoin('mst_cntry', 'mst_cntry.lCntry_IdNo', '=', 'mst_schl.lCntry_IdNo')->leftjoin('mst_state', 'mst_state.lState_IdNo', '=', 'mst_schl.lState_IdNo')->Where('mst_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
	        				->where(function($query) use ($sSchlName) {
	                            if (isset($sSchlName) && !empty($sSchlName)) {
	                                $query->where('sSchl_Name','LIKE', "%".$sSchlName."%");
	                            }
	                        })
	                        ->where(function($query) use ($sMobileNo) {
	                            if (isset($sMobileNo) && !empty($sMobileNo)) {
	                                $query->where('sMobile_No','LIKE', "%".$sMobileNo."%");
	                            }
	                        })->OrderBy('mst_schl.lSchl_IdNo','desc')
	        				->paginate(10);
	        return $oSchlLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function RegSchlLst($lSchlType = '')
    {
    	try
    	{
	        $oSchlLst	= School::Select('lSchl_IdNo','sSchl_Name','sSbrb_Name','sPin_Code')
	        			->where(function($query) use ($lSchlType) {
                            if (isset($lSchlType) && !empty($lSchlType)) {
                                $query->where('lSchl_Type',$lSchlType);
                            }
                        })->Where('mst_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->get();
	        return $oSchlLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

	public static function PreLoadSchl($lSchlType)
	{
		try
    	{
	        $oSchlLst	= School::Select('lSchl_IdNo','sSchl_Name')->where('lSchl_Type',$lSchlType)->Where('mst_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->get();
	        return $oSchlLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
	} 

    public function SchlAll()
    {
    	try
    	{
	        $oSchlLst	= School::Select('lSchl_IdNo','sSchl_Name')->Where('nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->get()->toArray();
	        return $oSchlLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
	
	public function SchlDtl($lSchlIdNo)
    {
    	try
    	{
	        $aSchlDtl	= School::Select('mst_schl.*','mst_cntry.sCntry_Name','mst_state.sState_Name')->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_schl.lState_IdNo')->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_schl.lCntry_IdNo')->Where('lSchl_IdNo',$lSchlIdNo)->Where('mst_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
	        return $aSchlDtl->toArray();
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
    public function ExlRcrd($sSchlName = '', $sMobileNo = '')
    {
    	try
    	{
	        $oSchlLst	= School::Select('mst_schl.*','mst_cntry.sCntry_Name','mst_state.sState_Name')->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_schl.lState_IdNo')->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_schl.lCntry_IdNo')->Where('mst_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
	        				->where(function($query) use ($sSchlName) {
	                            if (isset($sSchlName) && !empty($sSchlName)) {
	                                $query->where('sSchl_Name','LIKE', "%".$sSchlName."%");
	                            }
	                        })
	                        ->where(function($query) use ($sMobileNo) {
	                            if (isset($sMobileNo) && !empty($sMobileNo)) {
	                                $query->where('sMobile_No',$sMobileNo);
	                            }
	                        })
	        				->get()->toArray();
	        return $oSchlLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function CntRec()
    {
    	try
    	{
	        $aCntRec = School::Select(DB::raw('COUNT(*) As nTtlRec'))->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first()->toArray();
	        return $aCntRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function SchlOrdFltr()
    {
    	try
    	{
	        $oSchlLst	= School::Select('lSchl_IdNo','sSchl_Name')->Where('nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->get()->toArray();
	        return $oSchlLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
}