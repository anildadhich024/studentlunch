<div class="topbar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5  col-2">
                <div class="logo">
                    <a href="{{ !empty(session('USER_TYPE')) ? session('USER_TYPE') == 'P' ? url('parent_panel') : url('milkbar_panel') : url('admin_panel')}}">
                        <span class="logo-full">Students Lunch</span>
                        <span class="tag_line">As Nuture Teaches us</span>
                    </a>
                    <div class="sidebar-close-icon">
                        <h4>SL</h4>
                    </div>
                </div>
                <a href="JavaScript:Void(0);" class="menu-toggle wave-effect">
                <i class="fa fa-bars" aria-hidden="true"></i>
                </a>
            </div>
            <div class="col-md-7 col-10 col-lg-7 text-right">
                <div class="row user-profile-section justify-content-end">
                    <div class=" pr-3">
                        <div class="user-img"><img src="{{url('assets/images/user.png')}}"></div>
                    </div>
                    <div class=" pr-4">
                        <div class="user-profile-details">
                            <h5>WelCome To</h5>
                            <p>Student Lunch</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>