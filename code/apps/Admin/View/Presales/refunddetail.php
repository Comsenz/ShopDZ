
		<div class="tipsbox">
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
    <!--提示框结束-->
    	<div class="iframeCon">
		<div class="iframeMain">
			<div class="white-shadow2">
				<div class="details-box">
					<h1 class="details-tit">买家退款处理详情</h1>
					<div class="jurisdiction boxsizing">						
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">支付单号：</dt>
							<dd class="left text-l">
								{$list.order_sn}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">退款单号：</dt>
							<dd class="left text-l">
								{$list.refund_sn}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">申请时间：</dt>
							<dd class="left text-l">
								{:date('Y-m-d H:i:s',$list['dateline'])}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">退款原因：</dt>
							<dd class="left text-l">
								{$list.causes_name}
							</dd>
						</dl>

						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">退款原因：</dt>
							<dd class="left text-l">
								{$paymentcode}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">退款金额：</dt>
							<dd class="left text-l">
								{$list.refund_amount}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">退款凭证：</dt>
							<dd class="left text-l">
								<div class="evalute-tableImg">
									<foreach name="list.refund_images" item="v" key="k">
										<i class="evalute-icon view_img" id="refund-img{$k}" url="{$v}"></i>
									</foreach>
								</div>
							</dd>
						</dl>
					</div>
					<h1 class="details-tit">商城退款处理</h1>
					<div class="jurisdiction boxsizing marginT0">
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">处理结果：</dt>
							<dd class="left text-l">
								<switch name="list['status']" >
								<case value="4">拒绝退款</case>
								<case value="1">待处理</case>
								<!-- <case value="2">正在退款</case> -->
								<case value="3">已退款</case>
								</switch>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">处理人：</dt>
							<dd class="left text-l">
								{$list.user_name}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing remarks-dl">
							<dt class="left text-r boxsizing">处理备注：</dt>
							<dd class="left text-l">
								<div class="remarks">{$list.remark}</div>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">处理时间：</dt>
							<dd class="left text-l">
								<notempty name="list['enddate']">{:date('Y-m-d H:i:s',$list['enddate'])}</notempty>
							</dd>
						</dl>
					</div>
					<div class="btnbox3 boxsizing">
						<a type="button" class="btn1 radius3 btn3-btnmargin" href="{:U('/Presales/refunds')}">返回列表</a>
					</div>
				</div>
			</div>
		</div>
	</div>
		<script type="text/javascript">
			$(function(){
				$(document).posi({class:'view_img'});
			})
		</script>
