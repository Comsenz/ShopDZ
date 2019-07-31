var setmembermessageurl = '/member/editMember';
var getmembermessageurl = '/member/getMemberInfo';
var wxoutbingurl = '/member/wxOutbing';
var submit = true;
var is_ext = get('openid');
if(is_ext == 1){
	shopdz_alert('微信账户已存在，请退出后使用微信登录！',3);
}
$(function(){
	if(!key){
			window.location.href = WapSiteUrl+'/login.html';
	} else {
		/* 上传头像 */
		uploadimages($('#uploadphoto'), ApiUrl + '/Presales/refundimg', 'avatar', headcallback);
		getmemberdata();
		$(document).on('blur','input[name="true_name"]',function(){
			judgeuser($(this));
		});
		$('#submit').click(function(){
			var result = judgeuser($('input[name="true_name"]'));
			if(submit && result){
				submit = false;
				setmemberdata();
			}
		});
		
	}

	 /* 取消删除收货地址 */
    $('.cancelbtn1').bind('click',function(){
    	$('.cover').css('display','none');
    	$('.dele-sure').css('display','none');
    });
	
});
//微信解绑

function wxoutbing(type){
	if(type == 'wxoutbind'){
		$('.cover').css('display','block');
		$('.dele-sure').css('display','block');
		that=$(this);
		$('.surebtn1').click(function(){
			$('.cover').css('display','none');
			$('.dele-sure').css('display','none');
			url = ApiUrl + wxoutbingurl;
			$.post(url,{key:key},function(result) {
				result = eval("("+result+")");
				if(result.code == 1){
					shopdz_alert(result.msg,2,function(){
						location.reload();
					});
				}else if(result.code == 403){
					window.location.href= WapSiteUrl+'/wxoutbin.html';
				}
			});

		});
	}else{
		window.location.href= WapSiteUrl+'/wxoutbin.html';
	}

}


/* 上传头像的回调 */
function headcallback(info, label){
	if($('.cover').length){
		$(".cover,.inner").hide();
	}
	if(!info.code){
		window.location.href = WapSiteUrl + "/head_second.html?imgurl="+info['data']['url']+"&smallurl="+info['data']['smallurl']+"&width="+info['data']['width']+"&height="+info['data']['height'];
	} else {
		/* 上传失败 */
		shopdz_alert('请上传小于5M的图片！');
	}
}

/* 获取数据 */
function getmemberdata(callback){
	getmembercallback = callback || getmembercallback;
	data = {
		key:key
	}
	url = ApiUrl + getmembermessageurl;
	getdata(url, data, getmembercallback);
}

/* 设置用户信息 */
function setmemberdata(callback){
	setmembercallback = callback || setmembercallback;
	data = {
		key:key,
		member_username:$('input[name="member_mobile"]').val(),
		member_avatar:$('#uploadphoto').siblings('img').attr('src').split('Attach')[1],
		member_truename:$('input[name="true_name"]').val(),
		member_sex:$('input[name="radio-1-set"]:checked').val(),
		member_birthday:$('#birthday').html()
	}
	url = ApiUrl + setmembermessageurl;
	getdata(url, data, setmembercallback);
}

function getdata (ApiUrl,data,callback) {
	$.ajax({
		type: 'POST',
		url: ApiUrl,
		data: data,
		success: callback,
		dataType: 'json'
	});
}

function getmembercallback(info){
	if(info.code) {
		shopdz_alert('您还没有登录',1,function(){
			window.location.href = WapSiteUrl + '/login.html';
		});
	} else {
		var html = template('member_info_template',info.data);
		$('#member_info').html(html);
		$('.member_wx').css('display','none');
		if(isWeiXin()){
			$('.member_wx').css('display','block');
		}
		var reg = /head_second.html/;
		if(reg.test(document.referrer) && get('upload') == 1){
			shopdz_alert('保存头像成功！');
		}
		$('#uploadphoto').siblings('img').attr('src', info['data']['member_avatar']);
		/* 日期插件 
		mdatetimer({
			mode : 1, //时间选择器模式：1：年月日，2：年月日时分（24小时），3：年月日时分（12小时），4：年月日时分秒。默认：1
			format : 2, //时间格式化方式：1：2015年06月10日 17时30分46秒，2：2015-05-10  17:30:46。默认：2
			startyear : 1950, //开始年份
			endyear : 2016,//结束年份
			nowbtn : false, //是否显示现在按钮
			onOk : function(){
				$('#birthday').html($('#birthday').attr('value'));
			},  //点击确定时添加额外的执行函数 默认null
			onCancel : function(){
				
			}, //点击取消时添加额外的执行函数 默认null
		},$('#birthday'));	*/
		initPage();
	}
}

function setmembercallback(info){
	if(info['msg'] == 'success')info['msg'] = '修改成功！';
	shopdz_alert(info['msg'],2,function(){
		window.location.href = WapSiteUrl + '/member.html';
	})
}

