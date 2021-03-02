<?php
namespace App\Http\Controllers\MilkbarController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MilkBarAuth;
use App\Model\Category;
use App\Model\Item;
use App\Model\Variant;
use DB;
use Excel;

class VariantController extends Controller
{
	public function __construct()
	{
		$this->Category 	= new Category;
		$this->Item 		= new Item;
		$this->Variant 		= new Variant;
		$this->middleware(MilkBarAuth::class);
	}

	public function ListPage(Request $request)
	{
		$lMilkIdNo 	= session('USER_ID'); 
		$sVariantName  = $request['sVariantName'];  
		$aVariantLst	= $this->Variant->VariantLst($lMilkIdNo, $sVariantName);
		$sTitle 	= "Manage Variant";
    	$aData 		= compact('sTitle','aVariantLst','request');
        return view('milkbar_panel.variant_list',$aData);
	}

	 

	public function SaveCntrl(Request $request)
	{ 
		
		$lVariantIdNo 	= base64_decode($request['lVariantIdNo']);
		$rules = [
	        'sVariantName' 	=> 'required|max:30', 
	    ];

		//create milki bar session 
		$sarray=array();
		$i=1;
		for($i==1;$i<=$request['nTtlRec'];$i++)
		{
			if(!empty($request['sVarItemName'.$i]))
			{
				$rules['sVarItemName'.$i]	= 'required|max:30';
				$sarray[$i]=array('sVarItemName'=>$request['sVarItemName'.$i]);  				
			}
		}
	    $this->validate($request, $rules, config('constant.VLDT_MSG'));
	    try
		{
		    $aHdArr 	= $this->HdArr($request); 
			if($lVariantIdNo == 0)
			{
				$yItmStatus = $this->Variant->IsItmExstAdd($request['sVariantName']);
			}else{
				$yItmStatus = $this->Variant->IsItmExst($request['sVariantName'], $lVariantIdNo);
			}
		    if(!$yItmStatus)
		    { 
			    	if($lVariantIdNo == 0)
			    	{
						$this->InsrtArr($aHdArr);
						$lVariantIdNo	= $this->Variant->InsrtRecrd($aHdArr);
						// print_r($lVariantIdNo);exit;
						if(!empty($lVariantIdNo) && $lVariantIdNo > 0)
						{
							$i=1;
							for($i==1;$i<=$request['nTtlRec'];$i++)
							{
								if(!empty($request['sVarItemName'.$i]))
								{
									$aItemArr = $this->ItemArr($lVariantIdNo, $request['sVarItemName'.$i]);
									
									$this->Variant->InsrtItemRecrd($aItemArr);
								}
							} 
							$sMessage	= "Variant created successfully...";
						}
			    	}
			    	else
			    	{
						$this->Variant->UpDtRecrd($aHdArr,$lVariantIdNo);
						$this->Variant->DltItemRecrd($lVariantIdNo);
							$i=1;
							for($i==1;$i<=$request['nTtlRec'];$i++)
							{
								if(!empty($request['sVarItemName'.$i]))
								{
									$aItemArr = $this->ItemArr($lVariantIdNo, $request['sVarItemName'.$i]);
									//  print_r($aItemArr);
									 $this->Variant->InsrtItemRecrd($aItemArr);
								}
							} 
							$sMessage	= "Variant Updated successfully...";
			    	}
			  
			    	return redirect('milkbar_panel/variant/list')->with('Success', $sMessage);				 
			}
			else
			{
					return redirect()->back()->with('Failed', 'Variant already exist...'); 				
			}
		}
		catch(\Exception $e)
		{ 
			return redirect()->back()->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function DelRec(Request $request)
	{
		$lRecIdNo 	= base64_decode($request['lRecIdNo']);
		$sTblName 	= base64_decode($request['sTblName']);
		$sFldName 	= base64_decode($request['sFldName']);
		
		try
		{
			$IsRecExist = DB::table($sTblName)->Where($sFldName,$lRecIdNo)->Where('nDel_Status',config('constant.DEL_STATUS.UNDELETED'))->first();
			if(!isset($IsRecExist))
			{
				return redirect()->back()->with('Failed', 'Record not found...');
			}
			else
			{
				$nRow = DB::table($sTblName)->Where($sFldName,$lRecIdNo)->update(['nDel_Status'=> config('constant.DEL_STATUS.DELETED')]);
				$nRow = DB::table('variant_item')->Where($sFldName,$lRecIdNo)->update(['nDel_Status'=> config('constant.DEL_STATUS.DELETED')]);
				if($nRow > 0)
				{
					return redirect()->back()->with('Success', 'Record deleted successfully...');		
				}
				else
				{
					return redirect()->back()->with('Alert', 'Record did not deleted...');			
				}
			}
		}
		catch(\Exception $e)
		{
			return redirect()->back()->with('Failed', $e->getMessage());
		}
	}


	public function ItemArr($lVariantIdNo, $sVarItemName)
	{
		$aConArr = array(
			"IVariant_IdNo"	=> $lVariantIdNo,
			"sItem_name"	=> $sVarItemName, 
			'nBlk_UnBlk'	=> config('constant.STATUS.UNBLOCK'),
			'nDel_Status'	=> config('constant.DEL_STATUS.UNDELETED'),
		);
		return $aConArr;
	}

	public function HdArr($request)
	{
		$aConArr = array(
			'sVariant_Name' 	=> html_entity_decode(ucfirst($request['sVariantName'])),  
		);
		return $aConArr;
	}

	public function InsrtArr(&$aHdArr)
	{
		$aHdArr['lVar_Unq_Id']	= rand(1001,9999);
		$aHdArr['lMilk_IdNo']	= session('USER_ID');
		$aHdArr['nBlk_UnBlk']	= config('constant.STATUS.UNBLOCK');
		$aHdArr['nDel_Status']	= config('constant.DEL_STATUS.UNDELETED');
	}

	public function ExprtRcrd(Request $request)
	{
		$lMilkIdNo 	= session('USER_ID');
		$sItemName  = $request['sItemName'];
		$lCatgIdNo 	= $request['lCatgIdNo'];
		$oItmLst	= $this->Item->ExlRcrd($lMilkIdNo, $lCatgIdNo, $sItemName);
		if(count($oItmLst) > 0)
		{
			$FileName 	= 'Item_'.date('Ymd').'_'.date('His');
	        Excel::create($FileName, function($excel) use ($oItmLst) {
	            $excel->sheet('Sheet1', function($sheet)  use ($oItmLst) {
	                $this->SetExlHeader($sheet, $lRaw);
	                $this->SetExlData($sheet, $lRaw, $oItmLst);
	            });
	        })->download('xlsx');
	    }
	    else
	    {
	    	return redirect()->back()->with('Failed', 'Record not found...');
	    }
	}

	public function SetExlHeader($sheet, &$lRaw)
	{
		$lRaw = 1;
		Controller::SetCell(config('excel.XL_ITEM.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'right', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_ITEM.ITEM_ID'), $lRaw, 'Unique Id', $sheet, '', '#F2DDDC', 'right', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_ITEM.CATG_NAME'), $lRaw, 'Category Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_ITEM.ITEM_NAME'), $lRaw, 'Item Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_ITEM.ITEM_PRC'), $lRaw, 'Price', $sheet, '', '#F2DDDC', 'right', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_ITEM.ITEM_DESC'), $lRaw, 'Description', $sheet, '', '#F2DDDC', 'left', True, '', True, 35, '', 10);
		Controller::SetCell(config('excel.XL_ITEM.ITEM_DAY'), $lRaw, 'Availability Day', $sheet, '', '#F2DDDC', 'left', True, '', False, 40, '', 10);
		Controller::SetCell(config('excel.XL_ITEM.ITEM_STATUS'), $lRaw, 'Status', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
	}

	public function SetExlData($sheet, $lRaw, $oItmLst)
	{
		$i = 0;
		while(isset($oItmLst) && count($oItmLst) > 0 && $i<count($oItmLst))
		{
			$sWeakName = '';
			$nWeakDay = explode(",", $oItmLst[$i]['aItem_Week']);
			foreach ($nWeakDay as $nWkKey) 
			{
				$sWeakName .= array_search($nWkKey, config('constant.WEEK')).", ";
			}
			$lRaw = $lRaw + 1;

			Controller::SetCell(config('excel.XL_ITEM.SR_NO'), $lRaw, $i+1, $sheet, '', '', 'right', False, '', False, 8, '', 10);
			Controller::SetCell(config('excel.XL_ITEM.ITEM_ID'), $lRaw, $oItmLst[$i]['lItem_Unq_Id'], $sheet, '', '', 'right', False, '', False, 10, '', 10);
			Controller::SetCell(config('excel.XL_ITEM.CATG_NAME'), $lRaw, $oItmLst[$i]['sCatg_Name'], $sheet, '', '', 'left', False, '', False, 20, '', 10);
			Controller::SetCell(config('excel.XL_ITEM.ITEM_NAME'), $lRaw, $oItmLst[$i]['sItem_Name'], $sheet, '', '', 'left', False, '', False, 20, '', 10);
			Controller::SetCell(config('excel.XL_ITEM.ITEM_PRC'), $lRaw, $oItmLst[$i]['sItem_Prc'], $sheet, '', '', 'right', False, '#00.00', False, 8, '', 10);
			Controller::SetCell(config('excel.XL_ITEM.ITEM_DESC'), $lRaw, $oItmLst[$i]['sItem_Dscrptn'], $sheet, '', '', 'left', False, '', True, 35, '', 10);
			Controller::SetCell(config('excel.XL_ITEM.ITEM_DAY'), $lRaw, substr($sWeakName, 0, -2), $sheet, '', '', 'left', False, '', True, 40, '', 10);
			Controller::SetCell(config('excel.XL_ITEM.ITEM_STATUS'), $lRaw, $oItmLst[$i]['nBlk_UnBlk'] == config('constant.STATUS.BLOCK') ? 'Block' : 'Unblock', $sheet, '', '', 'left', False, '', False, 10, '', 10);

			$i++;
		}
	}

	public function ImportCntrl(Request $request)
	{
	   	$rules = [
	        'ItemFile' 	=> 'required',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    $sFileExt = $request->file('ItemFile')->getClientMimeType();
	    if($sFileExt != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
	    {
	    	return redirect('milkbar_panel/item/list')->with('Failed', 'File should be in excel format....');
	    }
	    else
	    {
		    try
		    {
		    	$lMilkIdNo = session('USER_ID');
		    	$sFilePath = $request->file('ItemFile')->getRealPath();

		    	Excel::load($sFilePath, function($reader) use (&$excel, $lMilkIdNo, &$nPndgData, &$nInsRec, &$nDupRec) {
	                $objExcel 		= $reader->getExcel();
	                $sheet 			= $objExcel->getSheet(0);
	                $highestRow 	= $sheet->getHighestRow();
	                $highestColumn 	= $sheet->getHighestColumn();
	                $nPndgData		= 0;
	                $nInsRec		= 0;
	                $nDupRec		= 0;
	                $lItemIdNo		= 0;
	                for($row = 6; $row <= $highestRow; $row++)
	                {

	                	$aItemWeek		= '';
	                    $aItmData = $sheet->rangeToArray('B' . $row . ':' . 'L' . $row,
	                        NULL, TRUE, FALSE);
	                    $sCatgName 	= substr($aItmData[0][0], 0, 30);
	                    $sItmName 	= substr($aItmData[0][1], 0, 50);
	                    $sItmDes 	= substr($aItmData[0][2], 0, 150);
	                    $nDay1 		= $aItmData[0][3];
	                    $nDay2 		= $aItmData[0][4];
	                    $nDay3		= $aItmData[0][5];
	                    $nDay4		= $aItmData[0][6];
	                    $nDay5		= $aItmData[0][7];
	                    $nDay6		= $aItmData[0][8];
	                    $nDay7		= $aItmData[0][9];
	                    $sItmPrc	= number_format($aItmData[0][10], 2);

	                    if(!empty($sCatgName) && !empty($sItmName) && !empty($sItmDes) && !empty($sItmPrc) && (!empty($nDay1) || !empty($nDay2) || !empty($nDay3) || !empty($nDay4) || !empty($nDay5) || !empty($nDay6) || !empty($nDay7)))
	                    {
	                    	if($nDay1 == 1) {
	                    		$aItemWeek .= config('constant.WEEK.MONDAY').',';
	                    	}
	                    	if($nDay2 == 1) {
	                    		$aItemWeek .= config('constant.WEEK.TUESDAY').',';
	                    	}
	                    	if($nDay3 == 1) {
	                    		$aItemWeek .= config('constant.WEEK.WEDNESDAY').',';
	                    	}
	                    	if($nDay4 == 1) {
	                    		$aItemWeek .= config('constant.WEEK.THURSDAY').',';
	                    	}
	                    	if($nDay5 == 1) {
	                    		$aItemWeek .= config('constant.WEEK.FRIDAY').',';
	                    	}
	                    	if($nDay6 == 1) {
	                    		$aItemWeek .= config('constant.WEEK.SATURDAY').',';
	                    	}
	                    	if($nDay7 == 1) {
	                    		$aItemWeek .= config('constant.WEEK.SUNDAY').',';
	                    	}

	                    	$aItemWeek = substr($aItemWeek, 0, -1);
	                    	$yCatgStatus = $this->Category->IsCatgExist($sCatgName, $lMilkIdNo, $lCatgIdNo);
	                    	if(!$yCatgStatus)
	                    	{
	                    		$aCatArr 	= $this->XlsCatgArr($sCatgName, $lMilkIdNo);
	                    		$lCatgIdNo 	= $this->Category->InsrtRecrd($aCatArr);
	                    	}

	                		$yItmStatus = $this->Item->IsItmExst($sItmName, $lCatgIdNo, $sItmPrc, $lItemIdNo);
	                		if(!$yItmStatus)
	                		{
	                			$aHdArr = $this->XlsHdArr($lCatgIdNo, $sItmName, $sItmDes, $sItmPrc, $aItemWeek);
	                			$this->InsrtArr($aHdArr);
	                			$this->Item->InsrtRecrd($aHdArr);
	                    		$nInsRec++;
	                		}
	                		else
	                		{
	                			$nDupRec++;
	                		}
	                    }
	                    else
	                    {
	                    	$nPndgData++;
	                    }   
	                }
	            });
	            $nTtlRec = $nInsRec + $nPndgData + $nDupRec;
	            return redirect('milkbar_panel/item/list')->with('Success', 'File uploaded, Total Records '.$nTtlRec.', Duplicate Records '.$nDupRec.', Corrupted Records '.$nPndgData);
		    }
		    catch(\Exception $e)
			{
				\DB::rollback();
				return redirect('milkbar_panel/item/list')->with('Failed', $e->getMessage().' on Line '.$e->getLine());
			}
		}
	}

	public function XlsHdArr($lCatgIdNo, $sItmName, $sItmDes, $sItmPrc, $aItemWeek)
	{
		$aConArr = array(
			'sItem_Name' 	=> html_entity_decode(ucfirst($sItmName)),
			'sItem_Dscrptn' => html_entity_decode($sItmDes),
			'lCatg_IdNo' 	=> $lCatgIdNo,
			'sItem_Prc' 	=> number_format($sItmPrc, 2),
			'aItem_Week' 	=> $aItemWeek,
		);
		return $aConArr;
	}

	public function XlsCatgArr($sCatgName, $lMilkIdNo)
	{
		$aConArr = array(
			'sCatg_Name' 	=> html_entity_decode(ucfirst($sCatgName)),
			'lCatg_Unq_Id'	=> rand(1001,9999),
			'lMilk_IdNo'	=> $lMilkIdNo,
			'nBlk_UnBlk'	=> config('constant.STATUS.UNBLOCK'),
			'nDel_Status'	=> config('constant.DEL_STATUS.UNDELETED'),
		);
		return $aConArr;
	}
}
?>