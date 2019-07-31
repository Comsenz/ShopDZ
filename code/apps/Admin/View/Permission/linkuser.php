<div class="ibox-content">
	<form action="" method="post" id="bind_form" class="form-horizontal" role="bind_form" enctype="multipart/form-data">
		<input type="hidden" value="submit" name="form_submit">
		<input type="hidden" value="<?=$id?>" name="id">
		
			<div class="col-md-6">
    <div class="form-group">
 
        <div class="col-sm-9">
		  <?php foreach($allusers as $val):?>
            <label class="checkbox-inline">
                <input type="checkbox" <?php if($users[$val['id']]) {?> checked="checked" <?php }?>value="<?=$val['id'] ?>" name="name[]"><?=$val['username'] ?></label>
             <?php endforeach;?>
          
        </div>
    </div>
	</div>
<div class="col-md-6"></div>
		
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
				parent.location.reload();
			}else{
				layer.alert(response.info);
			}
		}
});
});
</script>