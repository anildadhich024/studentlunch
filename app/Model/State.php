<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class State extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_state';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $nRow	= State::insert($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function UpDtRecrd($aHdArr, $lStateIdNo)
    {
    	try
    	{
	        $nRow	= State::Where('lState_IdNo',$lStateIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function FrntLst($lCntryIdNo)
    {
    	try
    	{
	        $aGetRec = State::Select('lState_IdNo','sState_Name','nArea_Code')->Where('lCntry_IdNo',$lCntryIdNo)->Where('nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->get()->toArray();
	        return $aGetRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function StateLst($sStateName)
    {
    	try
    	{
	        $aGetRec = State::Select('mst_state.*','mst_cntry.sCntry_Name')->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_state.lCntry_IdNo')->Where(function($query) use ($sStateName) {
                            if (isset($sStateName) && !empty($sStateName)) {
                                $query->where('sState_Name', 'LIKE', "%".$sStateName."%");
                            }
                        })->Where('mst_state.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->OrderBy('sCntry_Name')->OrderBy('sState_Name')->paginate(15);
	        return $aGetRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
}
