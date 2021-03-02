@include('admin_panel.layouts.header')
 
<style type="text/css">
.chld_name{
    border-right: #b7b7b7 solid 1px;
    width: 55%;
}
td{
    font-weight: bold;
    font-size: 16px !important;
} 
samp{
    font-weight:normal; font-size:16px;
}
#btnPrint{
    font-family: 'robotoregular';
    font-size: 14px;
    color: #ffffff;
    font-weight: 400;
    background-color: #003366;
    border: 1px #003366 solid;
    border-radius: 4px;
    width: 120px;
    margin-top: 23px;
    padding: 7px;
    cursor: pointer;
}
@media print
{
.noprint {display:none;} 
.printThis{width:80mm !important;}
}

</style>
    <div class="page-container animsition">
        <div id="dashboardPage"> 
            <div class="noprint">
                <!-- Main Menu -->
                @include('admin_panel.layouts.top_bar')
                <!-- Main Menu -->
                @include('milkbar_panel.layouts.side_panel') 
            </div>
            <main >
                <div class="page-breadcrumb" id="printableArea1">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="page-title">Order Tickets</h4>
                        </div>
                    </div>
                    <div class="container-fluid" id="OrderTicket">
                        <div class="row">
                            @foreach($aTcktDtl as $aRec)
                            <div class="col-sm-6 col-lg-6 pl-0">
                                <div class="order-box">
                                    <table width="100%">
                                        <tr>
                                            <td width="50%" align="left">Order ID: {{$aRec['sOrdr_Id']}}</td>
                                            @if($aRec['nOrder_Type'] == config('constant.ORD_TYPE.PICKUP'))
                                                <td width="50%" align="right">Pickup Time: {{date('h:i A', strtotime($aRec['sPic_Tm']))}}</td>
                                            @endif
                                        </tr>
                                    </table>
                                    <div class="order-details-ids"> 
                                        <table class="order-details-ids-1" style="width: 100%">
                                            <tr>
                                                @if($aRec['nUser_Type'] == config('constant.USER.PARENT'))
                                                    <td class="text-left">Pick Up - Parent Order</td>
                                                @endif
                                                @if($aRec['nUser_Type'] == config('constant.USER.TEACHER'))
                                                    @if($aRec['nOrder_Type'] == config('constant.ORD_TYPE.PICKUP'))
                                                        <td class="text-left">Pick Up - Teacher Order</td>
                                                    @else
                                                        <td class="text-left">Delivery - Teacher Order</td>
                                                    @endif
                                                @endif
                                                @if($aRec['nUser_Type'] == config('constant.USER.CHILD'))
                                                    @if($aRec['nOrder_Type'] == config('constant.ORD_TYPE.PICKUP'))
                                                        <td class="text-left">Pick Up - Child Order</td>
                                                    @else
                                                        <td class="text-left">{{$aRec['sSchl_Name']}}</td>
                                                    @endif
                                                @endif
                                                <td class="text-left">Order Date : {{date('d M, Y', strtotime($aRec['sDelv_Date']))}}</td>
                                            </tr>
                                            <tr>
                                                @php
                                                if($aRec['nUser_Type'] == config('constant.USER.TEACHER'))
                                                {
                                                    $sUserName = $aRec['sTchr_FName'].' '.$aRec['sTchr_LName'];
                                                }
                                                else if($aRec['nUser_Type'] == config('constant.USER.CHILD'))
                                                {
                                                    $sUserName = $aRec['sChld_FName'].' '.$aRec['sChld_LName'];
                                                }
                                                else
                                                {
                                                    $sUserName = $aRec['sPrnt_FName'].' '.$aRec['sPrnt_LName'];
                                                }
                                                @endphp
                                                <td class="text-left chld_name">{{$sUserName}}</td>

                                                @if($aRec['nOrder_Type'] == config('constant.ORD_TYPE.PICKUP'))
                                                    <td class="text-left">{{$aRec['nOrd_Otp']}}</td>
                                                @else
                                                    @if($aRec['nUser_Type'] == config('constant.USER.TEACHER'))
                                                        <td class="text-left">School Reception</td>
                                                    @else
                                                        <td class="text-left">{{$aRec['sCls_Name']}}</td>
                                                    @endif
                                                @endif
                                            </tr>
                                        </table>
                                        @php
                                        $sItmDtl = '';
                                        $sVrntName = '';
                                        $aOrdDtl = \App\Model\OrderDetail::Select('sItem_Name','nItm_Qty','sItem_Vrnt')->leftjoin('mst_item', 'mst_item.lItem_IdNo', '=', 'mst_ordr_dt.lItm_IdNo')->where('lOrdr_Hd_IdNo', $aRec['lOrder_IdNo'])->get()->toArray();
                                        @endphp
                                        <table style="width: 100%" class="order-details-ids-3">
                                            <tr>
                                                <td class="text-left" style="width:80%; border-right:1px solid black;" valign="top">
                                                    @php
                                                    foreach($aOrdDtl as $aRes)
                                                    {
                                                        $sItemVrnt=json_decode($aRes['sItem_Vrnt']);
                                                        $sVrntName = '';
                                                        if(!empty($sItemVrnt))
                                                        {
                                                            foreach($sItemVrnt as $nKey => $aVrnt)
                                                            {
                                                                foreach($aVrnt as $lVrntIdNo)
                                                                {
                                                                    $sVrntDtl = \App\Model\VariantItem::Where('IVar_Item_IdNo',$lVrntIdNo)->first()->toArray();
                                                                    $sVrntName .= $sVrntDtl['sItem_name'].', ';
                                                                }
                                                            }
                                                        }
                                                        $sVrntName = substr($sVrntName, 0, -2);
                                                        if(!empty($sVrntName))
                                                        {
                                                            echo $aRes['nItm_Qty'] .' X '.$aRes['sItem_Name'];
                                                            echo " <samp>($sVrntName)</samp>, ";
                                                        }
                                                        else
                                                        {
                                                            echo $aRes['nItm_Qty'] .' X '.$aRes['sItem_Name'].', ';
                                                        }
                                                    }
                                                    @endphp
                                                </td>
                                                <td class="text-left" style="width:20%"   valign="middle">{!! QrCode::size(100)->generate($aRec['sOrdr_Id']); !!}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="row printThis" id="printableArea" style="display:none">
                            <div class="col-12">
                                <h4 class="page-title">Order Tickets</h4>
                            </div>
                            @foreach($aTcktDtl as $aRec)
                            <div class="col-sm-12 col-lg-12 pl-0">
                                <div class="order-box">
                                    <table width="100%">
                                        <tr>
                                            <td width="50%" align="left">Order ID: {{$aRec['sOrdr_Id']}}</td>
                                            @if($aRec['nOrder_Type'] == config('constant.ORD_TYPE.PICKUP'))
                                                <td width="50%" align="right">Pickup Time: {{date('h:i A', strtotime($aRec['sPic_Tm']))}}</td>
                                            @endif
                                        </tr>
                                    </table>
                                    <div class="order-details-ids">
                                        <table class="order-details-ids-1" style="width: 100%">
                                            <tr>
                                                @if($aRec['nUser_Type'] == config('constant.USER.PARENT'))
                                                    <td class="text-left">Pick Up - Parent Order</td>
                                                @endif
                                                @if($aRec['nUser_Type'] == config('constant.USER.TEACHER'))
                                                    @if($aRec['nOrder_Type'] == config('constant.ORD_TYPE.PICKUP'))
                                                        <td class="text-left">Pick Up - Teacher Order</td>
                                                    @else
                                                        <td class="text-left">Delivery - Teacher Order</td>
                                                    @endif
                                                @endif
                                                @if($aRec['nUser_Type'] == config('constant.USER.CHILD'))
                                                    @if($aRec['nOrder_Type'] == config('constant.ORD_TYPE.PICKUP'))
                                                        <td class="text-left">Pick Up - Child Order</td>
                                                    @else
                                                        <td class="text-left">{{$aRec['sSchl_Name']}}</td>
                                                    @endif
                                                @endif
                                                <td class="text-left">Order Date : {{date('d M, Y', strtotime($aRec['sDelv_Date']))}}</td>
                                            </tr>
                                            <tr>
                                                @php
                                                if($aRec['nUser_Type'] == config('constant.USER.TEACHER'))
                                                {
                                                    $sUserName = $aRec['sTchr_FName'].' '.$aRec['sTchr_LName'];
                                                }
                                                else if($aRec['nUser_Type'] == config('constant.USER.CHILD'))
                                                {
                                                    $sUserName = $aRec['sChld_FName'].' '.$aRec['sChld_LName'];
                                                }
                                                else
                                                {
                                                    $sUserName = $aRec['sPrnt_FName'].' '.$aRec['sPrnt_LName'];
                                                }
                                                @endphp
                                                <td class="text-left chld_name">{{$sUserName}}</td>

                                                @if($aRec['nOrder_Type'] == config('constant.ORD_TYPE.PICKUP'))
                                                    <td class="text-left">{{$aRec['nOrd_Otp']}}</td>
                                                @else
                                                    @if($aRec['nUser_Type'] == config('constant.USER.TEACHER'))
                                                        <td class="text-left">School Reception</td>
                                                    @else
                                                        <td class="text-left">{{$aRec['sCls_Name']}}</td>
                                                    @endif
                                                @endif
                                            </tr>
                                        </table>
                                         @php
                                        $sItmDtl = '';
                                        $sVrntName = '';
                                        $aOrdDtl = \App\Model\OrderDetail::Select('sItem_Name','nItm_Qty','sItem_Vrnt')->leftjoin('mst_item', 'mst_item.lItem_IdNo', '=', 'mst_ordr_dt.lItm_IdNo')->where('lOrdr_Hd_IdNo', $aRec['lOrder_IdNo'])->get()->toArray();
                                        @endphp
                                        <table style="width: 100%" class="order-details-ids-3">
                                            <tr>
                                                <td class="text-left" style="width:80%; border-right:1px solid black;" valign="top">
                                                    @php
                                                    foreach($aOrdDtl as $aRes)
                                                    {
                                                        $sItemVrnt=json_decode($aRes['sItem_Vrnt']);
                                                        $sVrntName = '';
                                                        if(!empty($sItemVrnt))
                                                        {
                                                            foreach($sItemVrnt as $nKey => $aVrnt)
                                                            {
                                                                foreach($aVrnt as $lVrntIdNo)
                                                                {
                                                                    $sVrntDtl = \App\Model\VariantItem::Where('IVar_Item_IdNo',$lVrntIdNo)->first()->toArray();
                                                                    $sVrntName .= $sVrntDtl['sItem_name'].', ';
                                                                }
                                                            }
                                                        }
                                                        $sVrntName = substr($sVrntName, 0, -2);
                                                        if(!empty($sVrntName))
                                                        {
                                                            echo $aRes['nItm_Qty'] .' X '.$aRes['sItem_Name'];
                                                            echo " <samp>($sVrntName)</samp>, ";
                                                        }
                                                        else
                                                        {
                                                            echo $aRes['nItm_Qty'] .' X '.$aRes['sItem_Name'].', ';
                                                        }
                                                    }
                                                    @endphp
                                                </td>
                                                <td class="text-left" style="width:20%"   valign="middle">{!! QrCode::size(100)->generate($aRec['sOrdr_Id']); !!}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </main>
            <div class="row noprint"> 
            <div class="col-lg-3 services-btns">
            </div>
                <div class="col-lg-3 services-btns">
                    <ul class="m-auto text-center pt-4 pb-4">
                        <li>
                            <div class="add-btn  mt-0"><button title="Download PDF" type="button" id="btnExport" class="mt-0">Download PDF </button></div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 services-btns">
                    <ul class="m-auto text-center pt-4 pb-4">
                        <li>
                            <div class="add-btn  mt-0"><button title="Print PDF" type="button"  onclick="printDiv('printableArea')" id="btnPrint" class="mt-0">Thermal Print </button></div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 services-btns">
                    <ul class="m-auto text-center pt-4 pb-4">
                        <li>
                            <div class="add-btn  mt-0"><button title="Print PDF" type="button"  onclick="printDiv('printableArea1')" id="btnPrint" class="mt-0">Normal Print </button></div>
                        </li>
                    </ul>
                </div>
            </div> 
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript">  
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
$("#btnExport").on("click", function () {
    html2canvas($('#OrderTicket')[0], {
        onrendered: function (canvas) {
            var data = canvas.toDataURL();
            var docDefinition = {
                content: [{
                    image: data,
                    width: 500
                }]
            };
            pdfMake.createPdf(docDefinition).download("Order_Tickets.pdf");
        }
    });
});
</script>