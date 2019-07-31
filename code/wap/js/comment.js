var getcommenturl  = "/orders/commentlist";
var page_on_off = true;
/*获取商品评论*/
function getcomment(callback) {
	if(!get('goods_id')){
		window.location.href = "./goods_detail.html?id="+get('goods_id');
	}
	getcommentcallback = callback || getcommentcallback;
	data = {
		key: key ,
		id: get('id') ,
		goods_id:get('goods_id'),
		page:get('page') || 1
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
function getcommentcallback(info) {
	if(!info){
		window.location.href = "./goods_detail.html?id="+get('goods_id');
	}
	console.log(info);
	var html = template('commentlist', info);
	$('#commentdiv').html(html);
	var url = ApiUrl + getcommenturl;
	scrollgetdata(url, {key:key,id:get('id'),goods_id:get('goods_id'),page:1}, scrollgetdatacallback);
	initPage();
	SizeLimit($(".imgW"));

	viewimg();
	img_center();
}

function scrollgetdatacallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
		$(window).scroll(function(){});
		return ;
	}
	if(info['data']['orderlist'].length <1 ){
		shopdz_alert('评论加载完毕！');
		return ;
	}
	var html = template('commentlist', info);
	$('#commentdiv').append(html);
	page_on_off = true;
	initPage();
	SizeLimit($(".comment-img"));
	viewimg();
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