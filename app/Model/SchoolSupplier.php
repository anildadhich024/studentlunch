<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class SchoolSupplier extends Model
{
    public $timestamps  = false;
    protected $table    = 'schl_spplr';

    public function SchlSpplrLst($lSchlIdNo)
    {
    	try
    	{
	        $aSchlLst	= SchoolSupplier::Where('lSchl_IdNo',$lSchlIdNo)->get()->toArray();
	        return $aSchlLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
	
	public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $lSchlCntctIdNo	= SchoolSupplier::insertGetId($aHdArr);
	        return $lSchlCntctIdNo;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
    
    public function SchlSpplrDtl($lSchlIdNo)
    {
    	try
    	{
	        $aSchlCntctDtl	= SchoolSupplier::Where('lSchl_IdNo',$lSchlIdNo)->get()->toArray();
	        return $aSchlCntctDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
	
	public function DelSchl($lSchlIdNo)
    {
    	try
    	{
	        $nRow	= SchoolSupplier::Where('lSchl_IdNo',$lSchlIdNo)->delete();
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
  
  	
	public function MlkLst($aSchlIdLst)
    {
    	try
    	{
	        $aMlkLst	= SchoolSupplier::Select('mst_milk_bar.sBuss_Name','mst_milk_bar.lMilk_IdNo','schl_spplr.lSchl_IdNo')->Rightjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo', '=', 'schl_spplr.lMilk_IdNo')->Where('mst_milk_bar.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->WhereIn('schl_spplr.lSchl_IdNo',$aSchlIdLst)->get()->toArray();
	        return $aMlkLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
}
