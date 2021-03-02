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
                            <h4 class="page-title">Manage School</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section">
                    <form action="{{url('admin_panel/school/list')}}" method="get"> 
                        <div class="row first-block parent-list-form">
                            <div class='col-sm-6 col-md-3 col-6 pb-3'>
                                <label>School Name</label>
                                <input type="text" name="sSchlName" placeholder="School Name" value="{{$request['sSchlName']}}" onkeypress="return IsAlpha(event, this.value, '30')">
                            </div>
                            <div class='col-sm-6 col-md-3 col-6 pb-3'>
                                <label>Mobile Number</label>
                                <input type="text" name="sMobileNo" placeholder="Mobile Number" value="{{$request['sMobileNo']}}" onkeypress="return IsNumber(event, this.value, '10')">
                            </div>
                            <div class='col-sm-12 col-12 col-md-6   form-btns pb-3  pl15media767 pl-auto'  style=" padding-left: 15px;">
                                <ul>
                                    <li class="pb-2"><button type="submit" title="Filter" class="  autowidthbtn15">Filter</button></li>
                                    <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="  autowidthbtn15">Clear Filter</button></li>
                                    <li><button class="mr-0" type="button" title="Export To Excel" id="ExprtRcrd" class="  autowidthbtn15">Export To Excel</button></li>
                                    <li class="add-btn" >
                                        <a href="{{url('admin_panel/school/manage')}}">
                                            <button title="Add School" type="button" class="autowidthbtn mt-auto  autowidthbtn15">Add School</button>
                                        </a>
                                    </li>
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
                                    <th class="nowordwrap">School Name </th>
                                    <th class="nowordwrap">School Type</th>
                                    <th class="nowordwrap">State</th>
                                    <th class="nowordwrap">Country</th>
                                    <th class="nowordwrap">Rego Date & Time</th>
                                    <th class="nowordwrap">Status</th>
                                    <th class="nowordwrap">Action</th>
                                </tr>
								@if(count($aSchlLst) > 0)
                                    @foreach($aSchlLst As $aRec)
										<tr>
											<td>{{$aRec->lAcc_Id}}</td>
											<td>{{$aRec->sSchl_Name}}</td>
                                            <td>{{array_search($aRec->lSchl_Type, config('constant.SCHL_TYPE'))}}</td>
											<td>{{$aRec->sState_Name}}</td>
                                            <td>{{$aRec->sCntry_Name}}</td>
											<td>{{date('d M, Y h:i A', strtotime($aRec->sCrt_DtTm))}}</td>
											<td>
                                                @if($aRec->nBlk_UnBlk == config('constant.STATUS.UNBLOCK'))
                                                    <button class="active-btn" title="Active" onclick="chngStatus('{{base64_encode('mst_schl')}}','{{base64_encode('lSchl_IdNo')}}','{{base64_encode($aRec->lSchl_IdNo)}}','{{base64_encode(config('constant.STATUS.BLOCK'))}}')">Active</button>
                                                @else
                                                    <button class="block-btn" title="In-Active" onclick="chngStatus('{{base64_encode('mst_schl')}}','{{base64_encode('lSchl_IdNo')}}','{{base64_encode($aRec->lSchl_IdNo)}}','{{base64_encode(config('constant.STATUS.UNBLOCK'))}}')">In-Active</button>
                                                @endif
                                            </td>
											<td class="action-btns">
												<ul>
													<li><a href="{{url('admin_panel/school/detail')}}?lRecIdNo={{base64_encode($aRec->lSchl_IdNo)}}" title="View {{$aRec->sSchl_Name}} Details"> <i class="fa fa-eye"></i></a></li>
													<li><a href="{{url('admin_panel/school/manage')}}?lRecIdNo={{base64_encode($aRec->lSchl_IdNo)}}" title="Edit {{$aRec->sSchl_Name}}"> <i class="fa fa-edit"></i></a></li>
													<li><i class="fa fa-trash" onclick="DelRec('{{base64_encode('mst_schl')}}','{{base64_encode('lSchl_IdNo')}}','{{base64_encode($aRec->lSchl_IdNo)}}')" title="Delete {{$aRec->sSchl_Name}}"></i></li>
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
                            {{$aSchlLst->appends($request->all())->render()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript">
$('#ExprtRcrd').on('click', function() {
    var sSchlName = $("input[name=sSchlName]").val();
    var sMobileNo = $("input[name=sMobileNo]").val();
    window.location=APP_URL+"/admin_panel/school/export?sSchlName="+sSchlName+"&sMobileNo="+sMobileNo;
});
</script>