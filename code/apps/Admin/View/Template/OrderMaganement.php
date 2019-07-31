


	<meta charset="utf-8">
	<title>订单管理</title>
  	<link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css" />
  	<link rel="stylesheet" href="__PUBLIC__/css/bootstrap-datetimepicker.css" />
  	<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
  	<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
  	<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>

	<div class="content">
		<!--提示框开始-->
		<div class="alertbox1">
			<div class="alert-con radius3">
				<button class="closebtn1">×</button>
            	<div class="alert-con-div">
            		<h1 class="fontface alert-tit1">&nbsp;操作提示</h1>
            		<ol>
            			<li>提示文字信息提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字</li>
            			<li>提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字提示文字信息</li>
            		</ol>
            		<a href="#" class="alert-more">了解更多</a></div>
			</div>
        </div>
        <!--提示框结束-->
        <!--切换内容-->
       
		<div id="sidebar-tab" class="sidebar-tab"> 
			<div id="tab-title" class="tab-title"> 
				<!--<h3><span class="selected">最新评论</span><span>近期热评</span><span>随机文章</span></h3> -->
				<ul>
					<li class="selected">全部</li>
					<li>待付款</li>
					<li>待发货</li>
					<li>已发货</li>
					<li>已完成</li>
					<li>已取消</li>
				</ul>
			</div> 
			<div id="tab-content" class="sidebar-con"> 
				<div>
					<div class="tablebox1">
						<div class="table-tit">
							<h1 class="tabtit1 left">商品订单列表</h1>
						</div>
						<div class="clear-box">
							<div class="form-group left time2">
				            	<label class="font-noraml left time-label">下单时间</label>
				                <div class='input-group date left time-width'>
				                   <!-- <input type='text' class="form-control datetimepicker2"/>-->
				                    <input type="text" class="form-control com-inp1 datetimepicker2 time-inp1" name="start" value=""/>
	                                <span class="left timeto">--</span>
	                                <input type="text" class="form-control com-inp1 datetimepicker3 time-inp1"  name="end" value=""/>
				                    
				                </div>
				            </div>
						
	                        <div class="right search-box1">
	                        	<select class="sele-com1 search-sele boxsizing">
	                        		<option>选项选项</option>
	                        		<option>选项二</option>
	                        		<option>选项三</option>
	                        		<option>选项四</option>
	                        	</select>
	                        	<input type="text" placeholder="搜索相关数据..." class="search-inp inp-com1 boxsizing"/>
	                        	<a href="#" class="btn2 search-btn radius3">搜索</a>
	                        </div>
						</div>
			            
						<table class="tablelist1 check-table">
							<tr>
								<th style="width: 10%;">订单号</th>
								<th style="width: 10%;">买家账号</th>
								<th style="width: 8%;">收货人</th>
								<th style="width: 10%;">下单时间</th>
								<th style="width: 8%;">订单状态</th>
								<th style="width: 8%;">订单金额</th>
								<th style="width: 8%;">支付方式</th>
								<th style="width: 10%;">支付单号</th>
								<th style="width: 8%;">支付时间</th>
								<th style="width: 8%;">退款金额</th>
								<th style="width: 12%;">操作</th>
							</tr>
							<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>微信</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-search-plus icon-img" title="查看"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
									</div>
								</td>
							</tr>
							<!--<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>微信</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-search-plus icon-img" title="查看"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-truck icon-img" title="发货"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-check-square-o icon-img" title="保存"></a>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>支付宝</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-search-plus icon-img" title="查看"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-edit icon-img" title="编辑"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-eye icon-img" title="预览"></a>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>支付宝</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-trash-o icon-img" title="删除"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-cog icon-img" title="设置"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-share-square-o icon-img" title="退款"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-gift icon-img" title="发优惠券"></a>
										</div>
									</div>
								</td>
							</tr>-->
						</table>
						<ul class="paging">
							<li class="fontface2 prev-page prev-page-end"></li>
							<li class="fontface2 prevbtn prev-end"></li>
							<li>|</li>
							<li class="page-jump radius3">1</li>
							<li class="fontface2 page-common"><span>共</span>&nbsp;<span>2</span>&nbsp;<span>页</span></li>
							<li>|</li>
							<li class="fontface2 nextbtn next-end"></li>
							<li class="fontface2 next-page next-page-end"></li>
							<li class="choice-number">
								<select class="choice-number-sele">
									<option>10</option>
									<option>20</option>
									<option>30</option>
									<option>40</option>
								</select>
							</li>
							<li><span>1</span>-<span>14</span></li>
							<li class="page-common"><span>共</span><span>28</span><span>条</span></li>
						</ul>
					</div>
					
				</div> 
				<div class="hide2">
					<div class="tablebox1">
						<div class="table-tit">
							<h1 class="tabtit1 left">商品订单列表</h1>
						</div>
						<table class="tablelist1 check-table">
							<tr>
								<th style="width: 10%;">订单号</th>
								<th style="width: 10%;">买家账号</th>
								<th style="width: 8%;">收货人</th>
								<th style="width: 10%;">下单时间</th>
								<th style="width: 8%;">订单状态</th>
								<th style="width: 8%;">订单金额</th>
								<th style="width: 8%;">支付方式</th>
								<th style="width: 10%;">支付单号</th>
								<th style="width: 8%;">支付时间</th>
								<th style="width: 8%;">退款金额</th>
								<th style="width: 12%;">操作</th>
							</tr>
							<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>微信</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-rouble icon-img" title="积分"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-arrow-down icon-img" title="收货"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-files-o icon-img" title="订单"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>微信</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-search-plus icon-img" title="查看"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-files-o icon-img" title="订单"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>支付宝</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-files-o icon-img" title="订单"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
									</div>
								</td>
							</tr>
						</table>
						<ul class="paging">
							<li class="fontface2 prev-page prev-page-end"></li>
							<li class="fontface2 prev prev-end"></li>
							<li>|</li>
							<li class="page-jump radius3">1</li>
							<li class="fontface2 page-common"><span>共</span>&nbsp;<span>2</span>&nbsp;<span>页</span></li>
							<li>|</li>
							<li class="fontface2 next next-end"></li>
							<li class="fontface2 next-page next-page-end"></li>
							<li class="choice-number">
								<select class="choice-number-sele">
									<option>10</option>
									<option>20</option>
									<option>30</option>
									<option>40</option>
								</select>
							</li>
							<li><span>1</span>-<span>14</span></li>
							<li class="page-common"><span>共</span><span>28</span><span>条</span></li>
						</ul>
					</div>
				</div> 
				<div class="hide2">
					<div class="tablebox1">
						<div class="table-tit">
							<h1 class="tabtit1 left">商品订单列表</h1>
						</div>
						<table class="tablelist1 check-table">
							<tr>
								<th style="width: 10%;">订单号</th>
								<th style="width: 10%;">买家账号</th>
								<th style="width: 8%;">收货人</th>
								<th style="width: 10%;">下单时间</th>
								<th style="width: 8%;">订单状态</th>
								<th style="width: 8%;">订单金额</th>
								<th style="width: 8%;">支付方式</th>
								<th style="width: 10%;">支付单号</th>
								<th style="width: 8%;">支付时间</th>
								<th style="width: 8%;">退款金额</th>
								<th style="width: 12%;">操作</th>
							</tr>
							
							<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>微信</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>支付宝</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
									</div>
								</td>
							</tr>
						</table>
						<ul class="paging">
							<li class="fontface2 prev-page prev-page-end"></li>
							<li class="fontface2 prev prev-end"></li>
							<li>|</li>
							<li class="page-jump radius3">1</li>
							<li class="fontface2 page-common"><span>共</span>&nbsp;<span>2</span>&nbsp;<span>页</span></li>
							<li>|</li>
							<li class="fontface2 next next-end"></li>
							<li class="fontface2 next-page next-page-end"></li>
							<li class="choice-number">
								<select class="choice-number-sele">
									<option>10</option>
									<option>20</option>
									<option>30</option>
									<option>40</option>
								</select>
							</li>
							<li><span>1</span>-<span>14</span></li>
							<li class="page-common"><span>共</span><span>28</span><span>条</span></li>
						</ul>
					</div>
				</div> 
				<div class="hide2">
					<div class="tablebox1">
						<div class="table-tit">
							<h1 class="tabtit1 left">商品订单列表</h1>
						</div>
						<table class="tablelist1 check-table">
							<tr>
								<th style="width: 10%;">订单号</th>
								<th style="width: 10%;">买家账号</th>
								<th style="width: 8%;">收货人</th>
								<th style="width: 10%;">下单时间</th>
								<th style="width: 8%;">订单状态</th>
								<th style="width: 8%;">订单金额</th>
								<th style="width: 8%;">支付方式</th>
								<th style="width: 10%;">支付单号</th>
								<th style="width: 8%;">支付时间</th>
								<th style="width: 8%;">退款金额</th>
								<th style="width: 12%;">操作</th>
							</tr>
							<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>微信</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
									</div>
								</td>
							</tr>
						</table>
						<ul class="paging">
							<li class="fontface2 prev-page prev-page-end"></li>
							<li class="fontface2 prev prev-end"></li>
							<li>|</li>
							<li class="page-jump radius3">1</li>
							<li class="fontface2 page-common"><span>共</span>&nbsp;<span>2</span>&nbsp;<span>页</span></li>
							<li>|</li>
							<li class="fontface2 next next-end"></li>
							<li class="fontface2 next-page next-page-end"></li>
							<li class="choice-number">
								<select class="choice-number-sele">
									<option>10</option>
									<option>20</option>
									<option>30</option>
									<option>40</option>
								</select>
							</li>
							<li><span>1</span>-<span>14</span></li>
							<li class="page-common"><span>共</span><span>28</span><span>条</span></li>
						</ul>
					</div>
				</div> 
				<div class="hide2">
					<div class="tablebox1">
						<div class="table-tit">
							<h1 class="tabtit1 left">商品订单列表</h1>
						</div>
						<table class="tablelist1 check-table">
							<tr>
								<th style="width: 10%;">订单号</th>
								<th style="width: 10%;">买家账号</th>
								<th style="width: 8%;">收货人</th>
								<th style="width: 10%;">下单时间</th>
								<th style="width: 8%;">订单状态</th>
								<th style="width: 8%;">订单金额</th>
								<th style="width: 8%;">支付方式</th>
								<th style="width: 10%;">支付单号</th>
								<th style="width: 8%;">支付时间</th>
								<th style="width: 8%;">退款金额</th>
								<th style="width: 12%;">操作</th>
							</tr>
							<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>微信</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										
									</div>
								</td>
							</tr>
							<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>微信</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
									</div>
								</td>
							</tr>
						</table>
						<ul class="paging">
							<li class="fontface2 prev-page prev-page-end"></li>
							<li class="fontface2 prev prev-end"></li>
							<li>|</li>
							<li class="page-jump radius3">1</li>
							<li class="fontface2 page-common"><span>共</span>&nbsp;<span>2</span>&nbsp;<span>页</span></li>
							<li>|</li>
							<li class="fontface2 next next-end"></li>
							<li class="fontface2 next-page next-page-end"></li>
							<li class="choice-number">
								<select class="choice-number-sele">
									<option>10</option>
									<option>20</option>
									<option>30</option>
									<option>40</option>
								</select>
							</li>
							<li><span>1</span>-<span>14</span></li>
							<li class="page-common"><span>共</span><span>28</span><span>条</span></li>
						</ul>
					</div>
				</div> 
				<div class="hide2">
					<div class="tablebox1">
						<div class="table-tit">
							<h1 class="tabtit1 left">商品订单列表</h1>
						</div>
						<table class="tablelist1 check-table">
							<tr>
								<th style="width: 10%;">订单号</th>
								<th style="width: 10%;">买家账号</th>
								<th style="width: 8%;">收货人</th>
								<th style="width: 10%;">下单时间</th>
								<th style="width: 8%;">订单状态</th>
								<th style="width: 8%;">订单金额</th>
								<th style="width: 8%;">支付方式</th>
								<th style="width: 10%;">支付单号</th>
								<th style="width: 8%;">支付时间</th>
								<th style="width: 8%;">退款金额</th>
								<th style="width: 12%;">操作</th>
							</tr>
							<tr>
								<td>54564543456</td>
								<td>4614541234123</td>
								<td>张某某</td>
								<td>2016-05-06 12:05:30</td>
								<td>待付款</td>
								<td>10000.00</td>
								<td>微信</td>
								<td>12341320145456</td>
								<td>2016-05-06 12:05:30</td>
								<td>10000.00</td>
								<td>
									<div class="icon-box-com">
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
										<div class="icon-box left">
											<a class="fontface3 fa-times icon-img" title="取消"></a>
										</div>
									</div>
								</td>
							</tr>
						</table>
						<ul class="paging">
							<li class="fontface2 prev-page prev-page-end"></li>
							<li class="fontface2 prev prev-end"></li>
							<li>|</li>
							<li class="page-jump radius3"><input type="text" class="page-num"/></li>
							<li class="fontface2 page-common"><span>共</span>&nbsp;<span>2</span>&nbsp;<span>页</span></li>
							<li>|</li>
							<li class="fontface2 next next-end"></li>
							<li class="fontface2 next-page next-page-end"></li>
							<li class="choice-number">
								<select class="choice-number-sele">
									<option>10</option>
									<option>20</option>
									<option>30</option>
									<option>40</option>
								</select>
							</li>
							<li><span>1</span>-<span>14</span></li>
							<li class="page-common"><span>共</span><span>28</span><span>条</span></li>
						</ul>
					</div>
					
				</div> 
			</div> 
			
		</div>

        
	</div>
	<!--content结束-->
<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/alert.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/tab.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/bootstrap.min.js"></script>
<script src="__PUBLIC__/js/moment.js" charset="UTF-8"></script>
<script src="__PUBLIC__/js/moment-with-locales.js" charset="UTF-8"></script>
<script src="__PUBLIC__/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript">
	$(document).ready(function() {  
		 $('.datetimepicker2').datetimepicker({
		 	format: 'YYYY-MM-DD hh:mm',
		 	useCurrent: false,
            locale: 'zh-CN'
            
           
        });
        $('.datetimepicker3').datetimepicker({
        	format: 'YYYY-MM-DD hh:mm',
        	useCurrent: false,
            locale: 'zh-CN' 
            
        });
	}); 

</script>







