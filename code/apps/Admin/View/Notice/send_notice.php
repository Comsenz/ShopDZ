<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">
        <li>1.对固定会员或全站会员发送消息。</li>
    </ol>
</div>
<!--内容开始-->
<div class="iframeCon">
<div class="iframeMain">
   <ul class="transverse-nav">
       <li  >
             <a href="<?php echo U('Notice/template');?>"><span>消息模板</span></a>
         </li>
	   <li  class="activeFour"   >
             <a href="<?php echo U('Notice/send_notice');?>"><span>发送通知</span></a>
         </li>
</ul>
    <div class="white-bg">
        
        <div class="tab-conbox">
            <form method="post" class="form-horizontal" id="setting_form1" enctype="multipart/form-data" action="{:U('Admin/Notice/send_notice')}">
            <input name="form_submit"   type="hidden"  value="submit">
                <div class="jurisdiction boxsizing">
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>发送方式：</dt>
                        <dd class="left text-l">
                            <div class="button-holder" localrequired="">
                                <div class="radio i-checks">
                                    <p class="radiobox"><input type="radio" id="radio-1-a" name="type" value="on" checked class="regular-radio"/><label for="radio-1-a"></label><span class="radio-word">群发</span></p>
                                    <p class="radiobox"><input type="radio" id="radio-1-b" name="type" value="off" class="regular-radio"/><label for="radio-1-b"></label><span class="radio-word">指定会员</span></p>
                                </div>
                                <p class="remind1">选择群发，则向站内所有会员发送</p>
                            </div>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing" id="send_member" style="display:none;">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>会员名：</dt>
                        <dd class="left text-l">
<!--                             <div class=" boxsizing radius3">
                                <select class="left addFocus-sele" name="send_type" id="send_type">
                                    <option value="member_mobile">用户名</option>
                                    <option value="member_id">用户ID</option>
                                </select>
                            </div> -->
                            <input type="text" localrequired="" name="members" id="members" class="com-inp1 radius3 boxsizing">
                                <p class="remind1">填写需要发送的会员账号，如多个会员请用英文‘,’分隔</p>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>消息标题：</dt>
                        <dd class="left text-l">
                            <input type="text" localrequired="" name="title" class="com-inp1 radius3 boxsizing success-left">
                                <p class="remind1">消息的标题</p>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>消息内容：</dt>
                        <dd class="left text-l">
                            <textarea type="text" name="content" localrequired="" class="com-textarea1 radius3 boxsizing"></textarea>
                                <p class="remind1">消息的内容，支持HTML</p>
                        </dd>
                    </dl>
                </div>
                <div class="btnbox3 boxsizing">
                    <a type="button" id="wx_submit" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
                </div>
                </form>
        </div>

    </div>
</div>  
</div>  
<!--内容结束-->
<script>
    $('#wx_submit').click(function(){
        if($('input[name="type"]:checked').val() == 'off'){
            $('#members').attr('localrequired','');
            // $('#send_type').attr('localrequired','');
        }else{
            $('#members').removeAttr('localrequired');
            // $('#send_type').removeAttr('localrequired');
        }
        flag=checkrequire('setting_form1');
        if(!flag){
            $('#setting_form1').submit();
        }
    });

    $('.regular-radio').click(function(){
        _this = this;
        $('#send_member').hide();
        if($(_this).val() == 'off'){
             $('#send_member').show();
        }else if($(_this).val() == 'on'){
            $('#send_member').hide();
        }
    })
</script>
