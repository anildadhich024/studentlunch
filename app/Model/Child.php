<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Child extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_chld';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $nRow	= Child::insert($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function DelRecrd($aHdArr, $lPrntIdNo)
    {
    	try
    	{
	        $nRow	= Child::Where('lPrnt_IdNo',$lPrntIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function UpDtRecrd($aHdArr, $lChldIdNo)
    {
    	try
    	{
	        $nRow	= Child::Where('lChld_IdNo',$lChldIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function ChldLst($lPrntIdNo)
    {
    	try
    	{
	        $aChldLst	= Child::Select('mst_chld.*','mst_schl.sSchl_Name')->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo', '=', 'mst_chld.lSchl_IdNo')->Where('mst_chld.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('lPrnt_IdNo',$lPrntIdNo)->get()->toArray();
	        return $aChldLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
  
    
	public function ChldDtl($lChldIdNo)
	{
		try
    	{
	        $aChldLst	= Child::Select('mst_chld.*','mst_schl.sSchl_Name', 'mst_schl.lSchl_Type')->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo', '=', 'mst_chld.lSchl_IdNo')->Where('mst_chld.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('lChld_IdNo',$lChldIdNo)->get()->toArray();
	        return $aChldLst[0];
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
	}

	public function CntChld($lPrntIdNo)
    {
    	try
    	{
	        $aCntChld = Child::Select(DB::raw('COUNT(*) As nTtlRec'))->Where('lPrnt_IdNo',$lPrntIdNo)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first()->toArray();
	        return $aCntChld;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
}
