
		<!--<div class="tip-remind">收起提示</div>-->
		<div class="tipsbox">
			<div class="tips boxsizing radius3">
				<div class="tips-titbox">
					<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
					<span class="open-span span-icon"><i class="open-icon"></i></span>
				</div>
			</div>
			<ol class="tips-list" id="tips-list">
				{$Think.lang.manager_update_pwd_tips}
			</ol>
		</div>
<!--内容开始-->
<div class="iframeCon">
<div class="iframeMain">
			<ul class="transverse-nav">
				<li class="activeFour"><a href="javascript:;"><span>{$Think.lang.manager_update_pwd}</span></a></li>
			</ul>
    <div class="white-bg">
        <div class="tab-conbox">
            <form method="post" class="form-horizontal" name="system_form" id="system_form" action="<?=U('/system/info')?>">
                <div class="jurisdiction boxsizing">
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.manager_old_pwd}：</dt>
                        <dd class="left text-l">
                            <input  type="password"  class="com-inp1 radius3 boxsizing" localrequired=""  name="password"  />
                            <p class="remind1">{$Think.lang.manager_old_pwd_tips}</p>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.manager_new_pwd}：</dt>
                        <dd class="left text-l">
                            <input type="password" class="com-inp1 radius3 boxsizing"  localrequired=""  name="newpassword" id="newpassword" />
                            <p class="remind1">{$Think.lang.manager_new_pwd_tips}</p>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.manager_confirm_pwd}：</dt>
                        <dd class="left text-l">
                            <input type="password" class="com-inp1 radius3 boxsizing" localrequired=""  name="confirm_newpassword"   id="confirm_password"/>
                            <p class="remind1">{$Think.lang.manager_confirm_pwd_tips}</p>
                        </dd>
                    </dl>
                </div>
                <div class="btnbox3 boxsizing">
                    <input type="hidden" name='form_submit' value="submit" />
                    <a class="btn1 radius3 btn3-btnmargin" id="form_submitadd" href="javascript:;">{$Think.lang.submit_btn}</a>
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