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
                            <h4 class="page-title">Owed Credits</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section">
                    <form action="{{url('milkbar_panel/my_credits')}}" method="get" id="credit_form">
                        <div class="row first-block">
                            <div class='col-sm-6 col-md-4 col-6   col-lg-3 from-boxes'>
                                <label>From Date(Transaction)</label>
                                <input type="date" name="sFrmDate" placeholder="MM/DD/YYYY" value="{{$request['sFrmDate']}}"> 
                            </div>
                            <div class='col-sm-6 col-md-4 col-6   col-lg-3 from-boxes'>
                                <label>To Date(Transaction)</label>
                                <input type="date" name="sToDate" placeholder="MM/DD/YYYY" value="{{$request['sToDate']}}"> 
                            </div>
                            <div class='col-sm-6 col-md-4 col-6   col-lg-3 from-boxes'>
                                <label>School Name</label>
                                <select name="lSchlIdNo">
                                    <option value="">All School</option>
									@foreach($aAccSchl as $aRec)
										<option {{ $request['lSchlIdNo'] == $aRec['lSchl_IdNo'] ? 'selected' : ''}} value="{{$aRec['lSchl_IdNo']}}">{{$aRec['sSchl_Name']}}</option>
									@endforeach
                                </select>
                            </div>
                            <div class='col-sm-6 col-md-4 col-6   col-lg-3 from-boxes'>
                                <label>Order Number</label>
                                <input type="text" name="sOrdrId" value="{{$request['sOrdrId']}}" onkeypress="return IsNumber(event, this.value, '8')">
                            </div>
                            <div class='col-sm-6 col-md-3 col-6   col-lg-2 from-boxes'>
                                <label>Owed Credits</label>
                                <input type="text" value="$ {{ !empty($aCrdtDtl['sTtlAmo']) ? number_format($aCrdtDtl['sTtlAmo'], 2) : '0.00' }}" readonly class="text-right">
                            </div>
                            <div class='col-sm-6 col-md-3 col-6   col-lg-2 from-boxes'>
                                <label>Credit Used</label>
                                <input type="text" value="$ {{ !empty($aDbtDtl['sTtlAmo']) ? number_format($aDbtDtl['sTtlAmo'], 2) : '0.00' }}" readonly class="text-right">
                            </div>
                            <div class='col-sm-6 col-md-3 col-6 col-lg-2 from-boxes'>
                                <label>Remaining Credit</label>
                                <input type="text" value="$ {{!empty($aCrdtDtl['sTtlAmo']) ? number_format($aCrdtDtl['sTtlAmo']-$aDbtDtl['sTtlAmo'], 2) : '0.00' }}" readonly class="text-right">
                            </div>
                            <div class='col-sm-6 col-md-6 col-6 col-lg-6 form-btns'>
                                <ul>
                                    <li><button type="button" id="Filter">Filter</button></li>
                                    <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="autowidthbtn15">Clear Filter</button></li>
                                    <li><button type="button" class="mr-0" id="ExprtRcrd">Export To Excel</button></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%"  class="   tablescroll">
                                <tr>
									<th class="nowordwrap">Tansaction Date</th>
                                    <th class="nowordwrap">Order No</th>
									<th class="nowordwrap">User Name</th>
									<th class="text-right nowordwrap">Credit Amount</th>
									<th class="text-right nowordwrap">Amount Used</th>
									<th class="text-center nowordwrap">Action</th>
								</tr>
								@if(count($oCrdtDtl) > 0)
                                    @foreach($oCrdtDtl As $aRec)
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
											<td>{{date('d M, Y h:i A', strtotime($aRec->sCrt_DtTm))}}</td>
											<td>{{$aRec->sOrdr_Id}}</td>
											<td>{{$sUserName}}</td>
											<td class="text-right"> @if($aRec->nTyp_Status == config('constant.TRANS.Credit')) $ {{$aRec->sTtl_Amo}} @endif</td>
											<td class="text-right"> @if($aRec->nTyp_Status == config('constant.TRANS.Debit')) $ {{$aRec->sTtl_Amo}} @endif</td>
											<td class="action-btns text-center">
											    <ul>
													<li class="detail_btn my-order-btns"><a title="View" onclick="GetOrder('{{base64_encode($aRec->sOrdr_Id)}}')"> Get Details</a></li>
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
                            {{$oCrdtDtl->appends($request->all())->render()}}
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
        $('#credit_form').submit();
    }
});

$('#ExprtRcrd').on('click', function() {
    var sFrmDate    = $("input[name=sFrmDate]").val();
    var sToDate     = $("input[name=sToDate]").val();
    if(sFrmDate != '' && sFrmDate > sToDate)
    {
        alert('To Date should be greater then From Date');
        return false;   
    }
    else
    {
        var sOrdrId     = $("input[name=sOrdrId]").val();
        var lSchlIdNo   = $("select[name=lSchlIdNo]").find(":selected").val();
        var lSchlIdNo   = lSchlIdNo == 'undefined' ? '' : lSchlIdNo;
        var sItemName   = $("input[name=sItemName]").val();
        window.location=APP_URL+"/milkbar_panel/my_credits/export?sFrmDate="+sFrmDate+"&sToDate="+sToDate+"&lSchlIdNo="+lSchlIdNo+"&sOrdrId="+sOrdrId;
    }
});

$('#OrderModel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var aRec = button.data('rec');
	GetOrder(aRec);
});
</script>