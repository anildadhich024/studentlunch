<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Country extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_cntry';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $nRow	= Country::insert($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function UpDtRecrd($aHdArr, $lCntryIdNo)
    {
    	try
    	{
	        $nRow	= Country::Where('lCntry_IdNo',$lCntryIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

	public function CntryExist($sCntryName)
    {
    	try
    	{
	        $aGetRec = Country::Select('lCntry_IdNo')->Where('sCntry_Name',$sCntryName)->Where('nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
	        return $aGetRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
	}
	
	public function CntryExistWhere($sCntryName,$lCntryIdNo)
    {
    	try
    	{
	        $aGetRec = Country::Select('lCntry_IdNo')->Where('sCntry_Name',$sCntryName)->Where('lCntry_IdNo','!=',$lCntryIdNo)->Where('nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
	        return $aGetRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
	}
	

    public function FrntLst()
    {
    	try
    	{
	        $aGetRec = Country::Select('lCntry_IdNo','sCntry_Name','sCntry_Code')->Where('nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->OrderBy('sCntry_Name')->get()->toArray();
	        return $aGetRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function CntryLst($sCntryName)
    {
    	try
    	{
	        $aGetRec = Country::Where(function($query) use ($sCntryName) {
                            if (isset($sCntryName) && !empty($sCntryName)) {
                                $query->where('sCntry_Name', 'LIKE', "%".$sCntryName."%");
                            }
                        })->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->OrderBy('sCntry_Name')->paginate(15);
	        return $aGetRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function TaxDtl($lCntryIdNo, $lStateIdNo)
    {
    	try
    	{
	        $aGetRec = Country::Select('nTax_Mtdh','dTax_Per','sCurr_Code')->leftjoin('mst_state','mst_state.lCntry_IdNo','=','mst_cntry.lCntry_IdNo')->Where('mst_state.lCntry_IdNo',$lCntryIdNo)->Where('lState_IdNo',$lStateIdNo)->first()->toArray();
	        return $aGetRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
}
