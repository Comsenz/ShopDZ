    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css" />
    <link rel="stylesheet" href="__PUBLIC__/css/style.min862f.css" />
    <link href="__PUBLIC__/css/plugins/switchery/switchery.css" rel="stylesheet">
    <link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
    <link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
    <link href="__PUBLIC__/css/style.css" rel="stylesheet"/>
    <div class="content">
        <!--提示框开始-->
        <div class="alertbox1">
            <div class="alert-con radius3">
                <button class="closebtn1">×</button>
                <div class="alert-con-div">
                    <h1 class="fontface alert-tit1">&nbsp;{$Think.lang.operation_tips}</h1>
                    <ol>
                        <li>1.填写第三方登录的信息</li>
                        
                    </ol>
                </div>
            </div>
        </div>
        <!--提示框结束-->
        <!--切换内容-->
        <div id="sidebar-tab" class="sidebar-tab"> 
            <div id="tab-title" class="tab-title"> 
                <ul>
                    <li class="selected">微信登录设置</li>
                    <li>QQ登录设置</li>
                </ul>
            </div> 
            <div id="tab-content" class="sidebar-con"> 
                <div>
                    <form method="post" class="form-horizontal" id="setting_form1" action="{:U('Admin/Setting/thirdlogin')}">
                        <div class="jurisdiction">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>开启微信登录：</dt>
                                <dd class="left text-l">
                                    <div class="switch">
                                        <div class="onoffswitch">
                                            <input type="checkbox" <if condition="$config['wx_login']">checked="true" </if> name="wx_login" class="onoffswitch-checkbox" id="example1">
                                            <label class="onoffswitch-label" for="example1">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>应用标识ID：</dt>
                                <dd class="left text-l">
                                    <input type="text" name="wx_AppID" class="com-inp1 radius3 boxsizing" placeholder="请输入文本" value="{$config['wx_AppID']}" localrequired="">
                                    <a target="_blank" href="#" class="a-href1">在线申请</a>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>应用密钥：</dt>
                                <dd class="left text-l">
                                    <input type="text" name="wx_AppSecret" class="com-inp1 radius3 boxsizing" placeholder="请输入文本" value="{$config['wx_AppSecret']}" localrequired="">
                                </dd>
                            </dl>
                        </div>
                        <div class="btnbox3">
                            <button class="btn1 radius3 btn-margin" type="submit" name="sub" value="1">{$Think.lang.submit_btn}</button>
                        </div>
                    </form>
                </div> 
                <div class="hide2">
                    <form method="post" class="form-horizontal" id="setting_form2" action="{:U('Admin/Setting/thirdlogin')}">
                        <div class="jurisdiction">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>开启QQ登录：</dt>
                                <dd class="left text-l">
                                    <div class="switch">
                                        <div class="onoffswitch">
                                            <input type="checkbox" <if condition="$config['qq_login']">checked="true" </if> name="qq_login" class="onoffswitch-checkbox" id="example2">
                                            <label class="onoffswitch-label" for="example2">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>应用标识ID：</dt>
                                <dd class="left text-l">
                                    <input type="text" name="qq_login_appid" class="com-inp1 radius3 boxsizing" placeholder="请输入文本" value="{$config['qq_login_appid']}" localrequired="">
                                    <a target="_blank" href="#" class="a-href1">在线申请</a>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>应用密钥：</dt>
                                <dd class="left text-l">
                                    <input type="text" name="qq_login_key" class="com-inp1 radius3 boxsizing" placeholder="请输入文本" value="{$config['qq_login_key']}" localrequired="">
                                </dd>
                            </dl>
                        </div>
                        <div class="btnbox3">
                            <button class="btn1 radius3 btn-margin" type="submit" name="sub" value="1">{$Think.lang.submit_btn}</button>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>
    <!--content结束-->
<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/alert.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/tab.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/bootstrap.min.js"></script>
<script src="__PUBLIC__/js/plugins/switchery/switchery.js"></script>







