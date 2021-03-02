@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('milkbar_panel.layouts.side_panel')
            <main>
                <div class="page-breadcrumb">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="page-title">Manage Category</h4>
                        </div>
                    </div>
                </div>
                <!-- My Commissions From -->
                @include('admin_panel.layouts.message')
                <div class="container-fluid card-commission-section manage-page">
                    <form action="{{url('milkbar_panel/category/list')}}" method="get"> 
                        <div class="row first-block parent-list-form pt-3">
                            <div class='col-12 col-sm-6  col-md-3 pb-3'>
                                <label>Category Name</label>
                                <input type="text" name="sCatgName" placeholder="Category Name" value="{{$request['sCatgName']}}">
                            </div>
                            <div class='col-12 col-sm-12 col-md-9 pb-3 pt-0 form-btns'>
                                <ul>
                                    <li><button type="submit" title="Filter"  class="autowidthbtn15">Filter</button></li>
                                    <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="autowidthbtn15">Clear Filter</button></li>
                                    <li><button class="mr-0" type="button" title="Export To Excel" id="ExprtRcrd" class="autowidthbtn15">Export To Excel</button></li>
                                    <li>
                                        <div class="add-btn">
                                            <button title="Add Category" type="button" data-toggle="modal" data-target="#CategoryModel" class="autowidthbtn15">Add Category</button>
                                        </div>
                                    </li>
                                </ul>
                            </div>  
                        </div>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%"  class="   tablescroll" >
                                <tr>
                                    <th class="nowordwrap">Category ID</th>
                                    <th class="nowordwrap">Category Name</th>
                                    <th class="nowordwrap">Rego Date & Time</th>
                                    <th class="nowordwrap">Status</th>
                                    <th class="nowordwrap"> Action</th>
                                </tr>
                                @if(count($oCatgLst) > 0)
                                    @foreach($oCatgLst As $aRec)
                                    <tr>
                                        <td>{{$aRec->lCatg_Unq_Id}}</td>
                                        <td>{{$aRec->sCatg_Name}}</td>
                                        <td>{{date('d M, Y h:i A', strtotime($aRec->sCrt_DtTm))}}</td>
                                        <td>
                                            @if($aRec->nBlk_UnBlk == config('constant.STATUS.UNBLOCK'))
                                                <button class="active-btn" title="Active" onclick="chngStatus('{{base64_encode('mst_catg')}}','{{base64_encode('lCatg_IdNo')}}','{{base64_encode($aRec->lCatg_IdNo)}}','{{base64_encode(config('constant.STATUS.BLOCK'))}}')">Active</button>
                                            @else
                                                <button class="block-btn" title="In-Active" onclick="chngStatus('{{base64_encode('mst_catg')}}','{{base64_encode('lCatg_IdNo')}}','{{base64_encode($aRec->lCatg_IdNo)}}','{{base64_encode(config('constant.STATUS.UNBLOCK'))}}')">In-Active</button>
                                            @endif
                                        </td>
                                        <td class="action-btns">
                                            <ul>
                                                <li><a href="#" title="Edit {{$aRec->sCategory_Name}}" data-id="{{base64_encode($aRec->lCatg_IdNo)}}" data-name="{{$aRec->sCatg_Name}}" data-toggle="modal" data-target="#CategoryModel"> <i class="fa fa-edit"></i></a></li>
                                                <li><i class="fa fa-trash" onclick="DelRec('{{base64_encode('mst_catg')}}','{{base64_encode('lCatg_IdNo')}}','{{base64_encode($aRec->lCatg_IdNo)}}')" title="Delete {{$aRec->sCatg_Name}}"></i></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="5" class="text-center"><strong>No Record(s) Found</strong></td></tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="row pull-right">
                        <div class="col-sm-12 col-lg-12" style="padding-right: 0px;">
                            {{$oCatgLst->appends($request->all())->render()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="CategoryModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{url('milkbar_panel/category/save')}}" method="post" id="general_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="lCatgIdNo" id="lCatgIdNo" value="{{ base64_encode(0) }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Manage Category</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row account-form">
                                <div class="col">
                                    <label style="font-style: 12px !important; margin-bottom: 5px;">Category Name</label>
                                    <input type="text" name="sCatgName" id="sCatgName" placeholder="Sandwich" class="form-control" onkeypress="return IsAlphaSpecial(event, this.value, '30')" required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-lg-12 services-btns">
                                <ul class="m-auto text-center">
                                    <li>
                                        <div class="add-btn  mt-0"><button class="mt-0" data-dismiss="modal" aria-label="Close">Cancel</button></div>
                                    </li>
                                    <li>
                                        <div class="add-btn  mt-0"><button title="Add Category" type="submit" class="mt-0">Save</button></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript">
$('#CategoryModel').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var lCatgIdNo = button.data('id');
  var sCatgName = button.data('name');
  $(this).find('#lCatgIdNo').val(lCatgIdNo);
  $(this).find('#sCatgName').val(sCatgName);
})

$('#ExprtRcrd').on('click', function() {
    var sCatgName = $("input[name=sCatgName]").val();
    window.location=APP_URL+"/milkbar_panel/category/export?sCatgName="+sCatgName;
});
</script>