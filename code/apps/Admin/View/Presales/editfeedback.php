<div class="content">
		<div class="tipsbox">
		    <div class="tips boxsizing radius3">
		        <div class="tips-titbox">
		            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
		            <span class="open-span span-icon"><i class="open-icon"></i></span>
		        </div>
		    </div>
			<ol class="tips-list" id="tips-list">
				<li>1.处理用户的意见反馈。</li>
			</ol>
		</div>
		<div class="iframeCon">
		<div class="iframeMain">
			<div class="white-shadow2">
				<div class="details-box">
					<h1 class="details-tit">意见反馈处理</h1>
					<div class="jurisdiction boxsizing">
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">用户名：</dt>
							<dd class="left text-l">
								<notempty name="list['member_name']">{$list.member_name}<else /> 匿名用户</notempty>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">手机号：</dt>
							<dd class="left text-l">
								{$list.member_phone}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">申请时间：</dt>
							<dd class="left text-l">
								{:date('Y-m-d H:i:s',$list['ftime'])}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">反馈内容：</dt>
							<dd class="left text-l">
								{$list.content}
							</dd>
						</dl>
					</div>
					<h1 class="details-tit">商城处理</h1>
					<form method="post" name="settingForm" action="{:U('Presales/editfeedback')}" enctype="multipart/form-data" id="feedback_form" >
     					<input type="hidden" name="id" value="{$list.id}" />
						<div class="jurisdiction boxsizing">
							<input type="hidden" id="radio-a" name="status" value="1"/>
							<dl class="juris-dl boxsizing">
								<dt class="left text-r boxsizing"><span class="redstar">*</span>处理说明：</dt>
								<dd class="left text-l">
									<textarea type="text" name="instruction" class="com-textarea1 radius3 boxsizing" placeholder="" localrequired=""></textarea>
									<p class="remind1">请在上面输入处理说明</p>
								</dd>
							</dl>
						</div>
						<div class="btnbox3 boxsizing">
							<a type="button" class="btn1 radius3 marginT10  btn3-btnmargin form_data">{$Think.lang.submit_btn}</a>
							<a type="button" class="btn1 radius3 marginT10" href="{:U('/Presales/feedbacks')}">返回列表</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
		<!--<script type="text/javascript" src="__PUBLIC__/admin/js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="__PUBLIC__/admin/js/common.js"></script>-->
		<script type="text/javascript">
			$(function(){
				
			})
		</script>
