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
                            <h4 class="page-title">Service Provider Details </h4>
                        </div>
                    </div>
                </div>
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section  parent-details-section">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <div>
                                <h4>Get Details</h4>
                            </div>
                            <div>
                                <h5>Service Provider all information below</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-lg-6 table-responsive">
                            <table class="parent-details-table">
                                <tr>
                                    <td>Account ID</td>
                                    <td class="text-right">{{$aMilkDtl['sAcc_Id']}}</td>
                                </tr>
                                <tr>
                                    <td>Business Type</td>
                                    <td class="text-right">{{array_search($aMilkDtl['nBuss_Type'], config('constant.BUSS_TYPE'))}}</td>
                                </tr>
                                <tr>
                                    <td>First Name</td>
                                    <td class="text-right">{{$aMilkDtl['sFrst_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td class="text-right">{{$aMilkDtl['sCntry_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Post Code</td>
                                    <td class="text-right">{{$aMilkDtl['sPin_Code']}}</td>
                                </tr>
                                <tr>
                                    <td>Suburb</td>
                                    <td class="text-right">{{$aMilkDtl['sSbrb_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Phone Number</td>
                                    <td class="text-right">{{$aMilkDtl['sCntry_Code']}}{{$aMilkDtl['sArea_Code']}} {{$aMilkDtl['sPhone_No']}}</td>
                                </tr>
                                <tr>
                                    <td>Stripe Setup</td>
                                    <td class="text-right">{{ empty($aMilkDtl['sStrp_Acc_Id']) ? 'Pending' : 'Completed' }}</td>
                                </tr>
                            </table>
                        </div>
                        <!-- <div class="col-2"></div> -->
                        <div class="col-sm-6 col-lg-6 table-responsive">
                            <table class="parent-details-table">
                                <tr>
                                    <td>Business Name</td>
                                    <td class="text-right">{{$aMilkDtl['sBuss_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Business ABN</td>
                                    <td class="text-right">{{$aMilkDtl['sAbn_No']}}</td>
                                </tr>
                                <tr>
                                    <td>Surname</td>
                                    <td class="text-right">{{$aMilkDtl['sLst_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>State</td>
                                    <td class="text-right">{{$aMilkDtl['sState_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Mobile Number</td>
                                    <td class="text-right">{{$aMilkDtl['sCntry_Code']}} {{$aMilkDtl['sMobile_No']}}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td class="text-right">{{$aMilkDtl['sEmail_Id']}}</td>
                                </tr>
                                <tr>
                                    <td>Menu Setup</td>
                                    <td class="text-right">{{ $CntItm->{'TtlRec'} > 0 ? 'Completed' : 'Pending' }}</td>
                                </tr>
                                <tr>
                                    <td>Email Verifaction</td>
                                    <td class="text-right">{{ $aMilkDtl['nEmail_Status'] == config('constant.MAIL_STATUS.UNVERIFIED') ? 'Pending' : 'Completed' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="container-fluid card-commission-section  parent-details-section mt-5">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <div>
                                <h4>School Details</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 table-responsive pt-3">
                            <table width="100%" class="child-details-table    tablescroll">
                            <!-- tablescroll838  -->
                                <tr>
                                    <th class="nowordwrap">School Type</th>
                                    <th class="nowordwrap">School Name</th>
                                    <th class="nowordwrap">Distance (in KM)</th>
                                    <th class="nowordwrap">Pin Code</th>
                                    <th class="nowordwrap">Subrub</th>
                                    <th class="text-right nowordwrap">Cut-off Time</th>
                                </tr>
                                @foreach($aSchlLst As $aRec)
                                <tr>
                                    <td>{{array_search($aRec['nSchl_Type'], config('constant.SCHL_TYPE'))}}</td>
                                    <td>{{$aRec['sSchl_Name']}}</td>
                                    <td>{{number_format($aRec['dDist_Km'], 2)}} KM</td>
                                    <td>{{$aRec['sPin_Code']}}</td>
                                    <td>{{$aRec['sSbrb_Name']}}</td>
                                    <td class="text-right">{{date('h:i A', strtotime($aRec['sCut_Tm']))}}</td>
                                </tr>
                                @endforeach
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