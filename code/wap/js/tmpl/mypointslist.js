var mypointslisturl  = "/member/getpointslist";
var page_on_off = true;

/*获取积分明细数据*/
function mypointslist(callback) {
	getcallback = callback || getcallback;
	data = {
		key:key,
		page:1
	}
	url = ApiUrl + mypointslisturl;
	getdata(url,data,getcallback);
}

function getcallback(info) {
	if(!info){
		window.location.href = WapSiteUrl + "/member.html";
	}
	var html = template('mypointscontent', info);
	$('#mypointslist').html(html);
	$('.pointsum').html(info.data.pointsum);
	SizeLimit($(".order-img1"));
	var url = ApiUrl + mypointslisturl;
	scrollgetdata(url, {key:key,page:1}, scrollgetdatacallback);
	initPage();
}
function scrollgetdatacallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
		$(window).scroll(function(){});
		return ;
	}
	var html = template('mypointscontent', info);
	$('#mypointslist').append(html);
	page_on_off = true;
	initPage();
	
}
