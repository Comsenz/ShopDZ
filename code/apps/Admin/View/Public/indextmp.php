<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="aplus-terminal" content="1"/>
    <meta name="apple-mobile-web-app-title" content="TMALL"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title>首页</title>
    <link rel="stylesheet" href="__PUBLIC__/web/css/reset.css" />
    <link rel="stylesheet" href="__PUBLIC__/web/css/swiper.min.css">
    <link rel="stylesheet" href="__PUBLIC__/web/css/style.css" />
    <script type="text/javascript" src="__PUBLIC__/web/js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/web/js/gotoTop.js"></script>
</head>

<body>
<div class="wrapper">
    <!--header开始-->
    <div class="header-box">
        <div class="index-header">
            <img src="__PUBLIC__/web/img/logo.png" class="logo"/>
            <p class="search-btn1"><input type="text" placeholder="搜索"/><img src="__PUBLIC__/web/img/searchbtn.png" class="searchbtn"/></p>
            <a href="shopCart.html" class="shop-cart"><img src="__PUBLIC__/web/img/cart.png" class="shop-cart-img"/><img src="__PUBLIC__/web/img/redPoint.png" class="shop-cart-have"/></a>
            <a href="signIn.html" class="sign-in">登录</a>
        </div>
    </div>
    <!--header结束-->
        <!--从这里进行元素块的组装-->
        MODULES_CONTENT_TPL
        <!--从这里进行元素块的组装end-->
        <!--<div class="content">
            SPECIAL_CONTENT_TPL<!--专题列表-->
    <!--  GOODS_CONTENT_TPL<!--商品列表-->
    <!--  </div>-->
    <div class="foot1">
        <p class="icp"><span>网站版权所有</span><a href="http://www.miibeian.gov.cn" target="_blank">豫ICP备000001号</a></p>
    </div>
    <!--返回顶部-->
    <div style="display:none;" class="gotobtn" id="goTopBtn"><img border=0 src="__PUBLIC__/web/img/goToTop.png"></div>
</div>
<script src="__PUBLIC__/web/js/swiper.min.js"></script>
<script>
    //焦点图
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    });

    //调用返回顶部的函数
    goTopEx();
</script>
</body>
</html>
<!--wrapper结束-->