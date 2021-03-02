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
                            <h4 class="page-title">Teacher Details </h4>
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-lg-6 table-responsive">
                            <table class="parent-details-table">
                                <tr>
                                    <td>Account ID</td>
                                    <td class="text-right">{{$aTchrDtl['sAcc_Id']}}</td>
                                </tr>
                                <tr>
                                    <td>First Name</td>
                                    <td class="text-right">{{$aTchrDtl['sFrst_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td class="text-right">{{$aTchrDtl['sCntry_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Suburb</td>
                                    <td class="text-right">{{$aTchrDtl['sSbrb_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Mobile Number</td>
                                    <td class="text-right">{{$aTchrDtl['sCntry_Code']}} {{$aTchrDtl['sMobile_No']}}</td>
                                </tr>
                            </table>
                        </div>
                        <!-- <div class="col-2"></div> -->
                        <div class="col-sm- col-lg-6 table-responsive">
                            <table class="parent-details-table">
                                <tr>
                                    <td>Email</td>
                                    <td class="text-right">{{$aTchrDtl['sEmail_Id']}}</td>
                                </tr>
                                <tr>
                                    <td>Surname</td>
                                    <td class="text-right">{{$aTchrDtl['sLst_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>State</td>
                                    <td class="text-right">{{$aTchrDtl['sState_Name']}}</td>
                                </tr>
                                <tr>
                                    <td>Post Code</td>
                                    <td class="text-right">{{$aTchrDtl['sPin_Code']}}</td>
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
                        <div class="col-sm-12 col-lg-12 table-responsive">
                            <table width="100%" class="child-details-table tablescroll ">
                                <tr>
                                    <th class="nowordwrap">School Type</th>
                                    <th class="nowordwrap">School Name</th>
                                    <th class="nowordwrap">Suburb</th>
                                    <th class="nowordwrap">Pin Code</th>
                                    <th class="nowordwrap">Role</th>
                                </tr>  
                                <?php if(!empty($aSchlLst)){?>
                                    <tr>
                                        <td>{{array_search($aSchlLst['nSchl_Type'], config('constant.SCHL_TYPE'))}}</td>
                                        <td>{{$aSchlLst['sSchl_Name']}}</td>
                                        <td>{{$aSchlLst['sSbrb_Name']}}</td>
                                        <td>{{$aSchlLst['sPin_Code']}}</td>
                                        <td>{{array_search($aSchlLst['nRole_Type'], config('constant.SCHL_ROLE'))}}</td>
                                    </tr> 
                                <?php }else{ ?>
                                    <tr>
                                        <td colspan="5">NO RECORD FOUND</td>
                                    </tr> 
                                  <?php } ?>
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