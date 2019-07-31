   		<div class="tipsbox radius3">
			<div class="tips boxsizing radius3">
				<div class="tips-titbox">
					<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
					<span class="open-span span-icon"><i class="open-icon"></i></span>
				</div>
			</div>
			<ol class="tips-list" id="tips-list">
				<li>1.银行列表设置，列表里显示的银行将会在用户添加银行信息时显示。</li>
			</ol>
		</div>
		<div class="iframeCon">
		<div class="iframeMain">
				<include file="spreadnav" />
			<div class="white-bg">
				<div class="table-titbox">
					<h1 class="table-tit left boxsizing">奖励明细</h1>
					<ul class="operation-list left">
						<li class="refresh-li"><a href="javascript:;" onclick="javascript:window.location.reload();"><span><i href="#" class="operation-icon refresh-icon"></i></a> </span></li>
						<li class="export-li"><a href="{:U('Admin/Export/presales',$_GET)}"><span><i href="#" class="operation-icon export-icon"></i></span></a></li>
					</ul>
					<div  class="search-box1 right">
					<form action="<?=U('/Cms/presales')?>" method="get" id='formid'>
						<div class="search-boxcon boxsizing radius3 left">
							
							<select class="sele-com1 search-sele boxsizing" name="type" id="search_select">
                        		<option  value='member_truename' <?php if($type =='member_truename'){ ?> selected='selected' <?php }?>  >会员账号</option>
                        		<option   value='member_uid' <?php if($type =='member_uid'){ ?> selected='selected' <?php }?> >会员ID</option>
                        		<option value='order_sn'  <?php if($type =='order_sn'){ ?> selected='selected' <?php }?>  >订单号</option>
                        	</select>
							<input type="text" value="<?=$search_text?>" name="search_text" class="search-inp-con boxsizing"/>
						</div>
						<input type="submit"  value="搜索" class="search-btn right radius3"/>
					</form>
					</div>
				</div>
				
				<div class="comtable-box boxsizing">
					<table class="com-table">
						<thead>
							<tr>
								<th width="120">操作</th>
								<th width="120">会员ID</th>
								<th width="120">会员账号</th>
								<th class='text-l' width="120">奖励</th>
								<th width="120">订单号</th>
								<th width="120">级别</th>
								<th width="120">下单时间</th>
								<th width="120">状态</th>
								<th class='text-l' width="120">退款扣款</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<empty name="list">
	                        <tr class="tr-minH">
	                            <td colspan="9">暂无数据！</td>
	                            <td></td>
	                        </tr>
	                    <else />
						<?php foreach($list as $k => $v) {?>
							<tr>
								<td>
								-
								</td>
								<td><?=$v['member_uid']?></td>
								<td><?=$v['member_truename']?></td>
								<td class='text-l'><?=$v['reward_amount']?></td>
								<td><?=$v['order_sn']?></td>
								<td><?=$v['spread_level_text']?></td>
								<td><?=$v['order_add_time_text']?></td>
								<td><?=$v['spread_state_text']?></td>
								<td class='text-l'><?=$v['refund_amount']?></td>
								<td></td>
							</tr>
						<?php }?>
						</empty>
						</tbody>
					</table>
				</div>
				<div >
					<?=$page;?>
				</div>
			</div>
		</div>
	</div>
		<script type="text/javascript">
			$(function(){
				
				//添加会员提示
	 
				$('.add-li').mouseout(function(){
			     	$('.tip-remind').remove();
			    });
				$('.refresh-li').mousemove(function(){
					e=arguments.callee.caller.arguments[0] || window.event; 
					remindNeed($('.add-li'),e,'刷新');
				})
				$('.refresh-li').mouseout(function(){
			     	$('.tip-remind').remove();
			    });
			    $('.export-li').mousemove(function(){
					e=arguments.callee.caller.arguments[0] || window.event; 
					remindNeed($('.add-li'),e,'导出');
				})
				$('.export-li').mouseout(function(){
			     	$('.tip-remind').remove();
			    });
					
			})
		</script>