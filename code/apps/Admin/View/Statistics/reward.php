<div class="tipsbox">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">
        <li>1.推广会员、推广奖励、热门推广统计。</li>
        <li>1.统计日期分为一天、七天、30天，用户也可自定义日期筛选。</li>
    </ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
    <ul class="transverse-nav">
        <li <?php if($search_arr['search_key'] == 'member'){ echo 'class="activeFour"'; } ?> data-param="member"><a href="javascript:;"><span>推广新增会员</span></a></li>
        <li <?php if($search_arr['search_key'] == 'amount'){ echo 'class="activeFour"'; } ?> data-param="amount"><a href="javascript:;"><span>推广奖励统计</span></a></li>
        <li <?php if($search_arr['search_key'] == 'hot'){ echo 'class="activeFour"'; } ?> data-param="hot"><a href="javascript:;"><span>推广奖励top30</span></a></li>
    </ul>
    <div class="white-bg paddingB30">
        <div class="tab-conbox <?php if($search_arr['search_key'] != 'member'){ echo 'none'; } ?>">
            <ul class="stati-tab radius5">
                <li class="{$search_arr['search_type']=='day'?'stati-active-bg':''}"><a href="{:U('Statistics/reward',array('search_type'=>'day','search_key'=>'member'))}">今天</a></li>
                <li class="{$search_arr['search_type']=='week'?'stati-active-bg':''}"><a href="{:U('Statistics/reward',array('search_type'=>'week','search_key'=>'member'))}">最近7天</a></li>
                <li class="{$search_arr['search_type']=='month'?'stati-active-bg':''}"><a href="{:U('Statistics/reward',array('search_type'=>'month','search_key'=>'member'))}">最近30天</a></li>
            </ul>

            <div class="search-box1 right">
             <form  action=""  method="get">
             <input type="hidden" name="search_type" value="diydate">
             <input type="hidden" name="search_key" value="member">
                <div class="left">
                <p class="time-box"><input type="text" class="com-inp1 radius3 boxsizing" id="mstime" name="stime" readonly="" <?php echo $search_arr['diydate'] ? 'value="'.@date('Y-m-d',$search_arr['diydate']['stime']).'"' : 'placeholder="选择起始时间"';?> /><i class="timeinp-icon"></i></p>
                <span class="left time-line">—</span>
                <p class="time-box"><input type="text" class="com-inp1 radius3 boxsizing" id="metime" name="etime" readonly="" <?php echo $search_arr['diydate'] ? 'value="'.@date('Y-m-d',$search_arr['diydate']['etime']).'"' : 'placeholder="选择结束时间"';?> /><i class="timeinp-icon"></i></p>
                </div>
                <input type="submit" value="搜索" class="search-btn right radius3">
             </form>
            </div>

            <div class="staticstic-con">
                <h1 class="staticstic-tit">推广新增会员人数：&nbsp;&nbsp;<?php echo $count['reward_member']; ?>人</h1>
                <div id="reward_member" style=""></div>
            </div>
        </div>
        <div class="tab-conbox <?php if($search_arr['search_key'] != 'amount'){ echo 'none'; } ?>">
            <ul class="stati-tab radius5">
                <li class="{$search_arr['search_type']=='day'?'stati-active-bg':''}"><a href="{:U('Statistics/reward',array('search_type'=>'day','search_key'=>'amount'))}">今天</a></li>
                <li class="{$search_arr['search_type']=='week'?'stati-active-bg':''}"><a href="{:U('Statistics/reward',array('search_type'=>'week','search_key'=>'amount'))}">最近7天</a></li>
                <li class="{$search_arr['search_type']=='month'?'stati-active-bg':''}"><a href="{:U('Statistics/reward',array('search_type'=>'month','search_key'=>'amount'))}">最近30天</a></li>
            </ul>

            <div class="search-box1 right">
             <form  action=""  method="get">
             <input type="hidden" name="search_type" value="diydate">
             <input type="hidden" name="search_key" value="amount">
                <div class="left">
                <p class="time-box"><input type="text" class="com-inp1 radius3 boxsizing" id="astime" name="stime" readonly="" <?php echo $search_arr['diydate'] ? 'value="'.@date('Y-m-d',$search_arr['diydate']['stime']).'"' : 'placeholder="选择起始时间"';?> /><i class="timeinp-icon"></i></p>
                <span class="left time-line">—</span>
                <p class="time-box"><input type="text" class="com-inp1 radius3 boxsizing" id="aetime" name="etime" readonly="" <?php echo $search_arr['diydate'] ? 'value="'.@date('Y-m-d',$search_arr['diydate']['etime']).'"' : 'placeholder="选择结束时间"';?> /><i class="timeinp-icon"></i></p>
                </div>
                <input type="submit" value="搜索" class="search-btn right radius3">
             </form>
            </div>

            <div class="staticstic-con">
                <h1 class="staticstic-tit">推广奖励累积金额：&nbsp;&nbsp;<?php echo $count['reward_amount']; ?>元</h1>
                <div id="reward_amount" style=""></div>
            </div>
        </div>

        <div class="tab-conbox <?php if($search_arr['search_key'] != 'hot'){ echo 'none'; } ?>">
            <ul class="stati-tab radius5">
                <li class="{$search_arr['search_type']=='day'?'stati-active-bg':''}"><a href="{:U('Statistics/reward',array('search_type'=>'day','search_key'=>'hot'))}">今天</a></li>
                <li class="{$search_arr['search_type']=='week'?'stati-active-bg':''}"><a href="{:U('Statistics/reward',array('search_type'=>'week','search_key'=>'hot'))}">最近7天</a></li>
                <li class="{$search_arr['search_type']=='month'?'stati-active-bg':''}"><a href="{:U('Statistics/reward',array('search_type'=>'month','search_key'=>'hot'))}">最近30天</a></li>
            </ul>

            <div class="search-box1 right">
             <form  action=""  method="get">
             <input type="hidden" name="search_type" value="diydate">
             <input type="hidden" name="search_key" value="hot">
                <div class="left">
                <p class="time-box"><input type="text" class="com-inp1 radius3 boxsizing" id="hstime" name="stime" readonly="" <?php echo $search_arr['diydate'] ? 'value="'.@date('Y-m-d',$search_arr['diydate']['stime']).'"' : 'placeholder="选择起始时间"';?> /><i class="timeinp-icon"></i></p>
                <span class="left time-line">—</span>
                <p class="time-box"><input type="text" class="com-inp1 radius3 boxsizing" id="hetime" name="etime" readonly="" <?php echo $search_arr['diydate'] ? 'value="'.@date('Y-m-d',$search_arr['diydate']['etime']).'"' : 'placeholder="选择结束时间"';?> /><i class="timeinp-icon"></i></p>
                </div>
                <input type="submit" value="搜索" class="search-btn right radius3">
             </form>
            </div>

            <div class="staticstic-con">
                <div id="reward_hot" style=""></div>
            </div>
        </div>

    </div>
