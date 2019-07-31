		<div class="tipsbox">
		    <div class="tips boxsizing radius3">
		        <div class="tips-titbox">
		            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
		            <span class="open-span span-icon"><i class="open-icon"></i></span>
		        </div>
		    </div>
			<ol class="tips-list" id="tips-list">
				<li>1.添加退货退款原因。</li>
			</ol>
		</div>
		<div class="iframeCon">
		<div class="iframeMain">
			<ul class="transverse-nav">
				<li class="activeFour"><a  href="#"><span>{$causes}</span></a></li>
			</ul>
			<div class="white-bg">
				<div class="tab-conbox">
					<form action="{:U('Presales/addcauses')}" method="post" id="bind_form" role="bind_form" enctype="multipart/form-data">
						<input type="hidden" value="{$causes_id}" name="causes_id">
						<div class="jurisdiction boxsizing">
							<dl class="juris-dl boxsizing">
								<dt class="left text-r boxsizing"><span class="redstar">*</span>原因名：</dt>
								<dd class="left text-l">
									<input type="text" class="com-inp1 radius3 boxsizing" name="causes_name" value="{$causes_name}" localrequired="" />
									<p class="remind1">请输入原因名称!</p>
									
								</dd>
							</dl>
							<dl class="juris-dl boxsizing">
								<dt class="left text-r boxsizing"><span class="redstar">*</span>是否启用：</dt>
								<dd class="left text-l">
                                    <div class="switch-box">
                                        <input type="checkbox" name="status" id="switch-radio" class="switch-radio" <if condition="$status eq '1'">checked="true" </if>/>
                                        <span class="switch-half switch-open">启用</span>
                                        <span class="switch-half switch-close close-bg">停用</span>
                                    </div>
                                    <p class="remind1">停用则前台退款退货是不会显示该原因</p>
                                </dd>
							</dl>
						</div>
						<div class="btnbox3 boxsizing">
							<a type="button" class="btn1 radius3 marginT10  btn3-btnmargin form_data">{$Think.lang.submit_btn}</a>
							<a type="button" class="btn1 radius3 marginT10" href="{:U('Presales/causes')}">返回列表</a>
						</div>
					</from>
				</div>
			</div>
		</div>
		</div>
		<script type="text/javascript">
			$(function(){
				
			})
		</script>
