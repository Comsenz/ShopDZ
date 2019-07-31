<div class="tipsbox">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
	<ol class="tips-list">
		<li>1.‘+’按钮进行添加原因</li>
		<li>2.‘删除’按钮可删除原因（可批量删除，默认全选为空）</li>
		<li>3.‘编辑’操作可编辑原因内容以及是否启用该原因</li>
	</ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
    <div class="white-bg">
		<div class="table-titbox">
			<h1 class="table-tit left boxsizing">退款退货原因</h1>
			<ul class="operation-list left">
				<li class="add-li" onclick="location.href='{:U('/Presales/addcauses')}'"><a href="#"><span><i href="#" class="operation-icon add-icon"></i></span></a></li>
				<li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
				<li class="dele-li"><a href="#"><span><i href="#" class="operation-icon op-dele-icon"></i></span></a></li>
			</ul>
			
			
			<form action="{:U('/Presales/causes')}" name="Search_form" method="get">
				<div class="search-box1 right">
					
	                <div class="search-boxcon boxsizing radius3 left">
		            	<select id='causessearch' class="sele-com1 search-sele boxsizing" name="field">
							<option selected>原因名称</option>
						</select>
						<input type="text" placeholder="" name="value" class="search-inp-con boxsizing"  value="{$search_text}" />
					</div>
					<input value="搜索" class="search-btn right radius3" type="submit">
				</div>
			</form>
			
		</div>	
		<div class="comtable-box boxsizing">
			<table class="com-table">
				<thead>
					<tr>
						<th width="100" class="text-l">
							<div class="button-holder">
								<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-1" name="radio-1-set" class="regular-radio" /><label for="radio-1-1"></label><span class="radio-word">全选</span></p>
							</div>
						</th>
						<th width="180">操作</th>
						<th width="180" class="text-l">退款退货原因</th>
						<th width="150">使用次数</th>
						<th width="120">状态</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<empty name="list">
                        <tr class="tr-minH">
                            <td colspan="5">暂无数据！</td>
                            <td></td>
                        </tr>
                    <else />
	                <foreach name="list" item="vo" key="k">
	                    <tr>
	                    	<td class="text-l">
								<div class="button-holder">
									<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-{$k+2}" name="causes_id" class="regular-radio" value="{$vo.causes_id}"/><label for="radio-1-{$k+2}"></label><span class="radio-word"></span></p>
								</div>
							</td>
		                    <td>
		                    	<div class="table-iconbox">
									<div class="edit-iconbox left edit-sele marginR10">
										<a class="edit-word table-icon-a" href="javascript:void(0);" onclick="del({$vo['causes_id']});"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
									</div>
									<div class="edit-iconbox left edit-sele marginR10">
										<a class="edit-word table-icon-a" href="{:U('Presales/addcauses', array('causes_id'=>$vo['causes_id']))}"><i class="table-com-icon table-edit-icon"></i><span class="gap">编辑</span></a>
									</div>
								</div>
							</td>
		                    <td class="text-l">{$vo.causes_name}</td>
		                    <td>{$vo.clicknum}</td>
		                    <td>
		                    	<if condition="$vo['status'] eq 1 ">
				                	<div class="state-btn yes-state">
										<i class="yes-icon"></i>
										<span>正常</span>
									</div>
				                <else />
				                	<div class="state-btn no-state">
										<i class="no-icon"></i>
										<span>停用</span>
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
	function del(id) {
		showConfirm('系统将会删除所选中的退货退款原因，确认操作吗？',function () {
            $.ajax({
                url:"{:U('Presales/delcauses')}",
                type:"get",
                data:{causes_id:id},
                success: function(info) {
                    if(info.status==1){
                    	showSuccess("您已经永久删除了这条信息。",function(){window.location.reload()});
                    }else{
                    	showError(info.content);
                    }
                }
            });
        });
    }
    $(function(){
		$('.add-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.add-li'),e,'添加原因');
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
	    $('.dele-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.dele-li'),e,'批量删除');
		})
		$('.dele-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
	    $('.dele-li').click(function(){
	    	var ids = new Array();
	    	$('input[name="causes_id"]:checked').each(function(){
	    		if($(this).val()) ids[ids.length] = $(this).val();
	    	});
	    	if(ids.length > 0) del(ids);
	    });
    });
</script>
