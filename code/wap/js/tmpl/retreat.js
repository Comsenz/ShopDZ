var getrefundlisturl  = "/presales/refundlist";
var getreturnlisturl  = "/presales/returnlist";
var getrefunddetailsurl  = "/presales/refunddetail";
var getreturndetailsurl  = "/presales/returndetail";
var getcommenturl = "/orders/myevaluate";

function getrefundlist(callback){
	if($('#refund').attr('content') == '0'){
		getrefundlistcallback = callback || getrefundlistcallback;
		data = {
			key:key,
			order_sn:get('order_sn')
		}
		url = ApiUrl + getrefundlisturl;
		getdata(url, data, getrefundlistcallback);
	}
}

function getreturnlist(callback){
	if($('#return').attr('content') == '0'){
		getreturnlistcallback = callback || getreturnlistcallback;
		data = {
			key:key,
			order_sn:get('order_sn')
		}
		url = ApiUrl + getreturnlisturl;
		getdata(url, data, getreturnlistcallback);
	}
}

function getrefunddetails(callback){
	getdetailscallback = callback || getdetailscallback;
	data = {
		key:key,
		refund_sn:get('refund_sn')
	}
	url = ApiUrl + getrefunddetailsurl;
	getdata(url, data, getdetailscallback);
}

function getreturndetails(callback){
	getdetailscallback = callback || getdetailscallback;
	data = {
		key:key,
		rec_id:get('rec_id'),
		return_sn:get('return_sn')
	}
	url = ApiUrl + getreturndetailsurl;
	getdata(url, data, getdetailscallback);
}
/*查看评论详情*/
function getcomment(rec_id, order_sn, callback){
	if(!rec_id){
		//window.location.href = "./myorder.html";
	}
	getcommentcallback = callback || getcommentcallback;
	data = {
		key:key,
		rec_id:rec_id,
		order_sn:order_sn
	}
	url = ApiUrl + getcommenturl;
	getdata(url,data,getcommentcallback);
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

function getrefundlistcallback(info){
	var html = template('refundcontent', info);
	$('#refund').html(html);
	$('#refund').attr('content','1');
	initPage();
}

function getreturnlistcallback(info){
	var html = template('returncontent', info);
	$('#return').html(html);
	$('#return').attr('content','1');
	initPage();
}

function getdetailscallback(info){
	var html = template('detailscontent', info);
	$('#details').html(html);
	initPage();
	SizeLimit($(".imgW"));
	viewimg();
	img_center();
	/* 获取order_sn */
	var order_sn = info['data']['order_sn'] || info['data']['refund']['order_sn'];
	/* 拼装回退的url（前半部分url需要在html页面上定义） */
	back_url += '?order_sn=' + order_sn;
	/* 上一步的url正则 */
	var reg = /refund.html|returngoods.html/;
	var ret = /retreat.html/;
	/* 验证正则修改回退路径 */
	if (reg.test(document.referrer)) {
		$('.goback-header').find('.back-icon').unbind('click').click(function() {
			window.location.href = WapSiteUrl + back_url;
		});
	} else if (ret.test(document.referrer)) {
		$('.goback-header').find('.back-icon').unbind('click').click(function() {
			window.location.href = WapSiteUrl + '/retreat.html?status=' + status;
		});
	}
}
/* 获取个人评论的回调 */
function getcommentcallback(info){
	$('.goback-header').find('.back-icon').unbind('click').click(function() {
		window.location.href = WapSiteUrl + '/orderevaluate.html?order_sn=' + get('order_sn');
	});
	var html = template('comment', info);
	$('#details').html(html);
	initPage();
	SizeLimit($(".imgW"));

	viewimg();
	img_center();
}

function bodyH(){
	var bodyheight=document.documentElement.clientHeight || document.body.clientHeight;
	//alert(bodyheight);
	var commentImgH=$(".swiper-container").height();
	//alert(commentImgH);
	$(".swiper-container").css("margin-top",(bodyheight-commentImgH)/2+'px');
}



function commentLimit(){
	var  bodywidth =  0;
	if(intval(document.body.clientWidth)>0){
		bodywidth	 =  intval(document.body.clientWidth);
	}
	if(intval(document.documentElement.clientWidth<540)>0){
		bodywidth	 =  intval(document.documentElement.clientWidth<540);
	}

	if(bodywidth<540){ 
			//alert("小于540px");
			$(".swiperbox").css("width","100%");
		} else { 
			//alert("大于540px");
			$("body").find(".swiperbox").css({"width":"540px","left":(bodywidth/2-270)+'px'});
		} 
 	return true;
}