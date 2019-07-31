
		<!--<div class="tip-remind">收起提示</div>-->
		<div class="tipsbox">
			<div class="tips boxsizing radius3">
				<div class="tips-titbox">
					<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
					<span class="open-span span-icon"><i class="open-icon"></i></span>
				</div>
			</div>
			<ol class="tips-list" id="tips-list">
				<li>1.网站全局基本设置，商城及其他模块相关内容在其各容在其各自栏目设置项其各自栏目设置项内进行操作。</li>
			</ol>
		</div>
		<div class="iframeCon">
		<div class="iframeMain">
			<ul class="transverse-nav">
				<li class="activeFour"><a href="javascript:;"><span>管理员列表</span></a></li>
			</ul>
			<!--{
				选项卡注意事项：
				1，把 white-shadow tab-content 或者 white-shadow2 类去掉改为white-bg
				2，复制nav.php下的 class="option" 放置到<div class="table-titbox"> 下面
			}-->
			<div class="white-bg">
				<div class="table-titbox">
					<div class="option">
						<h1 class="table-tit left boxsizing">会员列表</h1>
						<ul class="operation-list left">
							<li class="add-li"><a href="#"><span><i href="#" class="operation-icon add-icon"></i></span></a></li>
							<li class="refresh-li"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
							<li class="export-li"><a href="#"><span><i href="#" class="operation-icon export-icon"></i></span></a></li>
							<li class="dele-li"><a href="#"><span><i href="#" class="operation-icon op-dele-icon"></i></span></a></li>
							<li class="back-li"><a href="#"><span><i href="#" class="operation-icon op-back-icon"></i></span></a></li>
							<li class="creat-li"><a href="#"><span><i href="#" class="operation-icon creat-icon"></i></span></a></li>
						</ul>
					</div>
				</div>
				
				<div class="comtable-box boxsizing">
					<table class="com-table">
						<thead>
							<tr>
								<th width="100" class="text-l">
									<div class="button-holder">
										<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1" name="radio-1-set" class="radio-1 regular-radio" /><label for="radio-1"></label><span class="radio-word">全选</span></p>
									</div>
								</th>
								
								<th width="200">操作</th>
								<th width="180">管理员</th>
								<th width="180">权限组</th>
								<th width="280">上次登录时间</th>
								<th width="120">IP</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach($lists as $key => $v) { ?>
									<td class="text-l">
										<div class="button-holder">
											<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-1" name="radio-1-set" class="radio-1-1 regular-radio" /><label for="radio-1-1"></label><span class="radio-word"></span></p>
										</div>
									</td>
									<td>
										<div class="table-iconbox">
											<div class="edit-iconbox left edit-sele marginR10">
												<a class="edit-word">编辑</a>
											</div>
											<div class="table-icon left setting-sele-par">
												<div class="setting-sele left radius3 boxsizing">
													<span class="setting-word"><i class="table-com-icon table-setting-icon"></i>查看</span>
													<span class="jtb-span-box boxsizing"><i class="jtb-span setting-jtb-icon"></i></span>
												</div>
												<ul class="setting-sele-con remind-layer">
													<li class="active-sele">会员列表</li>
													<li>会员列表</li>
													<li>会员列表</li>
												</ul>
											</div>
											
										</div>
									</td>
									<td><?=$v['username']; ?></td>
									<td><?=$roleAll[$v['groupid']]['name']; ?></td>
									<td><?=date('Y-m-d H:i:s',$v['lastdateline']); ?></td>
									<td><?=$v['ip']; ?></td>
									<td></td>
								<?php } ?>
							</tr>
							<tr>
								<td class="text-l">
									<div class="button-holder">
										<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-2" name="radio-1-set" class="radio-1-1 regular-radio" /><label for="radio-1-2"></label><span class="radio-word"></span></p>
									</div>
								</td>
								<td>
									<div class="table-iconbox">
										<div class="table-icon left  setting-sele-par">
											<div class="setting-sele left radius3 boxsizing">
												<span class="setting-word"><i class="table-com-icon table-setting-icon"></i>查看</span>
												<span class="jtb-span-box boxsizing"><i class="jtb-span setting-jtb-icon"></i></span>
											</div>
											<ul class="setting-sele-con remind-layer">
												<li class="active-sele">会员列表</li>
												<li>会员列表</li>
												<li>会员列表</li>
											</ul>
										</div>
										<div class="edit-iconbox left edit-sele radius3 boxsizing">
											<a class="edit-word">编辑</a>
										</div>
									</div>
								</td>
								<td>Data</td>
								<td>Data</td>
								<td>Data</td>
								<td>Data</td>
								<td>
									<div class="state-btn no-state">
										<i class="no-icon"></i>
										<span>关闭</span>
									</div>
								</td>
								<td></td>
							</tr>
							<tr>
								<td class="text-l">
									<div class="button-holder">
										<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-3" name="radio-1-set" class="radio-1-1 regular-radio" /><label for="radio-1-3"></label><span class="radio-word"></span></p>
									</div>
								</td>
								<td>
									<div class="table-iconbox">
										<div class="table-icon left  setting-sele-par">
											<div class="setting-sele left radius3 boxsizing">
												<span class="setting-word"><i class="table-com-icon table-setting-icon"></i>查看</span>
												<span class="jtb-span-box boxsizing"><i class="jtb-span setting-jtb-icon"></i></span>
											</div>
											<ul class="setting-sele-con remind-layer">
												<li class="active-sele">会员列表</li>
												<li>会员列表</li>
												<li>会员列表</li>
											</ul>
										</div>
										<div class="edit-iconbox left edit-sele radius3 boxsizing">
											<a class="edit-word">编辑</a>
										</div>
									</div>
								</td>
								<td>Data</td>
								<td>Data</td>
								<td>Data</td>
								<td>Data</td>
								<td>
									<div class="state-btn no-state">
										<i class="no-icon"></i>
										<span>关闭</span>
									</div>
								</td>
								<td></td>
							</tr>
							<tr>
								<td class="text-l">
									<div class="button-holder">
										<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-4" name="radio-1-set" class="radio-1-1 regular-radio" /><label for="radio-1-4"></label><span class="radio-word"></span></p>
									</div>
								</td>
								<td>
									<div class="table-iconbox">
										<div class="edit-iconbox left edit-sele marginR10">
											<a class="edit-word">编辑</a>
										</div>
										<div class="table-icon left setting-sele-par">
											<div class="setting-sele left radius3 boxsizing">
												<span class="setting-word"><i class="table-com-icon table-setting-icon"></i>查看</span>
												<span class="jtb-span-box boxsizing"><i class="jtb-span setting-jtb-icon"></i></span>
											</div>
											<ul class="setting-sele-con remind-layer">
												<li class="active-sele">会员列表</li>
												<li>会员列表</li>
												<li>会员列表</li>
											</ul>
										</div>
										
									</div>
								</td>
								<td>Data</td>
								<td>Data</td>
								<td>Data</td>
								<td>Data</td>
								<td>
									<div class="state-btn no-state">
										<i class="no-icon"></i>
										<span>关闭</span>
									</div>
								</td>
								<td></td>
							</tr>
							<tr>
								<td class="text-l">
									<div class="button-holder">
										<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-5" name="radio-1-set" class="radio-1-1 regular-radio" /><label for="radio-1-5"></label><span class="radio-word"></span></p>
									</div>
								</td>
								<td>
									<div class="table-iconbox">
										<div class="edit-iconbox left edit-sele marginR10">
											<a class="edit-word">编辑</a>
										</div>
										<div class="table-icon left setting-sele-par">
											<div class="setting-sele left radius3 boxsizing">
												<span class="setting-word"><i class="table-com-icon table-setting-icon"></i>查看</span>
												<span class="jtb-span-box boxsizing"><i class="jtb-span setting-jtb-icon"></i></span>
											</div>
											<ul class="setting-sele-con remind-layer">
												<li class="active-sele">会员列表</li>
												<li>会员列表</li>
												<li>会员列表</li>
											</ul>
										</div>
										
									</div>
								</td>
								<td>Data</td>
								<td>Data</td>
								<td>Data</td>
								<td>Data</td>
								<td>
									<div class="state-btn no-state">
										<i class="no-icon"></i>
										<span>关闭</span>
									</div>
								</td>
								<td></td>
							</tr>
							<tr>
								<td class="text-l">
									<div class="button-holder">
										<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-6" name="radio-1-set" class="radio-1-1 regular-radio" /><label for="radio-1-6"></label><span class="radio-word"></span></p>
									</div>
								</td>
								<td>
									<div class="table-iconbox">
										<div class="edit-iconbox left edit-sele marginR10">
											<a class="edit-word">编辑</a>
										</div>
										<div class="table-icon left setting-sele-par">
											<div class="setting-sele left radius3 boxsizing">
												<span class="setting-word"><i class="table-com-icon table-setting-icon"></i>查看</span>
												<span class="jtb-span-box boxsizing"><i class="jtb-span setting-jtb-icon"></i></span>
											</div>
											<ul class="setting-sele-con remind-layer">
												<li class="active-sele">会员列表</li>
												<li>会员列表</li>
												<li>会员列表</li>
											</ul>
										</div>
										
									</div>
								</td>
								<td>Data</td>
								<td>Data</td>
								<td>Data</td>
								<td>Data</td>
								<td>
									<div class="state-btn no-state">
										<i class="no-icon"></i>
										<span>关闭</span>
									</div>
								</td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="pagination boxsizing">
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
				</div>
			</div>
		</div>
		</div>
		<script type="text/javascript">
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
					remindNeed($('.refresh-li'),e,'刷新');
				})
				$('.refresh-li').mouseout(function(){
			     	$('.tip-remind').remove();
			    });
			    $('.export-li').mousemove(function(){
					e=arguments.callee.caller.arguments[0] || window.event; 
					remindNeed($('.export-li'),e,'导出');
				})
				$('.export-li').mouseout(function(){
			     	$('.tip-remind').remove();
			    });
			    $('.dele-li').mousemove(function(){
					e=arguments.callee.caller.arguments[0] || window.event; 
					remindNeed($('.dele-li'),e,'批量删除');
				})
				$('.dele-li').mouseout(function(){
			     	$('.tip-remind').remove();
			    });
			    $('.back-li').mousemove(function(){
					e=arguments.callee.caller.arguments[0] || window.event; 
					remindNeed($('.back-li'),e,'返回');
				})
				$('.back-li').mouseout(function(){
			     	$('.tip-remind').remove();
			    });
			})
		</script>