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
    <link rel="stylesheet" href="/static/web/css/reset.css" />
	<link rel="stylesheet" href="/static/web/css/swiper.min.css">
	<link rel="stylesheet" href="/static/web/css/style.css" />
	<script type="text/javascript" src="/static/web/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="/static/web/js/gotoTop.js"></script>
	<style type="text/css">
		.change1{
			width:414px;
			margin:0 auto;
		}
		.m-p{
			padding: 0 !important;
		}
		.topic-list{
			background: #fff;
			/*padding-top: 1.5rem;*/
		}
		.con-list{
			display: inline-block;
			background: none;
		}
		.more-box1{
			padding: 1.5rem 0;
			margin: 0;
		}
	</style>
</head>
<body style="overflow: scroll;">
	<div class="wrapper change1 m-p">
		<!--header开始-->
		<div class="header-box change1" style="left: auto;">
			<div class="index-header">
				<img src="/static/web/img/logo.png" class="logo"/>
				<p class="search-btn1"><input type="text" placeholder="搜索"/><img src="/static/web/img/searchbtn.png" class="searchbtn"/></p>
				<a href="shopCart.html" class="shop-cart"><img src="/static/web/img/cart.png" class="shop-cart-img"/><img src="/static/web/img/redPoint.png" class="shop-cart-have"/></a>
				<a href="signIn.html" class="sign-in">登录</a>
			</div>
		</div>
		<!--header结束-->
        
    	<!--content内容部分开始-->
    	<div class="content">
    	<foreach name="special_item_list" item="vo">
            <switch name="vo.item_type">
            	<case value="adv_list">
                    <include file="Special/adv_list" item_id= "{$vo.item_id}"/>
                </case>
                <case value="adv_img">
                    <include file="Special/adv_img" item_id= "{$vo.item_id}"/>
                </case>
                <case value="goods">
                    <include file="Special/adv_goods" item_id= "{$vo.item_id}"/>
                </case>
                <case value="adv_html">
                    <include file="Special/adv_html" item_id= "{$vo.item_id}"/>
                </case>
                <case value="adv_nav">
                    <include file="Special/adv_nav" item_id= "{$vo.item_id}"/>
                </case>
                <default />
            </switch>
            
        </foreach>
    	</div>
    	<!--content内容部分结束-->
    	
    	<div class="foot1">
    		<p class="icp"><span>网站版权所有</span><a href="http://www.miibeian.gov.cn" target="_blank">豫ICP备000001号</a></p>
    	</div>
    	<!--返回顶部-->
    	<div style="display:none;" class="gotobtn" id="goTopBtn"><img border=0 src="/static/web/img/goToTop.png"></div> 
	</div>
	<!--wrapper结束-->
	<script src="/static/web/js/swiper.min.js"></script>
	<script>
	//焦点图
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        //spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    });
    
    //调用返回顶部的函数
    goTopEx();
    </script>
</body>
</html>