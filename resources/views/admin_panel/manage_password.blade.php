@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('admin_panel.layouts.side_panel')
            <form action="{{url('admin_panel/change_password/save')}}" method="post" id="general_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <main>
                    <div class="page-breadcrumb">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="page-title">Change Password</h4>
                            </div>
                        </div>
                    </div>
                    @include('admin_panel.layouts.message')
                    <div class="container-fluid card-commission-section  parent-details-section">
                        <div class="row account-form">
                            <div class="col">
                                <label>Current Password</label>
                                <input type="password" class="form-control @error('sCurrPass') is-invalid @enderror" name="sCurrPass" id="sCurrPass" value="{{ old('sCurrPass') }}" onkeypress="return LenCheck(event, this.value, '16')" required />
                                <span class="show_password" onmousedown ="ShowPass('sCurrPass');"><i class="sCurrPass fa fa-eye-slash" aria-hidden="true"></i></span>
                                @error('sCurrPass') <div class="invalid-feedback"><span>{{$errors->first('sCurrPass')}}</span></div>@enderror
                            </div>
                            <div class="col">
                                <label>New Password</label>
                                <input type="password" class="form-control @error('sLgnPass') is-invalid @enderror" name="sLgnPass" id="sLgnPass" value="{{ old('sLgnPass') }}" onkeypress="return LenCheck(event, this.value, '16')" required />
                                <span class="show_password" onmousedown ="ShowPass('sLgnPass');"><i class="sLgnPass fa fa-eye-slash" aria-hidden="true"></i></span>
                                @error('sLgnPass') <div class="invalid-feedback"><span>{{$errors->first('sLgnPass')}}</span></div>@enderror
                            </div>
                            <div class="col">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control @error('sCnfrmPass') is-invalid @enderror" name="sCnfrmPass" id="sCnfrmPass" value="{{ old('sCnfrmPass') }}" onkeypress="return LenCheck(event, this.value, '16')" required />
                                <span class="show_password" onmousedown ="ShowPass('sCnfrmPass');"><i class="sCnfrmPass fa fa-eye-slash" aria-hidden="true"></i></span>
                                @error('sCnfrmPass') <div class="invalid-feedback"><span>{{$errors->first('sCnfrmPass')}}</span></div>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 services-btns">
                                <ul class="m-auto text-center pt-4 pb-4">
                                    <li>
                                        <div class="add-btn  mt-0"><button title="Back" class="mt-0" onclick="history.back()">Back</button></div>
                                    </li>
                                    <li>
                                        <div class="add-btn  mt-0"><button title="Update" type="submit" class="mt-0">Update</button></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </main>
            </form>
        </div>
    </div>
@include('admin_panel.layouts.footer')