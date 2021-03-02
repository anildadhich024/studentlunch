@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('parent_panel.layouts.side_panel')
            <main>
                <!-- My Commissions From -->
                @include('admin_panel.layouts.message')
                    <div class="page-breadcrumb">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="page-title">My Order</h4>
                            </div>
                        </div>
                    </div>
					<div class="container-fluid card-commission-section my-form my-credits-section">
						<form action="{{url('parent_panel/place_order/save')}}" method="post" id="general_form">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="row first-block">
								<div class='col-6 col-lg-2 col-md-6 pb-2 from-boxes'>
									<label>Date</label>
									<input type="date" id="sDtTm" name="sDtTm"  min="{{ !empty($aCart) ? $aCart['sDelvDate'] : '' }}" required>
								</div>
								<div class='col-6 col-lg-2 col-md-6 pb-2 from-boxes'>
									<label>Student Name</label>
									<select name="lChldIdNo" id="lChldIdNo" required style="padding: 10px 12px;">
									<option value="">Child Name</option>
										@foreach($aChldLst as $aChld)
											<option "@if(!empty($aCart)) @if($aCart['lChldIdNo'] == $aChld['lChld_IdNo']) selected @endif @endif" data-schoolId='@if($aChld["nBlk_UnBlk"] == config("constant.STATUS.UNBLOCK")){{$aChld["lSchl_IdNo"]}}@else 0 @endif' data-school='{{$aChld["sSchl_Name"]}}' value='{{$aChld["lChld_IdNo"]}}'>{{$aChld['sFrst_Name']}} {{$aChld['sLst_Name']}}</option>
										@endforeach
									</select>
								</div>
								<div class='col-6 col-lg-3 col-md-6 pb-3 from-boxes'>
									<label>Student School Name</label>
									<input type="text" name="sSchlName" id="sSchlName" required readonly> 
								</div>
								<div class='col-6 col-lg-3 col-md-6 pb-3 from-boxes'>
									<label>Select Service Provider</label>
									<select name="lMilkIdNo" id="lMilkIdNo" data-milk="{{!empty($aCart) ? $aCart['lMilkIdNo'] : ''}}" required style="padding: 10px 12px;">
										<option value="">Service Provider</option>
									</select>
								</div>
								<div class='col-6 col-lg-2 col-md-6 pb-2 from-boxes'>
									<label>Available Credits</label>
									<input type="text" name="sCrdtsAvlbl" id="sCrdtsAvlbl" required readonly class="text-right"> 
								</div>
							</div>
							<!-- Commssions Details Tabel -->
							<div class="row pt-4">
		                        <div class="col-sm-8 col-lg-8 commssions-table-details table-responsive lunch-order-form">
		                            <table id="orderTable" class=" tablescroll parentPlaceOrderTable"
		                                style="border: 1px solid #3d3e3e;">
		                                <thead>
		                                    <tr>
		                                        <th class="nowordwrap">Item ID</th>
		                                        <th class="nowordwrap">Category</th>
		                                        <th class="nowordwrap">Item Name</th>
		                                        <th class="text-right nowordwrap" style="width: 80px;">Price</th>
		                                        <th class="nowordwrap"></th>
		                                    </tr>
		                                </thead>
		                                <tbody>

		                                </tbody>
		                            </table>
		                        </div>
		                        <div class="col-sm-4 col-lg-4 pt-2">
		                            <div class="sidebartotal ">
		                                <div class="topfix">
		                                    <h5 class="modal-title sidecarttitle">
		                                        Your Cart
		                                    </h5>
		                                </div>
		                                <div id="CartData">
		                                    @if(!empty(session('CART_ITEMS')))
		                                        <div class="innercontent">
		                                        @php
		                                            $sTtlAmo = 0;
		                                        @endphp
		                                        @foreach(session('CART_ITEMS') as $nKey => $aCrtData)
		                                            @php
		                                                $aGetItm = \App\Model\Item::Select('sItem_Name')->Where('lItem_IdNo',$aCrtData['lItemIdNo'])->first()->toArray();
		                                                $sTtlAmo += $aCrtData['sItmPrc'] * $aCrtData['nItmQty'];
		                                            @endphp
		                                            <div class="singleproduct btn1px">
		                                                <div class="d-flex justify-content-between ">
		                                                    <div class="head">
		                                                        <span>{{$aCrtData['nItmQty']}} X </span>
		                                                        {{$aGetItm['sItem_Name']}}
		                                                    </div>
		                                                    <div class="singleamount d-flex">
		                                                        <span class="pr-2">${{number_format($aCrtData['sItmPrc'] * $aCrtData['nItmQty'], 2)}}</span>
		                                                        <div class="deletesingleamount">
		                                                            <svg width="20" height="20" class="_1liuvga remove_item" viewBox="0 0 20 20"
		                                                                aria-hidden="true" data-key={{$nKey}}>
		                                                                <title>Remove</title>
		                                                                <g fill="none" fill-rule="evenodd" stroke="#4C4C4C"
		                                                                    stroke-width="2" transform="translate(1 1)">
		                                                                    <circle cx="9" cy="9" r="9"></circle>
		                                                                    <g stroke-linecap="round" stroke-linejoin="round">
		                                                                        <path
		                                                                            d="M6.018 12.446l6.428-6.428M6.018 6.018l6.267 6.267">
		                                                                        </path>
		                                                                    </g>
		                                                                </g>
		                                                            </svg>
		                                                        </div>
		                                                    </div>
		                                                </div>
		                                                @if(!empty($aCrtData['sItmVrnt']))
		                                                    @php
		                                                        $aVrntData = json_decode($aCrtData['sItmVrnt']);
		                                                    @endphp
		                                                    @foreach($aVrntData as $nVrntKey => $IVarItemIdNos)
		                                                        @foreach($IVarItemIdNos as $IVarItemIdNo)
		                                                            @php
		                                                            $aVrntOpt   = \App\Model\VariantItem::Select('sItem_name')
		                                    ->Where('IVar_Item_IdNo', $IVarItemIdNo)->first()->toArray();
		                                                            @endphp
		                                                            <div class="extraoptions">
		                                                                + {{$aVrntOpt['sItem_name']}}
		                                                            </div>
		                                                        @endforeach
		                                                    @endforeach
		                                                @endif
		                                            </div>
		                                        @endforeach
		                                        <div class="singletotalline d-flex justify-content-between btn1px">
		                                            <span>Total</span>
		                                            <span>${{number_format($sTtlAmo, 2)}}</span>
		                                        </div>
		                                    </div>
		                                    <div class="addbtncart1">
		                                        <button class="btn addbtngrp1" id="addToCart" type="submit">
		                                            Review Order
		                                        </button>
		                                    </div>
		                                    @endif
		                                </div>
		                            </div>
		                        </div>
		                    </div>
						</form>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
