var key = getcookie("key");
if(!key){
    window.location.href = WapSiteUrl+'/login.html';
}
var page_on_off = false;
var page = 1;
var shop_name = getwebConf('shop_name');
$('#tab-title li').click(function(){
    var status = $(this).attr('status');
    $('#statusinput').val(status);
    page_on_off = true;
    page = 1;
	$.ajax({
        url:"../api.php/coupon/lists/status/"+status,
        type:"get",
        data:{key:key},
        success: function(result) {
        	result = eval("("+result+")");
            if(result.code == 0){
                var datas = result.data;
                datas.shop_name = shop_name;
            	$('#li'+result.data.status).addClass("selected").siblings().removeClass();
                result.data.weburl = WapSiteUrl;
        	    var html = template('data_list', result);

                if(parseInt(result.data.havepage) == 1){
                    page_on_off = false;
                }
                if(result.data.list == ''){
                    html = '<div class="show voucher-list img-center"><img src="img/coupon-null.png" alt="" class="coupon-null-img"/><h4 class="coupon-tit">暂无优惠券</h4></div>'; 
                }
                if(result.data.status == 1){
                    $('#couponall').show();
                }else{
                    $('#couponall').hide();
                }

                $('.integral-num').html(result.data.points);
				$('#tab-contentul').html(html);
                initPage();
            }else{
            	shopdz_alert('数据请求失败，请重试！');
            }
        }
	});
	$(this).addClass("selected").siblings().removeClass();//removeClass就是删除当前其他类；只有当前对象有addClass("selected")；siblings()意思就是当前对象的同级元素，removeClass就是删除；
	$("#tab-content > ul").hide().eq($('#tab-title li').index(this)).show();

});

$(document).ready(function(){
	$.ajax({
        url:"../api.php/coupon/lists/status/1",
        type:"GET",
        data:{key:key},
        success: function(result) {
        	result = eval("("+result+")");
            if(result.code == 0){
                var datas = result.data;
                datas.shop_name = shop_name;
            	$('#li'+result.data.status).addClass("selected").siblings().removeClass();
                result.data.weburl = WapSiteUrl;
                var html = template('data_list', result);
                if(parseInt(result.data.havepage) == 1){
                    page_on_off = false;
                }
                if(result.data.list == ''){
                    html = '<div class="show voucher-list img-center"><img src="img/coupon-null.png" alt="" class="coupon-null-img"/><h4 class="coupon-tit">暂无优惠券</h4></div>';
                }
                $('.integral-num').html(result.data.points);
                $('#couponall').show();
				$('#tab-contentul').append(html);

                initPage();
            }else{
            	shopdz_alert('数据请求失败，请重试！');
            }
        }
	});
    $(".back-icon").unbind('click').bind("click",function(){
         window.location.href="member.html";
    });
    page_on_off = true;
    coulist_data = {
            key:key,
            page:page,
    }


    scrollgetdatacoupon('../api.php/coupon/lists',coulist_data,couponlistcallback);
})

function couponlistcallback(result){
    if(result.code == 0){
        var datas = result.data;
        datas.shop_name = shop_name;
        var html = template('data_list', result);
        if(result.data.list == ''){
            page_on_off = false;
        }
        if(parseInt(result.data.page) <= parseInt(result.data.havepage)){
            page_on_off = true;
        }else{
            shopdz_alert('已经到底了哦~~');
        }
        $('#tab-contentul').append(html);
    }else{
        shopdz_alert('数据请求失败，请重试！');
    }
}

function scrollgetdatacoupon(url, data, callback){
    page_on_off = page_on_off || false;
    $(window).scroll(function(){
        data['status'] = $('#statusinput').val();
        if(page_on_off){
             /* 底部的高度 */
            var fth = $('#footer').offsetheight || 0;
            var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var windowHeight = $(window).height();
            if(parseInt(scrollTop) + parseInt(windowHeight) + parseInt(fth) + 100 >= parseInt(scrollHeight)){
                /* 开关(在回调函数中开启) */
                page_on_off = false;
                page += 1;
                data['page'] = page;
                /* 开始请求 */
                getdata(url, data, callback);
            }
        }
    });
}