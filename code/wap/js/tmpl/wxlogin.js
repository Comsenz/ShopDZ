if(!key){
	window.location.href = WapSiteUrl + '/login.html';
}else{
	$(function(){
		verify_status = getwebConf('verify_status');
		shop_login = getwebConf('shop_login');
		$('.sign-img1').attr('src',shop_login);
		if(verify_status =='0'){
			$('#click_verify').parent().hide();
		}
		$('.content2').show();
		//获取图形验证码
		$('.verification').attr('src',ApiUrl+'/base/showVerifyCode');
		$('.verification').attr('rel-src',ApiUrl+'/base/showVerifyCode');
		$('.verification').click(function(){ref_verifi();});
		$('.sign-inp').bind({
			focus:function(){
				$('.remind1').css("display",'none');
				if (this.value == this.defaultValue){
					this.value="";
					$(this).next('.dele').css('display','block');
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
		});

		var url_path_arr = ['passwd-find'];
		var url_path = url_path_arr.join("|");
		var pattern = eval('/('+url_path+')/ig');
		var referurl = document.referrer;//上级网址

	});
}


//注册输入手机号校验
$("#register-phone,#register-verification,#register-pword").keyup(function(){
	var inpLen=$("#register-phone").val().length;
	var regexpw=/^1[3|4|5|7|8]\d{9}$/;
	var mobile=$('#register-phone').val();
	// var val = $("#register-verification").val().replace(/[^A-za-z0-9]+/,'');
	// $("#register-verification").val(val);
	var verificatioLen=$("#register-verification").val().length;
	verify_status =='0' ? verificatioLen = 4 : verificatioLen;
	var inpLenP=$("#register-pword").val().length;
	var inpPw=$("#register-pword").val();
	var regexP2=/^\d{6}$/;

	if(inpLen==11 && regexpw.test(mobile) && verificatioLen==4){

		if($("#send_seccode_btn").find('span').length =='0' ) {
			$("#send_seccode_btn").addClass("send-out-bg");
		}

		$("#send_seccode_btn").unbind('click').bind('click',function(){
			sendRegcode();
		});

		if(regexP2.test(inpPw)) {
			$("#register-btn1").addClass("send-out-bg");
		}else{
			$("#register-btn1").removeClass("send-out-bg");
		}
	}else{
		$("#send_seccode_btn,#register-btn1").removeClass("send-out-bg");
	}
})

//注册第一步验证验短信验证码
$('#register-btn1').click(function(){
	var judgeval=judge($("#register-phone"),$("#register-pword"));
	if(!judgeval){
		return;
	}
	url = ApiUrl+'/member/wxregNext';
	var mobile = $('#register-phone').val();
	var seccode = $('#register-pword').val();
	$.post(url,{mobile:mobile,seccode:seccode,key:key},function(result) {
		result = eval("("+result+")");
		if(result.code) {
			ref_verifi();
			$('#register-btn1').removeClass('send-out-bg');
			$('#send_seccode_btn').removeClass('send-out-bg');
			$('#register-pword').val('');
			shopdz_alert(result.data);
		} else {
			if(document.referrer.length > 0){
				var redirect = document.referrer;//上级网址
			}else{
				var redirect = WapSiteUrl+'/member.html';
			}
			window.location.href = redirect;

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
