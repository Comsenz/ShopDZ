<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">
        <li>{$Think.lang.memberlist_tips_content}</li>
    </ol>
</div>


<div class="iframeCon">
<div class="iframeMain">
	<div class="white-bg">
		<div class="table-titbox">
			<h1 class="table-tit left boxsizing">{$Think.lang.member_list}</h1>
			<ul class="operation-list left">
				<li class="add-li"><a href="{:U('member/add')}"><span><i href="#" class="operation-icon add-icon"></i></span></a></li>
				<li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
				<li class="export-li"><a href="#"><span><i href="#" class="operation-icon export-icon"></i></span></a></li>
			</ul>
			<form action="<?=U('/member/lists')?>" method="get" id='search_form'>
			<div class="search-box1 right">
				<div class="search-boxcon boxsizing radius3 left">
					
					<select class="sele-com1 search-sele boxsizing" name="type" id="search_select">
                		<option value="member_username">{$Think.lang.member_name}</option>
                	</select>
					<input type="text" value="{$search_text}" name="search_text" id="search_text" class="search-inp-con boxsizing"/>
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
						<th width="180">{$Think.lang.member_name}</th>
						<th width="180">{$Think.lang.register_time}</th>
						<th width="280" class="text-l">{$Think.lang.wx_bind}</th>
						<th width="120" class="text-l">{$Think.lang.points}</th>
						<th width="120">{$Think.lang.allow_login}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<empty name="memberlist">
                        <tr class="tr-minH">
                            <td colspan="6">暂无数据！</td>
                            <td></td>
                        </tr>
                    <else />
					<foreach name="memberlist" item="member">
					<tr>
						<td>
							<div class="table-iconbox">
								<div class="edit-iconbox left edit-sele marginR10">
									<a class="edit-word table-icon-a" href="<?php echo U('Member/add', array('id'=>$member['member_id']));?>"><i class="table-com-icon table-edit-icon"></i><span class="gap">{$Think.lang.edit}</span></a>
								</div>
								<div class="table-icon left setting-sele-par">
									<div class="setting-sele left radius3 boxsizing">
										<span class="setting-word"><i class="table-com-icon table-setting-icon"></i><span class="gap">{$Think.lang.look}</span></span>
										<span class="jtb-span-box boxsizing"><i class="jtb-span setting-jtb-icon"></i></span>
									</div>
									<ul class="setting-sele-con remind-layer">
										<li><a href="<?php echo U('member/address_list', array('member_id' => $member['member_id']));?>">{$Think.lang.shipping_address}</a></li>
										<li><a href="<?php echo U('order/lists', array('field'=>'buyer_name','value' => $member['member_username']));?>">{$Think.lang.member_order}</a></li>
										<li><a href="<?php echo U('coupon/member_packet', array('field'=>'rpacket_owner_name','q' => $member['member_username ']));?>">优&nbsp;&nbsp;惠&nbsp;&nbsp;券</a>
			                            </li>
			                            <li><a href="<?php echo U('member/points', array('member_id' => $member['member_id']));?>">{$Think.lang.points_detail}</a>
			                            </li>
									</ul>
								</div>
								
							</div>
						</td>
						<td>{$member.member_username }</td>
						<td>{$member.member_time|date="Y-m-d H:i:s",###}</td>
		                <td class="text-l">{$member.weixin_openid}</td>
		                <td class="text-l">{$member.member_points}</td>
						<td>
						<if condition="$member.member_state eq 1 ">
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
		//添加会员提示
		$('.add-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.add-li'),e,'{$Think.lang.member_add}');
		})
		$('.add-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
		$('.refresh-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.add-li'),e,'{$Think.lang.refresh}');
		})
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
	})
</script>