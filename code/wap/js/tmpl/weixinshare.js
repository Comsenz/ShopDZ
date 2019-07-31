$(function(){
    category();
    var url = location.href.split('#')[0];
    var conf = getwebConf();
    if(isWeiXin()) {
        $.get(ApiUrl + '/Member/GetSignPackage', {"url": encodeURIComponent(url)}, function (data) {
            if (data.code == 0) {
                wx.config({
                    debug: false,
                    appId: data.data.appId,
                    timestamp: data.data.timestamp,
                    nonceStr: data.data.nonceStr,
                    signature: data.data.signature,
                    jsApiList: ['onMenuShareAppMessage','onMenuShareTimeline'
                        // 所有要调用的 API 都要加到这个列表中
                    ]
                });

            }
        }, 'json');
        wx.ready(function () {
            var imgurl = conf.wx_shareimg || conf.shop_logo;
            var url = location.href;
            if(key) {
				var tmp_url = location.href;
				url = tmp_url.indexOf('?') != -1 ? tmp_url+'&share=1&fromid=' + getcookie('uid') : tmp_url+'?share=1&fromid=' + getcookie('uid');
            }
            wx.onMenuShareAppMessage({
                title: conf.wx_sharetitle || conf.shop_name,
                desc: conf.wx_sharedesc || conf.web_introduce,
                link: url,
                imgUrl: imgurl,
                type: '',
                dataUrl: '',
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
            wx.onMenuShareTimeline({
                title:(conf.wx_sharetitle || conf.shop_name) + '-' + (conf.wx_sharedesc || conf.web_introduce),
                link: url,
                imgUrl: imgurl,
                success: function () {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                }
            });
        })
    }
})
