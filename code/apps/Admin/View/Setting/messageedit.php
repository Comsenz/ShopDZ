
<!--<div class="tip-remind">收起提示</div>-->
<div class="tipsbox radius3">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">

		<li> 1.设置短信模板内容，开启或关闭的状态。</li>
		<li> 2.短信内容为固定内容，不可编辑。</li>

	</ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
	<ul class="transverse-nav">
		<li class="activeFour"><a href="javascript:;"><span>{$message.name}</span></a></li>
	</ul>
	<div class="white-bg ">
		<div class="tab-conbox">
			<form method="post" class="form-horizontal" name="memberForm" id="memberForm" action="{:U('Setting/messageedit')}" enctype="multipart/form-data">
				<input type="hidden" name="id" value="{$message.id}"/>
				<input type="hidden" value="submit" name="form_submit">
				<div class="jurisdiction boxsizing">
					<dl class="juris-dl boxsizing" id="keywords_text">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>消息内容：</dt>
					<dd class="left text-l">
						<textarea type="text" name="content" id = "wxkeywords" class="com-textarea1 radius3 boxsizing" placeholder="请输入文本内容"   localrequired="" readonly="true">{$message.message_content}</textarea>
						<p class="remind1">消息内容，将显示在前台微信通知中</p>
					</dd>
					</dl>

					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>是否开启：</dt>
						<dd class="left text-l">
							<div class="switch-box">
								<input type="checkbox" name="states" id="switch-radio" class="switch-radio" <if condition="$message['message_states']">checked="true" </if>/>
								<span class="switch-half switch-open">ON</span>
								<span class="switch-half switch-close close-bg">OFF</span>
							</div>

						</dd>
					</dl>
				</div>

				<div class="btnbox3 boxsizing">
					<a id="wxsubmit" class="btn1 radius3 marginT10 btn3-btnmargin left">{$Think.lang.submit_btn}</a>
					<a class="btn1 radius3 marginT10 left" href="{:U('Setting/message')}">返回列表</a>
				</div>

			</form>
		</div>
	</div>
</div>
</div>



<script type="text/javascript">
	$('#wxsubmit').click(function(){
		if(!checkrequire('memberForm')){
			$('#memberForm').submit();
		}else{
			return false;
		}
	});
	$(function(){
		//simulateSelect('menutype',100,showtype);
		$('#menutype').change(showtype);

	});
	var showtype = function (){
		if($("#menutype").val() == '0'){

			$("#wxkeywords").attr("localrequired","");
			$("#refundsearch").removeAttr("localrequired");

			$("#keywords_text").show();
			$("#keywords_img").hide();
		}else{
			$("#refundsearch").attr("localrequired","");
			$("#wxkeywords").removeAttr("localrequired");
			$("#keywords_text").hide();
			$("#keywords_img").show();
		}
	}
</script>






