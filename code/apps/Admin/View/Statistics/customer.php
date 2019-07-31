<div class="tipsbox">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">
        <li>1.退款、退货金额统计，退款、退货单数统计。</li>
        <li>1.统计日期分为一天、七天、30天，用户也可自定义日期筛选。</li>
    </ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
    <ul class="transverse-nav">
        <li <?php if($search_arr['search_key'] == 'amount'){ echo 'class="activeFour"'; } ?> data-param="amount" ><a href="javascript:;"><span>退款金额</span></a></li>
        <li <?php if($search_arr['search_key'] == 'num'){ echo 'class="activeFour"'; } ?> data-param="num"><a href="javascript:;"><span>退款单数</span></a></li>
    </ul>
    <div class="white-bg paddingB30">
        <div class="tab-conbox <?php if($search_arr['search_key'] != 'amount'){ echo 'none'; } ?>">
            <ul class="stati-tab radius5">
                <li class="{$search_arr['search_type']=='day'?'stati-active-bg':''}"><a href="{:U('Statistics/customer',array('search_type'=>'day','search_key'=>'amount'))}">今天</a></li>
                <li class="{$search_arr['search_type']=='week'?'stati-active-bg':''}"><a href="{:U('Statistics/customer',array('search_type'=>'week','search_key'=>'amount'))}">最近7天</a></li>
                <li class="{$search_arr['search_type']=='month'?'stati-active-bg':''}"><a href="{:U('Statistics/customer',array('search_type'=>'month','search_key'=>'amount'))}">最近30天</a></li>
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
                <h1 class="staticstic-tit">退款总金额：&nbsp;&nbsp;<?php echo $count['refund_amount']; ?>元 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    退货总金额：&nbsp;&nbsp;<?php echo $count['return_amount']; ?>元</h1>
                <div id="customer_amount" style=""></div>
            </div>
            
        </div>
        <div class="tab-conbox <?php if($search_arr['search_key'] != 'num'){ echo 'none'; } ?>">
            <ul class="stati-tab radius5">
                <li class="{$search_arr['search_type']=='day'?'stati-active-bg':''}"><a href="{:U('Statistics/customer',array('search_type'=>'day','search_key'=>'num'))}">今天</a></li>
                <li class="{$search_arr['search_type']=='week'?'stati-active-bg':''}"><a href="{:U('Statistics/customer',array('search_type'=>'week','search_key'=>'num'))}">最近7天</a></li>
                <li class="{$search_arr['search_type']=='month'?'stati-active-bg':''}"><a href="{:U('Statistics/customer',array('search_type'=>'month','search_key'=>'num'))}">最近30天</a></li>
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
                <h1 class="staticstic-tit">退款单总数：&nbsp;&nbsp;<?php echo $count['refund_num']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    退货单总数：&nbsp;&nbsp;<?php echo $count['return_num']; ?></h1>
                <div id="customer_num" style=""></div>
            </div>
            
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    // $(function(){
    //     $('.stati-tab li').bind('click',function(){
    //         $(this).addClass('stati-active-bg').siblings().removeClass('stati-active-bg');
    //     })
    // })
</script>
<script src="__PUBLIC__/admin/js/highcharts.js"></script>
<script type="text/javascript">
var data_arr = new Array();
data_arr['num'] = <?php echo $stat_json['num'];?>;
data_arr['amount'] = <?php echo $stat_json['amount'];?>;
$(function(){
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
    $( "#nstime" ).datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel:true,
        dateFormat: 'yy-mm-dd',
        showAnim:"fadeIn",//淡入效果
    });
    $( "#netime" ).datepicker({
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
    if($('#customer_'+keys).html().length <= 0){
        $('#customer_'+keys).highcharts(data_arr[keys]);
    }
}
</script>
