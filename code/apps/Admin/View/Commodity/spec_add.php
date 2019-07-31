<div class="tipsbox radius3">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		<li>1.网站全局基本设置，商城及其他模块相关内容在其各容在其各自栏目设置项其各自栏目设置项内进行操作。</li>
		<li>2.网站全局基本设置，商城及其他模块相关内容在其各自栏目设置项内进行操作。</li>
		<li>3.网站全局基本设置，商城及其他模块相关内容在其各自栏目设置项内在其各自栏目设置项在其各自栏目设置项进行操作。</li>
	</ol>
</div>

<!--内容开始-->
<div class="iframeCon">
<div class="iframeMain">
    <ul class="transverse-nav">
        <li class="activeFour"><a href="<?php echo U('Commodity/spec_add');?>"><span>添加规格</span></a></li>
    </ul>
	<div class="white-bg">
	    <form  method="post"  action="<?php echo U('Commodity/spec_add');?>"   method="post"  id="main_form"  >  
		<div class="tab-conbox">
			<div class="jurisdiction boxsizing">
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>规格名称：</dt>
					<dd class="left text-l">
						<input type="text"  localrequired="localrequired"  name="spec_name" class="com-inp1 radius3 boxsizing" />
						<p class="remind1">请输入规格名称，不允许重复</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>规格值：</dt>
					<dd class="left text-l">    
						<input type="text" localrequired="localrequired"  name="spec_value" class="com-inp1 radius3 boxsizing" />
						<p class="remind1">请输入规格值，多个规格值用空格隔开 至少要输入一个规格值 </p>
					</dd>
				</dl>
			</div>
			<div class="btnbox3 boxsizing">
				<a type="button" id="submit_button" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
				<a  href="<?php echo U('Commodity/spec');?>"  class="btn1 radius3 marginT10" >返回列表</a>
			</div>
		</div>
		</form>
	</div>
</div>	
</div>	
<script>
$(function(){
    $('#submit_button').click(function(){
        flag=checkrequire('main_form');
        if(!flag){
            $('#main_form').submit();
        }
    });
});
</script>
