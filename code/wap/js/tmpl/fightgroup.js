var getfightgroupurl = '/Group/grouplist';
var getgroupgoodsdetailurl = '/Group/detail';
var getgroupdetailurl = '/Group/groupjoin';
var getmygroupurl = '/Group/mygroup';
var getgrouporderurl = '/Group/grouppayinfo';
var page_on_off = true;


/* 拼团列表开始 */
function getfightgroup(callback){
	callback = callback || getfightgroupcallback;
	var url = ApiUrl + getfightgroupurl;
	var data = {
		key:key
	};
	getdata(url,data,callback);
}
function getfightgroupcallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
	}else{
		var url = ApiUrl + getfightgroupurl;
		var html = template('fightgroupcontent',info);
		$('#fightgrouplist').html(html);
		scrollgetdata(url, {key:key,page:1}, scrollgetfightgroupcallback);
		
		if(info.data.length < 1 ){
			$('.default-page').show();
		}
	}
}
function scrollgetfightgroupcallback(info){
	if(info['data'].length < 1){
		shopdz_alert('已经到底了哟~');
		$(window).scroll(function(){});
		return ;
	}else{
		var html = template('fightgroupcontent', info);
		$('#fightgrouplist').append(html);
		page_on_off = true;
		initPage();
	}
}
/* 拼团列表结束 */
/* 我的拼团开始 */
function getmygroup(callback){
	callback = callback || getmygroupcallback;
	var url = ApiUrl + getmygroupurl;
	var data = {
		key:key
	};
	getdata(url,data,callback);
}
function getmygroupcallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
	}else{
		var url = ApiUrl + getfightgroupurl;
		var html = template('mygroupcontent',info);
		$('#mygroup').html(html);
		scrollgetdata(url, {key:key,page:1}, scrollgetmygroupcallback);
		if(info.data.length < 1 ){
			$('.default-page').show();
		}
	}
}
function scrollgetmygroupcallback(info){
	if(info['data'].length < 1){
		shopdz_alert('已经到底了哟~');
		$(window).scroll(function(){});
		return ;
	}else{
		var html = template('mygroupcontent', info);
		$('#mygroup').append(html);
		page_on_off = true;
		initPage();
	}
}
/* 我的拼团结束 */
/* 商品详情开始 */
function getgroupgoodsdetail(callback){
	callback = callback || getgroupgoodsdetailcallback;
	var url = ApiUrl + getgroupgoodsdetailurl;
	var data = {
		key:key,
		active_id:get('active_id')
	}
	getdata(url,data,callback);
}
function getgroupgoodsdetailcallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
	}else{
		var group_id = get('group_id');
		if(group_id){
			info.data.group_id = group_id;
		}
		var html = template('groupgoodsdetailcontent',info);
		$('#groupgoodsdetail').html(html);
		var swiper = new Swiper('.swiper-container', {
	        pagination: '.swiper-pagination',
	        nextButton: '.swiper-button-next',
	        prevButton: '.swiper-button-prev',
	        paginationClickable: true,
	        spaceBetween: 30,
	        centeredSlides: true,
	        autoplay: 2500,
	        autoplayDisableOnInteraction: false
	    });
	    $('.fight-group-money').click(function(){
	    	if(key== ''){
	    		shopdz_alert('请先登陆！',2,function(){redirectindex();});
	    		return false;
	    	}
	    	if(info['data']['group']['status']['status'] != 0){
		    	shopdz_alert(info['data']['group']['status']['tips']);
		    	return false;
		    }
	    });
	}
}
/* 商品详情结束 */
/* 拼团详情开始 */
function getgroupdetail(callback){
	callback = callback || getgroupdetailcallback;
	var url = ApiUrl + getgroupdetailurl;
	var data = {
		key:key,
		active_id:get('active_id'),
		group_id:get('group_id')
	}
	getdata(url,data,callback);
}
function getgroupdetailcallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
	}else{
		info['apiurl'] = ApiUrl;
		var html = template('groupdetailcontent',info);
		$('#groupdetail').html(html);
		if(info['data']['groupinfo']['status'] == 0){
			/* 倒计时 info['data']['group']['status']['status'] == 0 &&  */
			var Rtime = setInterval(function(){
			getRTime(info['data']['groupinfo']['group_end_time'],Rtime);},1000);
		}
		
		/* 微信分享开始 */
		var conf = getwebConf();
		var url = location.href.split('#')[0];
		$.get(ApiUrl + '/Member/GetSignPackage', {"url": encodeURIComponent(url)}, function (data) {
            if (data.code == 0) {
                wx.config({
                    debug: false,
                    appId: data.data.appId,
                    timestamp: data.data.timestamp,
                    nonceStr: data.data.nonceStr,
                    signature: data.data.signature,
                    jsApiList: ['onMenuShareAppMessage','onMenuShareTimeline'
                        // 所有要调用的 API 都要加到这个列表中
                    ]
                });

            }
        }, 'json');
		wx.ready(function () {
            var imgurl = info['data']['group']['group_image'] || conf.shop_logo;
            var title = info['data']['group']['group_name']+' '+info['data']['group']['goods_name'] || conf.shop_name;
            var desc = info['data']['group']['group_content'] || conf.web_introduce;
            var linkurl = WapSiteUrl+'/mineGroupDet.html?active_id='+info['data']['group']['id']+'&group_id='+info['data']['groupinfo']['id'];
            wx.onMenuShareAppMessage({
                title: title,
                desc: desc,
                link: linkurl,
                imgUrl: imgurl,
                type: '',
                dataUrl: '',
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            wx.onMenuShareTimeline({
                title:title + '-' + desc,
                link: linkurl,
                imgUrl: imgurl,
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
        });
		/* 微信分享结束 */
	}
}
/* 拼团详情结束 */
/* 拼团订单详情开始 */
function getgrouporder(callback){
	callback = callback || getgroupordercallback;
	var url = ApiUrl + getgrouporderurl;
	var data = {
		key:key,
		order_sn:get('order_sn')
	}
	getdata(url,data,callback);
}
function getgroupordercallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
	}else{
		info['apiurl'] = ApiUrl;
		var html = template('orderdetailscontent',info);
		$('#orderdetails').html(html);
		
	}
}
/* 拼团支付结果结束 */
/* 展开收起拼团成员列表开始 */
function groupjoinlist(label, num){
	num = num || 3;
	$(document).on('click',label,function(){
		var hidesum = 0;
		if($(this).prev('ul').css('display') == 'none'){
			$(this).prev('ul').slideDown();
			$(this).find('p').html('收起');
		}else{
			$(this).prev('ul').slideUp();
			$(this).find('p').html('查看更多');
		}

	});

}
/* 展开收起拼团成员列表结束 */