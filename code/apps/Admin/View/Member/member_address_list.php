<div class="iframeCon">
<div class="iframeMain">
			<div class="white-bg">
				<div class="table-titbox">
					<h1 class="table-tit left boxsizing" style="width:auto;padding-right:10px;margin-left:14px;">{$Think.lang.member}“{$info.member_username}”的{$Think.lang.shipping_address}</h1>
					<ul class="operation-list left">
						<li class="add-li"><a href="<?php echo U('Member/addressShow', array('member_id'=>$info['member_id']));?>"><span><i href="#" class="operation-icon add-icon"></i></span></a></li>
						<li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
					</ul>
				</div>
				
				<div class="comtable-box boxsizing">
					<table class="com-table">
						<thead>
							<tr>
								<th width="200">{$Think.lang.operation}</th>
								<th width="100">{$Think.lang.addressee}</th>
								<th width="180">{$Think.lang.phone_nember}</th>
								<th width="220" class="text-l">{$Think.lang.region}</th>
								<th width="300" class="text-l">{$Think.lang.address_detail}</th>
								<th width="80">{$Think.lang.default_address}</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<foreach name="address_list" item="address">
							<tr>
								<td>
									<div class="table-iconbox">
										<div class="edit-iconbox left edit-sele boxsizing marginR10">
											<a class="edit-word table-icon-a" onclick="address_del({$address.address_id})"><i class="table-com-icon table-dele-icon"></i><span class="gap">{$Think.lang.delete}</span></a>
										</div>
										<div class="edit-iconbox left edit-sele boxsizing marginR10">
											<a class="edit-word table-icon-a" href="<?php echo U('Member/addressShow', array('member_id'=>$info['member_id'],'address_id'=>$address['address_id']));?>"><i class="table-com-icon table-edit-icon"></i><span class="gap">{$Think.lang.edit}</span></a>
										</div>
									</div>
								</td>
				                <td>{$address.true_name}</td>
				                <td>{$address.tel_phone}</td>
								<td class="text-l">{$address.area_info}</td>
								<td class="text-l">{$address.address}</td>
								<td>
								<if condition="$address.is_default eq 1">
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
						</tbody>
					</table>
				</div>
			</div>
		</div>
</div>
		<script type="text/javascript">
			$(function(){
				//添加会员提示
				$('.add-li').mousemove(function(){
					e=arguments.callee.caller.arguments[0] || window.event; 
					remindNeed($('.add-li'),e,'{$Think.lang.address_add}');
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
			   
			})
			function address_del(address_id) {

				showConfirm("{$Think.lang.address_delete_tips}",function(){
					 $.ajax({
			            url:"<?php echo U('Member/address_del');?>",
			            type:"POST",
			            data:{address_id:address_id},
			            success: function(response) {
			                getResultDialog(response);
			            }
			        });
				})
			}
		</script>
