@include('admin_panel.layouts.header')
<style type="text/css">
.chld_name{
	border-right: #b7b7b7 solid 1px;
	width: 60%;
}
td{
    font-weight: bold;
    font-size: 18px !important;
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
.order-box{ 
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always; 
}
}
@page {
            size: 'A3';
            margin: 0;
        }


</style>
    <div class="page-container animsition">
        <div id="dashboardPage">  
            <main >
                <div class="page-breadcrumb">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="page-title">Order Tickets</h4>
                        </div>
                    </div>
                    <div class="container-fluid" id="OrderTicket">
                        <div class="row" id="printableArea">
                            @foreach($aTcktDtl as $aRec)
                            <div class="col-sm-12 col-lg-6 pl-0">
                                <div class="order-box">
                                    <h4>Order ID: {{$aRec['sOrdr_Id']}}</h4>
                                    <div class="order-details-ids">
                                        <table class="order-details-ids-1" style="width: 100%">
                                            <tr>
                                                <td class="text-left" colspan="2">{{$aRec['sSchl_Name']}}</td>
                                            </tr>
                                            <tr>
                                            	<td class="text-left chld_name">{{$aRec['sFrst_Name']}} {{$aRec['sLst_Name']}}</td>
                                                <td class="text-left">{{$aRec['sCls_Name']}}</td>
                                            </tr>
                                        </table>
                                        @php
                                        $sItmDtl = '';
                                        $aOrdDtl = \App\Model\OrderDetail::Select('sItem_Name','nItm_Qty')->leftjoin('mst_item', 'mst_item.lItem_IdNo', '=', 'mst_ordr_dt.lItm_IdNo')->where('lOrdr_Hd_IdNo', $aRec['lOrder_IdNo'])->get()->toArray();
                                        foreach($aOrdDtl as $aRec)
                                        {
                                            $sItmDtl .= $aRec['nItm_Qty'] .' X '.$aRec['sItem_Name'].', ';
                                        }
                                        @endphp
                                        <table style="width: 100%" class="order-details-ids-3">
                                            <tr>
                                                <td class="text-left">{{substr($sItmDtl, 0, -2)}}</td>
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
            <div class="row">
                <div class="col-lg-6 services-btns">
                    <ul class="m-auto text-center pt-4 pb-4">
                        <li>
                            <div class="add-btn  mt-0"><button title="Download PDF" type="button" id="btnExport" class="mt-0">Download PDF </button></div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6 services-btns">
                    <ul class="m-auto text-center pt-4 pb-4">
                        <li>
                            <div class="add-btn  mt-0"><button title="Print PDF" type="button"  onclick="printDiv('printableArea')" id="btnPrint" class="mt-0">Print </button></div>
                        </li>
                    </ul>
                </div>
            </div> 
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript">   
$(document).ready(function(){
    $("#btnPrint").click();
});
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