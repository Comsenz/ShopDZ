		<!--<div class="tip-remind">收起提示</div>-->
		<div class="tipsbox">
			<div class="tips boxsizing radius3">
				<div class="tips-titbox">
					<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
					<span class="open-span span-icon"><i class="open-icon"></i></span>
				</div>
			</div>
			<ol class="tips-list" id="tips-list">
				<li>1.订单详细信息展示。</li>
			</ol>
		</div>
		<div class="iframeCon">
			<div class="white-shadow2">
				<div class="details-box">
					<h1 class="details-tit">下单/支付</h1>
					<table class="order-table">
						<tr>
							<td class="text-r" width="120">订单ID</td>
							<td class="text-l" width="200">{$order_detail.order_id}</td>
							<td class="text-r" width="120">支付方式</td>
							<td class="text-l" width="200">{$order_detail.payment_code}</td>
							<td class="text-r" width="120">下单时间</td>
							<td class="text-l" width="200">{$order_detail.add_time}</td>
							<td></td>
						</tr>
						<tr>
							<td class="text-r" width="120">订单编号</td>
							<td class="text-l" width="200">{$order_detail.order_sn}</td>
							<td class="text-r" width="120">支付单号</td>
							<td class="text-l" width="200">{$order_detail.pay_sn}</td>
							<td class="text-r" width="120">支付时间</td>
							<td class="text-l" width="200">{$order_detail.payment_time}</td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-r" width="120">支付日志</td>
							<td class="text-l" colspan="6">系统收到货款（外部交易号：{$order_detail.trade_no}）</td>
						</tr>
						<!-- <tr>
							<td class="text-r" width="120">订单取消原因</td>
							<td class="text-l" colspan="6">付款的两个季度付款了个jfk领导的经历jfk过来的反馈结果好地方健康更何况</td>
						</tr> -->
					</table>
					<h1 class="details-tit">购买/收货方信息</h1>
					<table class="order-table">
						<tr>
							<td class="text-r" width="120">买家账号</td>
							<td class="text-l" width="200">{$order_detail['buyer_name']}</td>
							<td class="text-r" width="120">买家联系方式</td>
							<td class="text-l" width="200">{$order_detail.buyer_phone}</td>
							<td colspan="3"></td>
						</tr>
						<tr>
							<td class="text-r" width="120">收货人</td>
							<td class="text-l" width="200">{$orderCommon.reciver_name}</td>
							<td class="text-r" width="120">收货人联系方式</td>
							<td class="text-l" width="200">{$orderCommon.reciver_info.tel_phone}</td>
							<td colspan="3"></td>
						</tr>
						<tr>
							<td class="text-r" width="120">收货地址</td>
							<td class="text-l" colspan="6">{$orderCommon.reciver_info.area_info} {$orderCommon.reciver_info.address}</td>
						</tr>
					</table>
					<h1 class="details-tit">发货信息</h1>
					<table class="order-table">
						<tr>
							<td class="text-r" width="120">发货时间</td>
							<td class="text-l" width="200"><?php if($orderCommon['shipping_time']){ echo date('Y-m-d H:i:s',$orderCommon['shipping_time']); } else{
								echo '未发货';
							}?></td>
							<td class="text-r" width="120">快递公司</td>
							<td class="text-l" width="200"><?=$orderCommon['company']['name']?></td>
							<td class="text-r" width="120">物流单号</td>
							<td class="text-l" width="200"><?=$order_detail['shipping_code']?></td>
							<td></td>
						</tr>
					</table>
					<h1 class="details-tit">订单留言</h1>
					<table class="order-table">
						<tr>
							<td colspan=6 class="text-r" width="120"> 留言内容</td>
							<td><?=$orderCommon['order_message'];?></td>
							<td></td>
						</tr>
					</table>
					<h1 class="details-tit">商品信息</h1>
					<table class="order-table2">
						<tr>
							<th></th>
							<th width="562" class="text-l">
								商品信息
							</th>
							<th width="158">单价</th>
							<th width="120">数量</th>
							<!-- <th width="197">优惠活动</th> -->
						</tr>
						<foreach name="ordersGoods" item="vo">
						<tr>
							<td width="50" class="text-c">
								<a href="#">
									<img src="{$vo.goods_image}" alt="" class="order-goodsImg"/>
								</a>
							</td>
							<td class="text-l">
								<div class="order-goods-det">
									{$vo.goods_name}
								</div>
								<p class="table-spec"><span>{$vo.goods_spec}</span></p>
							</td>
							<td>{$vo.goods_price}</td>
							<td>{$vo.goods_num}</td>
							<!-- <td>{$vo.rpacket_detail.rpacket_title}</td> -->
						</tr>
						</foreach>
					</table>
					<div class="orderShow-foot">
						<p class="order-money">订单金额<span class="order-moneyNum orange-color">￥{$order_detail.order_amount}</span></p>
						<p class="order-freight">（ <?php if ($order_detail['rpacket_detail']['rpacket_price']){ ?>订单优惠<span>￥{$order_detail.rpacket_detail.rpacket_price}，</span><?php } ?>运费<span>￥{$order_detail.shipping_fee}</span>）</p><notempty name="name">
					</div>
				</div>
			</div>
		</div>
