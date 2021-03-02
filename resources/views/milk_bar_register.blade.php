@include('layouts.header')
<?php 
use App\Model\School;
  if(Session::has('register_milkBar')){
    $register_milkBar=Session::get('register_milkBar');
}
?>
<style>
.btn-warning{
    width: 50px; 
    padding: 10px;
    margin:auto; 
}
.btn-primary{
    padding: 6px 10px 6px 10px;
    margin:auto;
}

</style>
<div class="Parent-Student-Registration section-padding bg-milk">
    <form action="{{url('registration/milkbar/save')}}" method="post" id="general_form">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    	<div class="container">
            <div class="row">
                <div class="col-3">&nbsp;</div>
                <div class="col-9 form-area">
                    <div class="d-none Js_msg"></div>
                    @if(Session::has('Success'))
                    <div class="alert alert-success">
                        <strong>Success ! </strong> {{Session::get('Success')}}
                    </div>
                    @endif
                    @if(Session::has('Failed'))
                    <div class="alert alert-danger">
                        <strong>Failed ! </strong> {{Session::get('Failed')}}
                    </div>
                    @endif
                    <div class="switch-link">
                        <ul>
                            <li><a href="{{ url('registration/parent') }}">Parent/Student </a></li>
                            <li><a href="#" class="active">Service Provider</a></li>
                            <li><a href="{{ url('registration/teacher') }}">Teacher</a></li>
                        </ul>
                    </div>
                    <h3 class="form-heading">Service Provider Registration</h3>
                    <div class="form-field-area">
                        <h3>Store Information</h3>
                        <div class="row form-group form-main-div">
                            <div class="col-6 box-div">
                                <label>Registered Business Name</label>
                                <input type="text" class="@error('sBussName') is-invalid @enderror" name="sBussName" id="sBussName" value="{{ old('sBussName') }}" onkeypress="return IsAlpha(event, this.value, '50')" required />
                                @error('sBussName') <div class="invalid-feedback"><span>{{$errors->first('sBussName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 box-div">
                                <label>Business Type</label>
                                <select class="@error('nBussType') is-invalid @enderror" id="nBussType" name="nBussType" required>
                                    <option value="">Select Business Type</option>
                                    @foreach(config('constant.BUSS_TYPE') as $sTypeName => $nBussType)
                                        <option {{ old('nBussType') == $nBussType ? 'selected' : ''}} value="{{$nBussType}}">{{$sTypeName}}</option>
                                    @endforeach
                                </select>
                                @error('nBussType') <div class="invalid-feedback"><span>{{$errors->first('nBussType')}}</span></div>@enderror
                            </div>
                        </div>
                        <div class="row form-group form-main-div">
                            <div class="col-6 box-div">
                                <label>Business ABN</label>
                                <input type="text" class="abn @error('sAbnNo') is-invalid @enderror" name="sAbnNo" id="sAbnNo" value="{{ old('sAbnNo') }}" onkeypress="return IsNumber(event, this.value, '14')" required />
                                @error('sAbnNo') <div class="invalid-feedback"><span>{{$errors->first('sAbnNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 box-div">
                                <label for="Country">Country Name</label>
                                <select class="@error('lCntryIdNo') is-invalid @enderror" name="lCntryIdNo" id="lCntryIdNo" onchange="GetState(this.value)">
                                    <option value="">== Select Country ==</option>
                                    @foreach($aCntryLst as $aRec)
                                    <option {{ old('lCntryIdNo') == $aRec['lCntry_IdNo'] ? 'selected' : ''}} value="{{$aRec['lCntry_IdNo']}}" data-code="{{$aRec['sCntry_Code']}}">{{$aRec['sCntry_Name']}}</option>
                                    @endforeach
                                </select>
                                @error('lCntryIdNo') <div class="invalid-feedback"><span>{{$errors->first('lCntryIdNo')}}</span></div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-field-area">
                        <h3>Store Information Section</h3>
                        <div class="row form-group form-main-div">
                            <div class="col-6 box-div">
                                <label>Street No.</label>
                                <input type="text" class="@error('sStrtNo') is-invalid @enderror" name="sStrtNo" id="sStrtNo" value="{{ old('sStrtNo') }}" onkeypress="return IsNumber(event, this.value, '4')" required />
                                @error('sStrtNo') <div class="invalid-feedback"><span>{{$errors->first('sStrtNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 box-div">
                                <label>Street Name</label>
                                <input type="text" class="@error('sStrtName') is-invalid @enderror" id="sStrtName" value="{{ old('sStrtName') }}"  name="sStrtName" onkeypress="return IsAlpha(event, this.value, '50')" required />
                                @error('sStrtName') <div class="invalid-feedback"><span>{{$errors->first('sStrtName')}}</span></div>@enderror
                            </div>
                        </div>
                        <div class="row form-group form-main-div">
                            <div class="col-6 box-div">
                                <label>Suburb</label>
                                <input type="text" class="@error('sSbrbName') is-invalid @enderror" name="sSbrbName" id="sSbrbName" value="{{ old('sSbrbName') }}" onkeypress="return IsAlpha(event, this.value, '20')" required />
                                @error('sSbrbName') <div class="invalid-feedback"><span>{{$errors->first('sSbrbName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 box-div">
                                <input type="hidden" id="lStateIdNoHid" value="{{old('lStateIdNo')}}">
                                <label for="State">State Name</label>
                                <select class="@error('lStateIdNo') is-invalid @enderror" name="lStateIdNo" id="lStateIdNo">
                                    <option value="">Select State</option>
                                </select>
                                @error('lStateIdNo') <div class="invalid-feedback"><span>{{$errors->first('lStateIdNo')}}</span></div>@enderror
                            </div>
                        </div>
                        <div class="row form-group form-main-div">
                            <div class="col-6 box-div">
                                <label>Post Code</label>
                                <input type="text" class="@error('sPinCode') is-invalid @enderror" name="sPinCode" id="sPinCode" value="{{ old('sPinCode') }}" onkeypress="return IsNumber(event, this.value, '4')" required />
                                @error('sPinCode') <div class="invalid-feedback"><span>{{$errors->first('sPinCode')}}</span></div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-field-area">
                        <h3>Business Contact Details</h3>
                        <div class="row form-group form-main-div">
                            <div class="col-6 box-div">
                                <label>First Name</label>
                                <input type="text" class="@error('sFrstName') is-invalid @enderror" name="sFrstName" id="sFrstName" value="{{ old('sFrstName') }}" onkeypress="return IsAlpha(event, this.value, '15')" required />
                                @error('sFrstName') <div class="invalid-feedback"><span>{{$errors->first('sFrstName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 box-div">
                                <label>Last Name</label>
                                <input type="text" class="@error('sLstName') is-invalid @enderror" name="sLstName" id="sLstName" value="{{ old('sLstName') }}" onkeypress="return IsAlpha(event, this.value, '15')" required />
                                @error('sLstName') <div class="invalid-feedback"><span>{{$errors->first('sLstName')}}</span></div>@enderror
                            </div>
                        </div>
                        <div class="row form-group form-main-div">
                            <div class="col-6 box-div">
                                <label for="Contact_Mobile">Phone Number</label>
                                <div class="row">
                                    <div class="col-3 p-r-0"> <input type="text" class="cnoutry_code" value="" readonly /></div>
                                    <div class="col-4 p-l-0 p-r-0"> <input type="text" class="area_code" name="sAreaCode" id="sAreaCode" value="{{ old('sAreaCode') }}" placeholder="Area Code" onkeypress="return IsNumber(event, this.value, '1')" required readonly /></div>
                                    <div class="col-5 p-l-0"><input type="text" class="contact_number @error('sPhoneNo') is-invalid @enderror" name="sPhoneNo" id="sPhoneNo" value="{{ old('sPhoneNo') }}" onkeypress="return IsPhone(event, this.value, '9')" required /></div>
                                </div>
                                @error('sPhoneNo') <div class="invalid-feedback"><span>{{$errors->first('sPhoneNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 box-div">
                                <label for="Contact_Mobile">Contact Mobile</label>
                                <div class="row">
                                    <div class="col-3 p-r-0"> <input type="text" class="cnoutry_code" name="sCntryCode" id="sCntryCode" value="{{ old('sCntryCode') }}" readonly /></div>
                                    <div class="col-9 p-l-0"><input type="text" class="contact_number @error('sMobileNo') is-invalid @enderror" name="sMobileNo" id="sMobileNo" value="{{ old('sMobileNo') }}" onkeypress="return IsMobile(event, this.value, '11')" required /></div>
                                </div>
                                @error('sMobileNo') <div class="invalid-feedback"><span>{{$errors->first('sMobileNo')}}</span></div>@enderror
                            </div>
                        </div>
                        <div class="row form-group form-main-div">
                            <div class="col-12">
                                <label>Email</label>
                                <input type="email" class="@error('sEmailId') is-invalid @enderror" name="sEmailId" id="sEmailId" value="{{ old('sEmailId') }}"  onkeypress="return LenCheck(event, this.value, '50')" required />
                                @error('sEmailId') <div class="invalid-feedback"><span>{{$errors->first('sEmailId')}}</span></div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group form-main-div">
                <div class="col-12 child-login-details"> 
                    <div class="row">
                        <div class="col-6">
                            <h3>School's You Service</h3>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-6" style="width:0px;margin-left: 300px;">
                                    <button type="button" class="btn btn-warning add_row" onclick="CrtRow()"><i class="fa fa-plus"></i> Add Row</button>
                                </div>
                                <div class="col-6" style="width:0px;margin-left: 70px;">
                                    <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#MilkSchool"> Request New School</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="Add-School-Table">
                        <div style="overflow-x:auto;">
                            <table class="table-border">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>School Type</th>
                                        <th style="width: 329px;">School Name</th>
                                        <th>Distance (in KM.)</th>
                                        <th>Suburb</th>
                                        <th style="width: 110px;">Post Code</th>
                                        <th style="width: 155px;">Order Cut Off Time</th>
                                    </tr>
                                </thead>
                                <tbody id="schoolGrid">
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
                                                        <select name="nSchlType{{$nkey}}" id="nSchlType" data-id="{{$nkey}}" class="@error('nSchlType{{$nkey}}') is-invalid @enderror" onchange="GetSchlLst('{{$nkey}}', this.value)">
                                                            <option value="">Select School Type</option>
                                                            @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                            <option {{ old('nSchlType'.$nkey) == $nType ? 'selected' : ''}} value="{{$nType}}">{{$sTypeName}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="lSchlIdNo{{$nkey}}" id="lSchlIdNo{{$nkey}}" class="@error('lSchlIdNo{{$nkey}}') is-invalid @enderror" onchange="ChngDtl('{{$nkey}}')">
                                                            <option value="">Select School Name</option>
                                                            @if(!empty($aSchlLst))
                                                                @foreach($aSchlLst as $sData)
                                                                <option {{ old('lSchlIdNo'.$nkey) == $sData->lSchl_IdNo ? 'selected' : ''}} value="{{$sData->lSchl_IdNo}}">{{$sData->sSchl_Name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="dDistKm{{$nkey}}" value="{{ old('dDistKm'.$nkey) }}" id="dDistKm{{$nkey}}" onkeypress="return isNumberKey(event)" onchange="fn_do('{{$nkey}}')"/></td>
                                                    <td><input type="text" name="sSbrbName{{$nkey}}" id="sSbrbName{{$nkey}}" value="{{ old('sSbrbName'.$nkey) }}" onkeypress="return IsAlpha(event, this.value, '15')" readonly /></td>
                                                    <td><input type="text" name="sPinCode{{$nkey}}" id="sPinCode{{$nkey}}" value="{{ old('sPinCode'.$nkey) }}" onkeypress="return IsNumber(event, this.value, '4')" readonly/></td>
                                                    <td><input type="time" name="sCutTm{{$nkey}}" value="{{ old('sCutTm'.$nkey) }}" /></td>
                                                </tr>
                                            @endif
                                        @endfor
                                    @else
                                        <tr id="Row_1">
                                            <td><i class="fa fa-minus" onclick="DeleteRow(1)"></i></td>
                                            <td>
                                                <select name="nSchlType1" onchange="GetSchlLst(1, this.value)">
                                                    <option value="">Select School Type</option>
                                                    @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                        <option value="{{$nType}}">{{$sTypeName}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="lSchlIdNo1" id="lSchlIdNo1" onchange="ChngDtl(1)">
                                                    <option value="">Select School Name</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="dDistKm1" value="{{ old('dDistKm1') }}" id="dDistKm1" onkeypress="return isNumberKey(event)" onchange="fn_do(1)" /></td>
                                            <td><input type="text" name="sSbrbName1" id="sSbrbName1" value="{{ old('sSbrbName1') }}" onkeypress="return IsAlpha(event, this.value, '15')" readonly/></td>
                                            <td><input type="text" name="sPinCode1" id="sPinCode1" value="{{ old('sPinCode1') }}" onkeypress="return IsNumber(event, this.value, '4')" readonly/></td>
                                            <td><input type="time" name="sCutTm1" value="{{ old('sCutTm1') }}" /></td>
                                        </tr>
                                    @endif
                                    <input type="hidden" name="nTtlRec" id="nTtlRec" value="{{old('nTtlRec',1)}}">
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <h6 style="color: #ff0000bd;margin-left: 15px;margin-top: 15px;"><?php echo ucwords("Please select minimum one school or request for new school");?></h6>
                        </div>
                    </div>
                    <h3 class="form-heading-2">Login Credentials</h3>
                    <div class="row form-group form-main-div">
                        <div class="col-6 box-div">
                            <label for="Password">Password </label>
                            <input type="password" class="@error('sLgnPass') is-invalid @enderror" name="sLgnPass" id="sLgnPass" value="{{ old('sLgnPass') }}" id="sLgnPass" onkeypress="return LenCheck(event, this.value, '16')" required />
                            <span class="show_password" onmousedown ="ShowPass('sLgnPass');"><i class="sLgnPass fa fa-eye-slash" aria-hidden="true"></i></span>
                            @error('sLgnPass') <div class="invalid-feedback"><span>{{$errors->first('sLgnPass')}}</span></div>@enderror
                        </div>
                        <div class="col-6 box-div">
                            <label for="Password">Confirm Password </label>
                            <input type="password" class="@error('sCnfrmPass') is-invalid @enderror" name="sCnfrmPass"  id="sCnfrmPass" value="{{ old('sCnfrmPass') }}"  id="sCnfrmPass"  onkeypress="return LenCheck(event, this.value, '16')" required />
                            <span class="show_password" onmousedown ="ShowPass('sCnfrmPass');"><i class="sCnfrmPass fa fa-eye-slash" aria-hidden="true"></i></span>
                            @error('sCnfrmPass') <div class="invalid-feedback"><span>{{$errors->first('sCnfrmPass')}}</span></div>@enderror
                        </div>
                     </div>
                    <div class="row form-group form-main-div text-center">
                        <div class="custom-checkbox">
                            <label class="checkmarkcontainer"><a href="{{url('terms-and-conditions')}}" target="_blank"> Accept Terms & Conditions</a> / <a href="{{url('privacy-policy')}}" target="_blank"> Privacy Policy</a>
                                <input type="checkbox" name="nTerms">
                                <span class="checkmark @error('nTerms') is-invalid @enderror" ></span>
                            </label>
                            @error('nTerms') <div class="invalid-feedback"><span>{{$errors->first('nTerms')}}</span></div>@enderror
                       </div>
                       <div class="row form-group form-main-div text-center">
                            <button type="submit" class="btn-blue">REGISTER</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
 @include('layouts.footer')
 @include('layouts.school_request')
<script type="text/javascript">   
$('#CategoryModel').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var lCatgIdNo = button.data('id');
  var sCatgName = button.data('name');
  $(this).find('#lCatgIdNo').val(lCatgIdNo);
  $(this).find('#sCatgName').val(sCatgName);
});
var nRowId;
function CrtRow() 
{
    var rowCount = $('#schoolGrid tr').length; 
    if(rowCount == 3)
    {
        alert("Maximum 3 school allowed...");
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
        content += '<td><select name="nSchlType'+next_no+'"  onchange="GetSchlLst('+next_no+', this.value)"><option value="">Select School Type</option>@foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)<option {{ old('nSchlType1') == $nType ? 'selected' : ''}} value="{{$nType}}">{{$sTypeName}}</option>@endforeach</select></td>';
        content += '<td><select name="lSchlIdNo'+next_no+'" id="lSchlIdNo'+next_no+'" onchange="ChngDtl('+next_no+')"><option value="">Select School Name</option></select></td>';
        content += '<td><input type="text" name="dDistKm'+next_no+'" id="dDistKm'+next_no+'" onkeypress="return isNumberKey(event)" onchange="fn_do('+next_no+')" /></td>';
        content += '<td><input type="text" name="sSbrbName'+next_no+'" id="sSbrbName'+next_no+'" onkeypress="return IsAlpha(event, this.value, 15)" readonly/></td>';
        content += '<td><input type="text" name="sPinCode'+next_no+'" id="sPinCode'+next_no+'" onkeypress="return IsNumber(event, this.value, 4)" readonly/></td>';
        content += '<td><input type="time" name="sCutTm'+next_no+'" /></td>';
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
    GetState(lCntryIdNo, lStateIdNo);
});

$().ready(function () {
    $('.abn').bindABNControls();
});

</script>