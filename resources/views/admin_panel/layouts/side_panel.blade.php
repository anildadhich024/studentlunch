<div class="sidebar">
    <div class="logo">
        <a href="{{url('/admin_panel')}}">
        <span class="logo-full">Students Lunch</span>
        <span class="tag_line">As Nuture Teaches us</span>
        </a>
        <div class="sidebar-close-icon">
            <h4>SL</h4>
        </div>
    </div>
    <ul id="sidebarCookie">
        <li class="nav-item dashbord-itme">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('admin_panel') ? 'active' : '' }}" href="{{url('admin_panel')}}" >
            <i class="fa fa-th-large"></i>
            <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('admin_panel/manage_account*') ? 'active' : '' }}" href="{{url('admin_panel/manage_account')}}">
            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
            <span class="menu-title">Manage Account</span>
            </a>
        </li>
        <li class="nav-item submenu">
            <a class="nav-link wave-effect submenu collapsed wave-effect {{ Request::is('admin_panel/country/list*') || Request::is('admin_panel/state/list*') ? 'active' : '' }}"
                data-toggle="collapse" data-target="#collapseOne">
                <i class="fa fa-user-circle-o"></i>
                <span class="menu-title">Manage Location</span>
                <span class="moremenulink">
                    <i class="fas fa fa-chevron-up"></i>
                    <i class="fas fa fa-chevron-down"></i>
                </span>
            </a>
            <ul class="collapse {{ Request::is('admin_panel/country/list*') || Request::is('admin_panel/state/list*') ? 'show' : '' }}" data-parent="#accordionExample" id="collapseOne">
                <li class="subitems {{ Request::is('admin_panel/country/list*') ? 'active' : '' }}">
                    <a data-parent="#accordionExample" href="{{url('admin_panel/country/list')}}">
                        Manage Country
                    </a>
                </li>
                <li class="subitems {{ Request::is('admin_panel/state/list*') ? 'active' : '' }}">
                    <a data-parent="#accordionExample" href="{{url('admin_panel/state/list')}}">
                        Manage State
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item submenu">
            <a class="nav-link wave-effect submenu collapsed wave-effect {{ Request::is('admin_panel/school/list*') || Request::is('admin_panel/school/manage*') || Request::is('admin_panel/school/detail*') || Request::is('admin_panel/request/school/list*') ? 'active' : '' }}"
                data-toggle="collapse" data-target="#collapseTwo">
                <i class="fa fa-home"></i>
                <span class="menu-title">Manage School</span>
                <span class="moremenulink">
                    <i class="fas fa fa-chevron-up"></i>
                    <i class="fas fa fa-chevron-down"></i>
                </span>
            </a>
            <ul class="collapse {{ Request::is('admin_panel/school/list*') || Request::is('admin_panel/school/manage*') || Request::is('admin_panel/school/detail*') || Request::is('admin_panel/request/school/list*') ? 'show' : '' }}" data-parent="#accordionExample" id="collapseTwo">
                <li class="subitems {{ Request::is('admin_panel/school/list*') || Request::is('admin_panel/school/manage*') || Request::is('admin_panel/school/detail*') ? 'active' : '' }}">
                    <a data-parent="#accordionExample" href="{{url('admin_panel/school/list')}}">
                        Onboard School
                    </a>
                </li>
                <li class="subitems {{ Request::is('admin_panel/request/school/list*') ? 'active' : '' }}">
                    <a data-parent="#accordionExample" href="{{url('admin_panel/request/school/list')}}">
                        Requested School
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item submenu">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('admin_panel/milk_bar/list*') || Request::is('admin_panel/milk_bar/manage*') || Request::is('admin_panel/milk_bar/detail*') || Request::is('admin_panel/teacher/list*') || Request::is('admin_panel/teacher/detail*') || Request::is('admin_panel/parent/list*') || Request::is('admin_panel/parent/detail*') ? 'active' : '' }}" data-toggle="collapse" data-target="#collapseThree">
            <i class="fa fa-users"></i>
            <span class="menu-title">Manage Users</span>
            <span class="moremenulink">
                <i class="fas fa fa-chevron-up"></i>
                <i class="fas fa fa-chevron-down"></i>
            </span>
            </a>
            <ul class="collapse {{ Request::is('admin_panel/milk_bar/list*') || Request::is('admin_panel/milk_bar/manage*') || Request::is('admin_panel/milk_bar/detail*') || Request::is('admin_panel/teacher/list*') || Request::is('admin_panel/teacher/detail*') || Request::is('admin_panel/parent/list*') || Request::is('admin_panel/parent/detail*') ? 'show' : '' }}" data-parent="#accordionExample" id="collapseThree">
                <li class="subitems {{ Request::is('admin_panel/milk_bar/list*') || Request::is('admin_panel/milk_bar/manage*') || Request::is('admin_panel/milk_bar/detail*') ? 'active' : '' }}">
                    <a data-parent="#accordionExample" href="{{url('admin_panel/milk_bar/list')}}">
                        Service Provider
                    </a>
                </li>
                <li class="subitems {{ Request::is('admin_panel/parent/list*') || Request::is('admin_panel/parent/detail*') ? 'active' : '' }}">
                    <a data-parent="#accordionExample" href="{{url('admin_panel/parent/list')}}">
                        Parents
                    </a>
                </li>
                <li class="subitems {{ Request::is('admin_panel/teacher/list*') || Request::is('admin_panel/teacher/detail*') ? 'active' : '' }}">
                    <a data-parent="#accordionExample" href="{{url('admin_panel/teacher/list')}}">
                        Teacher
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('admin_panel/manage_order*') ? 'active' : '' }}" href="{{url('admin_panel/manage_order')}}">
            <i class="fa fa-sort"></i>
            <span class="menu-title">Manage Orders</span>
            </a>
        </li>
        <li class="nav-item submenu">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('admin_panel/manage_commission*') || Request::is('admin_panel/manage/plan*') || Request::is('admin_panel/plan/list*') || Request::is('admin_panel/manage_subscription*') || Request::is('admin_panel/subscription/list*') ? 'active' : '' }}" data-toggle="collapse" data-target="#collapseFour">
            <i class="fa fa-cogs"></i>
            <span class="menu-title">Manage Financials</span>
            <span class="moremenulink">
                <i class="fas fa fa-chevron-up"></i>
                <i class="fas fa fa-chevron-down"></i>
            </span>
            </a>
            <ul class="collapse {{ Request::is('admin_panel/manage_commission*') || Request::is('admin_panel/manage/plan*') || Request::is('admin_panel/plan/list*') || Request::is('admin_panel/manage_subscription*') || Request::is('admin_panel/subscription/list*') ? 'show' : '' }}" data-parent="#accordionExample" id="collapseFour">
                <li class="subitems {{ Request::is('admin_panel/manage/plan*') || Request::is('admin_panel/plan/list*')? 'active' : '' }}">
                    <a data-parent="#accordionExample" href="{{url('admin_panel/plan/list')}}">
                        Pricing Plan
                    </a>
                </li> 
                <li class="subitems {{ Request::is('admin_panel/manage_commission*') ? 'active' : '' }}">
                    <a data-parent="#accordionExample" href="{{url('admin_panel/manage_commission')}}">
                        Commissions
                    </a>
                </li>
                <li class="subitems {{ Request::is('admin_panel/manage_subscription*') || Request::is('admin_panel/subscription/list*') ? 'active' : '' }}">
                    <a data-parent="#accordionExample" href="{{url('admin_panel/manage_subscription')}}">
                        Subscriptions
                    </a>
                </li> 
            </ul>
        </li>
 
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect {{ Request::is('admin_panel/change_password*') ? 'active' : '' }}" href="{{url('admin_panel/change_password')}}">
            <i class="fa fa-lock"></i>
            <span class="menu-title">Change Password</span>
            </a>
        </li> 

         <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect  {{ Request::is('admin_panel/manage/holiday') || Request::is('admin_panel/holiday/list') ? 'active' : '' }}" href="{{url('admin_panel/holiday/list')}}">
            <i class="fa fa-cogs"></i>
            <span class="menu-title">Manage Holiday</span>
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect  {{ Request::is('admin_panel/manage/settings')? 'active' : '' }}" href="{{url('admin_panel/manage/settings')}}">
            <i class="fa fa-cogs"></i>
            <span class="menu-title">Manage Agreements</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect  {{ Request::is('admin_panel/manage/logFiles')? 'active' : '' }}" href="{{url('admin_panel/manage/logFiles')}}">
            <i class="fa fa-cogs"></i>
            <span class="menu-title">Manage Log Files</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link wave-effect collapsed wave-effect" href="{{url('admin_panel/logout')}}">
            <i class="fa fa-sign-out"></i>
            <span class="menu-title">Log Out</span>
            </a>
            <form id="logout-form" action="{{ url('admin_panel/logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>
