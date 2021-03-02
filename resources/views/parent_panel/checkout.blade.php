@include('admin_panel.layouts.header')
<style type="text/css">
	.panel-title {
	display: inline;
	font-weight: bold;
	}
	.display-table {
		display: table;
	}
	.display-tr {
		display: table-row;
	}
	.display-td {
		display: table-cell;
		vertical-align: middle;
		width: 61%;
	}
	.col-md-12.error.form-group.hide {
		display: none;
	}
	.tbl_hdng{
		font-size: 15px;
		font-weight: 600;
	}
	.tbl_dtl{
		font-size: 14px;
	}
</style>
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
						<div class="col-6">
							<h4 class="page-title">Checkout</h4>
						</div>
					</div>
				</div>
				@php
					$sTtlAmo = ($aCntryDtl['dTax_Per'] * $total / 100) +  $total;
				@endphp
				<!-- My Commissions From -->
				@include('admin_panel.layouts.message')
				<!-- My Commissions From -->
				<div class="container-fluid p-0">     
					<div class="row">
						<div class="col-6 col-lg-6 col-sm-6 payment-box ">
							<div class="payment-box-inner  card-commission-section  parent-details-section">   
								<h4 class="mb-0">Available Credits: ${{ number_format($nTtlCrdt , 2, '.', '')}}</h4>
								<h4 class="mb-0 mt-2 d-inline-block">Credits against this provider: </h4><h4 class="mb-0 milk-crdt d-inline-block">${{ number_format($sMlkCrdt , 2, '.', '')}}</h4> 
								@if(number_format($sMlkCrdt , 2, '.', '') > 0)
									<br/><input type="checkbox" id="useCrdt" name="useCrdt" value="1">
									<label for="useCrdt"> Use Credit</label>
								@else
									<br/><br/>
								@endif
							</div>
							<div class="payment-box-inner  card-commission-section  parent-details-section"> 
								<h4 class="mb-0 pb-4">Bill Details</h4>
								<p class="d-flex flex-row justify-content-between"> <span>Total ({{ $qty }} Items) </span>  <span class="text-right"> ${{ number_format($total , 2, '.', '')}} <span></p>
								<p class="d-flex flex-row justify-content-between"> <span>{{array_search($aCntryDtl['nTax_Mtdh'], config('constant.TAX_MTHD'))}} </span>  <span class="text-right">${{ number_format(($aCntryDtl['dTax_Per'] * $total / 100) , 2, '.', '')}}</p>
								<p class="d-flex flex-row justify-content-between"> <span><strong>Sub Total</strong></span>  <span class="text-right"><strong> ${{ number_format($sTtlAmo, 2, '.', '')}} </strong></p>
								<p id="display-credit" class="d-flex flex-row justify-content-between"> <span>Credits used:</span>  <span class="text-right"> - ${{ number_format(0 , 2, '.', '')}}</p>
								<p class="rmng_amnt d-flex flex-row justify-content-between"> <span><strong>Payable </strong>  </span>  <span class="text-right"> <strong> ${{ number_format($sTtlAmo, 2, '.', '')}} </strong></p>
								<p class="d-none cart-total ">     {{$sTtlAmo}}</p>
								<p class="d-none credit-total ">    {{$sMlkCrdt}}</p>
							</div>
							
						</div>
						<div class="col-6 col-lg-6 col-sm-6 payment-box ">
							<div class="payment-box-inner  card-commission-section  parent-details-section">
								<h4 class="mb-0 pb-4" style="padding-bottom: 0.8rem !important;">
									@if($aHldy == 1 || $nDelvStatus == 1)
										Holiday Pick Up Order
									@else
										Delivery Address
									@endif
								</h4>
								<table width="100%">
									<tr>
										<td class="tbl_hdng" width="25%">School Name</td>
										<td class="tbl_hdng" width="25%">Student Name</td>
										<td class="tbl_hdng" width="15%">Class ID</td>
										<td class="tbl_hdng" width="35%">
										@if($aHldy == 1 || $nDelvStatus == 1)
											Pickup Time
										@else
											Order Type
										@endif
										</td>
									</tr>
									<tr height="10"></tr>
									<tr>
										<td class="tbl_dtl" width="25%">{{ $aSchlDtl['sSchl_Name'] }}</td>
										<td class="tbl_dtl" width="24%">{{ $aChldDtl['sFrst_Name']}} {{$aChldDtl['sLst_Name'] }}</td>
										<td class="tbl_dtl" width="15%">
											@if($aHldy == 1 || $nDelvStatus == 1)
												Pick Up
											@else
												{{ $aChldDtl['sCls_Name'] }}
											@endif
										</td>
										<td class="tbl_dtl" width="35%">
											@if($aHldy == 1 || $nDelvStatus == 1)
												<input type="time" onBlur="getTime(this.value)" class="@error('sPicTm') is-invalid @enderror">
											@else
												Delivery
											@endif
										</td>
									</tr>
								</table>
							</div>
							<div class="payment-box-inner  card-commission-section  parent-details-section">
								<h4 class="mb-3">Process Payment </h4>
								<form role="form" action="{{ route('checkout.post') }}" method="post" class="require-validation"
																 data-cc-on-file="false"
																data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
																id="payment-form" >
									@csrf
			  						<input id="use_Crdt" name="use_Crdt" type="hidden" value="0">
			  						<input class='form-control' size='4' type='hidden' name="sAmount" value="{{ number_format($sTtlAmo, 2) }}"> 
									<input class='form-control' type='hidden' name="nOrderType" value="{{ $aHldy == 1 || $nDelvStatus == 1 ? 909 : 910 }}">
									<input class='form-control' type='hidden' name="sPicTm" id="sPicTm">
									<div class='form-row row'>
										<div class='col-6 form-group required'>
											<label class='control-label'>Name on Card</label> 
												<input class='form-control' size='4' type='text' onkeypress="return IsAlpha(event, this.value, '30')" name="user-name" value="{{old('user-name')}}">
										</div>
										<div class='col-6 form-group   required'>
											<label class='control-label'>Card Number</label> 
												<input autocomplete='off' class='form-control card-number' size='20'
												type='text' onkeypress="return IsCardNum(event, this.value, '19')" id="sCardNum" name="card-number" value="{{old('card-number')}}">
										</div>
									</div>
									<div class='form-row row'>
										<div class='col-6 col-md-4 form-group expiration required'>
											<label class='control-label'>Expiry Month</label> 
											<select class='form-control card-expiry-month' name="card-expiry-month" style="height: 39px !important;">
												<option value="">==MM==</option>
												@php
												$m = 1;
												@endphp
												@for($m==1;$m<=12;$m++)
													<option {{ old('card-expiry-year') == str_pad($m, 2, 0, STR_PAD_LEFT) ? 'selected=""' : ''}} value="{{str_pad($m, 2, 0, STR_PAD_LEFT)}}">{{str_pad($m, 2, 0, STR_PAD_LEFT)}}</option>
												@endfor
											</select>
										</div>
										<div class='col-6 col-md-4 form-group expiration required'>
											<label class='control-label'>Expiry Year</label> <input
												class='form-control card-expiry-year' placeholder='YYYY' size='4'
												type='text' onkeypress="return IsNumber(event, this.value, '4')" name="card-expiry-year" value="{{old('card-expiry-year')}}">
										</div>
										<div class='col-6 col-md-4 form-group cvc required'>
											<label class='control-label'>CVC</label> <input autocomplete='off'
												class='form-control card-cvc' placeholder='ex. 311' size='4'
												type='text' onkeypress="return IsNumber(event, this.value, '3')" name="card-cvc" value="{{old('card-cvc')}}">
										</div>
									</div>
			  
									<div class='form-row row'>
										<div class='col-md-12 error form-group hide'>
											<div class='alert-danger alert'>Please provide valid card information</div>
										</div>
									</div>
			  
									<div class="row">
										<div class="col-12 p-0">
											<div class="add-btn  mt-0 ml-3">
												<button title="Back" class="mt-0 mt-0 w-100 pay-card" type="submit">
													Pay Now (${{ number_format($sTtlAmo, 2, '.', '') }})
												</button>
											</div>
										</div>
									</div>
									  
								</form>
								<form role="form" action="{{ route('checkout.crpost') }}" method="post" class="cr-pay d-none" id="general_form">
									@csrf 
									<input class='form-control' type='hidden' name="nOrderType" value="{{ $aHldy == 1 || $nDelvStatus == 1 ? 909 : 910 }}">
									<input class='form-control' type='hidden' name="sPicTm" id="sPicTm">
									<div class="row">
										<div class="col-12 p-0">
											<div class="add-btn mt-0 ml-3"><button title="Back" class="mt-0 w-100 pay-card" type="submit">Pay Now (${{ number_format($sTtlAmo, 2, '.', '') }})</button></div>
										</div>
									</div>
									  
								</form>	
							</div>
						</div>
					</div>
				</div>
			</main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
  
