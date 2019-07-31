var myordersurl  = "/orders/getmyorders";
var delordersurl = "/orders/changeorder";
var rubbishordersurl = "/orders/changeorder";
var takeordersurl= "/orders/changeorder";
var cancelordersurl="/orders/changeorder";
var orderdetails = "/orders/orderdetails";
var evaluateurl  = "/orders/evaluate";
var page_on_off = true;
/*获取评论商品*/
function getgoods(rec_id, callback) {
	if(!rec_id){
		window.location.href = WapSiteUrl + "/orderdetails.html";
	}
	getgoodscallback = callback || getgoodscallback;
	data = {
		key:key,
		rec_id:rec_id
	}
	url = ApiUrl + evaluateurl;
	getdata(url,data,getgoodscallback);
}
/*提交评论*/
function submitcomment(rec_id, message, images, callback){
	if(!rec_id){
		//window.location.href = "./myorder.html";
	}
	subcallback = callback || subcallback;
	data = {
		key:key,
		rec_id:rec_id,
		message:message,
		evaluate_images:images
	}
	url = ApiUrl + evaluateurl;
	getdata(url,data,subcallback);
}
/*获取订单详情*/
function getorderdetails(order_sn, callback) {
	if(!order_sn){
		window.location.href = WapSiteUrl + "/myorder.html";
	}
	detailscallback = callback || detailscallback;
	data = {
		key:key,
		order_sn:order_sn
	}
	url = ApiUrl + orderdetails;
	getdata(url,data,detailscallback);
}
/*获取订单数据*/
function getmyorders(callback) {
	getcallback = callback || getcallback;
	data = {
		key:key,
		page:1
	}
	url = ApiUrl + myordersurl;
	getdata(url,data,getcallback);
}
/*确认收货*/
function takeorders(order_sn, callback){
	if(!order_sn){
		window.location.href = WapSiteUrl + "/myorder.html";
	}
	takecallback = callback || takecallback;
	data = {
		key:key,
		order_sn:order_sn,
		op:'ok'
	}
	url = ApiUrl + takeordersurl;
	getdata(url,data,takecallback);
}
/*删除订单*/
function delorders(order_sn, callback) {
	if(!order_sn){
		window.location.href = WapSiteUrl + "/myorder.html";
	}
	delcallback = callback || delcallback;
	data = {
		key:key,
		order_sn:order_sn,
		op:'del'
	}
	url = ApiUrl + delordersurl;
	getdata(url,data,delcallback);
}
/*取消订单*/
function cancelorders(order_sn, callback) {
	if(!order_sn){
		window.location.href = WapSiteUrl + "/myorder.html";
	}
	cancelcallback = callback || cancelcallback;
	data = {
		key:key,
		order_sn:order_sn,
		op:'cancel'
	}
	url = ApiUrl + cancelordersurl;
	getdata(url,data,cancelcallback);
}

function rubbishorder(order_sn, callback) {
	if(!order_sn){
		window.location.href = WapSiteUrl + "/myorder.html";
	}
	rubbishcallback = callback || rubbishcallback;
	data = {
		key:key,
		order_sn:order_sn,
		op:'rubbish'
	}
	url = ApiUrl + rubbishordersurl;
	getdata(url,data,rubbishcallback);
}

function getgoodscallback(info){
	console.log(info);
	if(!info){
		window.location.href = WapSiteUrl + "/myorder.html";
	}
	var html = template('goodscontent',info);
	$('#gooddetails').html(html);
	initPage();
	//获取上传图片的宽度为图片的高度
	var liWidth=$('#gooddetails').find('.liW').width();
	$('#gooddetails').find(".upload-inp").css("height",liWidth);
	$('#gooddetails').find(".liW").css("height",liWidth);
	$('#uploadphoto').change(function(){
		if(regImage($(this).val())){
			var li = $(this).parent().clone();
			viewimg();
			$(this).parent().before(li);
			$(this).parent().css('display','none');
		}
	});
	uploadimages($('#uploadphoto'), ApiUrl + '/Presales/refundimg', 'evaluate');
	
}
function detailscallback(info) {
	if(!info){
		window.location.href = WapSiteUrl + "/myorder.html";
	}
	var html = template('orderdetailscontent',info);
	$('#orderdetails').html(html);
	initPage();
}
function getcallback(info) {
	if(!info){
		window.location.href = WapSiteUrl + "/member.html";
	}
	var html = template('myorderscontent', info);
	$('#myorders').html(html);
	SizeLimit($(".order-img1"));
	var url = ApiUrl + myordersurl;
	scrollgetdata(url, {key:key,page:1}, scrollgetdatacallback);
	initPage();
}
function subcallback(info) {
	if(info['code'] == 0){
		window.location.href = WapSiteUrl + "/evaluateproductlist.html?order_sn=" + get('order_sn') + "&rec_id=" + get('rec_id');
	}else if(info['code']){
		shopdz_alert(info['msg']);
	}
}
function scrollgetdatacallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
		$(window).scroll(function(){});
		return ;
	}
	var html = template('myorderscontent', info);
	$('#myorders').append(html);
	page_on_off = true;
//	SizeLimit($(".upload-cover"));
	initPage();
	
}

function bodyH(){
	var bodyheight=document.documentElement.clientHeight || document.body.clientHeight;
	//alert(bodyheight);
	var commentImgH=$(".swiper-container").height();
	//alert(commentImgH);
	$(".swiper-container").css("margin-top",(bodyheight-commentImgH)/2+'px');
}



$(document).on('click','.swiperbox',function(){
		$(this).hide();
	})
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