@php
$aGetStng = \App\Model\Setting::first()->toArray();
@endphp
<div class="modal fade" id="StngModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('admin_panel/setting/save')}}" method="post" id="general_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Manage Setting</h4>
                </div>
                <div class="modal-body card-commission-section">
                    <form>
                        <div class="row account-form">
                            <div class="col">
                                <label>Connision (%)</label>
                                <input type="text" name="dComPer" id="dComPer" class="form-control" onkeypress="return isNumberKey(event)" onchange="CommPer()" required tabindex="1" value="{{$aGetStng['dCom_Per']}}">
                            </div>
                        </div>
                        <div class="row account-form">
                            <div class="col">
                                <label>Parent Monthly Fee</label>
                                <input type="text" name="sPrntAmo" id="sPrntAmo" class="form-control" onkeypress="return isNumberKey(event)" onchange="PlnAmo()" required tabindex="2" value="{{$aGetStng['sPrnt_Amo']}}">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-lg-12 services-btns">
                            <ul class="m-auto text-center">
                                <li>
                                    <div class="add-btn  mt-0"><button class="mt-0 btnhover" tabindex="6" data-dismiss="modal" aria-label="Close">Cancel</button></div>
                                </li>
                                <li>
                                    <div class="add-btn  mt-0"><button title="Save Item" type="submit" class="mt-0 btnhover" tabindex="7">Save</button></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>