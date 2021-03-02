@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('teacher_panel.layouts.side_panel')
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
                    <form action="{{url('teacher_panel/manage_order')}}" method="get">
                        <div class="row first-block">
                            <div class='col-sm-6 col-md-3 col-6 pb-3 from-boxes'>
                                <label>Transaction Date</label>
                                <input type="date" name="sCrtDtTm" placeholder="MM/DD/YYYY" value="{{ $request['sCrtDtTm'] }}"> 
                            </div>
                            <div class='col-sm-6 col-md-3 col-6 pb-3 from-boxes'>
                                <label>Order Status</label>
                                <select name="nOrdrStatus" style="padding: 10px 12px;">
                                    <option value="">All Order</option>
                                    @foreach(config('constant.ORDER_STATUS') as $sStatusName => $nOrdrStatus)
                                        <option {{ $request['nOrdrStatus'] == $nOrdrStatus ? 'selected' : ''}} value="{{$nOrdrStatus}}">{{$sStatusName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='col-sm-12 col-md-6  col-12 form-btns pb-3 pl15media767'>
                                <ul>
                                    <li class="pb-2"><button type="submit" title="Filter" class="autowidthbtn15">Filter</button></li>
                                    <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="autowidthbtn15">Clear Filter</button></li>
                                    <li class="pb-2"><button type="button" class="mr-0 autowidthbtn15 " title="Export Order" id="ExprtRcrd">Export To Excel</button></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%"  class=" tablescroll">
                                <tr> 
                                    <th class="nowordwrap">Order ID</th>
									<th class="nowordwrap">Delivery Date</th>
                                    <th class="nowordwrap">Order Type</th>
                                    <th class="nowordwrap">OTP</th>
									<th class="nowordwrap">Service Provider Name</th>
									<th class="text-right nowordwrap">Price</th>
									<th class="text-center nowordwrap">Status</th>
									<th class="nowordwrap">Transaction Date</th>
									<th class="nowordwrap">Action</th>
                                </tr>
								@if(count($oOrderLst) > 0)
                                    @foreach($oOrderLst As $aRec)
										<tr>
											<td>{{$aRec->sOrdr_Id}}</td>
											<td>{{date('d M, Y', strtotime($aRec->sDelv_Date))}}</td>
											<td>{{array_search($aRec->nOrder_Type, config('constant.ORD_TYPE'))}}</td>
                                            <td>{{$aRec->nOrder_Type == config('constant.ORD_TYPE.PICKUP') ? $aRec->nOrd_Otp : '===='}}</td>
                                            <td>{{$aRec->sBuss_Name}}</td>
											<td class="text-right">$ {{number_format($aRec->sGrnd_Ttl, 2)}}</td>
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
											<td>{{date('d M, Y h:i A', strtotime($aRec->sCrt_DtTm))}}</td>
											<td class="action-btns">
												<ul>
													<li class="detail_btn my-order-btns"><a title="View" onclick="GetOrder('{{base64_encode($aRec->sOrdr_Id)}}')"> View</a></li>
													@if($aRec->nOrdr_Status == config('constant.ORDER_STATUS.Pending') && $aRec->sDelv_Date >= date('Y-m-d'))
													   <li class="detail_btn my-order-btns"><a href="#" data-toggle="modal" data-target="#OrderCancelModel" onclick="CnclOrd('{{base64_encode($aRec->lOrder_IdNo)}}')">Cancel</a></li>
													@endif
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
                            {{$oOrderLst->appends($request->all())->render()}}
                        </div>
                    </div>					
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')

<script>
$('#OrderModel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var aRec = button.data('rec');
	GetOrder(aRec);
});

$('#ExprtRcrd').on('click', function() {
    var sCrtDtTm        = $("input[name=sCrtDtTm]").val();
    var nOrdrStatus     = $("select[name=nOrdrStatus]").find(":selected").val();
    var nOrdrStatus     = nOrdrStatus == 'undefined' ? '' : nOrdrStatus;
    window.location=APP_URL+"/teacher_panel/manage_order/export?sCrtDtTm="+sCrtDtTm+"&nOrdrStatus="+nOrdrStatus;
});

function CnclOrdConf()
{ 
    if(confirm("Are you sure you want to cancel this order ?") == true)
    {
        var Url=APP_URL+"/teacher_panel/cancel_order";
        $('#OrderCancelModel #cancelForm').attr('action',Url);
    } 
}

function CnclOrd(lOrderIdNo)
{
    if(lOrderIdNo != '')
    { 
        $('#OrderCancelModel #lRecIdNo').val(lOrderIdNo); 
    }
} 
</script>