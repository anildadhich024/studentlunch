<?
namespace App\Helpers; 
use DB;
use Excel;

class Helper 
{
	public function GetSingleField($table,$selectName,$id,$columnName){
		$result=DB::table($table)->select($selectName)->where($columnName,$id)->first();
		return $result->$selectName;
	}
	public function GetColName($nColNo)
	{
		if($nColNo <= 26) {
			$GetColName = Chr(64 + $nColNo);
		} else {
			$GetColName = Chr(64 + floor($nColNo / 26) +
                  (($nColNo / 26) - floor($nColNo / 26) == 0 ? -1 : 0)).
				  Chr(64 + $nColNo - (26 * floor($nColNo / 26)) + 
                  (($nColNo / 26) - floor($nColNo / 26) == 0 ? 26 : 0));
		}
		return $GetColName;
	}

	public function MakeBlankCell($lStartRow, $lNoOfRows, $nStartCol, $nEndCol, $sheet, $sBgColr = '')
	{
		$lRow=$lStartRow;
		$nCol=$nStartCol;
		$lNoOfRows = $lRow + ($lNoOfRows - 1);
		
		for($lRow==$lStartRow;$lRow<=$lNoOfRows; $lRow++) { 
			for($nCol==$nStartCol;$nCol<=$nEndCol;$nCol++){
				self::SetCell($nCol, $lRow, '', $sheet, '', $sBgColr, '', True);
			}
		}
	}

	public function SetCell($sStartCol, $lRow, $sValue, $sheet, $sEndCol, $sBgColr, $sAling, $yBold, $vFormate = '', $yWrap = False, $nWidth = '', $nMrgCell = '', $nFntSize = '', $sFntClr = '')
	{
		$Col1 = self::GetColName($sStartCol).$lRow;
		if(!empty($sEndCol)) {
			if($sStartCol != $sEndCol) {
				$Col2 = self::GetColName($sEndCol).$lRow;
				$sheet->mergeCells($Col1.':'.$Col2);
			} else {
				if(!empty($nMrgCell)) {
					$Col2 = self::GetColName($sStartCol).($lRow+$nMrgCell);
					$sheet->mergeCells($Col1.':'.$Col2);
				} else {
					$Col2 = self::GetColName($sStartCol).($lRow+1);
					$sheet->mergeCells($Col1.':'.$Col2);
				}
			}
		}

		$sheet->cell($Col1, function($cell) use ($sheet, $Col1, $sValue, $sBgColr, $sAling, $yBold, $vFormate, $yWrap, $nWidth, $sStartCol, $nFntSize, $sFntClr)  {
        	$cell->setValue($sValue); 
        	$cell->setBorder('thin','thin','thin','thin');
        	$cell->setValignment('center');
        	if(!empty($sBgColr)) {
        		$cell->setBackground($sBgColr);
        	}

        	if(!empty($sAling)) {
        		$cell->setAlignment($sAling);	
        	}
        	if($yBold){
        		$cell->setFont(array(
	                'bold'=>true
	            ));
        	}
        	if(!empty($vFormate)) {
        		$sheet->setColumnFormat(array(
				    $Col1 => $vFormate
				));
        	}
        	if($yWrap) {
        		$sheet->getStyle($Col1)->getAlignment()->setWrapText(true);
        	}
        	if(!empty($nWidth)) {
	        	$sheet->setWidth(self::GetColName($sStartCol), $nWidth);
	        }
	        if(!empty($nFntSize)) {
	        	$cell->setFontSize($nFntSize);
	        }
	        $cell->setFontColor($sFntClr);
        });
	}
}
?>