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
			<ul class="transverse-nav">
				<li   class="activeFour"  ><a href="javascript:;"><span>提现审核</span></a></li>
			</ul>
			<div class="white-bg">
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
					<form action="<?=U('Cms/edit');?>" method="post" id='edit_form'>
						<input type='hidden' name='form_submit' value ="submit" />
							<input type='hidden' name='id' value ="<?=$data['cash_id']?>" />
						<div class="jurisdiction boxsizing">
							<dl class="juris-dl boxsizing">
								<dt class="left text-r boxsizing"><span class="redstar">*</span>处理意见：</dt>
								<dd class="left text-l">
									<div class="button-holder" localrequired=''>
										<p class="radiobox"><input  type="radio" id="radio-1-2"  name='agree' value=1 class="regular-radio"/><label for="radio-1-2"></label><span class="radio-word">同意</span></p>
										<p class="radiobox"><input  type="radio" id="radio-1-3"  name='agree' value=2 class="regular-radio"/><label for="radio-1-3"></label><span class="radio-word">拒绝</span></p>
									</div>
									<!--<p class="remind1">免运费额度设置</p>-->
								</dd>
							</dl>
							<dl class="juris-dl boxsizing">
								<dt class="left text-r boxsizing">处理说明：</dt>
								<dd class="left text-l">
									<textarea name ='remark' type="text" class="com-textarea1 radius3 boxsizing" placeholder=""></textarea>
									<p class="remind1">同意或拒绝退款，请在上面输入处理说明</p>
								</dd>
							</dl>
						</div>
						<div class="btnbox3 boxsizing">
							<a id="edit_form_submit" class="btn1 radius3 btn3-btnmargin" href="javascript:;" >{$Think.lang.submit_btn}</a>
							<a class="btn1 radius3" href="<?=U('Cms/withdraw',array('status'=>$_GET['status']));?>">返回列表</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">
$('#edit_form_submit').click(function() {
	flag = checkrequire('edit_form');
		if(!flag){
		$('#edit_form').submit();
		}
});
</script>
