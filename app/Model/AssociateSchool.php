<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class AssociateSchool extends Model
{
    public $timestamps  = false;
    protected $table    = 'milk_schl';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $nRow	= AssociateSchool::insert($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function DelRecrd($aHdArr, $lMilkIdNo)
    {
    	try
    	{
	        $nRow	= AssociateSchool::Where('lMilk_IdNo',$lMilkIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function UpDtRecrd($aHdArr, $lMilkSchlIdNo)
    {
    	try
    	{
	        $nRow	= AssociateSchool::Where('lMilk_Schl_IdNo',$lMilkSchlIdNo)->update($aHdArr);
	        return $nRow;
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
	        $aSchlLst	= AssociateSchool::Select('milk_schl.*','mst_schl.sSchl_Name')->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo', '=', 'milk_schl.lSchl_IdNo')->Where('milk_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('lMilk_IdNo',$lMilkIdNo)->get()->toArray();
	        return $aSchlLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
	
	public function MlkLst($sSchlIdLst, $sDate)
    {
    	try
    	{
    		date_default_timezone_set('Australia/Adelaide');
    		if(strtotime($sDate) == strtotime(date('Y-m-d')))
	    	{
	    		$aMlkLst	= AssociateSchool::Select('mst_milk_bar.sBuss_Name','mst_milk_bar.lMilk_IdNo','milk_schl.lSchl_IdNo', \DB::raw('(CASE WHEN milk_schl.sCut_Tm >= "'.date('H:i:s').'" THEN 1 ELSE 0 END) AS yCut_Status'))->rightjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo', '=', 'milk_schl.lMilk_IdNo')
    			->Where('mst_milk_bar.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('mst_milk_bar.nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('mst_milk_bar.nAdmin_Status',config('constant.MLK_STATUS.ACTIVE'))->Where('milk_schl.lSchl_IdNo',$sSchlIdLst)->get()->toArray();
	    	}
	    	else
	    	{
	    		$aMlkLst	= AssociateSchool::Select('mst_milk_bar.sBuss_Name','mst_milk_bar.lMilk_IdNo','milk_schl.lSchl_IdNo')->Rightjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo', '=', 'milk_schl.lMilk_IdNo')
    			->Where('mst_milk_bar.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('mst_milk_bar.nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('mst_milk_bar.nAdmin_Status',config('constant.MLK_STATUS.ACTIVE'))->Where('milk_schl.lSchl_IdNo',$sSchlIdLst)->get()->toArray();
	    	}
			return $aMlkLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
    public function GetRecrd($lSchlIdNo, $lMilkIdNo)
    {
    	try
    	{
	        $nAssSchl	= AssociateSchool::Where('lMilk_IdNo',$lMilkIdNo)->where('lSchl_IdNo',$lSchlIdNo)->first();
	        return $nAssSchl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function GetCutTime($lChldIdNo, $lMilkIdNo, &$lSchlIdNo)
    {
    	try
    	{
	        $aTimArr	= AssociateSchool::Select('sCut_Tm','milk_schl.lSchl_IdNo')->leftjoin('mst_milk_bar','mst_milk_bar.lMilk_IdNo','=','milk_schl.lMilk_IdNo')->leftjoin('mst_chld','mst_chld.lSchl_IdNo','milk_schl.lSchl_IdNo')->Where('milk_schl.lMilk_IdNo',$lMilkIdNo)->Where('mst_chld.lChld_IdNo',$lChldIdNo)->first()->toArray();
	        $lSchlIdNo = $aTimArr['lSchl_IdNo'];
	        return $aTimArr;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function PrntSchool($lPrntIdNo, $sDate)
    {
    	try
    	{
    		$sSchlIds = '';
    		$oChldArr = DB::table('mst_chld')->Select('lSchl_IdNo')->Where('lPrnt_IdNo',$lPrntIdNo)->Where('mst_chld.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->get();
    		foreach($oChldArr as $aRec)
    		{
    			$sSchlIds .= $aRec->lSchl_IdNo.',';
    		}
    		$sSchlIds =  substr($sSchlIds, 0, -1);
    		date_default_timezone_set('Australia/Adelaide');
    		if(strtotime($sDate) == strtotime(date('Y-m-d')))
	    	{
	    		$aMlkArr	= AssociateSchool::Select('mst_milk_bar.sBuss_Name','mst_milk_bar.lMilk_IdNo','milk_schl.lSchl_IdNo', 'sSchl_Name', \DB::raw('(CASE WHEN milk_schl.sCut_Tm >= "'.date('H:i:s').'" THEN 1 ELSE 0 END) AS yCut_Status'))->rightjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo', '=', 'milk_schl.lMilk_IdNo')->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo','=','milk_schl.lSchl_IdNo')
    			->Where('mst_milk_bar.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('mst_milk_bar.nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('mst_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('mst_schl.nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('mst_milk_bar.nAdmin_Status',config('constant.MLK_STATUS.ACTIVE'))->WhereIn('milk_schl.lSchl_IdNo',array($sSchlIds))->get()->toArray();
	    	}
	    	else
	    	{
	    		$aMlkArr	= AssociateSchool::Select('mst_milk_bar.sBuss_Name','mst_milk_bar.lMilk_IdNo','milk_schl.lSchl_IdNo', 'sSchl_Name')->rightjoin('mst_milk_bar', 'mst_milk_bar.lMilk_IdNo', '=', 'milk_schl.lMilk_IdNo')->leftjoin('mst_schl', 'mst_schl.lSchl_IdNo','=','milk_schl.lSchl_IdNo')
    			->Where('mst_milk_bar.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('mst_milk_bar.nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('mst_milk_bar.nAdmin_Status',config('constant.MLK_STATUS.ACTIVE'))->Where('mst_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('mst_schl.nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->WhereIn('milk_schl.lSchl_IdNo',array($sSchlIds))->get()->toArray();
	    	}
	    	return $aMlkArr;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function SchlDtl($lPrntIdNo, $lMilkIdNo)
    {
    	try
    	{
    		$aSchlDtl = AssociateSchool::Select('mst_schl.lSchl_IdNo','sSchl_Name','mst_schl.lSchl_Type','sBuss_Name','sCut_Tm')->leftjoin('mst_chld','mst_chld.lSchl_IdNo','=','milk_schl.lSchl_IdNo')->leftjoin('mst_schl','mst_schl.lSchl_IdNo','=','mst_chld.lSchl_IdNo')->leftjoin('mst_milk_bar','mst_milk_bar.lMilk_IdNo','=','milk_schl.lMilk_IdNo')->Where('milk_schl.lMilk_IdNo',$lMilkIdNo)->Where('lPrnt_IdNo',$lPrntIdNo)->first()->toArray();
    		return $aSchlDtl;
    	}
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }		
    }

    public function SchlMlkLst($lSchlIdNo)
    {
    	$oSchlDtl = AssociateSchool::Select('sBuss_Name','mst_milk_bar.sCntry_Code','mst_milk_bar.sArea_Code','mst_milk_bar.sPhone_No','mst_milk_bar.sSbrb_Name','mst_milk_bar.sPin_Code','dDist_Km','sCut_Tm')->leftjoin('mst_milk_bar','mst_milk_bar.lMilk_IdNo','=','milk_schl.lMilk_IdNo')->Where('milk_schl.lSchl_IdNo',$lSchlIdNo)->get();
    	return $oSchlDtl;
    }
}
