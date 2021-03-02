<?php 
use App\Model\Variant;
?>
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
                            <h4 class="page-title">Manage Variant</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section manage-page">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <div>
                                <h4>Manage Variant</h4>
                            </div>
                        </div>
                    </div>
                    <form action="{{url('milkbar_panel/variant/list')}}" method="get"> 
                        <div class="row first-block parent-list-form pt-4">
                            <div class='col-sm-6 col-md-3 col-6    '>
                                <label>Variant Name</label>
                                <input type="text" name="sVariantName" placeholder="Variant Name" value="{{$request['sVariantName']}}">
                            </div>
                            <div class='col-12 col-sm-12 col-md-6 col-lg-6 pb-3 pt-0 form-btns'>
                                <ul>
                                    <li><button type="submit" title="Filter" class="autowidthbtn15">Filter</button></li>
                                    <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="autowidthbtn15">Clear Filter</button></li>
                                    <!-- <li><button class="mr-0" type="button" title="Export To Excel" id="ExprtRcrd" class="autowidthbtn15">Export To Excel</button></li> -->
                                    <li>
                                        <div class="add-btn">
                                            <button title="Add Variant" type="button" data-rec="" data-toggle="modal" data-target="#VariantModel" class="autowidthbtn15">Add Variant</button>
                                            <!-- <button title="Import Item" type="button" data-rec="" data-toggle="modal" data-target="#ImportVariant" class="autowidthbtn15">Import Item</button> -->
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
                                    <th class="nowordwrap">Variant ID</th>
                                    <th class="nowordwrap">Variant Name</th>
                                    <th class="nowordwrap">Item Name </th>
                                    <th class="nowordwrap">Status</th>
                                    <th class="nowordwrap">Action</th>
                                </tr>
                                @if(count($aVariantLst) > 0)
                                    @foreach($aVariantLst as $aRec)
                                    <tr>
                                        <td>{{$aRec->lVar_Unq_Id}}</td>
                                        <td>{{$aRec->sVariant_Name}}</td>
                                        <td><?php 
                                        $items=array();
                                        $item= Variant::VariantItemLst($aRec->IVariant_IdNo);
                                            if(count($item)>0){
                                                foreach($item as $i){ 
                                                    $items[]=$i->sItem_name; 
                                            }} $var_item=implode(',',$items);  echo $var_item;?></td>
                                        <td>
                                            @if($aRec->nBlk_UnBlk == config('constant.STATUS.UNBLOCK'))
                                                <button class="active-btn" title="Active" onclick="chngStatus('{{base64_encode('mst_variant')}}','{{base64_encode('IVariant_IdNo')}}','{{base64_encode($aRec->IVariant_IdNo)}}','{{base64_encode(config('constant.STATUS.BLOCK'))}}')">Active</button>
                                            @else
                                                <button class="block-btn" title="In-Active" onclick="chngStatus('{{base64_encode('mst_variant')}}','{{base64_encode('IVariant_IdNo')}}','{{base64_encode($aRec->IVariant_IdNo)}}','{{base64_encode(config('constant.STATUS.UNBLOCK'))}}')">In-Active</button>
                                            @endif
                                        </td>
                                        <td class="action-btns">
                                            <ul>
                                                <li><a href="#" title="Edit {{$aRec->sVariant_Name}}" data-rec="{{$aRec}}" data-item_rec="{{$var_item}}" data-toggle="modal" data-target="#VariantModel"> <i class="fa fa-edit"></i></a></li>
                                                <li><i class="fa fa-trash" onclick="DelVarRec('{{base64_encode('mst_variant')}}','{{base64_encode('IVariant_IdNo')}}','{{base64_encode($aRec->IVariant_IdNo)}}')" title="Delete {{$aRec->sVariant_Name}}"></i></li>
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
                            {{$aVariantLst->appends($request->all())->render()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
 
  <div class="modal fade" id="VariantModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{url('milkbar_panel/variant/save')}}" method="post" id="general_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="lVariantIdNo" id="lVariantIdNo" value="{{ base64_encode(0) }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Add Variant</h4>
                    </div>
                    <div class="modal-body card-commission-section"> 
                        <div class="row account-form">
                            <div class="col">
                                <label>Variant Name</label>
                                <input type="text" name="sVariantName" class="form-control" onkeypress="return IsItemName(event, this.value, '50')" required tabindex="2">
                            </div>
                        </div> 
                        <button type="button" style="float: right;margin-bottom: 10px;" class="btn btn-warning"><i class="fa fa-plus" onclick="CrtRow()"></i></button>

                        <div class="row account-form" style="width:100%">
                            
                            <div class="col">
                                <table class="table-border">
                                    <thead>
                                        <tr> 
                                            <th colspan=2>Variant Item Name</th>  
                                        </tr>
                                    </thead>
                                    <tbody id="var_body"> 
                                        <tr id="Row_1">
                                            <td><i class="fa fa-minus" onclick="DeleteRow(1)"></i></td>
                                            <td><input style="width: 390px;margin: 5px 0px 5px 15px;" type="text" class="@error('sVarItemName1') is-invalid @enderror form-control" name="sVarItemName1" onkeypress="return IsItemName(event, this.value, '50')" id="sVarItemName1" required /></td>
                                        </tr>
                                        <input type="hidden" name="nTtlRec" id="nTtlRec" value="1">
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-lg-12 services-btns">
                                <ul class="m-auto text-center">
                                    <li>
                                        <div class="add-btn  mt-0"><button class="mt-0 btnhover" tabindex="6" data-dismiss="modal" aria-label="Close">Cancel</button></div>
                                    </li>
                                    <li>
                                        <div class="add-btn  mt-0"><button title="Save Variant" type="submit" class="mt-0 btnhover" tabindex="7">Save</button></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="ImportVariant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{url('milkbar_panel/variant/import')}}" method="post" id="general_form" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Import Variant</h4>
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
$('#VariantModel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var aRec = button.data('rec'); 
    var aItemRec = button.data('item_rec');
    if(aRec != '')
    {
        var itemArrary=aItemRec.split(",");
        $('.modal-header h4').html('Edit Variant');
        $(this).find("input[name='sVariantName']").val(aRec['sVariant_Name']); 
         $(this).find("input[name='lVariantIdNo']").val(btoa(aRec['IVariant_IdNo']));

        for(var i = 0; i < itemArrary.length; i++)
        {
            var valNo=parseInt(i)+1;
            newdiv = document.createElement('tr');
            divid = "Row_"+valNo;
            newdiv.setAttribute('id', divid);
            content = ''; 
            content += '<tr id="Row_'+valNo+'">';
            content += '<td><i class="fa fa-minus" onclick="DeleteRow('+valNo+')"></i></td>';
            content += '<td><input type="text" class="form-control" style="width: 390px;margin: 5px 0px 5px 15px;" value="'+itemArrary[i]+'" name="sVarItemName'+valNo+'" id="sVarItemName'+valNo+'" onkeypress="return IsItemName(event, this.value, 50)"  required /></td>';
            content += '</tr>';
            newdiv.innerHTML = content;
            $("#var_body").append(newdiv);
        }
        $("#nTtlRec").val(valNo); 
        $("#Row_1").remove();
       
    }
    else
    {
        $('.form-control').val('');
        $('.modal-header h4').html('Add Variant');
    }
})

var nRowId;
function CrtRow() 
{
    
    var rowCount = $('#VariantModel tbody tr').length;
    if(rowCount == 5)
    {
        alert("Maximum 5 variant item name allowed...");
    }
    else
    {
        total = $("#nTtlRec").val();
        next_no = parseInt(total)+1;
        newdiv = document.createElement('tr');
        divid = "Row_"+next_no;
        newdiv.setAttribute('id', divid);
        content = '';
        content += '<tr id="Row_'+next_no+'">';
        content += '<td><i class="fa fa-minus" onclick="DeleteRow('+next_no+')"></i></td>';
        content += '<td><input type="text" style="width: 390px;margin: 5px 0px 5px 15px;" name="sVarItemName'+next_no+'" class="form-control" id="sVarItemName'+next_no+'" onkeypress="return IsItemName(event, this.value, 50)"  required /></td>';
        content += '</tr>';
        newdiv.innerHTML = content;
        $("#nTtlRec").val(next_no);
        $("#var_body").last().append(newdiv);
    }
}

function DeleteRow(nRow)
{
     var rowCount = $('#VariantModel tbody tr').length;
    if(rowCount == 1)
    {
        alert("Minimum 1 variant item name required...");
    } 
    else if(confirm("Are you sure to delete this row") == true) {
        var row = $('#Row_'+nRow); 
        row.remove();
    }
}

$('#ExprtRcrd').on('click', function() {
    var lCatgIdNo = $("select[name=lCatgIdNo]").find(":selected").val();
    var lCatgIdNo = lCatgIdNo == 'undefined' ? '' : lCatgIdNo;
    var sItemName = $("input[name=sItemName]").val();
    window.location=APP_URL+"/milkbar_panel/variant/export?lCatgIdNo="+lCatgIdNo+"&sItemName="+sItemName;
});
</script>