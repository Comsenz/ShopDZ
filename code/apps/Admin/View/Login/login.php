<!DOCTYPE html>
<html>
<!-- Mirrored from www.zi-han.net/theme/hplus/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:16:41 GMT -->
<head>
    <title>商城-登录</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <link rel="shortcut icon" href="__PUBLIC__/favicon.ico">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/style.min862f.css?v=4.1.0">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/index.css"/>
    <script type="text/javascript" src="__PUBLIC__/admin/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/admin/js/common.js"></script>
	<script type="text/javascript" src="__PUBLIC__/admin/js/login.js"></script>
	<script type="text/javascript" src="__PUBLIC__/admin/js/jquery.md5.js"></script>
</head>
<body class="gray-bg">
<div class="wrapper login-bg" >
	<div class="loginCon">
	
		<img src="__PUBLIC__/admin/images/loginlogo.png" alt="" class="loginlogo"/>
		<p class="edition">【B2C】电商系统管理中心&nbsp;&nbsp;|&nbsp;&nbsp;<span>V<?php echo SHOPDZ_VERSION;?></span></p>
		<form id="loginform" action="<?php echo U('/Login/login');?>" onsubmit="pwmd5('password');return false" >
		<div class="loginbox">
			<div class="logininp-box namebox">
				<span class="login-icon"><i class="name-icon"></i></span>
				<input type="text" name="name"  id="" class="login-inp name-inp" placeholder="请输入用户名"/>
			</div>
			<div class="logininp-box passwordbox">
				<span class="login-icon"><i class="password-icon"></i></span>
				<input type="password" name="pwd"  id="password"class="login-inp password-inp" placeholder="请输入密码"/>
			</div>
		</div>
		
		<div class="VerificationCode">
			<input type="text" name="verify" id="" value="" class="code-write left" placeholder="请输入验证码"/>
			<p class="code-show right"><img class="logincode-img" src="<?=U('/login/showVerifyCode')?>" id="click_verify" rel-src="<?=U('/login/showVerifyCode')?>"></p>
		</div>
			<p class='login-tips'></p>
		<input type="button" name="" id="" value="登录" class="login-btn"/>
		</form>
	</div>
</div>
<script type="text/javascript">
		$(function(){
		document.onkeydown=function(event){ 
			e = event ? event :(window.event ? window.event : null); 
			if(e.keyCode==13){
				  $(".login-btn").trigger("click");
			}
		}
				var flag = true;
				$('.login-btn').click(function() {
					input = 0
					$('input').each(function() {
						var _this = this;
						if($(_this).val() =='') {
									$(_this).addClass('inp-waring2');
									input =1;
								}
						$(_this).bind({
							focus:function() {
								if($(_this).val() =='') {
									$(_this).addClass('inp-waring2');
									input =1;
								}else{
									$(_this).removeClass('inp-waring2');
								}
							},
							blur:function() {
								if($(_this).val() =='') {
									$(_this).addClass('inp-waring2');
									input =1;
								}else{
									$(_this).removeClass('inp-waring2');
								}
							}
						});
					});
					if(input) return;
					var pw = $('#password').val();
					pwmd5('password');
					url = $('#loginform').attr('action');
					data = $('#loginform').serialize();
					$.post(url,data,function(data) {
						if(data.status != '0') {
							window.location.href = data.url;
						}else {
							$('#password').val(pw);
							$('.login-tips').html(data.info);
						}
					});
				});
				$('#click_verify').click(function(){
					var src = $(this).attr('rel-src');
					$(this).attr('src',src+'?rand='+Math.random());
				});
			});
	$(function(){
		$('.name-inp').focus(function(){
			$(this).parents('.logininp-box').animate({opacity:"1"},500);
			$(this).parents('.logininp-box').find('.name-icon').addClass('namefocus-icon');
		})
		$('.password-inp').focus(function(){
			$(this).parents('.logininp-box').animate({opacity:"1"},500);
			$(this).parents('.logininp-box').find('.password-icon').addClass('passwordfocus-icon');
		})
		$('.code-write').focus(function(){
			$(this).animate({opacity:"1"},500);
		})
	})
	
</script>
</body>

</html>
