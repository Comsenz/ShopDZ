

		<title>新增权限组</title>
		<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
		<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>
		<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>

		<div class="content">
			<div class="jurisdiction">
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>权限组：</dt>
					<dd class="left text-l">
						<input type="text" class="com-inp1 radius3 boxsizing"/>
						<p class="remind1">这是提示文字</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar"></span>权限设置：</dt>
					<dd class="left text-l">
						<input type="checkbox" class="check-all-btn check-top"/>
						<label>商品管理</label>
						<p class="remind1">这是提示文字</p>
					</dd>
				</dl>
			</div>
			<div class="tablebox1 check-table">
				<div class="table-tit">
					<h1 class="tabtit1 left">权限操作设置详情</h1>
				</div>
				<table class="tablelist1" id="tablelist1">
					<tr>
						<td class="contr" style="width:15%;">
							<div class="inp-box">
								<input type="checkbox" class="control2 controls check-top"/>
								<label>商品规格</label>
							</div>
						</td>
						<td style="width:85%;" id="shanpin1" class="shanpin">
							<div class="left inp-box">
								<input type="checkbox" id="male1" value="1" name="checkname" class="check-top"/>
								<label for="male1">商品管理</label>
							</div>
							<div class="left inp-box">
								<input type="checkbox" id="male2" value="2" name="checkname" class="check-top"/>
								<label for="male2">发布商品商品</label>
							</div>
							<div class="left inp-box">
								<input type="checkbox" id="male3" value="3" name="checkname" class="check-top"/>
								<label for="male3">商品分类商品管理商品</label>
							</div>
							<div class="left inp-box">
								<input type="checkbox" id="male4" value="4" name="checkname" class="check-top"/>
								<label for="male4">商品规格商品规格</label>
							</div>
							<div class="left inp-box">
								<input type="checkbox" id="male4" value="4" name="checkname" class="check-top"/>
								<label for="male4">商品规格商品规格</label>
							</div>
							<div class="left inp-box">
								<input type="checkbox" id="male4" value="4" name="checkname" class="check-top"/>
								<label for="male4">商品规格商品规格</label>
							</div>
							<div class="left inp-box">
								<input type="checkbox" id="male4" value="4" name="checkname" class="check-top"/>
								<label for="male4">商品规格商品</label>
							</div>
							<div class="left inp-box">
								<input type="checkbox" id="male4" value="4" name="checkname" class="check-top"/>
								<label for="male4">商品规格</label>
							</div>
						</td>
					</tr>
					<tr>
						<td class="contr">
							<div class="inp-box">
								<input type="checkbox" id="male6" class="control3 controls check-top"/>
								<label for="male6">商品管理商品管理商品</label>
							</div>
						</td>
						<td id="shanpin2" class="shanpin">
							<div class="left inp-box">
								<input type="checkbox" id="male7" value="1" name="checkname" class="check-top"/>
								<label for="male7">商品管理商品管理</label>
							</div>
							<div class="left inp-box">
								<input type="checkbox" id="male8" value="2" name="checkname" class="check-top"/>
								<label for="male8">发布商品商品</label>
							</div>
							<div class="left inp-box">
								<input type="checkbox" id="male9" value="3" name="checkname" class="check-top"/>
								<label for="male9">商品分类</label>
							</div>
							<div class="left inp-box">
								<input type="checkbox" id="male10" value="4" name="checkname" class="check-top"/>
								<label for="male10">商品规格</label>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div class="btnbox3">
				<a class="btn1 radius3 footbtn">确认提交</a>
				<a class="btn1 radius3 footbtn">返回列表</a>
			</div>
		</div>
		<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
		<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
		 
		
		<script type="text/javascript">
		$(function(){
			$('.check-all-btn').click(function(){
				$('.check-table').find('input').prop("checked", $(this).prop("checked"));
			})

			$(".controls").click(function(){
			    $($(this).parents("td").siblings().find("input")).prop("checked", $(this).prop("checked"));
		    });
			
			$(".tablelist1 tr td").each(function(i){
				var idName = $(this).attr("id");
				if(idName != undefined){
					$("#"+idName+" input").click(function(){
						var checkedLen = $("#"+idName+" input[name='checkname']:checked").length;
						var inputLen = $("#"+idName+" input").length;
						if(checkedLen == inputLen){
							$("#"+idName).prev().find("input").prop("checked",true);
						}else{
							$("#"+idName).prev().find("input").prop("checked",false);
						};
					});
				}
			});

			$(".shanpin input,.controls").click(function(){
				var inputLen = $("#tablelist1 input").length;
				var len2 = $("input[type='checkbox']:checked").length;
				if(len2 >=inputLen ){
					$(".check-all-btn").prop("checked",true);
				}else{
					$(".check-all-btn").prop("checked",false);
				};
			});
		})
			
		</script>
		
		
		
		

