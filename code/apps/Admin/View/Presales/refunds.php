
    
        <div class="tipsbox">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list">
                <li>1.点击审核操作可对待审核款项审核退款或拒绝退款 </li>
                <li>2.点击退款操作可对正在退款的商品进行原路退款或人工退款选择处理 </li>
                <li>3.点击详情操作显示退款订单（包括订单物品及处理）的详细信息 </li>
            </ol>
        </div>
        <div class="iframeCon">
		<div class="iframeMain">
            <ul class="transverse-nav">
                <li class="{$status==''?'activeFour':''}"><a href="{:U('Presales/refunds/status/')}">全部</a></li>
                <li class="{$status=='1'?'activeFour':''}" ><a href="{:U('Presales/refunds/status/1')}">待处理</a></li>
        		<li class="{$status=='2'?'activeFour':''}" ><a href="{:U('Presales/refunds/status/2')}">待退款</a></li>
        		<li class="{$status=='3'?'activeFour':''}" ><a href="{:U('Presales/refunds/status/3')}">已退款</a></li>
        		<li class="{$status=='4'?'activeFour':''}" ><a href="{:U('Presales/refunds/status/4')}">已拒绝</a></li>
            </ul>
            <div class="white-bg">
                <div class="table-titbox">
                    <div class="option">
                        <h1 class="table-tit left boxsizing">退款管理</h1>
                        <ul class="operation-list left">
                            <li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
                            <!-- <li class="export-li"><a href="#"><span><i href="#" class="operation-icon export-icon"></i></span></a></li> -->
                        </ul>
                    </div>
        			<form method="get" class="form-horizontal" name="Search_form"  action="{:U('Presales/refunds')}">
                        <input type="hidden" name="status" value="{$status}"/>
        				<div class="search-box1 right">
                            <div class="search-boxcon boxsizing radius3 left">
                       
                                <select id='refundsearch' class="sele-com1 search-sele boxsizing" name="where">
                                    <option <?php if($_GET['where'] == 'refund_sn'){ echo  'selected="selected"';}?> value="refund_sn">退款单号</option>
                                    <option <?php if($_GET['where'] == 'order_sn'){ echo  'selected="selected"';}?> value="order_sn">订单编号</option>
                                    <option <?php if($_GET['where'] == 'member_mobile'){ echo  'selected="selected"';}?> value="member_mobile">买家帐号</option>
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
                                <th width="90">操作</th>
                                <th width="160">订单编号</th>
                                <th width="160">退款单号</th>
                                <th width="160">买家帐号</th>
                                <th width="160" class="text-l">退款金额</th>
                                <th width="200" class="text-l">退款原因</th>
                                <th width="200" class="text-l">处理状态</th>
                                <th width="230" class="text-l">处理备注</th>
                                <th width="200">申请时间</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        	<empty name="list">
        						<tr class="tr-minH">
                                    <td colspan="9">暂无数据！</td>
                                    <td></td>
                                </tr>
        					<else />
        					<foreach name="list" item="vo">
        	                <tr>
        	                	<td>
                                    <div class="table-iconbox">
                                        <if condition="$vo.status_code eq '1' ">
                                            <div class="edit-iconbox left edit-sele marginR10">
                                                <a class="edit-word table-icon-a" href='{:U("Presales/editrefund/id/$vo[refund_id]")}'><i class="table-com-icon table-handle-icon"></i><span class="gap">审核</span></a>
                                            </div>
                                        <elseif condition="$vo.status_code eq '2' "/>
                                            <div class="edit-iconbox left edit-sele marginR10">
                                                <a class="edit-word table-icon-a" href='{:U("Presales/refunddetail/id/$vo[refund_id]/status/return")}'  data-refund_id="{$vo['refund_id']}"><i class="table-com-icon table-refund-icon"></i><span class="gap">退款</span></a>
                                            </div>
                                        <else/>
                                            <div class="edit-iconbox left edit-sele marginR10">
                                                <a class="edit-word table-icon-a" href='{:U("Presales/refunddetail/id/$vo[refund_id]/status/details")}'><i class="table-com-icon table-look-icon"></i><span class="gap">详情</span></a>
                                            </div>
                                        </if>
                                    </div>
                                </td>
                                <td>{$vo.order_sn}</td>
        	                    <td>{$vo.refund_sn}</td>
        	                    <td>{$vo.member_name}</td>
        	                    <td class="text-l">{$vo.refund_amount}</td>
        	                    <td class="text-l">{$vo['causes_name']}</td>
                                <td class="text-l">{$vo['status']}</td>
                                <td class="text-l"><div class="word-overflow" title="{$vo['remark']}">{$vo['remark']}</div>{$vo['']}</td>
        	                    <td>{:date('Y-m-d H:i:s',$vo['dateline'])}</td>
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

        <!--content结束-->

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
                

        	});
        </script>
   