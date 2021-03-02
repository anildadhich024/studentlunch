@php
date_default_timezone_set('Australia/Adelaide');
@endphp
@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('teacher_panel.layouts.side_panel')
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
						<form action="{{url('teacher_panel/place_order/save')}}" method="post" id="general_form">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="row first-block">
								<div class='col-6 col-lg-3 col-md-6 pb-2 from-boxes'>
									<label>Date</label>
									<input type="date" id="sDtTm" name="sDtTm"  min="{{date('d/m/Y')}}" required>
								</div>
								<input type="hidden" name="lChldIdNo" value="{{session('USER_ID')}}">
								<div class='col-6 col-lg-3 col-md-6 pb-3 from-boxes'>
									<label>Student School Name</label>
									<input type="text" name="sSchlName" value="{{$sSchlName}}" required readonly> 
								</div>
								<div class='col-6 col-lg-4 col-md-6 pb-3 from-boxes'>
									<label>Select Service Provider</label>
									<select name="lMilkIdNo" id="lMilkIdNo" data-milk="@if(is_object($aCart)){{ $aCart->lMilkIdNo }} @endif" required style="padding: 10px 12px;">
										<option value="">Service Provider</option>
										@foreach($aMilkLst as $aRec)
											<option value="{{$aRec->lMilk_IdNo}}">{{$aRec->sBuss_Name}}</option>
										@endforeach
									</select>
								</div>
								<div class='col-6 col-lg-2 col-md-6 pb-2 from-boxes'>
									<label>Available Credits</label>
									<input type="text" name="sCrdtsAvlbl" id="sCrdtsAvlbl" required readonly class="text-right"> 
								</div>
							</div>
							<!-- Commssions Details Tabel -->
							<div class="row pt-4">
								<div class="col-sm-12 col-lg-12 commssions-table-details table-responsive lunch-order-form">
									<table id="orderTable"  class=" tablescroll parentPlaceOrderTable">
										<thead>
											<tr>
												<th class="nowordwrap"></th>
												<th class="nowordwrap">Item ID</th>
												<th class="nowordwrap">Category</th>
												<th class="nowordwrap">Item Description</th>
												<th class="nowordwrap">Ingredinets</th>
												<th class="nowordwrap">Quantity</th>
												<th class="text-right nowordwrap" style="width: 100px;">Price</th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>
								</div>
							</div>
							<div class="row pt-3">
								<div class="col-sm-12 text-center">
									<div class="order-total mb-2 mt-5">Order Total (Ex. Taxes) : $<label id="lTotalOrder">0.00</label></div>
								</div>
							</div>
							<div class="row pt-3">
								<div class="col-lg-12 services-btns">
									<ul class="m-auto text-center pt-4 pb-4">
										<li>
											<div class="add-btn d-none mt-0"><button title="Review Order" class="mt-0">Review Your Order</button></div>
										</li>
									</ul>
								</div>
							</div>
						</form>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript">
Date.prototype.toDateInputValue = (function() {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0,10);
});

