var getbasketurl = "/basket/getBasket";
var delbasketurl = "/basket/delBasket";
var addbasketurl = "/basket/addBasket";
var subbasketurl = "/..../....";
var goodnum = 0;
function getbasket(callback) {
	getcallback = callback || getcallback;
	data = {
		key:key
	}
	url = ApiUrl+getbasketurl;
	getdata(url,data,getcallback);
}

function setbasket(label, goods_id, number,type, callback) {
	addcallback = callback || addcallback;
	type = type || 'basket';
	data = {
		key:key,
		id:goods_id,
		type:type,
		num:number
	}
	url = ApiUrl+addbasketurl;
	getgoodsnum(url,data,addcallback,label);
}

function delbasket(cart_id, uid, callback) {
	delcallback = callback || delcallback;
	data = {
		key:key,
		id:cart_id,
		member_uid:uid
	}
	url = ApiUrl+delbasketurl;
	getdata(url,data,delcallback);
}

function getgoodsnum (ApiUrl,data,callback,label) {
	$.ajax({
		type: 'POST',
		url: ApiUrl,
		data: data,
		success: function(info){
			callback(info,label)
		},
		dataType: 'json',
		async: false
	});
}
function getcallback(info) {
	var html = template('shopcartcontent', info);
	$('#shopcart').html(html);
	$('.text_box').each(function(){
		numAddClass($(this));
	})
	if(info['data'].length<1){
		$('#submit').addClass('bg');
		ajaxsubmit = false;
	}
	goodnum = info['data'].length;
	login = info['msg'];
	sumprice();
	SizeLimit($(".cart-list-img"));
	initPage();
//	addClass();
}

function addcallback(info, label) {
	var cartprice=label.parents('.cart-list-rl').siblings().children('p').children('span');
	var price=label.parents('.cart-list-rl').siblings().children('input');
	var c=cartprice.html();
	var p=price.val();
	var goods_maxnum = 99;
	if(info['code']){
		shopdz_alert(info['msg']);
		//label.val(label.attr('num'));
		label.val(info['data']['goods_num']);
		cartprice.html((parseFloat(p)*100)*parseInt(info['data']['goods_num'])/100);
		goods_maxnum = info['data']['goods_num'];
	}else{
		if(isNaN(label.val()) || parseInt(label.val())<1 || label.val() == ''){
			label.val(label.attr('num'));
			c=p;
		}else{
			label.val(parseInt(label.val()));
			var total = (parseFloat(p)*100)*parseInt(label.val())/100;
		  	cartprice.html(total);
		}
		label.attr('num',label.val())
	}
	/* 移除商品状态 */
	label.parents('.cart-list-r').find('.soldOut').hide();
	label.parents('li').find('input:checkbox').attr('goodnum','');
	label.parents('.cart-list-r').removeClass('basket');
	/* 判断商品数量加減上限 */
	numAddClass(label,goods_maxnum);
	sumprice();
	ajaxsubmit = true;
}

function delcallback(info) {
	if(info.code ==0){
		shopdz_alert('商品删除成功！');
		goodnum = goodnum -0 -1;
		if(goodnum > 0){
			$('.head-more').addClass('head-moreimgred');
		}else{
			$('.head-more').removeClass('head-moreimgred');
			$('.firstli').css('display','block');
			$('.shopcart-foot').remove();
		}
	}
	sumprice();
	ajaxdel = true;
}