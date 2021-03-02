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
                        <div class="col-6">
                            <h4 class="page-title">Parent Details </h4>
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
                                <h5>Parent all information below</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-lg-6 table-responsive">
                            <table class="parent-details-table">
                                <tr>
                                    <td>Account ID</td>
                                    <td class="text-right">{{$aPrntsDtl['sAcc_Id']}}</td>
                                </tr>
                                <tr>
                                    <td>First Name</td>
                                    <td class="text-right">{{$aPrntsDtl['sFrst_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td class="text-right">{{$aPrntsDtl['sCntry_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Post Code</td>
                                    <td class="text-right">{{$aPrntsDtl['sPin_Code']}}</td>
                                </tr>
                                <tr>
                                    <td>Suburb</td>
                                    <td class="text-right">{{$aPrntsDtl['sSbrb_Name']}}</td>
                                </tr>
                            </table>
                        </div>
                        <!-- <div class="col-2"></div> -->
                        <div class="col-sm- col-lg-6 table-responsive">
                            <table class="parent-details-table">
                                <tr>
                                    <td>Relationship with Student</td>
                                    <td class="text-right">{{array_search($aPrntsDtl['lRltn_IdNo'], config('constant.RLTN_IDNO'))}}</td>
                                </tr>
                                <tr>
                                    <td>Surname</td>
                                    <td class="text-right">{{$aPrntsDtl['sLst_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>State</td>
                                    <td class="text-right">{{$aPrntsDtl['sState_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Mobile Number</td>
                                    <td class="text-right">{{$aPrntsDtl['sCntry_Code']}} {{$aPrntsDtl['sMobile_No']}}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td class="text-right">{{$aPrntsDtl['sEmail_Id']}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="container-fluid card-commission-section  parent-details-section mt-5">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <div>
                                <h4>Child Details</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 table-responsive">
                            <table width="100%" class="child-details-table tablescroll ">
                                <tr>
                                    <th class="nowordwrap">First Name</th>
                                    <th class="nowordwrap">Surname</th>
                                    <th class="nowordwrap">School Name</th>
                                    <th class="nowordwrap">School Type</th>
                                    <th class="text-right nowordwrap">Class</th>
                                </tr>
                                @foreach($aChldLst As $aRec)
                                <tr>
                                    <td>{{$aRec['sFrst_Name']}}</td>
                                    <td>{{$aRec['sLst_Name']}}</td>
                                    <td>{{$aRec['sSchl_Name']}}</td>
                                    <td>{{array_search($aRec['nSchl_Type'], config('constant.SCHL_TYPE'))}}</td>
                                    <td class="text-right">{{$aRec['sCls_Name']}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="back-btn text-center"> <button title="Back" onclick="history.back()"> Back</button></div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')