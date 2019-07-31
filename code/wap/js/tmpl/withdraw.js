var getbank = '/Spread/getbank';
var setbank = '/Spread/setbank';
var getaccount = '/Spread/account';
var setwithdraw = '/Spread/withdraw';
var WXsetwithdraw = '/Spread/WxUserGetRefund';
var tit = $('title').text(); /* title文案 */
var phone = ''; /* 用户手机号 */
var submit = true; /* 提交开关 */
var page_on_off = true; /* 分页开关 */
var bank = new Object(); /* 保存银行卡信息的数组 */
var bank_mode = '';/* 提现方式：1-银行卡  2-微信 */
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
	/* 点击选择提现方式 */
	$(document).on('click','#select_withdraw_mothed',function(){
		$('#withdraw_home').hide();
		$('#withdraw_mothed').show();
		$('title').text('选择提现方式');
		$('#head_tit').html($('title').text());
	});
	
	/* 选择微信提现 */
	$('#wx').click(function(){
		bank_mode = 2; /* 提现方式设置为银行卡 */
		$('#select_withdraw_mothed span:eq(0)').html('微信钱包');
		$('#select_withdraw_mothed span:eq(1)').html('');
		/* 页面展示 */
		$('#withdraw_mothed').hide();
		$('#withdraw_home').show();
		$('title').text('提现');
		$('#head_tit').html($('title').text());
	});
	/* 选择银行卡提现 */
	$(document).on('click','#select_bank',function(){
		if(bank && bank.bank_no){
			/* 已经有银行卡 */
			bank_mode = 1; /* 提现方式设置为银行卡 */
			$('#select_withdraw_mothed span:eq(0)').html(bank['name']);
			$('#select_withdraw_mothed span:eq(1)').html(bank['bank_no']);
			hide_middle_str($('.hide_str'));
		}else{
			/* 没有银行卡 */
			$('#card_number_btn').click();/* 点击编辑提现帐户 */
		}
		/* 页面展示 */
		$('#withdraw_mothed').hide();
		$('#withdraw_home').show();
		$('title').text('提现');
		$('#head_tit').html($('title').text());
	});
	/* 点击编辑提现帐户 */
	$(document).on('click','#card_number_btn',function(){
		var mobile = $('.phone').text();
		if(!mobile){
			shopdz_alert('请绑定手机号',1,function(){
				window.location.href = WapSiteUrl+'/wxlogin.html';
			});
		}
		var str = '添加银行帐户';
		$('#click_verify').click(); /* 刷验证码 */
		$('#register-verification').val(''); /* 清空验证码 */
		$('#register-pword').val(''); /* 清空手机验证码 */
		$('#name').val('');
		$('#bank_name').val('');
		$('#bank_no').val('');
		if(bank && bank.bank_no){
			str = '修改银行帐户';
			$('#name').val(bank['name']); /* 银行名称 */
			$('#bank_name').val(bank['bank_name']); /* 开户人 */
			$('#bank_no').val(bank['bank_no']); /* 银行卡号 */
		}
		/* 页面展示 */
		$('#withdraw_mothed').hide();
		$('#withdraw_account').show();
		$('title').text(str);
		$('#head_tit').html($('title').text());
		return false;
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
	$(document).on('click','#withdraw_account_submit',function(){
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
	
	/* 提交提现申请 */
	$(document).on('click','#withdraw_home_submit',function(){
		var frozen_price = $('#frozen_price').val();
		var all_price = $('.price').html();

		if(frozen_price <= 0){
			shopdz_alert('请输入正确的提现金额！');
			return false;
		}
		if(all_price < frozen_price) {
			shopdz_alert('提现金额不能大于可提现金额！');
			return false;
		}
		var url = '';
		var data = new Array();
		if(bank_mode == 2){
			/* 微信提现 */
			url = ApiUrl + WXsetwithdraw;
			window.location.href = url + "?key=" + key + "&cash_amount=" + frozen_price;
		}else if(bank_mode == 1){
			/* 银行卡提现 */
			url = ApiUrl + setwithdraw;
			data = {
				key: key,
				name: bank['name'],
				bank_name: bank['bank_name'],
				cash_amount: frozen_price,
				bank_no: bank['bank_no']
			};
			getdata(url,data,function(info){
				var type = '';
				var msg = '';
				if(info['code']){
					type = 'error';
					msg = info['msg'];
				}else{
					type = 'success';
					msg = '申请成功，请等待管理员审核';
				}
				window.location.href = WapSiteUrl+'/refundsuccess.html?content='+msg+'&t='+type;
			});
		} else {
			shopdz_alert('请选择提现方式')
		}
	})
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
		initPage();
	}else{
		/* 判断是不是新绑定的微信用户跳转过来的 */
		if(get('wx') == 1){
			info['data']['bankinfo']['type'] = 2;
			info['data']['bankinfo']['name'] = '微信钱包';
		}
		/* 判断是不是微信客户端登陆 */
		if(!isWeiXin() || info['data']['banksetting']['wx_withdraw_status'] != 1){
			/* 不是微信端登陆或没开启微信提现 */
			if(info['data']['bankinfo']['type']==2){
				info['data']['bankinfo']['type'] = 0;
			}
			$('#wx').parents('.totle-box').hide();
		}
		/* 保存银行卡信息 */
		bank = info['data']['bank'];
		/* 保存提现方式 */
		bank_mode = info['data']['bankinfo']['type'];
		/* 渲染模板 */
		var html = template('withdraw_template',info.data);
		$('#withdraw_content').html(html);
		
		/* 获取个人帐号信息 */
		getdata(ApiUrl + getaccount,{key:key},function(info){
			if(info['code']){
				$('.price').html('0.00');
			}else{
				$('.price').html(info['data']['settlement_price']);
			}
		});

		/* 判断有没有银行卡 */
		if(bank && bank.bank_no){
			$('#card_number_btn span:eq(0)').html('点击修改');
		}else{
			$('#card_number_btn span:eq(0)').html('点击添加');
		}
		/* 提现金额限制 */
		$('#frozen_price').attr('placeholder','最少'+info.data.banksetting.minprice+'元，最多'+info.data.banksetting.maxprice+'元');
		/* 处理隐藏中间字符串 */
		hide_middle_str($('.hide_str'));
		/* 头部返回按纽事件 */
		$('.goback-header').find('.back-icon').unbind('click').click(function() {
			if($('#withdraw_home').css('display') != 'none'){
				goBack();
			} else if ($('#withdraw_mothed').css('display') != 'none'){
				$('#withdraw_mothed').hide();
				$('#withdraw_home').show();
				$('title').text('提现');
				$('#head_tit').html($('title').text());
			} else if ($('#withdraw_account').css('display') != 'none'){
				$('#withdraw_account').hide();
				$('#withdraw_mothed').show();
				$('title').text('选择提现方式');
				$('#head_tit').html($('title').text());
			}
		});
		initPage();
	}
}
/* 设置提现帐户回调 */
function setbankcallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
	}else{
		bank_mode = 1;
		/* 保存银行卡信息 */
		bank = info['data']['bank'];
		/* 显示页面 */
		$('#withdraw_home').show();
		$('#withdraw_mothed').hide();
		$('#withdraw_account').hide();
		$('title').text(tit);
		$('#head_tit').html($('title').text());
		/* 显示最新的银行帐号 */
		$('#select_withdraw_mothed span:eq(0)').html(bank['name']);
		$('#select_withdraw_mothed span:eq(1)').html(bank['bank_no']);
		/* 处理隐藏中间字符串 */
		hide_middle_str($('.hide_str'));
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
