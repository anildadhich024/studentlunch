@include('layouts.header')
    <div class="Parent-Student-Registration section-padding bg-milk">
		<div class="container">
		   	<div class="row">
				<div class="col-3">&nbsp;</div>
					<div class="col-6 form-area">
					   	<h3 class="form-heading">Login</h3>
					   	@if(Session::has('Success'))
						<div class="alert alert-success">
							<strong>Success ! </strong> {{Session::get('Success')}}
						</div>
						@endif
						@if(Session::has('Failed'))
						<div class="alert alert-danger">
							{{Session::get('Failed')}}
						</div>
						@endif
					   	<div class="form-field-area" id="login-form">
							<form action="{{url('user/authenticate')}}" method="post" autocomplete="off" id="general_form">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-12 form-main-div">
										<input type="email" placeholder="User Name" class="form-control @error('sEmailId') is-invalid @enderror" value="{{ old('sEmailId') }}"  name="sEmailId" onkeypress="return LenCheck(event, this.value, '50')" required />
										@error('sEmailId') <div class="invalid-feedback"><span>{{$errors->first('sEmailId')}}</span></div>@enderror
									</div>
									 <div class="col-12 form-main-div">
									   <input type="password" placeholder="Password"  class="@error('sLgnPass') is-invalid @enderror" name="sLgnPass" value="{{ old('sLgnPass') }}" onkeypress="return LenCheck(event, this.value, '16')" required />
										@error('sLgnPass') <div class="invalid-feedback"><span>{{$errors->first('sLgnPass')}}</span></div>@enderror
									   <p class="text-right"><a href="{{url('user/forgot')}}">Forgot password</a></p>
									</div>
									<div class="col-12 text-center">
										<button type="submit" class="btn-blue">Login</button><br /><br />
										<a href="{{url('registration/parent')}}" class="btn-blue">Register</a>
									</div>
								</div>
							</form>
				   		</div>
					</div>
					<div class="col-3">&nbsp;</div>
				</div>
		   	</div>
		</div>
	</div>
@include('layouts.footer')
