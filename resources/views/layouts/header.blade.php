<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="{{url('./')}}">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $sTitle }} | Student Lunch</title>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<!-- Font-Awesome CSS -->
<link rel="stylesheet" href="assets/css/all.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Theme CSS -->
<link rel="stylesheet" href="assets/css/admin-theme.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<!-- <script src="assets/scripts/jquery.min.js"></script> -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" type="text/css" media="all" href="css/style.css?v={{date('ymdHis')}}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


</head>

<body>
<div class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-6"><p>ABN: 39 436 254 039</p></div>
            <div class="col-6">
                <ul class="top-social-media">
                    <li><a href="#"><span class="top-linkdin-icon"></span></a></li>
                    <li><a href="#"><span class="top-youtube-icon"></span></a></li>
                    <li><a href="#"><span class="top-facebook-icon"></span></a></li>
                    <li><a href="#"><span class="top-instagram-icon"></span></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="main-header">
    <div class="container">
        <div class="row">
            <div class="logo-area">
                <a href="{{url('')}}"><img src="{{url('images/logo.png')}}" alt="" id="logo" /></a>
            </div>
            <div class="menu-area" id="menu-area">
                <ul>
                    <li class="active"><a href="{{url('')}}">Home</a></li>           
                    <li><a href="#">About us</a>
                        <ul>
                            <li><a href="#">Dropdown 1</a></li>
                            <li><a href="#">Dropdown 2</a></li>
                            <li><a href="#">Dropdown 3</a></li>
                            <li><a href="#">Dropdown 4</a></li>
                            <li><a href="#">Dropdown 5</a></li>
                        </ul>
                    </li>            
                    <li><a href="#">Menu</a></li>            
                    <li><a href="#">Services</a></li>            
                    <li><a href="#">Offers</a></li>            
                    <li><a href="#">Contact Us</a></li> 
			       <!--		Session('user')['sFrst_Name'] -->
					@if(Session::has('USER_ID'))
						<li> 
							<button class="btn dropdown-toggle" type="button" data-toggle="dropdown">{{ Session('USER_NAME') }}
							<span class="caret"></span></button>
							<ul class="dropdown-menu">
                                @php
                                    if(Session('USER_TYPE') == 'P') {
                                        $sUrl = 'parent_panel';
                                    } elseif(Session('USER_TYPE') == 'M') {
                                        $sUrl = 'milkbar_panel';
                                    } else {
                                        $sUrl = 'teacher_panel';
                                    }
                                @endphp
								<li><a href="{{url($sUrl)}}">Account</a></li>
								<li> 
									<a class="dropdown-item" href="{{ url('logout') }}">
                                        Logout
                                    </a>
								</li>
							</ul>
						</li>
					@else
						<li><a href="{{url('user/login')}}" class="btn-blue-border">Login</a></li> 
						<li><a href="{{url('registration/parent')}}" class="btn-blue">Register</a></li>
					@endif
                </ul>
            </div>
            <div class="toggle-menu" onclick="toogle_menu(this)">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>
        </div>
    </div>
</div>