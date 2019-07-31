
<div class="tipsbox">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list">
        <li>1.‘删除’按钮可删除礼品</li>
        <li>2.‘编辑’操作可重新修改该礼品</li>
    </ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
    
    <div class="white-bg">
		<div class="table-titbox">
			<h1 class="table-tit left boxsizing">礼品列表</h1>
			<ul class="operation-list left">
				<li class="add-li" onclick="location.href='{:U('Coupon/gift_add')}'"><a href="javascript:;"><span><i href="#" class="operation-icon add-icon"></i></span></a></li>
				<li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
			</ul>
			
			<form method="get" class="form-horizontal" name="Search_form"  action="{:U('Coupon/gift_list')}">
				<div class="search-box1 right">
	            
	                <div class="search-boxcon boxsizing radius3 left">
		            	<select id='refundsearch' class="sele-com1 search-sele boxsizing" name="field">
							<option  <?php if($_GET['field'] == 'gift_name'){ echo  'selected="selected"';}?> value="gift_name">礼品名称</option>
							<option  <?php if($_GET['field'] == 'goods_name'){ echo  'selected="selected"';}?>  value="goods_name" >关联商品</option>
						</select>
						<input type="text" name="value" class="search-inp-con boxsizing"  value="<?php echo $_GET['value'];?>" />
					</div>
					<input value="搜索" class="search-btn right radius3" type="submit">					
                </div>
            </form>
			
        </div>
		<div class="comtable-box boxsizing">
			<table class="com-table">
				<thead>
					<tr>
						<th width="180">操作</th>
						<th width="180">礼品名称</th>
						<th width="180">礼品图片</th>
						<th width="80">礼品价格</th>
						<th width="80">礼品库存</th>
						<th width="130" class="text-l">发布时间</th>
						<th width="150">积分兑换</th>
						<th width="180">兑换积分</th>
						<th width="180">限兑数量</th>
						<th width="130" class="text-l">开始时间</th>
						<th width="130" class="text-l">结束时间</th>
						<th width="180">关联商品</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php if(empty($lists)){  ?>
						<tr class="tr-minH">
                            <td colspan="8">暂无数据！</td>
                            <td></td>
                        </tr>
					<?php }else{ ?>
	                    <?php foreach($lists  as $v){ ?>
    					<tr>
    						<td>
    							<div class="table-iconbox">
    								<div class="edit-iconbox left edit-sele marginR10">
    									<a class="edit-word table-icon-a" href="javascript:void(0);" onclick="del({$v.gift_id})"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
    								</div>
    								<div class="edit-iconbox left edit-sele marginR10">
    									<a class="edit-word table-icon-a" href="<?php echo  U("Coupon/gift_edit",array('gift_id'=>$v['gift_id'])); ?>"><i class="table-com-icon table-edit-icon"></i><span class="gap">编辑</span></a>
    								</div>
    							</div>
    						</td>
    						<td ><?php echo $v['gift_name']?></td>
    						<td class="">
    							<div class="evalute-tableImg">
    							    <i class="evalute-icon view_img" id="evalute-img<?php echo $v['gift_id'];?>" url="<?php echo $v['gift_image']?>"></i>
    							</div>
    						</td>
    						<td ><?php echo $v['gift_price']?></td>
    						<td ><?php echo $v['gift_storage']?></td>
    						<td class="text-l"><?php echo date('Y-m-d',$v['add_time']);?></td>
    						<td >
    						    <?php if($v['credits_exchange']){ ?>
    						    <div class="state-btn yes-state">
                                    <i class="yes-icon"></i>
                                    <span>开启</span>
                                </div>
                                <?php }else{ ?>
                                    <div class="state-btn no-state">
                                    <i class="no-icon"></i>
                                    <span>关闭</span>
                                    </div>
                                <?php } ?>
    						</td>
    						<td ><?php echo $v['gift_points']?></td>
    						<td ><?php echo $v['limit_num']?></td>
    						<td class="text-l"><?php echo $v['credits_exchange'] ? date('Y-m-d',$v['start_time']) : '--';?></td>
    						<td class="text-l"><?php echo $v['credits_exchange'] ? date('Y-m-d',$v['end_time']) : '--';?></td>
    						<td ><?php echo $v['goods_name'] ? '<a href="'.C('wap_url').'goods_detail.html?id='.$v['goods_common_id'].'"  target="_blank" >'.$v['goods_name'].'</a>' : '--';?></td>
    						
    						<td></td>
    					</tr>
    					<?php } ?>
					<?php } ?>
				</tbody>
			</table>
		</div>
		{$page}
	</div> 
</div>
</div>

<script type="text/javascript">
	$(document).ready(function() { 
		$(document).posi({class:'view_img'});
		
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
        
        $('.dele-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.dele-li'),e,'批量删除');
		})
		$('.dele-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
	    $('.dele-li').click(function(){
	    	var ids = new Array();
	    	$('input[name="geval_id"]:checked').each(function(){
	    		if($(this).val()) ids[ids.length] = $(this).val();
	    	});
	    	if(ids.length > 0) del(ids);
	    });
	}); 
	function del(id) {
		showConfirm('将删除此礼品，确认操作吗？',function () {
            $.ajax({
                url:"<?php  echo U('Coupon/gift_delete');?>",
                type:"get",
                data:{"gift_id":id},
                success: function(data) {
                    if(data.status==1){
                    	showSuccess("您已经永久删除了这条信息。",function(){window.location.reload()});
                    }else{
                    	showError(data.info);
                    }
                }
            });
        });
    }
	
</script>







