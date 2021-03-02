@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('admin_panel.layouts.side_panel')
            <main>
                <div class="page-breadcrumb">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="page-title">Manage Plan</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section">
                    <form action="{{url('admin_panel/country/list')}}" method="get"> 
                        <div class="row first-block parent-list-form">
                            <div class='col-sm-6 col-md-3 col-6 pb-3'>
                                <label>Country Name</label>
                                <input type="text" name="sCntryName" placeholder="Country Name" value="{{$request['sCntryName']}}" onkeypress="return IsAlpha(event, this.value, '30')">
                            </div>
                            <div class='col-sm-12 col-12 col-md-6   form-btns pb-3  pl15media767 pl-auto' style=" padding-left: 15px;">
                                <div class="row justify-content-between">
                                    <div class="col-auto">
                                        <ul>
                                            <li class="pb-2"><button type="button" title="Filter" class="  autowidthbtn15">Filter</button></li>
                                            <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="  autowidthbtn15">Clear Filter</button></li>
                                            </li> 
                                            <li>
                                                <button title="Add Country" type="button" class="autowidthbtn mt-auto autowidthbtn15" onclick="GetModal()">Add New Plan</button>
                                            </li> 
                                        </ul>
                                    </div>
                                </div> 
                            </div>  
                        </div>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%" class=" tablescroll">
                            <!-- tablescroll936 -->
                                <tr>
                                    <th class="nowordwrap">Country Name</th>
                                    <th class="nowordwrap">State Name</th>
                                    <th class="nowordwrap">Admin Charge (%)</th>
                                    <th class="nowordwrap">Subscription</th>
                                    <th class="nowordwrap">Cancel (%)</th>
                                    <th class="nowordwrap">Start Date</th>
                                    <th class="nowordwrap">Status</th>
                                    <th class="nowordwrap">Rego Date & Time</th>
                                    <th class="nowordwrap">Action</th>
                                </tr>
                                @if(count($aPlanLst) > 0)
                                    @foreach($aPlanLst As $aRec)
                                    <tr>
                                        <td>{{$aRec->sCntry_Name}}</td>
                                        <td>{{$aRec->sState_Name}}</td>
                                        <td align="right" style="padding-right: 15px;">{{number_format($aRec->dCom_Per, 2)}}</td>
                                        <td align="right" style="padding-right: 15px;">$ {{number_format($aRec->sPrnt_Amo, 2)}}</td>
                                        <td align="right" style="padding-right: 15px;">{{number_format($aRec->dCacl_Per, 2)}}</td>
                                        <td>{{date('d M, Y', strtotime($aRec->sStrt_Dt))}}</td>
                                        <td>
                                            @if($aRec->nAply_Status == config('constant.PLN_STATUS.NON_ACTIVE'))
                                                <button class="block-btn" title="Offline" onclick="ActvStatus('{{base64_encode($aRec->lCommPln_IdNo)}}')">In-Active</button>
                                            @else
                                                <button class="active-btn" title="Live">Active</button>
                                            @endif
                                        </td>
                                        <td>{{date('d M, Y h:i A', strtotime($aRec->sCrt_DtTm))}}</td>
                                        <td class="action-btns">
                                            @if($aRec->nAply_Status == config('constant.PLN_STATUS.NON_ACTIVE'))
                                            <ul>
                                                <li><i class="fa fa-edit" onclick="GetModal('{{$aRec}}')"></i></li>
                                                <li><i class="fa fa-trash" onclick="DelRec('{{base64_encode('mst_comm_pln')}}','{{base64_encode('lCommPln_IdNo')}}','{{base64_encode($aRec->lCommPln_IdNo)}}')" title="Delete"></i></li>
                                            </ul>
                                            @else
                                                =======
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="8" class="text-center"><strong>No Record(s) Found</strong></td></tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="row pull-right">
                        <div class="col-sm-12 col-lg-12" style="padding-right: 0px;">
                            {{$aPlanLst->appends($request->all())->render()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript">
function GetModal(aRec = '')
{
    if(aRec != '')
    {
        aRec = JSON.parse(aRec);
        $('.modal-header h4').html('Edit Plan');
        $.ajax({
            url: APP_URL + "/get_state?lCntryIdNo=" + btoa(aRec['lCntry_IdNo']),
            success: function (response) {
                $('#lStateIdNo').find('option').remove();
                $('#lStateIdNo').append(`<option value="">== Select State ==</option>`);
                StateList = JSON.parse(response);
                StateList.forEach(function (StateList) {
                    var lStateIdNo = StateList['lState_IdNo'];
                    var sStateName = StateList['sState_Name'];
                    $('#lStateIdNo').append(`<option value="${lStateIdNo}">${sStateName}</option>`);
                });
                $('#PlanModel select[name="lStateIdNo"] option[value='+aRec['lState_IdNo']+']').attr('selected','selected');
            }
        });
        $("#PlanModel input[name='lCommPlnIdNo']").val(btoa(aRec['lCommPln_IdNo']));
        $('#PlanModel select[name="lCntryIdNo"] option[value='+aRec['lCntry_IdNo']+']').attr('selected','selected');
        $("#PlanModel input[name='dComPer']").val(aRec['dCom_Per']);
        $("#PlanModel input[name='dCaclPer']").val(aRec['dCacl_Per']);
        $("#PlanModel input[name='sPrntAmo']").val(aRec['sPrnt_Amo']);
        $("#PlanModel input[name='sStrtDt']").val(aRec['sStrt_Dt']);
    }
    else
    {
        $('.form-control').val('');
        $('.modal-header h4').html('Add Plan');
    }
    $('#PlanModel').modal('show');
}
</script>
<div class="modal fade" id="PlanModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('admin_panel/plan/save')}}" method="post" id="general_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="lCommPlnIdNo" value="{{ base64_encode(0) }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Add Plan</h4>
                </div>
                <div class="modal-body card-commission-section" style="margin-bottom: 0px !important;">
                    <form>
                        <div class="row account-form">
                            <div class="col">
                                <label>Country Name</label>
                                <select class="form-control" name="lCntryIdNo" required autofocus="on" tabindex="1" onchange="GetState(this.value)">
                                    <option value="">== Select Country ==</option>
                                    @foreach($aCntryLst as $aRec)
                                        <option value="{{$aRec['lCntry_IdNo']}}">{{$aRec['sCntry_Name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>State Name</label>
                                <select class="form-control" name="lStateIdNo" id="lStateIdNo" required tabindex="2">
                                    <option value="">== Select State ==</option>
                                </select>
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Commission (%)</label>
                                <input type="text" name="dComPer" id="dComPer" class="form-control" onkeypress="return isNumberKey(event)" onchange="CommPer(this.value)" required maxlength="5" tabindex="3" >
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Cancel Fee (%)</label>
                                <input type="text" name="dCaclPer" id="dCaclPer" class="form-control" onkeypress="return isNumberKey(event)" maxlength="5" required tabindex="4" >
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Parent Monthly Fee</label>
                                <input type="text" name="sPrntAmo" id="sPrntAmo" class="form-control" onkeypress="return isNumberKey(event)" onchange="PlnAmo()" maxlength="6" required tabindex="5">
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                @php
                                $sMinDate = date('Y-m-d', strtotime('+30 days'));
                                @endphp
                                <label>Active Date</label>
                                <input type="date" min={{$sMinDate}} id="sStrtDt" name="sStrtDt" class="form-control" placeholder="MM/DD/YYYY" required tabindex="6">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-lg-12 services-btns">
                            <ul class="m-auto text-center">
                                <li>
                                    <div class="add-btn  mt-0"><button class="mt-0 btnhover" tabindex="6" data-dismiss="modal" aria-label="Close">Cancel</button></div>
                                </li>
                                <li>
                                    <div class="add-btn  mt-0"><button title="Save Item" type="submit" class="mt-0 btnhover" tabindex="7">Save</button></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
$("#sStrtDt").keydown(function(event) { 
    return false;
});
function CommPer(numb) 
{
    if (numb <= 23 && numb > 0) 
    {
        var zz = parseFloat(numb);
        var zzz = zz.toFixed(2);
        document.getElementById('dComPer').value = zzz;
    }
    else 
    {
        $('#dComPer').val('23.00');
        document.getElementById('dComPer').focus();
        alert('Please put the value bettween 0.01 to 23.00');
    }
}

function PlnAmo() 
{
    var numb = document.getElementById('sPrntAmo').value;
    var zz = parseFloat(numb) || 0;
    var zzz = zz.toFixed(2);
    document.getElementById('sPrntAmo').value = zzz;
}
</script>