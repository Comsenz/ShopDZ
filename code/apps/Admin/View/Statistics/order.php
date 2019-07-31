<div class="tipsbox">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">
        <li>1.下单金额、下单数量统计。</li>
        <li>1.统计日期分为一天、七天、30天，用户也可自定义日期筛选。</li>
    </ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
    <ul class="transverse-nav">
        <li <?php if($search_arr['search_key'] == 'amount'){ echo 'class="activeFour"'; } ?> data-param="amount"><a href="javascript:;"><span>下单金额</span></a></li>
        <li <?php if($search_arr['search_key'] == 'num'){ echo 'class="activeFour"'; } ?> data-param="num"><a href="javascript:;"><span>下单数量</span></a></li>
    </ul>
    <div class="white-bg paddingB30">
        <div class="tab-conbox <?php if($search_arr['search_key'] != 'amount'){ echo 'none'; } ?>">
            <ul class="stati-tab radius5">
                <li class="{$search_arr['search_type']=='day'?'stati-active-bg':''}"><a href="{:U('Statistics/order',array('search_type'=>'day','search_key'=>'amount'))}">今天</a></li>
                <li class="{$search_arr['search_type']=='week'?'stati-active-bg':''}"><a href="{:U('Statistics/order',array('search_type'=>'week','search_key'=>'amount'))}">最近7天</a></li>
                <li class="{$search_arr['search_type']=='month'?'stati-active-bg':''}"><a href="{:U('Statistics/order',array('search_type'=>'month','search_key'=>'amount'))}">最近30天</a></li>
            </ul>

            <div class="search-box1 right">
             <form  action=""  method="get">
             <input type="hidden" name="search_type" value="diydate">
             <input type="hidden" name="search_key" value="amount">
                <div class="left">
                <p class="time-box"><input type="text" class="com-inp1 radius3 boxsizing" id="ostime" name="stime" readonly="" <?php echo $search_arr['diydate'] ? 'value="'.@date('Y-m-d',$search_arr['diydate']['stime']).'"' : 'placeholder="选择起始时间"';?> /><i class="timeinp-icon"></i></p>
                <span class="left time-line">—</span>
                <p class="time-box"><input type="text" class="com-inp1 radius3 boxsizing" id="oetime" name="etime" readonly="" <?php echo $search_arr['diydate'] ? 'value="'.@date('Y-m-d',$search_arr['diydate']['etime']).'"' : 'placeholder="选择结束时间"';?> /><i class="timeinp-icon"></i></p>
                </div>
                <input type="submit" value="搜索" class="search-btn right radius3">
             </form>
            </div>

            <div class="staticstic-con">
                <h1 class="staticstic-tit">总下单金额：&nbsp;&nbsp;<?php echo $count['order_amount']; ?>元</h1>
                <div id="order_amount" style=""></div>
            </div>
        </div>
        <div class="tab-conbox <?php if($search_arr['search_key'] != 'num'){ echo 'none'; } ?>">
            <ul class="stati-tab radius5">
                <li class="{$search_arr['search_type']=='day'?'stati-active-bg':''}"><a href="{:U('Statistics/order',array('search_type'=>'day','search_key'=>'num'))}">今天</a></li>
                <li class="{$search_arr['search_type']=='week'?'stati-active-bg':''}"><a href="{:U('Statistics/order',array('search_type'=>'week','search_key'=>'num'))}">最近7天</a></li>
                <li class="{$search_arr['search_type']=='month'?'stati-active-bg':''}"><a href="{:U('Statistics/order',array('search_type'=>'month','search_key'=>'num'))}">最近30天</a></li>
            </ul>

            <div class="search-box1 right">
             <form  action=""  method="get">
             <input type="hidden" name="search_type" value="diydate">
             <input type="hidden" name="search_key" value="num">
                <div class="left">
                <p class="time-box"><input type="text" class="com-inp1 radius3 boxsizing" id="nstime" name="stime" readonly="" <?php echo $search_arr['diydate'] ? 'value="'.@date('Y-m-d',$search_arr['diydate']['stime']).'"' : 'placeholder="选择起始时间"';?> /><i class="timeinp-icon"></i></p>
                <span class="left time-line">—</span>
                <p class="time-box"><input type="text" class="com-inp1 radius3 boxsizing" id="netime" name="etime" readonly="" <?php echo $search_arr['diydate'] ? 'value="'.@date('Y-m-d',$search_arr['diydate']['etime']).'"' : 'placeholder="选择结束时间"';?> /><i class="timeinp-icon"></i></p>
                </div>
                <input type="submit" value="搜索" class="search-btn right radius3">
             </form>
            </div>

            <div class="staticstic-con">
                <h1 class="staticstic-tit">总下单量：&nbsp;&nbsp;<?php echo $count['order_num']; ?></h1>
                <div id="order_num" style=""></div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="__PUBLIC__/admin/js/highcharts.js"></script>

<script type="text/javascript">
var data_arr = new Array();
data_arr['order_num'] = <?php echo $stat_json['order_num'];?>;
data_arr['order_amount'] = <?php echo $stat_json['order_amount'];?>;
$(function(){
    $("#ostime").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel:true,
        dateFormat: 'yy-mm-dd',
        showAnim:"fadeIn",//淡入效果
    });
    $("#oetime").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel:true,
        dateFormat: 'yy-mm-dd',
        showAnim:"fadeIn",//淡入效果
    });
    $("#nstime").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel:true,
        dateFormat: 'yy-mm-dd',
        showAnim:"fadeIn",//淡入效果
    });
    $("#netime").datepicker({
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
    if($('#order_'+keys).html().length <= 0){
        $('#order_'+keys).highcharts(data_arr['order_'+keys]);
    }
}
</script>
