@if(Session::has('Success'))
<div class="alert alert-success">
    {{Session::get('Success')}}
</div>
@endif
@if(Session::has('Failed'))
<div class="alert alert-danger">
    {{Session::get('Failed')}}
</div>
@endif
@if(Session::has('Alert'))
<div class="alert alert-warning">
    {{Session::get('Alert')}}
</div>
@endif