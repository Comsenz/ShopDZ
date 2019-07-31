<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">
        <li>{$Think.lang.points_add_tips}</li>
    </ol>
</div>

<!--内容开始-->
<div class="iframeCon">
<div class="iframeMain">
	<ul class="transverse-nav">
		<li><a href="{:U('Member/points')}"><span>{$Think.lang.points_detail}</span></a></li>
		<li><a  href="{:U('Member/points_setting')}"><span>{$Think.lang.points_setting}</span></a></li>
		<li class="activeFour"><a  href="#"><span>{$Think.lang.points_add}</span></a></li>
	</ul>
	<div class="white-bg">
		<form method="post" class="form-horizontal" name="pointsForm" id="pointsForm" action="{:U('Member/points_add')}">
		<div class="tab-conbox">
			<div class="jurisdiction boxsizing">
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.member_name}：</dt>
					<dd class="left text-l">
						<input type="hidden" name="points_member_id"/>
						<input type="hidden" value="submit" name="form_submit">
						<input type="text" class="com-inp1 radius3 boxsizing" localrequired="" name="pl_membername"/>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing">{$Think.lang.points_change_type}：</dt>
					<dd class="left text-l">
						<div class="search-boxcon boxsizing radius3 left borderR-none">
						<select  name="operatetype" id="operatetype">
							<option value="1">{$Think.lang.addition}</option>
							<option value="2">{$Think.lang.reduce}</option>
						</select>
						</div>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.points_value}：</dt>
					<dd class="left text-l">
						<input type="number" class="com-inp1 radius3 boxsizing" localrequired="" name="pl_points"/>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.operation_reasons}：</dt>
					<dd class="left text-l">
						<textarea class="com-textarea1 radius3 boxsizing" localrequired="" name="pl_desc"></textarea>
					</dd>
				</dl>
				
			</div>
			<div class="btnbox3 boxsizing">
				<a type="button" id="form_btn" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
			</div>
		</div>
		</form>
	</div>
</div>	
</div>	
<!--内容结束-->
<script type="text/javascript">
	$('#form_btn').click(function(){
    flag=checkrequire('pointsForm');
    if(!flag){
       $('#pointsForm').submit();
    }
    });
</script>
