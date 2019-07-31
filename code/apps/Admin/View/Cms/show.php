
		<!--<div class="tip-remind">收起提示</div>-->
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
		<div class="iframeCon">
		<div class="iframeMain">
			<div class="white-bg">
				<div class="white-shadow2">
				<div class="details-box">
					<h1 class="details-tit">提现信息</h1>
					<div class="jurisdiction boxsizing">
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">提现编号：</dt>
							<dd class="left text-l">
								<?=$data['cash_sn']?>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">会员账号：</dt>
							<dd class="left text-l">
								<?=$data['member_mobile']?>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">提现金额：</dt>
							<dd class="left text-l">
								<?=$data['cash_amount']?>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">收款银行：</dt>
							<dd class="left text-l">
								<?=$data['bank']?>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">收款账号：</dt>
							<dd class="left text-l">
									<?=$data['bank_no']?>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">收款人姓名：</dt>
							<dd class="left text-l">
								<?=$data['bank_name']?>
							</dd>
						</dl>
					</div>
					<h1 class="details-tit">商城处理申请</h1>
					<div class="jurisdiction boxsizing marginT0">
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">处理结果：</dt>
							<dd class="left text-l">
									<?=$data['status_text']?>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing details-dl">
							<dt class="left text-r boxsizing">处理人：</dt>
							<dd class="left text-l">
									<?=$data['user_name']?>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing remarks-dl">
							<dt class="left text-r boxsizing">处理备注：</dt>
							<dd class="left text-l">
								<div class="remarks">	<?=$data['remark']?></div>
							</dd>
						</dl>
					</div>
					<div class="btnbox3 boxsizing">
						<a id="form_submitadd" class="btn1 radius3 btn3-btnmargin" href="<?=U('Cms/withdraw',array('status'=>$_GET['status']));?>" >返回列表</a>
					</div>
				</div>
			</div>
			</div>
		</div>
		</div>