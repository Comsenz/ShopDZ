<!--<div class="tip-remind">收起提示</div>-->
<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">
        <li>1.设置微信菜单，可进行编辑菜单或添加新菜单。</li>
		<li> 2.微信登录地址为：<?php echo $wxlogin?></li>
    </ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
	<ul class="transverse-nav">
		<li class="activeFour"><a href="javascript:;"><span><?php echo $type == 'edit'? '编辑菜单':'新增菜单'; ?></span></a></li>
	</ul>
	<div class="white-bg ">
			<div class="tab-conbox">
			<form method="post" class="form-horizontal" name="memberForm" id="memberForm" action="{:U('Wx/menuedit')}" enctype="multipart/form-data">
						<input type="hidden" name="id" value="{$menu.id}"/>
						<input type="hidden" value="submit" name="form_submit">
			<div class="jurisdiction boxsizing">
				<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>菜单名称：</dt>
						<dd class="left text-l">
							<input type="text" name="name" class="com-inp1 radius3 boxsizing"  value="{$menu.name}" localrequired=""/>
							<p class="remind1">请输入菜单名称，菜单名称不能为空。</p>

					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>上级菜单：</dt>
					<dd class="left text-l">

						<div class="search-boxcon boxsizing radius3 borderR-none" style="display: inline-block">
							<select name="lid" id="refundsearch">

								<if condition="$menu.lid eq 0 ">
									<option value="0" selected="selected">一级菜单</option>
									<else />
									<option value="0">一级菜单</option>
								</if>

								<foreach name="menu_main" item="main">
									<if condition="$main.id eq $menu.lid ">
										<option value="{$main.id}" selected="selected">{$main.name}</option>
										<else />
										<option value="{$main.id}">{$main.name}</option>
									</if>
								</foreach>
							</select>

						</div>

						<p class="remind1">选择上级菜单，一级菜单为主菜单。</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>触发类型：</dt>
					<div class="search-boxcon boxsizing radius3 borderR-none" style="display: inline-block">
					<select name="type" id="menutype">
						<if condition="$menu.type eq 'view'">
							<option value="view" selected="selected">链接</option>
							<option value="click" >关键词</option>
							<else />
							<option value="view" >链接</option>
							<option value="click"  selected="selected">关键词</option>
						</if>
						</foreach>

					</select>
				</div>
				</dl>
				<dl class="juris-dl boxsizing" id="menu_url"<if condition="$menu.type eq 'click'"> style="display: none"</if>>
					<dt class="left text-r boxsizing"><span class="redstar">*</span>链接URL：</dt>
					<dd class="left text-l">
						<input type="text" name="url"  class="com-inp1 radius3 boxsizing" value="{$menu.url}"  id="wxurl" <if condition="$menu.type eq 'view'"> localrequired=""</if>/>
						<p class="remind1">触发事件为链接必填，请填写全地址。例如：http://www.baidu.com</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing" id="menu_keywords" <if condition="$menu.type eq 'view'"> style="display: none"</if>>
					<dt class="left text-r boxsizing"><span class="redstar">*</span>关键词：</dt>
					<dd class="left text-l">
						<input type="text" name="keywords" value="{$menu.keywords}" class="com-inp1 radius3 boxsizing"  id="wxkeywords" <if condition="$menu.type eq 'click'"> localrequired=""</if>/>
						<p class="remind1">触发事件为关键词，请填写已在关键词管理里面创建好的关键词。</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing ">
					<dt class="left text-r boxsizing">排序：</dt>
					<dd class="left text-l">
						<input type="text" name="order" value="{$menu.displayorder}" class="com-inp1 radius3 boxsizing"/>
					</dd>
				</dl>
				</div>

				<div class="btnbox3 boxsizing">
					<a type="button" id="menu_submit" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
					<a class="btn1 radius3 marginT10" href="{:U('Wx/menuOp')}">返回列表</a>
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
				if($("#menutype").val() == 'click'){
					$("#wxkeywords").attr("localrequired","");
					$("#wxurl").removeAttr("localrequired");

					$("#menu_keywords").show();
					$("#menu_url").hide();
				}else{
					$("#wxurl").attr("localrequired","");
					$("#wxkeywords").removeAttr("localrequired");
					$("#menu_keywords").hide();
					$("#menu_url").show();

				}
		}
	</script>





