<div class="wrapper wrapper-content animated fadeInRight">
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <span style="height:32px;line-height:32px;font-size:14px;"><i class="fa fa-bell-o"></i>&nbsp;操作提示</span>
        
        <p>1.填写邮件服务器相关参数，并点击“测试”按钮进行效验，保存后生效。</p>
        <p>2.如使用第三方提供的邮件服务器，请认真阅读服务商提供的相关帮助文档。</p>
    </div>
	<div class="row">
		<div class="col-sm-12">
			<div class="ibox float-e-margins">
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
				<table class="footable table table-stripped toggle-arrow-tiny" data-page-size="8">
                                <thead>
                                <tr>
                                    <th data-toggle="true">管理员</th>
                                    <th>权限组</th>
                                    <th>上次登录时间</th>
                                    <th data-hide="all">IP</th>
                                    <th >操作</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php foreach($lists as $key => $v) { ?>
									<tr>
										<td><?=$v['username']; ?></td>
										<td><?=$v['groupid']; ?></td>
										<td><?=date('Y-m-d H:i:s',$v['lastdateline']); ?></td>
										<td><?=$v['ip']; ?></td>
										<td>
										<a href="<?=U('/member/add/id/'.$v['uid'])?>" class="btn btn-white btn-sm"><i class="fa fa-wrench"></i></i> 编辑 </a>
										<a href="#" class="btn btn-white btn-sm"><i class="fa fa-times"></i> 删除</a></td>
									</tr>
								<?php } ?>
                           
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <div class="pagination pull-right">
										<?=$page ?>
										</div>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
				<div class="form-group">
					<div class="col-sm-4 col-sm-offset-2">
						<button class="btn btn-primary" type="submit" id="submit_bind">保存</button>
					</div>
				</div>
			</form>
		</div>
</div>
</div>
</div>
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
