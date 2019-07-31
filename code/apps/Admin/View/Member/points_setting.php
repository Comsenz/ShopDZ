<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
   <ol class="tips-list">
		<li>{$Think.lang.points_setting_tips}</li>
	</ol>
</div>
<!--内容开始-->
<div class="iframeCon">
<div class="iframeMain">
	<ul class="transverse-nav">
		<li><a href="{:U('Member/points')}"><span>{$Think.lang.points_detail}</span></a></li>
		<li class="activeFour"><a  href="#"><span>{$Think.lang.points_setting}</span></a></li>
		<li><a  href="{:U('Member/points_add')}"><span>{$Think.lang.points_add}</span></a></li>
	</ul>
	<div class="white-bg">
		<form method="post" class="form-horizontal" name="pointsForm" id="pointsForm" action="{:U('Member/points_setting')}">
		<input type="hidden" name="subval" id="subval" value="0"/>
		<div class="tab-conbox">
			<div class="jurisdiction boxsizing">
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.pset_register}：</dt>
					<dd class="left text-l">
						<input type="text" class="com-inp1 radius3 boxsizing" name="points_register" value="{$config.points_register}" localrequired=""/>
						<p class="remind1">{$Think.lang.pset_register_tips}</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.points_sign_in}：</dt>
					<dd class="left text-l">
						<input type="text" class="com-inp1 radius3 boxsizing" name="points_sign_in" value="{$config.points_sign_in}" localrequired=""/>
						<p class="remind1">{$Think.lang.pset_day_login_tips}</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.pset_order_comment}：</dt>
					<dd class="left text-l">
						<input type="text" class="com-inp1 radius3 boxsizing" name="points_order_comments" value="{$config.points_order_comments}" localrequired=""/>
						<p class="remind1">{$Think.lang.pset_order_comment_tips}</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.points_order_rate}：</dt>
					<dd class="left text-l">
						<input type="text" class="com-inp1 radius3 boxsizing" name="points_order_rate" value="{$config.points_order_rate}" style="width:90px;" localrequired=""/>&nbsp;%
						<p class="remind1">{$Think.lang.points_order_rate_tips}</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.points_order_max}：</dt>
					<dd class="left text-l">
						<input type="text" class="com-inp1 radius3 boxsizing" name="points_order_max" value="{$config.points_order_max}" localrequired=""/>
						<p class="remind1">{$Think.lang.points_order_max_tips}</p>
					</dd>
				</dl>
				
			</div>
			<div class="btnbox3 boxsizing">
				<a type="button" id="sub" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
			</div>
		</div>
		</form>
	</div>
</div>	
</div>	
<!--内容结束-->
<script type="text/javascript">
	$('#sub').click(function(){
    flag=checkrequire('pointsForm');
    if(!flag){
    	$('#subval').val(1);
       	$('#pointsForm').submit();
    }
    });
</script>