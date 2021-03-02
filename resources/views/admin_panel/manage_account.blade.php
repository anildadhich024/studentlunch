@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('admin_panel.layouts.side_panel')
            <form action="{{url('admin_panel/manage_account/save')}}" method="post" id="general_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                                    <h4>Business Information</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Business Name</label>
                                <input type="text" class="form-control @error('sCompName') is-invalid @enderror" name="sCompName" value="{{ old('sCompName', $aCompDtl['sComp_Name']) }}" onkeypress="return IsAlpha(event, this.value, '50')" required />
                                @error('sCompName') <div class="invalid-feedback"><span>{{$errors->first('sCompName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Business ABN</label>
                                <input type="text" class="form-control abn @error('sAbnNo') is-invalid @enderror" name="sAbnNo" value="{{ old('sAbnNo', $aCompDtl['sAbn_No']) }}" onkeypress="return IsNumber(event, this.value, '14')" required />
                                @error('sAbnNo') <div class="invalid-feedback"><span>{{$errors->first('sAbnNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Country</label>
                                <select class="form-control @error('lCntryIdNo') is-invalid @enderror" name="lCntryIdNo" id="lCntryIdNo" onchange="GetState(this.value)" required>
                                    <option value="">== Select Country ==</option>
                                    @foreach($aCntryLst as $aRec)
                                        <option {{ old('lCntryIdNo', $aCompDtl['lCntry_IdNo']) == $aRec['lCntry_IdNo'] ? 'selected' : ''}} value="{{$aRec['lCntry_IdNo']}}" data-code="{{$aRec['sCntry_Code']}}">{{$aRec['sCntry_Name']}}</option>
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
                                <input type="text" class="form-control @error('sStrtNo') is-invalid @enderror" name="sStrtNo" value="{{ old('sStrtNo', $aCompDtl['sStrt_No']) }}" onkeypress="return IsNumber(event, this.value, '4')" required />
                                @error('sStrtNo') <div class="invalid-feedback"><span>{{$errors->first('sStrtNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Street Name</label>
                                <input type="text" class="form-control @error('sStrtName') is-invalid @enderror" name="sStrtName" value="{{ old('sStrtName', $aCompDtl['sStrt_Name']) }}" onkeypress="return IsAlpha(event, this.value, '50')" required />
                                @error('sStrtName') <div class="invalid-feedback"><span>{{$errors->first('sStrtName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label> Suburb</label>
                                <input type="text" class="form-control @error('sSbrbName') is-invalid @enderror" name="sSbrbName" value="{{ old('sSbrbName', $aCompDtl['sSbrb_Name']) }}" onkeypress="return IsAlpha(event, this.value, '20')" required />
                                @error('sSbrbName') <div class="invalid-feedback"><span>{{$errors->first('sSbrbName')}}</span></div>@enderror
                            </div> 
                            <div class="col-6 col-sm-4 pb-3">
                                <label>State</label>
                                <input type="hidden" id="lStateIdNoHid" value="{{old('lStateIdNo', $aCompDtl['lState_IdNo'])}}">
                                <select class="form-control @error('lStateIdNo') is-invalid @enderror" name="lStateIdNo" id="lStateIdNo" required>
                                    <option value="">== Select State ==</option>
                                </select>
                                @error('lStateIdNo') <div class="invalid-feedback"><span>{{$errors->first('lStateIdNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label> Post Code</label>
                                <input type="text" class="form-control @error('sPinCode') is-invalid @enderror" name="sPinCode" value="{{ old('sPinCode', $aCompDtl['sPin_Code']) }}" onkeypress="return IsNumber(event, this.value, '4')" required />
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
                                <input type="text" class="form-control @error('sFrstName') is-invalid @enderror" name="sFrstName" value="{{ old('sFrstName', $aCompDtl['sFrst_Name']) }}" onkeypress="return IsAlpha(event, this.value, '15')" required />
                                @error('sFrstName') <div class="invalid-feedback"><span>{{$errors->first('sFrstName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Surname</label>
                                <input type="text" class="form-control @error('sLstName') is-invalid @enderror" name="sLstName" value="{{ old('sLstName', $aCompDtl['sLst_Name']) }}" onkeypress="return IsAlpha(event, this.value, '15')" required />
                                @error('sLstName') <div class="invalid-feedback"><span>{{$errors->first('sLstName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-5 mb-4 ">
                                <div class="col row">
                                    <label>Phone Number </label>
                                    <div class="row pr-0">
                                        <div class="col-lg-3 col-6 p-l-15">
                                            <input type="text" class="form-control cnoutry_code" name="sCntryCodePhone" value="{{ old('sCntryCodePhone', $aCompDtl['sCntry_Code']) }}" readonly />    
                                        </div>
                                        <div class="col-lg-3 col-6 p-0">
                                            <input type="text" class="form-control" name="sAreaCode" id="sAreaCode" value="{{ old('sAreaCode', $aCompDtl['sArea_Code']) }}" placeholder="Area Code" onkeypress="return IsNumber(event, this.value, '1')" required readonly />    
                                        </div>
                                        <div class="col-lg-6  col-12  pl-0 rplmedia pr-0">
                                            <input type="text" class="form-control @error('sPhoneNo') is-invalid @enderror" name="sPhoneNo" id="sPhoneNo" value="{{ old('sPhoneNo', $aCompDtl['sPhone_No']) }}" onkeypress="return IsPhone(event, this.value, '9')" required />    
                                        </div>
                                    </div>
                                    @error('sPhoneNo') <div class="invalid-feedback"><span>{{$errors->first('sPhoneNo')}}</span></div>@enderror
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Mobile Number </label>
                                <div class="row">
                                    <div class="col-lg-3 col-4 p-l-15">
                                        <input type="text" class="form-control cnoutry_code" name="sCntryCode" value="{{ old('sCntryCode', $aCompDtl['sCntry_Code']) }}" readonly />    
                                    </div>
                                    <div class="col-lg-9 col-8 p-r-15">
                                        <input type="text" class="form-control @error('sMobileNo') is-invalid @enderror" name="sMobileNo" id="sMobileNo" value="{{ old('sMobileNo', $aCompDtl['sMobile_No']) }}" onkeypress="return IsMobile(event, this.value, '11')" required />    
                                    </div>
                                </div>
                                @error('sMobileNo') <div class="invalid-feedback"><span>{{$errors->first('sMobileNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3 mt40maxmedia575">
                                <label> Email</label>
                                <input type="text" class="form-control @error('sLgnEmail') is-invalid @enderror" name="sLgnEmail" value="{{ old('sLgnEmail', $aCompDtl['sLgn_Email']) }}" onkeypress="return LenCheck(event, this.value, '50')" required />
                                @error('sLgnEmail') <div class="invalid-feedback"><span>{{$errors->first('sLgnEmail')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
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
$(document).ready(function() {
    var lCntryIdNo = $('#lCntryIdNo').val();
    var lStateIdNo = $('#lStateIdNoHid').val();
    if(lStateIdNo != '')
    {
        GetState(lCntryIdNo, lStateIdNo);
    }
});

$().ready(function () {
    $('.abn').bindABNControls();
});
</script>