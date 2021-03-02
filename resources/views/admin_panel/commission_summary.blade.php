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
                            <h4 class="page-title">Manage Commission</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section">
                    <form action="{{url('admin_panel/manage_commission')}}" method="get" id="commission_form">
                        <div class="row first-block">
                            <div class='col-sm-6 col-md-2 col-6 pb-3 from-boxes'>
                                <label>From (Transaction)</label>
                                <input type="date" name="sFrmDate" placeholder="MM/DD/YYYY" value="{{$request['sFrmDate']}}"> 
                            </div>
                            <div class='col-sm-6 col-md-2 col-6 pb-3 from-boxes'>
                                <label>To (Transaction)</label>
                                <input type="date" name="sToDate" placeholder="MM/DD/YYYY" value="{{$request['sToDate']}}"> 
                            </div>
                            <div class='col-sm-6 col-md-3 col-6 pb-3 from-boxes'>
                                <label>Service Provider Name</label>
                                <select name="lMilkIdNo" class="form-control">
                                    <option value="">All Service Provider</option>
									@foreach($aMilkLst as $aRec)
										<option {{ $request['lMilkIdNo'] == $aRec['lMilk_IdNo'] ? 'selected' : ''}} value="{{$aRec['lMilk_IdNo']}}">{{$aRec['sBuss_Name']}}</option>
									@endforeach
                                </select>
                            </div>
                            <div class='col-sm-6 col-md-5 col-12 pb-3 form-btns'>
                                <ul>
                                    <li class="pb-2"><button id="Filter" title="Filter" alt="Filter" class="  autowidthbtn15">Filter</button></li>
                                    <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter">Clear Filter</button></li>
                                    <li><button type="button" class="mr-0 autowidthbtn15" id="ExprtRcrd" title="Export To Excel" alt="Export To Excel"  >Export To Excel</button></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%" class="tablescroll">
                                <tr>
                                    <th>Service Provider Name</th>
									<th class="text-right">Order Count</th>
                                    <th class="text-right">Sale Amount</th>
                                    <th class="text-right">Credit Applied</th>
                                    <th class="text-right">Processed Payment</th>
                                    <th class="text-right">Commission</th>
                                    <th class="text-center">Action</th>
								</tr>
								@if(count($oComLst) > 0)
                                    @foreach($oComLst As $aRec)
                                        @php
                                        $oGetWlt = \App\Model\Wallet::Select(DB::raw('SUM(sTtl_Amo) as nTtlWlt'))
                                        ->where(function($query) use ($sFrmDate, $sToDate) {
                                            if (isset($sFrmDate) && !empty($sFrmDate) && !empty($sToDate)) {
                                                $query->whereBetween('mst_wallet.sCrt_DtTm', array($sFrmDate,$sToDate));
                                            }
                                        })->where('lMilk_IdNo', $aRec->lMilk_IdNo)->Where('nTyp_Status',config('constant.TRANS.Debit'))->first();
                                        @endphp
										<tr>
                                            <td>{{$aRec->sBuss_Name}}</td>
                                            <td class="text-right">{{ $aRec->{'nTtlOrd'} }}</td>
                                            <td class="text-right">$ {{ number_format($aRec->{'sTtlAmt'}, 2) }}</td>
                                            <td class="text-right">$ {{ number_format($oGetWlt['nTtlWlt'], 2) }}</td>
                                            <td class="text-right">$ {{ number_format($aRec->{'sTtlAmt'} - $oGetWlt['nTtlWlt'], 2) }}</td>
                                            <td class="text-right">$ {{ number_format($aRec->{'sTtlCom'}, 2)}}</td>
											<td class="action-btns text-center">
											    <ul>
													<li class="detail_btn my-order-btns"><a title="View" onclick="CommDtl('{{$request['sFrmDate']}}','{{$request['sToDate']}}','{{$aRec->lMilk_IdNo}}')"> View</a></li>
												</ul>
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
                            {{$oComLst->appends($request->all())->render()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript">
$('#Filter').on('click', function() {
    var sFrmDate = $("input[name=sFrmDate]").val();
    var sToDate = $("input[name=sToDate]").val();
    if(sFrmDate != '' && sFrmDate > sToDate)
    {
        alert('To Date should be greater then From Date');
        return false;
    }
    else
    {
        $('#commission_form').submit();
    }
});

$('#ExprtRcrd').on('click', function() {
    var sFrmDate       = $("input[name=sFrmDate]").val();
    var sToDate       = $("input[name=sToDate]").val();
    if(sFrmDate != '' && sFrmDate > sToDate)
    {
        alert('To Date should be greater then From Date');
        return false;
    }
    else
    {
        var lMilkIdNo       = $("select[name=lMilkIdNo]").find(":selected").val();
        var lMilkIdNo       = lMilkIdNo == 'undefined' ? '' : lMilkIdNo;
        window.location=APP_URL+"/admin_panel/manage_commission/export?sFrmDate="+sFrmDate+"&sToDate="+sToDate+"&lMilkIdNo="+lMilkIdNo;
    }
});


function CommDtl(sFrmDate = '', sToDate = '', lMilkIdNo)
{
    window.location=APP_URL+"/admin_panel/manage_commission_list?sFrmDate="+sFrmDate+"&sToDate="+sToDate+"&lMilkIdNo="+lMilkIdNo
}
</script>
