var getgeneralizeurl = '/spread/account';
var getmypresalesurl = '/spread/getmypresales';
var page_on_off = true;



function getgeneralize(callback){
	callback = callback || getgeneralizecallback;
	var url = ApiUrl + getgeneralizeurl;
	var data = {
		key:key
	};
	getdata(url,data,callback);
}
function getmypresales(callback){
	callback = callback || getmypresalescallback;
	var url = ApiUrl + getmypresalesurl;
	var data = {
		key:key
	}
	getdata(url,data,callback);
}
function getgeneralizecallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
	}else{
		var html = template('generalize_content',info['data']);
		$('#generalize').html(html);
		$('#qrcode').attr('src',info['data']['qrcode']);
		$('.http').html(info['data']['qrcode_url']);
		
	}
}
function getmypresalescallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
	}else{
		var html = template('generalize_content',info);
		$('#generalize ul').html(html);
		if(info.data && info.data.length > 0){
			$('#generalize').show();
		}else{
			$('.spread-null').show();
		}
		var url = ApiUrl + getmypresalesurl;
		scrollgetdata(url, {key:key,page:1}, scrollgetmypresalescallback);
		$('#all_price').html(get('all'));
		$('#qrcode').attr('src',info['data']['qrcode']);
	}
}
function scrollgetmypresalescallback(info){
	if(info['code']){
		shopdz_alert(info['msg']);
		$(window).scroll(function(){});
		return ;
	}
	var html = template('generalize_content', info);
	$('#generalize ul').append(html);
	page_on_off = true;
	initPage();
}