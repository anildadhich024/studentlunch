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
                            <h4 class="page-title">Parent List </h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section ">
                    <form action="{{url('admin_panel/parent/list')}}" method="get">
                        <div class="row first-block parent-list-form">
                            <div class="col-sm-6 col-md-3 col-6 pb-3">
                                <label>Parent Name</label>
                                <input type="text" name="sPrntName" placeholder="Parent Name" value="{{$request['sPrntName']}}" onkeypress="return IsAlpha(event, this.value, '30')">
                            </div>
                            <div class="col-sm-6 col-md-3 col-6 pb-3">
                                <label>Parent Mobile Number</label>
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
                                    <th class="nowordwrap">Parent Name</th>
                                    <th class="nowordwrap">No. of Child</th>
                                    <th class="nowordwrap">Credit Available</th>
                                    <th class="nowordwrap">State</th>
                                    <th class="nowordwrap">Country</th>
                                    <th class="nowordwrap">Rego Date & Time</th>
                                    <th class="nowordwrap">Status</th>
                                    <th class="nowordwrap">Action</th>
                                </tr>
                                @if(count($aPrntsLst) > 0)
                                    @foreach($aPrntsLst As $aRec)
                                    @php
                                    $Child          = new \App\Model\Child;
                                    $Wallet         = new \App\Model\Wallet;
                                    $aCntChld       = $Child->CntChld($aRec->lPrnt_IdNo);
                                    $nTtlCrdt       = $Wallet->PrntCrdt($aRec->lPrnt_IdNo, config('constant.TRANS.Credit'));
                                    $nUsedCrdt      = $Wallet->PrntCrdt($aRec->lPrnt_IdNo, config('constant.TRANS.Debit'));
                                    @endphp
                                    <tr>
                                        <td>{{$aRec->sAcc_Id}}</td>
                                        <td>{{$aRec->sFrst_Name}} {{$aRec->sLst_Name}}</td>
                                        <td align="center">{{ $aCntChld['nTtlRec'] }}</td>
                                        <td align="center">$ {{ number_format($nTtlCrdt['sTtlAmo'] - $nUsedCrdt['sTtlAmo'], 2) }}</td>
                                        <td>{{$aRec->sState_Name}}</td>
                                        <td>{{$aRec->sCntry_Name}}</td>
                                        <td>{{date('d M, Y h:i A', strtotime($aRec->sCrt_DtTm))}}</td>
                                        <td>
                                            @if($aRec->nBlk_UnBlk == config('constant.STATUS.UNBLOCK'))
                                                <button class="active-btn" title="Active" onclick="chngStatus('{{base64_encode('mst_prnts')}}','{{base64_encode('lPrnt_IdNo')}}','{{base64_encode($aRec->lPrnt_IdNo)}}','{{base64_encode(config('constant.STATUS.BLOCK'))}}')">Active</button>
                                            @else
                                                <button class="block-btn" title="In-Active" onclick="chngStatus('{{base64_encode('mst_prnts')}}','{{base64_encode('lPrnt_IdNo')}}','{{base64_encode($aRec->lPrnt_IdNo)}}','{{base64_encode(config('constant.STATUS.UNBLOCK'))}}')">In-Active</button>
                                            @endif
                                        </td>
                                        <td class="detail_btn"><a href="{{url('admin_panel/parent/detail')}}?lRecIdNo={{base64_encode($aRec->lPrnt_IdNo)}}" title="{{$aRec->sFrst_Name}} {{$aRec->sLst_Name}} Details">Get Details</a></td>
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
                            {{$aPrntsLst->appends($request->all())->render()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript">
$('#ExprtRcrd').on('click', function() {
    var sPrntName = $("input[name=sPrntName]").val();
    var sMobileNo = $("input[name=sMobileNo]").val();
    window.location=APP_URL+"/admin_panel/parent/export?sPrntName="+sPrntName+"&sMobileNo="+sMobileNo;
});
</script>