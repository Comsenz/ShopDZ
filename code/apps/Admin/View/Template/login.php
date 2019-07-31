<div class="wrapper login-bg">
	<div class="loginCon">
		<img src="images/loginlogo.png" alt="" class="loginlogo"/>
		<div class="loginbox">
			<div class="logininp-box namebox">
				<span class="login-icon"><i class="name-icon"></i></span>
				<input type="text" name="" id="" class="login-inp name-inp" placeholder="请输入用户名"/>
			</div>
			<div class="logininp-box passwordbox">
				<span class="login-icon"><i class="password-icon"></i></span>
				<input type="text" name="" id="" class="login-inp password-inp" placeholder="请输入密码"/>
			</div>
		</div>
		
		
		<div class="VerificationCode">
			<input type="text" name="" id="" value="" class="code-write left" placeholder="请输入验证码"/>
			<p class="code-show right"></p>
		</div>
		<input type="button" name="" id="" value="登录" class="login-btn"/>
	</div>
</div>


<script type="text/javascript" type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
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