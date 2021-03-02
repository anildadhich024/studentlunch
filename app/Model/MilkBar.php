<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class MilkBar extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_milk_bar';

    public function InsrtRecrd($aHdArr)
    {
    	try
    	{
	        $lMilkIdNo	= MilkBar::insertGetId($aHdArr);
	        return $lMilkIdNo;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function UpDtRecrd($aHdArr, $lMilkIdNo)
    {
    	try
    	{
	        $nRow	= MilkBar::Where('lMilk_IdNo',$lMilkIdNo)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function MlkBarLst($sMilkName = '', $sMobileNo = '')
    {
    	try
    	{
	        $aMlkBarLst	= 	MilkBar::Select('mst_milk_bar.*', 'mst_cntry.sCntry_Name','mst_state.sState_Name')
	        				->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_milk_bar.lState_IdNo')
	        				->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_milk_bar.lCntry_IdNo')
	        				->Where('mst_milk_bar.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
	        				->where(function($query) use ($sMilkName) {
	                            if (isset($sMilkName) && !empty($sMilkName)) {
	                                $query->where('sBuss_Name','LIKE', "%".$sMilkName."%");
	                            }
	                        })
	                        ->where(function($query) use ($sMobileNo) {
	                            if (isset($sMobileNo) && !empty($sMobileNo)) {
	                                $query->where('sMobile_No','LIKE', "%".$sMobileNo."%");
	                            }
	                        })
	        				->OrderBy('mst_milk_bar.lMilk_IdNo','desc')->paginate(10);
	        return $aMlkBarLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function MilkDtl($lMilkIdNo)
    {
    	try
    	{
	        $aMilkDtl	= MilkBar::Select('mst_milk_bar.*','mst_cntry.sCntry_Name','mst_state.sState_Name')->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_milk_bar.lState_IdNo')->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_milk_bar.lCntry_IdNo')->Where('lMilk_IdNo',$lMilkIdNo)->first()->toArray();
	        return $aMilkDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
    public function ShrtDtl($sRecData)
    {
    	try
    	{
	        $aMilkDtl	= MilkBar::Select('sFrst_Name','sLst_Name','sEmail_Id','sStrp_Acc_Id','lCntry_IdNo','lState_IdNo')->Where('sEmail_Id',$sRecData)->OrWhere('lMilk_IdNo',$sRecData)->first()->toArray();
	        return $aMilkDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
    public function StrpAccDtl($lMilkIdNo)
    {
    	try
    	{
	        $aMilkDtl	= MilkBar::Select('sStrp_Acc_Id')->Where('lMilk_IdNo',$lMilkIdNo)->first()->toArray();
	        return $aMilkDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
		
	public function IsUserExist($sEmailId, $sLgnPass, &$aMilkDtl)
	{
		try
		{
			$yUserExist = False;
			$aMilkDtl	= MilkBar::Where('sLgn_Pass', md5($sLgnPass))->Where('sEmail_Id', $sEmailId)->first();
			if(isset($aMilkDtl) && !empty($aMilkDtl))
			{
				$yUserExist = True;
				$aMilkDtl	= $aMilkDtl->toArray();
			}
	        return $yUserExist;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}

	public function IsEmailExist($sEmailId, $lMilkIdNo)
	{
		try
		{
			$yEmailExist = False;
			$oMilkDtl	= MilkBar::Where('lMilk_IdNo', $lMilkIdNo)->Where('sEmail_Id', $sEmailId)->first();
			if(!empty($oMilkDtl->lMilk_IdNo))
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
			$oMilkDtl	= MilkBar::Where('sEmail_Id', $sEmailId)->first();
			if(!empty($oMilkDtl->lMilk_IdNo))
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

	public function IsPassExist($sLgnPass, $lMilkIdNo)
	{
		try
		{
			$yPassExist = False;
			$aMilkDtl	= MilkBar::Where('sLgn_Pass', md5($sLgnPass))->Where('lMilk_IdNo', $lMilkIdNo)->first();
			if(isset($aMilkDtl) && !empty($aMilkDtl->lMilk_IdNo))
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
			$nAfftd	= MilkBar::Where('sEmail_Id', $sEmailId)->update(array('lRst_Code' => $lCode));
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
			$nAfftd	= MilkBar::Where('sEmail_Id', $sEmailId)->where('lRst_Code', $lCode)->update(array('sLgn_Pass' => $sLgnPass, 'lRst_Code' => NULL));
	        return $nAfftd;
		}
		catch(\Expection $e)
		{
			return $e->getMessage();
		}
	}
	
	public function ExlRcrd($sMilkName = '', $sMobileNo = '')
    {
    	try
    	{
	        $aMlkBarLst	= 	MilkBar::Select('mst_milk_bar.*','mst_cntry.sCntry_Name','mst_state.sState_Name')->leftjoin('mst_state','mst_state.lState_IdNo','=','mst_milk_bar.lState_IdNo')->leftjoin('mst_cntry','mst_cntry.lCntry_IdNo','=','mst_milk_bar.lCntry_IdNo')->Where('mst_milk_bar.nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
	        				->where(function($query) use ($sMilkName) {
	                            if (isset($sMilkName) && !empty($sMilkName)) {
	                                $query->where('sBuss_Name','LIKE', "%".$sMilkName."%");
	                            }
	                        })
	                        ->where(function($query) use ($sMobileNo) {
	                            if (isset($sMobileNo) && !empty($sMobileNo)) {
	                                $query->where('sMobile_No',$sMobileNo);
	                            }
	                        })
	        				->get()->toArray();
	        return $aMlkBarLst;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
    
    public function FltrMilkLst($nBlkUnBlk = '')
    {
    	try
    	{
	        $aMlkBarLst	= 	MilkBar::Select('lMilk_IdNo','sBuss_Name')->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))
	        				 ->where(function($query) use ($nBlkUnBlk) {
	                            if (isset($nBlkUnBlk) && !empty($nBlkUnBlk)) {
	                                $query->where('nBlk_UnBlk',$nBlkUnBlk);
	                            }
	                        })
	        				->OrderBy('sBuss_Name')->get()->toArray();
	        return $aMlkBarLst;
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
	        $aCntRec = MilkBar::Select(DB::raw('COUNT(*) As nTtlRec'))->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first()->toArray();
	        return $aCntRec;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }

    public function BussName($lMilkIdNo)
    {
    	try
    	{
	        $aMlkBar	= 	MilkBar::Select('sBuss_Name')->Where('lMilk_IdNo',$lMilkIdNo)->first()->toArray();
	        return $aMlkBar;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }	
    }
}
