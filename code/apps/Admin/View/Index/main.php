	<meta charset="utf-8">
	<title>订单统计</title>
  	<link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css" />
  	<link rel="stylesheet" href="__PUBLIC__/css/bootstrap-datetimepicker.css" />
  	<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
  	<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
  	<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>

	<div class="content">
		<!--提示框开始-->
		<div class="alertbox1">
			<div class="alert-con radius3">
				<button class="closebtn1">×</button>
            	<div class="alert-con-div">
            		<h1 class="fontface alert-tit1">&nbsp;{$Think.lang.operation_tips}</h1>
            		<ol>
            			<li>提示文字信息提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字</li>
            			<li>提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字信息</li>
            		</ol>
            		<a href="#" class="alert-more">了解更多</a></div>
			</div>
        </div>
        <!--提示框结束-->
        <!--切换内容-->
       
		<div id="sidebar-tab" class="sidebar-tab"> 
			<div id="tab-title" class="tab-title"> 
			</div> 
			<div  class="sidebar-con"> 
				<div class="tablebox1">
                    <div id="order_num" style=""></div>
                    <div id="order_amount" style=""></div>
                    <div id="order_member" style=""></div>
                    <div id="new_member" style=""></div>
				</div>
			</div> 
		</div>
	</div>
	<!--content结束-->
<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/alert.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/bootstrap.min.js"></script>
<script src="__PUBLIC__/js/moment.js" charset="UTF-8"></script>
<script src="__PUBLIC__/js/moment-with-locales.js" charset="UTF-8"></script>
<script src="__PUBLIC__/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
<script type="text/javascript">
	$(document).ready(function() {  
		 $('#start').datetimepicker({
		 	format: 'YYYY-MM-DD',
		 	useCurrent: false,
            locale: 'zh-CN'
            
           
        });
        $('#end').datetimepicker({
        	format: 'YYYY-MM-DD',
        	useCurrent: true,
            locale: 'zh-CN'
            
        });
	}); 
</script>

        
<script>
$(function(){
	 $('#order_num').highcharts(<?php echo $stat_json['order_num'];?>);
	 $('#order_amount').highcharts(<?php echo $stat_json['order_amount'];?>);
     $('#order_member').highcharts(<?php echo $stat_json['order_member'];?>);
     $('#new_member').highcharts(<?php echo $stat_json['new_member'];?>);
});


</script>







