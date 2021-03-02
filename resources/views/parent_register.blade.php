@include('layouts.header')
<?php if(Session::has('register_parent')){
    $register_parent=Session::get('register_parent');
}?>
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
<div class="Parent-Student-Registration section-padding bg-parent-2">
    <form action="{{url('registration/parent/save')}}" method="post" id="general_form">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="container">
            <div class="row form-group form-main-div">
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
                            <li><a href="#" class="active">Parent/Student </a></li>
                            <li><a href="{{url('registration/milkbar')}}">Service Provider</a></li>
                            <li><a href="{{url('registration/teacher')}}">Teacher</a></li>
                        </ul>
                    </div>
                    <h3 class="form-heading">Parent/Student Registration</h3>
                    <div class="form-field-area">
                        <h3>Your Information</h3>
                        <div class="row form-group form-main-div">
                            <div class="col-6 box-div">
                                <label for="First_Name">First Name </label>
                                <input type="text" id="sFrstName" class="@error('sFrstName') is-invalid @enderror" name="sFrstName" value="{{ old('sFrstName') }}" onkeypress="return IsAlpha(event, this.value, '15')" required onblur="ChkName()" />
                                @error('sFrstName') <div class="invalid-feedback"><span>{{$errors->first('sFrstName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 box-div">
                                <label for="Surname">Surname </label>
                                <input type="text" id="sLstName" class="@error('sLstName') is-invalid @enderror" name="sLstName" value="{{ old('sLstName') }}" onkeypress="return IsAlpha(event, this.value, '15')" required onblur="ChkName()"/>
                                @error('sLstName') <div class="invalid-feedback"><span>{{$errors->first('sLstName')}}</span></div>@enderror
                                <div class="invalid-feedback"><span id="ErrName"></span></div>
                            </div>
                        </div>
                        <div class="row form-group form-main-div">
                            <div class="col-6 box-div">
                                <label for="Country">Country Name</label>
                                <select class="@error('lCntryIdNo') is-invalid @enderror" name="lCntryIdNo" id="lCntryIdNo" onchange="GetState(this.value)" required>
                                	<option value="">== Select Country ==</option>
                                    @foreach($aCntryLst as $aRec)
                                        <option {{ old('lCntryIdNo') == $aRec['lCntry_IdNo'] ? 'selected' : ''}} value="{{$aRec['lCntry_IdNo']}}" data-code="{{$aRec['sCntry_Code']}}">{{$aRec['sCntry_Name']}}</option>
                                    @endforeach
                                </select>
                                @error('lCntryIdNo') <div class="invalid-feedback"><span>{{$errors->first('lCntryIdNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 box-div">
                                <input type="hidden" id="lStateIdNoHid" value="{{old('lStateIdNo')}}">
                                <label for="State">State Name</label>
                                <select class="@error('lStateIdNo') is-invalid @enderror" name="lStateIdNo" id="lStateIdNo" required>
                                    <option value="">== Select State ==</option>
                                </select>
                                @error('lStateIdNo') <div class="invalid-feedback"><span>{{$errors->first('lStateIdNo')}}</span></div>@enderror
                            </div>
                        </div>
                        <div class="row form-group form-main-div">
                            <div class="col-6 box-div">
                                <label for="Contact_Mobile">Contact Mobile</label>
                                <div class="row">
                                <div class="col-3 p-r-0"> <input type="text" class="cnoutry_code" id="sCntryCode" name="sCntryCode" value="{{ old('sCntryCode') }}" readonly required /></div>
                                <div class="col-9 p-l-0"><input type="text" class="contact_number @error('sMobileNo') is-invalid @enderror" name="sMobileNo" id="sMobileNo" value="{{ old('sMobileNo') }}" onkeypress="return IsMobile(event, this.value, '11')" required /></div>
                                </div>
                                @error('sMobileNo') <div class="invalid-feedback"><span>{{$errors->first('sMobileNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 box-div">
                                <label for="Relationship_to_Student">Relationship to Student</label>
                                <select id="Relationship_to_Student" class="@error('lRltnIdNo') is-invalid @enderror" name="lRltnIdNo" id="lRltnId" required>
                                    <option value="">==Select Relationship==</option>
                                    @foreach(config('constant.RLTN_IDNO') as $sRelName => $lRelIdNo)
                                        <option {{ old('lRltnIdNo') == $lRelIdNo ? 'selected' : ''}} value="{{$lRelIdNo}}">{{$sRelName}}</option>
                                    @endforeach
                                </select>
                                @error('lRltnIdNo') <div class="invalid-feedback"><span>{{$errors->first('lRltnIdNo')}}</span></div>@enderror
                            </div>
                        </div>
                        <div class="row form-group form-main-div">
                            <div class="col-12 box-div">
                                <label for="Email">Email Address</label>
                                <input type="text" class="@error('sEmailId') is-invalid @enderror" name="sEmailId" id="sEmailId" value="{{ old('sEmailId') }}" IsEmail='Yes' onkeypress="return LenCheck(event, this.value, '50')" required />
                                @error('sEmailId') <div class="invalid-feedback"><span>{{$errors->first('sEmailId')}}</span></div>@enderror
                            </div>
                        </div>
                        <div class="row form-group form-main-div">
                            <div class="col-6 box-div">
                                <label for="Suburb">Suburb </label>     
                                <input type="text" class="@error('sSbrbName') is-invalid @enderror" name="sSbrbName" id="sSbrbName" value="{{ old('sSbrbName') }}" onkeypress="return IsAlpha(event, this.value, '20')" required />
                                @error('sSbrbName') <div class="invalid-feedback"><span>{{$errors->first('sSbrbName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 box-div">
                                <label for="Post_Code">Post Code </label>
                                <input type="text" class="@error('sPinCode') is-invalid @enderror" name="sPinCode" id="sPinCode" value="{{ old('sPinCode') }}" onkeypress="return IsNumber(event, this.value, '4')"  required />
                                @error('sPinCode') <div class="invalid-feedback"><span>{{$errors->first('sPinCode')}}</span></div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
           </div> 
           <div class="row form-group form-main-div">
                <div class="col-12 box-div child-login-details">
                    
                    <div class="row">
                        <div class="col-6">
                            <h3>Child Details</h3>
                        </div>
                        <div class="col-6" style="width:0px;margin-left: 300px;">
                            <button type="button" class="btn btn-warning add_row" onclick="CrtRow()"><i class="fa fa-plus"></i> Add Row</button>
                        </div>
                        <div class="col-6" style="width:0px;margin-left: 70px;">
                            <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#ParentSchool"> Request New School</a>
                        </div>
                    </div>
                    
                    <div class="Add-School-Table">
                        <div style="overflow-x:auto;">
                            <table class="table-border">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>School Type</th>
                                        <th style="width: 340px;">School Name</th>
                                        <th>Child First Name</th>
                                        <th>Child Surname</th>
                                        <th style="width: 100px;">Class</th>
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
                                                        <select name="lSchlIdNo{{$nkey}}" id="lSchlIdNo{{$nkey}}" class="@error('lSchlIdNo{{$nkey}}') is-invalid @enderror">
                                                            <option value="">Select School Name</option>
                                                            @if(!empty($aSchlLst))
                                                                @foreach($aSchlLst as $sData)
                                                                <option {{ old('lSchlIdNo'.$nkey) == $sData->lSchl_IdNo ? 'selected' : ''}} value="{{$sData->lSchl_IdNo}}">{{$sData->sSchl_Name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="@error('sFrstName{{$nkey}}') is-invalid @enderror" name="sFrstName{{$nkey}}" value="{{ old('sFrstName'.$nkey) }}" onkeypress="return IsAlpha(event, this.value, '15')"/></td>
                                                    <td><input type="text" class="@error('sLstName{{$nkey}}') is-invalid @enderror" name="sLstName{{$nkey}}"  value="{{ old('sLstName'.$nkey) }}" onkeypress="return IsAlpha(event, this.value, '15')" /></td>
                                                    <td><input type="text" class="@error('sClsName{{$nkey}}') is-invalid @enderror" name="sClsName{{$nkey}}" value="{{ old('sClsName'.$nkey) }}" onkeypress="return IsAlphaNum(event, this.value, '4')" /></td>
                                                </tr>
                                            @endif
                                        @endfor
                                    @else
                                        <tr id="Row_1">
                                            <td><i class="fa fa-minus" onclick="DeleteRow(1)"></i></td>
                                            <td>
                                                <select name="nSchlType1" id="nSchlType" data-id="1" class="@error('nSchlType1') is-invalid @enderror" onchange="GetSchlLst(1, this.value)">
                                                    <option value="">Select School Type</option>
                                                    @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                    <option value="{{$nType}}">{{$sTypeName}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="lSchlIdNo1" id="lSchlIdNo1">
                                                    <option value="">Select School Name</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="sFrstName1" value="" onkeypress="return IsAlpha(event, this.value, '15')" /></td>
                                            <td><input type="text" name="sLstName1" value="" onkeypress="return IsAlpha(event, this.value, '15')" /></td>
                                            <td><input type="text" name="sClsName1" value="" onkeypress="return IsAlphaNum(event, this.value, '4')" /></td>
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
                                <input type="password" class="@error('sLgnPass') is-invalid @enderror" name="sLgnPass" value="{{ old('sLgnPass') }}" id="sLgnPass" onkeypress="return LenCheck(event, this.value, '16')" required autocomplete="off" />
                            <span class="show_password" onmousedown ="ShowPass('sLgnPass');"><i class="sLgnPass fa fa-eye-slash" aria-hidden="true"></i></span>
                            @error('sLgnPass') <div class="invalid-feedback"><span>{{$errors->first('sLgnPass')}}</span></div>@enderror
                        </div>
                        <div class="col-6 box-div">
                            <label for="Password">Confirm Password </label>
                            <input type="password" class="@error('sCnfrmPass') is-invalid @enderror" name="sCnfrmPass" value="{{ old('sCnfrmPass') }}" id="sCnfrmPass" onkeypress="return LenCheck(event, this.value, '16')" required autocomplete="off" />
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
                            <button type="submit" class="btn-blue submit">REGISTER</button>
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
var nRowId;
function CrtRow() 
{
    var rowCount = $('#schoolGrid tr').length;
    if(rowCount == 5)
    {
        alert("Maximum 5 child allowed...");
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
        content += '<td><select name="nSchlType'+next_no+'"  onchange="GetSchlLst('+next_no+', this.value)"><option value="">Select School Type</option>@foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)<option  {{ old('nSchlType1') == $nType ? 'selected' : ''}} value="{{$nType}}">{{$sTypeName}}</option>@endforeach</select></td>';
        content += '<td><select name="lSchlIdNo'+next_no+'" id="lSchlIdNo'+next_no+'"><option value="">Select School Name</option></select></td>';
        content += '<td><input type="text" name="sFrstName'+next_no+'" onkeypress="return IsAlpha(event, this.value, 15)" /></td>';
        content += '<td><input type="text" name="sLstName'+next_no+'" onkeypress="return IsAlpha(event, this.value, 15)" /></td>';
        content += '<td><input type="text" name="sClsName'+next_no+'" onkeypress="return IsAlphaNum(event, this.value, 4)" /></td>';
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
</script>