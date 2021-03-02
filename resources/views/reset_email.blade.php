@include('layouts.header')
    <div class="Parent-Student-Registration section-padding bg-milk">
		<div class="container">
		   <div class="row">
			<div class="col-3">&nbsp;</div>
			<div class="col-6 form-area">
				   <h3 class="form-heading">Forgot Password</h3>
				   @if(Session::has('Success'))
					<div class="alert alert-success">
						<strong>Success ! </strong> {{Session::get('Success')}}
					</div>
					@endif
					@if(Session::has('Failed'))
					<div class="alert alert-danger">
						<strong>Failed ! </strong> {{Session::get('Failed')}}
					</div>
					@endif
				   
				   <div class="form-field-area" id="login-form">
					<form action="{{url('user/send_otp')}}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						 <div class="row">
							<div class="col-12">
								<input type="email" placeholder="User Name" class="form-control @error('sEmailId') is-invalid @enderror" value="{{ old('sEmailId') }}"  name="sEmailId" onkeypress="return LenCheck(event, this.value, '50')" required />
									@error('sEmailId') <div class="invalid-feedback"><span>{{$errors->first('sEmailId')}}</span></div>@enderror
							</div>
							<div class="col-12 text-center">
								<button type="submit" class="btn-blue">Send OTP</button><br /><br />
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
