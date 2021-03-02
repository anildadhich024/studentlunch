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
            @include('parent_panel.layouts.side_panel')
            <main>
                <div class="page-breadcrumb">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="page-title">Dashboard</h4>
                        </div>
                    </div>
                    @include('admin_panel.layouts.message')
                    @if(empty($oPlnDtl))
                        <div class="alert alert-success">
                            Would you like to change to a paid subscription ? <span><a href="{{url('/parent_panel/manage_subscription')}}"><b>Manage Subscription</b></a></span>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-4 col-lg-4 pb-3">
                            <div class="welcome-box">
                                <div class="welcome-box1">
                                    <div class="left-text-holder">
                                        <h4>Welcome Back !</h4>
                                        <h5>Parents Dashboard</h5>
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
                                    <div class="view_profile_btn"><a href="{{url('parent_panel/manage_account')}}"> View Profile </a></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-8 ">
                            <div class="row justify-content-start">
                                    <div class="col-6  col-sm-4  col-md-4 px-1-7">
                                        <div class="boxes_dash row h-100">
                                            <div class="total-div col-12 px-0">
                                                <h5>Children</h5>
                                                <p>{{ $aCntChld['nTtlRec'] }}</p>
                                            </div>
                                            <a href="{{url('parent_panel/manage_account')}}"  class="  text-center pr-0">
                                                <div class="icon-div m-auto"><i class="fa fa-user"></i></div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-6  col-sm-4  col-md-4 px-1-7">
                                        <div class="boxes_dash row h-100">
                                            <div class="total-div col-12 px-0">
                                                <h5>Total Order</h5>
                                                <p>{{ $aTtlOrd['nTtlRec'] }}</p>
                                            </div>
                                            <a href="{{url('parent_panel/manage_order')}}"  class="  text-center pr-0">
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
                                            <a href="{{url('parent_panel/manage_order')}}?nOrdrStatus={{config('constant.ORDER_STATUS.Pending')}}"  class="  text-center pr-0">
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
                                            <a href="{{url('parent_panel/manage_order')}}?nOrdrStatus={{config('constant.ORDER_STATUS.Delivered')}}" class="  text-center pr-0">
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
                                            <a href="{{url('parent_panel/manage_order')}}?nOrdrStatus={{config('constant.ORDER_STATUS.Cancelled')}}"  class="  text-center pr-0">
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
                                            <a href="{{url('parent_panel/manage_order')}}?nOrdrStatus={{config('constant.ORDER_STATUS.Pending')}}"  class="  text-center pr-0">
                                                <div class="icon-div m-auto"><i class="fa fa-bars"></i></div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <form action="{{url('parent_panel/manage_order/export')}}" method="get" id="export_form">
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
<!-- <script src="https://js.stripe.com/v3/"></script> -->
<script>
/*var buyBtn = document.getElementById('payButton');
//var responseContainer = document.getElementById('paymentResponse');
    
// Create a Checkout Session with the selected product
var createCheckoutSession = function (stripe) {
    return fetch(APP_URL+"/parent_panel/purchases/subscription", {
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
});*/

$("#FreePln").on("click", function(e) {
    if(confirm("Are you sure to subscription free plan ? ") == true)
    {
        window.location=APP_URL+"/parent_panel/plan/free";
    }
});

$(document).ready(function() {
    $("#CardForm").on("submit", function() {
        $(".required").removeClass("is-invalid");
        $(".required").attr("readonly",true);
        $(".btnhover").attr("disabled",true);
        var required = $(this).find(".required").filter(function() {
          return this.value == '';
        });

        if(required.length > 0) 
        {
            required.addClass("is-invalid");
        } 
        else 
        {
            $('#loadingBox').removeClass('d-none');
            $('.card-error').addClass('d-none').removeClass('alert alert-danger');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="csrf-token"]').attr('value')
                },
                url:  $(this).attr("action"),
                type: "POST",
                data: $(this).serialize(),
                success: function(res) 
                {
                    response = JSON.parse(res);
                    if(response.Status) 
                    {
                        $('#loadingBox').addClass('d-none');
                        $('.card-error').removeClass('d-none alert-danger').addClass('alert alert-success').html(response.Message);
                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    } 
                    else 
                    {
                        $('#loadingBox').addClass('d-none');
                        $('.card-error').removeClass('d-none alert-success').addClass('alert alert-danger').html(response.Message);
                        $(".required").attr("readonly",false);
                        $(".btnhover").attr("disabled",false);
                    }
                }
            });
        }
        return false;
    });
});
</script>

<div class="modal fade" id="CardModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('parent_panel/plan/paid')}}" method="post" id="CardForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Add Card Details</h4>
                </div>
                <div class="card-error d-none"></div>
                <div class="modal-body card-commission-section" style="margin-bottom: 0px;">
                    <div class="row account-form">
                        <div class="col">
                            <label>Name on Card</label>
                            <input type="text" name="sCardHolder" class="form-control required" onkeypress="return IsAlpha(event, this.value, '30')" tabindex="1">
                        </div>
                        <div class="col">
                            <label>Card Number</label>
                            <input type="text" name="sCardNumber" class="form-control required"  onkeypress="return IsCardNum(event, this.value, '19')" size='20' tabindex="2" id="sCardNum">
                        </div>
                    </div>
                    <div class="row account-form">
                        <div class="col">
                            <label>Expiry Month</label>
                            <select class='form-control required' name="sExpMnth" tabindex="3">
                                <option value="">==MM==</option>
                                @php
                                $m = 1;
                                @endphp
                                @for($m==1;$m<=12;$m++)
                                    <option value="{{str_pad($m, 2, 0, STR_PAD_LEFT)}}">{{str_pad($m, 2, 0, STR_PAD_LEFT)}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col">
                            <label>Expiry Year</label>
                            <input type="text" name="sExpYear" class="form-control required"  onkeypress="return IsNumber(event, this.value, '4')" tabindex="4">
                        </div>
                        <div class="col">
                            <label>CVC</label>
                            <input type="text" name="sCvcCode" class="form-control required"  onkeypress="return IsNumber(event, this.value, '3')" tabindex="5" maxlength="5" minlength="3">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-lg-12 services-btns">
                            <ul class="m-auto text-center">
                                <li>
                                    <div class="add-btn mt-0"><button class="mt-0 btnhover" tabindex="6" data-dismiss="modal" aria-label="Close">Cancel</button></div>
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