
<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">

        <li> 1.启用后支持使用微信帐号来登录</li>
        <li> 2.微信开放平台相应的AppID</li>
        <li> 3.微信开放平台相应的AppSecret</li>
    </ol>
</div>

<div class="iframeCon">
<div class="iframeMain">
        <!--提示框结束-->
        <!--切换内容-->
    <ul class="transverse-nav">
        <li class="activeFour"><a href="javascript:;"><span>微信登录</span></a></li>
    </ul>
        <div id="sidebar-tab" class="white-bg">
            <div  class="tab-conbox">

                    <form method="post" class="form-horizontal" id="setting_form1" action="{:U('Admin/Wx/wxloginOp')}">
                        <div class="jurisdiction boxsizing"">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>微信登录：</dt>
                                <dd class="left text-l">
                                    <div class="switch">

                                        <div class="switch-box">
                                            <input type="checkbox" <if condition="$config['wx_login'] eq 'on'">checked="true" </if> name="wx_login" class="switch-radio" id="example1">
                                            <span class="switch-half switch-open">ON</span>
                                            <span class="switch-half switch-close close-bg">OFF</span>
                                        </div>
                                    </div>
                                    <p class="remind1">启用后支持使用微信帐号来登录</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>应用标识：</dt>
                                <dd class="left text-l">
                                    <input type="text" name="wx_AppID" class="com-inp1 radius3 boxsizing" placeholder="" value="{$config['wx_AppID']}" localrequired="">
                                    <a target="_blank" class="getpid" href="https://mp.weixin.qq.com/">在线申请</a>
                                    <p class="remind1">微信开放平台相应的AppID</p>
                                </dd>

                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>应用密钥：</dt>
                                <dd class="left text-l">
                                    <input type="text" name="wx_AppSecret" class="com-inp1 radius3 boxsizing" placeholder="" value="{$config['wx_AppSecret']}" localrequired="">
                                    <p class="remind1">微信开放平台相应的AppSecret</p>
                                </dd>

                            </dl>
                        </div>
                        <div class="btnbox3 boxsizing">
                            <input type="hidden" name="sub" value="1">
                            <a type="button" id="wx_submit" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
                        </div>
                    </form>


            </div> 
        </div>
    </div>
    </div>
    <script type="text/javascript">
        $('#wx_submit').click(function(){
           $('#setting_form1').submit();
        });
        $(function(){
            //添加会员提示
            $('.add-li').mousemove(function(){
                e=arguments.callee.caller.arguments[0] || window.event;
                remindNeed($('.add-li'),e,'添加会员');
            })
            $('.add-li').mouseout(function(){
                $('.tip-remind').remove();
            });
            $('.refresh-li').mousemove(function(){
                e=arguments.callee.caller.arguments[0] || window.event;
                remindNeed($('.add-li'),e,'刷新');
            })
            $('.refresh-li').mouseout(function(){
                $('.tip-remind').remove();
            });

        })
    </script>
    <!--content结束-->








