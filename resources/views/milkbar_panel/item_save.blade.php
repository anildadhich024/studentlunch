<?php 
use App\Model\Variant;
?>
@include('admin_panel.layouts.header')
    <style>
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        #ItemModel .days-div ul li input[type=checkbox] {
            display: block;
        }
        /* input[type=checkbox] {
            opacity: 0;
        }

        input[type=checkbox]:focus {
            opacity: 1;
        } */
        #ItemModel .days-div ul li input[type=checkbox]+label:before{
            content: none;
        } 
        .btnhover:focus {
            background-color: #154c83 !important;
            border: 1px solid #154c83 !important;
        } 
        .account-form{
            margin-bottom:20px;
        }
    </style>
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('milkbar_panel.layouts.side_panel')
            <main>
                <div class="page-breadcrumb">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="page-title">Manage Item</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section manage-page">
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details parent-list-table">
                            <form action="{{url('milkbar_panel/item/save')}}" method="post" class="general_form" id="general_form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="lItemIdNo" id="lItemIdNo" value="<?php if(isset($aItemDtl['lItem_IdNo'])){?>{{base64_encode($aItemDtl['lItem_IdNo'])}}<?php }else{?>{{ base64_encode(0) }}<?php } ?>">
                                    <div class="row account-form">
                                        <div class="col-lg-6">
                                            <label>Category Name</label>
                                            <select class="form-control" name="lCatgIdNo" required autofocus="on" tabindex="1">
                                                <option value="">== Select Category ==</option>
                                                @foreach($aCatgLst as $aRec)
                                                    <option <?php if(isset($aItemDtl['lCatg_IdNo'])){if($aItemDtl['lCatg_IdNo'] ==$aRec['lCatg_IdNo']) {?>selected<?php }} ?>{{ old('lCatgIdNo') == $aRec['lCatg_IdNo'] ? 'selected' : ''}} value="{{$aRec['lCatg_IdNo']}}">{{$aRec['sCatg_Name']}}</option>
                                                @endforeach
                                            </select>
                                            @error('lCatg_IdNo') <div class="invalid-feedback"><span>{{$errors->first('lCatg_IdNo')}}</span></div>@enderror
                                        </div> 
                                        <div class="col-lg-6">
                                            <label>Item Name</label>
                                            <input type="text" value="<?php if(isset($aItemDtl['sItem_Name'])){?> {{$aItemDtl['sItem_Name']}} <?php }else{ ?>{{old('sItemName')}} <?php } ?>" name="sItemName" class="form-control" onkeypress="return IsItemName(event, this.value, '50')" required tabindex="2">
                                            @error('sItemName') <div class="invalid-feedback"><span>{{$errors->first('sItemName')}}</span></div>@enderror
                                        </div> 
                                    </div>
                                    <div class="row account-form">
                                        <div class="col-lg-6">
                                            <label>Item Description</label>
                                            <textarea class="form-control" name="sItemDscrptn" onkeypress="return IsItemDes(event, this.value, '150')" required tabindex="3"><?php if(isset($aItemDtl['sItem_Dscrptn'])){?> {{$aItemDtl['sItem_Dscrptn']}} <?php } else{?>{{old('sItemDscrptn')}}<?php } ?></textarea>
                                            @error('sItemDscrptn') <div class="invalid-feedback"><span>{{$errors->first('sItemDscrptn')}}</span></div>@enderror
                                        </div>  
                                        <div class="col-lg-6">
                                            <label>Item Price</label>
                                            <input type="text" name="sItemPrc" value="<?php if(isset($aItemDtl['sItem_Prc'])){?> {{$aItemDtl['sItem_Prc']}} <?php } else{?>{{old('sItem_Prc')}}<?php } ?>" id="sItemPrc" class="form-control" onkeypress="return isNumberKey(event)" onchange="ItmPrc()" required tabindex="4">
                                            @error('sItem_Prc') <div class="invalid-feedback"><span>{{$errors->first('sItem_Prc')}}</span></div>@enderror
                                        </div>  
                                    </div>
                                    <div class="row account-form"> 
                                        <div class="col-lg-12"> 
                                            <label>Availability Day</label>  
                                        </div>  
                                            @php
                                            if(!empty($aItemDtl['aItem_Week']))
                                            {
                                                $aItemWeeks = explode(',',$aItemDtl['aItem_Week']);
                                            }
                                            @endphp                         
                                            @foreach(config('constant.WEEK') as $sWkName => $nWkDay)  
                                            <div class="col-lg-2" style="margin:10px 0px 10px 0px">
                                                <label style="width: 50px;" for="fruit{{$nWkDay}}">{{$sWkName}}</label>  
                                                <input type="checkbox" style="width: 50px;margin-top: -20px; margin-left: 90px;" class="form-control" name="aItemWeek[]" id="fruit{{$nWkDay}}" {{ isset($aItemWeeks) && in_array($nWkDay,$aItemWeeks) ? 'checked' : '' }} value="{{$nWkDay}}" {{ !isset($aItemDtl) ? 'checked' : ''}} tabindex="5">
                                            </div>
                                            @endforeach 
                                        @error('aItemWeek') <div class="invalid-feedback"><span>{{$errors->first('aItemWeek')}}</span></div>@enderror
                                        
                                    </div>
                                    <div class="row account-form"> 
                                        <div class="col-lg-12"> 
                                            <label><b>Variants</b></label>  
                                        </div>
                                            @php
                                                if(!empty($aItemDtl['sMenu_Variant']))
                                                {
                                                    $sMenuVariant=explode(',',$aItemDtl['sMenu_Variant']);
                                                }
                                            @endphp
                                            @foreach($aVargLst as $var)
                                               
                                                <div class="col-lg-12" style="margin:10px 0px 10px 0px">
                                                    <label style="width: 100px;" for="{{$var->IVariant_IdNo}}">{{strtoupper($var->sVariant_Name)}}</label>
                                                    <input {{isset($sMenuVariant) && in_array($var->IVariant_IdNo,$sMenuVariant) ? 'checked' : ''}} type="checkbox" style="width: 100px;margin-top: -20px; margin-left: 140px;" class="form-control" name="sMenuVar[]" id="{{$var->sVariant_Name}}" value="{{$var->IVariant_IdNo}}" tabindex="5">  
                                                </div>
                                                <?php 
                                                $items=array();
                                                $item= Variant::VariantItemLst($var->IVariant_IdNo);
                                                if(count($item)>0){
                                                    foreach($item as $i){  ?>
                                                        <div class="col-lg-4" style="margin:10px 0px 10px 0px">
                                                            <label style="width: 100px;" for="{{$i->IVar_Item_IdNo}}">{{$i->sItem_name}}</label>  
                                                         </div>
                                                <?php }} ?>  
                                                <div class="col-lg-12" style="margin:10px 0px 10px 0px;border-bottom:.5px solid #00000245;">
                                                </div>  
                                        @endforeach                                       
                                    </div>
                                    <div class="col-lg-12 services-btns">
                                        <ul class="m-auto text-center" id="buttons">
                                            <li>
                                                <div class="add-btn  mt-0"><button class="mt-0 btnhover" tabindex="6" type="button" onclick="history.back()" aria-label="Close">Cancel</button></div>
                                            </li>
                                            <li>
                                                <div class="add-btn  mt-0"><button title="Save & Exit" type="submit" id="save_exit" name="save_exit" class="mt-0 btnhover" tabindex="7">Save & Exit</button></div>
                                            </li>
                                            <?php if(isset($aItemDtl['lItem_IdNo'])){}else{?>
                                                <li>
                                                    <div class="add-btn  mt-0"><button title="Save & Continue" type="submit" id="save_continue" name="save_continue" class="mt-0 btnhover" tabindex="7">Save & Continue</button></div>
                                                </li> 
                                            <?php } ?>
                                        </ul>
                                    </div> 
                                </div> 
                            </form>
                        </div>
                    </div> 
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript"> 

</script>