<script type="text/javascript">
function getTime(time){
$("input[name=sPicTm]").val(time);
}
var format = new Intl.NumberFormat('en-AU', { 
    style: 'currency', 
    currency: 'AUD', 
    minimumFractionDigits: 2, 
}); 
$(".pay-button").on('click', function(){
	$(this).attr('disabled', true);
	$('.cr-pay').get(0).submit();
});

$(document).ready(function(){
	$('#useCrdt').click(function(){
		if($(this).prop("checked") == true)
		{

			if(parseFloat($('.cart-total').html()) < parseFloat($('.credit-total').html()))
			{
				$('#display-credit').css('color','#fe2525').html('<span>Credits used:   </span> <span>  - '+format.format(parseFloat($('.cart-total').html()).toFixed(2))+' </span>');
				$('.pay-card').html("Use Credit ("+format.format(parseFloat($('.cart-total').html()).toFixed(2))+")");
			}
			else
			{
				$('#display-credit').css('color','#ff3b56').html('<span>Credits used:   </span> <span>   - '+$('.milk-crdt').html() +' </span>');
			}
			$('#use_Crdt').val('1');
			if(parseFloat($('.cart-total').html()) > parseFloat($('.credit-total').html()))
			{
				$('.pay-card').html("Pay Now ($"+(parseFloat($('.cart-total').html()) - parseFloat($('.credit-total').html())).toFixed(2)+")");
				$(".cr-pay").addClass('d-none');
				$("#payment-form").removeClass('d-none');
				$('.rmng_amnt').html('<span><strong>Payable </strong>  </span> <span> <strong> $'+(parseFloat($('.cart-total').html()) - parseFloat($('.credit-total').html())).toFixed(2)+'  </strong> </span>');
			}
			else
			{
				$(".cr-pay").removeClass('d-none');
				$("#payment-form").addClass('d-none');
				$('.rmng_amnt').html('<span><strong>Payable </strong>  </span> <span>  <strong>$0.00  </strong></span>');
			}
		}
		else if($(this).prop("checked") == false)
		{
			$('.pay-card').html("Pay Now ($"+parseFloat($('.cart-total').html()).toFixed(2)+")");
			$('.rmng_amnt').html('<span><strong>Payable </strong>  </span> <strong> $'+parseFloat($('.cart-total').html()).toFixed(2)+' </strong></span>');
			$(".cr-pay").addClass('d-none');
			$("#payment-form").removeClass('d-none');
			$('#display-credit').css('color','#000000').html('<span>Credits used:  </span> <span>   - $0.00   </span> ');
			$('#use_Crdt').val('0');
		}
	});
});


