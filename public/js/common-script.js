function GetState(lCntryIdNo, lStateIdNo = '') {
     var lStateIdNo = $("#lStateIdNoHid").val();
    $('#loadingBox').removeClass('d-none');
    $.ajax({
        url: APP_URL + "/get_state?lCntryIdNo=" + btoa(lCntryIdNo),
        success: function (response) {
            var sCntryCode = $('option:selected', '#lCntryIdNo').data("code");
            $('.cnoutry_code').val(sCntryCode);
            $('#lStateIdNo').find('option').remove();
            $('#lStateIdNo').append(`<option value="">== Select State ==</option>`);
            StateList = JSON.parse(response);
            StateList.forEach(function (StateList) {
                var lStateIdNo  = StateList['lState_IdNo'];
                var sStateName  = StateList['sState_Name'];
                var nAreaCode   = StateList['nArea_Code'];
                $('#lStateIdNo').append(`<option value="${lStateIdNo}" data-code="${nAreaCode}">${sStateName}</option>`);
            });
            if (lStateIdNo != '') {
                $('#lStateIdNo option[value=' + lStateIdNo + ']').attr('selected', 'selected');
            }
            $('#loadingBox').addClass('d-none');
        }
    });
}

$(document).ready(function () {
    $("#lStateIdNo").change(function (e) {
        var nAreaCode = $(this).find(':selected').data('code');
        $('#sAreaCode').val(nAreaCode);
    });
});

function GetOrder(lOrdrIdNo) {
    var format = new Intl.NumberFormat('en-AU', {
        style: 'currency',
        currency: 'AUD',
        minimumFractionDigits: 2,
    });
    $('#loadingBox').removeClass('d-none');
    $.ajax({
        url: APP_URL + "/order/detail?lOrdrHdIdNo=" + lOrdrIdNo,
        success: function (response) {
            
            aRspns = JSON.parse(response);
            console.log(response);
            if (aRspns['oOrdrDtl']['nOrdr_Status'] != 211) {
                var sOthrDt = aRspns['oOrdrDtl']['nOrdr_Status'] == 212 ? aRspns['oOrdrDtl']['sDelvrd_Date'] : aRspns['oOrdrDtl']['sCncl_Date'];
                sDateStr1 = sOthrDt.split(" ");
                sDate1 = sDateStr1[0].split("-");
                sNewDate1 = sDate1[2] + '-' + sDate1[1] + '-' + sDate1[0] + ' ' + sDateStr1[1];
                if (aRspns['oOrdrDtl']['nOrdr_Status'] == 212) {
                    $('#sOthrDtTm').html("Delivered Dated: " + sNewDate1);
                }
                else {
                    $('#sOthrDtTm').html("Cancellation Date: " + sNewDate1);
                }
            }
            sDateStr = aRspns['oOrdrDtl']['sCrt_DtTm'].split(" ");
            sDate = sDateStr[0].split("-");
            sNewDate = sDate[2] + '-' + sDate[1] + '-' + sDate[0] + ' ' + sDateStr[1];

            sDate2 = aRspns['oOrdrDtl']['sDelv_Date'].split("-");
            sNewDate2 = sDate2[2] + '-' + sDate2[1] + '-' + sDate2[0];
            $('#sDelvDtTm').html("Delivery Date: " + sNewDate2);
            if (aRspns['aSchlDtl']['lSchl_Type'] == 501) {
                sSchlType = 'Child Care';
            }
            else if (aRspns['aSchlDtl']['lSchl_Type'] == 501) {
                sSchlType = 'Kinder';
            }
            else if (aRspns['aSchlDtl']['lSchl_Type'] == 501) {
                sSchlType = 'Primary';
            }
            else {
                sSchlType = 'Secondary';
            }
            $('#sOrdrIdNo').html("Order ID: " + aRspns['oOrdrDtl']['sOrdr_Id']);
            $('#sCrtDtTm').html("Date: " + sNewDate);
            $('#sBussName').html(aRspns['oOrdrDtl']['sBuss_Name']);
            $('#sMlkAdrs').html(aRspns['oOrdrDtl']['sStrt_No'] + " " + aRspns['oOrdrDtl']['sStrt_Name'] + ", " + aRspns['oOrdrDtl']['sSbrb_Name'] + ", " + aRspns['oOrdrDtl']['sCntry_Name'] + ", " + aRspns['oOrdrDtl']['sState_Name'] + ", " + aRspns['oOrdrDtl']['sPin_Code']);
            $('#sMlkPhnNo').html(aRspns['oOrdrDtl']['sPhone_No']);
            $('#sMlkEmail').html(aRspns['oOrdrDtl']['sEmail_Id']);

            if (aRspns['oOrdrDtl']['nUser_Type'] == 804) {
                $('#sChldName').html(aRspns['oOrdrDtl']['sFrst_Name'] + ' ' + aRspns['oOrdrDtl']['sLst_Name'] + ' (' + aRspns['oOrdrDtl']['sCls_Name'] + ')');
            }
            else {
                $('#sChldName').html(aRspns['oOrdrDtl']['sFrst_Name'] + ' ' + aRspns['oOrdrDtl']['sLst_Name']);
            }
            $('#sSchlAdrs').html(aRspns['aSchlDtl']['sSchl_Name'] + ' (' + sSchlType + ')');
            $('#sSchlPhnNo').html(aRspns['oOrdrDtl']['sCntry_Code'] + ' ' + aRspns['oOrdrDtl']['sMobile_No']);
            $('#sSchlEmail').html(aRspns['oOrdrDtl']['sEmail_Id']);
            var i = 1;
            var content = "";
            var total = 0;
            aRspns['oOrdrItms'].forEach(function (oOrdrItm) {
                content = content + "<tr><td>" + i + "</td><td>" + oOrdrItm['sItem_Name'] + "</td><td>" + oOrdrItm['nItm_Qty'] + "</td><td class='text-right'>" + format.format(parseFloat(oOrdrItm['sItem_Prc']).toFixed(2)) + "</td><td class='text-right'>" + format.format(parseFloat(oOrdrItm['sItem_Prc'] * oOrdrItm['nItm_Qty']).toFixed(2)) + "</td></tr>";
                i = i + 1;
                total = total + (oOrdrItm['sItem_Prc'] * oOrdrItm['nItm_Qty']);
            });
            $('#aItms').html(content);

            $('#sSubTtl').html(format.format(parseFloat(total).toFixed(2)));
            var sCrdt = aRspns['OrdrAmnt'] == null ? '0.00' : parseFloat(aRspns['OrdrAmnt']['sTtl_Amo']).toFixed(2);
            $('#sGst').html(format.format(parseFloat(total * 0.10).toFixed(2)));
            $('#sSubTtlnew').html(format.format(parseFloat(total + (total * 0.10)).toFixed(2)));
            $('#sCrdt').html('-' + format.format(sCrdt));
            var sPay = (total + total * 0.10) - sCrdt;
            $('#sPay').html(format.format(parseFloat(sPay).toFixed(2)));
            $('#loadingBox').addClass('d-none');
            $('#OrderDtlModel').modal();
        }
    });
}


