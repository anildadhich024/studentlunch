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
                            <h4 class="page-title">Manage Orders</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section">
                    <form action="{{url('admin_panel/manage_order')}}" method="get" id="order_form">
                        <div class="row first-block">
                            <div class='col-sm-6 col-md-3 col-6 pb-3 from-boxes'>
                                <label>From Date (Delivery)</label>
                                <input type="date" name="sFrmDate" placeholder="MM/DD/YYYY" value="{{$request['sFrmDate']}}"> 
                            </div>
                            <div class='col-sm-6 col-md-3 col-6 pb-3 from-boxes'>
                                <label>To Date (Delivery)</label>
                                <input type="date" name="sToDate" placeholder="MM/DD/YYYY" value="{{$request['sToDate']}}"> 
                            </div>
                            <div class='col-sm-6 col-md-4 col-6 pb-3 from-boxes'>
                                <label>School Name</label>
                                <select class="form-control" name="lSchlIdNo">
                                    <option value="">All School</option>
									@foreach($aSchlLst as $aRec)
										<option {{ $request['lSchlIdNo'] == $aRec['lSchl_IdNo'] ? 'selected' : ''}} value="{{$aRec['lSchl_IdNo']}}">{{$aRec['sSchl_Name']}}</option>
									@endforeach
                                </select>
                            </div>
                            <div class='col-sm-6 col-md-4 col-6 pb-3 from-boxes'>
                                <label>Service Provider</label>
                                <select class="form-control" name="lMilkIdNo">
                                    <option value="">All Service Provider</option>
									@foreach($aMilklLst as $aRec)
										<option {{ $request['lMilkIdNo'] == $aRec['lMilk_IdNo'] ? 'selected' : ''}} value="{{$aRec['lMilk_IdNo']}}">{{$aRec['sBuss_Name']}}</option>
									@endforeach
                                </select>
                            </div>
                            <div class='col-sm-12 col-md-4 col-12 pb-3 form-btns'>
                                <ul>
                                    <li><button id="Filter" title="Filter" alt="Filter"  class="  autowidthbtn15">Filter</button></li>
                                    <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter"  class="  autowidthbtn15">Clear Filter</button></li>
                                    <li><button type="button" class="mr-0" id="ExprtRcrd" title="Export To Excel" alt="Export To Excel"  class="  autowidthbtn15">Export To Excel</button></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%" class="tablescroll">
                                <tr>
                                    <th class="nowordwrap">Order No</th>
                                    <th class="nowordwrap">Order Type</th>
									<th class="nowordwrap">User Name</th>
                                    <th class="nowordwrap">Service Provider Name</th>
                                    <th class="nowordwrap">School Name</th>
									<th class="text-right nowordwrap">Amount</th>
									<th>Delivery Date</th>
									<th class="text-center nowordwrap">Status</th>
                                    <th class="text-center nowordwrap">Action</th>
								</tr>
								@if(count($oOrdLst) > 0)
                                    @foreach($oOrdLst As $aRec)
										<tr>
                                            @php
                                            if($aRec->nUser_Type == config('constant.USER.TEACHER'))
                                            {
                                                $sUserName = $aRec->sTchr_FName.' '.$aRec->sTchr_LName;
                                            }
                                            else if($aRec->nUser_Type == config('constant.USER.CHILD'))
                                            {
                                                $sUserName = $aRec->sChld_FName.' '.$aRec->sChld_LName;
                                            }
                                            else
                                            {
                                                $sUserName = $aRec->sPrnt_FName.' '.$aRec->sPrnt_LName;
                                            }
                                            @endphp
                                            <td>{{$aRec->sOrdr_Id}}</td>
                                            <td>{{array_search($aRec->nOrder_Type, config('constant.ORD_TYPE'))}}</td>
                                            <td>{{$sUserName}}</td>
                                            <td>{{$aRec->sBuss_Name}}</td>
                                            <td>{{$aRec->sSchl_Name}}</td>
                                            <td class="text-right">$ {{number_format($aRec->sGrnd_Ttl, 2)}}</td>
											<td>{{date('d M, Y', strtotime($aRec->sDelv_Date))}}</td>
											<td class="text-center">
                                                @if($aRec->sDelv_Date < date('Y-m-d') && $aRec->nOrdr_Status == config('constant.ORDER_STATUS.Pending'))
                                                    <button class="default-btn" title="Overdue">Overdue</button>
                                                @else
                                                    @if($aRec->nOrdr_Status == config('constant.ORDER_STATUS.Pending'))
                                                        <button class="primary-btn" title="Pending">Pending</button>
                                                    @elseif($aRec->nOrdr_Status == config('constant.ORDER_STATUS.Delivered'))
                                                        <button class="active-btn" title="Delivered">Delivered</button>
                                                    @else
                                                        <button class="block-btn" title="Cancelled">Cancelled</button>
                                                    @endif
                                                @endif
                                            </td>
											<td class="action-btns text-center">
											    <ul>
													<li class="detail_btn my-order-btns"><a title="View" onclick="GetOrder('{{base64_encode($aRec->sOrdr_Id)}}')"> View</a></li>
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
                            {{$oOrdLst->appends($request->all())->render()}}
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
        $('#order_form').submit();
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
        var sPrntName       = $("input[name=sPrntName]").val();
        var lMilkIdNo       = $("select[name=lMilkIdNo]").find(":selected").val();
        var lMilkIdNo       = lMilkIdNo == 'undefined' ? '' : lMilkIdNo;
        var lSchlIdNo       = $("select[name=lSchlIdNo]").find(":selected").val();
        var lSchlIdNo       = lSchlIdNo == 'undefined' ? '' : lSchlIdNo;
        window.location=APP_URL+"/admin_panel/manage_order/export?sFrmDate="+sFrmDate+"&sToDate="+sToDate+"&lSchlIdNo="+lSchlIdNo+"&lMilkIdNo="+lMilkIdNo;
    }
});

$('#OrderModel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var aRec = button.data('rec');
	GetOrder(aRec);
});
</script>
