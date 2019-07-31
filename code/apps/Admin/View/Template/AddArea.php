
	<meta charset="utf-8">
	<title>新增地区</title>
  	<link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css" />
  	<link rel="stylesheet" href="__PUBLIC__/css/bootstrap-datetimepicker.css" />
  	<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
  	<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
  	<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>



<!--content开始-->
<div class="content">
	<div class="jurisdiction">
		<dl class="juris-dl boxsizing">
			<dt class="left text-r boxsizing"><span class="redstar">*</span>地区名：</dt>
			<dd class="left text-l">
				<input type="text" class="com-inp1 radius3 boxsizing"/>
				<p class="remind1">这是提示文字</p>
			</dd>
		</dl>
		<dl class="juris-dl boxsizing">
			<dt class="left text-r boxsizing"><span class="redstar">*</span>上级地区：</dt>
			<dd class="left text-l">
				<!--区域选择，默认下级隐藏，选择上级之后显示下级-->
				<select class="com-sele area-sele boxsizing radius3">
					<option>请选择</option>
					<option>北京</option>
					<option>天津</option>
				</select>
				<select class="com-sele area-sele boxsizing radius3 sele-none">
					<option>北京市</option>
					<option>上海市</option>
					<option>天津市</option>
				</select>
				<select class="com-sele area-sele boxsizing radius3 sele-none">
					<option>新疆维吾尔自治区</option>
					<option>上海市</option>
					<option>天津市</option>
				</select>
				<p class="remind1">这是提示文字</p>
			</dd>
		</dl>
	</div>
	<div class="btnbox3">
		<a class="btn1 radius3 btn-margin">确认提交</a>
	</div>
</div>
<!--content结束-->

<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/alert.js"></script>

