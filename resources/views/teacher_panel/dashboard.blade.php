@include('admin_panel.layouts.header')
    <style type="text/css">
    span{cursor: pointer;}
    </style>
    <meta name="csrf-token" value="{{ csrf_token() }}">
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('teacher_panel.layouts.side_panel')
            <main>
                <div class="page-breadcrumb">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="page-title">Dashboard</h4>
                        </div>
                    </div>
                    @include('admin_panel.layouts.message')
                    <div class="row">
                        <div class="col-md-4 col-lg-4 pb-3">
                            <div class="welcome-box">
                                <div class="welcome-box1">
                                    <div class="left-text-holder">
                                        <h4>Welcome Back !</h4>
                                        <h5>Teacher Dashboard</h5>
                                    </div>
                                    <div class="right-img-holder">
                                        <img src="assets/images/user2.png" style="max-width: 82%;">
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="welcome-box2">
                                    <div class="student-lunch-img">
                                        <img src="assets/images/user4.png">
                                    </div>
                                    <div class="heading-text">
                                        <h3>{{session('USER_NAME')}}</h3>
                                    </div>
                                    <div class="view_profile_btn"><a href="{{url('teacher_panel/manage_account')}}"> View Profile </a></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-8 ">
                            <div class="row justify-content-start">
                                    <div class="col-6  col-sm-4  col-md-4 px-1-7">
                                        <div class="boxes_dash row h-100">
                                            <div class="total-div col-12 px-0">
                                                <h5>Total Order</h5>
                                                <p>{{ $aTtlOrd['nTtlRec'] }}</p>
                                            </div>
                                            <a href="{{url('teacher_panel/manage_order')}}"  class="  text-center pr-0">
                                                <div class="icon-div m-auto"><i class="fa fa-bars"></i></div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-6  col-sm-4  col-md-4 px-1-7">
                                        <div class="boxes_dash row h-100">
                                            <div class="total-div col-12 px-0">
                                                <h5>Pending Orders</h5>
                                                <p>{{ $aPndgOrd['nTtlRec'] }}</p>
                                            </div>
                                            <a href="{{url('teacher_panel/manage_order')}}?nOrdrStatus={{config('constant.ORDER_STATUS.Pending')}}"  class="  text-center pr-0">
                                                <div class="icon-div m-auto"><i class="fa fa-bars"></i></div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-6  col-sm-4  col-md-4 px-1-7">
                                        <div class="boxes_dash row h-100">
                                            <div class="total-div col-12 px-0">
                                                <h5>Completed Orders</h5>
                                                <p>{{ $aDlvrdOrd['nTtlRec'] }}</p>
                                            </div>
                                            <a href="{{url('teacher_panel/manage_order')}}?nOrdrStatus={{config('constant.ORDER_STATUS.Delivered')}}" class="  text-center pr-0">
                                                <div class="icon-div m-auto"><i class="fa fa-bars"></i></div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-6  col-sm-4  col-md-4 px-1-7">
                                        <div class="boxes_dash row h-100">
                                            <div class="total-div col-12 px-0">
                                                <h5>Cancelled Orders</h5>
                                                <p>{{ $aTtlOrd['nTtlRec'] - ($aPndgOrd['nTtlRec'] + $aDlvrdOrd['nTtlRec'] + $aOverOrd['nTtlRec']) }}</p>
                                            </div>
                                            <a href="{{url('teacher_panel/manage_order')}}?nOrdrStatus={{config('constant.ORDER_STATUS.Cancelled')}}"  class="  text-center pr-0">
                                                <div class="icon-div m-auto" ><i class="fa fa-bars"></i></div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-6  col-sm-4  col-md-4 px-1-7">
                                        <div class="boxes_dash row h-100">
                                            <div class="total-div col-12 px-0">
                                                <h5>Overdue Orders</h5>
                                                <p>{{ $aOverOrd['nTtlRec'] }}</p>
                                            </div>
                                            <a href="{{url('teacher_panel/manage_order')}}?nOrdrStatus={{config('constant.ORDER_STATUS.Pending')}}"  class="  text-center pr-0">
                                                <div class="icon-div m-auto"><i class="fa fa-bars"></i></div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <form action="{{url('teacher_panel/manage_order/export')}}" method="get" id="export_form">
                                <div class="generate-report generate-report2" style="padding-bottom: 220px;">
                                    <h4>Generate Report</h4>
                                    <ul>
                                        <li>
                                            <input type="radio" id="t-option" name="nRprtDur" value="0" onclick="SetDur(0)" checked>
                                            <label for="t-option">Custom</label>
                                            <div class="check">
                                                <div class="inside"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <input type="radio" id="f-option" name="nRprtDur" value="30" onclick="SetDur(30)">
                                            <label for="f-option"> 30 Days </label>
                                            <div class="check"></div>
                                        </li>
                                        <li>
                                            <input type="radio" id="s-option" name="nRprtDur" value="60" onclick="SetDur(60)">
                                            <label for="s-option">60 Days</label>
                                            <div class="check">
                                                <div class="inside"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <input type="radio" id="l-option" name="nRprtDur" value="90" onclick="SetDur(90)">
                                            <label for="l-option">90 Days</label>
                                            <div class="check">
                                                <div class="inside"></div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                    <div class="form-group" style="margin-top: 30px;">
                                        <label for="exampleFormControlSelect1">Start Date</label>
                                        <input type="date" name="sFrmDate" placeholder="MM/DD/YYYY" required> 
                                    </div>
                                    <div class="form-group" style="margin-top: 30px;">
                                        <label for="exampleFormControlSelect2">End Date</label>
                                        <input type="date" name="sToDate" placeholder="MM/DD/YYYY" required> 
                                    </div>
                                    <div class="form-group" style="float: none;">
                                        <label>Order Status</label>
                                        <select name="nOrdrStatus">
                                            <option value="">All Order</option>
                                            @foreach(config('constant.ORDER_STATUS') as $sStatusName => $nOrdrStatus)
                                                <option value="{{$nOrdrStatus}}">{{$sStatusName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class='form-btns text-center'>
                                        <ul>
                                            <li><button type="submit" class="mr-0">Export Excel</button></li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <div class="generate-report">
                                <div class="text-center">{!! $chart->html() !!}</div>
								<div class="colors">
                                    <ul>
									@foreach($aLbl as $key => $sMnth)
                                        <li>
                                            <div class="color_box1" style="background-color: {{$aClrs[$key]}} !important;"></div>
                                            <div class="colors-text">
                                                <p>{{$sMnth}} {{$aValue[$key]}}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@include('admin_panel.layouts.footer')
{!! $chart->script() !!}
<script type="text/javascript">
function SetDur(nDur)
{
    if(nDur == 0)
    {
        $("input[name=sFrmDate]").attr('required',true);
        $("input[name=sToDate]").attr('required',true);
        $("input[name=sFrmDate]").attr('readonly',false);
        $("input[name=sToDate]").attr('readonly',false);
    }
    else
    {
        $("input[name=sFrmDate]").removeClass('is-invalid');
        $("input[name=sToDate]").removeClass('is-invalid');
        $("input[name=sFrmDate]").attr('required',false);
        $("input[name=sToDate]").attr('required',false);
        $("input[name=sFrmDate]").attr('readonly',true);
        $("input[name=sToDate]").attr('readonly',true); 
        $("input[name=sFrmDate]").val('');
        $("input[name=sToDate]").val('');  
    }
}


$("#export_form").attr("novalidate", "novalidate");
$("#export_form").on("submit", function(e) {
    $("input").removeClass("is-invalid");
    $("select").removeClass("is-invalid");
    $("textarea").removeClass("is-invalid");
    var errorFlag = false;
    var sFrmDate    = $("input[name=sFrmDate]").val();
    var sToDate     = $("input[name=sToDate]").val();
    var nRprtDur    = $("input[name=nRprtDur]").val();
    $(this).find("input, select, textarea").each(function() {

        if($(this).prop("required") && $(this).val() == "") 
        {
            $(this).addClass("is-invalid");
            errorFlag = true;
            return false;
        }

        if(nRprtDur == 0)
        {   
            $("input[name=sFrmDate]").addClass('is-invalid');
            $("input[name=sToDate]").addClass('is-invalid');
            if(sFrmDate <= sToDate)
            {
                errorFlag = false;
            }
            else
            {
                alert('From Date should be less then To Date..');
                errorFlag = true;
                return false;
            }
        }
        else
        {
            errorFlag = false;
        }
    });

    if(errorFlag) 
    {
        e.preventDefault();
    }
});
</script>
<script src="https://js.stripe.com/v3/"></script>
<script>
var buyBtn = document.getElementById('payButton');
//var responseContainer = document.getElementById('paymentResponse');
    
// Create a Checkout Session with the selected product
var createCheckoutSession = function (stripe) {
    return fetch(APP_URL+"/teacher_panel/purchases/subscription", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('value')
        },
        body: JSON.stringify({
            checkoutSession: 1,
        }),
    }).then(function (result) {
        return result.json();
    });
};

// Handle any errors returned from Checkout
var handleResult = function (result) {
    if (result.error) {
        responseContainer.innerHTML = '<p>'+result.error.message+'</p>';
    }
    buyBtn.disabled = false;
    buyBtn.textContent = 'Subscribe Now';
};

// Specify Stripe publishable key to initialize Stripe.js
var stripe = Stripe('{{env('STRIPE_KEY')}}');

buyBtn.addEventListener("click", function (evt) {
    buyBtn.disabled = true;
    buyBtn.textContent = 'Please wait...';
    createCheckoutSession().then(function (data) {
        if(data.sessionId){
            stripe.redirectToCheckout({
                sessionId: data.sessionId,
            }).then(handleResult);
        }else{
            handleResult(data);
        }
    });
});
</script>