<?php 
use App\Model\School;
?>
<style type="text/css">
.btn-warning{
    width: 50px; 
    padding: 10px;
    margin:auto; 
    margin-right: 0px;
} 
tbody td .SchlName{
    width: 310px !important;
}
.btn-primary{
    padding: 6px 10px 6px 10px;
    margin:auto;
    background-color: #003366;
    border: 1px #003366 solid;
}
</style>
@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('parent_panel.layouts.side_panel')
            <form action="{{url('parent_panel/manage_account/save')}}" method="post" id="general_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="lPrntIdNo" id="lPrntIdNo" value="{{base64_encode($aPrntsDtl['lPrnt_IdNo'])}}">
                <main>
                    <div class="page-breadcrumb">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="page-title">Manage Account</h4>
                            </div>
                        </div>
                    </div>
                    @include('admin_panel.layouts.message')
                    <div class="container-fluid card-commission-section  parent-details-section">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <div>
                                    <h4>Basic Information</h4>
                                </div>
                            </div>
                        </div>
                        <form>
                            <div class="row  account-form">
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Account ID</label>
                                    <input type="text" class="form-control" value="{{$aPrntsDtl['sAcc_Id']}}" readonly>
                                </div>
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Relationship with Student</label>
                                    <select class="form-control @error('lRltnIdNo') is-invalid @enderror" name="lRltnIdNo" required>
                                        <option value="">==Select Relationship==</option>
                                        @foreach(config('constant.RLTN_IDNO') as $sRelName => $lRelIdNo)
                                            <option {{ old('lRltnIdNo', $aPrntsDtl['lRltn_IdNo']) == $lRelIdNo ? 'selected' : ''}} value="{{$lRelIdNo}}">{{$sRelName}}</option>
                                        @endforeach
                                    </select>
                                    @error('lRltnIdNo') <div class="invalid-feedback"><span>{{$errors->first('lRltnIdNo')}}</span></div>@enderror
                                </div> 
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Country</label>
                                    <select class="form-control @error('lCntryIdNo') is-invalid @enderror" name="lCntryIdNo" id="lCntryIdNo" onchange="GetState(this.value)" required>
                                        <option value="">== Select Country ==</option>
                                        @foreach($aCntryLst as $aRec)
                                            <option {{ old('lCntryIdNo', $aPrntsDtl['lCntry_IdNo']) == $aRec['lCntry_IdNo'] ? 'selected' : ''}} value="{{$aRec['lCntry_IdNo']}}" data-code="{{$aRec['sCntry_Code']}}">{{$aRec['sCntry_Name']}}</option>
                                        @endforeach
                                    </select>
                                    @error('lCntryIdNo') <div class="invalid-feedback"><span>{{$errors->first('lCntryIdNo')}}</span></div>@enderror
                                </div>
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>First Name</label>
                                    <input type="text" class="form-control @error('sFrstName') is-invalid @enderror" name="sFrstName" value="{{ old('sFrstName', $aPrntsDtl['sFrst_Name']) }}" onkeypress="return IsAlpha(event, this.value, '15')" required onblur="ChkName()" />
                                    @error('sFrstName') <div class="invalid-feedback"><span>{{$errors->first('sFrstName')}}</span></div>@enderror
                                </div>
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Surname </label>
                                    <input type="text" class="form-control @error('sLstName') is-invalid @enderror" name="sLstName" value="{{ old('sLstName', $aPrntsDtl['sLst_Name']) }}" onkeypress="return IsAlpha(event, this.value, '15')" required onblur="ChkName()" />
                                    @error('sLstName') <div class="invalid-feedback"><span>{{$errors->first('sLstName')}}</span></div>@enderror
                                    <div class="invalid-feedback" style="display: block;"><span id="ErrName"></span></div>
                                </div> 
                                <div class="col-6 col-sm-4 pb-3"></div>
                                <div class="col-6 col-sm-4 pb-3">
                                    <input type="hidden" id="lStateIdNoHid" value="{{old('lStateIdNo')}}">
                                    <label>State</label>
                                    <select class="form-control @error('lStateIdNo') is-invalid @enderror" name="lStateIdNo" id="lStateIdNo" required>
                                        <option value="">== Select State ==</option>
                                        @foreach($aStateLst as $aRec)
                                            <option {{ old('lStateIdNo', $aPrntsDtl['lState_IdNo']) == $aRec['lState_IdNo'] ? 'selected' : ''}} value="{{$aRec['lState_IdNo']}}">{{$aRec['sState_Name']}}</option>
                                        @endforeach
                                    </select>
                                    @error('lStateIdNo') <div class="invalid-feedback"><span>{{$errors->first('lStateIdNo')}}</span></div>@enderror
                                </div> 
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Post Code</label>
                                    <input type="text" class="form-control @error('sPinCode') is-invalid @enderror" name="sPinCode" value="{{ old('sPinCode', $aPrntsDtl['sPin_Code']) }}" onkeypress="return IsNumber(event, this.value, '4')" required />
                                    @error('sPinCode') <div class="invalid-feedback"><span>{{$errors->first('sPinCode')}}</span></div>@enderror
                                </div>
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Suburb</label>
                                    <input type="text" class="form-control @error('sSbrbName') is-invalid @enderror" name="sSbrbName" value="{{ old('sSbrbName', $aPrntsDtl['sSbrb_Name']) }}" onkeypress="return IsAlpha(event, this.value, '20')" required />
                                    @error('sSbrbName') <div class="invalid-feedback"><span>{{$errors->first('sSbrbName')}}</span></div>@enderror
                                </div>
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Mobile Number </label>
                                    <div class="row pr-0">
                                        <div class="col-lg-3 col-4 p-l-15">
                                            <input type="text" class="form-control cnoutry_code" name="sCntryCode" value="{{ old('sFrstName', $aPrntsDtl['sCntry_Code']) }}" readonly />    
                                        </div>
                                        <div class="col-lg-9 col-8 p-r-15">
                                            <input type="text" class="form-control @error('sMobileNo') is-invalid @enderror" name="sMobileNo" id="sMobileNo" value="{{ old('sMobileNo', $aPrntsDtl['sMobile_No']) }}" onkeypress="return IsMobile(event, this.value, '11')" required />    
                                        </div>
                                    </div>
                                    @error('sFrstName') <div class="invalid-feedback"><span>{{$errors->first('sFrstName')}}</span></div>@enderror
                                </div> 
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Email </label>
                                    <input type="text" class="form-control" value="{{ $aPrntsDtl['sEmail_Id'] }}" readonly />
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="container-fluid card-commission-section  parent-details-section">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h3>Child Details :</h3>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-warning" onclick="CrtRow()" style="width: 105px;"><i class="fa fa-plus"></i> Add Row</button>
                                    </div>
                                    <div class="col-sm-3 pull-right">
                                        <a class="btn btn-primary TchrSchool" href="#" onClick="ParentSchool('{{base64_encode($aPrntsDtl['lPrnt_IdNo'])}}')" data-toggle="modal" data-target="#ParentSchool">Request New School</a>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-sm-12 col-lg-12 school-service-table pt-3" id="GridView">
                                <table style="  width: 100%; " id="display-table" class=" tablescroll ">
                                    <thead>
                                        <tr>
                                            <th class="nowordwrap"></th>
                                            <th class="nowordwrap">School Type</th>
                                            <th class="nowordwrap" style="width: 315px !important;">School Name</th>
                                            <th class="nowordwrap">Child First Name</th>
                                            <th class="nowordwrap">Child Surname</th>
                                            <th class="nowordwrap">Class</th>
                                        </tr>
                                    </thead>
                                    <tbody id="schoolGrid">
                                        @if(!empty($errors->all()))
                                            @php
                                                $nkey = 1;
                                            @endphp
                                            @for($nkey=1; $nkey<=old('nTtlRec');$nkey++)
                                                @if(!empty(old('nSchlType'.$nkey)) && !empty(old('lSchlIdNo'.$nkey)) && !empty(old('sFrstName'.$nkey)) && !empty(old('sLstName'.$nkey)) && !empty(old('sClsName'.$nkey)))
                                                    @php
                                                       $aSchlLst = \App\Model\School::PreLoadSchl(old('nSchlType'.$nkey));
                                                    @endphp
                                                    <tr id="Row_{{$nkey}}">
                                                        <input type="hidden" name="lChldIdNo{{$nkey}}" value="{{old('lChldIdNo'.$nkey)}}"/>
                                                        <td><i class="fa fa-minus" onclick="DeleteRow('{{$nkey}}')"></i></td>
                                                        <td>
                                                            <select name="nSchlType{{$nkey}}" id="nSchlType" data-id="{{$nkey}}" class="@error('nSchlType{{$nkey}}') is-invalid @enderror form-control" onchange="GetSchlLst('{{$nkey}}', this.value)" style="width: 200px;">
                                                                <option value="">Select School Type</option>
                                                                @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                                <option {{ old('nSchlType'.$nkey) == $nType ? 'selected' : ''}} value="{{$nType}}">{{$sTypeName}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="lSchlIdNo{{$nkey}}" id="lSchlIdNo{{$nkey}}" class="@error('lSchlIdNo{{$nkey}}') is-invalid @enderror form-control" required style="width: 350px;">
                                                                <option value="">Select School Name</option>
                                                                @if(!empty($aSchlLst))
                                                                    @foreach($aSchlLst as $sData)
                                                                    <option {{ old('lSchlIdNo'.$nkey) == $sData->lSchl_IdNo ? 'selected' : ''}} value="{{$sData->lSchl_IdNo}}">{{$sData->sSchl_Name}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="@error('sFrstName{{$nkey}}') is-invalid @enderror form-control" name="sFrstName{{$nkey}}" value="{{ old('sFrstName'.$nkey) }}" onkeypress="return IsAlpha(event, this.value, '15')" required /></td>
                                                        <td><input type="text" class="@error('sLstName{{$nkey}}') is-invalid @enderror form-control" name="sLstName{{$nkey}}"  value="{{ old('sLstName'.$nkey) }}" onkeypress="return IsAlpha(event, this.value, '15')" required /></td>
                                                        <td><input type="text" class="@error('sClsName{{$nkey}}') is-invalid @enderror form-control" name="sClsName{{$nkey}}" value="{{ old('sClsName'.$nkey) }}" onkeypress="return IsAlphaNum(event, this.value, '4')" required /></td>
                                                    </tr>
                                                @endif
                                            @endfor
                                        @else
                                            @php
                                            $i = 1;
                                            @endphp
                                            @foreach($aChldLst as $aRes)
                                                @php
                                                    $School = new App\Model\School;
                                                    $aSchlLst = $School->RegSchlLst($aRes['nSchl_Type']);
                                                @endphp
                                                <input type="hidden" name="lChldIdNo{{$i}}" value="{{$aRes['lChld_IdNo']}}"/>
                                                <tr id="Row_{{$i}}">
                                                    <td><i class="fa fa-minus" onclick="DeleteRow('{{$i}}')"></i></td>
                                                    <td>
                                                        <select name="nSchlType{{$i}}" class="form-control" onchange="GetSchlLst('{{$i}}', this.value)" style="width: 200px;">
                                                            <option value="">School Type</option>
                                                            @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                                <option {{ $aRes['nSchl_Type'] == $nType ? 'selected' : ''}} value="{{$nType}}">{{$sTypeName}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="lSchlIdNo{{$i}}" id="lSchlIdNo{{$i}}" class="form-control SchlName" style="width: 350px;">
                                                            <option value="">Child School Name</option>
                                                            @foreach($aSchlLst as $aRec)
                                                                <option {{ $aRes['lSchl_IdNo'] == $aRec['lSchl_IdNo'] ? 'selected' : ''}} value="{{$aRec['lSchl_IdNo']}}">{{$aRec['sSchl_Name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="sFrstName{{$i}}" value="{{$aRes['sFrst_Name']}}" onkeypress="return IsAlpha(event, this.value, 15)" required /></td>
                                                    <td><input type="text" class="form-control" name="sLstName{{$i}}" value="{{$aRes['sLst_Name']}}" onkeypress="return IsAlpha(event, this.value, 15)" required /></td>
                                                    <td><input type="text" class="form-control" name="sClsName{{$i}}"  value="{{$aRes['sCls_Name']}}" onkeypress="return IsAlphaNum(event, this.value, 4)" required /></td>
                                                </tr>
                                                @php
                                                $i++;
                                                @endphp
                                            @endforeach
                                        @endif
                                        <input type="hidden" name="nTtlRec" id="nTtlRec" value="{{old('nTtlRec',count($aChldLst))}}">
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-lg-12 services-btns">
                                <ul class="m-auto text-center pt-4 pb-4">
                                    <li>
                                        <div class="add-btn  mt-0"><button type="button" title="Back" class="mt-0" onclick="history.back()">Back</button></div>
                                    </li>
                                    <li>
                                        <div class="add-btn  mt-0 mtautomedia364">
                                            <button id="EdtBtn" title="Edit" class="mt-0" type="button">Edit</button>
                                            <button id="SubmitBtn" title="Save" class="mt-0 d-none" type="submit">Update</button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </main>
            </form>
        </div>
    </div>
@include('admin_panel.layouts.footer')
@include('admin_panel.layouts.school_request') 

<script type="text/javascript">
function ParentSchool(id){ 
    $("#ParentSchool #lPrntIdNo").val(id);
}

function CrtRow()
{
    var rowCount = $('#schoolGrid tr').length;
    if(rowCount == 5)
    {
        alert("Maximum 5 childs allowed...");
    }
    else
    {
        total = $("#nTtlRec").val();
        next_no = parseInt(total)+1;
        newdiv = document.createElement('tr');
        divid = "Row_"+next_no;
        newdiv.setAttribute('id', divid);
        content = '';
        content += '<tr id="Row_'+next_no+'">';
        content += '<td><i class="fa fa-minus" onclick="DeleteRow('+next_no+')"></i></td>';
        content += '<td><select name="nSchlType'+next_no+'" class="form-control" onchange="GetSchlLst('+next_no+', this.value)" style="width: 200px;"><option value="">School Type</option>@foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)<option value="{{$nType}}">{{$sTypeName}}</option>  @endforeach</select></td>';
        content += '<td><select name="lSchlIdNo'+next_no+'" id="lSchlIdNo'+next_no+'" class="form-control SchlName" style="width: 350px;"><option value="">Select School Name</option></td>';
        content += '<td><input type="text" class="form-control" name="sFrstName'+next_no+'" value="" onkeypress="return IsAlpha(event, this.value, 15)" required /></td>';
        content += '<td><input type="text" class="form-control" name="sLstName'+next_no+'" value="" onkeypress="return IsAlpha(event, this.value, 15)" required /></td>';
        content += '<td><input type="text" class="form-control" name="sClsName'+next_no+'"  value="" onkeypress="return IsAlphaNum(event, this.value, 4)" required /></td>';
        content += '</tr>';
        newdiv.innerHTML = content;
        $("#nTtlRec").val(next_no);
        $("#schoolGrid").last().append(newdiv);
    }
}

function DeleteRow(nRow)
{ 
    var rowCount = $('#schoolGrid tr').length; 
    if(confirm("Are you sure to delete this row") == true) {
        var row = $('#Row_'+nRow);
        row.remove();
        if(rowCount == 1)
        {
            CrtRow();
        }
    }
}

$(document).ready(function() {
    var lCntryIdNo = $('#lCntryIdNo').val();
    var lStateIdNo = $('#lStateIdNoHid').val();
    if(lStateIdNo != '')
    {
        GetState(lCntryIdNo, lStateIdNo);
    }
});

$(document).ready(function(){
    $('input').attr("readonly", true);
    $('select').attr("disabled", true);
    $('.btn-warning').addClass('d-none');
    $('.fa-plus').addClass('d-none');
    $('.fa-minus').addClass('d-none');
    $('.TchrSchool').addClass('d-none'); 
});
</script>