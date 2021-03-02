@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('admin_panel.layouts.side_panel')
            <form action="{{url('admin_panel/school/save')}}" method="post" id="general_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="lSchlIdNo" id="lSchlIdNo" value="{{ !empty($aSchlDtl) ? base64_encode(!empty($aSchlDtl) ? $aSchlDtl['lSchl_IdNo'] : '') : base64_encode(0)}}">
                <main>
                    <div class="page-breadcrumb">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="page-title">Add School</h4>
                            </div>
                        </div>
                    </div>
                    <!-- My Commissions From -->
                    <div class="container-fluid card-commission-section  parent-details-section">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <div>
                                    <h4>School Information</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row  account-form">
                            <div class="col-6 col-sm-4 pb-3">
                                <label>School Name</label>
								<input type="text" class="form-control @error('sSchlName') is-invalid @enderror" name="sSchlName" value="{{ old('sSchlName', !empty($aSchlDtl) ? $aSchlDtl['sSchl_Name'] : '') }}"  onkeypress=" return IsSchool(event, this.value, '50')" required />
                                @error('sSchlName') <div class="invalid-feedback"><span>{{$errors->first('sSchlName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>School Type</label>
                                <select class="form-control @error('lSchlType') is-invalid @enderror" name="lSchlType" required>
									<option value="">School Type</option>
									@foreach(config('constant.SCHL_TYPE') as $sTypeName => $lSchlType)
										<option {{ old('lSchlType', !empty($aSchlDtl) ? $aSchlDtl['lSchl_Type'] : '') == $lSchlType ? 'selected' : ''}} value="{{$lSchlType}}">{{$sTypeName}}</option>
									@endforeach
								</select>
                                @error('lSchlType') <div class="invalid-feedback"><span>{{$errors->first('lSchlType')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Country</label>
                                <select class="form-control @error('lCntryIdNo') is-invalid @enderror" name="lCntryIdNo" id="lCntryIdNo" onchange="GetState(this.value)" required>
                                    <option value="">== Select Country ==</option>
                                    @foreach($aCntryLst as $aRec)
                                        <option {{ old('lCntryIdNo', !empty($aSchlDtl) ? $aSchlDtl['lCntry_IdNo'] : '') == $aRec['lCntry_IdNo'] ? 'selected' : ''}} value="{{$aRec['lCntry_IdNo']}}" data-code="{{$aRec['sCntry_Code']}}">{{$aRec['sCntry_Name']}}</option>
                                    @endforeach
                                </select>
                                @error('lCntryIdNo') <div class="invalid-feedback"><span>{{$errors->first('lCntryIdNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>School Email</label>
                                <input type="email" class="form-control @error('sEmailId') is-invalid @enderror" name="sEmailId" value="{{ old('sEmailId', !empty($aSchlDtl) ? $aSchlDtl['sEmail_Id'] : '') }}" onkeypress="return LenCheck(event, this.value, '50')" required />
                                @error('sEmailId') <div class="invalid-feedback"><span>{{$errors->first('sEmailId')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <div class="col row pr-0"> 
                                <label>Phone Number </label>
                                    <div class="row pr-0">
                                        <div class="col-lg-3 col-6 p-l-15">
                                            <input type="text" class="form-control cnoutry_code" name="sCntryCode" value="{{ old('sCntryCode', !empty($aSchlDtl) ? $aSchlDtl['sCntry_Code'] : '') }}" readonly required />    
                                        </div>
                                        <div class="col-lg-3 col-6 p-0">
                                            <input type="text" class="form-control" name="sAreaCode" id="sAreaCode" value="{{ old('sAreaCode', !empty($aSchlDtl) ? $aSchlDtl['sArea_Code'] : '') }}" placeholder="Area Code"  onkeypress="return IsNumber(event, this.value, '1')" required readonly />    
                                        </div>
                                        <div class="col-lg-6  col-12  pl-0 rplmedia pr-0">
                                            <input type="text" class="form-control @error('sPhoneNo') is-invalid @enderror" name="sPhoneNo" id="sPhoneNo" value="{{ old('sPhoneNo', !empty($aSchlDtl) ? $aSchlDtl['sPhone_No'] : '') }}" onkeypress="return IsPhone(event, this.value, '9')" required />    
                                        </div>
                                    </div>
                                </div>
                                @error('sPhoneNo') <div class="invalid-feedback"><span>{{$errors->first('sPhoneNo')}}</span></div>@enderror
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
                                <input type="text" class="form-control @error('sStrtNo') is-invalid @enderror" name="sStrtNo" value="{{ old('sStrtNo', !empty($aSchlDtl) ? $aSchlDtl['sStrt_No'] : '') }}" onkeypress="return IsNumber(event, this.value, '4')" required />
                                @error('sStrtNo') <div class="invalid-feedback"><span>{{$errors->first('sStrtNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Street Name</label>
                                <input type="text" class="form-control @error('sStrtName') is-invalid @enderror" name="sStrtName" value="{{ old('sStrtName', !empty($aSchlDtl) ? $aSchlDtl['sStrt_Name'] : '') }}" onkeypress="return IsAlpha(event, this.value, '50')" required  />
                                @error('sStrtName') <div class="invalid-feedback"><span>{{$errors->first('sStrtName')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label>Suburb</label>
                                <input type="text" class="form-control @error('sSbrbName') is-invalid @enderror" name="sSbrbName" value="{{ old('sSbrbName', !empty($aSchlDtl) ? $aSchlDtl['sSbrb_Name'] : '') }}" onkeypress="return IsAlpha(event, this.value, '30')" required  />
                                @error('sSbrbName') <div class="invalid-feedback"><span>{{$errors->first('sSbrbName')}}</span></div>@enderror
                            </div> 
                            <div class="col-6 col-sm-4 pb-3">
                                <input type="hidden" id="lStateIdNoHid" value="{{old('lStateIdNo', $aSchlDtl['lState_IdNo'])}}">
                                <label>State</label>
                                <select class="form-control @error('lStateIdNo') is-invalid @enderror" name="lStateIdNo" id="lStateIdNo" required>
                                    <option value="">== Select State ==</option>
                                </select>
                                @error('lStateIdNo') <div class="invalid-feedback"><span>{{$errors->first('lStateIdNo')}}</span></div>@enderror
                            </div>
                            <div class="col-6 col-sm-4 pb-3">
                                <label> Post Code</label>
                                <input type="text" class="form-control @error('sPinCode') is-invalid @enderror" name="sPinCode" value="{{ old('sPinCode', !empty($aSchlDtl) ? $aSchlDtl['sPin_Code'] : '') }}" onkeypress="return IsNumber(event, this.value, '4')" required  />
                                @error('sPinCode') <div class="invalid-feedback"><span>{{$errors->first('sPinCode')}}</span></div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid card-commission-section parent-list-section parent-details-section">
                        <div class="row ">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-11">
                                        <h4>Contact Details :</h4>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-warning" onclick="CrtRow()"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="Add-School-Table">
							<div style="overflow-x:auto;" class="col-sm-12 col-lg-12 school-service-table" id="GridDiv">
								<table style="  width: 100%;" class="mt-3  tablescroll">
									<thead>
										<tr>
											<th class="nowordwrap"></th>
											<th class="nowordwrap">Role</th>
											<th class="nowordwrap">Title</th>
											<th class="nowordwrap">First Name</th>
											<th class="nowordwrap">Surname</th>
											<th class="nowordwrap">Phone Number</th>
											<th class="nowordwrap">Mobile Number</th>
											<th class="nowordwrap">Email</th>
										</tr>
									</thead>
									<tbody id="CntctGrid">
                                        @if(!empty($errors->all()) || !empty($aCntctDtl))
                                            @if(!empty($errors->all()))
                                                @php
                                                    $nkey = 1;
                                                @endphp
                                                @for($nkey=1; $nkey<=old('nTtlRec');$nkey++)
                                                    @if(!empty(old('nCntctRole'.$nkey)) && !empty(old('sFrstName'.$nkey)) && !empty(old('sLstName'.$nkey)) && !empty(old('sPhoneNo'.$nkey)) && !empty(old('sMobileNo'.$nkey)) && !empty(old('sEmailId'.$nkey)))
                                                        <input type="hidden" name="lSchlCntctIdNo{{$nkey}}" value="{{old('lSchlCntctIdNo')}}"/>
                                                        <tr id="Row_{{$nkey}}">
                                                            <td><i class="fa fa-minus" onclick="DeleteRow('{{$nkey}}')"></i></td>
                                                            <td>
                                                                <select class="form-control" name="nCntctRole{{$nkey}}" required>
                                                                    <option value="">Select Role</option>
                                                                    @foreach(config('constant.SCHL_ROLE') as $sRoleName => $nCntctRole)
                                                                        <option {{ old('nCntctRole'.$nkey) == $nCntctRole ? 'selected' : ''}} value="{{$nCntctRole}}">{{$sRoleName}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-control" name="nCntctTitle{{$nkey}}" required>
                                                                    <option value="">Select Title</option>
                                                                    @foreach(config('constant.TITLE') as $sTitleName => $nCntctTitle)
                                                                        <option {{ old('nCntctTitle'.$nkey) == $nCntctTitle ? 'selected' : ''}} value="{{$nCntctTitle}}">{{$sTitleName}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="sFrstName{{$nkey}}" value="{{old('sFrstName'.$nkey)}}" onkeypress="return IsAlpha(event, this.value, '15')" required />
                                                            </td>
                                                            <td>
                                                                <input type="text" name="sLstName{{$nkey}}" value="{{old('sLstName'.$nkey)}}" onkeypress="return IsAlpha(event, this.value, '15')" required />
                                                            </td>
                                                            <td>
                                                                <input type="text" name="sPhoneNo{{$nkey}}" id="sPhoneNo{{$nkey}}" value="{{old('sPhoneNo'.$nkey)}}" onkeypress="return IsPhone(event, this.value, '9', '{{$nkey}}')" />
                                                            </td>
                                                            <td>
                                                                <input type="text" name="sMobileNo{{$nkey}}" id="sMobileNo{{$nkey}}" value="{{old('sMobileNo'.$nkey)}}" onkeypress="return IsMobile(event, this.value, '11', '{{$nkey}}')" />
                                                            </td>
                                                            <td>
                                                                <input type="email" name="sEmailId{{$nkey}}" value="{{old('sEmailId'.$nkey)}}" onkeypress="return LenCheck(event, this.value, '50')" required />
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endfor
                                            @else
                                                @php
                                                $i = 1;
                                                @endphp
                                                @foreach($aCntctDtl as $aRes)
                                                    <input type="hidden" name="lSchlCntctIdNo{{$i}}" value="{{$aRes['lSchl_Cntct_IdNo']}}"/>
                                                    <tr id="Row_{{$i}}">
                                                        <td><i class="fa fa-minus" onclick="DeleteRow('{{$i}}')"></i></td>
                                                        <td>
                                                            <select class="form-control" name="nCntctRole{{$i}}" required>
                                                                <option value="">Select Role</option>
                                                                @foreach(config('constant.SCHL_ROLE') as $sRoleName => $nCntctRole)
                                                                    <option {{ $aRes['nCntct_Role'] == $nCntctRole ? 'selected' : ''}} value="{{$nCntctRole}}">{{$sRoleName}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control" name="nCntctTitle{{$i}}" required>
                                                                <option value="">Select Title</option>
                                                                @foreach(config('constant.TITLE') as $sTitleName => $nCntctTitle)
                                                                    <option {{ $aRes['nCntct_Title'] == $nCntctTitle ? 'selected' : ''}} value="{{$nCntctTitle}}">{{$sTitleName}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="sFrstName{{$i}}" value="{{$aRes['sFrst_Name']}}" onkeypress="return IsAlpha(event, this.value, '15')" required />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="sLstName{{$i}}" value="{{$aRes['sLst_Name']}}" onkeypress="return IsAlpha(event, this.value, '15')" required />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="sPhoneNo{{$i}}" id="sPhoneNo{{$i}}" value="{{$aRes['sPhone_No']}}" onkeypress="return IsPhone(event, this.value, '9', '{{$i}}')" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="sMobileNo{{$i}}" id="sMobileNo{{$i}}" value="{{$aRes['sMobile_No']}}" onkeypress="return IsMobile(event, this.value, '11', '{{$i}}')" />
                                                        </td>
                                                        <td>
                                                            <input type="email" name="sEmailId{{$i}}" value="{{$aRes['sEmail_Id']}}" onkeypress="return LenCheck(event, this.value, '50')" required />
                                                        </td>
                                                    </tr>
                                                @php
                                                $i++;
                                                @endphp
                                                @endforeach
                                            @endif
                                            <input type="hidden" name="nTtlRec" id="nTtlRec" value="{{ old('nTtlRec',count($aCntctDtl)) }}">
                                        @else
                                            <tr id="Row_1">
                                                <td><i class="fa fa-minus" onclick="DeleteRow(1)"></i></td>
                                                <td>
                                                    <select class="form-control @error('nCntctRole1') is-invalid @enderror" name="nCntctRole1" required>
                                                        <option value="">Select Role</option>
                                                        @foreach(config('constant.SCHL_ROLE') as $sRoleName => $nCntctRole)
                                                            <option {{ old('nCntctRole1') == $nCntctRole ? 'selected' : ''}} value="{{$nCntctRole}}">{{$sRoleName}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control @error('nCntctTitle1') is-invalid @enderror" name="nCntctTitle1" required>
                                                        <option value="">Select Title</option>
                                                        @foreach(config('constant.TITLE') as $sTitleName => $nTtlNo)
                                                            <option {{ old('nCntctTitle1') == $nTtlNo ? 'selected' : ''}} value="{{$nTtlNo}}">{{$sTitleName}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="@error('sFrstName1') is-invalid @enderror" name="sFrstName1" value="{{ old('sFrstName1') }}" onkeypress="return IsAlpha(event, this.value, '15')" required />
                                                </td>
                                                <td>
                                                    <input type="text" class="@error('sLstName1') is-invalid @enderror" name="sLstName1" value="{{ old('sLstName1') }}" onkeypress="return IsAlpha(event, this.value, '15')" required />
                                                </td>
                                                <td>
                                                    <input type="text" class="@error('sPhoneNo1') is-invalid @enderror" name="sPhoneNo1" id="sPhoneNo1" value="{{ old('sPhoneNo1') }}"  onkeypress="return IsPhone(event, this.value, '9', 1)" required />
                                                </td>
                                                <td>
                                                    <input type="text" class="@error('sMobileNo1') is-invalid @enderror" name="sMobileNo1" id="sMobileNo1" value="{{ old('sMobileNo1') }}" onkeypress="return IsMobile(event, this.value, '11', 1)" required />
                                                </td>
                                                <td>
                                                    <input type="email" class="@error('sEmailId1') is-invalid @enderror" name="sEmailId1" value="{{ old('sEmailId1') }}" onkeypress="return LenCheck(event, this.value, '50')" required />
                                                </td>
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
    var rowCount = $('#CntctGrid tr').length;
    if(rowCount == 3)
    {
        alert("Maximum 3 conatct allowed...");
    }
    else
    {
        total = $("#nTtlRec").val();
        next_no = parseInt(total)+1;
        newdiv = document.createElement('tr');
        divid = "Row_"+next_no;
        newdiv.setAttribute('id', divid);
        content = '';
        content +='<tr id="Row_'+next_no+'">';
        content +='<td><i class="fa fa-minus" onclick="DeleteRow('+next_no+')"></i></td>';
        content +='<td><select class="form-control" name="nCntctRole'+next_no+'"><option value="">Select Role</option>@foreach(config("constant.SCHL_ROLE") as $sRoleName => $nCntctRole)<option value="{{$nCntctRole}}">{{$sRoleName}}</option>@endforeach</select></td>';
        content +='<td><select class="form-control" name="nCntctTitle'+next_no+'"><option value="">Select Title</option>@foreach(config("constant.TITLE") as $sTitleName => $nTtlNo)<option value="{{$nTtlNo}}">{{$sTitleName}}</option>@endforeach</select></td>';
        content +='<td><input type="text" name="sFrstName'+next_no+'" value="" onkeypress="return IsAlpha(event, this.value, 15)" required /></td>';
        content +='<td><input type="text" name="sLstName'+next_no+'" value="" onkeypress="return IsAlpha(event, this.value, 15)" required /></td>';
        content +='<td><input type="text" name="sPhoneNo'+next_no+'" id="sPhoneNo'+next_no+'" value="" onkeypress="return IsPhone(event, this.value, 9, '+next_no+')" /></td>';
        content +='<td><input type="text" name="sMobileNo'+next_no+'" id="sMobileNo'+next_no+'" value="" onkeypress="return IsMobile(event, this.value, 11, '+next_no+')" /></td>';
        content +='<td><input type="email" name="sEmailId'+next_no+'" value="" onkeypress="return LenCheck(event, this.value, 50)" required /></td>';
        content +='</tr>';
        newdiv.innerHTML = content;
        $("#nTtlRec").val(next_no);
        $("#CntctGrid").last().append(newdiv);
    }
}

function DeleteRow(nRow)
{
    var rowCount = $('#CntctGrid tr').length; 
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
</script>