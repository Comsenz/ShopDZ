<!--内容开始-->
<div class="iframeCon">
<div class="iframeMain">
	<ul class="transverse-nav">
		<li class="activeFour"><a  href="#"><span><if condition="$info.address_id eq ''">{$Think.lang.address_add}<else/>{$Think.lang.address_edit}</if></span></a></li>
	</ul>
	<div class="white-bg">

		<div class="tab-conbox">
		<form method="post" class="form-horizontal" name="addressForm" id="addressForm" action="{:U('Member/member_address',array('area_id',$info.area_id))}" enctype="multipart/form-data">
			<div class="jurisdiction boxsizing">
			
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.addressee}：</dt>
					<dd class="left text-l">
						<input class="com-inp1 radius3 boxsizing" name="true_name" type="text" value="{$info.true_name}" localrequired="">
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.phone_nember}：</dt>
					<dd class="left text-l">
						<input class="com-inp1 radius3 boxsizing" name="tel_phone" type="text" value="{$info.tel_phone}" localrequired="">
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.select_area}：</dt>
					<dd class="left text-l">
						<input id="member_id" name="member_id" type="hidden" value="{$member_id}"/>
						<input id="region" name="area_info" type="hidden" value="{$info.area_info}" >
						<input id="_area" type="hidden" value="{$info.area_id}" name="area_id">
						<input type="hidden" value="{$info.address_id}" name="address_id">
					</dd>
				</dl>
				
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.address_detail}：</dt>
					<dd class="left text-l">
						<input class="com-inp1 radius3 boxsizing" name="address" type="text" value="{$info.address}" localrequired="">
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.set_default}：</dt>
					<dd class="left text-l">
					<div class="button-holder">
						<p class="radiobox"><input type="checkbox" <?php if($info['is_default']){?> checked="checked" <?php } ?> name="is_default" id="is_default" class="regular-radio" /><label for="is_default"></label><span class="radio-word">{$Think.lang.set_default_select}</span></span></p>
					</div>
					</dd>
				</dl>
			</div>
			<div class="btnbox3 boxsizing">
				<a type="button" id="subbtn" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
				<a class="btn1 radius3 marginT10" href="{:U('member/address_list',array('member_id'=>$member_id))}">{$Think.lang.return_list}</a>
			</div>
		</form>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
$(function(){
	$('#subbtn').click(function(){
        flag=checkrequire('addressForm');
        if(!flag){
           $('#addressForm').submit();
        }
    });
	$("#region").nc_region({src:'db',show_deep:3,width:150});
});
</script>