$(document).ready(function () {

});
function GetSchlLst(num, lSchlType) {
    $('#loadingBox').removeClass('d-none');
    $.ajax({
        url: APP_URL + "/get_school/list?lSchlType=" + btoa(lSchlType),
        success: function (response) {
            $('input[name="sSbrbName' + num + '"]').val('');
            $('input[name="sPinCode' + num + '"]').val('');
            $('input[name="dDistKm' + num + '"]').val('');
            $('input[name="sCutTm' + num + '"]').val('');
            $('#lSchlIdNo' + num + '').find('option').remove();
            $('#lSchlIdNo' + num + '').append(`<option value="">Select School Name</option>`);
            SchlList = JSON.parse(response);
            SchlList.forEach(function (SchlList) {
                var lSchlIdNo = SchlList['lSchl_IdNo'];
                var sSchlName = SchlList['sSchl_Name'];
                var sSbrbName = SchlList['sSbrb_Name'];
                var sPinCode = SchlList['sPin_Code'];
                $('#lSchlIdNo' + num + '').append(`<option data-subrb="${sSbrbName}" data-pin="${sPinCode}" value="${lSchlIdNo}">${sSchlName}</option>`);
            });
            $('#loadingBox').addClass('d-none');
        }
    });
}

function ChngDtl(num) {
    var sSbrbName = $('select[name="lSchlIdNo' + num + '"] option:selected').data('subrb');
    var sPinCode = $('select[name="lSchlIdNo' + num + '"] option:selected').data('pin');
    $('#sSbrbName' + num + '').val(sSbrbName);
    $('#sPinCode' + num + '').val(sPinCode);
}

function ShowPass(id) {
    var x = document.getElementById(id);
    if (x.type === "password") {
        x.type = "text";
        $('.' + id + '').addClass('fa-eye').removeClass('fa-eye-slash');
    }
    else {
        x.type = "password";
        $('.' + id + '').removeClass('fa-eye').addClass('fa-eye-slash');
    }
}


