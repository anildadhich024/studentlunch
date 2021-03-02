<?
namespace App\Helpers; 
use DB;
use Excel;

class CategoryExport extends Helper  
{
	public static function GnrtExlRtp($sheet, $oCatgLst)
	{
		self::SetExlHeader($sheet, $lRaw);
		//self::SetExlData($lRaw, $sheet, $sRsoIdNo, $sFromDate, $sToDate, $lLstRaw);
	}

	public static function SetExlHeader($sheet, &$lRaw)
	{
		$lRaw = 1;
		Helper::SetCell(config('excel.XL_CATG.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'left', True, '', False, 8, '', 10);
		Helper::SetCell(config('constant.XL_CATG.CATG_ID'), $lRaw, 'Unique Id', $sheet, '', '#F2DDDC', 'left', True, '', False, 7, '', 10);
		Helper::SetCell(config('constant.XL_CATG.CATG_NAME'), $lRaw, 'Category Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 25, '', 10);
		Helper::SetCell(config('constant.XL_CATG.CATG_STATUS'), $lRaw, 'Status', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
	}

	public static function SetExlData($lRaw, $sheet, $sRsoIdNo, $sFromDate, $sToDate, &$lLstRaw)
	{
		$sTranDate = $sFromDate;
		while($sTranDate <= $sToDate) 
		{
			$oReSet = self::ReSet($sTranDate, $sRsoIdNo);
			if(isset($oReSet)) 
			{	
				$lRaw = $lRaw + 1;
				Helper::SetCell(config('constant.XL_CATG.COL_DATE'), $lRaw, date('d-M-Y', strtotime($sTranDate)), $sheet, '', '#F2DDDC', 'left', False, '', False, 11, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_DAY'), $lRaw, date('D', strtotime($sTranDate)), $sheet, '', '#F2DDDC', 'left', False, '', False, 7, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_FOOT'), $lRaw, $oReSet[0]->FtFll, $sheet, '', '#F2DDDC', 'center', True, '', True, 10, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_CASH'), $lRaw, $oReSet[0]->RprOrd, $sheet, '', '', 'right', False, '', True, 10, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_BKNG'), $lRaw, $oReSet[0]->AdvBkng+$oReSet[0]->PryOrd, $sheet, '', '', 'right', False, '', True, 10, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_MEET'), $lRaw, $oReSet[0]->FtFll, $sheet, '', '', 'right', True, '', False, 10, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_WLK_N'), $lRaw, $oReSet[0]->Ttlwlk-$oReSet[0]->Rptwlk, $sheet, '', '', 'right', False, '', False, 8, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_WLK_R'), $lRaw, $oReSet[0]->Rptwlk, $sheet, '', '', 'right', False, '', False, 8, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_WLK_T'), $lRaw, $oReSet[0]->Ttlwlk, $sheet, '', '#F2DDDC', 'right', True, '', False, 8, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_CONV_N'), $lRaw, $oReSet[0]->CurrCust, $sheet, '', '', 'right', False, '', False, 8, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_CONV_R'), $lRaw, $oReSet[0]->RptCust, $sheet, '', '', 'right', False, '', False, 8, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_CONV_T'), $lRaw, $oReSet[0]->CurrCust+$oReSet[0]->RptCust, $sheet, '', '#F2DDDC', 'right', True, '', False, 8, '', 10);

				Helper::SetCell(config('constant.XL_CATG.COL_NP_N'), $lRaw, '='.Helper::GetColName(config('constant.XL_CATG.COL_WLK_N')).$lRaw.'-'.Helper::GetColName(config('constant.XL_CATG.COL_CONV_N')).$lRaw, $sheet, '', '', 'right', False, '', False, 8, '', 10);

				Helper::SetCell(config('constant.XL_CATG.COL_NP_R'), $lRaw, '='.Helper::GetColName(config('constant.XL_CATG.COL_WLK_R')).$lRaw.'-'.Helper::GetColName(config('constant.XL_CATG.COL_CONV_R')).$lRaw, $sheet, '', '', 'right', False, '', False, 8, '', 10);

				Helper::SetCell(config('constant.XL_CATG.COL_NP_T'), $lRaw, '='.Helper::GetColName(config('constant.XL_CATG.COL_NP_N')).$lRaw.'+'.Helper::GetColName(config('constant.XL_CATG.COL_NP_R')).$lRaw, $sheet, '', '#F2DDDC', 'right', True, '', False, 8, '', 10);

				Helper::SetCell(config('constant.XL_CATG.COL_CONVP_N'), $lRaw, '=IFERROR('.Helper::GetColName(config('constant.XL_CATG.COL_CONV_N')).$lRaw.'/'.Helper::GetColName(config('constant.XL_CATG.COL_WLK_N')).$lRaw.',0)', $sheet, '', '', 'right', False, '0.00%', False, 8, '', 10);

				Helper::SetCell(config('constant.XL_CATG.COL_CONVP_R'), $lRaw, '=IFERROR('.Helper::GetColName(config('constant.XL_CATG.COL_CONV_R')).$lRaw.'/'.Helper::GetColName(config('constant.XL_CATG.COL_WLK_R')).$lRaw.',0)', $sheet, '', '', 'right', False, '0.00%', False, 8, '', 10);

				Helper::SetCell(config('constant.XL_CATG.COL_CONVP_T'), $lRaw, '=IFERROR('.Helper::GetColName(config('constant.XL_CATG.COL_CONV_T')).$lRaw.'/'.Helper::GetColName(config('constant.XL_CATG.COL_WLK_T')).$lRaw.',0)', $sheet, '', '#F2DDDC', 'right', True, '0.00%', False, 8, '', 10);

				Helper::SetCell(config('constant.XL_CATG.COL_CALL'), $lRaw, $oReSet[0]->TtlCall, $sheet, '', '', 'right', False, '', False, 7, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_SMS'), $lRaw, $oReSet[0]->TtlSMS, $sheet, '', '', 'right', False, '', False, 7, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_EMAIL'), $lRaw, $oReSet[0]->TtlEmail, $sheet, '', '', 'right', False, '', False, 7, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_PAPR'), $lRaw, $oReSet[0]->TtlPaper, $sheet, '', '', 'right', False, '', False, 7, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_HRDNG'), $lRaw, $oReSet[0]->TtlHrdng, $sheet, '', '', 'right', False, '', False, 7, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_RDIO'), $lRaw, $oReSet[0]->TtlRdio, $sheet, '', '', 'right', False, '', False, 7, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_OTH'), $lRaw, $oReSet[0]->TtlOthr, $sheet, '', '', 'right', True, '', False, 7, '', 10);
				Helper::SetCell(config('constant.XL_CATG.COL_RMRK'), $lRaw, $oReSet[0]->Rmrk, $sheet, '', '', 'left', False, '', False, 25);
			}
			$sTranDate = date('Y-m-d', strtotime($sTranDate . ' +1 day'));		
		}
		$lLstRaw = $lRaw;
	}
}
?>