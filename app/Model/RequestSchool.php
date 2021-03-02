<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class RequestSchool extends Model
{
    public $timestamps  = false;
    protected $table    = 'req_schl';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $nRow	= RequestSchool::insert($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function ChngRecrdStatus($aHdArr, $ReqIdNo)
    {
    	try
    	{
	        $nRow	= RequestSchool::Where('Req_IdNo',$SchlReqIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

	public function DelRecrd($aHdArr, $lUserIdNo,$nUserType)
    {
    	try
    	{
	        $nRow	= RequestSchool::Where('lUser_IdNo',$lUserIdNo)->Where('nUser_Type',$nUserType)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }


    public function ReqSchlLst($sSchlName = '', $nSchType = '')
    {
    	try
    	{
	        $oSchReqlLst	= RequestSchool::Select('req_schl.*')
			->where(function($query) use ($sSchlName) {
				if (isset($sSchlName) && !empty($sSchlName)) {
					$query->where('sSchl_Name','LIKE', "%".$sSchlName."%");
				}
			})
			->where(function($query) use ($nSchType) {
				if (isset($nSchType) && !empty($nSchType)) {
					$query->where('nSchl_Type',$nSchType);
				}
			})->OrderBy('req_schl.Req_IdNo','desc')
			->paginate(10);
	        return $oSchReqlLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function CntPndgSchl()
    {
    	try
    	{
	        $nRow	= RequestSchool::Where('nReq_Status',config('constant.REQ_STATUS.Pending'))->count();
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
}
