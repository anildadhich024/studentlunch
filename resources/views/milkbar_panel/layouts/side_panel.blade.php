<div class="sidebar">
    <div class="logo">
        <a href="{{url('milkbar_panel')}}">
        <span class="logo-full">Students Lunch</span>
        <span class="tag_line">As Nuture Teaches us</span>
        </a>
        <div class="sidebar-close-icon">
            <h4>SL</h4>
        </div>
    </div>
    <ul id="sidebarCookie">
        <li class="nav-item dashbord-itme">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('milkbar_panel') ? 'active' : '' }}" href="{{url('milkbar_panel')}}" >
            <i class="fa fa-th-large"></i>
            <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('milkbar_panel/manage_account*') ? 'active' : '' }}" href="{{url('milkbar_panel/manage_account')}}">
            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
            <span class="menu-title">Manage Account</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('milkbar_panel/stripe*') ? 'active' : '' }}" href="{{url('milkbar_panel/stripe')}}">
            <i class="fa fa-cc-stripe" aria-hidden="true"></i>
            <span class="menu-title">Setup Payment</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('milkbar_panel/category/list*') ? 'active' : '' }}" href="{{url('milkbar_panel/category/list')}}">
            <i class="fa fa-sort"></i>
            <span class="menu-title">Manage Category</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('milkbar_panel/item/list*') || Request::is('milkbar_panel/manage/item*') ? 'active' : '' }}" href="{{url('milkbar_panel/item/list')}}">
            <i class="fa fa-product-hunt"></i>
            <span class="menu-title">Manage Menu</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('milkbar_panel/variant/list*') ? 'active' : '' }}" href="{{url('milkbar_panel/variant/list')}}">
            <i class="fa fa-product-hunt"></i>
            <span class="menu-title">Manage Variant</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('milkbar_panel/my_orders*') ? 'active' : '' }}" href="{{url('milkbar_panel/my_orders')}}">
            <i class="fa fa-sort"></i>
            <span class="menu-title">Manage Orders</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('milkbar_panel/my_credits*') ? 'active' : '' }}" href="{{url('milkbar_panel/my_credits')}}">
            <i class="fa fa-credit-card-alt"></i>
            <span class="menu-title">Owed Credits</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('milkbar_panel/change_password*') ? 'active' : '' }}" href="{{url('milkbar_panel/change_password')}}">
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