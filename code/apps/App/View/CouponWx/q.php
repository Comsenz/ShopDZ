<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="aplus-terminal" content="1"/>
    <meta name="keywords" content="SHOPDZ" />
    <meta name="apple-mobile-web-app-title" content="SHOPDZ"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title>领取优惠券</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div id="header_has_nav">
</div>
<!--wrapper开始-->
<div class="wrapper wrapper-bg" style="margin-top: 14px;">
    <div class="content">
        <div class="coupon-list">
            <ul id="tab-content">
                <script type="text/html"  id="data_list">
                    <li class="not-used-coupon coupon-li">
                        <div class="coupon-box-det coupon-det">
                            <div class="coupon-box-top <%=data['info']['rpacket_t_color']%>">
                                <div class="coupon-top-con">
                                    <input type="hidden" id="rpacket_t_id" value="<%=data['info']['rpacket_t_id']%>" />
                                    <p class="coupon-subtit"><%=data['info']['rpacket_t_title']%></p>
                                    <%if(data['info']['rpacket_t_share'] == 1){%> <span class="coupon-share">分享</span> <%}%>
                                    <p class="coupon-tit">￥<%=data['info']['rpacket_t_price']%></p>
                                    <p class="coupon-limit">订单满<span><%=data['info']['rpacket_t_limit']%>元（含运费）</span></p>
                                    <p class="coupon-time">有效期：<span><%=data['info']['start']%></span>-<span><%=data['info']['end']%></span></p>
                                </div>
                            </div>
                            <div class="coupon-box-bottom">
                                <p class="sc-btn"><%=data['info']['rpacket_t_points']%>积分兑换</p>
                            </div>
                        </div>
                    </li>
                </script>
            </ul>
        </div>
        <input type="submit" value="立即领取" class="immediately-recive"/>
    </div>
</div>
<!--wrapper结束-->
</body>
<script type="text/javascript" src="/wap//wap/js/jquery-1.7.1.min.js"></script>
<!-- <script type="text/javascript" src="/wap//wap/js/config.js"></script> -->
<script type="text/javascript" src="/wap//wap/js/template.js"></script>
<script type="text/javascript" src="/wap//wap/js/common.js"></script>
<script type="text/javascript" src="/wap//wap/js/tmpl/weixin.js"></script>
<script type="text/javascript" src="/wap//wap/js/tmpl/coupon-receivewx.js"></script>
<script type="text/javascript">
    $(function(){
        initPage();
    })
</script>



</html>
