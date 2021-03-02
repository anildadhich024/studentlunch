<?php

namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SuperAdmin;
use App\Model\Country;
use Excel;
use App\Model\Company; 

class CountryController extends Controller
{
	public function __construct()
	{
		$this->Company 	= new Company; 
		$this->Country 			= new Country;
		$this->middleware(SuperAdmin::class);
	}

	public function SaveCntrl(Request $request)
	{
		$lCntryIdNo 	= base64_decode($request['lCntryIdNo']);
		$rules = [
	        'sCntryName' 	=> 'required|unique:mst_cntry,sCntry_Name,'.$lCntryIdNo.',lCntry_IdNo|min:5|max:30|regex:/^[\pL\s]+$/u',
            'sCntryCode' 	=> 'required',
            'sCurrCode' 	=> 'required',
            'sCurrSymbl' 	=> 'required',
            'nTaxMtdh' 		=> 'required',
	    ];
	    $this->validate($request, $rules, config('constant.VLDT_MSG'));

	    try
		{
		    $aHdArr 	= $this->HdArr($request);
		    \DB::beginTransaction();
		    	if($lCntryIdNo == 0)
		    	{
					$this->InsrtArr($aHdArr);
					$lCntryIdNo	= $this->Country->InsrtRecrd($aHdArr);
					Controller::writeFile('Country Created');
					$sMessage	= "Country created successfully...";
		    	}
		    	else
		    	{
					$nRow		= $this->Country->UpDtRecrd($aHdArr, $lCntryIdNo);
					Controller::writeFile('Country Updated');
	    			$sMessage	= "Country update successfully...";
		    	}
			\DB::commit();
		    return redirect('admin_panel/country/list')->with('Success', $sMessage);
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect('admin_panel/country/list')->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function HdArr($request)
	{
		$aConArr = array(
			'sCntry_Name' 	=> $request['sCntryName'],
            'sCntry_Code' 	=> $request['sCntryCode'],
            'sCurr_Code' 	=> $request['sCurrCode'],
            'sCurr_Symbl' 	=> $request['sCurrSymbl'],
            'nTax_Mtdh' 	=> $request['nTaxMtdh'],
		);
		return $aConArr;
	}

	public function InsrtArr(&$aHdArr)
	{
		$aHdArr['nBlk_UnBlk']		= config('constant.STATUS.UNBLOCK');
		$aHdArr['nDel_Status']		= config('constant.DEL_STATUS.UNDELETED');
	}

	public function ListPage(Request $request)
	{
		$sCntryName = $request['sCntryName'];
		$aGetCurr	= file_get_contents('https://openexchangerates.org/api/currencies.json');
		$aGetCurr	= json_decode($aGetCurr);
		$oCntryLst 	= $this->Country->CntryLst($request['sCntryName']);
		$sTitle 	= "Manage Country List";
    	$aData 		= compact('sTitle','oCntryLst','aGetCurr','request');
        return view('admin_panel.country_list',$aData);	
	}
}
?>