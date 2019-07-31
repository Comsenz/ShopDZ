
	<head>
		<meta charset="UTF-8">
		<title>添加管理员</title>
		<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
		<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>
		<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
	</head>
		<div class="content">
			<div class="jurisdiction">
			<form id="signupForm" role="form" action="<?=U('/Logistics/setstorage',array('id'=>$info['id']))?>"  method ="post" >
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>仓库名称：</dt>
					<dd class="left text-l">
						<input type="text" localrequired=""  class="com-inp1 radius3 boxsizing"   name="name" id="firstname" value="<?=$info['name']?>" />
						<p class="remind1">填写仓库名称</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>负责人：</dt>
					<dd class="left text-l">
						<input type="text"  <?php if (empty($info['person'])) {?>  localrequired="" <?php }?>   name="person" id="person"  value="<?=$info['person']?>" class="com-inp1 radius3 boxsizing"/>
						<p class="remind1">填写仓库负责人</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>联系电话：</dt>
					<dd class="left text-l">
						<input type="text" <?php if (empty($info['telphone'])) {?> localrequired="" <?php }?>   name="telphone" value="<?=$info['telphone']?>"  class="com-inp1 radius3 boxsizing"/>
						<p class="remind1">填写联系方式</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>所在地区：</dt>
					<dd class="left text-l">
						<?php if($info) { ?>
					 <input id="region"localrequired=""  name="region" type="hidden" value="<?=$area[$info['add_province']].' '.$area[$info['add_city']].' '.$area[$info['add_dist']]?>" >
					 <?php }else {?>
						 <input id="region"localrequired=""  name="region" type="hidden" value="" >
					  <?php }?>
						<p class="remind1">填写仓库所在地区</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>街道地址：</dt>
					<dd class="left text-l">
						<input type="text" <?php if (empty($info['add_community'])) {?> localrequired="" <?php }?> value="<?=$info['add_community']?>"   name="add_community"  class="com-inp1 radius3 boxsizing"/>
						<p class="remind1">填写仓库地址街道信息</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar"></span>默认仓库：</dt>
					<dd class="left text-l">
						<input type="checkbox" <?php if (!empty($info['status'])) {?>  checked='checked' <?php }?> name="status"  class="radius3 boxsizing"/>
						<p class="remind1">是否为默认仓库</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar"></span>备注信息：</dt>
					<dd class="left text-l">
						<textarea name="ext_info" class="com-textarea1 radius3 boxsizing" type="text"><?=$info['ext_info']?></textarea>
						<p class="remind1">填写仓库备注信息</p>
					</dd>
				</dl>
			</div>
			<div class="btnbox3">
			
				<input  type="hidden" name="form_submit" value="submit" >
				<input type="submit"  value="{$Think.lang.submit_btn}" class="btn1 radius3 footbtn"/>
				<input type="button"  onclick="javascript:window.location.href='<?=U('/Logistics/storageList')?>'" value="返回列表" class="btn1 radius3 footbtn"/>
				<input type="hidden" name="area_id" id="area_id" value="{$info.area_id}" />
				<input type="hidden" name="parent_id" id="_area" value="{$info.add_dist}">
				<input type="hidden" name="area_deep" id="area_deep" value="{$info.area_deep}">
			</div>
			</form>
		</div>
		<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
		<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script>

$(function(){
    $("#region").nc_region({src:'db',show_deep:3});
 
}
);

</script>
