//输入框获取焦点事件


$(function(){
	if(key){
		window.location.href=WapSiteUrl+'/member.html';	
	}
	var conf = getwebConf();
	if(isWeiXin()){
		if(conf.wx_login == 'on'){
			$('#wxlogin').show();
		}
		if(conf.smg_login == '1'){
			$('#tomlogin').show();
		}
	}else{
		if(conf.smg_login == '1') {
			$('#tomlogin').show();
		}
	}
	verify_status = getwebConf('verify_status');
	if(verify_status =='0'){
		$('.verification').parent().hide();
	}else{
		//获取图形验证码
		$('.verification').attr('src',ApiUrl+'/base/showVerifyCode');
		$('.verification').attr('rel-src',ApiUrl+'/base/showVerifyCode');
		$('.verification').click(function(){ref_verifi();});
	}
	var shop_login = getwebConf('shop_login');
	if(typeof(shop_login) != 'undefined'){
			$('.sign-img1').attr('src',shop_login);
	}

	$('.sign-inp').bind({ 
		keyup:function(){
			if (this.value != ''){
				$(this).next('.dele').css('display','block');
			} else {
				this.value = this.defaultValue; 
			}
		}, 
		blur:function(){ 
			if (this.value == ""){
				this.value = this.defaultValue; 
				$(this).next('.dele').css('display','none');
			} 
		} 
	}); 
	$('.dele').bind('click',function(){
		$(this).prev('.sign-inp').val("");
		$(this).css('display','none');
		$('#sign-btn1').removeClass('send-out-bg');
		$('#register-btn1').removeClass('send-out-bg');
	});
	$('#wxlogin').bind('click',function(){

		if(document.referrer.length > 0){
			var redirect = document.referrer;//上级网址
		}else{
			var redirect = WapSiteUrl+'/member.html';
		}
		addcookie('redirect',encodeURI(redirect),24);
		window.location.href = ApiUrl + '/Member/wxlogin?redirect='+redirect;
	});
	$('#tomlogin').bind('click',function(){

		if(document.referrer.length > 0){
			var redirect = document.referrer;//上级网址
		}else{
			var redirect = WapSiteUrl+'/member.html';
		}
		addcookie('redirect',encodeURI(redirect),24);
		window.location.href = WapSiteUrl+'/mlogin.html';
	});
	var url_path_arr = ['passwd-find'];
	var url_path = url_path_arr.join("|");
	var pattern = eval('/('+url_path+')/ig');
	var referurl = document.referrer;//上级网址

	var flag = true;
	/* 判断登陆页手机是否正常 */
	$(document).on('keyup','#sign-phone',function(){
		if($('#sign-phone').val().length >0 && $('#sign-pword').val().length > 0){
			$('#sign-btn1').addClass('send-out-bg');
		}else{
			$('#sign-btn1').removeClass('send-out-bg');
		}
	});
	/* 判断登陆页密码是否正常 */
	$(document).on('keyup','#sign-pword',function(){
		if($('#sign-phone').val().length >0 && $('#sign-pword').val().length > 0){
			$('#sign-btn1').addClass('send-out-bg');
		}else{
			$('#sign-btn1').removeClass('send-out-bg');
		}
	});
	//判断登录页面手机号输入是否正确
	$('#sign-btn1').click(function(){
		//judge($("#sign-phone"),$("#sign-pword"),$('#sign-remind'));		
		input = 0
		$('.content1 input').each(function() {
			if($(this).val() == ''){
				shopdz_alert($(this).attr('placeholder'));
				input = 1;
				return false;
			}

		});
		if(input) return;
		pwd = $('#sign-pword').val();
		url = ApiUrl+'/member/login';
		pwmd5('sign-pword');
		var mobile = $('#sign-phone').val();
		var passwd = $('#sign-pword').val();
		var client = 'wap';
		$.post(url,{mobile:mobile,passwd:passwd,client:client},function(result) {
			result = eval("("+result+")");
			if(result.code) {
				$('#sign-pword').val(pwd);
				shopdz_alert(result.msg);
			} else {
				if(typeof(result.data.key)=='undefined'){
					return false;
				}else{
					$('#sign-btn1').unbind('click');
					$('#sign-btn1').removeClass('send-out-bg');

						if(referurl!= ''){
							window.location.href = referurl+'?t='+Math.random();
						}else{
							window.location.href = WapSiteUrl+'?t='+Math.random();
						}	

				
				}
			}
		
		});	
		
	});
});


//注册页面显示
$("#register1").bind('click',function(){
	window.location.href = WapSiteUrl + '/register.html'
	//$('.content1').slideUp("slow")
	//$('.content2').slideDown("slow");
});
$('#sign-back').bind('click',function(){
	window.location.href = WapSiteUrl + '/login.html'
	//$('.content1').slideDown("slow");
	//$('.content2').slideUp("slow");
});
$('#sign-back1').bind('click',function(){
	$('.content1').slideDown("slow");
	$('.content3').slideUp("slow");
});

