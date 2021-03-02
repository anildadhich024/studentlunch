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
                            <h4 class="page-title">School Detail</h4>
                        </div>
                    </div>
                </div>
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section  parent-details-section">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <div>
                                <h4>Account Info</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-lg-6 table-responsive">
                            <table class="parent-details-table">
                                <tr>
                                    <td>Account ID</td>
                                    <td class="text-right">{{$aSchlDtl['lAcc_Id']}}</td>
                                </tr>
                                <tr>
                                    <td>School Type</td>
                                    <td class="text-right">{{array_search($aSchlDtl['lSchl_Type'], config('constant.SCHL_TYPE'))}}</td>
                                </tr>
                                <tr>
                                    <td>School Name</td>
                                    <td class="text-right">{{$aSchlDtl['sSchl_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td class="text-right">{{$aSchlDtl['sEmail_Id']}}</td>
                                </tr>
                                <tr>
                                    <td>Phone Number</td>
                                    <td class="text-right">{{$aSchlDtl['sCntry_Code']}} {{$aSchlDtl['sArea_Code']}} {{$aSchlDtl['sPhone_No']}}</td>
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td class="text-right">{{$aSchlDtl['sCntry_Name']}}</td>
                                </tr>
                            </table>
                        </div>
                        <!-- <div class="col-2"></div> -->
                        <div class="col-sm-6 col-lg-6 table-responsive">
                            <table class="parent-details-table">
                                <tr>
                                    <td>Street Number</td>
                                    <td class="text-right">{{$aSchlDtl['sStrt_No']}}</td>
                                </tr>
                                <tr>
                                    <td>Street Name</td>
                                    <td class="text-right">{{$aSchlDtl['sStrt_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Suburb</td>
                                    <td class="text-right">{{$aSchlDtl['sSbrb_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>State Name</td>
                                    <td class="text-right">{{$aSchlDtl['sState_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Post Code</td>
                                    <td class="text-right">{{$aSchlDtl['sPin_Code']}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="container-fluid card-commission-section  parent-details-section mt-5">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <div>
                                <h4>School Contact</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 table-responsive pt-3">
                            <table width="100%" class="child-details-table tablescroll">
                                <tr>
									<th class="nowordwrap">Role</th>
									<th class="nowordwrap">Title</th>
									<th class="nowordwrap">First Name</th>
									<th class="nowordwrap">Surname</th>
									<th class="nowordwrap">Phone Number</th>
									<th class="nowordwrap">Mobile Number</th>
									<th class="nowordwrap">Email</th>
								</tr>
                                @foreach($aSchlCntctLst As $aRec)
                                <tr>
                                    <td>{{array_search($aRec['nCntct_Role'], config('constant.SCHL_ROLE'))}}</td>
									<td>{{array_search($aRec['nCntct_Title'], config('constant.TITLE'))}}</td>
                                    <td>{{$aRec['sFrst_Name']}}</td>
                                    <td>{{$aRec['sLst_Name']}}</td>
                                    <td>{{$aRec['sPhone_No']}}</td>
                                    <td>0{{$aRec['sMobile_No']}}</td>
                                    <td>{{$aRec['sEmail_Id']}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
				</div>
                <div class="container-fluid card-commission-section  parent-details-section mt-5">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <div>
                                <h4>Services Provider</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 table-responsive pt-3">
                            <table width="100%" class="child-details-table tablescroll">
                                <tr>
                                    <th class="nowordwrap">Service Provider</th>
                                    <th class="nowordwrap">Suburb</th>
                                    <th class="nowordwrap">Pin Code</th>
                                    <th class="nowordwrap">Phone Number</th>
                                    <th class="nowordwrap">Distance</th>
                                    <th class="nowordwrap">Cut-Off Time</th>
                                </tr>
                                @if(count($oAssSchl) > 0)
                                    @foreach($oAssSchl As $aRec)
                                    <tr>
                                        <td>{{$aRec['sBuss_Name']}}</td>
                                        <td>{{$aRec['sSbrb_Name']}}</td>
                                        <td>{{$aRec['sPin_Code']}}</td>
                                        <td>{{$aRec['sCntry_Code']}} {{$aRec['sArea_Code']}} {{$aRec['sPhone_No']}}</td>
                                        <td>{{$aRec['dDist_Km']}} KM</td>
                                        <td>{{$aRec['sCut_Tm']}}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="6" align="center"><b>No Record Found</b></td></tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="back-btn text-center">
                                <button title="Back" onclick="history.back()"> Back</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')