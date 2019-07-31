var getfavoritesurl = "/favorites/getfavorites";
var delfavoritesurl = "/favorites/delfavorites";
var goodnum = 0;
var page_on_off = true;
function getfavorites(callback) {
	getcallback = callback || getcallback;
	data = {
		key:key
	}
	url = ApiUrl+getfavoritesurl;
	getdata(url,data,getcallback);
}

function delfavorites(favorites_id, uid, callback) {
	delcallback = callback || delcallback;
	data = {
		key:key,
		fav_id:favorites_id,
		member_id:uid
	}
	url = ApiUrl+delfavoritesurl;
	getdata(url,data,delcallback);
}
function getcallback(info) {
	var html = template('shopfavoritescontent', info);
	$('#shopfavorites').html(html);
	scrollgetdata(ApiUrl+getfavoritesurl, {key:key,page:1}, scrollgetdatacallback);
	initPage();
}
function scrollgetdatacallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
		$(window).scroll(function(){});
		return ;
	}
	goodnum = info.data.list.length;
	if(goodnum == '0'){
		page_on_off = false;
		shopdz_alert('已经到底了哦~~');
		return;
	}
	var html = template('shopfavoritescontent', info);
	$('#shopfavorites').append(html);
	initPage();
}

function delcallback(info) {
	if(info.code ==0){
		shopdz_alert('收藏删除成功！');
		if($('.myorder1').length < 1){
			$('.firstli').css('display','block');
		}
	}
	ajaxdel = true;
}