$(function(){
	//判断是否同意协议
	$(".agree-btn").bind('click',function(){
		if (!$('.agree-btn').prop('checked')) {
			$(this).parents("div.remind2").prev("div.signbox").find("div.sign-btn1").children("#register-btn2").addClass("enjoin");
			//alert("0000");
		} else {
			$(this).parents("div.remind2").prev("div.signbox").find("div.sign-btn1").children("#register-btn2").removeClass("enjoin");
		}
	})
	
})


//注册输入手机号校验
$("#register-phone,#register-verification,#register-pword").keyup(function(){
	var inpLen=$("#register-phone").val().length;
	var regexpw=/^1[3|4|5|7|8]\d{9}$/;
	var mobile=$('#register-phone').val();
	var verificatioLen=$("#register-verification").val().length;
	var inpLenP=$("#register-pword").val().length;
	var inpPw=$("#register-pword").val();
	var regexP2=/^\d{6}$/;
	verify_status =='0' ? verificatioLen = 4 : verificatioLen;
	if(inpLen==11  && verificatioLen==4){

		if($("#send_seccode_btn").find('span').length =='0' ) {
			$("#send_seccode_btn").addClass("send-out-bg");
		}
		$("#send_seccode_btn").unbind('click').bind('click',function(){
			sendRegcode();
		});
	
	}else{
		$("#send_seccode_btn").removeClass("send-out-bg");
		$("#send_seccode_btn").unbind("click");
	}

	if(inpLen==11 && regexpw.test(mobile) && regexP2.test(inpPw)) {
		$("#register-btn1").addClass("send-out-bg");
	}else{
		$("#register-btn1").removeClass("send-out-bg");
	}
})
/* 用户协定点击事件 */
$('.remind-check').click(function(){
	if($(this).is(':checked')){
		$("#register-btn1").addClass("send-out-bg");
	}else{
		$("#register-btn1").removeClass("send-out-bg");
	}
})

//注册第一步验证验短信验证码
$('#register-btn1').click(function(){
	/* 判断用户是否同意用户协定 */
	if(!$('.remind-check').is(':checked')){
		shopdz_alert('未同意注册协议');
		return false;
	}
	var judgeval=judge($("#register-phone"),$("#register-pword"),$('#register-remind'));
	if(!judgeval){
		return;
	}
	url = ApiUrl+'/member/regNext';
	var mobile = $('#register-phone').val();
	var seccode = $('#register-pword').val();
	var verifyCode = $('#register-verification').val();
	$.post(url,{mobile:mobile,seccode:seccode,verifyCode:verifyCode},function(result) {
		result = eval("("+result+")");
		if(result.code) {
			shopdz_alert(result.msg);
		} else {
			$('.content2').slideUp("fast");
			$('.content3').slideDown("slow");
		}
	});	
});


//注册提交
$('#register-btn2').click(function(){
	var first = $('#pword-first').val();
	var second = $('#pword-second').val();
	var mobile = $('#register-phone').val();
	var seccode = $('#register-pword').val();
	var fromid = getcookie('fromid');
	if(first==""){
		shopdz_alert("设置密码不能为空");
		return false;
	}else if(!regexPas.test(first)){
		shopdz_alert("密码必须是6-18位的数字和字母组合");
		 return false;
	}
	if(second==""){
		shopdz_alert("确认密码不能为空");
		return false;
	}else if(first != second){
		shopdz_alert("两次密码设置不一样");
		 return false;
	}
	url = ApiUrl+'/member/register';
	$.post(url,{mobile:mobile,passwd:first,repasswd:second,seccode:seccode,fromid:fromid},function(result) {
		result = eval("("+result+")");
		if(result.code) {
			shopdz_alert(result.msg);
		} else {
			shopdz_alert('注册成功',2,function(){
				$("#register-btn2").unbind("click");
				window.location.href = WapSiteUrl;
			});
		}
	
	});	
});

//发送短信校验码
function sendRegcode() {
	var mobile = $('#register-phone').val();
	var verifyCode = $('#register-verification').val();
	url = ApiUrl+'/member/sendRegSeccode';
	if (!$('#send_seccode_btn').hasClass('send-out-bg')){
		return false;
	}
	$('#send_seccode_btn').removeClass('send-out-bg');
	$.post(url,{mobile:mobile,verifyCode:verifyCode},function(result) {

		result = eval("("+result+")");
		if(result.code) {
			ref_verifi();
			$('#register-btn1').removeClass('send-out-bg');
			$('#send_seccode_btn').removeClass('send-out-bg');
			$('#register-pword').val('');
			shopdz_alert(result.msg);
		} else {
			times('sendRegcode');
			$('#send_seccode_btn').removeClass('send-out-bg');
			$('#send_seccode_btn').unbind("click");
			return '';
		}
	});	
}


function pwmd5(_id) {
	var re = /^[\w]{32}$/i;
	orgval = $('#'+_id).val();
	if(re.test(orgval)) return;
	md5val= $.md5(orgval);
	$('#'+_id).val(md5val);
}
