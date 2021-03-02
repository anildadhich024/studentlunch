<?php 
use App\Model\School;
$add_sch=0;
if(Session::has('add_sch')){
    $add_sch=1;
}
?>
<style type="text/css">
.btn-warning{
    width: 50px; 
    padding: 10px;
    margin:auto; 
    margin-right: 0px;
} 
</style>
@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('admin_panel.layouts.side_panel')
            <form action="{{url('admin_panel/milk_bar/save')}}" method="post" id="general_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="lMilkIdNo" id="lMilkIdNo" value="{{ !empty($aMilkDtl) ? base64_encode(!empty($aMilkDtl) ? $aMilkDtl['lMilk_IdNo'] : '') : base64_encode(0)}}">
                <main>
                    <div class="page-breadcrumb">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="page-title">Manage Service Provider</h4>
                            </div>
                        </div>
                    </div>
                    @include('admin_panel.layouts.message')
                    <div class="container-fluid card-commission-section  parent-details-section">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <div>
                                    <h4>Business Information</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Business Name</label>
                                <input type="text" class="form-control @error('sBussName') is-invalid @enderror" name="sBussName" value="{{ old('sBussName', !empty($aMilkDtl) ? $aMilkDtl['sBuss_Name'] : '') }}" onkeypress="return IsAlpha(event, this.value, '50')" required />
                                @error('sBussName') <div class="invalid-feedback"><span>{{$errors->first('sBussName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Business Type</label>
                                <select class="form-control @error('nBussType') is-invalid @enderror" name="nBussType" required>
                                    <option value="">Business Type</option>
                                    @foreach(config('constant.BUSS_TYPE') as $sTypeName => $nBussType)
                                        <option {{ old('sBussName', !empty($aMilkDtl) ? $aMilkDtl['nBuss_Type'] : '') == $nBussType ? 'selected' : ''}} value="{{$nBussType}}">{{$sTypeName}}</option>
                                    @endforeach
                                </select>
                                @error('nBussType') <div class="invalid-feedback"><span>{{$errors->first('nBussType')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Business ABN</label>
                                <input type="text" class="abn form-control @error('sAbnNo') is-invalid @enderror" name="sAbnNo" value="{{ old('sAbnNo', !empty($aMilkDtl) ? $aMilkDtl['sAbn_No'] : '') }}" onkeypress="return IsNumber(event, this.value, '14')" required />
                                @error('sAbnNo') <div class="invalid-feedback"><span>{{$errors->first('sAbnNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Country</label>
                                <select class="form-control @error('lCntryIdNo') is-invalid @enderror" name="lCntryIdNo" id="lCntryIdNo" onchange="GetState(this.value)" required>
                                    <option value="">== Select Country ==</option>
                                    @foreach($aCntryLst as $aRec)
                                        <option {{ old('lCntryIdNo', !empty($aMilkDtl) ? $aMilkDtl['lCntry_IdNo'] : '') == $aRec['lCntry_IdNo'] ? 'selected' : ''}} value="{{$aRec['lCntry_IdNo']}}" data-code="{{$aRec['sCntry_Code']}}">{{$aRec['sCntry_Name']}}</option>
                                    @endforeach
                                </select>
                                @error('lCntryIdNo') <div class="invalid-feedback"><span>{{$errors->first('lCntryIdNo')}}</span></div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid card-commission-section parent-list-section parent-details-section">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <div>
                                    <h4>Address</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row  account-form">
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Street No.</label>
                                <input type="text" class="form-control @error('sStrtNo') is-invalid @enderror" name="sStrtNo" value="{{ old('sStrtNo', !empty($aMilkDtl) ? $aMilkDtl['sStrt_No'] : '') }}" onkeypress="return IsNumber(event, this.value, '4')" required />
                                @error('sStrtNo') <div class="invalid-feedback"><span>{{$errors->first('sStrtNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Street Name</label>
                                <input type="text" class="form-control @error('sStrtName') is-invalid @enderror" name="sStrtName" value="{{ old('sStrtName', !empty($aMilkDtl) ? $aMilkDtl['sStrt_Name'] : '') }}" onkeypress="return IsAlpha(event, this.value, '50')" required />
                                @error('sStrtName') <div class="invalid-feedback"><span>{{$errors->first('sStrtName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>  Suburb</label>
                                <input type="text" class="form-control @error('sSbrbName') is-invalid @enderror" name="sSbrbName" value="{{ old('sSbrbName', !empty($aMilkDtl) ? $aMilkDtl['sSbrb_Name'] : '') }}" onkeypress="return IsAlpha(event, this.value, '20')" required />
                                @error('sSbrbName') <div class="invalid-feedback"><span>{{$errors->first('sSbrbName')}}</span></div>@enderror
                            </div> 
                            <div class="col-6 col-sm-4 pb-3">
                                <label>State</label>
                                <input type="hidden" id="lStateIdNoHid" value="{{old('lStateIdNo', $aMilkDtl['lState_IdNo'])}}">
                                <select class="form-control @error('lStateIdNo') is-invalid @enderror" name="lStateIdNo" id="lStateIdNo" required>
                                    <option value="">== Select State ==</option>
                                </select>
                                @error('lStateIdNo') <div class="invalid-feedback"><span>{{$errors->first('lStateIdNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label> Post Code</label>
                                <input type="text" class="form-control @error('sPinCode') is-invalid @enderror" name="sPinCode" value="{{ old('sPinCode', !empty($aMilkDtl) ? $aMilkDtl['sPin_Code'] : '') }}" onkeypress="return IsNumber(event, this.value, '4')" required />
                                @error('sPinCode') <div class="invalid-feedback"><span>{{$errors->first('sPinCode')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid card-commission-section parent-list-section parent-details-section">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <div>
                                    <h4>Business Contact Details</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row  account-form">
                            <div class="col-6 col-sm-4 pb-3">
                                <label>First Name</label>
                                <input type="text" class="form-control @error('sFrstName') is-invalid @enderror" name="sFrstName" value="{{ old('sFrstName', !empty($aMilkDtl) ? $aMilkDtl['sFrst_Name'] : '') }}" onkeypress="return IsAlpha(event, this.value, '15')" required />
                                @error('sFrstName') <div class="invalid-feedback"><span>{{$errors->first('sFrstName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Surname</label>
                                <input type="text" class="form-control @error('sLstName') is-invalid @enderror" name="sLstName" value="{{ old('sLstName', !empty($aMilkDtl) ? $aMilkDtl['sLst_Name'] : '') }}" onkeypress="return IsAlpha(event, this.value, '15')" required />
                                @error('sLstName') <div class="invalid-feedback"><span>{{$errors->first('sLstName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <div class="col row">
                                    <label>Phone Number </label>
                                    <div class="row pr-0">
                                        <div class="col-lg-3 col-4 p-l-15">
                                            <input type="text" class="form-control cnoutry_code" name="sCntryCodePhone" value="{{ old('sCntryCodePhone', !empty($aMilkDtl) ? $aMilkDtl['sCntry_Code'] : '') }}" readonly />    
                                        </div>
                                        <div class="col-lg-3 col-8 p-0">
                                            <input type="text" class="form-control" name="sAreaCode" id="sAreaCode" value="{{ old('sAreaCode', !empty($aMilkDtl) ? $aMilkDtl['sArea_Code'] : '') }}" placeholder="Area Code"  onkeypress="return IsNumber(event, this.value, '1')" required readonly />    
                                        </div>
                                        <div class="col-lg-6  col-12  pl-0 rplmedia pr-0">
                                            <input type="text" class="form-control @error('sPhoneNo') is-invalid @enderror" name="sPhoneNo" id="sPhoneNo" value="{{ old('sPhoneNo', !empty($aMilkDtl) ? $aMilkDtl['sPhone_No'] : '') }}" onkeypress="return IsPhone(event, this.value, '9')" required />    
                                        </div>
                                    </div>
                                    @error('sPhoneNo') <div class="invalid-feedback"><span>{{$errors->first('sPhoneNo')}}</span></div>@enderror
                                </div>
                                
                            </div> 
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Mobile Number </label>
                                <div class="row pr-0">
                                    <div class="col-lg-3 col-4 p-l-15">
                                        <input type="text" class="form-control cnoutry_code" name="sCntryCode" value="{{ old('sCntryCode', !empty($aMilkDtl) ? $aMilkDtl['sCntry_Code'] : '') }}" readonly />    
                                    </div>
                                    <div class="col-lg-9 col-8 p-r-15">
                                        <input type="text" class="form-control @error('sMobileNo') is-invalid @enderror" name="sMobileNo" id="sMobileNo" value="{{ old('sMobileNo', !empty($aMilkDtl) ? $aMilkDtl['sMobile_No'] : '') }}" onkeypress="return IsMobile(event, this.value, '11')" required />    
                                    </div>
                                </div>
                                @error('sMobileNo') <div class="invalid-feedback"><span>{{$errors->first('sMobileNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3 mt40maxmedia575">
                                <label> Email</label>
                                <input type="email" class="form-control @error('sEmailId') is-invalid @enderror" name="sEmailId" value="{{ old('sEmailId', !empty($aMilkDtl) ? $aMilkDtl['sEmail_Id'] : '') }}" {{!empty($aMilkDtl) ? 'readonly' : '' }} />
                                @error('sEmailId') <div class="invalid-feedback"><span>{{$errors->first('sEmailId')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                            </div>
                        </div>
                    </div>
                    @if(empty($aMilkDtl))
                    <div class="container-fluid card-commission-section parent-list-section parent-details-section">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <div>
                                    <h4>Login Information</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row  account-form">
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Password</label>
                                <input type="text" class="form-control @error('sLgnPass') is-invalid @enderror" name="sLgnPass" id="sLgnPass" value="{{ old('sLgnPass') }}" onkeypress="return LenCheck(event, this.value, '16')" required />
                                <span class="show_password" onmousedown ="ShowPass('sLgnPass');"><i class="sLgnPass fa fa-eye-slash" aria-hidden="true"></i></span>
                                @error('sLgnPass') <div class="invalid-feedback"><span>{{$errors->first('sLgnPass')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Confirm Password</label>
                                <input type="text" class="form-control @error('sCnfrmPass') is-invalid @enderror" name="sCnfrmPass" id="sCnfrmPass" value="{{ old('sCnfrmPass') }}" onkeypress="return LenCheck(event, this.value, '16')" required />
                                <span class="show_password" onmousedown ="ShowPass('sCnfrmPass');"><i class="sCnfrmPass fa fa-eye-slash" aria-hidden="true"></i></span>
                                @error('sCnfrmPass') <div class="invalid-feedback"><span>{{$errors->first('sCnfrmPass')}}</span></div>@enderror
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="container-fluid card-commission-section parent-list-section parent-details-section">
                        <div class="row  mt-4">                           
                            <div class="col-sm-6">
                                <h3>School's You Service :</h3>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-warning add_school"><i class="fa fa-plus" onclick="CrtRow()"></i></button>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-sm-12 col-lg-12 school-service-table" id="GridView">
                                <table style="  width: 100%; " id="display-table" class=" tablescroll ">
                                <!-- tablescroll1204 -->
                                    <thead>
                                        <tr>
                                            <th class="nowordwrap"></th>
                                            <th class="nowordwrap">School Type</th>
                                            <th class="nowordwrap">School Name</th>
                                            <th class="nowordwrap">Distance (in KM)</th>
                                            <th class="nowordwrap">Suburb</th>
                                            <th class="nowordwrap">Post Code</th>
                                            <th class="nowordwrap">Order Cut-Off Time</th>
                                        </tr>
                                    </thead>
                                    <tbody id="schoolGrid">
                                        @if(!empty($errors->all()) || !empty($aAccSchl))
                                            @if(!empty($errors->all()))
                                                @php
                                                    $nkey = 1;
                                                @endphp
                                                @for($nkey=1; $nkey<=old('nTtlRec');$nkey++)
                                                    @if(!empty(old('nSchlType'.$nkey)) && !empty(old('lSchlIdNo'.$nkey)) && !empty(old('dDistKm'.$nkey)) && !empty(old('sSbrbName'.$nkey)) && !empty(old('sPinCode'.$nkey)) && !empty(old('sCutTm'.$nkey)))
                                                        @php
                                                            $School     = new App\Model\School;
                                                            $aSchlLst   = $School->RegSchlLst(old('nSchlType'.$nkey));
                                                        @endphp
                                                        <tr id="Row_{{$nkey}}">
                                                            <td><i class="fa fa-minus" onclick="DeleteRow('{{$nkey}}')"></i></td>
                                                            <td>
                                                                <input type="hidden" name="lMilkSchlIdNo{{$nkey}}" value="{{old('lMilkSchlIdNo'.$nkey)}}"/>
                                                                <select name="nSchlType{{$nkey}}" id="nSchlType" data-id="{{$nkey}}" class="@error('nSchlType{{$nkey}}') is-invalid @enderror form-control" onchange="GetSchlLst('{{$nkey}}', this.value)" required>
                                                                    <option value="">Select School Type</option>
                                                                    @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                                    <option {{ old('nSchlType'.$nkey) == $nType ? 'selected' : ''}} value="{{$nType}}">{{$sTypeName}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="lSchlIdNo{{$nkey}}" id="lSchlIdNo{{$nkey}}" class="@error('lSchlIdNo{{$nkey}}') is-invalid @enderror form-control" required onchange="ChngDtl('{{$nkey}}')" style="width: 300px;" required>
                                                                    <option value="">Select School Name</option>
                                                                    @if(!empty($aSchlLst))
                                                                        @foreach($aSchlLst as $sData)
                                                                        <option data-subrb="{{$sData->sSbrb_Name}}" data-pin="{{$sData->sPin_Code}}" {{ old('lSchlIdNo'.$nkey) == $sData->lSchl_IdNo ? 'selected' : ''}} value="{{$sData->lSchl_IdNo}}">{{$sData->sSchl_Name}}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td><input type="text" name="dDistKm{{$nkey}}" value="{{ old('dDistKm'.$nkey) }}" class="@error('dDistKm{{$nkey}}') is-invalid @enderror form-control" id="dDistKm{{$nkey}}" onkeypress="return isNumberKey(event)" onchange="fn_do('{{$nkey}}')" required /></td>
                                                            <td><input type="text" name="sSbrbName{{$nkey}}" id="sSbrbName{{$nkey}}" class="@error('sSbrbName{{$nkey}}') is-invalid @enderror form-control" value="{{ old('sSbrbName'.$nkey) }}" onkeypress="return IsAlpha(event, this.value, '15')" required style="width: 170px;" /></td>
                                                            <td><input type="text" name="sPinCode{{$nkey}}" id="sPinCode{{$nkey}}" class="@error('sPinCode{{$nkey}}') is-invalid @enderror form-control" value="{{ old('sPinCode'.$nkey) }}" onkeypress="return IsNumber(event, this.value, '4')" required /></td>
                                                            <td><input type="time" class="@error('sCutTm{{$nkey}}') is-invalid @enderror form-control" name="sCutTm{{$nkey}}" value="{{ old('sCutTm'.$nkey) }}" required /></td>
                                                        </tr>
                                                    @endif
                                                @endfor
                                            @else
                                                @if(count($aAccSchl) > 0)
                                                    @php
                                                    $i = 1;
                                                    @endphp
                                                    @foreach($aAccSchl as $aRes)
                                                        @php
                                                            $School = new App\Model\School;
                                                            $aSchlLst = $School->RegSchlLst($aRes['nSchl_Type']);
                                                        @endphp
                                                        <input type="hidden" name="lMilkSchlIdNo{{$i}}" value="{{$aRes['lMilk_Schl_IdNo']}}"/>
                                                        <tr id="Row_{{$i}}"> 
                                                            <td><i class="fa fa-minus" onclick="DeleteRow({{$i}})"></i></td> 
                                                            <td>
                                                                <select name="nSchlType{{$i}}" class="form-control" onchange="GetSchlLst('{{$i}}', this.value)" required>
                                                                    <option value="">School Type</option>
                                                                     @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                                        <option {{ $aRes['nSchl_Type'] == $nType ? 'selected' : ''}} value="{{$nType}}">{{$sTypeName}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="lSchlIdNo{{$i}}" id="lSchlIdNo{{$i}}" class="form-control" onchange="ChngDtl('{{$i}}')" style="width: 300px;" required>
                                                                    <option value="">Select School Name</option>
                                                                    @foreach($aSchlLst as $aRec)
                                                                        <option data-subrb="{{$aRec['sSbrb_Name']}}" data-pin="{{$aRec['sPin_Code']}}" {{ $aRes['lSchl_IdNo'] == $aRec['lSchl_IdNo'] ? 'selected' : ''}} value="{{$aRec['lSchl_IdNo']}}">{{$aRec['sSchl_Name']}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control" name="dDistKm{{$i}}" value="{{$aRes['dDist_Km']}}" id="dDistKm{{$i}}" onkeypress="return isNumberKey(event)" onchange="fn_do('{{$i}}')" required /></td>
                                                            <td><input type="text" class="form-control" name="sSbrbName{{$i}}" id="sSbrbName{{$i}}" value="{{$aRes['sSbrb_Name']}}" onkeypress="return IsAlpha(event, this.value, 15)" required  style="width: 170px;"/></td>
                                                            <td><input type="text" class="form-control" name="sPinCode{{$i}}" id="sPinCode{{$i}}" value="{{$aRes['sPin_Code']}}" onkeypress="return IsNumber(event, this.value, 4)" required /></td>
                                                            <td><input type="time" class="form-control text-right" name="sCutTm{{$i}}" value="{{$aRes['sCut_Tm']}}" required /></td>
                                                        </tr>
                                                        @php
                                                        $i++;
                                                        @endphp
                                                    @endforeach
                                                @else
                                                    
                                                @endif
                                            @endif
                                            <input type="hidden" name="nTtlRec" id="nTtlRec" value="{{ old('nTtlRec',count($aAccSchl)) }}">
                                        @else
                                            <tr id="Row_1"> 
                                                <td><i class="fa fa-minus" onclick="DeleteRow(1)"></i></td> 
                                                <td>
                                                    <select name="nSchlType1" class="form-control" onchange="GetSchlLst(1, this.value)" required>
                                                        <option value="">School Type</option>
                                                         @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                            <option value="{{$nType}}">{{$sTypeName}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="lSchlIdNo1" id="lSchlIdNo1" class="form-control" onchange="ChngDtl(1)" style="width: 300px;" required>
                                                        <option value="">Select School Name</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="dDistKm1" value="" id="dDistKm1" onkeypress="return isNumberKey(event)" onchange="fn_do(1)" required /></td>
                                                <td><input type="text" class="form-control" name="sSbrbName1" id="sSbrbName1" value="" onkeypress="return IsAlpha(event, this.value, 15)" required  style="width: 170px;"/></td>
                                                <td><input type="text" class="form-control" name="sPinCode1" id="sPinCode1" value="" onkeypress="return IsNumber(event, this.value, 4)" required /></td>
                                                <td><input type="time" class="form-control text-right" name="sCutTm1" value="" required /></td>
                                            </tr>
                                            <input type="hidden" name="nTtlRec" id="nTtlRec" value="1">
                                        @endif
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
                                        <div class="add-btn  mt-0 mtautomedia364"><button title="Save" type="submit" class="mt-0">Save</button></div>
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
<script type="text/javascript">
function CrtRow()
{
    var rowCount = $('#schoolGrid tr').length;
    if(rowCount == 3)
    {
        alert("Maximum 3 schools allowed...");
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
        content += '<td><select name="nSchlType'+next_no+'" class="form-control"><option value="">School Type</option>@foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)<option value="{{$nType}}">{{$sTypeName}}</option>  @endforeach</select></td>';
        content += '<td><select name="lSchlIdNo'+next_no+'" class="form-control" style="width: 345px;"><option value="">Child School Name</option>@foreach($aSchlLst as $aRec)<option value="{{$aRec['lSchl_IdNo']}}">{{$aRec['sSchl_Name']}}</option>@endforeach</select></td>';
        content += '<td><input type="text" class="form-control" name="dDistKm'+next_no+'" id="dDistKm'+next_no+'" onkeypress="return isNumberKey(event)" onchange="fn_do('+next_no+')" value="" onkeypress="return isNumberKey(event)" onchange="fn_do("dDistKm'+next_no+'")" required /></td>';
        content += '<td><input type="text" class="form-control" name="sSbrbName'+next_no+'" value="" onkeypress="return IsAlpha(event, this.value, 15)" required /></td>';
        content += '<td><input type="text" class="form-control" name="sPinCode'+next_no+'"  value="" onkeypress="return IsNumber(event, this.value, 4)" required /></td>';
        content += '<td><input type="time" class="form-control text-right" name="sCutTm'+next_no+'" value="" required /></td>';
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

$().ready(function () {
    $('.abn').bindABNControls();
});

$(document).ready(function() {
    var lCntryIdNo = $('#lCntryIdNo').val();
    var lStateIdNo = $('#lStateIdNoHid').val();
    if(lStateIdNo != '')
    {
        GetState(lCntryIdNo, lStateIdNo);
    }
});
</script>