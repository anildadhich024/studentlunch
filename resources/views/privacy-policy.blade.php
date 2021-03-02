@include('layouts.header')
<style type="text/css">
h3{
   font-size: 19px !important;
   line-height: 13px !important;
   margin-bottom: 5px !important;
   margin-top: 35px !important;
}
p{
   font-size: 14px !important;
   color: #000 !important;
   padding-left: 30px !important;
   margin-bottom:5px !important;
}
ul{
   padding-left: 50px !important;
}
ul>li>p{
   padding-left: 0px !important;
   margin-bottom:5px !important;
}
u{
   font-size: 30px !important;
   text-align: center !important;
   padding-bottom:15px  !important;
}
</style>
<div class="welcome-section section-padding">
	<div class="container">
      <div class="row">
         <div class="col-12">
            <div class="text-box">
              <h3 class="hadding"><u>Privacy Policy</u></h3>
              <?= $StngDtl->sPrivacy ?>
            </div>
            <!-- <button type="button" onclick="close()" class="btn-blue">Close</button> -->
         </div>
      </div>
       <div class="row">
       <div class="col-4">
       </div>
         <div class="col-3">
                  <input type="button" class="btn-blue" onclick="window.opener=null; window.close(); return false;"  value="Close"/>
         </div>
         </div>
    </div>
</div>
 
@include('layouts.footer')