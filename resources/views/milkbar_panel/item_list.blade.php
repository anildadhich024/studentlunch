@include('admin_panel.layouts.header')
    <style>
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        #ItemModel .days-div ul li input[type=checkbox] {
            display: block;
        }
        /* input[type=checkbox] {
            opacity: 0;
        }

        input[type=checkbox]:focus {
            opacity: 1;
        } */
        #ItemModel .days-div ul li input[type=checkbox]+label:before{
            content: none;
        } 
        .btnhover:focus {
            background-color: #154c83 !important;
            border: 1px solid #154c83 !important;
        } 
          #add_item{background-color: #0a2c4e;
    border: 1px solid #0a2c4e; 
    border-radius: 4px;
    color: #fff;
    width: 120px;
    margin-right: 4px;
    padding: 6px 0px;
    font-family: 'poppinsregular';
    cursor: pointer;
        }
    </style>
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
                            <h4 class="page-title">Manage Menu</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section manage-page">
                    <form action="{{url('milkbar_panel/item/list')}}" method="get"> 
                        <div class="row first-block parent-list-form pt-4">
                            <div class='col-sm-6 col-md-3 col-6    '>
                                <label>Category Name</label>
                                <select class="form-control" name="lCatgIdNo">
                                    <option value="">== Select Category ==</option>
                                    @foreach($aCatgLst as $aRec)
                                        <option {{ $request['lCatgIdNo'] == $aRec['lCatg_IdNo'] ? 'selected' : ''}} value="{{$aRec['lCatg_IdNo']}}">{{$aRec['sCatg_Name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='col-sm-6 col-md-3 col-6'>
                                <label>Item Name</label>
                                <input type="text" name="sItemName" placeholder="Item Name" value="{{$request['sItemName']}}">
                            </div>
                            <div class='col-12 col-sm-12 col-md-6 col-lg-6 pb-3 pt-0 form-btns'>
                                <ul>
                                    <li><button type="submit" title="Filter" class="autowidthbtn15">Filter</button></li>
                                    <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="autowidthbtn15">Clear Filter</button></li>
                                    <li><button class="mr-0" type="button" title="Export To Excel" id="ExprtRcrd" class="autowidthbtn15">Export To Excel</button></li>
                                    <li>
                                        <div class="add-btn">
                                            <a title="Add Item" href="{{url('milkbar_panel/manage/item')}}">
                                                <button title="Add Item" type="button" class="autowidthbtn15">Add Item</button>
                                            </a>
                                            <button title="Import Item" type="button" data-rec="" data-toggle="modal" data-target="#ImportItem" class="autowidthbtn15">Import Item</button>
                                        </div>
                                    </li>
                                </ul>
                            </div> 
                        </div>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details parent-list-table">
                            <table style="width:100%"   class="   tablescroll">
                                <tr>
                                    <th class="nowordwrap">Item ID</th>
                                    <th class="nowordwrap">Category Name</th>
                                    <th class="nowordwrap">Item Name </th>
                                    <th class="nowordwrap">Price</th>
                                    <th class="nowordwrap">Status</th>
                                    <th class="nowordwrap">Action</th>
                                </tr>
                                @if(count($aItemLst) > 0)
                                    @foreach($aItemLst as $aRec)
                                    <tr>
                                        <td>{{$aRec->lItem_Unq_Id}}</td>
                                        <td>{{$aRec->sCatg_Name}}</td>
                                        <td>{{$aRec->sItem_Name}}</td>
                                        <td>$ {{number_format($aRec->sItem_Prc, 2)}}</td>
                                        <td>
                                            @if($aRec->nBlk_UnBlk == config('constant.STATUS.UNBLOCK'))
                                                <button class="active-btn" title="Active" onclick="chngStatus('{{base64_encode('mst_item')}}','{{base64_encode('lItem_IdNo')}}','{{base64_encode($aRec->lItem_IdNo)}}','{{base64_encode(config('constant.STATUS.BLOCK'))}}')">Active</button>
                                            @else
                                                <button class="block-btn" title="In-Active" onclick="chngStatus('{{base64_encode('mst_item')}}','{{base64_encode('lItem_IdNo')}}','{{base64_encode($aRec->lItem_IdNo)}}','{{base64_encode(config('constant.STATUS.UNBLOCK'))}}')">In-Active</button>
                                            @endif
                                        </td>
                                        <td class="action-btns">
                                            <ul>
                                                <li><a href="{{url('milkbar_panel/manage/item')}}?lRecIdNo={{base64_encode($aRec->lItem_IdNo)}}" title="Edit {{$aRec->sItem_Name}}"> <i class="fa fa-edit"></i></a></li>
                                                <li><i class="fa fa-trash" onclick="DelRec('{{base64_encode('mst_item')}}','{{base64_encode('lItem_IdNo')}}','{{base64_encode($aRec->lItem_IdNo)}}')" title="Delete {{$aRec->sItem_Name}}"></i></li>
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
                            {{$aItemLst->appends($request->all())->render()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
 
    <div class="modal fade" id="ImportItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{url('milkbar_panel/item/import')}}" method="post" id="general_form" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Import Item</h4>
                    </div>
                    <div class="modal-body card-commission-section">
                        <div class="row account-form"> 
                            <label class="col-lg-4 pt-2">Choose File (xls)</label>
                            <div class="col-lg-8">
                                <input type="file" class="required" name="ItemFile" value="" required accept=".xls,.xlsx">
                            </div> 
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-lg-12 services-btns">
                                <ul class="m-auto text-center">
                                    <li>
                                        <div class="add-btn  mt-0"><button class="mt-0" data-dismiss="modal" aria-label="Close">Back</button></div>
                                    </li>
                                    <li>
                                        <div class="add-btn  mt-0"><button title="Add Category" type="submit" class="mt-0">Import</button></div>
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
$('#ItemModel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var aRec = button.data('rec');
    if(aRec != '')
    {
        $('.modal-header h4').html('Edit Item');
        var aItemWeek = aRec['aItem_Week'].split(",");
        $(".save_continue").remove();
        //$(this).find(':checkbox').prop('checked', false);
        $(this).find('select[name="lCatgIdNo"] option[value='+aRec['lCatg_IdNo']+']').attr('selected','selected');
        $(this).find("input[name='lItemIdNo']").val(btoa(aRec['lItem_IdNo']));
        $(this).find("input[name='sItemName']").val(aRec['sItem_Name']);
        $(this).find("textarea").val(aRec['sItem_Dscrptn']);
        console.log(aRec['sItem_Dscrptn']);
        $(this).find("input[name='sItemPrc']").val(aRec['sItem_Prc']);
        $(this).find('select[name="lCatgIdNo"] option[value='+aRec['lCatg_IdNo']+']').attr('selected','selected');
        $("input[name='aItemWeek[]']").each(function(index) {
            var val = $(this).val();
            if (aItemWeek.includes(val)) 
            {
                $(this).prop('checked', true);
            }
            else
            {
                $(this).prop('checked', false);
            }
        });
    }
    else
    {
        $('.form-control').val('');
        $('.modal-header h4').html('Add Item');
        $("#ItemModel #buttons").append('<li class="save_continue"><div class="add-btn  mt-0"><button title="Save & Continue" data-toggle="modal" data-target="#ItemModel" type="submit" class="mt-0 btnhover" tabindex="7">Save & Continue</button></div></li>');
    }
});

$('#ExprtRcrd').on('click', function() {
    var lCatgIdNo = $("select[name=lCatgIdNo]").find(":selected").val();
    var lCatgIdNo = lCatgIdNo == 'undefined' ? '' : lCatgIdNo;
    var sItemName = $("input[name=sItemName]").val();
    window.location=APP_URL+"/milkbar_panel/item/export?lCatgIdNo="+lCatgIdNo+"&sItemName="+sItemName;
});

</script>