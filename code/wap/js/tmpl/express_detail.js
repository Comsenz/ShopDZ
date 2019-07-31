$(function(){
	var order_sn = get('order_sn');
	if(!order_sn){
		shopdz_alert('参数错误,网址后面加上?order_sn=111',2,function(){
			window.location.href=WapSiteUrl;			
		});
	}else{
		$.post(ApiUrl+'/Express/express_detail',{"order_sn":order_sn},function(data){
			if(data.code == 0){
				var html = template('express_detail_template',data.data);
				$('#express_detail').html(html);
				initPage();
			}else{
				shopdz_alert(data.msg);
			}

		},'json');
	}

});