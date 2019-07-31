<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
<div class="content">
<form method="post" class="form-horizontal" name="company" id="company" action="<?=U('/Logistics/companyList')?>" enctype="multipart/form-data">
	<div class="jurisdiction2">
		<dl class="juris-dl2 boxsizing">
			<dt class="left text-r boxsizing"><span class="redstar">*</span>选择快递公司：</dt>
			<dd class="left text-l">
				<select name='code' class="com-sele radius3 juris-dl-sele sele-m1">
					
					<option value="">-请选择-</option>
					 <?php foreach($list as $k=>$v) {?>
						<option value="<?=$k?>"><?=$v?></option>
					 <?php }?>
				</select>
				<p class="remind1">请选择快递公司</p>
			</dd>
		</dl>
	</div>
	<div class="btnbox2">
	<button class="btn1 radius3 btn-margin2" type="submit">{$Think.lang.submit_btn}</button>
	</div>
	<input type="hidden" name="form_submit" value="submit">
	</form>
</div>

<script>
$(function(){
    $('#company').ajaxForm({
		dataType: 'json',
		success: function(response) {
			if(response.status==1){
				location.reload();     
			}else{
				parent.layer.alert(response.info);
			}
		}
	});

});
</script>
<script type="text/javascript">
	$(function(){
	$('.alert-btn').click(function(){
		$('.cover').removeClass('none');
		$('.alertcon').removeClass('none');
	})
		
	$('.cover,.closebtn2').click(function(){
		$('.cover').addClass('none');
		$('.alertcon').addClass('none');
	})
})
</script>
