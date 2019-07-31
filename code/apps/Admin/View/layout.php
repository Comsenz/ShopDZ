<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>SHOPDZ商城</title>
		<meta name="keywords" content="SHOPDZ" />
		<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/index.css"/>
		 <script type="text/javascript" src="__PUBLIC__/admin/js/jquery-1.9.1.min.js"></script>
	</head>
	<body>
		<div class="wrapper">
			<div class="head-box">
					<div class="logobox left">
						<img src="__PUBLIC__/admin/images/logo.png" alt="logo" class="logo"/>
					</div>
					<div class="headbox-r left">
						<ul class="person-box left">
							<li><img src="__PUBLIC__/admin/images/person.png" class="per-icon2"/></li>
							<li class="name"><?=$admin_user['username'];?></li>
							<li><?=$admin_user['group_text'];?></li>
						</ul>
						<ul class="setting-list">
							<li class="li-icon posiR" id="plusnum" tips='待处理事项' ><a href="javascript:;" class="message-remind"></a>
							
							<span class="message-num radius10" <?php if(empty($countdata['plusnum'])) {?> style="display:none" <?php }?> ><?php echo $countdata['plusnum']; ?></span></li>
							<li class="li-icon" tips='更新缓存'><a target="myIframeName" href="<?=U('/Setting/clean_cache')?>" class="clear-cache thenewurl"></a></li>
							<li class="li-icon" tips='商城首页'><a target='_blank' href="<?=C("WAP_URL"); ?>" class="first-page"></a></li>
							<li class="li-icon" tips='修改密码'><a target="myIframeName" href="<?=U('/System/info')?>" class="change-password"></a></li>
							<li class="li-icon" tips='退出'><a href="<?=U('/Login/logout')?>" class="sign-out border-none"></a></li>
							<!--<li style="background: url(images/setting1.png) no-repeat center center;width: 46px;"></li>-->
						</ul>
					</div>
				</div>
			<div class="sidebar-box">
				<div class="first-nav left">
					<ul class="main-nav">
						<!--<li class="activeOne"><a href="d.html" target="myIframeName"><i class="sidebar-icon sidebar-icon-survey"></i>概况</a></li>-->
						<?php  foreach($menu as $k => $v) { ?>
						<li class="main_<?=$k;?> main-nav-li ">
							<a href="javascript:;" target="myIframeName"><i class="sidebar-icon <?=$v['menu_icon']; ?>"></i><?=$v['menu_name']; ?></a>
							<ul class="second-nav secondary">
								<?php foreach($v['son'] as $sk => $sv) { ?>
								<li class='secondary-li secondary_<?=$sv['name_el'];?>'><a href="<?=$sv['url'];?>" target="myIframeName"><?=$sv['name'];?></a></li>
								<?php }?>
							</ul>
						</li>
					<?php }?>
					<li class="main-nav-li" style="display: block;">
							<a href="javascript:;" target="myIframeName"><i class="sidebar-icon sidebar-icon-Jurisdiction"></i>模板</a>
							<ul class="second-nav secondary">
								<li class='secondary-li'><a href="<?=U('/template/test')?>" target="myIframeName">测试页面</a></li>
								<li class='secondary-li'><a href="<?=U('/template/freightTemplate')?>" target="myIframeName">新建运费模板</a></li>
								<li class='secondary-li'><a href="<?=U('/template/freightTemplate2')?>" target="myIframeName">运费模板</a></li>
								<li class='secondary-li'><a href="<?=U('/template/fightGroupChoice')?>" target="myIframeName">拼团选择商品</a></li>
								<li class='secondary-li'><a href="<?=U('/template/FightGroups')?>" target="myIframeName">拼团</a></li>
								<li class='secondary-li'><a href="<?=U('/template/iconAlert')?>" target="myIframeName">图标库</a></li>
								<li class='secondary-li'><a href="<?=U('/template/coupon')?>" target="myIframeName">优惠券</a></li>
								<li class='secondary-li'><a href="<?=U('/template/address')?>" target="myIframeName">地址设置</a></li>
								<li class='secondary-li'><a href="<?=U('/template/evaluate')?>" target="myIframeName">商品评价</a></li>
								<!--<li class='secondary-li'><a href="<?=U('/template/classify')?>" target="myIframeName">商品分类</a></li>
								<li class='secondary-li'><a href="<?=U('/template/details')?>" target="myIframeName">详情页编辑</a></li>
								<li class='secondary-li'><a href="<?=U('/template/details2')?>" target="myIframeName">详情页显示</a></li>-->
								<li class='secondary-li'><a href="<?=U('/template/nav')?>" target="myIframeName">导航</a></li>
								<li class='secondary-li'><a href="<?=U('/template/survey')?>" target="myIframeName">概况</a></li>
								<li class='secondary-li'><a href="<?=U('/template/statistics')?>" target="myIframeName">统计</a></li>
								<li class='secondary-li'><a href="<?=U('/template/releaseOne')?>" target="myIframeName">商品一</a></li>
								<!--<li class='secondary-li'><a href="<?=U('/template/releaseTwo')?>" target="myIframeName">商品二</a></li>-->
								<li class='secondary-li'><a href="<?=U('/template/releaseThree')?>" target="myIframeName">商品三</a></li>
								<!--<li class='secondary-li'><a href="<?=U('/template/releaseFour')?>" target="myIframeName">商品四</a></li>
								<li class='secondary-li'><a href="<?=U('/template/releaseFive')?>" target="myIframeName">商品五</a></li>-->
								<!--<li class='secondary-li'><a href="<?/*=U('/template/commodityControl')*/?>" target="myIframeName">商品管理</a></li>
								<li class='secondary-li'><a href="<?/*=U('/template/orderDisplay')*/?>" target="myIframeName">订单显示</a></li>
								<li class='secondary-li'><a href="<?/*=U('/template/shippedSetting')*/?>" target="myIframeName">待发货设置</a></li>
								<li class='secondary-li'><a href="<?/*=U('/template/deliverSetting')*/?>" target="myIframeName">发货设置</a></li>-->
								<li class='secondary-li'><a href="<?=U('/template/notice')?>" target="myIframeName">通知弹窗</a></li>
								<li class='secondary-li'><a href="<?=U('/template/login')?>" target="myIframeName">登陆页</a></li>
								<li class='secondary-li'><a href="<?=U('/template/deleCoupon')?>" target="myIframeName">删除优惠券</a></li>
								<!--<li class='secondary-li'><a href="<?=U('/template/addSpec')?>" target="myIframeName">添加规格值</a></li>
								<li class='secondary-li'><a href="<?=U('/template/addFocus')?>" target="myIframeName">添加图片</a></li>
								<li class='secondary-li'><a href="<?=U('/template/firstpage')?>" target="myIframeName">首页</a></li>
								<li class='secondary-li'><a href="<?=U('/template/LogisticsDetails')?>" target="myIframeName">物流详情</a></li>-->
								<li class='secondary-li'><a href="<?=U('/template/logistics2')?>" target="myIframeName">物流详情2</a></li>
								<li class='secondary-li'><a href="<?=U('/template/tonglan')?>" target="myIframeName">通栏</a></li>
								<li class='secondary-li'><a href="<?=U('/template/management')?>" target="myIframeName">首页管理</a></li>
								<li class='secondary-li'><a href="<?=U('/template/data')?>" target="myIframeName">数据调用</a></li>
								
								
							</ul>
						</li>
						<li class="main-nav-li ">
							<a href="javascript:;" target="myIframeName"><i class="sidebar-icon sidebar-icon-Jurisdiction"></i>表格</a>
							<ul class="second-nav secondary">
								<li class='secondary-li'><a href="<?=U('/Presalestable/refunds')?>" target="myIframeName">退款表格</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
			<div class="content-box">
				<div class="content-iframe">
					<iframe src="" id="myIframeId" name="myIframeName" width="100%" frameborder="0" border="0"  scrolling="no" class="iframe-con"></iframe>
				</div>
				<div class="myfoot">
					@2012-2018 Powered By ShopDZ&nbsp;&nbsp;&nbsp; <?php echo($license);?>
				</div>	
			</div>
		</div>
		
		<div class="cover none"></div>
		<div class="alert itemAlert radius3 none">
			<h1 class="items-tit boxsizing">待处理事项</h1>
			<i class="close-icon"></i>
			<ul class="item-list">
			<?php foreach($countdata['list'] as $v){ ?>
				<li>
					<span class="category-tit left"><?=$v['name']?> </span>
					<span class="category-con left"><?=$v['link']?> </span>
				</li>
			<?php } ?>
			</ul>
		</div>
		<script type="text/javascript" src="__PUBLIC__/admin/js/common.js"></script>
	<!-- 	<script type="text/javascript" src="__PUBLIC__/admin/js/remindMove.js"></script> -->
		<script type="text/javascript">
			$(function(){  //class="activeOne"
			
				$('.main-nav li:first').addClass('activeOne');
				$('.main-nav li:first ul li:first').addClass('activeThree');
				$('#myIframeId').attr('src',$('.main-nav li:first ul li:first').find('a').attr('href'));
			    //导航
				$('.main-nav-li').bind('click',function(event){
					$(this).addClass("activeOne").siblings().removeClass("activeOne");//removeClass就是删除当前其他类；只有当前对象有addClass("selected")；siblings()意思就是当前对象的同级元素，removeClass就是删除； 
					$(this).find("ul").show().parents('li').siblings().find("ul").hide();
					$(this).find('ul li').removeClass('activeThree');
					$(this).find('ul li:first').addClass('activeThree');
					$('#myIframeId').attr('src',$(this).find('ul li:first-child').find('a').attr('href'));
					 event.stopPropagation();
				});
				$(".main-nav").bind("click",function(event) {
					event.stopPropagation();
				})
				$(".main-nav .secondary-li").bind("click",function(event){
					$(this).addClass("activeThree").siblings().removeClass('activeThree');
					 event.stopPropagation();
				})
				$('#plusnum').click(function(){
					$('.alert').removeClass('none');
					$('.cover').removeClass('none');
				});
				$('.thenewurl').click(function(){
					$('.alert').addClass('none');
					$('.cover').addClass('none');
					$('#myIframeId').attr('src',$(this).attr('uri'));
				})
			});
	$(function(){
				$('.setting-list>li').each(function() {
					var _this = this;
					$(_this).bind(
						{
							mousemove:function(){
								e=arguments.callee.caller.arguments[0] || window.event; 
								tip = $(_this).attr('tips');
								remindNeed($(_this),e,tip);
							},
							mouseout:function() {
								$('.tip-remind').remove();
							}
						}
					);
				});
			}); 
		</script>
	</body>
</html>