var getcauseurl = "/presales/cause";
var getorderurl = "/orders/orderdetails";
var getordergoodsurl = "/orders/getordergoods";
var setrefunddataurl  = "/presales/refund";
var setreturndataurl  = "/presales/returngood";

function setrefunddata(callback){
	setdatacallback = callback || setdatacallback;
	data = {
		key:key,
		order_sn:get('order_sn'),
		causes_id:$('select[name="causes_id"]').val(),
		causes:$('textarea[name="causes"]').val(),
		refund_images:{
			0:$('.upload-cover:eq(0)').attr('src'),
			1:$('.upload-cover:eq(1)').attr('src'),
			2:$('.upload-cover:eq(2)').attr('src'),
		}
	}
	url = ApiUrl + setrefunddataurl;
	getdata(url, data, setdatacallback);
}

function setreturngoodsdata(callback){
	setdatacallback = callback || setdatacallback;
	data = {
		key:key,
		order_sn:get('order_sn'),
		rec_id:get('rec_id'),
		return_goodsnum:$('input[name="goods_num"]').val(),
		return_amount:$('input[name="goods_amount"]').val(),
		causes_id:$('select[name="causes_id"]').val(),
		causes:$('textarea[name="causes"]').val(),
		return_images:{
			0:$('.upload-cover:eq(0)').attr('src'),
			1:$('.upload-cover:eq(1)').attr('src'),
			2:$('.upload-cover:eq(2)').attr('src'),
		}
	}
	url = ApiUrl + setreturndataurl;
	getdata(url, data, setdatacallback);
}

function getcause(callback){
	getcausecallback = callback || getcausecallback;
	data = {
		key:key
	}
	url = ApiUrl + getcauseurl;
	getdata(url, data, getcausecallback);
}

function getorder(callback){
	getordercallback = callback || getordercallback;
	data = {
		key:key,
		order_sn:get('order_sn')
	}
	url = ApiUrl + getorderurl;
	getdata(url, data, getordercallback);
}

function getordergoods(callback){
	getordercallback = callback || getordercallback;
	data = {
		key:key,
		order_sn:get('order_sn'),
		rec_id:get('rec_id')
	}
	url = ApiUrl + getordergoodsurl;
	getdata(url, data, getordercallback);
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

function setdatacallback(info){
	if(info['code'] == 0){
		var location_url = '';
		if (info['data']['refund_sn']) {
			location_url = "/refundproductlist.html?refund_sn=" + info['data']['refund_sn'];
		} else if (info['data']['return_sn']) {
			location_url = "/returnproductlist.html?return_sn=" + info['data']['return_sn'];
		}
		window.location.href = WapSiteUrl + location_url;
	} else {
		shopdz_alert(info['msg']);
		$('.refund-btn2').removeClass('bg');
		submit = true;
	}
}

function getcausecallback(info){
	var html = template('messagecontent', info);
	$('#message').html(html);
	$('#uploadphoto').change(function(){
		if(regImage($(this).val())){
			var li = $(this).parent().clone();
			$(this).parent().before(li);
			$(this).parent().css('display','none');
		}
	});
	uploadimages($('#uploadphoto'), ApiUrl + '/Presales/refundimg', 'refund');
	initPage();
	//获取上传图片的宽度为图片的高度
	var liWidth=$('#message').find('.liW').width();
	$('#message').find(".upload-inp").css("height",liWidth);
	$('#message').find(".liW").css("height",liWidth);
}

function getordercallback(info){
	goods_num = parseInt(info['data']['goods_num']);
	amount = parseFloat(info['data']['order_amount']) || parseFloat(info['data']['goods_price']);
	var html = template('goodscontent', info);
	$('#goods').html(html);
	SizeLimit($(".return-img"));
	initPage();
}

