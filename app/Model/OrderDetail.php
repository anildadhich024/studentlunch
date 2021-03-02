<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class OrderDetail extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_ordr_dt';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $nRow	= OrderDetail::insert($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function ItemLst($lOrdrHdIdNo)
    {
    	try
    	{
	        $aItemLst	= OrderDetail::select('mst_ordr_dt.nItm_Qty', 'mst_ordr_dt.sItem_Prc', 'mst_item.sItem_Name')
			->leftjoin('mst_item', 'mst_item.lItem_IdNo', '=', 'mst_ordr_dt.lItm_IdNo')
			->where('lOrdr_Hd_IdNo', $lOrdrHdIdNo)
			->get()->toArray();
	        return $aItemLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
    public function ExlRcrd($lOrdrHdIdNo)
    {
        try
        {
            $aItmLst  = OrderDetail::Select('mst_ordr_dt.*','sItem_Name')->leftjoin('mst_item', 'mst_item.lItem_IdNo', '=', 'mst_ordr_dt.lItm_IdNo')->where('mst_ordr_dt.lOrdr_Hd_IdNo', $lOrdrHdIdNo)->get()->toArray();
            return $aItmLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
    }

    public function TknRcrd($lOrdrHdIdNo)
    {
        try
        {
            $aItmLst  = OrderDetail::Select('sItem_Name')->leftjoin('mst_item', 'mst_item.lItem_IdNo', '=', 'mst_ordr_dt.lItm_IdNo')->where('mst_ordr_dt.lOrdr_Hd_IdNo', $lOrdrHdIdNo)->get()->toArray();
            return $aItmLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
    }
}
