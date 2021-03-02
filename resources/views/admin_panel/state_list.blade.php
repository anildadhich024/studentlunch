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
                            <h4 class="page-title">Manage State / Town</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section">
                    <form action="{{url('admin_panel/state/list')}}" method="get"> 
                        <div class="row first-block parent-list-form">
                            <div class='col-sm-6 col-md-3 col-6 pb-3'>
                                <label>State / Town Name</label>
                                <input type="text" name="sStateName" placeholder="State / Town Name" value="{{$request['sStateName']}}" onkeypress="return IsAlpha(event, this.value, '30')">
                            </div>
                            <div class='col-sm-12 col-12 col-md-6   form-btns pb-3  pl15media767 pl-auto' style=" padding-left: 15px;">
                                <div class="row justify-content-between">
                                    <div class="col-auto">
                                        <ul>
                                            <li class="pb-2"><button type="submit" title="Filter" class="  autowidthbtn15">Filter</button></li>
                                            <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="  autowidthbtn15">Clear Filter</button></li>
                                            </li> 
                                            <li>
                                                <button title="Add Country" type="button" class="autowidthbtn mt-auto autowidthbtn15" onclick="GetModal()">Add New State / Town</button>
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
                            <table style="width:100%" class="tablescroll">
                            <!-- tablescroll936 -->
                                <tr>
                                    <th class="nowordwrap">Country Name</th>
                                    <th class="nowordwrap">State / Town Name</th>
                                    <th class="nowordwrap">Area Code</th>
                                    <th class="nowordwrap">Tax Rate (%)</th>
                                    <th class="nowordwrap">Rego Date & Time</th>
                                    <th class="nowordwrap">Status</th>
                                    <th class="nowordwrap">Action</th>
                                </tr>
                                @if(count($oStateLst) > 0)
                                    @foreach($oStateLst As $aRec)
                                    <tr>
                                        <td>{{$aRec->sCntry_Name}}</td>
                                        <td>{{$aRec->sState_Name}}</td>
                                        <td>{{$aRec->nArea_Code}}</td>
                                        <td align="right" style="padding-right: 65px;">{{number_format($aRec->dTax_Per, 2)}}</td>
                                        <td>{{date('d M, Y h:i A', strtotime($aRec->sCrt_DtTm))}}</td>
                                        <td>
                                            @if($aRec->nBlk_UnBlk == config('constant.STATUS.UNBLOCK'))
                                                <button class="active-btn" title="Active" onclick="chngStatus('{{base64_encode('mst_state')}}','{{base64_encode('lState_IdNo')}}','{{base64_encode($aRec->lState_IdNo)}}','{{base64_encode(config('constant.STATUS.BLOCK'))}}')">Active</button>
                                            @else
                                                <button class="block-btn" title="In-Active" onclick="chngStatus('{{base64_encode('mst_state')}}','{{base64_encode('lState_IdNo')}}','{{base64_encode($aRec->lState_IdNo)}}','{{base64_encode(config('constant.STATUS.UNBLOCK'))}}')">In-Active</button>
                                            @endif
                                        </td>
                                        <td class="action-btns">
                                            <ul>
                                                <li><i class="fa fa-edit" onclick="GetModal('{{$aRec}}')"></i></li>
                                                <li><i class="fa fa-trash" onclick="DelRec('{{base64_encode('mst_state')}}','{{base64_encode('lState_IdNo')}}','{{base64_encode($aRec->lState_IdNo)}}')" title="Delete {{$aRec->sState_Name}}"></i></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="6" class="text-center"><strong>No Record(s) Found</strong></td></tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="row pull-right">
                        <div class="col-sm-12 col-lg-12" style="padding-right: 0px;">
                            {{$oStateLst->appends($request->all())->render()}}
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
        $('.modal-header h4').html('Edit State / Town');
        $("#StateModal input[name='lStateIdNo']").val(btoa(aRec['lState_IdNo']));
        $('#StateModal select[name="lCntryIdNo"] option[value='+aRec['lCntry_IdNo']+']').attr('selected','selected');
        $("#StateModal input[name='sStateName']").val(aRec['sState_Name']);
        $("#StateModal input[name='dTaxPer']").val(aRec['dTax_Per']);
        $("#StateModal input[name='nAreaCode']").val(aRec['nArea_Code']);
    }
    else
    {
        $('.form-control').val('');
        $('.modal-header h4').html('Add State / Town');
    }
    $('#StateModal').modal('show');
}
</script>
<div class="modal fade" id="StateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('admin_panel/state/save')}}" method="post" id="general_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="lStateIdNo" id="lStateIdNo" value="{{ base64_encode(0) }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Add Country</h4>
                </div>
                <div class="modal-body card-commission-section">
                    <form>
                        <div class="row account-form">
                            <div class="col">
                                <label>Country Name</label>
                                <select class="form-control" name="lCntryIdNo" required autofocus="on" tabindex="1">
                                    <option value="">== Select Country ==</option>
                                    @foreach($aCntryLst as $aRec)
                                        <option value="{{$aRec['lCntry_IdNo']}}">{{$aRec['sCntry_Name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>State / Town Name</label>
                                <input type="text" name="sStateName" class="form-control" onkeypress="return IsAlpha(event, this.value, '30')" required tabindex="2" min="5" max="30">
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Area Code</label>
                                <input type="text" name="nAreaCode" class="form-control" onkeypress="return IsNumber(event, this.value, '1')" required tabindex="3" min="1" max="5">
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Tax Rate (%)</label>
                                <input type="text" name="dTaxPer" class="form-control" onkeypress="return IsNumber(event, this.value, '5')" required tabindex="4" min="1" max="5">
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