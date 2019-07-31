
		<!--<div class="tip-remind">收起提示</div>-->
		<div class="tipsbox">
			<div class="tips boxsizing radius3">
				<div class="tips-titbox">
					<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
					<span class="open-span span-icon"><i class="open-icon"></i></span>
				</div>
			</div>
			<ol class="tips-list" id="tips-list">
				{$Think.lang.permiss_group_edit_tips}
			</ol>
		</div>
<div class="iframeCon">
<div class="iframeMain">
			<ul class="transverse-nav">
				<li class="activeFour"><a href="javascript:;"><span>{$Think.lang.permiss_group_edit}</span></a></li>
			</ul>
<form action="<?=U('/system/editpermission')?>" role="form" method="post" id="role_form">
	<div class="white-shadow2">
		<div class="jurisdiction boxsizing">
			<dl class="juris-dl boxsizing">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.permiss_group}：</dt>
				<dd class="left text-l">
					<input type="text" class="com-inp1 radius3 boxsizing" localrequired="" value="<?=$role['name'] ?>" name="groupname" />
					<p class="remind1">{$Think.lang.permiss_group_name_tips}</p>
				</dd>
			</dl>
			<dl class="juris-dl boxsizing dlborder-bot">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.permiss_setting}：</dt>
				<dd class="left text-l">
					<div class="checkbox-holder">
						<p class="radiobox"><input type="checkbox" id="radio-1-1" name="radio-1-set" class="regular-radio" /><label for="radio-1-1"></label><span class="radio-word">{$Think.lang.permiss_select_all}</span></p>
					</div>
					<p class="remind1">{$Think.lang.permiss_setting_tips}</p>
				</dd>
			</dl>
		</div>
		<div class="powers-tablebox">
			<table class="powers-table">
				<thead>
					<tr>
						<th colspan="2">{$Think.lang.permiss_setting_detail}</th>
					</tr>
				</thead>
				<tbody>

				<?php  $i=2;
				foreach($rbacnode as $nodek=>$nodes) { 
					$j=2;
					?>
					<tr>
						<td style="width: 148px;">
							<div class="checkbox-holder powers-tdcheck">
								<p class="radiobox checkbox-box">
									<input type="checkbox" id="radio-<?php echo $i; ?>-1" name="radio-1-set" class="regular-radio this-parent" child="radio-<?php echo $i; ?>" /><label for="radio-<?php echo $i; ?>-1"></label>
									<span class="radio-word"><?=$nodes[$nodek]['name_cn'];?></span>
								</p>
							</div>
						</td>


						<td id="shanpin_<?=$nodek?>" class="shanpin" >
						<?php foreach($nodes as $k=>$node) {
							if($node['level'] ==3 ) { ?>
							<div class="checkbox-holder left powers-checkbox">
								<p class="radiobox checkbox-box">
									<input type="checkbox" id="radio-<?php echo $i; ?>-<?php echo $j; ?>" <?php if($nodelist[$node['id']]) {?> ischecked="yes" <?php }?>   value="<?=$rbacnode[$nodek][$nodek]['id'];?>_<?=$node['id'];?>" name="checkname[]" class="check-top regular-radio radio-<?php echo $i; ?>" /><label for="radio-<?php echo $i; ?>-<?php echo $j; ?>"></label>
									<span class="radio-word"><?=$node['name_cn'];?></span>
								</p>
							</div>
						<?php $j++; }}?>
						</td>

					</tr>
					<?php $i++; } ?>

				</tbody>
			</table>
		</div>
        <div class="btnbox3 boxsizing">
            <input type="hidden" name='form_submit' value="submit" />
            <input  type="hidden" name="id" value="<?=$role['id'] ?>" >
            <a class="btn1 radius3 btn3-btnmargin" id="form_submitadd" href="javascript:;">{$Think.lang.submit_btn}</a>
            <a class="btn1 radius3" href='{:U("/system/rolelist")}'>{$Think.lang.return_list}</a>
        </div>
	</div>
	</div>
</form>
</div>
<script type="text/javascript">
    $(function(){
        $('#form_submitadd').click(function(){
            flag=checkrequire('role_form');
            nameval = $('[name="checkname[]"]:checked').length;
            if(nameval == 0){
            	showError({$Think.lang.permiss_select_only_one});
            	return;
            }else{
	            if(!flag){
	                $('#role_form').submit();
	            }
        	}
        });


 //     	$("input[class='regular-radio']").each(function(i){
	// 	_this = this;
	// 	if($(_this).attr('ischecked') == 'yes'){ 
	// 		 $(_this).attr('checked','checked');
	// 	     $(_this).parent('.radiobox').find('.radio-word').addClass('radio-word-black');
	// 	} else {
	// 		$(_this).removeAttr('checked','');
	// 		$(_this).parent('.radiobox').find('.radio-word').removeClass('radio-word-black');
	// 	}
	// });
			var j = k = 0;
			$('.shanpin').each(function(i) {
				$(this).find("[ischecked='yes']").attr('checked','checked');
				$(this).find("[ischecked='yes']").parent('.radiobox').find('.radio-word').addClass('radio-word-black');
				var inputLen =$(this).find("input[type='checkbox']").length;
				var input_checked_Len =$(this).find("[ischecked='yes']").length;
				if(inputLen == input_checked_Len) {
					$(this).siblings().find("input").prop("checked",true);
					$(this).siblings().find('.radio-word').addClass('radio-word-black');
				}
				j += input_checked_Len;
				k += inputLen;
			}
			);
			if(j ==k) {
				$("#radio-1-1").prop("checked",true);
			}

    })
</script>