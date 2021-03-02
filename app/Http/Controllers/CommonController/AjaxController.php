<?php

namespace App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Country;
use App\Model\State;
use App\Model\School;

class AjaxController extends Controller
{
	public function __construct()
	{
		$this->Country 			= new Country;
		$this->State 			= new State;
		$this->School 			= new School;
	}

	public function StateLst(Request $request)
	{
		$lCntryIdNo = base64_decode($request['lCntryIdNo']);
		$aStateLst	= $this->State->FrntLst($lCntryIdNo);
		return json_encode($aStateLst, JSON_PRETTY_PRINT);
	}

	public function SchlLst(Request $request)
	{
		$lSchlType = base64_decode($request['lSchlType']);
		$aSchlLst	= $this->School->RegSchlLst($lSchlType);
		return json_encode($aSchlLst, JSON_PRETTY_PRINT);
	}
}
?>