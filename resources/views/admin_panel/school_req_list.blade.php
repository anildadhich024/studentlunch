 
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
                            <h4 class="page-title">Manage Requested School</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section">
                    <form action="{{url('admin_panel/request/school/list')}}" method="get"> 
                        <div class="row first-block parent-list-form">
                            <div class='col-sm-6 col-md-3 col-6 pb-3'>
                                <label>School Type</label>
                                <select name="nSchlType" id="nSchlType" class="form-control">
                                    <option value="">Select School Type</option>
                                    @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                    <option <?php if($request['nSchlType']==$nType){ echo 'selected'; } ?> value="{{$nType}}">{{$sTypeName}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='col-sm-6 col-md-3 col-6 pb-3'>
                                <label>School Name</label>
                                <input type="text" name="sSchlName" placeholder="School Name" value="{{$request['sSchlName']}}" onkeypress="return IsAlpha(event, this.value, '30')">
                            </div>
                            <div class='col-sm-12 col-12 col-md-6   form-btns pb-3  pl15media767 pl-auto'  style=" padding-left: 15px;">
                                <ul>
                                    <li class="pb-2"><button type="submit" title="Filter" class="  autowidthbtn15">Filter</button></li>
                                    <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="  autowidthbtn15">Clear Filter</button></li>
                                </ul>
                            </div>  
                        </div>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%" class="tablescroll">
                                <tr>
                                    <th class="nowordwrap">Request By</th> 
                                    <th class="nowordwrap">School Type</th> 
                                    <th class="nowordwrap">School Name</th>
                                    <th class="nowordwrap">Suburb</th> 
                                    <th class="nowordwrap">Post Code</th> 
                                    <th class="nowordwrap text-center">Status</th>
                                    <th class="nowordwrap">Rego Date & Time</th>
                                    <th class="nowordwrap text-center">Action</th>
                                </tr>
                                	  
								@if(count($aReqSchlLst) > 0)
                                    @foreach($aReqSchlLst As $aRec)
										<tr>
                                            <td>
                                                <?php 
                                                if($aRec->nUser_Type == config('constant.USER.PARENT'))
                                                {
                                                    $aGetUser=DB::table('mst_prnts')->select('sFrst_Name','sLst_Name')->where('lPrnt_IdNo',$aRec->lUser_IdNo)->first();
                                                    echo $aGetUser->sFrst_Name." ".$aGetUser->sLst_Name;
                                                } 
                                                else if($aRec->nUser_Type == config('constant.USER.TEACHER'))
                                                {
                                                    $aGetUser=DB::table('mst_tchr')->select('sFrst_Name','sLst_Name')->where('lTchr_IdNo',$aRec->lUser_IdNo)->first();
                                                    echo $aGetUser->sFrst_Name." ".$aGetUser->sLst_Name;
                                                } else
                                                { 
                                                     $aGetUser=DB::table('mst_milk_bar')->select('sBuss_Name')->where('lMilk_IdNo',$aRec->lUser_IdNo)->first();
                                                    echo $aGetUser->sBuss_Name;
                                                } 
                                                ?>
                                            </td>
                                            <td>{{array_search($aRec->nSchl_Type, config('constant.SCHL_TYPE'))}}</td>
											<td>{{$aRec->sSchl_Name}}</td>
											<td>{{$aRec->sSbrb_Name}}</td>
                                            <td>{{$aRec->sPin_Code}}</td>
                                            <td class="text-center">
                                                @if($aRec->nReq_Status == config('constant.REQ_STATUS.Pending'))
                                                    <button class="primary-btn" title="Pending">Pending</button>
                                                @else
                                                    <button class="active-btn" title="Listed">Listed</button>
                                                @endif
                                            </td>
											<td>{{date('d M, Y h:i A', strtotime($aRec->sCrt_DtTm))}}</td>
											<td class="action-btns text-center">
												<ul>
													<li><a title="Mark as listed"> <i class="fa fa-check"></i></a></li>
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
                            {{$aReqSchlLst->appends($request->all())->render()}}
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