@include('admin_panel.layouts.item_popup')
<script type="text/javascript">
Date.prototype.toDateInputValue = (function() {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0,10);
});
$( document ).ready(function() {
	$('#sDtTm').attr('min', new Date().toDateInputValue());
	var sDelvDate = '{{ isset($aCart['sDelvDate']) ? $aCart['sDelvDate'] : '' }}';
	$('#sDtTm').val(sDelvDate == '' ? '' : sDelvDate);
    var optionSelected = $("option:selected", $('#lChldIdNo'));
	$('#sSchlName').val(optionSelected.data('school'));
	var milk = JSON.stringify(optionSelected.data('milk'));
	var schlId = optionSelected.data('schoolid');
	
	$.ajax({url: "parent_panel/get_milk?schl="+optionSelected.data('schoolid')+"&dttm="+ $('#sDtTm').val(), success: function(result){
		var oMlkBars = JSON.parse(result);
		if(schlId == 0){
			alert("The school has been blocked. Hence no Service Provider available.");
		}else{
			content = '<option value="">Service Provider</option>';
			for(var i in oMlkBars) {
				var oMlkBar = oMlkBars[i];
				if (oMlkBar['yCut_Status'] == 0) 
                    {
                        content += '<option disabled value="' + oMlkBar['lMilk_IdNo'] + '">' +
                            oMlkBar['sBuss_Name'] + '</option>';
                    } 
                    else 
                    {
                        if ($('#lMilkIdNo').data('milk') == oMlkBar['lMilk_IdNo']) 
                        {
                            content += '<option selected value="' + oMlkBar['lMilk_IdNo'] +
                                '">' + oMlkBar['sBuss_Name'] + '</option>';
                        } 
                        else 
                        {
                            content += '<option value="' + oMlkBar['lMilk_IdNo'] + '">' +
                                oMlkBar['sBuss_Name'] + '</option>';
                        }
                    }
			}
			$('#lMilkIdNo').html(content);
			var optionSelected = $("option:selected", $('#lMilkIdNo'));
			$.ajax({url: "get_menu/list?milkbar="+optionSelected.val()+"&nUserType={{config('constant.USER.PARENT')}}", success: function(result){
				var oMenuItems = JSON.parse(result);
				content = '';
				for (var i in oMenuItems) {
                    if (i != 'wallet') {
                        var oMenuItem = oMenuItems[i];
                        content += '<tr><td>' + oMenuItem['lItem_Unq_Id'] +
                            '</td>';
                        content += '<td>' + oMenuItem['sCatg_Name'] +
                            '</td>';
                        content += '<td>' + oMenuItem['sItem_Name'] +
                            '</td>';
                        content += '<input type="hidden" value="' +
                            oMenuItem['sItem_Prc'] + '"/>';
                        if (oMenuItem["nItemQty"] === undefined) {
                            content +=
                                '<input class="total-price" type="hidden" value="0"  />';
                        } else {
                            content +=
                                '<input class="total-price" type="hidden" value="' +
                                oMenuItem['sItem_Prc'] * oMenuItem[
                                    "nItemQty"] + '"  />';
                        }
                        content += '</div> </td>';
                        if (oMenuItem["nItemQty"] === undefined) {
                            content +=
                                '<td class="text-right tot-price">$ ' +
                                parseFloat(oMenuItem['sItem_Prc']).toFixed(
                                    2) + '</td>';
                        } else {
                            content +=
                                '<td class="text-right tot-price">$ <sapn>' +
                                parseFloat(oMenuItem['sItem_Prc'] *
                                    oMenuItem["nItemQty"]).toFixed(2) +
                                '</td>';
                        }
                        nOption = 0;
                        if(oMenuItem['sMenu_Variant'] != null)
                        {
                            nOption = 1;
                        }
                        content +=
                            '<td><div id="field1" class="d-flex flex-row justify-content-around"><button type="button" class="add_cart align-self-center" data-id="'+oMenuItem['lItem_IdNo']+'" data-name="'+oMenuItem['sItem_Name']+'" data-des="'+oMenuItem['sItem_Dscrptn']+'" data-price="'+oMenuItem['sItem_Prc']+'" data-option="'+nOption+'">ADD TO CART</button></div></td></tr>';
                    }
                }
				$('#orderTable tbody').html(content);
				$('#sCrdtsAvlbl').val('$ '+parseFloat(oMenuItems['wallet']).toFixed(2));
				totalOrder();
			}});
		}
	}});
});

