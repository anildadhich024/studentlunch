<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Category extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_catg';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $lCatgIdNo	= Category::insertGetId($aHdArr);
	        return $lCatgIdNo;
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
	        $nRow	= Category::Where('lCatg_IdNo',$lChldIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function CatgLst($lMilkIdNo, $sCatgName)
    {
    	try
    	{
	        $aCatgLst	= Category::Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
	        			->Where(function($query) use ($sCatgName) {
                            if (isset($sCatgName) && !empty($sCatgName)) {
                                $query->where('sCatg_Name','LIKE', "%".$sCatgName."%");
                            }
                        })
                        ->Where('lMilk_IdNo',$lMilkIdNo)->OrderBy('lCatg_IdNo','desc')->paginate(15);
	        return $aCatgLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
    public function CatgLstFlt($lMilkIdNo, $sCatgName = '')
    {
    	try
    	{
	        $aCatgLst	= Category::select('lCatg_IdNo', 'lCatg_Unq_Id', 'sCatg_Name', 'nBlk_UnBlk')->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('nBlk_UnBlk',config('constant.STATUS.UNBLOCK'))->Where('lMilk_IdNo',$lMilkIdNo)
	        	->Where(function($query) use ($sCatgName) {
                    if (isset($sCatgName) && !empty($sCatgName)) {
                        $query->where('sCatg_Name','LIKE', "%".$sCatgName."%");
                    }
                })->get()->toArray();
	        return $aCatgLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function ExlRcrd($lMilkIdNo, $sCatgName = '')
    {
        try
        {
            $aCatgLst   = Category::Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('lMilk_IdNo',$lMilkIdNo)
                        ->Where(function($query) use ($sCatgName) {
                            if (isset($sCatgName) && !empty($sCatgName)) {
                                $query->where('sCatg_Name','LIKE', "%".$sCatgName."%");
                            }
                        })->get()->toArray();
            return $aCatgLst;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
    }

    public function IsCatgExist($sCatgName, $lMilkIdNo, &$lCatgIdNo)
    {
        try
        {
            $yCatgStatus = False;
            $oCatgRec   = Category::Select('lCatg_IdNo')->Where('sCatg_Name',$sCatgName)->Where('lMilk_IdNo', $lMilkIdNo)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
            if(!empty($oCatgRec->lCatg_IdNo))
            {
                $yCatgStatus = True;
                $lCatgIdNo   = $oCatgRec->lCatg_IdNo;
            }
            return $yCatgStatus;
        }
        catch(\Expection $e)
        {
            return $e->getMessage();
        }   
    }
}
