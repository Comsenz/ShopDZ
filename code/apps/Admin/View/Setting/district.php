<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
   	<ol class="tips-list" id="tips-list">
		{$Think.lang.dist_list_tip_content}
	</ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
	<div class="white-bg">
		<div class="table-titbox">
			<h1 class="table-tit left boxsizing">{$Think.lang.district_manager}</h1>
			<ul class="operation-list left">
				<li class="add-li"><a href="{:U('district/add',array('parent_id' => $this_area_id))}"><span><i href="#" class="operation-icon add-icon"></i></span></a></li>
				<li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
				<li class="dele-li del"><a href="#"><span><i href="#" class="operation-icon op-dele-icon"></i></span></a></li>
				<li class="back-li"><a href="{:U('Setting/district',array('area_parent_id' => $area_parent_id))}"><span><i href="#" class="operation-icon op-back-icon"></i></span></a></li>
			</ul>
			<div class="search-box1 right">
			<form action="<?=U('/setting/district',array('area_parent_id' => $this_area_id))?>" method="get" id='formid'>
				<div class="search-boxcon boxsizing radius3 left">
					<select name="type" id="search_select">
	                    <option value="area_name">{$Think.lang.dist_name}</option>
	                </select>
					<input type="text" name="search_text" value="{$search_text}" class="search-inp-con boxsizing"/>
				</div>
				<input type="submit"  value="{$Think.lang.search}" class="search-btn right radius3"/>
			</form>
			</div>
		</div>
		
		<div class="comtable-box boxsizing">
			<table class="com-table">
				<thead>
					<tr>
						<th width="100" class="text-l">
							<div class="button-holder">
								<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-1" name="radio-1-set" class="regular-radio" /><label for="radio-1-1"></label><span class="radio-word">{$Think.lang.select_all}</span></p>
							</div>
						</th>
						<th width="200">{$Think.lang.operation}</th>
						<th width="200">{$Think.lang.district}</th>
						<th width="100">{$Think.lang.dist_level}</th>
						<th width="100">{$Think.lang.dist_parent}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<empty name="arealist">
                        <tr class="tr-minH">
                            <td colspan="5">暂无数据！</td>
                            <td></td>
                        </tr>
                    <else />
					<foreach name="arealist" key="i" item="area">
					<tr>
						<td class="text-l">
							<div class="button-holder">
								<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-{$i+2}" name="allcheck" class="regular-radio" value="{$area.area_id}" /><label for="radio-1-{$i+2}"></label><span class="radio-word"></span></p>
							</div>
						</td>
						<td>
						<if condition="$area.area_deep eq 3">
							<div class="table-iconbox">
								<div class="edit-iconbox left edit-sele marginR10">
									<a class="edit-word table-icon-a" href="#" onclick="area_del({$area.area_id})"><i class="table-com-icon table-dele-icon"></i><span class="gap">{$Think.lang.delete}</span></a>
								</div>
								<div class="edit-iconbox left edit-sele marginR10">
									<a class="edit-word table-icon-a" onclick="location.href='<?php echo U('district/edit', array('area_id'=>$area['area_id']));?>'"><i class="table-com-icon table-edit-icon"></i><span class="gap">{$Think.lang.edit}</span></a>
								</div>
							</div>

						<else/>
							<div class="table-iconbox">
								<div class="edit-iconbox left edit-sele marginR10">
									<a class="edit-word table-icon-a" onclick="location.href='<?php echo U('district/edit', array('area_id'=>$area['area_id']));?>'"><i class="table-com-icon table-edit-icon"></i><span class="gap">{$Think.lang.edit}</span></a>
								</div>
								<div class="table-icon left setting-sele-par">
									<div class="setting-sele left radius3 boxsizing">
										<span class="setting-word"><i class="table-com-icon table-setting-icon"></i><span class="gap">{$Think.lang.operation}</span></span>
										<span class="jtb-span-box boxsizing"><i class="jtb-span setting-jtb-icon"></i></span>
									</div>
									<ul class="setting-sele-con remind-layer">
										
										<li><a href="<?php echo U('district/add', array('parent_id'=>$area['area_id']));?>">{$Think.lang.dist_add_child}</a></li>
										<li><a href="<?php echo U('Setting/district', array('area_parent_id'=>$area['area_id']));?>">{$Think.lang.dist_view_child}</a>
			                            </li>
			                            <li><a href="#" onclick="area_del({$area.area_id})">{$Think.lang.dist_remove_region}</a>
			                            </li>
									</ul>
								</div>
								
							</div>
						</if>
						</td>
						<td>{$area.area_name}</td>
		                <td>{$area.area_deep}</td>
		                <td>{$area.parent_area_name}</td>
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
		//添加会员提示
		$('.add-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.add-li'),e,'{$Think.lang.district_add}');
		})
		$('.add-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
		$('.refresh-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.refresh-li'),e,'{$Think.lang.refresh}');
		})
		$('.refresh-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
	   
		$('.export-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
	    $('.dele-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.dele-li'),e,'{$Think.lang.batch_delete}');
		})
		$('.dele-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
	    $('.back-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.back-li'),e,'{$Think.lang.back_parent}');
		})
		$('.back-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
			
	})
	$('.del').click(function(){
        var ids = "";
        $("input[name=allcheck]").each(function(){
            if($(this).is(":checked")){
                ids += $(this).val()+",";
            }
        });
        if(ids) {
            ids = ids.substring(0, ids.length - 1); //把最后一个逗号去掉
            area_del(ids);
        }
    });
    //删除
    function area_del(area_ids) {
    	showConfirm("{$Think.lang.dist_delete_tips}",function(){
			$.ajax({
                url:"<?php echo U('district/delArea');?>",
                type:"POST",
                data:{area_ids:area_ids},
                success: function(response) {
                    getResultDialog(response);
                }
            });
    	});
    }
</script>
