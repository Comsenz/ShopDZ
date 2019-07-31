<!--<div class="tip-remind">收起提示</div>-->
<div class="tipsbox">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		<li>1.点击查看操作将显示订单（包括订单物品）的详细信息。</li>
		<li>2.点击取消操作可以取消订单（在线支付但未付款的订单）。</li>
		<li>3.如果平台已确认收到买家的付款，但系统支付状态并未变更，可以点击设置收货操作(仅限于下单后7日内可更改收款状态)，并填写相关信息后更改订单支付状态。</li>
	</ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
    <ul class="transverse-nav">
        <li class="{$type==''?'activeFour':''}"><a href="{:U('Admin/Order/lists')}"><span>全部</span></a></li>
		<li class="{$type=='10'?'activeFour':''}"><a href="{:U('Admin/Order/lists')}?type=10"><span>待付款</span></a></li>
		<li class="{$type=='20'?'activeFour':''}"><a href="{:U('Admin/Order/lists')}?type=20"><span>待发货</span></a></li>
		<li class="{$type=='30'?'activeFour':''}"><a href="{:U('Admin/Order/lists')}?type=30"><span>已发货</span></a></li>
		<li class="{$type=='40'?'activeFour':''}"><a href="{:U('Admin/Order/lists')}?type=40"><span>已完成</span></a></li>
		<li class="{$type=='0'?'activeFour':''}"><a href="{:U('Admin/Order/lists')}?type=0"><span>已取消</span></a></li>
    </ul>
    <div class="white-bg">
        <div class="table-titbox">
            <div class="option">
                <h1 class="table-tit left boxsizing">订单列表</h1>
                <ul class="operation-list left">
                    <li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
					<li class="export-li need-remind"><a href="{:U('Admin/Export/orderlists',$_GET)}"><span><i class="operation-icon export-icon" href="#"></i></span></a></li>
                </ul>
            </div>            
            <form  action="{:U('Order/lists')}"  method="get">
                <input type="hidden" name="type" value="{$type}">            	
                <div class="search-box1 right">
                    <div class="search-boxcon boxsizing radius3 left">
                        <select  name="field" id="field"  class="sele-com1 search-sele boxsizing">
    						<option  <?php if($_GET['field'] == 'order_sn'){ echo  'selected="selected"';}?> value="order_sn">订单号</option>
    						<option  <?php if($_GET['field'] == 'trade_no'){ echo  'selected="selected"';}?>  value="trade_no" >交易流水号</option>
    							
    						<option  <?php if($_GET['field'] == 'reciver_name'){ echo  'selected="selected"';}?> value="reciver_name" >收货人</option>
    						<option  <?php if($_GET['field'] == 'buyer_name'){ echo  'selected="selected"';}?> value="buyer_name" >买家账号</option>
    					</select>
                        <input type="text" name="value" value="<?php echo $_GET['value'];?>" class="search-inp-con boxsizing"/>
                    </div>
                    <input type="submit" name="search" value="搜索" class="search-btn right radius3"/>
                </div>
            </form>            
        </div>
        
        <div class="comtable-box boxsizing">
            <table class="com-table">
                <thead>
                    <tr>
                        <th width="200">操作</th>
                        <th width="150">订单号</th>
						<th width="110">买家账号</th>
						<th width="110">收货人</th>
						<th width="180">下单时间</th>
						<th width="90">状态</th>
						<th width="120">订单金额</th>
						<?php if ($type != '0'){ ?>
						<th width="160">支付方式</th>
						<th width="200">交易流水号</th>
						<th width="180">支付时间</th>
						<th width="120">退款金额</th>
						<?php }?>
                        <th width="10"></th>
                    </tr>
                </thead>
                <tbody>
                	<empty name="lists">
						<tr class="tr-minH">
                            <?php if ($type != '0'){ ?>
                            <td colspan="11">暂无数据！</td>
                            <?php }else{ ?>
                            <td colspan="7">暂无数据！</td>
                            <?php } ?>
                            <td></td>
                        </tr>
					<else />
                    <foreach name="lists" item="vo">
                    <tr>
                        <td>
                            <div class="table-iconbox">
                                <div class="edit-iconbox left edit-sele ">
                                    <a class="edit-word table-icon-a" href="{:U('Admin/Order/detail')}?id={$vo.order_id}"><i class="table-com-icon table-look-icon"></i><span class="gap">查看</span></a>
                                </div>
                           
                                <div class="table-icon left setting-sele-par">
                                    <div class="setting-sele left radius3 boxsizing">
                                        <span class="setting-word"><i class="table-com-icon table-setting-icon"></i><span class="gap">设置</span></span>
                                        <span class="jtb-span-box boxsizing"><i class="jtb-span setting-jtb-icon"></i></span>
                                    </div>
                                    <ul class="setting-sele-con remind-layer">      <li><a target='_blank' href="{:U('Order/order_print',array('order_id'=>$vo['order_id']))}">打印订单</a></li>                               
                                        <if condition="$vo.order_state eq 10 ">
											<li onclick="change_order({$vo.order_id})">取消订单</li>
										
    										<?php if($vo['order_state'] == 10 && $vo['payment_starttime'] != 0){ ?>
    										<li><a href="{:U('Order/settrade')}?id={$vo.order_id}">设置付款</a></li>
    										<?php } ?>

                                        </if>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td>{$vo.order_sn}</td>
						<td>{$vo.buyer_name}</td>
						<td>{$vo.reciver_name}</td>
						<td>{$vo.add_time|date="Y-m-d H:i:s",###}</td>
						<td>{$vo.order_state_text}</td>
						<td>{$vo.order_amount}</td>
						<?php if ($type != '0'){ ?>
						<td>{$vo.payment_code}</td>
						<td>{$vo.trade_no}</td>
						<td><?php if($vo['payment_time'] != 0) echo date('Y-m-d H:i:s',$vo['payment_time']); ?></td>
						<td><?php if($vo['refund_amount'] != 0) echo $vo['refund_amount']; ?></td>
						<?php }?>
                        <td></td>
                    </tr>
                    </foreach>
                    </empty>
                </tbody>
            </table>
        </div>
        {$page}
    </div>
</div>
</div>
<script type="text/javascript">
    $(function(){
        $('.refresh-li').mousemove(function(){
            e=arguments.callee.caller.arguments[0] || window.event; 
            remindNeed($('.add-li'),e,'刷新');
        })
        $('.refresh-li').mouseout(function(){
            $('.tip-remind').remove();
        });

            
    })
    //删除订单
	// function order_del(order_id) {
 //    	showConfirm("将删除此订单，确认操作吗？",function(){
	// 		$.get('<?php echo U('Order/delorder')?>',{id:order_id},function(data){
	// 	        if(data.status == 1){
	// 	           showSuccess(data.info,function(){
	//     			    window.location.reload();
 //                  });
	// 		    }else{
	// 		    	showError(data.info);
	// 			}
	// 		},'json')
 //    	});
 //    }

    //取消订单
    function change_order(order_id) {
    	showConfirm("将取消此订单，确认操作吗？",function(){
    		
			$.get('<?php echo U('Order/changeorder')?>',{id:order_id},function(data){
		        if(data.status == 1){
		           showSuccess(data.info,function(){
	    			    window.location.reload();
                  });
			    }else{
			    	showError(data.info);
				}
			},'json')

    	});
    }  
</script>