<?php 
use App\Model\School;
// $SchlReqst=Session::get('request_school');
// print_r($SchlReqst);exit;
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
            @include('teacher_panel.layouts.side_panel')
            <form action="{{url('teacher_panel/manage_account/save')}}" method="post" id="general_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="lTchrIdNo" id="lTchrIdNo" value="{{base64_encode($aTchrDtl['lTchr_IdNo'])}}">
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
                            <div class="row  account-form">
                                <div class="col-4 col-sm-4 pb-3">
                                    <label>Account ID</label>
                                    <input type="text" class="form-control" value="{{$aTchrDtl['sAcc_Id']}}" readonly>
                                </div>
                                <div class="col-4 col-sm-4 pb-3">
                                    <label>First Name</label>
                                    <input type="text" class="form-control @error('sFrstName') is-invalid @enderror" name="sFrstName" value="{{ old('sFrstName', $aTchrDtl['sFrst_Name']) }}" onkeypress="return IsAlpha(event, this.value, '15')" required onblur="ChkName()" />
                                    @error('sFrstName') <div class="invalid-feedback"><span>{{$errors->first('sFrstName')}}</span></div>@enderror
                                </div>
                                <div class="col-4 col-sm-4 pb-3">
                                    <label>Surname </label>
                                    <input type="text" class="form-control @error('sLstName') is-invalid @enderror" name="sLstName" value="{{ old('sLstName', $aTchrDtl['sLst_Name']) }}" onkeypress="return IsAlpha(event, this.value, '15')" required onblur="ChkName()" />
                                    @error('sLstName') <div class="invalid-feedback"><span>{{$errors->first('sLstName')}}</span></div>@enderror
                                    <div class="invalid-feedback" style="display: block;"><span id="ErrName"></span></div>
                                </div>
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Country</label>
                                    <select class="form-control @error('lCntryIdNo') is-invalid @enderror" name="lCntryIdNo" id="lCntryIdNo" onchange="GetState(this.value)" required>
                                        <option value="">== Select Country ==</option>
                                        @foreach($aCntryLst as $aRec)
                                            <option {{ old('lCntryIdNo', $aTchrDtl['lCntry_IdNo']) == $aRec['lCntry_IdNo'] ? 'selected' : ''}} value="{{$aRec['lCntry_IdNo']}}" data-code="{{$aRec['sCntry_Code']}}">{{$aRec['sCntry_Name']}}</option>
                                        @endforeach
                                    </select>
                                    @error('lCntryIdNo') <div class="invalid-feedback"><span>{{$errors->first('lCntryIdNo')}}</span></div>@enderror
                                </div> 
                                <div class="col-6 col-sm-4 pb-3">
                                    <input type="hidden" id="lStateIdNoHid" value="{{old('lStateIdNo')}}">
                                    <label>State</label>
                                    <select class="form-control @error('lStateIdNo') is-invalid @enderror" name="lStateIdNo" id="lStateIdNo" required>
                                        <option value="">== Select State ==</option>
                                        @foreach($aStateLst as $aRec)
                                            <option {{ old('lStateIdNo', $aTchrDtl['lState_IdNo']) == $aRec['lState_IdNo'] ? 'selected' : ''}} value="{{$aRec['lState_IdNo']}}">{{$aRec['sState_Name']}}</option>
                                        @endforeach
                                    </select>
                                    @error('lStateIdNo') <div class="invalid-feedback"><span>{{$errors->first('lStateIdNo')}}</span></div>@enderror
                                </div> 
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Post Code</label>
                                    <input type="text" class="form-control @error('sPinCode') is-invalid @enderror" name="sPinCode" value="{{ old('sPinCode', $aTchrDtl['sPin_Code']) }}" onkeypress="return IsNumber(event, this.value, '4')" required />
                                    @error('sPinCode') <div class="invalid-feedback"><span>{{$errors->first('sPinCode')}}</span></div>@enderror
                                </div>
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Suburb</label>
                                    <input type="text" class="form-control @error('sSbrbName') is-invalid @enderror" name="sSbrbName" value="{{ old('sSbrbName', $aTchrDtl['sSbrb_Name']) }}" onkeypress="return IsAlpha(event, this.value, '20')" required />
                                    @error('sSbrbName') <div class="invalid-feedback"><span>{{$errors->first('sSbrbName')}}</span></div>@enderror
                                </div>
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Mobile Number </label>
                                    <div class="row pr-0">
                                        <div class="col-lg-3 col-4 p-l-15">
                                            <input type="text" class="form-control cnoutry_code" name="sCntryCode" value="{{ old('sFrstName', $aTchrDtl['sCntry_Code']) }}" readonly />    
                                        </div>
                                        <div class="col-lg-9 col-8 p-r-15">
                                            <input type="text" class="form-control @error('sMobileNo') is-invalid @enderror" name="sMobileNo" id="sMobileNo" value="{{ old('sMobileNo', $aTchrDtl['sMobile_No']) }}" onkeypress="return IsMobile(event, this.value, '11')" required />    
                                        </div>
                                    </div>
                                    @error('sFrstName') <div class="invalid-feedback"><span>{{$errors->first('sFrstName')}}</span></div>@enderror
                                </div> 
                                <div class="col-6 col-sm-4 pb-3">
                                    <label>Email </label>
                                    <input type="text" class="form-control" value="{{ $aTchrDtl['sEmail_Id'] }}" readonly />
                                </div>
                            </div> 
                    </div>
                    <div class="container-fluid card-commission-section  parent-details-section">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <h3>School Details :</h3>
                                    </div> 
                                    <div class="col-sm-3">
                                        <a class="btn btn-primary TchrSchool" href="#" data-toggle="modal" onClick="tchrSch('{{base64_encode($aTchrDtl['lTchr_IdNo'])}}')" data-target="#TchrSchool"> Request New School</a>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-sm-12 col-lg-12 school-service-table pt-3" id="GridView">
                                <table style="  width: 100%; " id="display-table" class=" tablescroll ">
                                    <thead>
                                        <tr>
                                            <th class="nowordwrap">School Type</th>
                                            <th class="nowordwrap" style="width: 315px !important;">School Name</th>
                                            <th class="nowordwrap" style="width: 120px !important;">Suburb</th>
                                            <th class="nowordwrap" style="width: 120px !important;">Post Code</th>
                                            <th class="nowordwrap" style="width: 250px !important;">Role</th>
                                        </tr>
                                    </thead>
                                    <tbody id="schoolGrid">
                                        @if(!empty($errors->all()))
                                            @php
                                               $aSchlLst = \App\Model\School::PreLoadSchl(old('nSchlType1'));
                                            @endphp
                                            <tr id="Row_1">
                                                <input type="hidden" name="lTchrSchlIdNo1" value="{{old('lTchrSchlIdNo1')}}"/>
                                                <td>
                                                    <select name="nSchlType1" id="nSchlType" data-id="1" class="@error('nSchlType1') is-invalid @enderror form-control" onchange="GetSchlLst('1', this.value)" style="width: 200px;">
                                                        <option value="">Select School Type</option>
                                                        @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                        <option {{ old('nSchlType1') == $nType ? 'selected' : ''}} value="{{$nType}}">{{$sTypeName}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="lSchlIdNo1" id="lSchlIdNo1" class="@error('lSchlIdNo1') is-invalid @enderror form-control" required style="width: 350px;">
                                                        <option value="">Select School Name</option>
                                                        @if(!empty($aSchlLst))
                                                            @foreach($aSchlLst as $sData)
                                                            <option {{ old('lSchlIdNo1') == $sData->lSchl_IdNo ? 'selected' : ''}} value="{{$sData->lSchl_IdNo}}">{{$sData->sSchl_Name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </td>
                                                <td><input type="text" class="@error('sSbrbName1') is-invalid @enderror form-control" name="sSbrbName1" value="{{ old('sSbrbName1') }}" onkeypress="return IsAlpha(event, this.value, 20)" required /></td>
                                                <td><input type="text" class="@error('sPinCode1') is-invalid @enderror form-control" name="sPinCode1" value="{{ old('sPinCode1') }}" onkeypress="return IsNumber(event, this.value, '4')" required /></td>
                                                <td>
                                                    <select name="nRoleType1" class="@error('nRoleType1') is-invalid @enderror" required style="width: 250px !important;">
                                                        <option value="">Select Role</option>
                                                        @foreach(config('constant.SCHL_ROLE') as $sTypeName => $nType)
                                                        <option  {{ old('nRoleType1')  == $nType ? 'selected' : ''}} value="{{$nType}}">{{$sTypeName}}</option>
                                                        @endforeach
                                                    </select> 
                                                </td>
                                            </tr>
                                        @else
                                            @php
                                                if(isset($aAssSchl))
                                                {
                                                    $School = new App\Model\School;
                                                    $aSchlLst = $School->RegSchlLst($aAssSchl->nSchl_Type);
                                                }
                                            @endphp
                                            <input type="hidden" name="lTchrSchlIdNo1" value="{{isset($aAssSchl) ? $aAssSchl->lTchr_Schl_IdNo : ''}}"/>
                                            <tr id="Row_1">
                                                <td>
                                                    <select name="nSchlType1" class="form-control" onchange="GetSchlLst(1, this.value)" style="width: 200px;">
                                                        <option value="">School Type</option>
                                                        @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                            <option {{ isset($aAssSchl) ? $aAssSchl->nSchl_Type : '' == $nType ? 'selected' : ''}} value="{{$nType}}">{{$sTypeName}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="lSchlIdNo1" id="lSchlIdNo1" class="form-control SchlName" style="width: 350px;" onchange="ChngDtl('1')">
                                                        <option value="">Select School Name</option>
                                                        @foreach($aSchlLst as $aRec)
                                                            <option  data-subrb="{{$aRec->sSbrb_Name}}" data-pin="{{$aRec->sPin_Code}}" {{ isset($aAssSchl) ? $aAssSchl->lSchl_IdNo : '' == $aRec['lSchl_IdNo'] ? 'selected' : ''}} value="{{$aRec['lSchl_IdNo']}}">{{$aRec['sSchl_Name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="sSbrbName1" id="sSbrbName1" value="{{ isset($aAssSchl) ? $aAssSchl->sSbrb_Name : ''}}" onkeypress="return IsAlpha(event, this.value, 20)" required readonly /></td>
                                                <td><input type="text" class="form-control" name="sPinCode1" id="sPinCode1" value="{{  isset($aAssSchl) ? $aAssSchl->sPin_Code : '' }}" onkeypress="return IsNumber(event, this.value, '4')" required readonly /></td>
                                                <td>
                                                    <select name="nRoleType1" required style="width: 250px !important;">
                                                        <option value="">Select Role</option>
                                                        @foreach(config('constant.SCHL_ROLE') as $sTypeName => $nType)
                                                        <option  {{ isset($aAssSchl) ? $aAssSchl->nRole_Type : '' == $nType ? 'selected' : ''}} value="{{$nType}}">{{$sTypeName}}</option>
                                                        @endforeach
                                                    </select> 
                                                </td>
                                            </tr>
                                        @endif
                                        <input type="hidden" name="nTtlRec" id="nTtlRec" value="1">
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
function tchrSch(id){
    $("#TchrSchool #lTchrIdNo").val(id);
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