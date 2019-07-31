
		<div class="tipsbox">
		    <div class="tips boxsizing radius3">
		        <div class="tips-titbox">
		            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
		            <span class="open-span span-icon"><i class="open-icon"></i></span>
		        </div>
		    </div>
			<ol class="tips-list" id="tips-list">
				<li>1.商家处理意见反馈的详情的展示。</li>
			</ol>
		</div>
    	<!--提示框结束-->
    	<div class="iframeCon">
		<div class="iframeMain">
			<div class="white-shadow2">
				<div class="details-box">
					<h1 class="details-tit">意见反馈详情</h1>
					<div class="jurisdiction boxsizing">						
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">用户名：</dt>
							<dd class="left text-l">
								<notempty name="list['member_truename']">{$list.member_truename}<else /> 匿名用户</notempty>
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
					<div class="jurisdiction boxsizing marginT0">
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">处理结果：</dt>
							<dd class="left text-l">
								<switch name="list['status']" >
								<case value="0">待处理</case>
								<case value="1">已处理</case>
								</switch>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">处理人：</dt>
							<dd class="left text-l">
								{$list.username}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing remarks-dl">
							<dt class="left text-r boxsizing">处理备注：</dt>
							<dd class="left text-l">
								<div class="remarks">{$list.instruction}</div>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">处理时间：</dt>
							<dd class="left text-l">
								<notempty name="list['ctime']">{:date('Y-m-d H:i:s',$list['ctime'])}</notempty>
							</dd>
						</dl>
					</div>
					<div class="btnbox3 boxsizing">
						<a type="button" class="btn1 radius3 btn3-btnmargin" href="{:U('/Presales/feedbacks')}">返回列表</a>
					</div>
				</div>
			</div>
		</div>
	</div>