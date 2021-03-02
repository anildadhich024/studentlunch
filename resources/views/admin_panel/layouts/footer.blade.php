		<div class="modal fade bd-example-modal-lg" id="OrderDtlModel" tabindex="-1" role="dialog" aria-spanledby="myLargeModalspan"  >
			<div class="modal-dialog modal-lg">
			  <div class="modal-content"> 
				 
				<div class="modal-body">
					<div class="row">
						<div class="col-6">
							<h4>Your Order Details</h4>
						</div>
						<div class="col-6 text-right">
						   <span id="sOrdrIdNo"></span> <br>  <span id="sCrtDtTm"></span> <br>  <span id="sDelvDtTm"></span> <br>  <span id="sOthrDtTm"></span>
						</div>
					</div>
					<div class="row pt-4">
						<div class="col-md-6">
							<strong>From : </strong> <br>
							<p class="pl-4">
								<span id="sBussName"></span> <br>
								<span id="sMlkAdrs"></span> <br>
								<span id="sMlkPhnNo"></span> <br>
								<span id="sMlkEmail"></span>
							</p>
						  
						</div>
						<div class="col-md-6">
							<strong>Ordered For :  </strong> <br>
							 
							<p class="pl-4">  
								<span id="sChldName"></span> <br>
								<span id="sSchlAdrs"></span> <br>
								<span id="sSchlPhnNo"></span> <br>
								<span id="sSchlEmail"></span>
							</p>
						</div>
					</div>
					<div class="row pt-4">
						<div class="col-12">
							<table class="table  addresspopuptable">
								<thead>
								  <tr>
									<th scope="col">S.No</th>
									<th scope="col">Item Name</th>
									<th scope="col">Quantity</th>
									<th scope="col">Unit Cost</th>
									<th scope="col">Total</th>
								  </tr>
								</thead>
								<tbody id="aItms">
								  
								</tbody>
							  </table>
						</div>
					</div>
					<div class="row justify-content-end">  
						<div class="col-md-6 col-lg-4 col-sm-6  col-9">
							<table  class=" w-100">
								<tr style="height: 25px; text-align: right;">
									<td>
										Item Total (Ex. Tax) :
									</td>
									<td>
										<span id="sSubTtl" class='text-right'></span>
									</td>
								</tr>
								<tr style="height: 25px; text-align: right;">
									<td>
										Taxes (GST) :
									</td>
									<td>
										<span id="sGst" class='text-right'></span>
									</td>
								</tr>
								<tr style="height: 25px; text-align: right;">
									<td>
										<b>Subtotal</b> :
									</td>
									<td>
										<span id="sSubTtlnew" class='text-right'></span>
									</td>
								</tr>
								<tr style="height: 25px; text-align: right;">
									<td>
										Less Credit Used :
									</td>
									<td>
										<span id="sCrdt" class='text-right'></span>
									</td>
								</tr>
								<tr style="height: 25px; text-align: right;">
									<td>
										<b>Total Due</b> :
									</td>
									<td>
										<span id="sPay" class='text-right'></span>
									</td>
								</tr>
							</table> 
						</div> 
					</div>
					<div class="row mt-4">
						<div class="col-12 text-center">
							<button class="btn btn-primary"  data-dismiss="modal" aria-span="Close">
								Close
							</button>
						</div>
					</div>
				</div>
			  </div>
			</div>
		</div>
		<div class="modal fade bd-example-modal-lg" id="OrderCancelModel" tabindex="-1" role="dialog" aria-spanledby="myLargeModalspan"  >
			<div class="modal-dialog modal-lg" style="max-width:600px !important">
			  <div class="modal-content"> 
				<form method="get" id="cancelForm">
					<div class="modal-body">
						<div class="row">
							<div class="col-12">
								<h4>Cancel Order</h4>
							</div> 
						</div>
						<div class="row pt-4">
						<input type="hidden" name="lRecIdNo" id="lRecIdNo">
							<div class="col-md-12 d-none" id="OthrRsn"> 
								@foreach(config('constant.CANCEL_REASON_OTHR') as $sCncRsn => $nCncRsn)  
									<div class="col-lg-5" style="margin:7px 0px 7px 0px">
										<label for="fruit{{$nCncRsn}}">{{$sCncRsn}}</label>  
										<input type="radio" style="width: 50px;margin-top: -15px; margin-left: 110px;" class="form-control" name="sCnclReason" onClick="CancelValue(this.value)" id="fruit{{$nCncRsn}}" value="{{$nCncRsn}}" tabindex="5">
									</div>
								@endforeach 
								@error('aCncRsn') <div class="invalid-feedback"><span>{{$errors->first('aCncRsn')}}</span></div>@enderror
							</div>
							<div class="col-md-12" id="ChldRsn"> 
								@foreach(config('constant.CANCEL_REASON_CHLD') as $sCncRsn => $nCncRsn)  
									<div class="col-lg-12" style="margin:7px 0px 7px 0px">
										<label for="fruit{{$nCncRsn}}">{{$sCncRsn}}</label>  
										<input type="radio" style="width: 50px;margin-top: -15px; margin-left: 220px;" class="form-control" name="sCnclReason" onClick="CancelValue(this.value)" id="fruit{{$nCncRsn}}" value="{{$nCncRsn}}" tabindex="5">
									</div>
								@endforeach 
								@error('aCncRsn') <div class="invalid-feedback"><span>{{$errors->first('aCncRsn')}}</span></div>@enderror
							</div>
							<div class="col-md-12" style="display:none;margin-top: 40px;" id="cancel_box">
			  					<textarea class="sCnclNote" name="sCnclNote" id="sCnclNote"></textarea>
							</div>
						</div> 
						<div class="row mt-4">
							<div class="col-6 text-center"> 
								<button class="btn btn-primary"  onclick="CnclOrdConf()" type="submit" name="submit">
									Submit
								</button>
							</div>
							<div class="col-6 text-center">
								<button class="btn btn-primary"  data-dismiss="modal" aria-span="Close">
									Close
								</button>
							</div>
						</div>
					</div>
				</form>
			  </div>
			</div>
		</div>
		<script>
		function CancelValue(val){
			if(val == 216){
				$("#cancel_box").show();
			}else{
				$("#cancel_box").hide();
			}
		}</script>
		<style>
			.addresspopuptable td, .addresspopuptable th{
				border:1px solid #0009 !important;

			}
			.addresspopuptable thead tr{
				background:#f2f2f2;
			}
			 
			@media only screen and (max-width: 500px) {
				.addresspopuptable{
					display: block;
					overflow: scroll;
				}
			  }
		</style>
		<div class="loading_bg loader-block d-none" id="loadingBox">
		    <div class="loading_popup">
		        <div><img src="images/loder.gif" width="90"></div>
		        <strong class="loading_text">Processing, Please wait....</strong>
		    </div>
		</div>
		<script src="assets/scripts/jquery.min.js" ></script>
		<script type="text/javascript">
		var APP_URL = "{{url('/')}}";
		</script>
		{!! Charts::scripts() !!}

		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
 		<!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap js -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
    	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
		<script src="js/form-validation.min.js?v={{date('ymdHis')}}"></script> 
        <script src="assets/scripts/popper.min.js" ></script>
        <script src="assets/scripts/bootstrap.min.js" ></script>
        <script src="assets/scripts/library.min.js"></script>
        <script src="assets/scripts/main.js"></script>
        <script src="assets/js/custome-script.js?v={{date('ymdHis')}}"></script>
        <script src="js/common-script.js?v={{date('ymdHis')}}"></script>
        <script type="text/javascript" src="js/abn.js"></script>
    </body>
</html>