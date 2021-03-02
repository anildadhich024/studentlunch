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
                            <h4 class="page-title">Manage Commission ({{$aBussName['sBuss_Name']}})</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section">
                    <form action="{{url('admin_panel/manage_commission_list')}}" method="get" id="commission_form">
                        <div class="row first-block">
                            <div class='col-sm-6 col-md-3 col-6 pb-3 from-boxes'>
                                <label>From (Transaction)</label>
                                <input type="date" name="sFrmDate" placeholder="MM/DD/YYYY" value="{{$request['sFrmDate']}}"> 
                            </div>
                            <div class='col-sm-6 col-md-3 col-6 pb-3 from-boxes'>
                                <label>To (Transaction)</label>
                                <input type="date" name="sToDate" placeholder="MM/DD/YYYY" value="{{$request['sToDate']}}"> 
                            </div>
                            <input type="hidden" name="lMilkIdNo" value="{{$request['lMilkIdNo']}}">
                            <div class='col-sm-6 col-md-6 col-12 pb-3 form-btns'>
                                <ul>
                                    <li class="pb-2"><button id="Filter" title="Filter" alt="Filter" class="  autowidthbtn15">Filter</button></li>
                                    <li class="pb-2"><button type="button" id="ClrFltrCom" title="Clear Filter">Clear Filter</button></li>
                                    <li><button type="button" class="mr-0 autowidthbtn15" id="ExprtRcrd" title="Export To Excel" alt="Export To Excel"  >Export To Excel</button></li>
                                    <li class="pb-2"><button title="Go Back" alt="Go Back" type="button" class="autowidthbtn15" onclick="window.location=APP_URL+'/admin_panel/manage_commission'">Back</button></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%" class="tablescroll">
                                <tr>
                                    <th>Order No</th>
                                    <th>Transaction Date</th>
                                    <th>Student Name</th>
									<th class="text-right">Order Amount</th>
                                    <th class="text-right">Used Credit</th>
                                    <th class="text-right">Online Pay</th>
                                    <th class="text-right">Commission</th>
                                    <th class="text-center">Action</th>
								</tr>
								@if(count($oComLst) > 0)
                                    @foreach($oComLst As $aRec)
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
                                        $oGetWlt = \App\Model\Wallet::Select('sTtl_Amo')->Where('nTyp_Status',config('constant.TRANS.Debit'))->Where('lOrder_IdNo',$aRec->lOrder_IdNo)->first();
                                        @endphp
										<tr>
                                            <td>{{$aRec->sOrdr_Id}}</td>
                                            <td>{{date('d M, Y h:i A', strtotime($aRec->sCrt_DtTm))}}</td>
                                            <td>{{$sUserName}}</td>
                                            <td class="text-right">$ {{number_format($aRec->sGrnd_Ttl, 2)}}</td>
                                            <td class="text-right">$ {{number_format($oGetWlt['sTtl_Amo'], 2)}}</td>
                                            <td class="text-right">$ {{number_format($aRec->sGrnd_Ttl - $oGetWlt['sTtl_Amo'], 2)}}</td>
                                            <td class="text-right">$ {{ number_format($aRec->sCom_Amo, 2)}}</td>
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
        var lMilkIdNo  = $("input[name=lMilkIdNo]").val();
        window.location=APP_URL+"/admin_panel/manage_commission_list/export?sFrmDate="+sFrmDate+"&sToDate="+sToDate+"&lMilkIdNo="+lMilkIdNo;
    }
});

$('#OrderModel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var aRec = button.data('rec');
	GetOrder(aRec);
});

$('#ClrFltrCom').on('click', function() {
    var lMilkIdNo  = $("input[name=lMilkIdNo]").val();
    var currentURL = location.protocol + '//' + location.host + location.pathname;
    window.location=currentURL+"?lMilkIdNo="+lMilkIdNo;
});
</script>
