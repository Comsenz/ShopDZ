<!--<div class="tip-remind">收起提示</div>-->
<div class="tipsbox">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">
        <li>{$Think.lang.article_class_list_tips}</li>
    </ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
    <div class="white-bg">
        <div class="table-titbox">
            <div class="option">
                <h1 class="table-tit left boxsizing">{$Think.lang.article_class_list}</h1>
                <ul class="operation-list left">
                    <li class="add-li"><a href="{:U('Cms/article_class_add')}"><span><i class="operation-icon add-icon"></i></span></a></li>
                    <li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
                </ul>
            </div>         
        </div>
        
        <div class="comtable-box boxsizing">
            <table class="com-table">
                <thead>
                    <tr>
                        <th width="180">{$Think.lang.operation}</th>
                        <th width="120">{$Think.lang.sequence}</th>
                        <th width="200">{$Think.lang.class_name}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <empty name="list">
                        <tr class="tr-minH">
                            <td colspan="3">暂无数据！</td>
                            <td></td>
                        </tr>
                    <else />
                    <foreach name="list" item="vo">
                    <tr>
                        <?php if($vo['class_code']==0){ ?>
                        <td>
                            <div class="table-iconbox">                            
                                <div class="edit-iconbox left edit-sele boxsizing marginR10">
                                    <a class="edit-word table-icon-a" href='{:U("Cms/article_class_add/id/$vo[class_id]")}'><i class="table-com-icon table-edit-icon"></i><span class="gap">{$Think.lang.edit}</span></a>
                                </div>
                                <div class="edit-iconbox left edit-sele marginR10">
                                    <a class="edit-word table-icon-a" href="javascript:;" onclick="class_del({$vo.class_id});"><i class="table-com-icon table-dele-icon"></i><span class="gap">{$Think.lang.delete}</span></a>
                                </div>
                            </div>
                        </td>
                        <?php }else{ ?>
                        <td>
                            <div style="text-align: center;">--</div>
                        </td>
                        <?php } ?>
                        <td>{$vo.class_sort}</td>
                        <td>{$vo.class_name}</td>
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
<!-- <div id="append_parent"></div> -->
<script type="text/javascript">
    $(function(){
        $('.add-li').mousemove(function(){
            e=arguments.callee.caller.arguments[0] || window.event; 
            remindNeed($('.add-li'),e,'{$Think.lang.articla_class_add}');
        })
        $('.add-li').mouseout(function(){
            $('.tip-remind').remove();
        });
        $('.refresh-li').mousemove(function(){
            e=arguments.callee.caller.arguments[0] || window.event; 
            remindNeed($('.add-li'),e,'{$Think.lang.refresh}');
        })
        $('.refresh-li').mouseout(function(){
            $('.tip-remind').remove();
        });

        
            
    }) 
</script>
<script type="text/javascript">
    function class_del(id) {
        showConfirm("{$Think.lang.class_delete_tips}",function(){
                var url = "<?php echo U('Cms/article_class_del');?>"+'?id='+id;
                $.get(url,function(data){
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