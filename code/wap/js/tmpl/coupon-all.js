var key = getcookie("key");
var page_on_off = false;
var shop_name = getwebConf('shop_name');
var type = get('type')
var wxCoupid = get('wxCoupid')
if(!key && type != 'wx'){
    window.location.href = WapSiteUrl+'/login.html';
}
if(wxCoupid){
    sendWxcard(wxCoupid);
}
$(document).ready(function(){
        if($('.logo').length){
            var shoplogo = getwebConf('shop_logo');
            $('.logo').attr('src',shoplogo);
        }
        if(type == 'wx'){
            var url = "../api.php/couponWx/lists"
        }else{
            var url = "../api.php/coupon/lists/status/4"
        }
        $.ajax({
            url:url,
            type:"GET",
            data:{key:key},
            success: function(result) {
                result = eval("("+result+")");
                if(result.code == 0){
                    $('.integral-num').html(result.data.points);/*插入积分数据*/
                    if(result.data.list.length > 0){
                        var datas = result.data;
                        datas.shop_name = shop_name;
                        result._ApiUrl = ApiUrl;
                        var html = template('data_list_all', result);
                        $('#tab-contentul').append(html);/*插入优惠券数据*/
                        page_on_off = true;
                    }else{
                        $('#tab-contentul').html('<div class="show voucher-list img-center"><img src="img/coupon-null.png" alt="" class="coupon-null-img"/><h4 class="coupon-tit">暂无优惠券</h4></div>');
                        page_on_off = false;
                    }
                    initPage();
                }else{
                   // shopdz_alert('数据请求失败，请重试！');
                }
            }
        });
        coulist_data = {
            key:key,
            page:1,
        }
        scrollgetdatacoupon('../api.php/coupon/lists',coulist_data,couponlistcallback);
    })

function tocard(id,state){
    if(state == 0){
        shopdz_confirm('是否确认领取？','确定','取消',function(c){
                sendCoup(id);
        })
    }else{
        if(isWeiXin()){
            window.location.href = ApiUrl + '/CouponWx/UserBindWx?id=' + id;
        }else{
            shopdz_confirm('请在微信浏览器中领取微信卡券','确定','取消',function(){
                return;
            });
        }
    }

}
function wxtocard(id){

    if(isWeiXin()){
        var key = getcookie("key");
        if(key){
            window.location.href = ApiUrl + '/CouponWx/UserBindWx?type=wx&id='+id;
        }else{
            sendWxcard(id)
        }
    }else{
        shopdz_confirm('请在微信浏览器中领取微信卡券','确定','取消',function(){
            return;
        });
    }
}
function sendWxcard(id){
    var url = location.href.split('#')[0];
    $.get(ApiUrl + '/Member/GetSignPackage', {"url": encodeURIComponent(url)}, function (data) {
        if (data.code == 0) {
            wx.config({
                debug: false,
                appId: data.data.appId,
                timestamp: data.data.timestamp,
                nonceStr: data.data.nonceStr,
                signature: data.data.signature,
                jsApiList: ['addCard'
                    // 所有要调用的 API 都要加到这个列表中
                ]
            });
        }
    }, 'json');
    $.ajax({
        url:ApiUrl + '/CouponWx/GetCodeSign',
        type:'post',
        data:{"id": id,"url":url},
        async: false,
        success: function(result) {
            result = eval("("+result+")");
            if (result.code == 0) {
                wx.ready(function () {
                    wx.addCard({
                        cardList: [{
                            cardId:result.data.card_id,
                            cardExt:result.data.ext
                        }],
                        success: function (res) {
                           shopdz_alert('已添加卡券',1,function(){
                              window.location.href = document.referrer;//上级网址
                           });
                        }
                    })

                })
            }
        }
    })
}
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
function couponlistcallback(result){
    if(result.code == 0){
        var datas = result.data;
        datas.shop_name = shop_name;
        var html = template('data_list_all', result);
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
        data['status'] = 4;
        if(page_on_off){
             /* 底部的高度 */
            var fth = $('#footer').offsetheight || 0;
            var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var windowHeight = $(window).height();
            if(parseInt(scrollTop) + parseInt(windowHeight) + parseInt(fth) + 100 >= parseInt(scrollHeight)){
                /* 开关(在回调函数中开启) */
                page_on_off = false;
                data['page'] += 1;
                /* 开始请求 */
                getdata(url, data, callback);
            }
        }
    });
}

function gologin(){
    window.location.href="login.html";
}
