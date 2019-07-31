
<!--<div class="tip-remind">收起提示</div>-->
<div class="tipsbox radius3">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		<li> 1.关键词不能重复添加。</li>
		<li> 2.关键词删除后为彻底删除。</li>
		<li> 2.关键词不启用微信用户触发后不能返回内容</li>
	</ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
	<ul class="transverse-nav">
		<li class="activeFour"><a href="javascript:;"><span>关键词设置</span></a></li>
	</ul>
	<div class="white-bg ">
		<div class="tab-conbox">
			<form method="post" class="form-horizontal" name="memberForm" id="memberForm" action="{:U('Wx/keywordsedit')}" enctype="multipart/form-data">
				<input type="hidden" name="id" value="{$keywords.id}"/>
				<input type="hidden" value="submit" name="form_submit">
				<div class="jurisdiction boxsizing">
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>关键词：</dt>
						<dd class="left text-l">
							<input type="text" name="keywords" class="com-inp1 radius3 boxsizing"  value="{$keywords.keyword}" localrequired=""/>
							<p class="remind1">请输入关键词内容。</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>回复类型：</dt>
						<div class="search-boxcon boxsizing radius3 borderR-none" style="display: inline-block">
							<select name="isimg" id="menutype">
								<if condition="$keywords.isimg eq '0'">
									<option value="0" selected="selected">文本回复</option>
									<option value="1" >图文回复</option>
									<else />
									<option value="0" >文本回复</option>
									<option value="1"  selected="selected">图文回复</option>
								</if>
								</foreach>
							</select>
						</div>
					</dl>
					<dl class="juris-dl boxsizing" id="keywords_text"<if condition="$keywords.isimg eq '1'"> style="display: none"</if>>
					<dt class="left text-r boxsizing"><span class="redstar">*</span>回复内容：</dt>
					<dd class="left text-l">
						<textarea type="text" name="content" id = "wxkeywords" class="com-textarea1 radius3 boxsizing"   <if condition="$keywords.isimg eq '0'"> localrequired=""</if>>{$keywords.content}</textarea>
						<p class="remind1">分享描述，将显示在前台微信分享中</p>
					</dd>
					</dl>
					<dl class="juris-dl boxsizing" id="keywords_img" <if condition="$keywords.isimg eq '0'"> style="display: none"</if>>
					<dt class="left text-r boxsizing"><span class="redstar">*</span>素材选择：</dt>
					<dd class="left text-l">
						<div class="search-boxcon boxsizing radius3 borderR-none" style="display: inline-block">
							<select name="tid" id="refundsearch" <if condition="$keywords.isimg eq '1'"> localrequired=""</if>>
							<empty name="img_text">
								<option value="1">暂无素材</option>
							<else />
								<foreach name="img_text" item="imgval">
									<if condition="$imgval.tid eq $keywords.tid ">
									<option value="{$imgval.tid}" selected="selected">{$imgval.modename}</option>
									<else />
										<option value="{$imgval.tid}" >{$imgval.modename}</option>
									</if>
								</foreach>
							</empty>
							</select>
						</div>
						<p class="remind1">素材不能为空</p>
					</dd>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>是否开启：</dt>
						<dd class="left text-l">
							<div class="switch-box">
								<input type="checkbox" name="states" id="switch-radio" class="switch-radio" <if condition="$keywords['states']">checked="true" </if>/>
								<span class="switch-half switch-open">ON</span>
								<span class="switch-half switch-close close-bg">OFF</span>
							</div>
							<p class="remind1">开启后微信分享使用配置信息</p>
						</dd>
					</dl>
				</div>
				<div class="btnbox3 boxsizing">
					<!-- <input type="submit" class="btn1 radius3 btn3-btnmargin left"  value="确认提交"> -->
					<a type="button" id="menu_submit" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
					<a class="btn1 radius3 marginT10" href="{:U('Wx/keywordsOp')}">返回列表</a>
				</div>
			</form>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">
	$('#menu_submit').click(function(){
            flag=checkrequire('memberForm');
            if(!flag){
                $('#memberForm').submit();
            }
        });
	$(function(){
		//simulateSelect('menutype',100,showtype);
		$('#menutype').change(showtype);
	});
	var showtype = function (){
		if($("#menutype").val() == '0'){

			$("#wxkeywords").attr("localrequired","");
			$("#refundsearch").removeAttr("localrequired");

			$("#keywords_text").show();
			$("#keywords_img").hide();
		}else{
			$("#refundsearch").attr("localrequired","");
			$("#wxkeywords").removeAttr("localrequired");
			$("#keywords_text").hide();
			$("#keywords_img").show();
		}
	}
</script>






