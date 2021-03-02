<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Teacher extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_tchr';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $nRow	= Teacher::insertGetId($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function UpDtRecrd($aHdArr, $lTchrIdNo)
    {
    	try
    	{
	        $nRow	= Teacher::Where('lTchr_IdNo',$lTchrIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function TchrLst($sTchrName = '', $sMobileNo = '')
    {
    	try
    	{
	        $aPrntsLst	= Teacher::Select('mst_tchr.*','mst_state.sState_Name','mst_cntry.sCntry_Name')
	        				->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_tchr.lState_IdNo')
	        				->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_tchr.lCntry_IdNo')
	        				->Where('mst_tchr.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
	        				->Where(function($query) use ($sTchrName) {
	                            if (isset($sTchrName) && !empty($sTchrName)) {
	                                $query->where('sFrst_Name','LIKE', "%".$sTchrName."%");
	                            }
	                        })
	                        ->OrWhere(function($query) use ($sTchrName) {
	                            if (isset($sTchrName) && !empty($sTchrName)) {
	                                $query->where('sLst_Name','LIKE', "%".$sTchrName."%");
	                            }
	                        })
	                        ->Where(function($query) use ($sMobileNo) {
	                            if (isset($sMobileNo) && !empty($sMobileNo)) {
	                                $query->where('sMobile_No','LIKE', "%".$sMobileNo."%");
	                            }
	                        })->OrderBy('mst_tchr.lTchr_IdNo','desc')
	        				->paginate(10);
	        return $aPrntsLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function TchrDtl($lTchrIdNo)
    {
    	try
    	{
	        $aPrntsDtl	= Teacher::Select('mst_tchr.*','mst_cntry.sCntry_Name','mst_state.sState_Name')->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_tchr.lState_IdNo')->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_tchr.lCntry_IdNo')->Where('lTchr_IdNo',$lTchrIdNo)->first()->toArray();
	        return $aPrntsDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function TchrRevw($lTchrIdNo)
    {
    	try
    	{
	        $aPrntsDtl	= Teacher::Select('sFrst_Name','sLst_Name','nSchl_Type','sSchl_Name','mst_schl.lSchl_IdNo')
	        ->leftjoin('tchr_schl','tchr_schl.lTchr_IdNo','=','mst_tchr.lTchr_IdNo')->leftjoin('mst_schl','mst_schl.lSchl_IdNo','=','tchr_schl.lSchl_IdNo')->Where('tchr_schl.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->Where('mst_tchr.lTchr_IdNo',$lTchrIdNo)->first()->toArray();
	        return $aPrntsDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
    public function ShrtDtl($sEmailId)
    {
    	try
    	{
	        $aTchrDtl	= Teacher::Select('sFrst_Name','sLst_Name','sEmail_Id')->Where('sEmail_Id',$sEmailId)->OrWhere('lTchr_IdNo',$sEmailId)->first()->toArray();
	        return $aTchrDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function IsEmailExist($sEmailId, $lTchrIdNo)
	{
		try
		{
			$yEmailExist = False;
			$oTeacherDtl	= Teacher::Where('lTchr_IdNo', $lTchrIdNo)->Where('sEmail_Id', $sEmailId)->first();
			if(!empty($oTeacherDtl->lTchr_IdNo))
			{
				$yEmailExist = True;
			}
	        return $yEmailExist;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}

	public function IsMailExist($sEmailId)
	{
		try
		{
			$yEmailExist = False;
			$oTeacherDtl	= Teacher::Where('sEmail_Id', $sEmailId)->first();
			if(!empty($oTeacherDtl->lTchr_IdNo))
			{
				$yEmailExist = True;
			}
	        return $yEmailExist;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}

	public function IsPassExist($sLgnPass, $lTchrIdNo)
	{
		try
		{
			$yPassExist = False;
			$aPrntDtl	= Teacher::Where('sLgn_Pass', md5($sLgnPass))->Where('lTchr_IdNo', $lTchrIdNo)->first();
			if(isset($aPrntDtl) && !empty($aPrntDtl->lTchr_IdNo))
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
	
	public function IsUserExist($sEmailId, $sLgnPass, &$aTeacherDtl)
	{
		try
		{
			$yUserExist = False;
			$aTeacherDtl	= Teacher::Where('sLgn_Pass', md5($sLgnPass))->Where('sEmail_Id', $sEmailId)->first();
			if(!empty($aTeacherDtl))
			{
				$yUserExist 	= True;
				$aTeacherDtl	= $aTeacherDtl->toArray();
			}
	        return $yUserExist;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}

	public function GetOtp($sEmailId, $lCode)
	{
		try
		{
			$nAfftd	= Teacher::Where('sEmail_Id', $sEmailId)->update(array('lRst_Code' => $lCode));
	        return $nAfftd;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}
	
	public function ResetPass($sEmailId, $lCode, $sLgnPass)
	{
		try
		{
			$nAfftd	= Teacher::Where('sEmail_Id', $sEmailId)->Where('lRst_Code', $lCode)->update(array('sLgn_Pass' => $sLgnPass, 'lRst_Code' => NULL));
			return $nAfftd;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}
	
	public function ExlRcrd($sTchrName = '', $sMobileNo = '')
    {
    	try
    	{
	        $aPrntsLst	= Teacher::Select('mst_tchr.*','mst_cntry.sCntry_Name','mst_state.sState_Name')->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_tchr.lState_IdNo')->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_tchr.lCntry_IdNo')->Where('mst_tchr.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
	        				->Where(function($query) use ($sTchrName) {
	                            if (isset($sTchrName) && !empty($sTchrName)) {
	                                $query->where('sFrst_Name','LIKE', "%".$sTchrName."%");
	                            }
	                        })
	                        ->OrWhere(function($query) use ($sTchrName) {
	                            if (isset($sTchrName) && !empty($sTchrName)) {
	                                $query->where('sLst_Name','LIKE', "%".$sTchrName."%");
	                            }
	                        })
	                        ->Where(function($query) use ($sMobileNo) {
	                            if (isset($sMobileNo) && !empty($sMobileNo)) {
	                                $query->where('sMobile_No',$sMobileNo);
	                            }
	                        })
	        				->get()->toArray();
	        return $aPrntsLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function CntRec()
    {
    	try
    	{
	        $aCntRec = Teacher::Select(DB::raw('COUNT(*) As nTtlRec'))->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first()->toArray();
	        return $aCntRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
}
