<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to MyLunchOrder.Online</title>
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
			  	  <p>Welcome to MyLunchOrder.Online a new way for your business to provide friendly service to your customers!</p>
			  	  <p>MyLunchOrder.Online has been established keeping you in mind (our local businesses who operate around the local schools). COVID-19 has given us all a lot to think about and going forward only having an online presences will not be sufficient as businesses need a technology solution that enables customers to connect with you and vice versa.</p>
			  	  <p>You have taken the first step towards connecting with your customers and we thank you for that, but before you start selling we need to ensure that you have everything in place so your customers get a pleasant experience.</p>
			  	  <p>Login to your account and set up your Lunch Menu, once you’ve completed this please hit/click the submit button and our experts will take care of the rest.</p>
			  	  <p>If you have any question send us an email at <a href="mailto:info@loggedin.com.au">info@loggedin.com.au</a></p>
			  	  <p>Kind regards,<br/>MyLunchOrder™ Team</p>
			  </div>
			   <div style="display:block; position:relative; padding: 15px;">
			   	<div style="display: block; text-align: center; margin-bottom: 20px">
			   	  	<a href="#" style="display: inline-block;margin:8px 5px; background-image: url(https://mylunchorder.online/images/social-media.jpg); background-repeat: no-repeat; width: 33px; height: 33px; background-position:-13px -6px; font-size: 0px">facebook</a>
			   	  	<a href="#" style="display: inline-block;margin:8px 5px; background-image: url(https://mylunchorder.online/images/social-media.jpg); background-repeat: no-repeat; width: 33px; height: 33px; background-position: -58px -6px; font-size: 0px">instagram</a>
			   	  	<a href="#" style="display: inline-block;margin:8px 5px; background-image: url(https://mylunchorder.online/images/social-media.jpg); background-repeat: no-repeat; width: 33px; height: 33px; background-position: -103px -6px; font-size: 0px">twitter</a>
			   	  	<a href="#" style="display: inline-block;margin:8px 5px; background-image: url(https://mylunchorder.online/images/social-media.jpg); background-repeat: no-repeat; width: 33px; height: 33px; background-position: -148px -6px; font-size: 0px">pintrest</a>
			   	  	<a href="#" style="display: inline-block;margin:8px 5px; background-image: url(https://mylunchorder.online/images/social-media.jpg); background-repeat: no-repeat; width: 33px; height: 33px; background-position: -193px -6px; font-size: 0px">youtube</a>
			   	</div>
			   </div>
		</div>
	</div>
</body>
</html>