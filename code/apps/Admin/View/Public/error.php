		<div class="cover"></div>
		<div style="width:1px;height:300px;"></div>
		<div class="alert showAlert radius3 showFailed">
			
			<i class="alert-icon"></i>
			<h3 class="alert-tit">操作失败</h3>
			<p class="Failed-remind">{$error}</p>
			<p class="timeNum"><i class="time-icon"></i><span id='CountDown'></span>页面自动&nbsp;<span class="jump"><a id="href" href="<?php echo($jumpUrl); ?>">跳转</a></span>&nbsp;等待时间：<span id='wait'><?php echo($waitSecond); ?></span></p>
		</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>