$("#sDtTm").on('change', function(event){
	daystatus = false;
	$('#lChldIdNo option').prop('selected', function() {
        return this.defaultSelected;
    });
    $('#sSchlName').val('');
    $('#lMilkIdNo').html('<option value="">Service Provider</option>');
    $('#sCrdtsAvlbl').val('$ 0.00');
    $('#orderTable tbody').html('');
    $('#CartData').html('');
    if($(this).val() < new Date().toDateInputValue()){
        $(this).val(new Date().toDateInputValue());
    }
    var weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
	var a = new Date($(this).val());
	dayname = weekday[a.getDay()];
	if(dayname == 'Saturday')
	{
		daystatus = true;
	}
	if(dayname == 'Sunday')
	{
		daystatus = true;
	}

	if(!daystatus)
	{
	    $.ajax({url: "get_holiday/detail?sDtTm="+$(this).val(), 
	    	success: function(result){
	    		var aData = JSON.parse(result);
	    		if(aData.Status)
	    		{
	    			if(confirm(aData.Message) == false)
	    			{
	    				location.reload();
	    			}
	    		}
			}
		});
	}
	else
	{
		const monthNames = ["January", "February", "March", "April", "May", "June",
		  "July", "August", "September", "October", "November", "December"
		];
		if(confirm(""+a.getDate()+" "+monthNames[a.getMonth()]+", "+a.getFullYear()+" have SCHOOL HOLIDAY, So delivery not avilable. Are you sure to continue ?") == false)
		{
			location.reload();
		}
	}
});

$('#lChldIdNo').on('change', function (event) {
	var optionSelected = $("option:selected", this);
	nOrdStatus = true;
	if(optionSelected.val() != '' && $('#sDtTm').val() != '')
	{
		lChldIdNo = $('#lChldIdNo').val();
		$.ajax({url: "parent_panel/get_order/child?lChldIdNo="+lChldIdNo+"&sDate="+$('#sDtTm').val(), 
			success: function(result) {
				var oOrdDtl = JSON.parse(result);
				if(oOrdDtl.Status)
				{
					if(confirm(oOrdDtl.Message) == true)
					{
						nOrdStatus = true;
					}
					else
					{
						nOrdStatus = false;
					}
				}

				if(nOrdStatus)
				{
					$('#sSchlName').val(optionSelected.data('school'));
					$.ajax({url: "parent_panel/get_milk?schl="+optionSelected.data('schoolid')+"&dttm="+ $('#sDtTm').val(), success: function(result){
						var oMlkBars = JSON.parse(result);
						if(optionSelected.data('schoolid') == 0){
							alert("The school has been blocked. Hence no Service Provider available.");
						}else{
							content = '<option value="">Service Provider</option>';
							for(var i in oMlkBars) {
								var oMlkBar = oMlkBars[i];
								if (oMlkBar['yCut_Status'] == 0) {
			                        content += '<option disabled value="' + oMlkBar['lMilk_IdNo'] +
			                            '">' + oMlkBar['sBuss_Name'] + '</option>';
			                    } else {
			                        content += '<option value="' + oMlkBar['lMilk_IdNo'] + '">' +
			                            oMlkBar['sBuss_Name'] + '</option>';
			                    }
							}
							$('#lMilkIdNo').html(content);
						}
						$('#orderTable tbody').html('');
					    totalOrder();
					}});
				}
				else
				{
					location.reload();
				}
			}
		});
	}
	else{
		$('#lMilkIdNo').html('<option value="">Service Provider</option>');
		$('#orderTable tbody').html('');
		totalOrder();
	}
});

