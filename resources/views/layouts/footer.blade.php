<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-3">
                <h3>Contact Us</h3>
                <ul class="contact-list">
                    <li class="address">
                        Plot No. 1024 Panchwati Colony 
                        Bhascker Ratanada Jaipur
                    </li>
                    <li class="mobile">
                        +91-1234567892, +91-1234567892. 
                    </li>
                    <li class="email">
                        nmsmagic1994@mail.com
                    </li>
                </ul><br />
                <a href="#"><img src="{{url('images/map.jpg')}}" /></a>
            </div>
            <div class="col-9 text-center">
                <a href="#"><img src="{{url('images/footer-logo.jpg')}}" id="footer-logo" /></a>
                <ul class="footer-menu">
                    <li><a href="#">Home</a></li>               
                    <li><a href="#">About Us  </a></li>             
                    <li><a href="#">Menu  </a></li>             
                    <li><a href="#">Services  </a></li>             
                    <li><a href="#">Offers     </a></li>          
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Privacy Policy</a></li>             
                    <li><a href="#">Refund Policy</a></li>          
                    <li><a href="#">Delivery Conditions</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
                <ul class="footer-social-media">
                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>               
                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>               
                    <li><a href="#"><i class="fa fa-twitter"></i> </a></li>             
                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>             
                    <li><a href="#"><i class="fa fa-pinterest"></i></a></li>             
                    <li><a href="#"><i class="fa fa-youtube"></i></a></li>          
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="footer-bottom">
    <p>Design and developed by <strong><a href="http://i4consulting.org/" target="_blank"> i4 Consulting Pvt. Ltd.</a></strong></p>
</div>
<script>
$(document).ready(function(){
});
var APP_URL = '{{url('')}}';
function toogle_menu(x) {
  x.classList.toggle("change");
  $("#menu-area").slideToggle();
}
</script>
<script type="text/javascript" src="js/form-validation.min.js?v={{date('ymdHis')}}"></script>
<script type="text/javascript" src="js/common-script.js?v={{date('ymdHis')}}"></script>
<script type="text/javascript" src="js/abn.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap js -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script src="assets/scripts/popper.min.js" ></script>
<script src="assets/scripts/bootstrap.min.js" ></script>
<script src="assets/scripts/library.min.js"></script>
<script src="assets/scripts/main.js"></script>
<script src="assets/js/custome-script.js?v={{date('ymdHis')}}"></script>

</body>
</html>

<div class="loading_bg loader-block d-none" id="loadingBox">
    <div class="loading_popup">
        <div><img src="images/loder.gif" width="90"></div>
        <strong class="loading_text">Processing, Please wait....</strong>
    </div>
</div>


