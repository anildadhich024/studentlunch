<title>{{$sTitle}}</title>
<center>
    <div style="width:600px;">
        <div style="margin: 100px auto;">
            <div style="padding-bottom: 15px;"><img src="{{url('public/images/loder.gif')}}" width="100"></div>
            @if(empty($sPayStatus) || $sPayStatus != 'succeeded')
            	<strong style="font-size: 29px; color: #a05416; padding-top: 20px;">Payment Failed !</strong>
                <p style="font-weight: bold;">Payment Confirmation, Do not refresh. Please wait redirecting.........</p>
            @else
            	<strong style="font-size: 29px; color: #3ab818; padding-top: 20px;">Payment Successfull !</strong>
                <p style="font-weight: bold;">Payment Confirmation, Do not refresh. Please wait redirecting.........</p>
            @endif
        </div>    
    </div>
</center>

<script src="{{url('assets/js/jquery.min.js')}}"></script>
<script type="text/javascript">
var APP_URL = "{{url('/')}}";
$(document).ready(function() {
    window.setTimeout(function() {
        	window.location = APP_URL+"/parent_panel";
    }, 5000);
});
</script>