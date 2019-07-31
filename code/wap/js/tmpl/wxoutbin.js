//输入框获取焦点事件
$(function(){
	$('.content3').show();
	$('#register-remind2').show();
	$.post(ApiUrl+'/base/getSetting',{type:'shop_login'},function(result) {
		result = eval("("+result+")");
		$('.sign-img1').attr('src',result.data);
	});

});

//注册提交
$('#register-btn2').click(function(){
	var error = $('#register-remind2');
	var first = $('#pword-first').val();
	var second = $('#pword-second').val();

	if(first==""){
		error.slideDown();
		error.text("设置密码不能为空");
		return false;
	}else if(!regexPas.test(first)){
		error.slideDown();
		error.text("密码必须是6-18位的数字和字母组合");
		return false;
	}
	if(second==""){
		error.slideDown();
		error.text("确认密码不能为空");
		return false;
	}else if(first != second){
		error.slideDown();
		error.text("两次密码设置不一样");
		return false;
	}
	url = ApiUrl+'/member/wxOutbing';
	$.post(url,{changpwd:1,passwd:first,repasswd:second},function(result) {
		result = eval("("+result+")");
		if(result.code) {
			$('#register-remind2').slideDown();
			$('#register-remind2').text(result.msg);
		} else {
			window.location.href=WapSiteUrl+'/changemember.html';
		}

	});
});





