<div class="tipsbox">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list">
        <li>1.‘删除’按钮可删除商品评价(可批量删除。默认全选为空)</li>
        <li>2.‘详情’操作可查看用户反馈意见和商城处理详情</li>
        <li>3.‘处理’操作可对用户反馈进行处理说明</li>
    </ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
    <ul class="transverse-nav">
        <li class="{$status=='-1'?'activeFour':''}"><a href="{:U('Presales/feedbacks/status/-1')}">全部</a></li>
		<li class="{$status=='0'?'activeFour':''}" ><a href="{:U('Presales/feedbacks/status/0')}">待处理</a></li>
		<li class="{$status=='1'?'activeFour':''}" ><a href="{:U('Presales/feedbacks/status/1')}">已处理</a></li>
    </ul>
	<div class="white-bg">
        <div class="table-titbox">
            <div class="option">
                <h1 class="table-tit left boxsizing">意见反馈</h1>
                <ul class="operation-list left">
                    <li class="refresh-li"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
                    <!-- <li class="export-li"><a href="#"><span><i href="#" class="operation-icon export-icon"></i></span></a></li> -->
                    <li class="dele-li"><a href="#"><span><i href="#" class="operation-icon op-dele-icon"></i></span></a></li>
                </ul>
            </div>
           
			<form method="get" class="form-horizontal" name="Search_form"  action="{:U('Presales/feedbacks')}">
                <input type="hidden" name="status" value="{$status}"/>
				
                <div class="search-box1 right">
                    <div class="search-boxcon boxsizing radius3 left">
                    	<select id='refundsearch' class="sele-com1 search-sele boxsizing" name="field">
                    		<option  <?php if($_GET['field'] == 'member_phone'){ echo  'selected="selected"';}?>  value="member_phone" >手机号</option>
                    		<option  <?php if($_GET['field'] == 'username'){ echo  'selected="selected"';}?> value="username" >处理人</option>
                    	</select>
                    	<input name="value" value="<?php echo $_GET['value'];?>" class="search-inp-con boxsizing" type="text"/>
                    </div>
                    <input value="搜索" class="search-btn right radius3" type="submit"/>
                </div>
            </form>
			
        </div>
		<div class="comtable-box boxsizing">
            <table class="com-table">
                <thead>
                    <tr>
                        <th width="100" class="text-l">
                            <div class="button-holder">
                                <p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-1" name="radio-1-set" class="radio-1 regular-radio"/><label for="radio-1-1"></label><span class="radio-word">全选</span></p>
                            </div>
                        </th>
                        <th width="180">操作</th>
                        <th width="130">手机号</th>
                        <th width="200" class="text-l">反馈内容</th>
                        <th width="200" class="text-l">处理说明</th>
                        <th width="120" class="text-l">状态</th>
                        <th width="120" class="text-l">处理人</th>
                        <th width="200">发起时间</th>
                        <th width="200">处理时间</th>
                        
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                	<empty name="feedback_list">
						<tr class="tr-minH">
                            <td colspan="10">暂无数据！</td>
                            <td></td>
                        </tr>
					<else />				
					<foreach name="feedback_list" item="vo" key="k">
					<tr>
                        <td class="text-l">
                            <div class="button-holder">
                                <p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-{$k+2}" name="id" class="regular-radio" value="{$vo[id]}"/><label for="radio-1-{$k+2}"></label><span class="radio-word"></span></p>
                            </div>
                        </td>
						<td>
							<div class="table-iconbox">
                                <div class="edit-iconbox left edit-sele marginR10">
                                    <a class="edit-word table-icon-a" href="javascript:void(0);" onclick="del({$vo[id]})"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
                                </div>
                                <if condition="$vo['status'] eq '待处理'"> 
                                    <div class="edit-iconbox left edit-sele marginR10">
                                        <a class="edit-word table-icon-a" href='{:U("Presales/editfeedback/id/$vo[id]")}'><i class="table-com-icon table-handle-icon"></i><span class="gap">处理</span></a>
                                    </div>
                                <else/>
                                    <div class="edit-iconbox left edit-sele marginR10">
                                        <a class="edit-word table-icon-a" href='{:U("Presales/feedbackdetail/id/$vo[id]")}'><i class="table-com-icon table-look-icon"></i><span class="gap">详情</span></a>
                                    </div>
                                </if>
                            </div>
						</td>
                        <td>{$vo.member_phone}</td>
						<td class="text-l"><div class="word-overflow" title="{$vo['content']}"><?php echo cutstr($vo['content'],20)?></div></td>
                        <td class="text-l"><div class="word-overflow" title="{$vo.instruction}">{$vo.instruction}</div></td>
                        <td class="text-l">{$vo.status}</td>
						<td class="text-l">{$vo.username}</td>
                        <td>{$vo.ftime|date="Y-m-d H:i:s",###}</td>
						<td><notempty name="vo['ctime']">{$vo.ctime|date="Y-m-d H:i:s",###}</notempty></td>
						<td></td>
					</tr>
					</foreach>
					</empty>
				</tbody>
            </table>
		</div>
        {$page}
	</div> 
</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {  
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
        $('.dele-li').mousemove(function(){
            e=arguments.callee.caller.arguments[0] || window.event; 
            remindNeed($('.dele-li'),e,'批量删除');
        })
        $('.dele-li').mouseout(function(){
            $('.tip-remind').remove();
        });
        $('.dele-li').click(function(){
            var ids = new Array();
            $('input[name="id"]:checked').each(function(){
                if($(this).val()) ids[ids.length] = $(this).val();
            });
            if(ids.length > 0) del(ids);
        });
        /*
		$('.datetimepicker2').datetimepicker({
		 	format: 'YYYY-MM-DD hh:mm',
            locale: 'zh-CN'
           
        });
        $('.datetimepicker3').datetimepicker({
        	format: 'YYYY-MM-DD hh:mm',
            locale: 'zh-CN'
            
        });
		*/
	}); 
    function del(id) {
        showConfirm('将删除此意见反馈，确认操作吗？',function () {
            $.ajax({
                url:"{:U('Presales/delfeedback')}",
                type:"get",
                data:{id:id},
                success: function(info) {
                    if(info.status==1){
                        showSuccess("您已经永久删除了这条信息。",function(){window.location.reload()});
                    }else{
                        //showError(info.content);
                    }
                }
            });
        });
    }
</script>






