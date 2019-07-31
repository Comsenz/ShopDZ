var getfightgroupurl = '/plugin/index/code/group/action/grouplist';
var getgroupgoodsdetailurl = '/plugin/index/code/group/action/detail';
var getgroupdetailurl = '/plugin/index/code/group/action/groupjoin';
var getmygroupurl = '/plugin/index/code/group/action/mygroup';
var getgrouporderurl = '/plugin/index/code/group/action/grouppayinfo';
var groupcheckurl = '/plugin/index/code/group/action/groupcheck';
var groupsettlement = 'plugins/group/wap/groupsettlement.html';
var groupset ='/plugin/index/code/group/action/getGroupSet';
var page_on_group = page_on_active = true;
$(document).on('click','.comAlert-close',function() {
	$(".comAlert-box").hide();
	$(".cover").hide();
});
/* 公共的滚动下拉展示数据 */
function groupscrollgetdata(url, data, callback){
	if($('.mygroup_list').hasClass('show')){
		var url = ApiUrl + getmygroupurl;
		callback = scrollgetmygroupcallback;
	}else if($('.active_list').hasClass('show')){
		var url = ApiUrl + getfightgroupurl;
		callback = scrollgetfightgroupcallback;
	}
    $(window).scroll(function(){
    	if(page_on_group){
		    var h = $(window).height();
		    /* 整个文档的高度 */
		    var th = $(document).height();
		    /* 底部的高度 */
		    var fth = $('#footer').height();
		    /* 页面的滚动高度 */
		    var st =$(window).scrollTop();
		    if(th- h - fth - st < 300){
		    	/* 开关(在回调函数中开启) */
		    	//page_on_off = false;
				page_on_group = false;
				
		    	data['page'] += 1;
		        /* 开始请求 */
		        getdata(url, data, callback);
		    }
		}
    });
}
function activescrollgetdata(url, data, callback){
	if($('.mygroup_list').hasClass('show')){
		var url = ApiUrl + getmygroupurl;
		callback = scrollgetmygroupcallback;
	}else if($('.active_list').hasClass('show')){
		var url = ApiUrl + getfightgroupurl;
		callback = scrollgetfightgroupcallback;
	}
	
    $(window).scroll(function(){
    	if(page_on_active){
		    var h = $(window).height();
		    /* 整个文档的高度 */
		    var th = $(document).height();
		    /* 底部的高度 */
		    var fth = $('#footer').height();
		    /* 页面的滚动高度 */
		    var st =$(window).scrollTop();
		    if(th- h - fth - st < 300){
		    	/* 开关(在回调函数中开启) */
		    	//page_on_off = false;
				page_on_active = false;
		    	data['page'] += 1;
		        /* 开始请求 */
		        getdata(url, data, callback);
		    }
		}
    });
}

function creatgroup(callback) {
	callback = callback || addcallback;
	data = {
		key:key,
		active_id: get('active_id'),
	}
	url = ApiUrl+groupcheckurl;
	getdata(url,data,callback);
}
function joingroup(callback) {
	callback = callback || addcallback;
	data = {
		key:key,
		active_id: get('active_id'),
		group_id:get('group_id'),
	}
	url = ApiUrl+groupcheckurl;
	getdata(url,data,callback);
}

function clickA(url) {    
	var el = document.createElement("a");
	document.body.appendChild(el);
	el.href = url; //url 是你得到的连接
	el.click();
	document.body.removeChild(el);
}

function addcallback(info){

	if(info.code > 0){
		switch (info.code){
			case 1:
				if(info.msg == '请登录'){

					shopdz_alert('请登录',2,function(){
						window.location.href = rootUrl+'/wap/login.html';
					});
				}else{
					shopdz_alert(info.msg,2);
				}

				break;
			case 2:
				$(".comAlert-word").html(info.msg);
				$(".comAlert-box").find('a').attr('href',rootUrl+groupsettlement+'?order_sn='+info.data.order_sn);
				$(".comAlert-box").show();
				$(".cover").show();

				break;
			case 3:
				clickA(rootUrl+groupsettlement+'?order_sn='+info.data.order_sn);
				//window.location.href = rootUrl+groupsettlement+'?order_sn='+info.data.order_sn;
				break;
			case 4:
				shopdz_alert(info.msg,2);
				break;
			case 6:
				clickA( rootUrl+groupsettlement+'?active_id='+get('active_id')+'&group_id='+get('group_id'));
				//window.location.href = rootUrl+groupsettlement+'?active_id='+get('active_id')+'&group_id='+get('group_id');
				break;
			case 7:
				clickA(rootUrl+groupsettlement+'?active_id='+get('active_id'));
				//window.location.href = rootUrl+groupsettlement+'?active_id='+get('active_id');
				break;
		}

	}else{
		clickA(rootUrl+groupsettlement+'?active_id='+get('active_id')+'&group_id='+get('group_id'));
		//window.location.href = rootUrl+groupsettlement+'?active_id='+get('active_id')+'&group_id='+get('group_id');
	}
}

/* 拼团列表开始 */
function getfightgroup(callback){
	callback = callback || getfightgroupcallback;
	var url = ApiUrl + getfightgroupurl;
	var data = {
		
	};
	getdata(url,data,callback);
}

function getfightgroupcallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
	}else{
		var url = ApiUrl + getfightgroupurl;
		info.rootUrl = rootUrl;
		var html = template('fightgroupcontent',info);
		$('#fightgrouplist').html(html);
		initPage();
		activescrollgetdata(url, {key:key,page:1}, scrollgetfightgroupcallback);
		
		if(info.data.length < 1 ){
			$('.default-page').show();
		}
	}
}
function scrollgetfightgroupcallback(info){

	if(info['data'].length < 1){
		shopdz_alert('已经到底了哟~');
		page_on_active = false;
		return ;
	}else{
		info.rootUrl = rootUrl;
		var html = template('fightgroupcontent', info);
		$('#fightgrouplist').append(html);
		page_on_active = true;
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
		groupscrollgetdata(url, {key:key,page:1}, scrollgetmygroupcallback);
		if(info.data.length < 1 ){
			$('.default-page').show();
		}
	}
}
function scrollgetmygroupcallback(info){
	if(info['data'].length < 1){
		shopdz_alert('已经到底了哟~');
		page_on_group = false;
		return ;
	}else{
		var html = template('mygroupcontent', info);
		$('#mygroup').append(html);
		page_on_group = true;
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
		$('#goods_detail').html(info.data.goods.goods_detail);
		initPage();
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
		info['rootUrl'] = rootUrl;
		var NowTime = new Date();
        var t = info['data']['groupinfo']['group_end_time']*1000 - NowTime.getTime();
		info['data']['groupinfo']['day'] = Math.floor(t/1000/60/60/24);
		var html = template('groupdetailcontent',info);
		$('#groupdetail').html(html);
		initPage();
		
		$('#goods_detail').html(info.data.goods.goods_detail);
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
            var linkurl = rootUrl+'plugins/group/wap/groupInvitation.html?active_id='+info['data']['group']['id']+'&group_id='+info['data']['groupinfo']['id'];
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
		info['rootUrl'] = rootUrl;
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
function getGroupSet(callback){
	callback = callback || getGroupSetCallback;
	var url = ApiUrl + groupset;
	var data = {
	}
	getdata(url,data,callback)
}
function getGroupSetCallback(info){
	if(info.data.group_img.length > 0){
		$("#group_img").attr("src",info.data.group_img);
	}
}
function groupplay(info){
	$(".artical-content").html(info.data.group_content);
	initPage();
}
/* 展开收起拼团成员列表结束 */