$(document).ready(function () {
    $("#EdtBtn").click(function () {
        $('input').attr("readonly", false);
        $('select').attr("disabled", false);
        $('.cnoutry_code').attr("readonly", true);
        $('#sAreaCode').attr("readonly", true);
        $('#SubmitBtn').removeClass('d-none');
        $('.TchrSchool').removeClass('d-none');
        $('.btn-warning').removeClass('d-none');
        $('.fa-plus').removeClass('d-none');
        $('.fa-minus').removeClass('d-none');
        $('#SubmitBtn').removeClass('d-none');
        $(this).addClass('d-none');
    });
});

$('#ClrFltr').on('click', function () {
    var currentURL = location.protocol + '//' + location.host + location.pathname;
    window.location = currentURL;
});

function ChkName() {
    var sFrstName = $("input[name=sFrstName]").val();
    var sLstName = $("input[name=sLstName]").val();
    if (sFrstName == sLstName && sFrstName != '' && sLstName != '') {
        $('#ErrName').html("First name and surname should not be same");
        $("input[name=sLstName]").val('');
        $("input[name=sLstName]").focus();
    }
}

function empty(val) {
    if ($("#addSchool" + val).prop("checked") == true) {
        $("#lSchlTypes" + val).attr("required", true).removeAttr('disabled');
        $("#sSchlName" + val).attr("required", true).removeAttr('readonly');
        $("#sSbrbName" + val).attr("required", true).removeAttr('readonly');
        $("#sPinCode" + val).attr("required", true).removeAttr('readonly');
    }
    else if ($("#addSchool" + val).prop("checked") == false) {
        $("#lSchlTypes" + val).removeAttr("required").val('').attr('disabled', true);
        $("#sSchlName" + val).removeAttr("required").val('').attr('readonly', true);
        $("#sSbrbName" + val).removeAttr("required").val('').attr('readonly', true);
        $("#sPinCode" + val).removeAttr("required").val('').attr('readonly', true);
    }
}

$(document).ready(function () {
    $("#request_parent").on("submit", function () {
        $('#loadingBox').removeClass('d-none');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('input[name="csrf-token"]').attr('value')
            },
            url: $(this).attr("action"),
            type: "POST",
            data: $(this).serialize(),
            success: function (res) 
            {
                response = JSON.parse(res);
                $('#loadingBox').addClass('d-none');
                $('#ParentSchool').modal('hide');
                if(response.Status) 
                {
                    $('.Js_msg').removeClass('d-none alert-danger').addClass('alert alert-success').html(response.Message);
                }
                else
                {
                    $('.Js_msg').removeClass('d-none alert-success').addClass('alert alert-danger').html(response.Message);
                }
            }
        });
        return false;
    });
});

$(document).ready(function () {
    $("#request_milk").on("submit", function () {
        $('#loadingBox').removeClass('d-none');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('input[name="csrf-token"]').attr('value')
            },
            url: $(this).attr("action"),
            type: "POST",
            data: $(this).serialize(),
            success: function (res) 
            {
                response = JSON.parse(res);
                $('#loadingBox').addClass('d-none');
                $('#MilkSchool').modal('hide');
                if(response.Status) 
                {
                    $('.Js_msg').removeClass('d-none alert-danger').addClass('alert alert-success').html(response.Message);
                }
                else
                {
                    $('.Js_msg').removeClass('d-none alert-success').addClass('alert alert-danger').html(response.Message);
                }
            }
        });
        return false;
    });
});

$(document).ready(function () {
    $("#request_teacher").on("submit", function () {
        $('#loadingBox').removeClass('d-none');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('input[name="csrf-token"]').attr('value')
            },
            url: $(this).attr("action"),
            type: "POST",
            data: $(this).serialize(),
            success: function (res) 
            {
                response = JSON.parse(res);
                $('#loadingBox').addClass('d-none');
                $('#TchrSchool').modal('hide');
                if(response.Status) 
                {
                    $('.Js_msg').removeClass('d-none alert-danger').addClass('alert alert-success').html(response.Message);
                }
                else
                {
                    $('.Js_msg').removeClass('d-none alert-success').addClass('alert alert-danger').html(response.Message);
                }
            }
        });
        return false;
    });
});

$(document).on("click", ".remove_item", function () {
    var nKey = $(this).data('key');
    $('#loadingBox').removeClass('d-none');
    $.ajax({
        url: APP_URL + "/cart_item/remove?nKey=" + btoa(nKey),
        success: function (response) {
            GetCartDtl();
            $('#loadingBox').addClass('d-none');
        }
    });
});

function GetCartDtl()
{
    $.ajax({
        url: APP_URL + "/get_cart/data",
        success: function (response) {
            $('#CartData').html(response);
        }
    });
}