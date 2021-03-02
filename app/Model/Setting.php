<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Setting extends Model
{
    public $timestamps  = false;
    protected $table    = 'mst_sttng';

    public function UpDtRecrd($aHdArr)
    {
     	try
    	{
	        $nRow	= Setting::Where('lSttng_IdNo',1)->update($aHdArr);
	        return $nRow;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }

    public function StngDtl()
    {
    	try
    	{
	        $aStngDtl	= Setting::first();
	        return $aStngDtl;
	    }
	    catch(\Expection $e)
	    {
	    	return $e->getMessage();
	    }
    }
}
