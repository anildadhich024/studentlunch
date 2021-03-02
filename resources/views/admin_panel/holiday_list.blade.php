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
                            <h4 class="page-title">Manage Holiday</h4>
                        </div>
                    </div>
                </div>
                @include('admin_panel.layouts.message')
                <!-- My Commissions From -->
                <div class="container-fluid card-commission-section">
                    <form action="{{url('admin_panel/holiday/list')}}" method="get"> 
                        <div class="row first-block parent-list-form">
                            <div class='col-sm-6 col-md-3 col-6 pb-3'>
                                <select class="form-control" name="lCntryIdNo" autofocus="on" style="margin-top: 23px;">
                                    <option value="">== Select Country ==</option>
                                    @foreach($aCntryLst as $aRec)
                                        <option <?php if($lCntryId != ""){ if($lCntryId == $aRec['lCntry_IdNo']){ echo "selected";}} ?> value="{{base64_encode($aRec['lCntry_IdNo'])}}">{{$aRec['sCntry_Name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class='col-sm-12 col-12 col-md-6   form-btns pb-3  pl15media767 pl-auto' style=" padding-left: 15px;">
                                <div class="row justify-content-between">
                                    <div class="col-auto">
                                        <ul>
                                            <li class="pb-2"><button type="submit" title="Filter" class="  autowidthbtn15">Filter</button></li>
                                            <li class="pb-2"><button type="button" id="ClrFltr" title="Clear Filter" class="  autowidthbtn15">Clear Filter</button></li>
                                            </li> 
                                            <li>
                                                <button title="Add Country" type="button" class="autowidthbtn mt-auto autowidthbtn15" onclick="GetModal()">Add New Holiday</button>
                                            </li> 
                                        </ul>
                                    </div>
                                </div> 
                            </div>  
                        </div>
                    </form>
                    <!-- Commssions Details Tabel -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 commssions-table-details table-responsive parent-list-table">
                            <table style="width:100%" class=" tablescroll">
                            <!-- tablescroll936 -->
                                <tr>
                                    <th class="nowordwrap">Country Name</th>
                                    <th class="nowordwrap">State Name</th>
                                    <th class="nowordwrap">Holiday Type</th>
                                    <th class="nowordwrap">Holiday Name</th>
                                    <th class="nowordwrap">Start Date</th>
                                    <th class="nowordwrap">End Date</th> 
                                    <th class="nowordwrap">Action</th>
                                </tr>
                                @if(count($aHolidayLst) > 0)
                                    @foreach($aHolidayLst As $aRec)
                                    <tr>
                                        <td>{{$aRec->sCntry_Name}}</td>
                                        <td>{{$aRec->sState_Name}}</td>
                                        <td>@php echo ucwords(strtolower(array_search($aRec->nHolday_Type, config('constant.HOLIDAY_TYPE')))) @endphp
                                        </td>
                                        <td>{{$aRec->sHolday_Name}}</td>
                                        <td>{{date('d M, Y', strtotime($aRec->sStrt_Dt))}}</td>
                                        <td>{{date('d M, Y', strtotime($aRec->sEnd_Dt))}}</td> 
                                        <td class="action-btns">
                                             <ul>
                                                <li><i class="fa fa-edit" onclick="GetModal('Edit','{{$aRec->lHoliday_IdNo}}','{{$aRec->lCntry_IdNo}}','{{$aRec->lState_IdNo}}','{{$aRec->sStrt_Dt}}','{{$aRec->sEnd_Dt}}','{{$aRec->sHolday_Name}}','{{$aRec->nHolday_Type}}','{{$aRec->nDel_Status}}','{{$aRec->sCntry_Name}}')"></i></li>
                                                <li><i class="fa fa-trash" onclick="DelRec('{{base64_encode('mst_holiday')}}','{{base64_encode('lHoliday_IdNo')}}','{{base64_encode($aRec->lHoliday_IdNo)}}')" title="Delete"></i></li>
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
                            {{$aHolidayLst->appends($request->all())->render()}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript">
function GetModal(aRec="",lHoliday_IdNo="",lCntry_IdNo="",lState_IdNo="",sStrt_Dt="",sEnd_Dt="",sHolday_Name="",nHolday_Type="",nDel_Status="",sCntry_Name="")
{  
    if(aRec != '')
    {
        // aRec = JSON.parse(aRec);
        $('.modal-header h4').html('Edit Holiday');
        $.ajax({
            url: APP_URL + "/get_state?lCntryIdNo=" + btoa(lCntry_IdNo),
            success: function (response) {
                $('#lStateIdNo').find('option').remove();
                $('#lStateIdNo').append(`<option value="">== Select State ==</option>`);
                StateList = JSON.parse(response);
                StateList.forEach(function (StateList) {
                    var lStateIdNo = StateList['lState_IdNo'];
                    var sStateName = StateList['sState_Name'];
                    $('#lStateIdNo').append(`<option value="${lStateIdNo}">${sStateName}</option>`);
                });
                $('#HolidayModel select[name="lStateIdNo"] option[value='+lState_IdNo+']').attr('selected','selected');
            }
        });
        $("input[name='sStrtDt']").removeAttr('min');
        $("input[name='sEndDt']").removeAttr('min');
        $("#HolidayModel input[name='lHolidayIdNo']").val(btoa(lHoliday_IdNo));
        $('#HolidayModel select[name="lCntryIdNo"] option[value='+lCntry_IdNo+']').attr('selected','selected');
        $("#HolidayModel input[name='sHoldayName']").val(sHolday_Name);
        $('#HolidayModel select[name="nHoldayType"] option[value='+nHolday_Type+']').attr('selected','selected');
        $("#HolidayModel input[name='sStrtDt']").val(sStrt_Dt);
        $("#HolidayModel input[name='sEndDt']").val(sEnd_Dt);
        checkDate();
    }
    else
    {
        $('.form-control').val('');
        $('.modal-header h4').html('Add Holiday');
        $("input[name='sStrtDt']").prop('min', function(){ return new Date().toJSON().split('T')[0];});
        $("input[name='sEndDt']").prop('min', function(){ return new Date().toJSON().split('T')[0];});
    }
    $('#HolidayModel').modal('show');
}
</script>
<div class="modal fade" id="HolidayModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('admin_panel/holiday/save')}}" method="post" id="general_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="lHolidayIdNo" value="{{ base64_encode(0) }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Add Holiday</h4>
                </div>
                <div class="modal-body card-commission-section" style="margin-bottom: 0px !important;">
                    <form>
                        <div class="row account-form">
                            <div class="col">
                                <label>Country Name</label>
                                <select class="form-control" name="lCntryIdNo" required autofocus="on" tabindex="1" onchange="GetState(this.value)">
                                    <option value="">== Select Country ==</option>
                                    @foreach($aCntryLst as $aRec)
                                        <option value="{{$aRec['lCntry_IdNo']}}">{{$aRec['sCntry_Name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>State Name</label>
                                <select class="form-control" name="lStateIdNo" id="lStateIdNo" required tabindex="2">
                                    <option value="">== Select State ==</option>
                                </select>
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Holiday Name</label>
                                <input type="text" name="sHoldayName" id="sHoldayName" class="form-control" required tabindex="3" >
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Holiday Type</label>
                                <select name="nHoldayType" id="nHoldayType" class="form-control" required tabindex="4" >
                                    <option value="">Select Holiday Type</option>
                                    @foreach(config('constant.HOLIDAY_TYPE') as $sHType => $nHType)  
                                         <option value="{{$nHType}}">@php echo ucwords(strtolower($sHType)) @endphp</option>
                                    @endforeach 
                                </select>                                      
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Start Date</label>
                                <input type="date" name="sStrtDt" id="sStrtDt"  class="form-control"  onchange='checkDate()' placeholder="MM/DD/YYYY" required tabindex="5">
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>End Date</label>
                                <input type="date" name="sEndDt" id="sEndDt" class="form-control" onchange='checkDate()'  placeholder="MM/DD/YYYY" required tabindex="6">
                                <span class="text text-alert error" style="color:red"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-lg-12 services-btns">
                            <ul class="m-auto text-center">
                                <li>
                                    <div class="add-btn  mt-0"><button class="mt-0 btnhover" tabindex="6" data-dismiss="modal" aria-label="Close">Cancel</button></div>
                                </li>
                                <li>
                                    <div class="add-btn  mt-0"><button title="Save Item"  type="submit" class="mt-0 btnhover" id="submit" tabindex="7">Save</button></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript"> 
function checkDate()
{ 
    // $("#sEndDt").attr({ 
    //    "min" : minVal     // values (or variables) here
    // });
    var fromDate = $("#sStrtDt").val();
    var toDate = $('#sEndDt').val();
    var fdate = new Date(fromDate);
    var tdate = new Date(toDate);

    if (fdate.valueOf() > tdate.valueOf()) {
        $(".error").text('End Date must be greater than start date');
        $("#submit").attr("disabled", true);
        $('#submit').attr('style', 'background-color:#808080ba;border: 1px solid #808080;');
        // $('#submit').attr('style', 'border: 1px solid #808080;');         
    }else{ 
         $("#submit").removeAttr("disabled");
         $('#submit').removeAttr('style');
         $(".error").text('');
    }
}
function CommPer(numb) 
{
    if (numb <= 23 && numb > 0) 
    {
        var zz = parseFloat(numb) || 0;
        var zzz = zz.toFixed(2);
        document.getElementById('dComPer').value = zzz;
    }
    else 
    {
        $('#dComPer').val('23.00');
        document.getElementById('dComPer').focus();
        alert('Please put the value bettween 0.01 to 23.00');
    }
}

function PlnAmo() 
{
    var numb = document.getElementById('sPrntAmo').value;
    var zz = parseFloat(numb) || 0;
    var zzz = zz.toFixed(2);
    document.getElementById('sPrntAmo').value = zzz;
}
</script>