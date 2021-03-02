<?php
namespace App\Http\Controllers\MilkbarController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MilkBarAuth;
use App\Model\Category;
use Excel;

class CategoryController extends Controller
{
	public function __construct()
	{
		$this->Category 		= new Category;
		$this->middleware(MilkBarAuth::class);
	}

	public function ListPage(Request $request)
	{
		$lMilkIdNo 	= session('USER_ID');
		$sCatgName  = $request['sCatgName'];
		$oCatgLst	= $this->Category->CatgLst($lMilkIdNo, $sCatgName);
		$sTitle 	= "Manage Category";
    	$aData 		= compact('sTitle','oCatgLst','request');
        return view('milkbar_panel.category_list',$aData);	
	}

	public function SaveCntrl(Request $request)
	{
		$lCatgIdNo 	= base64_decode($request['lCatgIdNo']);
		$rules = [
	        'sCatgName' 	=> 'required|min:3|max:30|regex:/^[\pL\s]+$/u',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
		{
		    $aHdArr 	= $this->HdArr($request);
		    \DB::beginTransaction();
		    	if($lCatgIdNo == 0)
		    	{
		    		$this->InsrtArr($aHdArr);
		    		$lCatgIdNo	= $this->Category->InsrtRecrd($aHdArr);
		    		$sMessage	= "Category created successfully...";
		    	}
		    	else
		    	{
		    		$nRow		= $this->Category->UpDtRecrd($aHdArr, $lCatgIdNo);
		    		$sMessage	= "Category updated successfully...";
		    	}
			\DB::commit();
		    return redirect('milkbar_panel/category/list')->with('Success', $sMessage);
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect('milkbar_panel/category/list')->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function HdArr($request)
	{
		$aConArr = array(
			'sCatg_Name' 	=> html_entity_decode(ucfirst($request['sCatgName'])),
		);
		return $aConArr;
	}

	public function InsrtArr(&$aHdArr)
	{
		$aHdArr['lCatg_Unq_Id']	= rand(1001,9999);
		$aHdArr['lMilk_IdNo']	= session('USER_ID');
		$aHdArr['nBlk_UnBlk']	= config('constant.STATUS.UNBLOCK');
		$aHdArr['nDel_Status']	= config('constant.DEL_STATUS.UNDELETED');
	}

	public function ExprtRcrd(Request $request)
	{
		$lMilkIdNo 	= session('USER_ID');
		$sCatgName  = $request['sCatgName'];
		$oCatgLst	= $this->Category->ExlRcrd($lMilkIdNo, $sCatgName);
		if(count($oCatgLst) > 0)
		{
			$FileName = 'Category_'.date('Ymd').'_'.date('His');
	        Excel::create($FileName, function($excel) use ($oCatgLst) {
	            $excel->sheet('Sheet1', function($sheet)  use ($oCatgLst) {
	                $this->SetExlHeader($sheet, $lRaw);
	                $this->SetExlData($sheet, $lRaw, $oCatgLst);
	            });
	        })->download('xlsx');
	    }
	    else
	    {
        	return redirect()->back()->with('Success', 'Record not found...');
	    }

	}

	public function SetExlHeader($sheet, &$lRaw)
	{
		$lRaw = 1;
		Controller::SetCell(config('excel.XL_CATG.SR_NO'), $lRaw, 'Sr. No', $sheet, '', '#F2DDDC', 'right', True, '', False, 8, '', 10);
		Controller::SetCell(config('excel.XL_CATG.CATG_ID'), $lRaw, 'Unique Id', $sheet, '', '#F2DDDC', 'right', True, '', False, 10, '', 10);
		Controller::SetCell(config('excel.XL_CATG.CATG_NAME'), $lRaw, 'Category Name', $sheet, '', '#F2DDDC', 'left', True, '', False, 20, '', 10);
		Controller::SetCell(config('excel.XL_CATG.CATG_STATUS'), $lRaw, 'Status', $sheet, '', '#F2DDDC', 'left', True, '', False, 10, '', 10);
	}

	public function SetExlData($sheet, $lRaw, $oCatgLst)
	{
		$i = 0;
		while(isset($oCatgLst) && count($oCatgLst) > 0 && $i<count($oCatgLst))
		{
			$lRaw = $lRaw + 1;
			Controller::SetCell(config('excel.XL_CATG.SR_NO'), $lRaw, $i+1, $sheet, '', '', 'right', False, '', False, 8, '', 10);
			Controller::SetCell(config('excel.XL_CATG.CATG_ID'), $lRaw, $oCatgLst[$i]['lCatg_Unq_Id'], $sheet, '', '', 'right', False, '', False, 10, '', 10);
			Controller::SetCell(config('excel.XL_CATG.CATG_NAME'), $lRaw, $oCatgLst[$i]['sCatg_Name'], $sheet, '', '', 'left', False, '', False, 20, '', 10);
			Controller::SetCell(config('excel.XL_CATG.CATG_STATUS'), $lRaw, $oCatgLst[$i]['nBlk_UnBlk'] == config('constant.STATUS.BLOCK') ? 'Block' : 'Unblock', $sheet, '', '', 'left', False, '', False, 10, '', 10);

			$i++;
		}
	}
}
?>