$('#lMilkIdNo').on('change', function (event) {
	var optionSelected = $("option:selected", this);
	$.ajax({url: "get_menu/list?milkbar="+optionSelected.val()+"&nUserType={{config('constant.USER.PARENT')}}", success: function(result){
		var oMenuItems = JSON.parse(result);
		content = '';
		for (var i in oMenuItems) {
            if (i != 'wallet') {
                var oMenuItem = oMenuItems[i];
                content += '<tr><td>' + oMenuItem['lItem_Unq_Id'] + '</td>';
                content += ' <td>' + oMenuItem['sCatg_Name'] + '</td>';
                content += '<td>' + oMenuItem['sItem_Name'] +'</td>';
                content += '<input type="hidden" value="' + oMenuItem['sItem_Prc'] +
                    '" min="1" max="100"  />';
                content +=
                    '<input class="total-price" type="hidden" value="0" min="1" max="100"  />';
                content += '</div> </td>';
                content += '<td class="text-right tot-price">$ ' + parseFloat(oMenuItem[
                    'sItem_Prc']).toFixed(2) + '</td>';
                nOption = 0;
                if(oMenuItem['sMenu_Variant'] != null)
                {
                    nOption = 1;
                }
                content +=
                    '<td><div id="field1" class="d-flex flex-row justify-content-around"><button type="button" class="add_cart align-self-center" data-id="'+oMenuItem['lItem_IdNo']+'" data-name="'+oMenuItem['sItem_Name']+'" data-des="'+oMenuItem['sItem_Dscrptn']+'" data-price="'+oMenuItem['sItem_Prc']+'" data-option="'+nOption+'">ADD TO CART</button></div></td></tr>';
            }
        }
		$('#orderTable tbody').html(content);
		$('#sCrdtsAvlbl').val('$ '+parseFloat(oMenuItems['wallet']).toFixed(2));
		totalOrder();
	}});
});  
$('#orderTable tbody').on('click', '.add', function() {
	$(this).closest('tr').find(".checkbox").prop("checked", true);
	if ($(this).prev().val() < 10) {
		$(this).prev().val(+$(this).prev().val() + 1);
		if($(this).prev().prev().hasClass('d-none')){
			$(this).prev().prev().removeClass('d-none');
		}
		var nItemPrice = $(this).next().val();
		var lItemQty = $(this).prev().val();
		$(this).next().next().val(+(nItemPrice*lItemQty).toFixed(2));
		$(this).closest('td').next().html((nItemPrice*lItemQty).toFixed(2));
		totalOrder();
	}
});

$('#orderTable tbody').on('click', '.sub', function() {
	if ($(this).next().val() > 1) {
		if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);
		if($(this).next().val() == 1){
			 
		}
		var nItemPrice = $(this).next().next().next().val();
		var lItemQty = $(this).next().val();
		$(this).next().next().next().next().val(+(nItemPrice*lItemQty).toFixed(2));
		$(this).closest('td').next().html((nItemPrice*lItemQty).toFixed(2));
		totalOrder();
	}
});

$('#orderTable tbody').on('click', '.checkbox', function() {
	if($(this).is(':checked')){
		$(this).closest('tr').find(".lItemQty").next().next().next().val($(this).closest('tr').find(".lItemQty").next().next().val());
		$(this).closest('tr').find(".lItemQty").val("1");
	}else {
		$(this).closest('tr').find(".lItemQty").val("0");
		$(this).closest('tr').find(".lItemQty").next().next().next().val('0');
		$(this).closest('tr').find(".tot-price").html($(this).closest('tr').find(".lItemQty").next().next().val());
	}
	totalOrder();
});

function totalOrder(){
	var sum = 0;
	$(".total-price").each(function(){
		sum += parseFloat($(this).val());
	});
	if(sum == 0){
		$('.add-btn').addClass('d-none');
	}
	else
	{
		$('.add-btn').removeClass('d-none');
	}
	$('.order-total').html("Order Total (Ex. Taxes) : $ "+sum.toFixed(2));
}

</script>