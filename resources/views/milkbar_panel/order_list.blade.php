@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('milkbar_panel.layouts.side_panel')
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
                    <form action="{{url('milkbar_panel/my_orders')}}" method="get">
                        <div class="row first-block">
                            <div class='col-sm-6 col-md-2 col-6  from-boxes'>
                                <label>Date of Delivery</label>
                                <input type="date" name="sDelvDate" placeholder="MM/DD/YYYY" value="{{$request['sDelvDate']}}"> 
                            </div>
                            <div class='col-sm-6 col-md-3 col-6  from-boxes'>
                                <label>School Name</label>
                                <select name="lSchlIdNo" class="form-control">
                                    <option value="">All School</option>
									@foreach($aAccSchl as $aRec)
										<option {{ $request['lSchlIdNo'] == $aRec['lSchl_IdNo'] ? 'selected' : ''}} value="{{$aRec['lSchl_IdNo']}}">{{$aRec['sSchl_Name']}}</option>
									@endforeach
                                </select>
                            </div>
                            <div class='col-sm-6 col-md-2 col-6  from-boxes'>
                                <label>Order Status</label>
                                <select name="nOrdrStatus" class="form-control">
                                    <option value="">All Order</option>
                                    @foreach(config('constant.ORDER_STATUS') as $sStatusName => $nOrdrStatus)
                                        <option {{ $request['nOrdrStatus'] == $nOrdrStatus ? 'selected' : ''}} value="{{$nOrdrStatus}}">{{$sStatusName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='col-sm-12 col-md-5  form-btns'>
                                <ul>
                                    <li class="pb-2"><button type="submit"  class="autowidthbtn15">Filter</button></li>
                                    <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter"  class="autowidthbtn15">Clear Filter</button></li>
                                    <li><button type="button" class="mr-0 autowidthbtn15" id="ExprtRcrd"  >Export To Excel</button></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%"  class="   tablescroll">
                                <tr>
                                    <th class="nowordwrap">Order No</th>
                                    <th class="nowordwrap">Order Type</th>
                                    <th class="nowordwrap">OPT</th>
                                    <th class="nowordwrap">School Name</th>
									<th class="nowordwrap">User Name</th>
                                    <th class="nowordwrap">Class</th>
									<th class="text-right nowordwrap" >Amount</th>
									<th class="nowordwrap">Delivery Date</th>
									<th class="text-center nowordwrap">Status</th>
                                    <th class="text-center nowordwrap" >Action</th>
								</tr>
								@if(count($oOrdLst) > 0)
                                    @foreach($oOrdLst As $aRec)
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
										<tr>
                                            <td>{{$aRec->sOrdr_Id}}</td>
                                            <td>{{array_search($aRec->nOrder_Type, config('constant.ORD_TYPE'))}}</td>
                                            <td>{{$aRec->nOrder_Type == config('constant.ORD_TYPE.PICKUP') ? $aRec->nOrd_Otp : '===='}}</td>
                                            <td>{{$aRec->sSchl_Name}}</td>
                                            <td>{{$sUserName}}</td>
                                            <td>{{$aRec->sCls_Name}}</td>
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
											<td class="action-btns">
											    <ul>
													<li class="detail_btn my-order-btns"><a title="View" onclick="GetOrder('{{base64_encode($aRec->sOrdr_Id)}}')"> View</a></li>
                                                    @if($aRec->nOrdr_Status == config('constant.ORDER_STATUS.Pending'))
                                                        <li class="detail_btn my-order-btns"><a onclick="DelOrder('{{base64_encode($aRec->lOrder_IdNo)}}')">Deliverd</a></li>
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
                            {{$oOrdLst->appends($request->all())->render()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript">
$('#ExprtRcrd').on('click', function() {
    var sDelvDate       = $("input[name=sDelvDate]").val();
    var lSchlIdNo       = $("select[name=lSchlIdNo]").find(":selected").val();
    var lSchlIdNo       = lSchlIdNo == 'undefined' ? '' : lSchlIdNo;
    var nOrdrStatus     = $("select[name=nOrdrStatus]").find(":selected").val();
    var nOrdrStatus     = nOrdrStatus == 'undefined' ? '' : nOrdrStatus;
    window.location=APP_URL+"/milkbar_panel/my_orders/export?sDelvDate="+sDelvDate+"&lSchlIdNo="+lSchlIdNo+"&nOrdrStatus="+nOrdrStatus;
});

$('#OrderModel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var aRec = button.data('rec');
	GetOrder(aRec);
});

function DelOrder(lOrderIdNo)
{
    if(confirm("Are you sure you want to deliver this order ?") == true)
    {
        window.location=APP_URL+"/milkbar_panel/my_orders/deliverd?lRecIdNo="+lOrderIdNo;
    }
}
</script>