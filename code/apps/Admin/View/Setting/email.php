<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/plugins/iCheck/custom.css" rel="stylesheet">
  	
<!--content开始-->
<div class="content">
	<!--提示框开始-->
    <div class="alertbox1">
        <div class="alert-con radius3">
            <button class="closebtn1">×</button>
            <div class="alert-con-div">
                <h1 class="fontface alert-tit1">&nbsp;操作提示</h1>
                <ol>
                    <li>1.填写邮件服务器相关参数，并点击“测试”按钮进行效验，保存后生效。</li>
                    <li>2.如使用第三方提供的邮件服务器，请认真阅读服务商提供的相关帮助文档。</li>
                </ol>
            </div>
        </div>
    </div>
    <!--提示框结束-->
    <form method="post" class="form-horizontal" name="settingForm" action="{:U('Admin/Setting/email')}" enctype="multipart/form-data">
		<div class="jurisdiction">
			<dl class="juris-dl boxsizing">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>SMTP服务器：</dt>
				<dd class="left text-l">
					<input type="text" class="com-inp1 radius3 boxsizing" value="{$email_config.email_host}" name="email_host" id="email_host">
					<p class="remind1">设置 SMTP 服务器的地址，如 smtp.163.com</p>
				</dd>
			</dl>
			<dl class="juris-dl boxsizing">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>SMTP端口：</dt>
				<dd class="left text-l">
					<input type="text" class="com-inp1 radius3 boxsizing" value="{$email_config.email_port}" name="email_port" id="email_port"> 
					<p class="remind1">设置 SMTP 服务器的端口，默认为 25</p>
				</dd>
			</dl>
			<dl class="juris-dl boxsizing">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>安全协议：</dt>
				<dd class="left text-l">
					<!--<input type="text" class="com-inp1 radius3 boxsizing" value="{$email_config.email_port}" name="email_port" id="email_port">-->
					<div class="radio i-checks">
                        <label>
                            <input type="radio" value="1" name="email_secure" <if condition="$email_config.email_secure eq 1 ">checked="checked" <else /> </if>> <i></i> 开启</label>
                        <label>
                            <input type="radio" value="0" name="email_secure" <if condition="$email_config.email_secure eq 0 ">checked="checked" <else /> </if>> <i></i> 关闭 </label>
                    </div> 
				</dd>
			</dl>
			<dl class="juris-dl boxsizing">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>发信人邮件地址：</dt>
				<dd class="left text-l">
					<input type="text" class="com-inp1 radius3 boxsizing" value="{$email_config.email_addr}" name="email_addr" id="email_addr">
					<p class="remind1">使用 SMTP 协议发送的邮件地址，如 shopdz@163.com</p>
				</dd>
			</dl>
			<dl class="juris-dl boxsizing">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>SMTP身份证用户名：</dt>
				<dd class="left text-l">
					<input type="text" class="com-inp1 radius3 boxsizing" value="{$email_config.email_id}" name="email_id" id="email_id">
					<p class="remind1">如 admin</p>
				</dd>
			</dl>
			<dl class="juris-dl boxsizing">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>SMTP身份证密码：</dt>
				<dd class="left text-l">
					<input type="password" class="com-inp1 radius3 boxsizing" value="{$email_config.email_pass}" name="email_pass" id="email_pass">
					<p class="remind1">shopdz@163.com邮件的密码，如 123456</p>
				</dd>
			</dl>
			<dl class="juris-dl boxsizing">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>测试接收的邮件地址：</dt>
				<dd class="left text-l">
					<input type="text" class="com-inp1 radius3 boxsizing" value="" name="email_test" id="email_test">
                    <button class="btn2 test-btn" type="button" name="send_test_email" id="send_test_email">测试 </button>
				</dd>
			</dl>
		</div>
		<div class="btnbox3">
			<button class="btn1 radius3 btn-margin" type="submit">{$Think.lang.submit_btn}</button>
		</div>
	</form>
</div>
<!--content结束-->



<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/alert.js"></script>
<script src="__PUBLIC__/js/bootstrap.min.js?v=3.3.6"></script>
<script src="__PUBLIC__/js/plugins/iCheck/icheck.min.js"></script>
<script>
    $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>
<script>
$(document).ready(function(){
    $('#send_test_email').click(function(){
        $.ajax({
            type:'POST',
            url:"email_testing",
            data:'email_host='+$('#email_host').val()+'&email_port='+$('#email_port').val()+'&email_secure='+$('input[name=email_secure]:checked').val()+'&email_addr='+$('#email_addr').val()+'&email_id='+$('#email_id').val()+'&email_pass='+$('#email_pass').val()+'&email_test='+$('#email_test').val(),
            success:function(data){
                if(data.code == 200){
                    //alert(data.msg);
                    swal({title:''+data.msg+'',text:"邮件发送成功。",type:"success"})
                }else{
                    swal({title:''+data.msg+'',text:"邮件发送失败。",type:"error"});
                }
            },
            dataType:'json'
        });
    });
});
</script>