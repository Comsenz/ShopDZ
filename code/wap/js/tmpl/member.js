if(!key){
	window.location.href = WapSiteUrl + '/login.html';

} else { 
	var url = ApiUrl+'/member/getMemberInfo';
	var signurl = ApiUrl+'/member/sign_in';

	$.post(url,{key:key},function(result) {
		result = eval("("+result+")");
		if(result.code) {
			shopdz_alert('您还没有登录',1,function(){
				delCookie('key');
				delCookie('uid');
				window.location.href=WapSiteUrl+'/login.html';
			});
		} else {
			var html = template('member_index_template',result.data);
			var sign_template = template('sign_template',result.data);
			$('#member_info').html(html);
			$('#sign').html(sign_template);
			
			SizeLimit($('.head-img'));
		}
	});
}
$(function(){
	var phone = getwebConf('enterprise_contact');
	if(typeof(phone) != 'undefined'){
		if(phone){
			if(isPhone() == 0){
				$('#callphone').attr('onclick','alertphone()');
				$('.phonetext').html(phone);
			}else{
				$('#callphone').attr('href','tel:'+phone);
			}
		}else{
			$('#callphone').attr('onclick','alertphone()');
			$('.phonetext').html('暂无联系电话');
		}
	}
	$('.sure-phone').bind('click',function(){
		$('.cover').css('display','none');
		$('.show-phone').css('display','none');
	})
	
	$(document).on('click','.sign',function() {
		_this = this;
		getdata(signurl,{key:key},function(result) {
			if(result.code) {
				shopdz_alert(result.msg,1);
			}else{
				$('.sign-alert').show();
				$('.cover').show();
				$('.add-integral-num').html('+'+result.data.points);
				$(_this).unbind('click').find('.text').html('已签到');
			}
		});
	});
	$('#logout').unbind().bind('click',function(){

		$('.cover').css('display','block');
    	$('.dele-sure').css('display','block');
    	that=$(this);
    	/* 确定删除收货地址 */
    	$('.surebtn1').click(function(){
    		$('.cover').css('display','none');
    		$('.dele-sure').css('display','none');
    		url = ApiUrl+'/member/logout';
			$.post(url,{client:'wap',key:key},function(result) {
				result = eval("("+result+")");
				delCookie('key');
				delCookie('uid');
					//shopdz_alert(result.msg,1,function() {
						window.location.href = WapSiteUrl+'?t='+Math.random();
					//});
			});	
    		
    	});
	});

	 /* 取消删除收货地址 */
    $('.cancelbtn1').bind('click',function(){
    	$('.cover').css('display','none');
    	$('.dele-sure').css('display','none');
    });
});
//function headImg(){
//	var headImgW=$(".head-img").width();
//	$(".head-img").height(headImgW);
//}
function isWeiXin(){
	var ua = navigator.userAgent.toLowerCase();//获取判断用的对象
	if (ua.match(/MicroMessenger/i) == "micromessenger") {
		return true;
	}else{
		return false;
	}

}

function alertphone(){
	$('.cover').css('display','block');
	$('.show-phone').css('display','block');
}