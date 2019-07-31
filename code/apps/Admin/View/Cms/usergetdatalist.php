<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">
        <li>1.如果修改模板信息，请更新缓存以便于及时获取最新信息</li>
    </ol>
</div>


<div class="iframeCon">
<div class="iframeMain">
	<div class="white-bg">
		<div class="table-titbox">
			<h1 class="table-tit left boxsizing">数据列表</h1>
			<ul class="operation-list left">
				<li class="add-li"><a href="{:U('Cms/UserGetDate')}"><span><i href="#" class="operation-icon add-icon"></i></span></a></li>
				<li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
				<li class="clear-li" onclick="updateCash(0)"><a href="#"><span><i class="operation-icon cache-icon"></i></span></a></li>
			</ul>
			<form action="<?=U('/Cms/UserGetDateList')?>" method="post" id='search_form'>
			<div class="search-box1 right">
				<div class="search-boxcon boxsizing radius3 left">
					
					<select class="sele-com1 search-sele boxsizing" name="type" id="search_select">
                		<option value="member_mobile">数据名称</option>
                	</select>
					<input type="text" value="{$modename}" name="modename"  class="search-inp-con boxsizing" />
				</div>
				<button type="submit" class="search-btn right radius3">{$Think.lang.search}</button>
			</div>
			</form>
		</div>
		
		<div class="comtable-box boxsizing">
			<table class="com-table">
				<thead>
					<tr>
						<th width="200">{$Think.lang.operation}</th>
						<th width="200">数据调用名称</th>
						<th width="90">数量</th>
						<th width="320" class="text-l">调用代码</th>
						<th width="120" class="text-c">状态</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<empty name="getdatalist">
                        <tr class="tr-minH">
                            <td colspan="6">暂无数据！</td>
                            <td></td>
                        </tr>
                    <else />
					<foreach name="getdatalist" item="data">
					<tr id="deldata_{$data.id}">
						<td>
							<div class="table-iconbox">
								<div class="edit-iconbox left edit-sele marginR10">
									<a class="edit-word table-icon-a deldata"  href="javascript:void(0)" id="{$data.id}"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
								</div>
								<div class="edit-iconbox left edit-sele marginR10">
									<a class="edit-word table-icon-a" href="<?php echo U('Cms/UserGetDateEdit', array('modeid'=>$data['id']));?>"><i class="table-com-icon table-edit-icon"></i><span class="gap">编辑</span></a>
								</div>
							</div>
						</td>
						<td>{$data.modename}</td>
						<td>{$data.goodsnum}</td>
		                <td class="text-l"><?php echo trim(SITE_URL,'/');?>{$data.usergetdata}</td>
		                <td class="text-l">
							<if condition="$data.state eq 1 ">
								<div class="state-btn yes-state">
									<i class="yes-icon"></i>
									<span>{$Think.lang.yes}</span>
								</div>
								<else />
								<div class="state-btn no-state">
									<i class="no-icon"></i>
									<span>{$Think.lang.no}</span>
								</div>
							</if>
						</td>

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

		$('.add-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.add-li'),e,'数据列表');
		})
		$('.add-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
		$('.refresh-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.add-li'),e,'{$Think.lang.refresh}');
		})
		$('.clear-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event;
			remindNeed($('.add-li'),e,'更新全部缓存');
		})
		$('.clear-li').mouseout(function(){
			$('.tip-remind').remove();
		});
		$('.refresh-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
	    $('.export-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.export-li'),e,'{$Think.lang.export}');
		})
		$('.export-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
	    $('.export-li').click(function(){
	    	var action_url = $("#search_form").attr("action");
	    	location.href = action_url+"?export=1&search_select="+$("#search_select").val()+"&search_text="+$("#search_text").val();
	    });
		$('.deldata').click(function(){
			var id = $(this).attr('id');
			showConfirm('您确定要删除吗',function () {
				$.ajax({
					url:"<?php echo U('Cms/deldata');?>",
					type:"POST",
					data:{id:id},
					success: function(data) {
						var data = data;
						if(data.code == 0){
							showSuccess(data.message);
							$("#deldata_"+id).remove();
						}else{
							showError(data.message);
						}
					}
				});
			});

		})
	})
	function updateCash(id){

		$.ajax({
			url:"<?php echo U('Cms/updateCash');?>",
			type:"POST",
			data:{id:id},
			success: function(data) {
				var data = data;
				if(data.code == 0){
					showSuccess(data.message);
				}else{
					showError(data.message);
				}
			}
		});
	}
</script>