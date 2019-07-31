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
				<div class="">
						<span class="btn">权限组列表</span>
                         <a class="btn btn-primary pull-right" href="<?=U('/member/addrole')?>" >添加权限组</a>
                        </div>
                            <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="1">
                                <thead>
                                <tr>
                                    <th data-toggle="true">权限组</th>
                                    <th>人数</th>
                                    <th >操作</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php foreach($data_list as $key => $v) { ?>
									<tr>
										<td><?=$v['name']; ?></td>
										<td><?=$v['ip']; ?></td>
										<td>
										<a href="<?=U('/member/add/id/'.$v['uid'])?>" class="btn btn-white btn-sm"><i class="fa fa-wrench"></i></i> 编辑 </a>
										<a href="#" class="btn btn-white btn-sm"><i class="fa fa-times"></i> 删除</a></td>
									</tr>
								<?php } ?>
                           
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <div class="pagination pull-right">
										<?=$page ?>
										</div>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
				</div>
			</div>
		</div>
	</div>
</div>