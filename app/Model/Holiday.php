<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Holiday extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_holiday';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $lPlnIdNo	= Holiday::insertGetId($aHdArr);
	        return $lPlnIdNo;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function UpDtRecrd($aHdArr, $lHolidayIdNo)
    {
    	try
    	{
	        $nRow	= Holiday::Where('lHoliday_IdNo',$lHolidayIdNo)->update($aHdArr);
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
	        $aHolidayDtl = Holiday::Select('*')->Where('lCntry_IdNo',$lCntryIdNo)->Where('lState_IdNo',$lStateIdNo)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->OrderBy('sStrt_Dt','DESC')->first()->toArray();	        
	        return $aHolidayDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function HolidayLst($lCntryId = '')
    {
    	try
    	{
			$aHolidayDtl = Holiday::Select('mst_holiday.*','sCntry_Name','sState_Name')
			->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_holiday.lCntry_IdNo')
			->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_holiday.lState_IdNo')
			->Where('mst_holiday.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
			->where(function($query) use ($lCntryId) {
				if (isset($lCntryId) && !empty($lCntryId)) {
					$query->where('mst_holiday.lCntry_IdNo',$lCntryId);
				}
			})
			->OrderBy('mst_holiday.sStrt_Dt','DESC')->paginate(15);
	        return $aHolidayDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function HldyDtl($sDate)
    {
    	try
    	{
    		$aGetHldy = Holiday::whereRaw('"'.$sDate.'" between `sStrt_Dt` and `sEnd_Dt`')->first();
    		return $aGetHldy;
    	}
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function HldyCnt($sDate)
    {
    	try
    	{
    		$aGetHldy = Holiday::whereRaw('"'.$sDate.'" between `sStrt_Dt` and `sEnd_Dt`')->count();
    		return $aGetHldy;
    	}
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }

    }
}
