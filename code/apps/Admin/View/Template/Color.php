
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
<title>ColorPicker颜色选择器</title>
<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.bigcolorpicker.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.bigcolorpicker.css" type="text/css" />
<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>

<div id="container" class="content">
	<div class="color-box">
		<input type="text" id="bn4" class="com-inp1"/><input f="bnn" data-target="bn4" type="button" class="color-btn"/>
	</div>
	

</div>
<script type="text/javascript">
    $(function(){
			$("#f1").bigColorpicker("f1","L",10);
			
			$("#bn").bigColorpicker("f3");

			$("#img").bigColorpicker(function(el,color){
				$(el).css("backgroundColor",color);
			});

			$(":text[name='t']").bigColorpicker(function(el,color){
				$(el).val(color);
			});
			
			$(":input[f='bnn']").bigColorpicker(function(el,color){
				$("#" + $(el).attr("data-target"))
				             .val(color);
			});
			
			$("#f333").bigColorpicker("f3","L",6);
		});
</script>
</div>
