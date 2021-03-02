<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class SchoolContact extends Model
{
    public $timestamps  = false;
    protected $table    = 'schl_cntct';

    public function SchlCntctlLst($lSchlIdNo)
    {
    	try
    	{
	        $aSchlLst	= SchoolContact::Where('lSchl_IdNo',$lSchlIdNo)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->get()->toArray();
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
	        $lSchlCntctIdNo	= SchoolContact::insertGetId($aHdArr);
	        return $lSchlCntctIdNo;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function UpDtRecrd($aHdArr, $lSchlCntctIdNo)
    {
    	try
    	{
	        $nRow	= SchoolContact::Where('lSchl_Cntct_IdNo',$lSchlCntctIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
	
	public function DelSchl($aHdArr, $lSchlIdNo)
    {
    	try
    	{
	        $nRow	= SchoolContact::Where('lSchl_IdNo',$lSchlIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
}
