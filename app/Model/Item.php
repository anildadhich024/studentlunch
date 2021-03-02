<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Item extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_item';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $nRow	= Item::insert($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function UpDtRecrd($aHdArr, $lChldIdNo)
    {
    	try
    	{
	        $nRow	= Item::Where('lItem_IdNo',$lChldIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function ItemLst($lMilkIdNo, $lCatgIdNo, $sItemName)
    {
    	try
    	{
	        $aCatgLst	= Item::Select('mst_item.*','mst_catg.sCatg_Name')->leftjoin('mst_catg', 'mst_catg.lCatg_IdNo', '=', 'mst_item.lCatg_IdNo')
	        			->Where(function($query) use ($lCatgIdNo, $sItemName) {
                            if (isset($lCatgIdNo) && !empty($lCatgIdNo)) {
                                $query->where('mst_item.lCatg_IdNo',$lCatgIdNo);
                            }
                            if (isset($sItemName) && !empty($sItemName)) {
                                $query->where('sItem_Name','LIKE', "%".$sItemName."%");
                            }
                        })->Where('mst_item.lMilk_IdNo', $lMilkIdNo)
                        ->Where('mst_catg.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
                        ->Where('mst_item.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->OrderBy('mst_item.lItem_IdNo','desc')->paginate(15);
	        return $aCatgLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function ItemLstTkt($lMilkIdNo)
    {
        try
        {
            $aItmLst   = Item::Select('lItem_IdNo','sItem_Name')->Where('lMilk_IdNo', $lMilkIdNo)->OrderBy('sItem_Name')->get()->toArray();
            return $aItmLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
    }

    public function CntItm($lMilkIdNo)
    {
    	try
    	{
    		$CntItm = Item::Select(DB::raw('COUNT(lItem_IdNo) As TtlRec'))->Where('lMilk_IdNo',$lMilkIdNo)->first();
    		return $CntItm;
    	}
    	catch(\Expection $e)
    	{
    		return $e->getMessage();
    	}
    }
	
	public function ItemCtgryLst($lMilkIdNo)
    {
    	try
    	{
	        $aCatgLst	= Item::Where('mst_item.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
						->Where('mst_item.nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))
						->Where('mst_item.aItem_Week', 'like', '%'.config('constant.WEEK.'.strtoupper(date("l"))).'%')
	        			->join('mst_catg', 'mst_catg.lCatg_IdNo', '=', 'mst_item.lCatg_IdNo')
						->Where('mst_catg.lMilk_IdNo', $lMilkIdNo)->OrderBy('mst_item.lCatg_IdNo')->get()->toArray();
	        return $aCatgLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
	
	public function CartItemLst($aCrtItems)
    {
    	try
    	{
	        $aCatgLst	= Item::join('mst_catg', 'mst_catg.lCatg_IdNo', '=', 'mst_item.lCatg_IdNo')
						->WhereIn('mst_item.lItem_IdNo', $aCrtItems)->OrderBy('mst_catg.lCatg_IdNo','desc')
	        				->paginate(10);
	        return $aCatgLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function ExlRcrd($lMilkIdNo, $lCatgIdNo, $sItemName)
    {
    	try
    	{
	        $aCatgLst	= Item::Select('mst_item.*','mst_catg.sCatg_Name')->leftjoin('mst_catg', 'mst_catg.lCatg_IdNo', '=', 'mst_item.lCatg_IdNo')
	        			->Where(function($query) use ($lCatgIdNo, $sItemName) {
                            if (isset($lCatgIdNo) && !empty($lCatgIdNo)) {
                                $query->where('mst_item.lCatg_IdNo',$lCatgIdNo);
                            }
                            if (isset($sItemName) && !empty($sItemName)) {
                                $query->where('sItem_Name','LIKE', "%".$sItemName."%");
                            }
                        })->Where('mst_item.lMilk_IdNo', $lMilkIdNo)
                        ->Where('mst_catg.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
                        ->Where('mst_item.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->get()->toArray();
	        return $aCatgLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
	public function DtlItm($lItemIdNo)
    {
    	try
    	{
    		$ItmDtl = Item::join('mst_catg', 'mst_catg.lCatg_IdNo', '=', 'mst_item.lCatg_IdNo')
						->Where('mst_item.lItem_IdNo', $lItemIdNo)->first();
    		return $ItmDtl;
    	}
    	catch(\Expection $e)
    	{
    		return $e->getMessage();
    	}
    }

    public function IsItmExst($sItmName, $lCatgIdNo, $sItemPrc, $lItemIdNo)
    {
        try
        {
            $yItmStatus = False;
            $oCntItm   = Item::Select(DB::raw('COUNT(lItem_IdNo) as nTtlRec'))->Where('sItem_Name', $sItmName)->Where('lCatg_IdNo', $lCatgIdNo)->Where('sItem_Prc', $sItemPrc)->Where('lItem_IdNo','!=', $lItemIdNo)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
            if($oCntItm->{'nTtlRec'} == 1)
            {
                $yItmStatus = True;
            }
            return $yItmStatus;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
	}

	public function IsItmExstAdd($sItmName, $lCatgIdNo)
    {
        try
        {
            $yItmStatus = False;
            $oCntItm   = Item::Select('lItem_IdNo')->Where('sItem_Name', $sItmName)->Where('lCatg_IdNo', $lCatgIdNo)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
            if($oCntItm)
            {
                $yItmStatus = True;
            }
            return $yItmStatus;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
	}

	public function ItmDtl($lItemIdNo)
    {
        try
        {
            $aCatgLst	= Item::Select('mst_item.*','mst_catg.sCatg_Name')->leftjoin('mst_catg', 'mst_catg.lCatg_IdNo', '=', 'mst_item.lCatg_IdNo')
						->Where('lItem_IdNo', $lItemIdNo)
                        ->Where('mst_item.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
	        return $aCatgLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
    }

    public function ItmVrnt($lItemIdNo)
    {
        try
        {
            $aCatgLst   = Item::Select('sMenu_Variant')->Where('lItem_IdNo', $lItemIdNo)->first()->toArray();
            return $aCatgLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
    }
}