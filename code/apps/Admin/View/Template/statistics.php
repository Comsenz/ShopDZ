
		<!--<div class="tip-remind">收起提示</div>-->
		<div class="tipsbox">
			<div class="tips boxsizing radius3">
				<div class="tips-titbox">
					<h1 class="tip-tit"><i class="tips-icon-lamp"></i>操作提示</h1>
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
			<ul class="transverse-nav">
				<li class="activeFour"><a href="javascript:;"><span>下单金额</span></a></li>
				<li><a href="javascript:;"><span>下单数量</span></a></li>
			</ul>
			<div class="white-bg">
				<div class="tab-conbox">
					<ul class="stati-tab radius5">
						<li class="stati-active-bg"><a href="#">今天</a></li>
						<li><a href="#">最近7天</a></li>
						<li><a href="#">最近30天</a></li>
					</ul>
					<div class="staticstic-con">
						<h1 class="staticstic-tit">总下单金额&nbsp;&nbsp;998.00元</h1>
					</div>
				</div>
				<div class="tab-conbox none">
					<ul class="stati-tab radius5">
						<li class="stati-active-bg"><a href="#">今天</a></li>
						<li><a href="#">最近7天</a></li>
						<li><a href="#">最近30天</a></li>
					</ul>
					<div class="staticstic-con">
						<h1 class="staticstic-tit">总下单金额&nbsp;&nbsp;998.00元</h1>
					</div>
					
				</div>
			</div>
		</div>
		<script type="text/javascript">
			$(function(){
				$('.stati-tab li').bind('click',function(){
					$(this).addClass('stati-active-bg').siblings().removeClass('stati-active-bg');
				})
			})
		</script>