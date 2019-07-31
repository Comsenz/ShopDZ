        <div class="tipsbox">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.用户优惠券列表。</li>
                <li>2.列表包含用户已使用和未使用全部优惠券。</li>
                <li>3.如需发放优惠券给指定用户请点击“+”进行发放。</li>
                <li>4.删除操作请谨慎进行。</li>
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
            <h1 class="table-tit left boxsizing">用户优惠券</h1>
            <ul class="operation-list left">
                <li class="add-li" onclick="location.href='{:U('coupon/grant')}'"><a href="javascript:;"><span><i href="#" class="operation-icon add-icon"></i></span></a></li>
                <li class="refresh-li" onclick="location.reload();"><a href="javascript:;"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
                <!-- <li class="export-li"><a href="javascript:;"><span><i href="#" class="operation-icon export-icon"></i></span></a></li> -->
            </ul>
        </div>
            <div class="search-box1 right">
            <form  method="get" class="form-horizontal" name="Search_form" action=''>
<!--                             <div class="form-group time2 left">
                    <select  name="timetype"  class="sele-com1 boxsizing left search-sele1" style="height:24px;">
                        <option  <?php if($_GET['timetype'] == 'rpacket_used_date'){ echo  'selected="selected"';}?> value="rpacket_used_date" >使用时间</option>
                        <option  <?php if($_GET['timetype'] == 'rpacket_active_date'){ echo  'selected="selected"';}?>  value="rpacket_active_date" >发放日期</option>
                        <option  <?php if($_GET['timetype'] == 'rpacket_end_date'){ echo  'selected="selected"';}?> value="rpacket_end_date">有效期</option>
                    </select>
                    <div class='input-group date left time-width' data-date="2012-02-20" data-date-format="yyyy-mm-dd">
                        <input type="text" class="form-control com-inp1 datetimepicker2 time-inp1 search-inp1" name="min_date" value="<?php echo  $_GET['min_date'];?>" />
                        <span class="left timeto">--</span>
                        <input type="text" class="form-control com-inp1 datetimepicker3 time-inp1 search-inp1" name="max_date" value="<?php echo $_GET['max_date'];?>" />
                        
                    </div>
                </div> -->
                <div class="search-boxcon boxsizing radius3 left">
                    <select class="com-sele radius3 juris-dl-sele" id="field" name="field">
                        <option value="rpacket_title" <?php if($_GET['field'] == 'rpacket_title' || empty($_GET['field'])){ echo 'selected="selected"';}?> >优惠券名称</option>
                        <option value="rpacket_owner_name" <?php if($_GET['field'] == 'rpacket_owner_name'){ echo  'selected="selected"';}?> >用户名</option>
                    </select>
                    <input type="text" name="q" value="<?php echo $_GET['q'];?>" class="search-inp-con boxsizing"/>
                </div>
                <input type="submit" name="search" value="搜索" class="search-btn right radius3"/>
            </form>
            </div>
        </div>
        
        <div class="comtable-box boxsizing">
            <table class="com-table">
                <thead>
                    <tr>
                        <th width="180">操作</th>
                        <th width="120">用户名</th>
                        <th class="text-l" width="220">优惠券名称</th>
                        <th width="120">优惠券面额（元）</th>
                        <th width="180">有效期</th>
                        <th width="180">领取时间</th>
                        <th width="180">使用时间</th>
                        <th width="120">订单号</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <empty name="list">
                        <tr class="tr-minH">
                            <td colspan="8">暂无数据！</td>
                            <td></td>
                        </tr>
                    <else />
                    <?php foreach($list as $v){ ?>
                    <tr>
                        <td>
                            <div class="table-iconbox">
                                <div class="edit-iconbox left edit-sele boxsizing marginR10">
                                    <a class="edit-word table-icon-a alert-btn" id="<?php echo $v['rpacket_id']; ?>"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
                                </div>
                                <div class="edit-iconbox left edit-sele boxsizing marginR10">
                                    <a class="edit-word table-icon-a" href="<?php echo U('coupon/member_packet_info/id/'.$v['rpacket_id']); ?>"><i class="table-com-icon table-look-icon"></i><span class="gap">详情</span></a>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $v['rpacket_owner_name']; ?></td>
                        <td class="text-l"><?php echo $v['rpacket_title']; ?></td>
                        <td><?php echo $v['rpacket_price']; ?></td>
                        <td><?php echo $v['end_date']; ?></td>
                        <td><?php echo $v['active_date']; ?></td>
                        <td><?php echo $v['used_date']; ?></td>
                        <td><?php echo $v['order_id']; ?></td>
                        <td></td>
                    </tr>
                    <?php } ?>
                    </empty>
                </tbody>
            </table>
        </div>
        {$page}
    </div>
</div>

<div class="cover none"></div>
<div class="alert showAlert radius3 deleCoupon-alert specialAlert none">
    <i class="close-icon"></i>
    <h1 class="special-tit">删除用户优惠券</h1>
    <form method="post" class="form-horizontal" name="redpacke_form" id="redpacke_form"
         action="{:U('Coupon/rep_delete')}">
    <div class="special-con">
        <input type="hidden" name="checksubmit" value="yes" />
        <input type="hidden" name="id" value="" id="rpacket_id"/>
        <span class="special-con-left left">是否返还积分</span>
        <div class="button-holder left">
            <p class="radiobox"><input type="radio" id="radio-1-2" name="point_back" class="regular-radio" value="1" checked="checked"/><label for="radio-1-2"></label><span class="radio-word">是</span></p>
            <p class="radiobox"><input type="radio" id="radio-1-3" name="point_back" value="2" class="regular-radio"/><label for="radio-1-3"></label><span class="radio-word">否</span></p>
        </div>
        <div class="clear"></div>
    </div>
    <div class="alert-btnbox boxsizing">
        <a class="btn1 radius3" id="redpacke_form_submit">确认</a>
    </div>
    </form>
</div>


</div>
<script type="text/javascript">
    $(function(){
        //添加会员提示
        $('.add-li').mousemove(function(){
            e=arguments.callee.caller.arguments[0] || window.event; 
            remindNeed($('.add-li'),e,'发放优惠券');
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
            





        $('.alert-btn').click(function(){
            $('.cover').removeClass('none');
            $('.deleCoupon-alert').removeClass('none');
            $('#rpacket_id').val($(this).attr('id'));
        })
            
        $('.cover,.close-icon').click(function(){
            $('.cover').addClass('none');
            $('.deleCoupon-alert').addClass('none');
        })
        $('#redpacke_form_submit').click(function(){
            $('#redpacke_form').submit();
        });
    }) 
</script>