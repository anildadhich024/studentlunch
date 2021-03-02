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
                            <h4 class="page-title">Manage Country</h4>
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
                                            <li class="pb-2"><button type="submit" title="Filter" class="  autowidthbtn15">Filter</button></li>
                                            <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="  autowidthbtn15">Clear Filter</button></li>
                                            </li> 
                                            <li>
                                                <button title="Add Country" type="button" class="autowidthbtn mt-auto autowidthbtn15" onclick="GetModal()">Add New Country</button>
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
                                    <th class="nowordwrap">Code</th>
                                    <th class="nowordwrap">Currency</th>
                                    <th class="nowordwrap">Symbol</th>
                                    <th class="nowordwrap">Tax Method</th>
                                    <th class="nowordwrap">Rego Date & Time</th>
                                    <th class="nowordwrap">Status</th>
                                    <th class="nowordwrap">Action</th>
                                </tr>
                                @if(count($oCntryLst) > 0)
                                    @foreach($oCntryLst As $aRec)
                                    <tr>
                                        <td>{{$aRec->sCntry_Name}}</td>
                                        <td>{{$aRec->sCntry_Code}}</td>
                                        <td>{{strtoupper($aRec->sCurr_Code)}}</td>
                                        <td>{{$aRec->sCurr_Symbl}}</td>
                                        <td>{{array_search($aRec->nTax_Mtdh, config('constant.TAX_MTHD'))}}</td>
                                        <td>{{date('d M, Y h:i A', strtotime($aRec->sCrt_DtTm))}}</td>
                                        <td>
                                            @if($aRec->nBlk_UnBlk == config('constant.STATUS.UNBLOCK'))
                                                <button class="active-btn" title="Active" onclick="chngStatus('{{base64_encode('mst_cntry')}}','{{base64_encode('lCntry_IdNo')}}','{{base64_encode($aRec->lCntry_IdNo)}}','{{base64_encode(config('constant.STATUS.BLOCK'))}}')">Active</button>
                                            @else
                                                <button class="block-btn" title="In-Active" onclick="chngStatus('{{base64_encode('mst_cntry')}}','{{base64_encode('lCntry_IdNo')}}','{{base64_encode($aRec->lCntry_IdNo)}}','{{base64_encode(config('constant.STATUS.UNBLOCK'))}}')">In-Active</button>
                                            @endif
                                        </td>
                                        <td class="action-btns">
                                            <ul>
                                                <li><i class="fa fa-edit" onclick="GetModal('{{$aRec}}')"></i></li>
                                                <li><i class="fa fa-trash" onclick="DelRec('{{base64_encode('mst_cntry')}}','{{base64_encode('lCntry_IdNo')}}','{{base64_encode($aRec->lCntry_IdNo)}}')" title="Delete {{$aRec->sCntry_Name}}"></i></li>
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
                            {{$oCntryLst->appends($request->all())->render()}}
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
        $('.modal-header h4').html('Edit Country');
        $("#CountryModel input[name='lCntryIdNo']").val(btoa(aRec['lCntry_IdNo']));
        $("#CountryModel input[name='sCntryName']").val(aRec['sCntry_Name']);
        $("#CountryModel input[name='sCntryCode']").val(aRec['sCntry_Code']);
        $('#CountryModel select[name="sCurrCode"] option[value='+aRec['sCurr_Code']+']').attr('selected','selected');
        $('#CountryModel select[name="nTaxMtdh"] option[value='+aRec['nTax_Mtdh']+']').attr('selected','selected');
        $("#CountryModel input[name='sCurrSymbl']").val(aRec['sCurr_Symbl']);
    }
    else
    {
        $('.form-control').val('');
        $('.modal-header h4').html('Add Country');
    }
    $('#CountryModel').modal('show');
}

function IsCntryCode(eEvnt, sValue, nLen)
{
    var nKeyCode = eEvnt.which || eEvnt.keyCode;
    if ((nKeyCode >= 48 && nKeyCode <= 57) || nKeyCode == 43) 
    {
        if(sValue.length < nLen) 
        {
            return true;
        }
        else 
        {
            return false;
        }
    }
    else 
    {
        return false;
    }   
}
</script>
<div class="modal fade" id="CountryModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('admin_panel/country/save')}}" method="post" id="general_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="lCntryIdNo" id="lCntryIdNo" value="{{ base64_encode(0) }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Add Country</h4>
                </div>
                <div class="modal-body card-commission-section">
                    <form>
                        <div class="row account-form">
                            <div class="col">
                                <label>Country Name</label>
                                <input type="text" name="sCntryName" class="form-control" onkeypress="return IsAlpha(event, this.value, '30')" required tabindex="1" min="5" max="30">
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Country Code</label>
                                <input type="text" name="sCntryCode" class="form-control" onkeypress="return IsCntryCode(event, this.value, '4')" required tabindex="2" min="2" max="4">
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Currency</label>
                                <select class="form-control" name="sCurrCode" required autofocus="on" tabindex="3">
                                    <option value="">== Select Currency ==</option>
                                    @foreach($aGetCurr as $sKey => $aVal)
                                        <option value="{{strtolower($sKey)}}">{{$aVal}} ({{$sKey}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Tax Method</label>
                                <select class="form-control" name="nTaxMtdh" required autofocus="on" tabindex="4">
                                    <option value="">== Select Tax Method ==</option>
                                    @foreach(config('constant.TAX_MTHD') as $sName => $nVal)
                                        <option value="{{$nVal}}">{{$sName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Currency Symbol</label>
                                <input type="text" name="sCurrSymbl" class="form-control" onkeypress="return LenCheck(event, this.value, '1')" required tabindex="5" min="1" max="1">
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