</div>
</div>
<script src="__PUBLIC__/admin/js/highcharts.js"></script>
<script type="text/javascript">
var data_arr = new Array();
data_arr['reward_member'] = <?php echo $stat_json['reward_member'];?>;
data_arr['reward_amount'] = <?php echo $stat_json['reward_amount'];?>;
data_arr['reward_hot'] = <?php echo $stat_json['reward_hot'];?>;
$(function(){
    $( "#mstime" ).datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel:true,
        dateFormat: 'yy-mm-dd',
        showAnim:"fadeIn",//淡入效果
    });
    $( "#metime" ).datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel:true,
        dateFormat: 'yy-mm-dd',
        showAnim:"fadeIn",//淡入效果
    });
    $( "#astime" ).datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel:true,
        dateFormat: 'yy-mm-dd',
        showAnim:"fadeIn",//淡入效果
    });
    $( "#aetime" ).datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel:true,
        dateFormat: 'yy-mm-dd',
        showAnim:"fadeIn",//淡入效果
    });
    $( "#hstime" ).datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel:true,
        dateFormat: 'yy-mm-dd',
        showAnim:"fadeIn",//淡入效果
    });
    $( "#hetime" ).datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel:true,
        dateFormat: 'yy-mm-dd',
        showAnim:"fadeIn",//淡入效果
    });

    var search_key = '<?php echo $search_arr['search_key']; ?>';
    gethigtcharts(search_key);

    $(".transverse-nav>li").unbind("click").bind('click',function(){
        $(this).addClass("activeFour").siblings().removeClass();
        // $($(".tab-conbox")[$(this).index()]).show().siblings().hide();
        $($(".tab-conbox")[$(this).index()]).removeClass('none').siblings().addClass('none');
        var par = $(this).attr('data-param');
        gethigtcharts(par);
        reloadScroll();
    });

});
function gethigtcharts(keys){
    if($('#reward_'+keys).html().length <= 0){
        $('#reward_'+keys).highcharts(data_arr['reward_'+keys]);
    }
}
</script>
