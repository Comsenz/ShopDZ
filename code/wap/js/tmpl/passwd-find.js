//获取图形验证码
$('.verification').attr('src',ApiUrl+'/base/showVerifyCode');
$('.verification').attr('rel-src',ApiUrl+'/base/showVerifyCode');
$('.verification').click(function(){
	ref_verifi();
});

$.post(ApiUrl+'/base/getSetting',{type:'shop_login'},function(result) {
	result = eval("("+result+")");
	$('.sign-img1').attr('src',result.data);
});	
verify_status = getwebConf('verify_status');
if(verify_status =='0'){
	$('#register-verification').parent().hide();
}
//注册输入手机号校验
$("#member_mobile,#register-verification,#mobile_seccode").keyup(function(){
	var inpLen=$("#member_mobile").val().length;
	var regexpw=/^1[3|4|5|7|8]\d{9}$/;
	var mobile=$('#member_mobile').val();
	var verificatioLen=$("#register-verification").val().length;
	verify_status =='0' ? verificatioLen = 4 : verificatioLen;
	var inpLenP=$("#mobile_seccode").val().length;
	var inpPw=$("#mobile_seccode").val();
	var regexP2=/^\d{6}$/;
	if(inpLen==11 && regexpw.test(mobile) && verificatioLen==4){
		if($("#send_seccode_btn").find('span').length =='0' ) {
			$("#send_seccode_btn").addClass("send-out-bg");
		}
		$("#send_seccode_btn").unbind('click').bind('click',function(){
			send_seccode();
		});
		
	} else {
		$("#send_seccode_btn").removeClass("send-out-bg");
		$("#send_seccode_btn").unbind("click");
	}
	if(inpLen==11 && regexpw.test(mobile) && regexP2.test(inpPw)) {
		$("#edit_passwd_next").addClass("send-out-bg");
	} else {
		$("#edit_passwd_next").removeClass("send-out-bg");
	}
	
})

//发送短信验证码
function send_seccode(){
	var mobile = $('#member_mobile').val();
	var verifyCode = $('#register-verification').val();
	if (!$('#send_seccode_btn').hasClass('send-out-bg')){
		return false;
	}
	$('#send_seccode_btn').removeClass('send-out-bg');
	$.ajax({
	    url:ApiUrl+'/member/sendFindPasswdSeccode',
	    type:"POST",
	    async:false,
	    data:{mobile:mobile,verifyCode:verifyCode},
	    success: function(result) {
	       	result = eval("("+result+")");
			if(result.code){
				ref_verifi();
				$('#send_seccode_btn').removeClass('send-out-bg');
				$('#edit_passwd_next').removeClass('send-out-bg');
				$('#mobile_seccode').val('');
				shopdz_alert(result.msg);
			} else {
				times('send_seccode');
				$('#send_seccode_btn').removeClass('send-out-bg');
				$('#send_seccode_btn').unbind("click");
			}
	    }
    });
}



//验证校验码 进行下一步
$('#edit_passwd_next').click(function(){
	var mobile = $('#member_mobile').val();
	var seccode = $('#mobile_seccode').val();
	var verifyCode = $('#register-verification').val();
	if(!mobile){shopdz_alert('请输入手机号');return false;}
	if(!seccode){shopdz_alert('请输入短信验证码');return false;}
	var verRes = false;
	$.ajax({
        url:ApiUrl+'/member/FindPasswdNext',
        type:"POST",
        async:false,
        data:{mobile:mobile,seccode:seccode},
        success: function(result) {
           	result = eval("("+result+")");
			if(result.code){
				verRes = true;
				shopdz_alert(result.msg);
			}
        }
    });
    
	if(verRes) {return false;}

	$('.content1').slideUp("fast");
	$('.content2').slideDown("slow");
	$('#edit_passwd_next').unbind("click");

});
//修改密码提交
$("#passwd-edit-btn").click(function(){
	var error = $('#register-remind2');
	var mobile = $('#member_mobile').val();
	var newpasswd = $('#pword-new').val();
	var repasswd = $('#pword-renew').val();
	var seccode = $('#mobile_seccode').val();

	if(newpasswd==""){
		error.slideDown();
		error.text("设置密码不能为空");
		return false;
	}else if(!regexPas.test(newpasswd)){
		 error.slideDown();
		 error.text("密码必须是6-18位的数字和字母组合");
		 return false;
	}
	if(repasswd==""){
		error.slideDown();
		error.text("确认密码不能为空");
		return false;
	}else if(newpasswd != repasswd){
		 error.slideDown();
		 error.text("两次密码设置不一样");
		 return false;
	}

	url = ApiUrl+'/member/passwdEdit';
	$.post(url,{mobile:mobile,passwd:newpasswd,repasswd:repasswd,seccode:seccode},function(result) {
		result = eval("("+result+")");
		if(result.code){
			shopdz_alert('失败');
		} else {
			shopdz_alert('密码重置成功',2,function(){
				window.location.href=WapSiteUrl+'/login.html';	
			});
		}
	});
});
$(function(){
	var shop_name = getwebConf('shop_name');
	if(shop_name !== false){
		$('title').html('找回密码-'+shop_name);
	}
	initPage();
})