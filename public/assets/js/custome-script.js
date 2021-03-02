function GetAccSchl(lMilkIdNo) {
    $.ajax({
        url: APP_URL + "/milk_bar/grid?lRecIdNo=" + lMilkIdNo,
        success: function (response) {
            $aRecSet = JSON.parse(response);
            CrtGrd($aRecSet['aSchlLst'], $aRecSet['aAccSchl'], $aRecSet['aSchlType']);
        }
    });
}

function GetChld(lPrntIdNo) {
    $.ajax({
        url: APP_URL + "/child/grid?lRecIdNo=" + lPrntIdNo,
        success: function (response) {
            $aRecSet = JSON.parse(response);
            CrtGrd($aRecSet['aSchlLst'], $aRecSet['aChldLst'], $aRecSet['aSchlType']);
        }
    });
}

function GetSchlCntct(lSchlIdNo) {
    $.ajax({
        url: APP_URL + "/admin_panel/school/contact?lRecIdNo=" + lSchlIdNo,
        success: function (response) {
            $aRecSet = JSON.parse(response);
            CrtCntctGrd($aRecSet['aSchlLst']);
        }
    });

    $.ajax({
        url: APP_URL + "/admin_panel/school/supplier?lRecIdNo=" + lSchlIdNo,
        success: function (response) {
            $aRecSet = JSON.parse(response);
            CrtSpplrGrd($aRecSet['aSchlLst'], $aRecSet['aMlkBarLst']);
        }
    });
}

function chngStatus(sTblName, sFldName, lRecIdNo, nBlkUnBlk) {
    if (confirm("Are you sure to change status ? ") == true) {
        window.location = APP_URL + "/record/change_status?sTblName=" + sTblName + "&sFldName=" + sFldName + "&lRecIdNo=" + lRecIdNo + "&nBlkUnBlk=" + nBlkUnBlk;
    }
}

function DelRec(sTblName, sFldName, lRecIdNo) {
    if (confirm("Are you sure to delete this record ? ") == true) {
        window.location = APP_URL + "/record/delete?sTblName=" + sTblName + "&sFldName=" + sFldName + "&lRecIdNo=" + lRecIdNo;
    }
}

function DelVarRec(sTblName, sFldName, lRecIdNo) {
    if (confirm("Are you sure to delete this record ? ") == true) {
        window.location = APP_URL + "/record/variant/delete?sTblName=" + sTblName + "&sFldName=" + sFldName + "&lRecIdNo=" + lRecIdNo;
    }
}

function ActvStatus(lRecIdNo) 
{
    if (confirm("Are you sure active this plan ? ") == true) 
    {
        window.location = APP_URL + "/admin_panel/plan/active?lRecIdNo="+lRecIdNo;
    }
}