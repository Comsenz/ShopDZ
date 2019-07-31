var getfootprintsurl = ApiUrl+"/FootPrint/getFootPrint";
function getfootprints(callback) {
	getcallback = callback || getcallback;
	data = {
		key:key
	}
	url = getfootprintsurl;
	getdata(url,data,getcallback);
}


function getcallback(info) {
	var html = template('shopfavoritescontent', info);
	$('#shopfavorites').html(html);
	goodnum = info.data.list.length;
	initPage();
}