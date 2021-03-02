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
                            <h4 class="page-title">Manage Service Provider</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section">
                    <form action="{{url('admin_panel/milk_bar/list')}}" method="get"> 
                        <div class="row first-block parent-list-form">
                            <div class='col-sm-6 col-md-3 col-6 pb-3'>
                                <label>Service Provider Name</label>
                                <input type="text" name="sBussName" placeholder="Service Provider Name" value="{{$request['sBussName']}}" onkeypress="return IsAlpha(event, this.value, '30')">
                            </div>
                            <div class='col-sm-6 col-md-3 col-6 pb-3'>
                                <label>Mobile Number</label>
                                <input type="text" name="sMobileNo" placeholder="Mobile Number" value="{{$request['sMobileNo']}}" onkeypress="return IsNumber(event, this.value, '10')">
                            </div>
                            <div class='col-sm-12 col-12 col-md-6   form-btns pb-3  pl15media767 pl-auto' style=" padding-left: 15px;">
                                <div class="row justify-content-between">
                                    <div class="col-auto">
                                        <ul>
                                            <li class="pb-2"><button type="submit" title="Filter" class="  autowidthbtn15">Filter</button></li>
                                            <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="  autowidthbtn15">Clear Filter</button></li>
                                            <li ><button class="mr-0" type="button" title="Export To Excel" id="ExprtRcrd" class="  autowidthbtn15">Export To Excel</button>
                                            </li> 
                                        </ul>
                                    </div>
                                    <div class="col-auto">
                                        <div class="add-btn" >
                                            <a href="{{url('admin_panel/milk_bar/manage')}}"> 
                                                <button title="Add Service Provider" type="button" class="autowidthbtn mt-auto autowidthbtn15">Add New</button>
                                            </a>
                                        </div>
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
                                    <th class="nowordwrap">Account ID</th>
                                    <th class="nowordwrap">Seller Name</th>
                                    <th class="nowordwrap">Seller Type</th>
                                    <th class="nowordwrap">State</th>
                                    <th class="nowordwrap">Country</th>
                                    <th class="nowordwrap">Rego Date & Time</th>
                                    <th class="nowordwrap">Admin Status</th>
                                    <th class="nowordwrap">Status</th>
                                    <th class="nowordwrap">Action</th>
                                </tr>
                                @if(count($aMlkBarLst) > 0)
                                    @foreach($aMlkBarLst As $aRec)
                                    <tr>
                                        <td>{{$aRec->sAcc_Id}}</td>
                                        <td>{{$aRec->sBuss_Name}}</td>
                                        <td>{{array_search($aRec->nBuss_Type, config('constant.BUSS_TYPE'))}}</td>
                                        <td>{{$aRec->sState_Name}}</td>
                                        <td>{{$aRec->sCntry_Name}}</td>
                                        <td>{{date('d M, Y h:i A', strtotime($aRec->sCrt_DtTm))}}</td>
                                        <td>
                                            @if($aRec->nAdmin_Status == config('constant.MLK_STATUS.UNACTIVE'))
                                                <button class="block-btn" title="Offline" onclick="ActvStatus('{{base64_encode($aRec->lMilk_IdNo)}}')">Offline</button>
                                            @else
                                                <button class="active-btn" title="Live">Live</button>
                                            @endif
                                        </td>
                                        <td>
                                            @if($aRec->nBlk_UnBlk == config('constant.STATUS.UNBLOCK'))
                                                <button class="active-btn" title="Active" onclick="chngStatus('{{base64_encode('mst_milk_bar')}}','{{base64_encode('lMilk_IdNo')}}','{{base64_encode($aRec->lMilk_IdNo)}}','{{base64_encode(config('constant.STATUS.BLOCK'))}}')">Active</button>
                                            @else
                                                <button class="block-btn" title="In-Active" onclick="chngStatus('{{base64_encode('mst_milk_bar')}}','{{base64_encode('lMilk_IdNo')}}','{{base64_encode($aRec->lMilk_IdNo)}}','{{base64_encode(config('constant.STATUS.UNBLOCK'))}}')">In-Active</button>
                                            @endif
                                        </td>
                                        <td class="action-btns">
                                            <ul>
                                                <li><a href="{{url('admin_panel/milk_bar/detail')}}?lRecIdNo={{base64_encode($aRec->lMilk_IdNo)}}" title="View {{$aRec->sBuss_Name}}"> <i class="fa fa-eye"></i></a></li>
                                                <li><a href="{{url('admin_panel/milk_bar/manage')}}?lRecIdNo={{base64_encode($aRec->lMilk_IdNo)}}" title="Edit {{$aRec->sBuss_Name}}"> <i class="fa fa-edit"></i></a></li>
                                                <li><i class="fa fa-trash" onclick="DelRec('{{base64_encode('mst_milk_bar')}}','{{base64_encode('lMilk_IdNo')}}','{{base64_encode($aRec->lMilk_IdNo)}}')" title="Delete {{$aRec->sBuss_Name}}"></i></li>
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
                            {{$aMlkBarLst->appends($request->all())->render()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript">
$('#ExprtRcrd').on('click', function() {
    var sBussName = $("input[name=sBussName]").val();
    var sMobileNo = $("input[name=sMobileNo]").val();
    window.location=APP_URL+"/admin_panel/milk_bar/export?sBussName="+sBussName+"&sMobileNo="+sMobileNo;
});
</script>