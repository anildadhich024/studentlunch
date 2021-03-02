@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('parent_panel.layouts.side_panel')
            <form action="{{url('milkbar_panel/change_password/save')}}" method="post" id="general_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <main>
                    <div class="page-breadcrumb">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="page-title">Manage Subscription</h4>
                            </div>
                        </div>
                    </div>
                    @include('admin_panel.layouts.message')
                    <div class="container-fluid card-commission-section  parent-details-section">
                        <div class="row account-form">
                            <div class="col">
                                @if($aPrntsDtl['nPln_Status'] == config('constant.PRNT_PLN.FREE'))
                                    <p>Would you like to change to a paid subscription ?</p>
                                @else
                                    <p>You have paid subscription plan.</p>
                                @endif    
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 services-btns">
                                <ul class="m-auto text-center pt-4 pb-4">
                                    <li>
                                        <div class="add-btn  mt-0">
                                            <button title="Go Back" type="button" class="mt-0" onclick="history.back()">GO BACK</button>
                                            @if($aPrntsDtl['nPln_Status'] == config('constant.PRNT_PLN.FREE'))
                                                <button title="Paid Subscription" type="button" class="mt-0" style="width: 150px;" data-toggle="modal" data-target="#CardModel">GO WITH PAID</button>
                                            @else
                                                <button title="Free Subscription" type="button" class="mt-0" style="width: 150px;" id="FreePln">GO WITH FREE</button>
                                                <button title="Add Card" type="button" class="mt-0" style="width: 230px;" data-toggle="modal" data-target="#CardModel">UPDATE PAYMENT DETAILS</button>
                                            @endif
                                        </div>
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
<script type="text/javascript">
$("#FreePln").on("click", function(e) {
    if(confirm("Are you sure to subscription free plan ? ") == true)
    {
        window.location=APP_URL+"/parent_panel/manage_subscription/free";
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
        <form action="{{url('parent_panel/manage_subscription/paid')}}" method="post" id="CardForm">
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
                            <input type="text" name="sExpYear" class="form-control required"  onkeypress="return IsNumber(event, this.value, '4')" tabindex="4" minlength="2" max="4">
                        </div>
                        <div class="col">
                            <label>CVC</label>
                            <input type="text" name="sCvcCode" class="form-control required"  onkeypress="return IsNumber(event, this.value, '4')" tabindex="5" maxlength="4" minlength="3">
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