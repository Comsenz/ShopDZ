
		<div class="ibox-content">
			<form action="" method="post" id="bind_form" class="form-horizontal" role="bind_form" enctype="multipart/form-data">
				<input type="hidden" value="submit" name="form_submit">
				<input type="hidden" value="<?=$id?>" name="id">
				<div class="form-group">
					<label class="col-sm-2 control-label">角色名</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="name" value="<?=$name?>">
					</div>
				</div>
				<div class="hr-line-dashed"></div>
				<div class="form-group">
					<div class="col-sm-4 col-sm-offset-2">
						<button class="btn btn-primary" type="submit" id="submit_bind">保存</button>
					</div>
				</div>
			</form>
		</div>

<script type="text/javascript">
$(function(){
$('#bind_form').ajaxForm({
		dataType: 'json',
		success: function(response) {
			if(response.status==1){
				var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
				parent.layer.close(index);	 
			}else{
				parent.layer.alert(response.info);
			}
		}
});
});
</script>
