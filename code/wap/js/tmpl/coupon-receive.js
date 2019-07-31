var id = get('id');
var key = getcookie("key");
var shop_name = getwebConf('shop_name');
$(document).ready(function(){
	$.ajax({
        url:"../api.php/coupon/getcoupon/id/"+id,
        type:"GET",
        data:{key:key},
        success: function(result) {
        	result = eval("("+result+")");
            if(result.code == 0){
                var datas = result.data;
                datas.shop_name = shop_name;
            	var html = template('data_list', result);
				$('#tab-content').html(html);
                if(result['data']['info']['status']){
                    flag = false;
                    shopdz_alert('您已经领取了'+result['data']['info']['number']+'张！',1,function(){
                        window.location.href = WapSiteUrl + '/coupon-all.html';
                    });
                    $('.immediately-recive').addClass('bg-grayf0');
                }
            }else{
            	shopdz_alert('数据请求失败，请重试！');
            }
        }
	});


})

var flag = true;
function sendCoup (id){
    var id = id;
    var key = getcookie("key");
    if(!flag) return;
    flag = false;
    var url = location.href.split('#')[0];
    $.ajax({
        url:'../api.php/coupon/coupon_insert/rpacket_t_id/'+id,
        type:'get',
        data:{key:key},
        success: function(result) {
            result = eval("("+result+")");
            if(result.code == 0){
                flag = true;
                shopdz_alert('领取成功，该优惠券已领取'+result.data+'张。');
            }else if(result.code == 1){
                flag = false;
                $('.immediately-recive').addClass('bg-grayf0');
                shopdz_alert(result.msg,1,function(){
                        window.location.href = WapSiteUrl + '/coupon-all.html';
                    });
            }else{
                flag = false;

                    shopdz_alert('领取成功，您已领完所有优惠券。',1,function(){
                        window.location.href = WapSiteUrl + '/coupon-all.html';
                    });

            }
        }

    })
}
/*
function callbackgo(){
    $.ajax({
        url:"../api.php/coupon/getcoupon/id/"+id,
        type:"GET",
        data:{key:key},
        success: function(result) {
            result = eval("("+result+")");
            if(result.code == 0){
                var html = template('data_list', result);
                $('#tab-content').html(html);
                number = result.data.number;
                flag = true;
            }else{
                shopdz_alert('数据请求失败，请重试！');
            }
        }
    });
}
*/