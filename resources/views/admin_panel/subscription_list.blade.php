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
                            <h4 class="page-title">Subscription Detail</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section"> 
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%" class=" tablescroll">
                            <!-- tablescroll936 -->
                                <tr> 
                                    <th class="nowordwrap">Duration</th>
                                    <th class="nowordwrap">Account ID</th> 
                                    <th class="nowordwrap">Parent Name</th> 
                                    <th class="nowordwrap">Country Name</th>
                                    <th class="nowordwrap">State Name</th>
                                    <th class="nowordwrap">Plan Amount</th>  
                                    <th class="nowordwrap">TXN ID</th> 
                                </tr>
                                @if(count($aSubLst) > 0)
                                    @foreach($aSubLst As $aRec)
                                    <tr> 
                                        <td>{{date('M-Y', strtotime($aRec->sStrt_Dt))}}</td>
                                        <td>{{$aRec->sAcc_Id}}</td> 
                                        <td>{{$aRec->sFrst_Name.' '.$aRec->sLst_Name}}</td>
                                        <td>{{$aRec->sCntry_Name}}</td>
                                        <td>{{$aRec->sState_Name}}</td>
                                        <td>$ {{ number_format($aRec->sPln_Amo)}}</td>
                                        <td>{{$aRec->sStrp_Id}}</td>
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
                            {{$aSubLst->appends($request->all())->render()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer') 