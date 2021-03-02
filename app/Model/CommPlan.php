<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class CommPlan extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_comm_pln';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $lPlnIdNo	= CommPlan::insertGetId($aHdArr);
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
	        $nRow	= CommPlan::Where('lCommPln_IdNo',$lCommPlnIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function AplyDtl($lCntryIdNo, $lStateIdNo)
    {
    	try
    	{
	        $aPlnDtl = CommPlan::Select('dCom_Per','sPrnt_Amo','dCacl_Per')->Where('lCntry_IdNo',$lCntryIdNo)->Where('lState_IdNo',$lStateIdNo)->Where('nAply_Status',config('constant.PLN_STATUS.ACTIVE'))->OrderBy('sStrt_Dt','DESC')->first()->toArray();
	        return $aPlnDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function PlanLst()
    {
    	try
    	{
	        $aPlnDtl = CommPlan::Select('mst_comm_pln.*','sCntry_Name','sState_Name')->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_comm_pln.lCntry_IdNo')->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_comm_pln.lState_IdNo')->OrderBy('sCntry_Name')->OrderBy('sStrt_Dt','DESC')->paginate(15);
	        return $aPlnDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
}
