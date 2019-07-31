	<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
	<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>
	<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
	<style type="text/css">
		.onoffswitch {
		    position: relative;
		    width: 54px;
		    -webkit-user-select: none;
		    -moz-user-select: none;
		    -ms-user-select: none;
		    text-align: center;
		    margin: 0 auto;
		}
		.onoffswitch-inner::before {
		    content: "ON";
		    /*padding-left:-5px !important;*/
		   position: relative;
		   left: -10px;
		    background-color: rgb(26, 179, 148);
		    color: rgb(255, 255, 255);
		}
	</style>


	<div class="content">
		<!--提示框开始-->
        <div class="alertbox1">
            <div class="alert-con radius3">
                <button class="closebtn1">×</button>
                <div class="alert-con-div">
                    <h1 class="fontface alert-tit1">&nbsp;操作提示</h1>
                    <ol>
                        <li>1.平台可以选择开启一种或多种消息通知方式。</li>
                        <li>2.短消息、微信需要用户绑定手机、微信后才能正常接收。</li>
                    </ol>
                </div>
            </div>
        </div>
        <!--提示框结束-->
		<div class="tablebox1"> 
			<div class="table-tit">
				<h1 class="tabtit1 left">用户消息模板</h1>
			</div>
			<table class="tablelist1">
				<tr>
					<th style="width:20%;">模板描述</th>
                    <th style="width:20%;">站内信</th>
                    <th style="width:20%;">手机短信</th>
                    <th style="width:20%;">微信</th>
                    <th style="width:20%;">操作</th>
					
				</tr>
				<notempty name="mmtpl_list">
                <foreach name="mmtpl_list" item="vo" >
					<tr>
						<td>{$vo.mmt_name}</td>
						<td>
                        <div class="td-icon-box">
	                        <if condition="$vo.mmt_message_switch eq 1 ">
	                        	<span class="yes"><i class="fa fa-check-circle"></i>开启</span> 
	                        <else />
	                        	<span class="no"><i class="fa fa-ban"></i>关闭</span>
	                        </if>
                        </div>
                        </td>
                        <td>
                        <div class="td-icon-box">
	                        <if condition="$vo.mmt_short_switch eq 1 ">
	                        	<span class="yes"><i class="fa fa-check-circle"></i>开启</span> 
	                        <else />
	                        	<span class="no"><i class="fa fa-ban"></i>关闭</span>
	                        </if>
                        </div>
                        </td>
                        <td>
                        <div class="td-icon-box">
	                        <if condition="$vo.mmt_wx_switch eq 1 ">
	                        	<span class="yes"><i class="fa fa-check-circle"></i>开启</span> 
	                        <else />
	                        	<span class="no"><i class="fa fa-ban"></i>关闭</span>
	                        </if>
                        </div>
                        </td>
						<td>
							<div class="icon-box-com">
								<div class="icon-box left">
									<a title="编辑" class="fontface3 fa-edit icon-img" href="{:U('Admin/Setting/notes_edit')}?code={$vo.mmt_code}" >
									</a>
								</div>
							</div>
						</td>
					</tr>
				</foreach>
                </notempty>
			</table>
		</div>
	</div>
	<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
	<script type="text/javascript" src="__PUBLIC__/js/icon.js"></script>