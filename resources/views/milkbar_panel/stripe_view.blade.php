@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('milkbar_panel.layouts.side_panel')
            <form action="{{url('milkbar_panel/change_password/save')}}" method="post" id="general_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <main>
                    <div class="page-breadcrumb">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="page-title">Manage Payment Setup</h4>
                            </div>
                        </div>
                    </div>
                    @include('admin_panel.layouts.message')
                    <div class="container-fluid card-commission-section  parent-details-section">
                        <div class="row account-form">
                            @if(empty($sAccLink))
                                <div class="col">
                                    <p>We use Stripe to make sure you get paid on real time basis, and to keep your personal bank and details secure. Order payments will be directly transferred to your stripe account as soon as a parent places an order with your Service Provider.</p>
                                    <p>This will require you to set up an account with Stripe Payment Gateway.  You will have full control over your stripe account.</p>
                                    <p>Setting Account with Stripe is very easy and will take only few minutes.Please click button 'Connect with Stripe' to set up your payments on Stripe.</p>
                                </div>
                            @else
                                <div class="col">
                                    <p>Click on below link to login in your <b>Stripe Account</b>.</p>
                                    <a href="{{$sAccLink}}" target="_blank" style="color: #000;"><b>{{$sAccLink}}</b></a>
                                </div>
                            @endif
                        </div>
                        @if(empty($sAccLink))
                        <div class="row">
                            <div class="col-lg-12 services-btns">
                                <ul class="m-auto text-center pt-4 pb-4">
                                    <li>
                                        <div class="add-btn  mt-0"><button title="Back" type="button" class="mt-0" onclick="history.back()">Back</button></div>
                                    </li>
                                    <li>
                                        <div class="add-btn  mt-0">
                                            <a href="https://connect.stripe.com/express/oauth/authorize?client_id={{env('STRIPE_CLIENT')}}&state=AU&stripe_user[email]={{$aGetDtl['sEmail_Id']}}">
                                                <button title="Connect with Stripe" type="button" class="mt-0" style="width: 150px;">Connect with Stripe</button>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                </main>
            </form>
        </div>
    </div>
@include('admin_panel.layouts.footer')