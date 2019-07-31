<!--内容开始-->
<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
   	<ol class="tips-list">
		{$Think.lang.dist_tip_content}
	</ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
	<ul class="transverse-nav">
		<li class="activeFour"><a  href="#"><span><if condition="$info.area_id eq ''">{$Think.lang.district_add}<else/>{$Think.lang.district_edit}</if></span></a></li>
	</ul>
	<div class="white-bg">

		<div class="tab-conbox">
		<form method="post" class="form-horizontal" name="areaform" id="areaform"
 action="{:U('District/save',array('area_id',$info.area_id))}">
			<input type="hidden" name="area_id" id="area_id" value="{$info.area_id}" />
			<input type="hidden" name="parent_id" id="_area" value="{$info.area_parent_id}">
			<input type="hidden" name="area_deep" id="area_deep" value="{$info.area_deep}">
			<div class="jurisdiction boxsizing">
				<dl class="juris-dl boxsizing">
		            <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.dist_name}：</dt>
		            <dd class="left text-l">
		                <input type="text" class="com-inp1 radius3 boxsizing" value="{$info.area_name}" name="area_name" id="title" localrequired="">
		                <p class="remind1">{$Think.lang.dist_name_tips}</p>
		            </dd>
		        </dl>
		        <dl class="juris-dl boxsizing">
		            <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.dist_parent}：</dt>
		            <dd class="left text-l">
		                <!--区域选择，默认下级隐藏，选择上级之后显示下级-->
		                <div class="zoom">
		                	 <input id="region" name="region" type="hidden" value="{$info.area_parent_name}" >
		                </div>
		               
		                <p class="remind1">{$Think.lang.area_parent_tips}</p>
		            </dd>
		        </dl>
			</div>
			<div class="btnbox3 boxsizing">
				<!-- <input type="button" id='areaform_setting' class="btn1 radius3 btn3-btnmargin left"  value="确认提交"> -->
				<a type="button" id="areaform_setting" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
				<a class="btn1 radius3 marginT10" href="{:U('setting/district')}">{$Think.lang.return_list}</a>
			</div>
		</form>
		</div>
	</div>
</div>	
</div>	

<script>
$(function(){

	$('#areaform_setting').click(function(){
        flag=checkrequire('areaform');
        if(!flag){
           $('#areaform').submit();
        }
    });
    $("#region").nc_region({src:'db',show_deep:3,width:150});
});
</script>
