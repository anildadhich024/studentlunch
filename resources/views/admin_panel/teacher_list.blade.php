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
                            <h4 class="page-title">Teacher List </h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section ">
                    <form action="{{url('admin_panel/teacher/list')}}" method="get">
                        <div class="row first-block parent-list-form">
                            <div class="col-sm-6 col-md-3 col-6 pb-3">
                                <label>Teacher Name</label>
                                <input type="text" name="sTchrName" placeholder="Teacher Name" value="{{$request['sTchrName']}}" onkeypress="return IsAlpha(event, this.value, '30')">
                            </div>
                            <div class="col-sm-6 col-md-3 col-6 pb-3">
                                <label>Mobile Number</label>
                                <input type="text" name="sMobileNo"  placeholder="Mobile Number" value="{{$request['sMobileNo']}}" onkeypress="return IsNumber(event, this.value, '10')">
                            </div>
                            <div class='col-sm-12 col-md-6 col-12 form-btns pl15media767'>
                                <ul>
                                    <li class="pb-2"><button title="Filter">Filter</button></li>
                                    <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter">Clear Filter</button></li>
                                    <li><button class="mr-0" type="button" title="Export To Excel" id="ExprtRcrd">Export To Excel</button></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%" class="tablescroll">
                                <tr>
                                    <th class="nowordwrap">Account ID</th>
                                    <th class="nowordwrap">Teacher Name</th>
                                    <th class="nowordwrap">No. of School</th>
                                    <th class="nowordwrap">Credit Available</th>
                                    <th class="nowordwrap">State</th>
                                    <th class="nowordwrap">Country</th>
                                    <th class="nowordwrap">Rego Date & Time</th>
                                    <th class="nowordwrap">Status</th>
                                    <th class="nowordwrap">Action</th>
                                </tr>
                                @if(count($aTchrLst) > 0)
                                    @foreach($aTchrLst As $aRec)
                                    @php
                                    $TeacherSchool  = new \App\Model\TeacherSchool;
                                    $Wallet         = new \App\Model\Wallet;
                                    $aCntSchl       = $TeacherSchool->CntSchl($aRec->lTchr_IdNo);
                                    @endphp
                                    <tr>
                                        <td>{{$aRec->sAcc_Id}}</td>
                                        <td>{{$aRec->sFrst_Name}} {{$aRec->sLst_Name}}</td>
                                        <td align="center">{{ $aCntSchl['nTtlRec'] }}</td>
                                        <td align="center">$ 0.00</td>
                                        <td>{{$aRec->sState_Name}}</td>
                                        <td>{{$aRec->sCntry_Name}}</td>
                                        <td>{{date('d M, Y h:i A', strtotime($aRec->sCrt_DtTm))}}</td>
                                        <td>
                                            @if($aRec->nBlk_UnBlk == config('constant.STATUS.UNBLOCK'))
                                                <button class="active-btn" title="Active" onclick="chngStatus('{{base64_encode('mst_tchr')}}','{{base64_encode('lTchr_IdNo')}}','{{base64_encode($aRec->lTchr_IdNo)}}','{{base64_encode(config('constant.STATUS.BLOCK'))}}')">Active</button>
                                            @else
                                                <button class="block-btn" title="In-Active" onclick="chngStatus('{{base64_encode('mst_tchr')}}','{{base64_encode('lTchr_IdNo')}}','{{base64_encode($aRec->lTchr_IdNo)}}','{{base64_encode(config('constant.STATUS.UNBLOCK'))}}')">In-Active</button>
                                            @endif
                                        </td>
                                        <td class="detail_btn"><a href="{{url('admin_panel/teacher/detail')}}?lRecIdNo={{base64_encode($aRec->lTchr_IdNo)}}" title="{{$aRec->sFrst_Name}} {{$aRec->sLst_Name}} Details">Get Details</a></td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="9" class="text-center"><strong>No Record(s) Found</strong></td></tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="row pull-right">
                        <div class="col-sm-12 col-lg-12" style="padding-right: 0px;">
                            {{$aTchrLst->appends($request->all())->render()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript">
$('#ExprtRcrd').on('click', function() {
    var sTchrName = $("input[name=sTchrName]").val();
    var sMobileNo = $("input[name=sMobileNo]").val();
    window.location=APP_URL+"/admin_panel/teacher/export?sTchrName="+sTchrName+"&sMobileNo="+sMobileNo;
});
</script>