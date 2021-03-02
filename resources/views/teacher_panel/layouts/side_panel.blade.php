<div class="sidebar">
    <div class="logo">
        <a href="{{url('teacher_panel')}}">
        <span class="logo-full">Students Lunch</span>
        <span class="tag_line">As Nuture Teaches us</span>
        </a>
        <div class="sidebar-close-icon">
            <h4>SL</h4>
        </div>
    </div>
    <ul id="sidebarCookie">
        <li class="nav-item dashbord-itme">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('teacher_panel') ? 'active' : '' }}" href="{{url('teacher_panel')}}" >
            <i class="fa fa-th-large"></i>
            <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('teacher_panel/manage_account*') ? 'active' : '' }}"  href="{{url('teacher_panel/manage_account')}}">
            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
            <span class="menu-title">Manage Account</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('teacher_panel/manage_order*') ? 'active' : '' }}" href="{{url('teacher_panel/manage_order')}}">
            <i class="fa fa-sort"></i>
            <span class="menu-title">Manage Orders</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('teacher_panel/place_order*') || Request::is('teacher_panel/review_order*') || Request::is('teacher_panel/checkout*') ? 'active' : '' }}" href="{{url('teacher_panel/place_order')}}">
            <i class="fa fa-cutlery"></i>
            <span class="menu-title">Place Order</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('teacher_panel/my_credits*') ? 'active' : '' }}"  href="teacher_panel/my_credits">
            <i class="fa fa-credit-card-alt"></i>
            <span class="menu-title">My Credits</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('teacher_panel/change_password*') ? 'active' : '' }}" href="{{url('teacher_panel/change_password')}}">
            <i class="fa fa-lock"></i>
            <span class="menu-title">Change Password</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect" href="{{url('logout')}}">
            <i class="fa fa-sign-out"></i>
            <span class="menu-title">Log Out</span>
            </a>
            <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>