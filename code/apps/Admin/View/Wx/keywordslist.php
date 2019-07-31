
<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">

        <li> 1.关键词不能重复添加。</li>
        <li> 2.关键词删除后为彻底删除。</li>
        <li> 2.关键词不启用微信用户出发后不能返回内容</li>
    </ol>
</div>

<div class="iframeCon">
<div class="iframeMain">
    <div class="white-bg">
        <div class="table-titbox">
            <h1 class="table-tit left boxsizing">关键词列表</h1>
            <ul class="operation-list left">
                <li class="add-li"><a href="{:U('Wx/keywordsedit')}"><span><i href="#" class="operation-icon add-icon"></i></span></a></li>
                <li class="refresh-li"><a href="javascript:void (0)"  onclick="location.reload()"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
            </ul>
            <form action="<?=U('/Wx/keywordsOp')?>" method="post" id='formid'>
                <div class="search-box1 right">
                    <div class="search-boxcon boxsizing radius3 left">
                        <select class="sele-com1 search-sele boxsizing" name="type" id="search_select">
                            <option value="member_mobile">关键词</option>
                        </select>
                        <input type="text" value="{$search_text}" name="search_text" class="search-inp-con boxsizing"/>
                    </div>
                    <button type="submit" class="search-btn right radius3">搜索</button>
                </div>
            </form>
        </div>
        <div class="comtable-box boxsizing">
            <table class="com-table">
                <thead>
                <tr>
                    <th width="180">操作</th>
                    <th width="140" class="text-l">关键词</th>
                    <th width="120">回复类型</th>
                    <th width="120">状态</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <empty name="keywords">
                    <tr class="tr-minH">
                        <td colspan="4">暂无数据！</td>
                        <td></td>
                    </tr>
                <else />
                <foreach name="keywords" item="keyword">
                    <tr id="menu_{$keyword.id}" class="menulid_{$keyword.id}">
                        <td>
                            <div class="table-iconbox">
                                <div class="edit-iconbox left edit-sele marginR10">
                                    <a class="edit-word table-icon-a delmenu"  href="javascript:void(0)" id="{$keyword.id}"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
                                </div>
                                <div class="edit-iconbox left edit-sele marginR10">
                                    <a class="edit-word table-icon-a" href="<?php echo U('Wx/keywordsedit', array('id'=>$keyword['id']));?>"><i class="table-com-icon table-edit-icon"></i><span class="gap">编辑</span></a>
                                </div>
                            </div>
                        </td>
                        <td ><div class="td-word classify-name text-l">{$keyword.keyword}</div></td>
                        <td>{$keyword.isimg}</td>
                        <td>
                            <if condition="$keyword.states eq '1'">
                                <div class="state-btn yes-state">
                                    <i class="yes-icon"></i>
                                    <span>开启</span>
                                </div>
                            <else/>
                                <div class="state-btn no-state">
                                    <i class="no-icon"></i>
                                    <span>关闭</span>
                                </div>
                            </if>
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
            remindNeed($('.add-li'),e,'添加关键词');
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

        $('.creat-li').mouseout(function(){
            $('.tip-remind').remove();
        });
        $('.delmenu').click(function(){
            var id = $(this).attr('id');
            showConfirm('您确定要删除吗',function () {
                $.ajax({
                    url:"<?php echo U('Wx/delkeywords');?>",
                    type:"POST",
                    data:{id:id},
                    success: function(data) {
                        var data = data;
                        if(data.code == 0){
                            showSuccess(data.message);
                            $("#menu_"+id).remove();
                        }else{
                            showError(data.message);
                        }
                    }
                });
            });

        })

    })

</script>
