<!DOCTYPE html>
<html>


<!-- Mirrored from www.zi-han.net/theme/hplus/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:16:41 GMT -->
<head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <link rel="shortcut icon" href="__PUBLIC__/favicon.ico">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/scrollbar.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/style.min862f.css?v=4.1.0">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/index.css"/>
    <link rel="stylesheet" href="__PUBLIC__/admin/css/jquery-ui.css">
    <link href="__PUBLIC__/admin/css/jquery-ui-timepicker-addon.css" type="text/css" />
    <script type="text/javascript" src="__PUBLIC__/admin/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/admin/js/select.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/jquery.md5.js"></script>
    <script type="text/javascript" src="__PUBLIC__/admin/js/plupload-2.0.0/plupload.full.min.js"></script>
    <script src="__PUBLIC__/admin/js/jquery-ui.js"></script>
    <script src="__PUBLIC__/admin/js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
    <script src="__PUBLIC__/admin/js/jquery.ui.datepicker-zh-CN.js.js" type="text/javascript" charset="gb2312"></script>
    <script src="__PUBLIC__/admin/js/jquery-ui-timepicker-zh-CN.js" type="text/javascript"></script>
    <script type="text/javascript" src="__PUBLIC__/admin/js/posi-img.js"></script>
    <script type="text/javascript">
		var  __BASE__= '<?=$baseUrl?>';
    </script>
</head>
<body class="gray-bg">
    <div class="scrollbox" style="overflow-y: scroll; overflow-x: hidden;">
        <div id='append_parent'></div>
    	{__CONTENT__}
    </div>
		<script type="text/javascript" src="__PUBLIC__/admin/js/iscroll.js"></script>
        <script type="text/javascript" src="__PUBLIC__/admin/js/common.js"></script>
        <script type="text/javascript" src="__PUBLIC__/admin/js/common_branch.js"></script>
        <script type="text/javascript" src="__PUBLIC__/admin/js/remindMove.js"></script>
        <script type="text/javascript" src="__PUBLIC__/admin/js/getdata.js"></script>
        <script type="text/javascript" src="__PUBLIC__/admin/js/iframe.js"></script>
        <script type="text/javascript" src="__PUBLIC__/admin/js/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="__PUBLIC__/admin/js/scrollbar.min.js"></script>
        <script type="text/javascript" src="__PUBLIC__/admin/js/buttons.js"></script>
		<script type="text/javascript">
				var settting = <?= $setting?>;
				//显示左侧标签
				var main_li="{$Think.CONTROLLER_NAME}";
				var second_li="{$Think.ACTION_NAME}";
				$('.main_'+main_li, window.parent.document).siblings().removeClass('activeOne');
				$('.main_'+main_li, window.parent.document).addClass('activeOne');
				$('.main_'+main_li, window.parent.document).find("ul").show().parents('li').siblings().find("ul").hide();
				$('.secondary_'+second_li, window.parent.document).siblings().removeClass('activeThree');
				$('.secondary_'+second_li, window.parent.document).addClass('activeThree');
				module = $('.activeOne',window.parent.document).find('a:eq(0)').text();
				action = $('.activeOne',window.parent.document).find('ul').find('.activeThree').text();
				$('title',window.parent.document).html(settting.shop_name +'-'+module+'-'+action);
		</script>
</body>

</html>
