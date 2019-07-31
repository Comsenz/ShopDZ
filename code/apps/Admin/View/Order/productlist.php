<style>
.printBox {
	float: right;
	margin-right:5px;
}
.print {
	 border-radius: 2px;
    height: 16px;
    line-height: 16px;
    padding: 3px 7px;
	background: #ccd0d9;
	color: #fff;
    cursor: pointer;
    display: inline-block;
	text-align:center;
	vertical-align: middle;
}
.icon-print {
	background: url(__PUBLIC__/admin/images/ncsc_bg_img.png) no-repeat;
	background-size:100%  100%;
	vertical-align: middle;
	display: inline-block;
	width: 16px;
	height: 16px;
	margin-right:5px;
}
</style>



<div class="tipsbox">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		<li>1.设置订单商品发货。</li>
	</ol>
</div>
<div class="iframeCon">
	<ul class="transverse-nav">
		<li class="{$type=='20'?'activeFour':''}"><a href="{:U('Admin/Order/productlist')}?type=20"><span>待发货</span></a></li>
		<li class="{$type=='30'?'activeFour':''}"><a href="{:U('Admin/Order/productlist')}?type=30"><span>发货中</span></a></li>
		<li class="{$type=='40'?'activeFour':''}"><a href="{:U('Admin/Order/productlist')}?type=40"><span>已完成</span></a></li>
	</ul>
	<div class="white-bg">
		<div class="order-box tab-conbox boxsizing">
			<div class="shipped-tit">
					<form  action="{:U('Order/productlist')}?type={$type}"  method="get">
