<!--<div class="tip-remind">收起提示</div>-->
<div class="tipsbox">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		{$Think.lang.manager_add_tips}
	</ol>
</div>
<!--内容开始-->
<div class="iframeCon">
<div class="iframeMain">
			<ul class="transverse-nav">
				<li class="activeFour"><a href="javascript:;"><span><if condition="$info.uid eq ''">{$Think.lang.manager_add}<else/>{$Think.lang.manager_edit}</if></span></a></li>
			</ul>
    <div class="white-bg">
        <div class="tab-conbox">
            <form method="post" class="form-horizontal" name="system_form" id="system_form" action="<?=U('/System/add')?>">
                <div class="jurisdiction boxsizing">
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.manager_uname}：</dt>
                        <dd class="left text-l">
                            <input type="text" class="com-inp1 radius3 boxsizing" localrequired="" name="username"   value="<?=$info['username']?>" id="firstname" <?php if($type == 'edit') echo 'readonly="true"'?>/>
                            <p class="remind1">管理员账户不可修改</p>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.manager_pwd}：</dt>
                        <dd class="left text-l">
                            <input type="password" class="com-inp1 radius3 boxsizing" <?php if (empty($info['username'])) {?> localrequired="" <?php }?> name="password" id="password"/>
                            <p class="remind1">{$Think.lang.manager_pwd_tips}</p>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.manager_pwd_again}：</dt>
                        <dd class="left text-l">
                            <input type="password" class="com-inp1 radius3 boxsizing" <?php if (empty($info['username'])) {?> localrequired="" <?php }?> name="confirm_password"  id="confirm_password"/>
                            <p class="remind1">{$Think.lang.manager_pwd_again_tips}</p>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.permiss_group}：</dt>
                        <dd class="left text-l">
                        <div class="search-boxcon boxsizing radius3 borderR-none" style="display: inline-block;">
						<select localrequired=""  name="groupid" id="groupid" class="com-sele radius3 juris-dl-sele">
							<option value=0>{$Think.lang.permiss_group_select}</option>
							<?php foreach($role as $rk=>$rv) {?>
							<option value=<?=$rv['id']?> <?php if($info['groupid'] == $rv['id']) {?> selected <?php } ?>  > <?=$rv['name']?></option>
							<?php }?>
						</select>
                        </div>
                            <p class="remind1">{$Think.lang.permiss_group_tips }</p>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing" style="<?php if($type == 'add') echo 'display:none'?>">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>绑定微信：</dt>
                        <dd class="left text-l">
                            <img src="<?php echo $ewimg?>" width="200" height="200">
                            <p class="remind1">扫描二维码,绑定管理员帐号和微信号</p>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing" style="<?php if($type == 'add') echo 'display:none'?>">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>新订单微信通知：</dt>
                        <dd class="left text-l">

                            <div class="switch-box">
                                <input type="checkbox" localrequired="" name="statues" id="switch-radio" class="switch-radio" <?php if($info['statues'] == 'on') echo 'checked="true"' ?>/>
                                <span class="switch-half switch-open">ON</span>
                                <span class="switch-half switch-close close-bg">OFF</span>
                            </div>
                            <p class="remind1">开启后若管理员已绑定微信号，则可以收到新订单通知，关闭则不接收新订单通知。</p>
                        </dd>
                    </dl>

                </div>
                <div class="btnbox3 boxsizing">
                	<input name="id" type="hidden" value="<?=$info['uid'] ?>" >
                    <input type="hidden" name='form_submit' value="submit" />
                    <a class="btn1 radius3 btn3-btnmargin" id="form_submitadd" href="javascript:;">{$Think.lang.submit_btn}</a>
                    <a class="btn1 radius3" href='{:U("/system/lists")}'>{$Think.lang.return_list}</a>
                </div>
            </form>
        </div>
    </div>
</div>  
</div>  
<!--内容结束-->
<script type="text/javascript">
    $(function(){
        $('#form_submitadd').click(function(){
            flag=checkrequire('system_form');
            if(!flag){
                $('#system_form').submit();
            }
        });
    })
</script>