$(function() {
    var $form         = $(".require-validation");
  $('form.require-validation').bind('submit', function(e) {
    var $form         = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs       = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid         = true;
        $errorMessage.addClass('hide');
 
        $('.has-error').removeClass('has-error');
    $inputs.each(function(i, el) {
      var $input = $(el);
      if ($input.val() === '') {
        $input.parent().addClass('has-error');
        $errorMessage.removeClass('hide');
        e.preventDefault();
      }
    });
  
    if (!$form.data('cc-on-file') && $('.card-cvc').val() != '') {
      e.preventDefault();
      Stripe.setPublishableKey($form.data('stripe-publishable-key'));
      Stripe.createToken({
        number: $('.card-number').val(),
        cvc: $('.card-cvc').val(),
        exp_month: $('.card-expiry-month').val(),
        exp_year: $('.card-expiry-year').val()
      }, stripeResponseHandler);
    }
  
  });
  
  function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
			$('.btn-primary').attr('disabled', true);
            // token contains id, last4, and card type
            var token = response['id'];
            // insert the token into the form so it gets submitted to the server
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
  
});

$(document).ready(function(){
	$("input[type='radio']").change(function(){
		$("input[name='nOrderType']").val($(this).val());
		if($(this).val() == 909){
			$("#sTime").removeClass('d-none');
		}else{
			$("#sTime").addClass('d-none');
			$("#sPicTm").val();
		}
	});
});
</script>