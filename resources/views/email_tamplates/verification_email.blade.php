<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Email Verification</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
<style type="text/css">
	*{font-family: 'Roboto', sans-serif;margin:0px;padding: 0px;}
	h2{color: #000; font-size: 34px;font-weight: 400;margin-bottom: 10px;}
	strong{color: #000; font-size: 18px;font-weight: 300;margin-bottom: 15px;}
	p{color: #555555;font-size: 15px;font-weight: 300;margin-bottom: 20px;line-height: 25px;}
</style>
</head>

<body>
	<div style="max-width: 600px; margin:0 auto;">
		<div style="display: block;position: relative;">
			<img src="{{url('public/images/header.jpg')}}" style="width: 100%">
			<a href="#"><img src="{{url('public/images/logo_email.png')}}" style="position: absolute;top: 24%; right: 5%; z-index: 2"></a>
		</div>
		<div style ="display:block; position:relative; padding:25px; background-color: #eef0f3">
			  <div style="display: block; position: relative;padding: 32px;background-color: #fff;border-radius: 10px;box-shadow: 0px 0px 10px #e2e2e2;">
			  	  <p><strong>Dear {{$sUserName}}</strong></p>
			  	  <p>You have received this message because your email address has been registerd with "MyLunchOrder.Online" plateform. Verify yourself and confirm your email by clicking below.</p>
			  	  <p style="text-align: center;">Clicking on the button below or use this link</p>
			  	  <!-- <p style="text-align: center;"><a href="https://mylunchorder.online/account/verify?sEmailId={{base64_encode($sEmailId)}}&lRecIdNo={{base64_encode($lRecIdNo)}}&nUserType={{base64_encode($nUserType)}}">https://mylunchorder.online/account/verify?sEmailId={{base64_encode($sEmailId)}}&lRecIdNo={{base64_encode($lRecIdNo)}}&nUserType={{base64_encode($nUserType)}}</a></p>
			  	  <p style="text-align: center;"><a href="https://mylunchorder.online/account/verify?sEmailId={{base64_encode($sEmailId)}}&lRecIdNo={{base64_encode($lRecIdNo)}}&nUserType={{base64_encode($nUserType)}}" style="display: inline-block;background-color: #203869;color: #fff;position: relative;border-radius: 60px;padding: 12px 30px;text-decoration: none;">Verify Email</a></p> -->
				  <p style="text-align: center;"><a href="https://studentlunch.i4acmmosmedia.com/account/verify?sEmailId={{base64_encode($sEmailId)}}&lRecIdNo={{base64_encode($lRecIdNo)}}&nUserType={{base64_encode($nUserType)}}">https://studentlunch.i4acmmosmedia.com/account/verify?sEmailId={{base64_encode($sEmailId)}}&lRecIdNo={{base64_encode($lRecIdNo)}}&nUserType={{base64_encode($nUserType)}}</a></p>
			  	  <p style="text-align: center;"><a href="https://studentlunch.i4acmmosmedia.com/account/verify?sEmailId={{base64_encode($sEmailId)}}&lRecIdNo={{base64_encode($lRecIdNo)}}&nUserType={{base64_encode($nUserType)}}" style="display: inline-block;background-color: #203869;color: #fff;position: relative;border-radius: 60px;padding: 12px 30px;text-decoration: none;">Verify Email</a></p>
			 
				</div>
			   <div style="display:block; position:relative; padding: 15px;">
			   	  <div style="display: block; text-align: center; margin-bottom: 20px">
			   	  	<a href="#" style="display: inline-block;margin:8px 5px; background-image: url(https://mylunchorder.online/images/social-media.jpg); background-repeat: no-repeat; width: 33px; height: 33px; background-position:-13px -6px; font-size: 0px">facebook</a>
			   	  	<a href="#" style="display: inline-block;margin:8px 5px; background-image: url(https://mylunchorder.online/images/social-media.jpg); background-repeat: no-repeat; width: 33px; height: 33px; background-position: -58px -6px; font-size: 0px">instagram</a>
			   	  	<a href="#" style="display: inline-block;margin:8px 5px; background-image: url(https://mylunchorder.online/images/social-media.jpg); background-repeat: no-repeat; width: 33px; height: 33px; background-position: -103px -6px; font-size: 0px">twitter</a>
			   	  	<a href="#" style="display: inline-block;margin:8px 5px; background-image: url(https://mylunchorder.online/images/social-media.jpg); background-repeat: no-repeat; width: 33px; height: 33px; background-position: -148px -6px; font-size: 0px">pintrest</a>
			   	  	<a href="#" style="display: inline-block;margin:8px 5px; background-image: url(https://mylunchorder.online/images/social-media.jpg); background-repeat: no-repeat; width: 33px; height: 33px; background-position: -193px -6px; font-size: 0px">youtube</a>
			   	  </div>
                     <p style="text-align: center;font-size: 13px;color: #555555;line-height: 22px;">You’re receiving this email because you recently created a new MyLuncuOrder account or added a new email address. If this wasn’t you, please ignore this email and please don’t click the above button. You will not receive any further emails from us.</p>
			   </div>
		</div>
	</div>
</body>
</html>