<?php

namespace App\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class VariantItem extends Model
{
    public $timestamps  = false;
    protected $table    = 'variant_item';
}