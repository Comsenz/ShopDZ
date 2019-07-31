
	<!--content开始-->
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
					<h1 class="details-tit">买家评论详情</h1>
					<div class="jurisdiction boxsizing">						
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">评价帐号：</dt>
							<dd class="left text-l">
								{$lists['geval_frommembername']}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">评价内容：</dt>
							<dd class="left text-l">
								{$lists.geval_content}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">评价商品：</dt>
							<dd class="left text-l">
								{$lists.geval_goodsname}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">商品图片：</dt>
							<dd class="left text-l">
								<div class="evalute-tableImg">
									<i class="evalute-icon view_img" url="{$lists.geval_goodsimage}"></i>
								</div>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">评价时间：</dt>
							<dd class="left text-l">
								{$lists.geval_addtime|date="Y-m-d H:i:s",###}
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">评价图片：</dt>
							<dd class="left text-l">
								<div class="evalute-tableImg">
									<foreach name="lists.geval_image" item="v" key="k">
										<i class="evalute-icon view_img" url="{$v}"></i>
									</foreach>
								</div>
							</dd>
						</dl>
					</div>
					<div class="btnbox3 boxsizing">
						<a type="button" class="btn1 radius3 btn3-btnmargin" href="{:U('/Presales/evaluate')}">返回列表</a>
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