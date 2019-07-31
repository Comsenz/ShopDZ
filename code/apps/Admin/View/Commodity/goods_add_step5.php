
		<ul class="release-tab">
			<li class="radius5"><a href="<?php echo  U('Commodity/goods_add_step1',array('goods_common_id'=>$goods_common['goods_common_id']));?>" >1.&nbsp;基本信息</a></li>
			<li class="radius5"><a href="<?php echo  U('Commodity/goods_add_step2',array('goods_common_id'=>$goods_common['goods_common_id']));?>" >2.&nbsp;规格设置</a></li>
			<li class="radius5"><a  href="<?php echo  U('Commodity/goods_add_step3',array('goods_common_id'=>$goods_common['goods_common_id']));?>" >3.&nbsp;运费设置</a></li>
			<li class="radius5"><a  href="<?php echo  U('Commodity/goods_add_step4',array('goods_common_id'=>$goods_common['goods_common_id']));?>" >4.&nbsp;参数设置</a></li>
			<li class="activeRelease radius5">5.&nbsp;发布成功</li>
		</ul>
		<!--内容开始-->
		<div class="iframeCon">
			<ul class="transverse-nav">
				<li class="activeFour"><a href="javascript:;"><span>发布成功</span></a></li>
			</ul>
			<div class="white-bg">
				
				<div class="release-success">
					<div class="success-left left">
						<h1 class="re-success-tit"><i class="success-icon"></i><span class="success-tit-word">发布成功!</span></h1>
						<p class="success-href-box">
							<a target="_blank" href="/wap/goods_detail.html?id=<?php echo $goods_common['goods_common_id'];?>" class="success-href">去店铺查看详情></a>
							<a  href="<?php echo  U('Commodity/goods_add_step1',array('goods_common_id'=>$goods_common['goods_common_id'],'edit'=>1));?>" class="success-href">重新编辑刚发布的商品></a>
						</p>
						<div class="success-btn-box">
							<input type="button"  onclick="direct(this)"  rel-href="<?php echo  U('Commodity/goods_add_step1');?>"   class="success-btn radius3 marR20 btn1" value="继续发布新商品"/>
							<input type="button"  onclick="direct(this)"   rel-href="<?php echo  U('Commodity/goods_list');?>"  class="success-btn radius3 btn1" value="查看上架商品"/>
						</div>
					</div>
					<div class="success-right right">
						<div class="commidity-code-box">
							<img src="<?=$goods_common['qrcode'];?>" alt=""  class="commidity-code"/>
							<p class="commidity-word">扫码查看商品详情</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--内容结束-->
		<script type='text/javascript'>
			function direct(my) {
				var url = $(my).attr('rel-href');
				window.location.href = url;
			}
		</script>
		

