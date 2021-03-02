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
                            <h4 class="page-title">Subscription Summary</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section">
                    <form action="{{url('admin_panel/manage_subscription')}}" method="get"> 
                        <div class="row first-block parent-list-form">
                            <div class='col-sm-6 col-md-3 col-6 pb-3'>
                                <label>Country Name</label>
                                <input type="text" name="sCntryName" placeholder="Country Name" value="{{$request['sCntryName']}}" onkeypress="return IsAlpha(event, this.value, '30')">
                            </div>
                            <div class='col-sm-6 col-md-3 col-6 pb-3'>
                                <label>State Name</label>
                                <input type="text" name="sStateName" placeholder="State Name" value="{{$request['sStateName']}}" onkeypress="return IsAlpha(event, this.value, '30')">
                            </div>
                            <div class='col-sm-12 col-12 col-md-6   form-btns pb-3  pl15media767 pl-auto' style=" padding-left: 15px;">
                                <div class="row justify-content-between">
                                    <div class="col-auto">
                                        <ul>
                                            <li class="pb-2"><button type="submit" title="Filter" class="  autowidthbtn15">Filter</button></li>
                                            <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="  autowidthbtn15">Clear Filter</button></li> 
                                        </ul>
                                    </div>
                                </div> 
                            </div>  
                        </div>
                    </form>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%" class="tablescroll">
                                <tr>
                                    <th>Duration</th>
									<th class="text-center">Plan Count</th>
                                    <th class="text-center">Plan Amount</th>
                                    <th class="text-center">Country</th>
                                    <th class="text-center">State</th> 
                                    <th class="text-center">Action</th>
								</tr>
								@if(count($oPlnLst) > 0)
                                    @foreach($oPlnLst As $aRec) 
										<tr>
                                            <td>{{date('M-Y',strtotime($aRec->sPln_Dur))}}</td>
                                            <td class="text-center">{{ $aRec->{'nTtlPln'} }}</td>
                                            <td class="text-center">$ {{ number_format($aRec->{'sTtlAmt'}, 2) }}</td> 
                                            <td class="text-center">{{$aRec->sCntry_Name}}</td>
                                            <td class="text-center">{{$aRec->sState_Name}}</td>
                                            <td class="action-btns text-center">
											    <ul>
													<li class="detail_btn my-order-btns"><a title="View" href="{{url('admin_panel/subscription/list')}}?lStateIdNo={{$aRec->lState_IdNo}}&sPlnDur={{$aRec->sPln_Dur}}"> View</a></li>
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
                            {{$oPlnLst->appends($request->all())->render()}}
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
