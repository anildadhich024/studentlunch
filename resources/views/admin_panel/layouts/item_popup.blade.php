<script type="text/javascript">
$(document).on("click", ".add_cart", function () {
    $('.modal-header .modal-title').html($(this).data('name'));
    $('.modal-header p').html($(this).data('des'));
    $('.quantityinputsection .amount').html('$'+$(this).data('price'));
    $('.quantityinputsection .amount').html('$'+$(this).data('price'));
    $('.quantityinputsection #sItemPrc').val($(this).data('price'));
    $('.quantityinputsection #nItemQty').val(1);
    $('.quantityinputsection #lItemIdNo').val($(this).data('id'));
    $('.quantityinputsection .numberinputdiv').html(1);
    $('#ItemVrant').html('');
    $('#addToCartPopup').modal('show');
    if($(this).data('option') == 0)
    {
        $('#ItemVrant').addClass('d-none');
    }
    else
    {
        $('#ItemVrant').removeClass('d-none');
        $('#ItemVrant').html('<div class="item_loder"><div><img src="images/loder.gif" width="90"></div><strong class="loading_text">Processing, Please wait....</strong></div>');
        $.ajax({
            url: APP_URL + "/get_varient/list?lItemIdNo=" + btoa($(this).data('id')),
            success: function (response) {
                $('#ItemVrant').html(response);
            }
        });
    }
});

$(document).on("click", ".inputquantity .minus", function () {
    nItemQty = parseInt($('.quantityinputsection #nItemQty').val());
    sItemPrc = parseFloat($('.quantityinputsection #sItemPrc').val());
    if(nItemQty > 1)
    {
        nItemQty = nItemQty - 1;
        $('.quantityinputsection #nItemQty').val(nItemQty);
        $('.quantityinputsection .numberinputdiv').html(nItemQty);
        $('.quantityinputsection .amount').html('$'+(nItemQty*sItemPrc).toFixed(2));
    }
});

$(document).on("click", ".inputquantity .plus", function () {
    nItemQty = parseInt($('.quantityinputsection #nItemQty').val());
    sItemPrc = parseFloat($('.quantityinputsection #sItemPrc').val());
    if(nItemQty < 10)
    {
        nItemQty = nItemQty + 1;
        $('.quantityinputsection #nItemQty').val(nItemQty);
        $('.quantityinputsection .numberinputdiv').html(nItemQty);
        $('.quantityinputsection .amount').html('$'+(nItemQty*sItemPrc).toFixed(2));
    }
});
</script>
<div class="modal fade" id="addToCartPopup" tabindex="-1" role="dialog" aria-labelledby="addToCartPopupTitle"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{url('/user/save_cart')}}" method="post" id="CartForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="addToCartPopupTitle">
                        </h5>
                        <p>
                        </p>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="quantityinputsection pt-3">
                        <input type="hidden" name="sItemPrc" id="sItemPrc" value="">
                        <input type="hidden" name="nItemQty" id="nItemQty" value="1">
                        <input type="hidden" name="lItemIdNo" id="lItemIdNo" value="">
                        <div class="title">
                            Quantity
                        </div>
                        <div class="d-flex justify-content-between pt-3 ">
                            <div class="inputquantity d-flex align-items-center">
                                <button class="minus" type="button"></button>
                                <span class="numberfield">
                                    <span class="numberinputdiv">1</span>
                                </span>
                                <button class="plus" type="button"></button>
                            </div>
                            <div class="amount"></div>
                        </div>
                    </div>
                    <div id="ItemVrant"></div>
                    <span class="borderbottom1px"></span>
                    <div class="addbtncart">
                        <button class="btn" id="addToCart" type="submit">
                            Add To Cart
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#CartForm").on("submit", function() {
        $('#loadingBox').removeClass('d-none');
        $('.login-error').addClass('d-none').removeClass('alert alert-danger');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('input[name="csrf-token"]').attr('value')
            },
            url:  $(this).attr("action"),
            type: "POST",
            data: $(this).serialize(),
            success: function(res) 
            {
                $('#loadingBox').addClass('d-none');
                $('#addToCartPopup').modal('hide');
                GetCartDtl();
            }
        });
        return false;
    });
});
</script>