var getbank = '/Spread/getbank';
var setbank = '/Spread/setbank';
var getaccount = '/Spread/account';
var setwithdraw = '/Spread/withdraw';
var tit = $('title').text();
var phone = ''; /* 用户手机号 */
var submit = true;
var page_on_off = true;
$(function(){
	if(!key){
			window.location.href = WapSiteUrl+'/login.html';
	}
	verify_status = getwebConf('verify_status');
	if(verify_status =='0'){
		$('#register-verification').parents('li').hide();
		$('#send_seccode_btn').addClass('send-out-bg');
		$("#send_seccode_btn").bind('click',function(){
			sendRegcode();
		});
	}

	$('.input-box').bind({ 
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
		$(this).prev('.input-box').val("");
		$(this).css('display','none');
		
	});
	$('#bank_no').blur(function(){
		var val = $(this).val();
		var reg = /^\d{16,19}$/;
		if(!reg.test(val)){
			shopdz_alert('请输入正确的银行卡号！');
		}
	});
	$('#card_name').blur(function(){
		judgeuser($(this),'姓名不能超过7位！');
	});
	/* 验证码 */
	$('.verification').attr('src',ApiUrl+'/base/showVerifyCode');
	$('.verification').attr('rel-src',ApiUrl+'/base/showVerifyCode');
	$('.verification').click(function(){ref_verifi();});
	$('#register-verification').keyup(function() {
		if($(this).val().length == 4){
			$('#send_seccode_btn').addClass('send-out-bg');
			$("#send_seccode_btn").unbind('click').bind('click',function(){
				sendRegcode();
			});
		}else{
			$('#send_seccode_btn').removeClass('send-out-bg');
			$("#send_seccode_btn").unbind("click");
		}
	});
	/* 获取数据 */
	getdata(ApiUrl + getbank,{key:key},getbankcallback);
	
	/* 提交提现帐户 */
	$('#withdraw_account_submit').click(function(){
		var name = $('#name').val();
		var bank_name = $('#bank_name').val();
		var bank_no = $('#bank_no').val();
		var verify = $('#register-verification').val();
		var seccode = $('#register-pword').val();
		if(submit_verify(name, bank_name, bank_no, seccode)){
			var url = ApiUrl + setbank;
			var data = {
				key: key,
				name: name,
				bank_name: bank_name,
				bank_no: bank_no,
				verify: verify,
				seccode: seccode
			};
			getdata(url,data,setbankcallback);
		}
	});
	
});
function submit_verify(name, bank_name, bank_no, seccode){
	var result = true;
	var reg = /^\d{16,19}$/;
	if(name == ''){
		result = false;
		shopdz_alert('请填写银行名称！');
	}else if(bank_name == ''){
		result = false;
		shopdz_alert('请填写姓名！');
	}else if(!reg.test(bank_no)){
		result = false;
		shopdz_alert('请输入正确的银行卡号！');
	}else if(seccode == ''){
		result = false;
		shopdz_alert('请输入手机验证码！');
	}
	return result;
}
/* 获取提现数据回调 */
function getbankcallback(info){
	phone = info['data']['member']['member_mobile'];
	$('.phone').html(phone);
	if(!info['data']['bankinfo']){
		$('title').text('添加提现帐户');
		$('#head_tit').html($('title').text());
	}else{
		$('#name').val(info['data']['bank']['name']);
		$('#bank_name').val(info['data']['bank']['bank_name']);
		$('#bank_no').val(info['data']['bank']['bank_no']);
		$('title').text('修改提现帐户');
		$('#head_tit').html($('title').text());
	}
	initPage();
}
/* 设置提现帐户回调 */
function setbankcallback(info){
	console.log(info);
	if(info['code']){
		shopdz_alert(info['msg']);
	}else{
		shopdz_alert('保存成功！',2,function(){goBack();});
	}
}
//发送短信校验码
function sendRegcode() {
	var mobile = phone;
	var verifyCode = $('#register-verification').val();
	url = ApiUrl+'/Spread/sendSeccode';
	if (!$('#send_seccode_btn').hasClass('send-out-bg')){
		return false;
	}
	$('#send_seccode_btn').removeClass('send-out-bg');
	$.post(url,{key:key,mobile:mobile,verifyCode:verifyCode},function(result) {
		result = eval("("+result+")");
		if(result.code) {
			ref_verifi();
			$('#send_seccode_btn').removeClass('send-out-bg');
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
