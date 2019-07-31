
	<meta charset="utf-8">
	<title>地区管理</title>
  	<link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css" />
  	<link rel="stylesheet" href="__PUBLIC__/css/bootstrap-datetimepicker.css" />
  	<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
  	<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
  	<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>


<!--content开始-->
<div class="content">
	<div class="tablebox1">
		<div class="table-tit">
			<h1 class="tabtit1 left">地区列表</h1>
			<div class="btnbox1 right">
				<a class="btn1 radius3 btn-margin">批量删除</a>
				<a class="btn1 radius3 btn-margin">返回上级</a>
				<a class="btn1 radius3 btn-margin">新增地区</a>
			</div>
		</div>
		<div class="clear-box">
			<!--<div class="form-group left time2">
            	<label class="font-noraml left time-label">下单时间</label>
                <div class='input-group date left time-width' data-date="2012-02-20" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control com-inp1 datetimepicker2 time-inp1" name="start" value="2014-11-11" />
                    <span class="left timeto">--</span>
                    <input type="text" class="form-control com-inp1 datetimepicker3 time-inp1" name="end" value="2014-11-17" />
                    
                </div>
            </div>-->
		
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
				<th style="width:20%;">
					<div class="inp-box2">
						<input type="checkbox" id="check-all" class="check-top"/>
						<label for="male4">全选</label>
					</div>
				</th>
				<th style="width: 20%;">地区</th>
				<th style="width: 20%;">所在层级</th>
				<th style="width: 20%;">下级地区数量</th>
				<th style="width: 20%;">操作</th>
			</tr>
			<tr>
				<td>
					<div class="inp-box2">
						<input type="checkbox" name="subCheck" class="check_sub check-top"/>
						<label for="male4">选中</label>
					</div>
				</td>
				<td>北京</td>
				<td>1</td>
				<td>2</td>
				<td>
					<div class="icon-box-com">
						<div class="icon-box left">
							<a class="fontface3 fa-cog icon-img" title="设置"></a>
						</div>
						<div class="icon-box left">
							<a class="fontface3 fa-trash-o icon-img" title="删除"></a>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="inp-box2">
						<input type="checkbox" name="subCheck" class="check_sub check-top"/>
						<label for="male4">选中</label>
					</div>
				</td>
				<td>北京</td>
				<td>1</td>
				<td>2</td>
				<td>
					<div class="icon-box-com">
						<div class="icon-box left">
							<a class="fontface3 fa-cog icon-img" title="设置"></a>
						</div>
						<div class="icon-box left">
							<a class="fontface3 fa-trash-o icon-img" title="删除"></a>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="inp-box2">
						<input type="checkbox" name="subCheck" class="check_sub check-top"/>
						<label for="male4">选中</label>
					</div>
				</td>
				<td>北京</td>
				<td>1</td>
				<td>2</td>
				<td>
					<div class="icon-box-com">
						<div class="icon-box left">
							<a class="fontface3 fa-cog icon-img" title="设置"></a>
						</div>
						<div class="icon-box left">
							<a class="fontface3 fa-trash-o icon-img" title="删除"></a>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="inp-box2">
						<input type="checkbox" name="subCheck" class="check_sub check-top"/>
						<label for="male4">选中</label>
					</div>
				</td>
				<td>北京</td>
				<td>1</td>
				<td>2</td>
				<td>
					<div class="icon-box-com">
						<div class="icon-box left">
							<a class="fontface3 fa-cog icon-img" title="设置"></a>
						</div>
						<div class="icon-box left">
							<a class="fontface3 fa-trash-o icon-img" title="删除"></a>
						</div>
					</div>
				</td>
			</tr>
		</table>
		<ul class="paging">
			<li class="fontface2 prev-page prev-page-end"></li>
			<li class="fontface2 prevbtn prev-end"></li>
			<li>|</li>
			<li class="page-jump radius3"><input type="text" class="page-num"/></li>
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
<!--content结束-->


<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/alert.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/check.js"></script>



