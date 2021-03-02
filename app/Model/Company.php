<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Company extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_comp';

    public function UpDtRecrd($aHdArr)
    {
    	try
    	{
	        $nRow	= Company::Where('lComp_IdNo',session('ADMIN_ID'))->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

	public function IsAdminExst($sEmailId, $sLgnPass, &$lCompIdNo)
	{
		try
		{
			$yAdminExst = False;
			$oAdminDtl	= Company::Where('sLgn_Pass', md5($sLgnPass))->Where('sLgn_Email', $sEmailId)->first();
			if(!empty($oAdminDtl))
			{
				$yAdminExst = True;
				$lCompIdNo 	= $oAdminDtl->lComp_IdNo;
			}
	        return $yAdminExst;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}

	public function IsEmailExst($sEmailId, &$aAdminDtl)
	{
		try
		{
			$yAdminExst = False;
			$oAdminDtl	= Company::Where('sLgn_Email', $sEmailId)->first();
			if(!empty($oAdminDtl))
			{
				$yAdminExst = True;
				$aAdminDtl 	= $oAdminDtl->toArray();
			}
	        return $yAdminExst;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}

	public function IsPassExist($sLgnPass)
	{
		try
		{
			$yPassExist = False;
			$aAdminDtl	= Company::Select('lComp_IdNo')->Where('sLgn_Pass', md5($sLgnPass))->Where('lComp_IdNo',session('ADMIN_ID'))->first();
			if(isset($aAdminDtl) && !empty($aAdminDtl->lComp_IdNo))
			{
				$yPassExist = True;
			}
	        return $yPassExist;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}

	public function IsTokenExist($sRstToken)
	{
		try
		{
			$yPassExist = False;
			$aAdminDtl	= Company::Select('lComp_IdNo')->Where('sRst_Token', $sRstToken)->Where('lComp_IdNo',session('ADMIN_ID'))->first();
			if(isset($aAdminDtl) && !empty($aAdminDtl->lComp_IdNo))
			{
				$yPassExist = True;
			}
	        return $yPassExist;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}
	
	public function CompDtl()
	{
		try
		{
			$aCompDtl	= Company::Where('lComp_IdNo',session('ADMIN_ID'))->first()->toArray();
	        return $aCompDtl;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}
}
