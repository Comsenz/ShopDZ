//获取图形验证码
$('.verification').attr('src',ApiUrl+'/base/showVerifyCode');
$('.verification').attr('rel-src',ApiUrl+'/base/showVerifyCode');
$('.verification').click(function(){ref_verifi();});

$("#member_mobile").val(get('mobile'));
verify_status = getwebConf('verify_status');
if(verify_status =='0'){
	$('#register-verification').parent().hide();
	if($("#send_seccode_btn").find('span').length =='0' ) {
		$("#send_seccode_btn").addClass("send-out-bg");
	}
	$("#send_seccode_btn").unbind().bind('click',function(){
		send_seccode();
	});
}
$("#register-verification,#mobile_seccode").keyup(function(){
	var verificatioLen=$("#register-verification").val().length;
	verify_status =='0' ? verificatioLen = 4 : verificatioLen;
	var inpPw=$("#mobile_seccode").val();
	var regexP2=/^\d{6}$/;
	if(verificatioLen==4){
		//alert(verificatioLen);
		if($("#send_seccode_btn").find('span').length =='0' ) {
			$("#send_seccode_btn").addClass("send-out-bg");
		}
		$("#send_seccode_btn").unbind().bind('click',function(){
			send_seccode();
		});
		
	} else {
		$("#send_seccode_btn").removeClass("send-out-bg");
		$("#send_seccode_btn").unbind("click");
	}

	if(regexP2.test(inpPw)){
		$("#edit_passwd_next").addClass("send-out-bg");
	}else{
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
	    url:ApiUrl+'/member/sendEditPasswdSeccode',
	    type:"POST",
	    async:false,
	    data:{mobile:mobile,verifyCode:verifyCode},
	    success: function(result) {
	       	result = eval("("+result+")");
			if(result.code){
				ref_verifi();
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
	if(!seccode){shopdz_alert('请输入短信验证码');return false;}

	var verRes = false;
	$.ajax({
        url:ApiUrl+'/member/EditPasswdNext',
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
	$.post(url,{key:key,mobile:mobile,passwd:newpasswd,repasswd:repasswd,seccode:seccode},function(result) {
		result = eval("("+result+")");
		if(result.code){
			shopdz_alert(result.msg);
		} else {
			shopdz_alert('密码重置成功',2,function(){
				window.location.href=WapSiteUrl+'/member.html';	
			});
		}
	});
});

$(function(){
	initPage()
})