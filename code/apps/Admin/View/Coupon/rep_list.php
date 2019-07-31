        <div class="tipsbox">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.用户优惠券信息查看。</li>
                <li>2.显示为优惠券模板内已发放或未发放优惠券。</li>
            </ol>
        </div>
<div class="iframeCon">
<div class="iframeMain">
    <ul class="transverse-nav">
        <li><a href="{:U('Admin/Coupon/lists')}"><span>优惠券</span></a></li>
        <li class="activeFour"><a href="javascript:;"><span>用户优惠券</span></a></li>
    </ul>
    <div class="white-bg">
        <div class="table-titbox">
            <div class="option">
                <h1 class="table-tit left boxsizing">优惠券“{$temp.rpacket_t_title}”{$temp.status}列表</h1>
                <ul class="operation-list left">
                    <li class="back-li"onclick="history.go(-1);"><a href="#"><span><i href="#" class="operation-icon op-back-icon"></i></span></a></li>
                    <li class="refresh-li" onclick="location.reload();"><a href="javascript:;"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
                </ul>
            </div>
        </div>
        
        <div class="comtable-box boxsizing">
            <table class="com-table">
                <thead>
                    <tr>
                        <th width="220">操作</th>
                        <th width="120">用户名</th>
                        <th width="220">优惠券名称</th>
                        <th width="120">优惠券面额（元）</th>
                        <th width="180">有效期</th>
                        <th width="180">领取时间</th>
                        <th width="180">使用时间</th>
                        <th width="120">订单号</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($list as $v){ ?>
                    <tr>
                        <td>
                            <div class="table-iconbox">
                                <div class="edit-iconbox left edit-sele boxsizing marginR10">
                                    <a class="edit-word table-icon-a" onclick="rep_del(<?php echo $v['rpacket_id']; ?>);"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $v['rpacket_owner_name']; ?></td>
                        <td><?php echo $v['rpacket_title']; ?></td>
                        <td><?php echo $v['rpacket_price']; ?></td>
                        <td><?php echo $v['end_date']; ?></td>
                        <td><?php echo $v['active_date']; ?></td>
                        <td><?php echo $v['used_date']; ?></td>
                        <td><?php echo $v['order_id']; ?></td>
                        <td></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        {$page}
    </div>
</div>
</div>

<script type="text/javascript">
    $(function(){
        //添加会员提示
        $('.refresh-li').mousemove(function(){
            e=arguments.callee.caller.arguments[0] || window.event; 
            remindNeed($('.add-li'),e,'刷新');
        })
        $('.refresh-li').mouseout(function(){
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
    function rep_del(id){
        showConfirm("确认删除吗？",function(){
            $.get('<?php echo U('Coupon/rep_delete')?>',{checksubmit:'yes',id:id},function(data){
                if(data.status == 1){
                   showSuccess(data.info,function(){
                        window.location.reload();
                  });
                }else{
                    showError(data.info);
                }
            },'json')

        });   
    }
</script>