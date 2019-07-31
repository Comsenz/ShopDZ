
<div class="tipsbox">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		<li>1.对商品分类管理。</li>
		<li>2.可以添加，编辑，删除。</li>
		<li>3.分类下有商品的分类不允许删除。</li>
	</ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
	<div class="white-bg">
		<div class="table-titbox">
			<div class="option">
				<h1 class="table-tit left boxsizing">商品分类</h1>
				<ul class="operation-list left">
					<li class="add-li"><a href="<?php echo U('commodity/category_add');?>"><span><i   class="operation-icon add-icon"></i></span></a></li>
					<li class="refresh-li"><a href="<?php echo U('commodity/category');?>"><span><i  class="operation-icon refresh-icon"></i></span></a></li>
				</ul>
			</div>
		</div>
		<div class="comtable-box boxsizing">
			<table class="com-table">
				<thead>
					<tr>
						<th width="180">操作</th>
						<th class="text-l" width="200">分类名称</th>
						<th width="100">是否显示</th>
						<th width="100">排序值</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				    <?php if(!empty($category_list)){ ?>
				    <?php foreach($category_list as $one){ ?>
					<tr>
						<td>
							<div class="table-iconbox">
								<div class="edit-iconbox left edit-sele marginR10">
									<a class="edit-word table-icon-a" href="<?php echo U('commodity/category_edit', array('gc_id'=>$one['gc_id']));?>"><i class="table-com-icon table-edit-icon"></i><span class="gap">编辑</span></a>
								</div>
								<div class="edit-iconbox left edit-sele marginR10">
									<a class="edit-word table-icon-a" href="javascript:;" onclick="delete_category(<?php echo $one['gc_id']?>);"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
								</div>
							</div>
						</td>
						<td class="text-l"><div class="td-word classify-name"><?php echo $one['gc_name']?><a href="<?php echo U('commodity/category_add',array('gc_id'=>$one['gc_id']));?>"><i class="classify-icon classify-open-icon marginL5"></i></a></div></td>
						<td>
						<?php if($one['is_show']){ ?>
							<div class="state-btn yes-state">
								<i class="yes-icon"></i>
								<span>显示</span>
							</div>
						<?php }else{ ?>
						    <div class="state-btn no-state">
								<i class="no-icon"></i>
								<span>隐藏</span>
							</div>
						<?php } ?>
						</td>
						<td><?php echo $one['listorder'];?></td>
						<td></td>
					</tr>
					<?php if(!empty($one['child'])){ ?>
                        <?php foreach($one['child'] as $key => $two){ ?>
                        <tr>
    						<td>
    							<div class="table-iconbox">
    								<div class="edit-iconbox left edit-sele boxsizing marginR10">
										<a class="edit-word table-icon-a" href="<?php echo U('commodity/category_edit', array('gc_id'=>$two['gc_id']));?>"><i class="table-com-icon table-edit-icon"></i><span class="gap">编辑</span></a>
    								</div>
    								<div class="edit-iconbox left edit-sele boxsizing marginR10">
										<a class="edit-word table-icon-a" href="javascript:;" onclick="delete_category(<?php echo $two['gc_id']?>);"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
    								</div>
    							</div>
    						</td>
    						<td class="text-l"><div class="td-word classify-name">
    						<?php if(empty($one['child'][$key+1])){ ?>
    						    <i class="classify-icon forks-icon marginR5"></i>
    						<?php }else{ ?>
    						    <i class="classify-icon forks-icon forks-iconF marginR5"></i>
    						<?php } ?>
    						<?php echo $two['gc_name']?></div></td>
    						<td>
    						<?php if($two['is_show']){ ?>
    							<div class="state-btn yes-state">
    								<i class="yes-icon"></i>
    								<span>显示</span>
    							</div>
    						<?php }else{ ?>
    						    <div class="state-btn no-state">
    								<i class="no-icon"></i>
    								<span>隐藏</span>
    							</div>
    						<?php } ?>
    						</td>
    						<td><?php echo $two['listorder'];?></td>
    						<td></td>
    					</tr>
                        <?php } ?>
                    <?php } ?>
					<?php } ?>
					<?php }else{ ?>
						<tr class="tr-minH">
                            <td colspan="4">暂无数据！</td>
                            <td></td>
                        </tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>
<script>
function  delete_category(gc_id){
	showConfirm('您确定要删除该分类吗',function(){
		$.ajax({
            url:"<?php echo U('commodity/category_del');?>",
            type:"POST",
            data:{gc_id:gc_id},
            success: function(response) {
                if(response.status==1){
                    showSuccess('删除成功',function(){
                        window.location.reload();
                    });
                }else{
                    showError(response.info);
                }
            }
        });
	});
}
</script>
