@php
$aVrntIds   = explode(',',$aGetItm['sMenu_Variant']);
@endphp
@foreach($aVrntIds as $lVrntIdNo)
    @php
    $aVrntDtl   = \App\Model\Variant::Select('IVariant_IdNo','sVariant_Name')
                        ->Where('IVariant_IdNo', $lVrntIdNo)->first()->toArray();
    $aVrntOpt   = \App\Model\VariantItem::Select('IVar_Item_IdNo','sItem_name')
                        ->Where('IVariant_IdNo', $aVrntDtl['IVariant_IdNo'])->get();
    @endphp
    <span class="borderbottom1px"> </span>
    <div class="optionchoosediv">
        <div class="d-flex justify-content-between">
            <div class="title">
                Choose an {{$aVrntDtl['sVariant_Name']}}
            </div>
            <div class="about">
                Select only one
            </div>
        </div>
        <div class="radiooptions">
            @foreach($aVrntOpt as $aOpt)
            <div class="pt-3">
                <label>
                    <input name="aItmVrnt[{{$aVrntDtl['IVariant_IdNo']}}][]" type="radio" class="singleradiooption with-gap" value="{{$aOpt->IVar_Item_IdNo}}" />
                    <span>{{ucfirst($aOpt->sItem_name)}}</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
@endforeach