<?php
namespace App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SuperAdmin;
use Validator;
use App\Model\Setting;
use App\Model\Country;
use App\Model\State;
use Illuminate\Support\Facades\File;
use App\Model\Company; 

class SettingController extends Controller
{
	public function __construct()
	{
		$this->Company 	= new Company; 
		$this->Setting 	= new Setting;
		$this->middleware(SuperAdmin::class);
	}

	public function SaveCntrl(Request $request)
	{
		$rules = [
	        'dComPer' 	=> 'required',
	        'sPrntAmo' 	=> 'required',
	    ];

	    $this->validate($request, $rules, config('constant.VLDT_MSG'));
		try
		{
		    \DB::beginTransaction();
		    	$aHdArr 	= $this->HdArr($request);
				$nRow		= $this->Setting->UpDtRecrd($aHdArr); 
			\DB::commit();
		    return redirect()->back()->with('Success', 'Setting updated successfully...');
		}
		catch(\Exception $e)
		{
			\DB::rollback();
			return redirect()->back()->with('Failed', $e->getMessage().' on Line '.$e->getLine());
		}
	}

	public function HdArr($request)
	{
		$aConArr = array(
			'dCom_Per' 		=> $request['dComPer'],
			'sPrnt_Amo' 	=> $request['sPrntAmo'],
		);
		return $aConArr;
	}

	public function SettingCntrl(Request $request)
	{
		if(isset($request['submit'])){
			$rules = [
				'sTermCond'=>'required',
				'sSerProvTerms'=>'required',
				'sPrivacy'=>'required',
			];

			$this->validate($request, $rules, config('constant.VLDT_MSG'));
			try
			{
				\DB::beginTransaction();
					$aHdArr 	= $this->HdArrSet($request);
					$nRow		= $this->Setting->UpDtRecrd($aHdArr);
				\DB::commit();
				Controller::writeFile("Settings Updated");
				return redirect()->back()->with('Success', 'Setting updated successfully...');
			}
			catch(\Exception $e)
			{
				\DB::rollback();
				return redirect()->back()->with('Failed', $e->getMessage().' on Line '.$e->getLine());
			}
		}else{ 
			$StngDtl 	= $this->Setting->StngDtl();
			$sTitle 	= "Manage Settings";
			$aData 		= compact('sTitle','StngDtl','request');
			return view('admin_panel.setting',$aData);	
		}
	}

	public function HdArrSet($request)
	{
		$aConArr = array(
			'sTerm_Cond' 		=> $request['sTermCond'],
			'sSerProv_Terms' 		=> $request['sSerProvTerms'],
			'sPrivacy' 	=> $request['sPrivacy'],
		);
		return $aConArr; 
	}

	public function LogFilesCntrl(Request $request)
	{  
    	$path = public_path('/assets/logfiles/'); 
    	$files = File::files($path);  
		$sTitle 	= "Manage Log Files";
		$aData 		= compact('sTitle','request','files');
		return view('admin_panel.logFiles_list',$aData); 
	}

	public function LogFilesDlt(Request $request,$name)
	{   
		 $imgToDel = public_path("assets/logfiles/" . $name);
		if (file_exists($imgToDel)) {
			unlink($imgToDel);
		}
		return redirect()->back()->with('Success', 'File Deleted Successfully...');
	}
	
}
?>