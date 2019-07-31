    <link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
    <link href="__PUBLIC__/css/style.css" rel="stylesheet"/>
    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css" />
    <link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap-datetimepicker.css" />
    

    <div class="content">
        <!--提示框开始-->
        <div class="alertbox1">
            <div class="alert-con radius3">
                <button class="closebtn1">×</button>
                <div class="alert-con-div">
                    <h1 class="fontface alert-tit1">&nbsp;操作提示</h1>
                    <ol>
                        <li>1.平台可以选择开启一种或多种消息通知方式。</li>
                        <li>2.短消息、微信需要用户绑定手机、微信后才能正常接收。</li>
                    </ol>
                </div>
            </div>
        </div>
        <!--提示框结束-->
        <!--切换内容-->
       
        <div id="sidebar-tab" class="sidebar-tab"> 
            <div id="tab-title" class="tab-title"> 
                <ul>
                    <li class="selected">站内信模板</li>
                    <!-- <li>手机短信模板</li> -->
                    <li>微信模板</li>
                </ul>
            </div> 
            <div id="tab-content" class="sidebar-con" style="margin-top: -10px;"> 
                <div>
                    <form method="post" class="form-horizontal" name="message_form" action="{:U('Admin/Setting/notes_edit')}" enctype="multipart/form-data">
                        <input type="hidden" name="code" value="{$mmtpl_info.mmt_code}" />
                        <input type="hidden" name="type" value="message" />
                        <div class="jurisdiction">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>站内信开关：</dt>
                                <dd class="left text-l">
                                    <div class="switch">
                                        <div class="onoffswitch">
                                            <input name="message_switch" type="checkbox"  class="onoffswitch-checkbox" id="message" <if condition="$mmtpl_info['mmt_message_switch'] eq 1 ">checked="checked" <else /> </if>>
                                            <label class="onoffswitch-label" for="message">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>消息内容：</dt>
                                <dd class="left text-l">
                                    <textarea type="text" class="com-textarea1 radius3 boxsizing" name="message_content">{$mmtpl_info.mmt_message_content}</textarea>
                                </dd>
                            </dl>
                        </div>
                        <div class="btnbox3">
                            <button class="btn1 radius3 btn-margin" type="submit">{$Think.lang.submit_btn}</button>
                            <a class="btn1 radius3 btn-margin" href="{:U('Admin/Setting/notes')}">返回列表</a>
                        </div>
                    </form>
                </div> 
<!--                 <div class="hide2">
                    <form method="post" class="form-horizontal" name="short_name" action="{:U('Admin/Setting/notes_edit')}" enctype="multipart/form-data">
                        <input type="hidden" name="code" value="{$mmtpl_info.mmt_code}" />
                        <input type="hidden" name="type" value="short" />
                        <div class="jurisdiction">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>手机短信开关：</dt>
                                <dd class="left text-l">
                                    <div class="switch">
                                        <div class="onoffswitch">
                                            <input name="short_switch" type="checkbox" class="onoffswitch-checkbox" id="short" <if condition="$mmtpl_info['mmt_short_switch'] eq 1 ">checked="checked" <else /></if>>
                                            <label class="onoffswitch-label" for="short">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>消息内容：</dt>
                                <dd class="left text-l">
                                    <textarea type="text" class="com-textarea1 radius3 boxsizing" name="short_content">{$mmtpl_info.mmt_short_content}</textarea>
                                </dd>
                            </dl>
                        </div>
                        <div class="btnbox3">
                            <button class="btn1 radius3 btn-margin" type="submit">确认提交</button>
                            <a class="btn1 radius3 btn-margin" href="{:U('Admin/Setting/notes')}">返回列表</a>
                        </div>
                    </form>
                </div>  -->
                <div class="hide2">
                    <form method="post" class="form-horizontal" name="wx_message" action="{:U('Admin/Setting/notes_edit')}" enctype="multipart/form-data">
                        <input type="hidden" name="code" value="{$mmtpl_info.mmt_code}" />
                        <input type="hidden" name="type" value="weixin" />
                        <div class="jurisdiction">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>微信开关：</dt>
                                <dd class="left text-l">
                                    <div class="switch">
                                        <div class="onoffswitch">
                                            <input name="wx_switch" type="checkbox"  class="onoffswitch-checkbox" id="weixin" <if condition="$mmtpl_info['mmt_wx_switch'] eq 1 ">checked="checked" <else /></if>>
                                            <label class="onoffswitch-label" for="weixin">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>消息内容：</dt>
                                <dd class="left text-l">
                                    <textarea type="text" class="com-textarea1 radius3 boxsizing" name="wx_content">{$mmtpl_info.mmt_wx_content}</textarea>
                                </dd>
                            </dl>
                        </div>
                        <div class="btnbox3">
                            <button class="btn1 radius3 btn-margin" type="submit">{$Think.lang.submit_btn}</button>
                            <a class="btn1 radius3 btn-margin" href="{:U('Admin/Setting/notes')}">返回列表</a>
                        </div>
                    </form>
                </div> 
                    
                </div> 
            </div> 
            
        </div>

        
    </div>
    <!--content结束-->
<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/alert.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/icon.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/tab.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/bootstrap.min.js"></script>
<script src="__PUBLIC__/js/moment-with-locales.js"></script>
<script src="__PUBLIC__/js/bootstrap-datetimepicker.js"></script>