$( document ).ready(function() {
	$('#sDtTm').attr('min', new Date().toDateInputValue());
	var sDelvDate = '{{isset($aCart->sDelvDate) ? $aCart->sDelvDate : ''}}';
	$('#sDtTm').val(sDelvDate == '' ? new Date().toDateInputValue() : sDelvDate);
    var optionSelected = $("option:selected", $('#lChldIdNo'));
	$('#sSchlName').val(optionSelected.data('school'));
	var milk = JSON.stringify(optionSelected.data('milk'));
	var schlId = optionSelected.data('schoolid');
	var optionSelected = $("option:selected", $('#lMilkIdNo'));
	$.ajax({url: "teacher_panel/get_milk?sDtTm="+$('#sDtTm').val(), success: function(result){
		var oMlkBars = JSON.parse(result);
		if(schlId == 0){
			alert("The school has been blocked. Hence no Service Provider available.");
		}else{
			content = '<option value="">Service Provider</option>';
			for(var i in oMlkBars) {
				var oMlkBar = oMlkBars[i];
				if(oMlkBar['yCut_Status'] == 0)
				{
					content += '<option disabled value="'+oMlkBar['lMilk_IdNo']+'">'+oMlkBar['sBuss_Name']+'</option>';
				}
				else
				{
					if($('#lMilkIdNo').data('milk') == oMlkBar['lMilk_IdNo'])
					{
						content += '<option selected value="'+oMlkBar['lMilk_IdNo']+'">'+oMlkBar['sBuss_Name']+'</option>';
					}else{
						content += '<option value="'+oMlkBar['lMilk_IdNo']+'">'+oMlkBar['sBuss_Name']+'</option>';
					}	
				}
			}
			$('#lMilkIdNo').html(content);
			
			var optionSelected = $("option:selected", $('#lMilkIdNo'));
			$.ajax({url: "get_menu/list?milkbar="+optionSelected.val()+"&nUserType={{config('constant.USER.TEACHER')}}", success: function(result){
				var oMenuItems = JSON.parse(result);
				content = '';
				for(var i in oMenuItems) {
					if(i != 'wallet'){
						var oMenuItem = oMenuItems[i];
						if(oMenuItem['selected']){
							content += '<tr> <td> <div class="checkbox-warning"> <label><input type="checkbox" name="lItemIdNo[]" checked value="'+oMenuItem['lItem_IdNo']+'" class="styled checkbox"></label> </div> </td>';
						}else{
							content += '<tr> <td> <div class="checkbox-warning"> <label><input type="checkbox" name="lItemIdNo[]" value="'+oMenuItem['lItem_IdNo']+'" class="styled checkbox"></label> </div> </td>';
						}
						content += '<td>'+oMenuItem['lItem_Unq_Id']+'</td> <td>';
						content += oMenuItem['sCatg_Name']+'</td>';
						content += ' <td>'+oMenuItem['sItem_Name']+'</td>';
						content += '<td>'+oMenuItem['sItem_Dscrptn']+'</td>';
						content += '<td><div id="field1" class="d-flex flex-row justify-content-around">';
							content += '<button type="button" id="sub" class="sub align-self-center ">-</button>';
						if(oMenuItem["nItemQty"] === undefined){
							content += '<input type="text" id="lItemQty" class="lItemQty col-6" name="lItemQty'+oMenuItem["lItem_IdNo"]+'" value="0" min="1" max="10" readonly/>';
						}else{
							if(oMenuItem["nItemQty"] == 1){
								
								content += '<input type="text" id="lItemQty" class="lItemQty col-6"  name="lItemQty'+oMenuItem["lItem_IdNo"]+'" value="'+oMenuItem["nItemQty"]+'" min="1" max="10" readonly/>';
							}else{
								 
								content += '<input type="text" id="lItemQty"  class="lItemQty col-6 "name="lItemQty'+oMenuItem["lItem_IdNo"]+'" value="'+oMenuItem["nItemQty"]+'" min="1" max="10" readonly/>';
							}
						}
						content += '<button type="button" id="add" class="add  align-self-center" >+</button>';
						content += '<input type="hidden" value="'+oMenuItem['sItem_Prc']+'"/>';
						if(oMenuItem["nItemQty"] === undefined){
							content += '<input class="total-price" type="hidden" value="0"  />';
						}else{
							content += '<input class="total-price" type="hidden" value="'+oMenuItem['sItem_Prc']*oMenuItem["nItemQty"]+'"  />';
						}
						content += '</div> </td>';
						if(oMenuItem["nItemQty"] === undefined){
							content += '<td class="text-right tot-price">$ '+parseFloat(oMenuItem['sItem_Prc']).toFixed(2)+'</td></tr>';
						}else{
							content += '<td class="text-right tot-price">$ '+parseFloat(oMenuItem['sItem_Prc']*oMenuItem["nItemQty"]).toFixed(2)+'</td></tr>';
						}
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
    if($(this).val() < new Date().toDateInputValue()){
        $(this).val(new Date().toDateInputValue());
    }
});

$('#sDtTm').on('change', function (event) {
	var optionSelected = $("option:selected", this);
	$('#sSchlName').val(optionSelected.data('school'));
	if(optionSelected.val() != '' && $('#sDtTm').val() != ''){
		$.ajax({url: "teacher_panel/get_milk?sDtTm="+$('#sDtTm').val(), success: function(result){
			var oMlkBars = JSON.parse(result);
			if(optionSelected.data('schoolid') == 0)
			{
				alert("The school has been blocked. Hence no Service Provider available.");
			}
			else
			{
				content = '<option value="">Service Provider</option>';
				for(var i in oMlkBars) {
					var oMlkBar = oMlkBars[i];
					if(oMlkBar['yCut_Status'] == 0)
					{
						content += '<option disabled value="'+oMlkBar['lMilk_IdNo']+'">'+oMlkBar['sBuss_Name']+'</option>';
					}
					else
					{
						content += '<option value="'+oMlkBar['lMilk_IdNo']+'">'+oMlkBar['sBuss_Name']+'</option>';	
					}
				}
				$('#lMilkIdNo').html(content);
				$('#sCrdtsAvlbl').val('$ 0.00');
			}
			$('#orderTable tbody').html('');
		    totalOrder();
		}});
	}
	else{
		$('#lMilkIdNo').html('<option value="">Service Provider</option>');
		$('#orderTable tbody').html('');
		totalOrder();
	}
});

$('#lMilkIdNo').on('change', function (event) {
	var optionSelected = $("option:selected", this);
	$.ajax({url: "get_menu/list?milkbar="+optionSelected.val()+"&nUserType={{config('constant.USER.TEACHER')}}", success: function(result){
		var oMenuItems = JSON.parse(result);
		content = '';
		for(var i in oMenuItems) {
			if(i != 'wallet'){
				var oMenuItem = oMenuItems[i];
				content += '<tr> <td> <div class="checkbox-warning"> <label><input type="checkbox" name="lItemIdNo[]" value="'+oMenuItem['lItem_IdNo']+'" class="styled checkbox"></label> </div> </td>';
				content += '<td>'+oMenuItem['lItem_Unq_Id']+'</td> <td>';
				content += oMenuItem['sCatg_Name']+'</td>';
				content += ' <td>'+oMenuItem['sItem_Name']+'</td>';
				content += '<td>'+oMenuItem['sItem_Dscrptn']+'</td>';
				content += '<td><div id="field1"  class="d-flex flex-row justify-content-around">';
				content += '<button type="button" id="sub" class="sub  align-self-center ">-</button>';
				content += '<input type="text" id="lItemQty" class="lItemQty col-6"  name="lItemQty'+oMenuItem["lItem_IdNo"]+'" value="0" min="1" max="10" readonly/>';
				content += '<button type="button" id="add" class="add  align-self-center " >+</button>';
				content += '<input type="hidden" value="'+oMenuItem['sItem_Prc']+'" min="1" max="100"  />';
				content += '<input class="total-price" type="hidden" value="0" min="1" max="100"  />';
				content += '</div> </td>';
				content += '<td class="text-right tot-price">$ '+parseFloat(oMenuItem['sItem_Prc']).toFixed(2)+'</td></tr>';
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