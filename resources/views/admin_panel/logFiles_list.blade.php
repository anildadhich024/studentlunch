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
                            <h4 class="page-title">Manage Log Files</h4>
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
                                    <th class="nowordwrap">S.No</th> 
                                    <th class="nowordwrap">Files</th>
                                    <th class="nowordwrap">File Name</th>  
                                    <th class="nowordwrap">Action</th>
                                </tr>
                                    <?php if(count($files) > 0){
                                        $i=1; 
                                        foreach($files as $aRec){ ?>
                                        <tr>
                                            <td> {{$i}} </td> 
                                            <td><a  style="color:#00fffe"  download href="{{('assets/logfiles/'.$aRec->getFileName())}}"><i class="fa fa-file-alt"></i></a></td> 
                                            <td>{{$aRec->getFileName()}} </td> 
                                            <td class="action-btns">
                                                <ul>
                                                    <li><a onClick="DltLogConf('{{$aRec->getFileName()}}')"><i class="fa fa-trash"></i></a></li>
                                                    <li><a style="color:#00fffe" download href="{{('assets/logfiles/'.$aRec->getFileName())}}"><i class="fa fa-download"></i></a></li>
                                                </ul> 
                                            </td>
                                        </tr> 
                                    <?php $i++; }}else{ ?>
                                        <tr><td colspan="8" class="text-center"><strong>No Record(s) Found</strong></td></tr>
                                    <?php } ?>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-lg-12"> 
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
  

 <script>
 function DltLogConf(FileName)
{ 
    if(confirm("Are you sure you want to delete this log file ?") == true)
    {
        window.location=APP_URL+"/admin_panel/logFiles/delete/"+FileName;  
    } 
}
 </script>
