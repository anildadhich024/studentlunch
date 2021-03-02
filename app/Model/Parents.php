<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Parents extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_prnts';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $nRow	= Parents::insertGetId($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function UpDtRecrd($aHdArr, $lPrntIdNo)
    {
    	try
    	{
	        $nRow	= Parents::Where('lPrnt_IdNo',$lPrntIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function PrntsLst($sPrntName = '', $sMobileNo = '')
    {
    	try
    	{
	        $aPrntsLst	= Parents::Select('mst_prnts.*','mst_state.sState_Name','mst_cntry.sCntry_Name')
	        				->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_prnts.lState_IdNo')
	        				->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_prnts.lCntry_IdNo')
	        				->Where('mst_prnts.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
	        				->Where(function($query) use ($sPrntName) {
	                            if (isset($sPrntName) && !empty($sPrntName)) {
	                                $query->where('sFrst_Name','LIKE', "%".$sPrntName."%");
	                            }
	                        })
	                        ->OrWhere(function($query) use ($sPrntName) {
	                            if (isset($sPrntName) && !empty($sPrntName)) {
	                                $query->where('sLst_Name','LIKE', "%".$sPrntName."%");
	                            }
	                        })
	                        ->Where(function($query) use ($sMobileNo) {
	                            if (isset($sMobileNo) && !empty($sMobileNo)) {
	                                $query->where('sMobile_No','LIKE', "%".$sMobileNo."%");
	                            }
	                        })->OrderBy('mst_prnts.lPrnt_IdNo','desc')
	        				->paginate(10);
	        return $aPrntsLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function PrntsDtl($lPrntIdNo)
    {
    	try
    	{
	        $aPrntsDtl	= Parents::Select('mst_prnts.*','mst_cntry.sCntry_Name','mst_state.sState_Name')->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_prnts.lState_IdNo')->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_prnts.lCntry_IdNo')->Where('lPrnt_IdNo',$lPrntIdNo)->first()->toArray();
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
	        $aPrntsDtl	= Parents::Select('sFrst_Name','sLst_Name','sEmail_Id')->Where('sEmail_Id',$sEmailId)->first()->toArray();
	        return $aPrntsDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function LocDtl($lPrntIdNo)
    {
    	try
    	{
	        $aPrntsDtl	= Parents::Select('lCntry_IdNo','lState_IdNo','sStrp_CustId','sStrp_CardId','nPln_Status')->Where('lPrnt_IdNo',$lPrntIdNo)->first()->toArray();
	        return $aPrntsDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
	
	public function IsUserExist($sEmailId, $sLgnPass, &$aParentsDtl)
	{
		try
		{
			$yUserExist = False;
			$aParentsDtl	= Parents::Where('sLgn_Pass', md5($sLgnPass))->Where('sEmail_Id', $sEmailId)->first();
			if(!empty($aParentsDtl))
			{
				$yUserExist 	= True;
				$aParentsDtl	= $aParentsDtl->toArray();
			}
	        return $yUserExist;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}

	public function IsEmailExist($sEmailId, $lPrntIdNo)
	{
		try
		{
			$yEmailExist = False;
			$oParentsDtl	= Parents::Where('lPrnt_IdNo', $lPrntIdNo)->Where('sEmail_Id', $sEmailId)->first();
			if(!empty($oParentsDtl->lPrnt_IdNo))
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
			$yEmailExist 	= False;
			$oParentsDtl	= Parents::Where('sEmail_Id', $sEmailId)->first();
			if(!empty($oParentsDtl->lPrnt_IdNo))
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

	public function IsPassExist($sLgnPass, $lPrntIdNo)
	{
		try
		{
			$yPassExist = False;
			$aPrntDtl	= Parents::Where('sLgn_Pass', md5($sLgnPass))->Where('lPrnt_IdNo', $lPrntIdNo)->first();
			if(isset($aPrntDtl) && !empty($aPrntDtl->lPrnt_IdNo))
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

	public function GetOtp($sEmailId, $lCode)
	{
		try
		{
			$nAfftd	= Parents::Where('sEmail_Id', $sEmailId)->update(array('lRst_Code' => $lCode));
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
			$nAfftd	= Parents::Where('sEmail_Id', $sEmailId)->Where('lRst_Code', $lCode)->update(array('sLgn_Pass' => $sLgnPass, 'lRst_Code' => NULL));
			return $nAfftd;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}
	
	public function ExlRcrd($sPrntName = '', $sMobileNo = '')
    {
    	try
    	{
	        $aPrntsLst	= Parents::Select('mst_prnts.*','mst_cntry.sCntry_Name','mst_state.sState_Name')->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_prnts.lState_IdNo')->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_prnts.lCntry_IdNo')->Where('mst_prnts.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
	        				->Where(function($query) use ($sPrntName) {
	                            if (isset($sPrntName) && !empty($sPrntName)) {
	                                $query->where('sFrst_Name','LIKE', "%".$sPrntName."%");
	                            }
	                        })
	                        ->OrWhere(function($query) use ($sPrntName) {
	                            if (isset($sPrntName) && !empty($sPrntName)) {
	                                $query->where('sLst_Name','LIKE', "%".$sPrntName."%");
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
	        $aCntRec = Parents::Select(DB::raw('COUNT(*) As nTtlRec'))->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first()->toArray();
	        return $aCntRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function StrpDtl()
    {
    	try
    	{
	        $aPrntsDtl	= Parents::Select('lPrnt_IdNo','sFrst_Name','sLst_Name','sEmail_Id','sStrp_CustId','sCurr_Code','mst_cntry.lCntry_IdNo','mst_state.lState_IdNo')->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_prnts.lCntry_IdNo')->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_prnts.lState_IdNo')->whereNotNull('sStrp_CustId')->Where('nPln_Status',config('constant.PRNT_PLN.PAID'))->get()->toArray();
	        return $aPrntsDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
}