<!-- 		                <div class="left">
		                <p class="time-box"><input type="text" class="com-inp1 radius3 boxsizing" id="start" name="start" readonly="" value="<?php echo  $_GET['start'];?>" /><i class="timeinp-icon"></i></p>
		                <span class="left time-line">—</span>
		                <p class="time-box"><input type="text" class="com-inp1 radius3 boxsizing" id="end" name="end" readonly="" value="<?php echo  $_GET['end'];?>" /><i class="timeinp-icon"></i></p>
		                </div> -->

						<div class="search-box1 right">
			                <div class="search-boxcon boxsizing radius3 left">
			                    <select class="com-sele radius3 juris-dl-sele" id="field" name="field">
									<option  <?php if($_GET['field'] == 'order_sn'){ echo  'selected="selected"';}?> value="order_sn">订单号</option>
									<!-- <option  <?php if($_GET['field'] == 'trade_no'){ echo  'selected="selected"';}?>  value="trade_no" >交易流水号</option> -->
										
									<option  <?php if($_GET['field'] == 'reciver_name'){ echo  'selected="selected"';}?> value="reciver_name" >收货人</option>
									<option  <?php if($_GET['field'] == 'buyer_name'){ echo  'selected="selected"';}?> value="buyer_name" >买家账号</option>
			                    </select>
			                    <input type="hidden" name="type"  value="<?=$type;?>" />
			                    <input type="text" name="value" value="<?php echo $_GET['value'];?>" class="search-inp-con boxsizing"/>
			                </div>
			                <input type="submit" name="search" value="搜索" class="search-btn right radius3"/>
			            </div>
					</form>
			</div>


			<?php foreach($list as $kk =>$vv ) { ?>
			<div class="order-box-Tit boxsizing">
				<p class="left order-tit-p">订单编号<span class="order-tit-span"><?=$vv['order_sn']?></span></p>
				<p class="left order-tit-p">下单时间<span class="order-tit-span"><?=$vv['add_time']?></span></p>
				
				<p class="left order-tit-p {$type=='20'?'none':''}">发货时间<span class="order-tit-span"><?=date('Y-m-d  H:i:s',$vv['ordercommon']['shipping_time'])?></span> </p>
				<a href="{:U('Order/logistics')}?id=<?=$vv['order_id']?> " class="getpid {$type=='20'?'none':''}">查看物流</a>
				<span class="printBox"><a  target='_blank' href="{:U('Order/order_print',array('order_id'=>$vv['order_id']))}"><i class="icon-print"></i>打印发货单</a></span>
			</div>
			<div class="order-box-con">
				<div class="order-box-L boxsizing">
					<?php $num = count($vv['goodslist']) - 1;
					foreach( $vv['goodslist'] as $gk=>$gv ) {?>
						<div class="order-product-box <?php if($gk == $num){ echo 'borderB-none';} ?>">
							<dl class="order-product-dl left">
								<dt class="product-dl-dt"> <a href="<?php echo C('WAP_URL').'goods_detail.html?id='.$gv['goods_common_id']; ?>" target="_blank"> <img src="<?=$gv['goods_image']?>" class="product-img1 view_img" url="<?=$gv['goods_image']?>" /> </a></dt>
								<dd class="product-dl-dd boxsizing">
									<div class="order-goods-det shipping-Det">
									<a href="<?php echo C('WAP_URL').'goods_detail.html?id='.$gv['goods_common_id']; ?>" target="_blank"> <?=$gv['goods_name']?> </a>
									</div>
									<p class="deliver-spec"><?=$gv['goods_spec']?></p>
								</dd>
							</dl>
							<div class="product-num right"><?=$gv['goods_num']?>件</div>
						</div>
					<?php }?>
				</div>

				<div class="order-box-R boxsizing">
					<div class="send-details boxsizing">
						<div class="send-details-L left">买家</div>
						<div class="send-details-R left"><?=$vv['buyer_name']?></div>
					</div>
					<div class="send-details boxsizing">
						<div class="send-details-L left">收货人</div>
						<div class="send-details-R left send-person-det boxsizing">
							<p><span class="send-name"><?=$vv['ordercommon']['reciver_name']?></span><span class="send-phone"><?=$vv['ordercommon']['reciver_info']['tel_phone']?></span></p>
							<p class="send-address"><?=$vv['ordercommon']['reciver_info']['area_info'].$vv['ordercommon']['reciver_info']['address']?></p>
						</div>
					</div>
					<div class="send-details set-box boxsizing">
						<div class="send-details-L left">运费</div>
						<div class="send-details-R left">
							<p class="left"><?=$vv['shipping_fee']?></p>
							<?php if(in_array($vv['refund_state'],array(1,2,3,5,6)) || $vv['return_state']) {?>
							<p class="setDelivery-gray right"><input type="button" name="" id="" value="退款退货中"/></p>
							<?php }else{ ?>
								<?php if($vv['order_state'] ==30) {?>
								<a href="<?=U('Order/setstorage',array('id'=>$vv['order_id']))?>" ><p class="setDelivery right"><i class="send-car"></i><input type="button" name="" id="" value="编辑发货"/></p></a>
								<?php }elseif($vv['order_state'] ==20) { ?>
								<a href="<?=U('Order/setstorage',array('id'=>$vv['order_id']))?>"><p class="setDelivery right"><i class="send-car"></i><input type="button" name="" id="" value="设置发货"/></p></a>
								<?php } ?>
								</a></p>
							<?php } ?>
						</div>	
					</div>
				</div>
			</div>
			<?php }?>
			<?=$page;?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('.print').click(function() {
			var url = $(this).attr('rel');
			//parent.location.href = url;
		});
		$(document).posi({class:'view_img'});
		$( "#start" ).datepicker({
	        changeMonth: true,
	        changeYear: true,
	        showButtonPanel:true,
	        dateFormat: 'yy-mm-dd',
	        showAnim:"fadeIn",//淡入效果
	    });
	    $( "#end" ).datepicker({
	        changeMonth: true,
	        changeYear: true,
	        showButtonPanel:true,
	        dateFormat: 'yy-mm-dd',
	        showAnim:"fadeIn",//淡入效果
	    });

	})
</script>