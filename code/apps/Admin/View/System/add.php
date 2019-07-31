<div class="wrapper wrapper-content animated fadeInRight">
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <span style="height:32px;line-height:32px;font-size:14px;"><i class="fa fa-bell-o"></i>&nbsp;操作提示</span>
        
        <p>1.填写邮件服务器相关参数，并点击“测试”按钮进行效验，保存后生效。</p>
        <p>2.如使用第三方提供的邮件服务器，请认真阅读服务商提供的相关帮助文档。</p>
    </div>
	<div class="row">
		<div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>添加管理员</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a href="form_basic.html#" data-toggle="dropdown" class="dropdown-toggle">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="form_basic.html#">选项1</a>
                                </li>
                                <li><a href="form_basic.html#">选项2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <form id="signupForm" role="form" action="<?=U('/member/add')?>"  class="form-horizontal m-t" method ="post" >
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><span>*</span>账号：</label>
                                <div class="col-sm-8">
                                    <input type="text" required=""  class="form-control"  name="username" id="firstname" value="<?=$info['username']?>">
                                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 这里写点提示的内容</span>
                                </div>
                            </div>
      
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><span>*</span>密码：</label>
                                <div class="col-sm-8">
                                    <input type="password" <?php if (empty($info['username'])) {?>  required="" <?php }?> class="form-control" name="password" id="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><span>*</span>确认密码：</label>
                                <div class="col-sm-8">
                                    <input type="password" <?php if (empty($info['username'])) {?> required="" <?php }?> class="form-control" name="confirm_password" id="confirm_password">
                                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 请再次输入您的密码</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">权限组：</label>
                                <div class="col-sm-8">
                                    
                                    <select name="groupid" class="form-control">
                                        <option value=0>默认</option>
										<?php foreach($role as $rk=>$rv) {?>
                                        <option value=<?=$rv['id']?> <?php if($info['groupid'] == $rv['id']) {?> selected <?php } ?>  > <?=$rv['name']?></option>
										<?php }?>
                                    </select>
									
                                </div>
								<input name="id" type="hidden" value="<?=$info['uid'] ?>" >
                            </div>
   
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-3">
                                    <button type="submit" value="submit" name="form_submit" class="btn btn-primary">{$Think.lang.submit_btn}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
	</div>
,</div>