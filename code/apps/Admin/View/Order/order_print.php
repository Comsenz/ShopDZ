{__NOLAYOUT__}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="__PUBLIC__/admin/css/base-print.css" rel="stylesheet" type="text/css"/>
<link href="__PUBLIC__/admin/css/print.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="__PUBLIC__/admin/js/jquery-1.7.1.min.js" charset="utf-8"></script>

<script type="text/javascript" src="__PUBLIC__/admin/js/jquery.poshytip.min.js" charset="utf-8"></script>
<script type="text/javascript" src="__PUBLIC__/admin/js/jquery.printarea.js" charset="utf-8"></script>
<title>打印--<?php echo $setting['shop_name'];?></title>
</head>

<body>
<div class="print-layout">
  <div class="print-btn" id="printbtn" title="选择喷墨或激光打印机<br/>根据下列纸张描述进行<br/>设置并打印发货单据"><i></i><a href="javascript:void(0);">打印</a></div>
  <div class="a5-size"></div>
  <dl class="a5-tip">
    <dt>
      <h1>A5</h1>
      <em>Size: 210mm x 148mm</em></dt>
    <dd>当打印设置选择A5纸张、横向打印、无边距时每张A5打印纸可输出1页订单。</dd>
  </dl>
  <div class="a4-size"></div>
  <dl class="a4-tip">
    <dt>
      <h1>A4</h1>
      <em>Size: 210mm x 297mm</em></dt>
    <dd>当打印设置选择A4纸张、竖向打印、无边距时每张A4打印纸可输出2页订单。</dd>
  </dl>
  <div class="print-page">
    <div id="printarea">
            <div class="orderprint">
        <div class="top">
		 <div class='logo-title'>发货单</div>
		  </div>
        <table class="buyer-info">
          <tr>
            <td class="w200">收货人：<?php echo $orderCommon['reciver_name'];?></td>
            <td>电话：<?php echo $orderCommon['reciver_info']['tel_phone'];?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="3">地址：<?php echo $orderCommon['reciver_info']['area_info'].$orderCommon['reciver_info']['address'];?></td>
          </tr>
          <tr>
            <td>订单号：<?php echo $order_detail['order_sn']; ?></td>
            <td>下单时间：<?php echo $order_detail['add_time']; ?></td>
            <td></td>
          </tr>
        </table>
        <table class="order-info">
          <thead>
            <tr>
              <th class="w40">序号</th>
              <th class="tl">商品名称</th>
              <th class="w170 tl">商品规格</th>
              <th class="w70 tl">单价(元)</th>
              <th class="w50">数量</th>
              <th class="w70 tl">小计(元)</th>
            </tr>
          </thead>
          <tbody>
		  <?php $num = 0;
		  foreach($ordersGoods as $k=>$v) {?>
             <tr>
              <td><?php echo $k+1; ?></td>
              <td class="tl"><?php echo $v['goods_name']; ?></td>
              <td class="tl"><?php echo $v['goods_spec']; ?></td>
              <td class="tl">&yen;<?php echo $v['goods_price']; ?></td>
              <td><?php $num +=$v['goods_num']; echo $v['goods_num']; ?></td>
              <td class="tl">&yen;<?php echo ($v['goods_price']*100 * $v['goods_num'])/100; ?></td>
            </tr>
			<?php }?>
            <tr>
              <th></th>
              <th colspan="3" class="tl">合计</th>
              <th><?php echo $num; ?></th>
              <th class="tl">&yen;<?php echo $order_detail['goods_amount']; ?></th>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="10"><span>总计：&yen;<?php echo $order_detail['goods_amount']; ?></span><span>运费：&yen;<?php echo $order_detail['shipping_fee']; ?></span><span>优惠：&yen;<?php echo $order_detail['goods_amount']+$order_detail['shipping_fee']-$order_detail['order_amount']; ?></span><span>订单总额：&yen;<?php echo $order_detail['order_amount']; ?></span><span>店铺：<?php echo $setting['shop_name'];?></span>
                </th>
            </tr>
          </tfoot>
        </table>
                <div class="explain">
        	     </div>
                <div style = 'display:none' class="tc page">第1页/共1页</div>
      </div>
          </div>
      </div>
</div>
</body>
<script>
$(function(){
	$("#printbtn").click(function(){
	$("#printarea").printArea();
	});
});

//打印提示
$('#printbtn').poshytip({
	className: 'tip-yellowsimple',
	showTimeout: 1,
	alignTo: 'target',
	alignX: 'center',
	alignY: 'bottom',
	offsetY: 5,
	allowTipHover: false
});
</script>
</html>