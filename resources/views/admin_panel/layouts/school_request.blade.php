<style type="text/css">
.modal-header h3{
    font-size: 19px;
    margin: 0px 0px;
    padding: 0px;
}
</style>
<div class="modal fade" id="ParentSchool" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 800px;">
        <form action="{{url('parent_panel/school/save')}}" method="post" id="request_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="lPrntIdNo" id="lPrntIdNo">
             <div class="modal-content" style="padding: 10px;">
                <div class="modal-header">
                    <div class="row"> 
                        <h3>School Request Form :</h3> 
                    </div>
                </div>
                <div class="modal-body">   
                    <div class="row account-form"> 
                        <div class="Add-School-Table">
                            <div style="overflow-x:auto;">
                                <table class="table-border" style="min-width: 0px !important;">
                                    <thead>
                                        <tr>
                                            <th style="width: 170px;">School Type</th>
                                            <th style="width: 210px;">School Name</th>
                                            <th style="width: 145px;">Suburb</th> 
                                            <th style="width: 100px;">Post Code</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if(Session::has('request_school'))
                                        {                                               
                                        $SchlReqst=Session::get('request_school'); 
                                        foreach($SchlReqst as $key=>$se)
                                        {
                                            $lSchlType=$se['lSchlTypes'];?>
                                            <tr id="Row_<?=$key?>" class="addMore">
                                                <td>
                                                    <select name="lSchlTypes{{$key}}"  id="lSchlTypes{{$key}}" class="form-control">
                                                        <option value="">School Type</option>
                                                        @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                            <option <?php if($lSchlType==$nType){ echo 'selected'; } ?> value="{{$nType}}">{{$sTypeName}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="sSchlName{{$key}}" id="sSchlName{{$key}}" value="{{ $se['sSchlName'] }}" class="form-control" onkeypress=" return IsSchool(event, this.value, '50')">
                                                </td>
                                                <td>
                                                    <input type="text" name="sSbrbName{{$key}}" id="sSbrbName{{$key}}" value="{{ $se['sSbrbName'] }}" class="form-control" onkeypress=" return IsAlpha(event, this.value, '30')">
                                                </td>
                                                <td>
                                                    <input type="text" name="sPinCode{{$key}}" id="sPinCode{{$key}}" value="{{ $se['sPinCode'] }}" class="form-control"  onkeypress=" return IsNumber(event, this.value, '4')">
                                                </td>
                                            </tr>
                                        <?php 
                                        }
                                        } 
                                        else
                                        { 
                                        for($i=1; $i<=5;$i++)
                                        {
                                        ?>
                                            <tr id="Row_<?=$i?>" class="addMore">
                                                <td>
                                                    <select name="lSchlTypes{{$i}}" id="lSchlTypes{{$i}}" class="form-control">
                                                        <option value="">School Type</option>
                                                        @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                            <option  value="{{$nType}}">{{$sTypeName}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="sSchlName{{$i}}" id="sSchlName{{$i}}" class="form-control" onkeypress=" return IsSchool(event, this.value, '50')">
                                                </td>
                                                <td>
                                                    <input type="text" name="sSbrbName{{$i}}" id="sSbrbName{{$i}}" class="form-control" onkeypress=" return IsAlpha(event, this.value, '30')">
                                                </td>
                                                <td>
                                                    <input type="text" name="sPinCode{{$i}}" id="sPinCode{{$i}}" class="form-control"  onkeypress=" return IsNumber(event, this.value, '4')">
                                                </td>
                                            </tr>
                                        <?php }} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div>
                <input type="hidden" name="nTtlRecs" id="nTtlRecs" value="5">
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-lg-12 services-btns"> 
                            <div class="row">
                                <div class="col-lg-6">
                                    <button class="btn btn-danger mt-0" data-dismiss="modal" aria-label="Close">Cancel</button>
                                </div>
                                <div class="col-lg-6">
                                    <button title="Add Category" type="submit" name="submit" class="btn btn-primary mt-0">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="MilkSchool" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 800px;">
        <form action="{{url('milkbar_panel/school/save')}}" method="post" id="request_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="lMilkIdNo" id="lMilkIdNo">
             <div class="modal-content" style="padding: 10px;">
                <div class="modal-header">
                    <div class="row"> 
                        <h3>School Request Form :</h3> 
                    </div>
                </div>
                <div class="modal-body">   
                    <div class="row account-form"> 
                        <div class="Add-School-Table">
                            <div style="overflow-x:auto;">
                                <table class="table-border" style="min-width: 0px !important;">
                                    <thead>
                                        <tr>
                                            <th style="width: 170px;">School Type</th>
                                            <th style="width: 210px;">School Name</th>
                                            <th style="width: 145px;">Suburb</th> 
                                            <th style="width: 100px;">Post Code</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                         if(Session::has('request_school')){                                               
                                        $SchlReqst=Session::get('request_school'); 
                                        foreach($SchlReqst as $key=>$se){
                                             $lSchlType=$se['lSchlTypes'];?>
                                           <tr id="Row_<?=$key?>" class="addMore">
                                                <td>
                                                    <select name="lSchlTypes{{$key}}"  id="lSchlTypes{{$key}}" class="form-control">
                                                        <option value="">School Type</option>
                                                        @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                            <option <?php if($lSchlType==$nType){ echo 'selected'; } ?> value="{{$nType}}">{{$sTypeName}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="sSchlName{{$key}}" id="sSchlName{{$key}}" value="{{ $se['sSchlName'] }}" class="form-control" onkeypress=" return IsSchool(event, this.value, '50')">
                                                </td>
                                                <td>
                                                    <input type="text" name="sSbrbName{{$key}}" id="sSbrbName{{$key}}" value="{{ $se['sSbrbName'] }}" class="form-control" onkeypress=" return IsAlpha(event, this.value, '30')">
                                                </td>
                                                <td>
                                                    <input type="text" name="sPinCode{{$key}}" id="sPinCode{{$key}}" value="{{ $se['sPinCode'] }}" class="form-control"  onkeypress=" return IsNumber(event, this.value, '4')">
                                                </td>
                                            </tr>
                                        <?php }?><?php } 
                                        else{ for($i=1; $i<=3;$i++){?>
                                            <tr id="Row_<?=$i?>" class="addMore">
                                                <td>
                                                    <select name="lSchlTypes{{$i}}" id="lSchlTypes{{$i}}" class="form-control">
                                                        <option value="">School Type</option>
                                                        @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                            <option  value="{{$nType}}">{{$sTypeName}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="sSchlName{{$i}}" id="sSchlName{{$i}}" class="form-control" onkeypress=" return IsSchool(event, this.value, '50')">
                                                </td>
                                                <td>
                                                    <input type="text" name="sSbrbName{{$i}}" id="sSbrbName{{$i}}" class="form-control" onkeypress=" return IsAlpha(event, this.value, '30')">
                                                </td>
                                                <td>
                                                    <input type="text" name="sPinCode{{$i}}" id="sPinCode{{$i}}" class="form-control"  onkeypress=" return IsNumber(event, this.value, '4')">
                                                </td>
                                            </tr>
                                        <?php }} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div>
                <input type="hidden" name="nTtlRecs" id="nTtlRecs" value="3">
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-lg-12 services-btns"> 
                            <div class="row">
                                <div class="col-lg-6">
                                    <button class="btn btn-danger mt-0" data-dismiss="modal" aria-label="Close">Cancel</button>
                                </div>
                                <div class="col-lg-6">
                                    <button title="Add Category" type="submit" name="submit" class="btn btn-primary mt-0">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="TchrSchool" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 800px;">
        <form action="{{url('teacher_panel/school/save')}}" method="post" id="request_form">
            <input type="hidden" name="_token" value="{{ csrf_token()}}">
            <input type="hidden" name="lTchrIdNo" id="lTchrIdNo">
             <div class="modal-content" style="padding: 10px;">
                <div class="modal-header">
                    <div class="row"> 
                        <h3>School Request Form :</h3> 
                    </div>
                </div>
                <div class="modal-body">   
                    <div class="row account-form"> 
                        <div class="Add-School-Table">
                            <div style="overflow-x:auto;">
                                <table class="table-border" style="min-width: 0px !important;">
                                    <thead>
                                        <tr>
                                            <th style="width: 170px;">School Type</th>
                                            <th style="width: 210px;">School Name</th>
                                            <th style="width: 145px;">Suburb</th> 
                                            <th style="width: 100px;">Post Code</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                         if(Session::has('request_school')){                                               
                                        $SchlReqst=Session::get('request_school'); 
                                        foreach($SchlReqst as $key=>$se){ 
                                             $lSchlType=$se['lSchlTypes'];?>
                                           <tr id="Row_<?=$key?>" class="addMore">
                                                <td>
                                                    <select name="lSchlTypes{{$key}}"  id="lSchlTypes{{$key}}" class="form-control">
                                                        <option value="">School Type</option>
                                                        @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                            <option <?php if($lSchlType==$nType){ echo 'selected'; } ?> value="{{$nType}}">{{$sTypeName}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="sSchlName{{$key}}" id="sSchlName{{$key}}" value="{{ $se['sSchlName'] }}"  onkeypress=" return IsSchool(event, this.value, '50')">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="sSbrbName{{$key}}" id="sSbrbName{{$key}}" value="{{ $se['sSbrbName'] }}"  onkeypress=" return IsAlpha(event, this.value, '30')">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control"  name="sPinCode{{$key}}" id="sPinCode{{$key}}" value="{{ $se['sPinCode'] }}"  onkeypress=" return IsNumber(event, this.value, '4')">
                                                </td>
                                            </tr>
                                        <?php } } 
                                        else{ ?>
                                            <tr id="Row_1" class="addMore">
                                                <td>
                                                    <select name="lSchlTypes1" id="lSchlTypes1" class="form-control">
                                                        <option value="">School Type</option>
                                                        @foreach(config('constant.SCHL_TYPE') as $sTypeName => $nType)
                                                            <option  value="{{$nType}}">{{$sTypeName}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="sSchlName1" id="sSchlName1" class="form-control" onkeypress=" return IsSchool(event, this.value, '50')">
                                                </td>
                                                <td>
                                                    <input type="text" name="sSbrbName1" id="sSbrbName1" class="form-control"" onkeypress=" return IsAlpha(event, this.value, '30')">
                                                </td>
                                                <td>
                                                    <input type="text" name="sPinCode{{$i}}" id="sPinCode1" class="form-control"  onkeypress=" return IsNumber(event, this.value, '4')">
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div>
                <input type="hidden" name="nTtlRecs" id="nTtlRecs" value="1">
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-lg-12 services-btns"> 
                            <div class="row">
                                <div class="col-lg-6">
                                    <button class="btn btn-danger mt-0" data-dismiss="modal" aria-label="Close">Cancel</button>
                                </div>
                                <div class="col-lg-6">
                                    <button title="Add Category" type="submit" name="submit" class="btn btn-primary mt-0">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>