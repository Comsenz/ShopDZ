   		<div class="tipsbox radius3">
			<div class="tips boxsizing radius3">
				<div class="tips-titbox">
					<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
					<span class="open-span span-icon"><i class="open-icon"></i></span>
				</div>
			</div>
			<ol class="tips-list" id="tips-list">
				<li>1.银行列表设置，列表里显示的银行将会在用户添加银行信息时显示。</li>
			</ol>
		</div>
		<div class="iframeCon">
		<div class="iframeMain">
				<include file="spreadnav" />
			<div class="white-bg">
				<div class="table-titbox">
					<h1 class="table-tit left boxsizing">银行列表</h1>
					<ul class="operation-list left">
						<li class="add-li"><a href="<?=U('/Cms/bank')?>"><span><i href="#" class="operation-icon add-icon"></i></span></a></li>
						<li class="refresh-li"><a href="javascript:;" onclick="javascript:window.location.reload();"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
						<!--<li class="export-li"><i href="#" class="operation-icon export-icon"></i></li>-->
					</ul>
					<div  style="display:none" class="search-box1 right">
						<div class="search-boxcon boxsizing radius3 left">
							<div class="search-sele left">
								<input type="text" class="search-inp left boxsizing" value="会员账号" readonly="readonly"/>
								<span class="jtb-span-box boxsizing left"><i class="jtb-span search-jtb-icon"></i></span>
							</div>
							<input type="text" value="1574234234546464" class="search-inp-con boxsizing"/>
						</div>
						<input type="button"  value="搜索" class="search-btn right radius3"/>
						<ul class="search-sele-con radius3 layer-shadow remind-layer">
							<li class="active-sele">会员列表</li>
							<li>会员列表</li>
							<li>会员列表</li>
						</ul>
					</div>
				</div>
				
				<div class="comtable-box boxsizing">
					<table class="com-table">
						<thead>
							<tr>
								<th width="220">操作</th>
								<th width="180">银行名字</th>
								<th width="120">添加时间</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($list as $k => $v) {?>
							<tr>
								<td>
									<div class="table-iconbox">
										<div class="edit-iconbox left edit-sele boxsizing marginR10">
											<a class="edit-word table-icon-a" href ="javascript:;"  rel-href="<?=U('/Cms/delbank')?>" index="<?=$v['id']?>"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
										</div>
								
										<div class="edit-iconbox left edit-sele boxsizing marginR10">
											<a class="edit-word table-icon-a" href ="<?=U('/Cms/bank',array('id'=>$v['id']))?>"><i class="table-com-icon table-edit-icon"></i><span class="gap">编辑</span></a>
										</div>
									</div>
								</td>
								<td><?=$v['name']?></td>
								<td><?=date('Y-m-d H:i',$v['add_time']);?></td>
		
								<td></td>
							</tr>
							<?php }?>
							
							
							
						</tbody>
					</table>
				</div>
				<!--<div class="pagination boxsizing">
					<div class="eachPage-most left">
						<label for="" class="left">每页最多显示</label>
						<div class="most-box radius3 boxsizing left">
							<input type="text" name=""  value="10" readonly="readonly" class="most-inp left"/>
							<span class="most-span left"><i class="most-icon"></i></span>
						</div>
						<ul class="most-sele-con layer-shadow radius3">
							<li class="most-active-sele">15</li>
							<li>15</li>
							<li>15</li>
							<li>15</li>
						</ul>
					</div>
					<ul class="page-list">
						<li class="first-pagenation"><i class="first-icon"></i></li>
						<li class="prev-pagenation"><i class="prev-icon"></i></li>
						<li class="now-pagenation"><input type="text" value="1" class="now-inp radius3"/></li>
						<li class="all-pagenation">6页</li>
						<li class="next-pagenation"><i class="next-icon"></i></li>
						<li class="last-pagenation"><i class="last-icon"></li>
					</ul>
				</div>-->
			</div>
		</div>
		</div>
		<script type="text/javascript">
			$(function(){
				//添加会员提示
				$('.add-li').mousemove(function(){
					e=arguments.callee.caller.arguments[0] || window.event; 
					remindNeed($('.add-li'),e,'添加银行');
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
			    $('.export-li').mousemove(function(){
					e=arguments.callee.caller.arguments[0] || window.event; 
					remindNeed($('.add-li'),e,'导出');
				})
				$('.export-li').mouseout(function(){
			     	$('.tip-remind').remove();
			    });
				
				$('.delbank').click(function() {
					_this = this;
					showConfirm('确认删除？',function() {
						url = $(_this).attr('rel-href');
						id = $(_this).attr('index');
						getdata(url,{id:id},function(info) {
							if(info.status =='0'){
							        showSuccess(info.info,function(){
	    			    window.location.reload();
					});
							}else{
								showError(info.info);
							}
						});
					})
				});
					
			})
		</script>