<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class TeacherSchool extends Model
{
    public $timestamps  = false;
    protected $table    = 'tchr_schl';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $nRow	= TeacherSchool::insert($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function DelRecrd($aHdArr, $lTchrIdNo)
    {
    	try
    	{
	        $nRow	= TeacherSchool::Where('lTchr_IdNo',$lTchrIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function UpDtRecrd($aHdArr, $lTchrSchlIdNo)
    {
    	try
    	{
	        $nRow	= TeacherSchool::Where('lTchr_Schl_IdNo',$lTchrSchlIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function SchlLst($lTchrIdNo)
    {
    	try
    	{
	        $aSchlLst	= TeacherSchool::Select('tchr_schl.*','mst_schl.sSchl_Name')->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo', '=', 'tchr_schl.lSchl_IdNo')->Where('tchr_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('lTchr_IdNo',$lTchrIdNo)->first();
	        return $aSchlLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
  
	public function CntSchl($lTchrIdNo)
    {
    	try
    	{
	        $aCntSchl = TeacherSchool::Select(DB::raw('COUNT(*) As nTtlRec'))->Where('lTchr_IdNo',$lTchrIdNo)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first()->toArray();
	        return $aCntSchl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function AssMilkBar($lTchrIdNo, $sDtTm, &$lSchlIdNo, &$sSchlName)
    {
    	try
    	{
    		date_default_timezone_set('Australia/Adelaide');
	    	$aSchlLst	= TeacherSchool::Select('mst_schl.lSchl_IdNo','mst_schl.sSchl_Name')->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo', '=', 'tchr_schl.lSchl_IdNo')->Where('tchr_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('lTchr_IdNo',$lTchrIdNo)->first();

	    	if(strtotime($sDtTm) == strtotime(date('Y-m-d')))
	    	{
	    		$aMilkLst = DB::table('milk_schl')->select('mst_milk_bar.lMilk_IdNo','sBuss_Name', \DB::raw('(CASE 
                        		WHEN milk_schl.sCut_Tm >= "'.date('H:i:s').'" THEN 1 ELSE 0 END) AS yCut_Status'))
                        ->leftjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo','=','milk_schl.lMilk_IdNo')->Where('lSchl_IdNo',$aSchlLst->lSchl_IdNo)->Where('milk_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('mst_milk_bar.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('nAdmin_Status',config('constant.MLK_STATUS.ACTIVE'))->get();
	    	}
	    	else
	    	{
	    		$aMilkLst = DB::table('milk_schl')->select('mst_milk_bar.lMilk_IdNo','sBuss_Name')
                        ->leftjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo','=','milk_schl.lMilk_IdNo')->Where('lSchl_IdNo',$aSchlLst->lSchl_IdNo)->Where('milk_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('mst_milk_bar.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('nAdmin_Status',config('constant.MLK_STATUS.ACTIVE'))->get();
	    	}

	    	$lSchIdNo 	= $aSchlLst->lSchl_IdNo;
	    	$sSchlName 	= $aSchlLst->sSchl_Name;
	    	return $aMilkLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }

    }

    public function GetCutTime($lTchrIdNo, $lMilkIdNo, &$lSchlIdNo)
    {
    	try
    	{
    		$aSchlLst	= TeacherSchool::Select('mst_schl.lSchl_IdNo')->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo', '=', 'tchr_schl.lSchl_IdNo')->Where('lTchr_IdNo',$lTchrIdNo)->first();

    		$aTimArr	= TeacherSchool::Select('sCut_Tm')->leftjoin('milk_schl','milk_schl.lSchl_IdNo','=','tchr_schl.lSchl_IdNo')->Where('lTchr_IdNo',$lTchrIdNo)->Where('milk_schl.lSchl_IdNo',$aSchlLst->lSchl_IdNo)->Where('milk_schl.lMilk_IdNo',$lMilkIdNo)->first()->toArray();
    		$lSchlIdNo = $aSchlLst->lSchl_IdNo;
	        return $aTimArr;
    	}
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
}
