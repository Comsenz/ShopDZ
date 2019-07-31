<!--<div class="tip-remind">收起提示</div>-->
<div class="tipsbox">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">
        {$Think.lang.article_list_tips}
    </ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
    <div class="white-bg">
        <div class="table-titbox">
            <div class="option">
                <h1 class="table-tit left boxsizing">{$Think.lang.article_list}</h1>
                <ul class="operation-list left">
                    <li class="add-li"><a href="{:U('Cms/article_add')}"><span><i class="operation-icon add-icon"></i></span></a></li>
                    <li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
                </ul>
            </div>         
        
        <form  action="{:U('cms/article_list')}"  method="get">               
            <div class="search-box1 right">
                <div class="search-boxcon boxsizing radius3 left">
                    <select  name="field" id="field"  class="sele-com1 search-sele boxsizing">
                        <option  <?php if($_GET['field'] == 'article_title'){ echo  'selected="selected"';}?> value="article_title">{$Think.lang.article_title}</option>
                    </select>
                    <input type="text" name="value" value="<?php echo $_GET['value'];?>" class="search-inp-con boxsizing"/>
                </div>
                <input type="submit" name="search" value="{$Think.lang.search}" class="search-btn right radius3"/>
            </div>
        </form>
        </div>
        <div class="comtable-box boxsizing">
            <table class="com-table">
                <thead>
                    <tr>
                        <th width="180">{$Think.lang.operation}</th>
                        <th width="120">{$Think.lang.sequence}</th>
                        <th width="300" class="text-l">{$Think.lang.title}</th>
                        <th width="400">文章链接</th>
                        <th width="200">{$Think.lang.class_name}</th>
                        <th width="200">{$Think.lang.release_time}</th>
                        <th width="120">{$Think.lang.status}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <empty name="list">
                        <tr class="tr-minH">
                            <td colspan="7">暂无数据！</td>
                            <td></td>
                        </tr>
                    <else />
                    <foreach name="list" item="vo">
                    <tr>
                        <td>
                            <div class="table-iconbox">                            
                                <div class="edit-iconbox left edit-sele boxsizing marginR10">
                                    <a class="edit-word table-icon-a" href='{:U("Cms/article_add/id/$vo[article_id]")}'><i class="table-com-icon table-edit-icon"></i><span class="gap">{$Think.lang.edit}</span></a>
                                </div>
                                <div class="edit-iconbox left edit-sele boxsizing marginR10">
                                    <a class="edit-word table-icon-a" href="javascript:;" onclick="class_del({$vo.article_id});"><i class="table-com-icon table-dele-icon"></i><span class="gap">{$Think.lang.delete}</span></a>
                                </div>
                            </div>
                        </td>
                        <td>{$vo.article_sort}</td>
                        <td class="text-l"><a href="<?php echo SITE_URL;?>wap/artical.html?id={$vo.article_id}" target='blank'>{$vo.article_title}</a></td>
                        <td><?php echo SITE_URL;?>wap/artical.html?id={$vo.article_id}</td>
                        <td><?php echo $class_array[$vo['class_id']] ?></td>
                        <td><?php echo date('Y-m-d H:i:s',$vo['article_time']);?></td>                        
                        <td>
                            <?php if(!empty($vo['article_show'])){ ?>
                            <div class="state-btn yes-state">
                                <i class="yes-icon"></i>
                                <span>{$Think.lang.open}</span>
                            </div>
                            <?php }else{ ?>
                            <div class="state-btn no-state">
                                <i class="no-icon"></i>
                                <span>{$Think.lang.close}</span>
                            </div>
                            <?php } ?>
                        </td>
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
    $(function(){
        $('.add-li').mousemove(function(){
            e=arguments.callee.caller.arguments[0] || window.event; 
            remindNeed($('.add-li'),e,'{$Think.lang.article_add}');
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
        showConfirm("{$Think.lang.article_delete_tips}",function(){
                var url = "<?php echo U('Cms/article_del');?>"+'?id='+id;
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