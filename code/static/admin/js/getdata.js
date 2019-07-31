/* 公共获取数据函数 */
function getdata (ApiUrl,data,callback) {
	$.ajax({
		type: 'POST',
		url: ApiUrl,
		data: data,
		success: callback,
		dataType: 'json'
	});
}