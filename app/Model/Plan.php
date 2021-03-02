<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Plan extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_plan';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $lPlnIdNo	= Plan::insertGetId($aHdArr);
	        return $lPlnIdNo;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function UpDtRecrd($aHdArr, $lCommPlnIdNo)
    {
    	try
    	{
	        $nRow	= Plan::Where('lCommPln_IdNo',$lCommPlnIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

	public function PlnSmry($sCntryName = "",$sStateName = "")
    {
        try
        {
			$oPlnLst = Plan::Select('mst_prnts.lCntry_IdNo','sPln_Dur','mst_prnts.lState_IdNo','sCntry_Name','sState_Name',DB::raw('COUNT(lPln_IdNo) as nTtlPln'),DB::raw('SUM(sPln_Amo) as sTtlAmt'))->leftjoin('mst_prnts','mst_prnts.lPrnt_IdNo','=','mst_plan.lPrnt_IdNo')
			->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_prnts.lCntry_IdNo')
			->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_prnts.lState_IdNo') 
			->Where(function($query) use ($sCntryName,$sStateName) {
				if (isset($sCntryName) && !empty($sCntryName)) {
					$query->where('mst_cntry.sCntry_Name','LIKE', "%".$sCntryName."%");
				}
				if (isset($sStateName) && !empty($sStateName)) {
					$query->where('mst_state.sState_Name','LIKE', "%".$sStateName."%");
				}
			})
            ->GroupBy('mst_prnts.lCntry_IdNo','sPln_Dur','mst_prnts.lState_IdNo','sCntry_Name','sState_Name')->paginate(15);
            return $oPlnLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }
	}
	
	public function PlnLst($lStateIdNo = "",$sPlnDur = "")
    { 
    	try
    	{
			$aSubDtl = Plan::Select('mst_plan.*','mst_prnts.sAcc_Id','mst_prnts.sFrst_Name','mst_prnts.sLst_Name','sCntry_Name','sState_Name')
			->leftjoin('mst_prnts','mst_prnts.lPrnt_IdNo','=','mst_plan.lPrnt_IdNo')
			->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_prnts.lCntry_IdNo')
			->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_prnts.lState_IdNo') 
			->where('mst_plan.sPln_Dur',$sPlnDur)->where('mst_prnts.lState_IdNo',$lStateIdNo)
			->OrderBy('sCrt_DtTm','desc')->paginate(15);
			 return $aSubDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
	
    public function PlnDtl($lPrntIdNo)
    {
    	try
    	{
	        $aPlnDtl	= Plan::Where('lPrnt_IdNo',$lPrntIdNo)->OrderBy('sCrt_DtTm','DESC')->first();
	        return $aPlnDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
}
