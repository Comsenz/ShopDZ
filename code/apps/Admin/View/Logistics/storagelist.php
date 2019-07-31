<!--<div class="tip-remind">收起提示</div>-->
<div class="tipsbox">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		<li>1.设置商品发货时的地址。</li>
	</ol>
</div>
<div class="iframeCon">
    <ul class="transverse-nav">
        <li class="activeFour"><a href="javascript:;"><span>发货仓库</span></a></li>
    </ul>
    <div class="white-bg">
        <div class="table-titbox">
            <div class="option">
                <h1 class="table-tit left boxsizing">发货仓库列表</h1>
                <ul class="operation-list left">
                	<li class="add-li"><a href="<?=U('/Logistics/setstorage')?>"><span><i class="operation-icon add-icon"></i></span></a></li>
                    <li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
                </ul>
            </div>            
            <form action="<?=U('/Logistics/storageList')?>" method="get" id='formid'>
            	
            	<div class="search-box1 right">
                    <div class="search-boxcon boxsizing radius3 left">
                        <select id="field" name="type" class="sele-com1 search-sele boxsizing">
	                		<option <?php if($_GET['type'] == 'name') { ?> selected <?php }?> value="name">仓库名称</option>
	                		<option <?php if($_GET['type'] == 'person') { ?> selected <?php }?>  value="person">负责人</option>
	                		<option  <?php if($_GET['type'] == 'telphone') { ?> selected <?php }?> value="telphone">电话</option>
	                	</select>
                        <input type="text" name="search_text" value = "<?=$_GET['search_text']; ?>" class="search-inp-con boxsizing"/>
                	
                    </div>
                    <input type="button" name="search" value="搜索" class="search-btn right radius3" onclick="javascript:document.getElementById('formid').submit();"/>
                </div>
            </form>            
        </div>
        
        <div class="comtable-box boxsizing">
            <table class="com-table">
                <thead>
                    <tr>
                        <th width="180">操作</th>
                        <th width="120">仓库名称</th>
						<th width="120">负责人</th>
						<th width="120">电话</th>
						<th width="350">地址</th>
						<th width="120">状态</th>
						<th width="350">备注</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                	<empty name="lists">
                        <tr class="tr-minH">
                            <td colspan="7">暂无数据！</td>
                            <td></td>
                        </tr>
                    <else />
                    <?php foreach( $lists as $k => $v)  {?>
                    <tr>
                        <td>
                            <div class="table-iconbox">                            
                                <div class="edit-iconbox left edit-sele boxsizing marginR10">
                                    <a class="edit-word table-icon-a" href="<?=U('/Logistics/setstorage',array('id'=>$v['id']))?>" ><i class="table-com-icon table-edit-icon"></i><span class="gap">编辑</span></a>
                                </div>
                                <div class="edit-iconbox left edit-sele boxsizing marginR10">
                                    <a class="edit-word table-icon-a" href="javascript:void(0)" rel-href="<?=U('/Logistics/del',array('id'=>$v['id']))?>"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
                                </div>
                            </div>
                        </td>
                        <td><?=$v['name']?></td>
						<td><?=$v['person']?></td>
						<td><?=$v['telphone']?></td>
						<td><?=$area[$v['add_province']].' '.$area[$v['add_city']].' '.$area[$v['add_dist']].' '.$v['add_community']?></td>
						<td>
						<?php if($v['status'] == 1){ ?>
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
						<td><?=$v['ext_info']?></td>
                        <td></td>
                    </tr>
                    <?php }?>
                    </empty>
                </tbody>
            </table>
        </div>
        {$page}
    </div>
</div>
<div id="append_parent"></div>
<script type="text/javascript">
    $(function(){
    	$('.add-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.add-li'),e,'添加');
		})
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

      
            
    })

    $('.btn-del').click(function() {
		url = $(this).attr('rel-href');
		showConfirm('确认要删除么？',function() {
			 $.ajax({
				type:"get",
				dataType:"json",
	            url:url,
				 success:function(data){
					    if(data.status != 1) {
							showError(data.info);
						}else {
							showSuccess(data.info,function(){
								window.location.reload();	
							});
						}
	            }
			 })
		});
	});
</script>