<?php

namespace App\Http\Controllers;
use Excel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Model\Company;
use Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
	{
		$this->Company 	= new Company; 
    }
    
    public function SendEmail($sEmailId, $sName, $sEmailTamplt, $sEmailSubject, $aEmailData)
    {
        $_REQUEST['sEmilId'] = $sEmailId;
        $_REQUEST['sName'] = $sName;
        $_REQUEST['sEmailSubject'] = $sEmailSubject;
        Mail::send('email_tamplates/'.$sEmailTamplt, $aEmailData, function($message) {
            $message->to($_REQUEST['sEmilId'], $_REQUEST['sName'])->subject($_REQUEST['sEmailSubject']);
            $message->from('studentlunch@i4dev.in','Student Lunch');
        });
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
                $this->SetCell($nCol, $lRow, '', $sheet, '', $sBgColr, '', True);
            }
        }
    }

    public function SetCell($sStartCol, $lRow, $sValue, $sheet, $sEndCol, $sBgColr, $sAling, $yBold, $vFormate = '', $yWrap = False, $nWidth = '', $nMrgCell = '', $nFntSize = '', $sFntClr = '')
    {
        $Col1 = $this->GetColName($sStartCol).$lRow;
        if(!empty($sEndCol)) {
            if($sStartCol != $sEndCol) {
                $Col2 = $this->GetColName($sEndCol).$lRow;
                $sheet->mergeCells($Col1.':'.$Col2);
            } else {
                if(!empty($nMrgCell) && $nMrgCell > 0)
                {
                    $Col2 = $this->GetColName($sStartCol).($lRow+$nMrgCell);
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
                $sheet->setWidth($this->GetColName($sStartCol), $nWidth);
            }
            if(!empty($nFntSize)) {
                $cell->setFontSize($nFntSize);
            }
            $cell->setFontColor($sFntClr);
        });
    }

    public function writeFile($message){   
        $CompDtl=$this->Company->CompDtl();  
        $message=date('H:i A').' ('.$CompDtl['sFrst_Name'].' '.$CompDtl['sLst_Name'].')'."\t\t".$message; 
        $date=date('Y-m-d');
        if (is_file('public/assets/logfiles/'.$date.'.txt')) {   
            $myfile = fopen('public/assets/logfiles/'.$date.'.txt', 'a'); 
            fwrite($myfile, $message."\n");
            fclose($myfile); 
        }else{ 
            $myfile = fopen('public/assets/logfiles/'.$date.'.txt', "w"); 
            fwrite($myfile, $message."\n");  
            fclose($myfile);
        }  
    }
}
