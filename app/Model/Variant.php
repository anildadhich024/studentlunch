<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Variant extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_variant';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
            $nRow	= Variant::insertGetId($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function InsrtItemRecrd($aItemArr)
    {
       
        try
    	{
			$nRow	= DB::table('variant_item')->insert($aItemArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
	}

    public function DltItemRecrd($lVariantIdNo)
    {
        try
    	{
	        $nRow	= DB::table('variant_item')->where('IVariant_IdNo',$lVariantIdNo)->delete();
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
	        $nRow	= Variant::Where('IVariant_IdNo',$lChldIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function VariantLst($lMilkIdNo, $sVariantName)
    {
    	try
    	{
	        $aVariantgLst	= Variant::Select('mst_variant.*')
	        			->Where(function($query) use ( $sVariantName) {
                            if (isset($sVariantName) && !empty($sVariantName)) {
                                $query->where('sVariant_Name','LIKE', "%".$sVariantName."%");
                            }
                        })->Where('mst_variant.lMilk_IdNo', $lMilkIdNo)
                        ->Where('mst_variant.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->OrderBy('mst_variant.IVariant_IdNo','desc')->paginate(15);
	        return $aVariantgLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public static function VariantItemLst($sVariantIdNo)
    {
    	try
    	{
	        $aVarItemgLst	= DB::table('variant_item')->Select('variant_item.*')
	        			->where('variant_item.IVariant_IdNo',$sVariantIdNo)
                        ->Where('variant_item.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->get();
	        return $aVarItemgLst;
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
    		$CntItm = Variant::Select(DB::raw('COUNT(lItem_IdNo) As TtlRec'))->Where('lMilk_IdNo',$lMilkIdNo)->first();
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
	        $aCatgLst	= Variant::Where('mst_item.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
						->Where('mst_item.nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))
						->Where('mst_item.aItem_Week', 'like', '%'.config('constant.WEEK.'.strtoupper(date("l"))).'%')
	        			->join('mst_catg', 'mst_catg.lCatg_IdNo', '=', 'mst_item.lCatg_IdNo')
						->Where('mst_catg.lMilk_IdNo', $lMilkIdNo)->get()->toArray();
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
	        $aCatgLst	= Variant::join('mst_catg', 'mst_catg.lCatg_IdNo', '=', 'mst_item.lCatg_IdNo')
						->WhereIn('mst_item.lItem_IdNo', $aCrtItems)
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
	        $aCatgLst	= Variant::Select('mst_item.*','mst_catg.sCatg_Name')->leftjoin('mst_catg', 'mst_catg.lCatg_IdNo', '=', 'mst_item.lCatg_IdNo')
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
    		$ItmDtl = Variant::join('mst_catg', 'mst_catg.lCatg_IdNo', '=', 'mst_item.lCatg_IdNo')
						->Where('mst_item.lItem_IdNo', $lItemIdNo)->first();
    		return $ItmDtl;
    	}
    	catch(\Expection $e)
    	{
    		return $e->getMessage();
    	}
    }

    public function IsItmExst($sVariantName, $IVariantIdNo)
    {
        try
        {
            $yItmStatus = False;
            $oCntItm   = Variant::Select(DB::raw('COUNT(IVariant_IdNo) as nTtlRec'))->Where('sVariant_Name', $sVariantName)->where('IVariant_IdNo','!=', $IVariantIdNo)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
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

	public function IsItmExstAdd($sVariantName	)
    {
        try
        {
            $yItmStatus = False;
            $oCntItm   = Variant::Select('IVariant_IdNo')->Where('sVariant_Name', $sVariantName)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
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

    
	public function ItmDtl($lItemIdNo,$lMilkIdNo)
    {
        try
        {
            $aCatgLst	= Variant::Select('mst_item.*','mst_catg.sCatg_Name')->leftjoin('mst_catg', 'mst_catg.lCatg_IdNo', '=', 'mst_item.lCatg_IdNo')
						->Where('mst_item.lItem_IdNo', $lItemIdNo)
						->Where('mst_item.lMilk_IdNo', $lMilkIdNo)
                        ->Where('mst_catg.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
                        ->Where('mst_item.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
	        return $aCatgLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
    }

    public function ItmVrntLst($lItemIdNo)
    {
        try
        {
            $aItmDtl    = DB::table('mst_item')->Select('sMenu_Variant')->Where('lItem_IdNo',$lItemIdNo)->first()->toArray();
            $aCatgLst   = Variant::Select('IVariant_IdNo','sVariant_Name')
                        ->Where('mst_item.lMilk_IdNo', $lMilkIdNo)
                        ->Where('mst_catg.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
                        ->Where('mst_item.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
            return $aCatgLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
    }
}