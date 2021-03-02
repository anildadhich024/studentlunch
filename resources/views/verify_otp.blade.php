@include('layouts.header')
    <div class="section-padding">
		<div class="forgot-password"  >
		   <div class="row">
			<div class="col-7"><img src="images/forget-pass.jpg"></div>
			<div class="col-5 form-area form-forgot-password">
				   <img src="images/logo.png">
				   <h3 class="form-heading">OTP Verification Sent On Email Address
	& Change New Password  </h3>
				   <div class="form-field-area" id="milk-bars-form">
					<form action="{{url('user/reset_password')}}" method="post">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					 <div class="row">
					 
						<div class="col-12">
						   <label>Please Enter Your OTP</label>
						   <input type="text" placeholder="Enter OTP" class="@error('lOtp') is-invalid @enderror" value="{{ old('lOtp') }}" name="lOtp" id="lOtp" onkeypress="return IsNumber(event, this.value, '6')" required />
                                @error('lOtp') <div class="invalid-feedback"><span>{{$errors->first('lOtp')}}</span></div>@enderror
						</div>
						<div class="col-12">
						  <label>New Password</label>
						  <input type="password" placeholder="Enter Your Password" class="@error('sLgnPass') is-invalid @enderror" name="sLgnPass" id="sLgnPass" value="{{ old('sLgnPass') }}"  onkeypress="return LenCheck(event, this.value, '16')" required />
						  <span class="show_password" onmousedown ="ShowPass('sLgnPass');"><i class="sLgnPass fa fa-eye-slash" aria-hidden="true"></i></span>
                            @error('sLgnPass') <div class="invalid-feedback"><span>{{$errors->first('sLgnPass')}}</span></div>@enderror
						</div>
						  <div class="col-12">
						  <label>Confirm Password</label>
						  <input type="password" placeholder="Confirm Your Password" class="@error('sCnfrmPass') is-invalid @enderror" name="sCnfrmPass" id="sCnfrmPass" value="{{ old('sCnfrmPass') }}"  onkeypress="return LenCheck(event, this.value, '16')" required />
						  <span class="show_password" onmousedown ="ShowPass('sCnfrmPass');"><i class="sLgnPass fa fa-eye-slash" aria-hidden="true"></i></span>
                            @error('sCnfrmPass') <div class="invalid-feedback"><span>{{$errors->first('sCnfrmPass')}}</span></div>@enderror
						</div>
						@if(Session::has('Failed'))
							<div class="alert alert-danger">
								<strong>Failed ! </strong> {{Session::get('Failed')}}
							</div>
						@endif
						 <div class="col-12 text-center mt-4">
						<button class="btn-blue">Submit</button><br /><br />
					  
						</div>
					 </div>
					</form>
				   </div>
				  
			</div>
			<div class="col-3">&nbsp;</div>
		   </div>
		   
		</div>
	</div>
@include('layouts.footer')
