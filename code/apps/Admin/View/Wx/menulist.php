<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">

        <li> 1.自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。</li>
        <li> 2.一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。</li>
        <li> 3.创建自定义菜单后，菜单的刷新策略是，在用户进入公众号会话页或公众号profile页时，如果发现上一次拉取菜单的请求在5分钟以前，就会拉取一下菜单，如果菜单有更新，就会刷新客户端的菜单。测试时可以尝试取消关注公众账号后再次关注，则可以看到创建后的效果</li>
        <li> 4.微信登录地址为：<?php echo $wxlogin?></li>
    </ol>
</div>

<div class="iframeCon">
<div class="iframeMain">
    <div class="white-bg">
        <div class="table-titbox">
            <h1 class="table-tit left boxsizing">菜单列表</h1>
            <ul class="operation-list left">
                <li class="add-li"><a href="{:U('Wx/menuedit')}"><span><i href="#" class="operation-icon add-icon"></i></span></a></li>
                <li class="refresh-li"><a href="javascript:void (0)"  onclick="location.reload()"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
                <li class="creat-li"><a href="javascript:void (0)" onclick="disp_prompt()"><span><i class="operation-icon creat-icon"></i></span></a></li>
            </ul>

        </div>
        <div class="comtable-box boxsizing">
            <table class="com-table">
                <thead>
                <tr>
                    <th width="180">操作</th>
                    <th width="140" class="text-l">菜单名称</th>
                    <th width="140">触发类型</th>
                    <th width="450" class="text-l">响应动作</th>
                    <th width="110">排序</th>
                    <th width="240"></th>
                </tr>
                </thead>
                <tbody>
                <empty name="menu">
                    <tr class="tr-minH">
                        <td colspan="5">暂无数据！</td>
                        <td></td>
                    </tr>
                <else />
                <foreach name="menu" item="member">
                    <tr id="menu_{$member.id}" class="menulid_{$member.lid}">
                        <td>
                            <div class="table-iconbox">
                                <div class="edit-iconbox left edit-sele marginR10">
                                    <a class="edit-word table-icon-a delmenu"  href="javascript:void(0)" id="{$member.id}" lid="{$member.lid}"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
                                </div>
                                <div class="edit-iconbox left edit-sele marginR10">
                                    <a class="edit-word table-icon-a" href="<?php echo U('Wx/menuedit', array('id'=>$member['id'],'type'=>'edit'));?>"><i class="table-com-icon table-edit-icon"></i><span class="gap">编辑</span></a>
                                </div>
                            </div>
                        </td>

                        <if condition="$member.level eq 1 ">
                            <if condition="$member.last neq 1 ">
                            <td class="text-l"><div class="td-word classify-name"><i class="classify-icon forks-icon forks-iconF marginR5"></i>{$member.name}</div></td>
                                <else />
                                <td class="text-l"><div class="td-word classify-name"><i class="classify-icon forks-icon marginR5"></i>{$member.name}</div></td>
                                </if>
                        <else />
                            <td class="text-l"><div class="td-word classify-name">{$member.name}<a href="<?php echo U('Wx/menuedit', array('id'=>$member['id'],'type'=>'add'));?>"><i class="classify-icon classify-open-icon marginL5"></i></a></div></td>
                        </if>
                        <td>{$member.type}</td>
                        <td class="text-l"><div class="word-overflow" title="{$member.keywords}">{$member.keywords}</div></td>
                        <td>{$member.order}</td>
                        <td></td>

                    </tr>
                </foreach>
                </empty>
                </tbody>
            </table>
        </div>

    </div>
</div>
</div>

<script type="text/javascript">
    $(function(){

        $('.add-li').mousemove(function(){
            e=arguments.callee.caller.arguments[0] || window.event;
            remindNeed($('.add-li'),e,'添加菜单');
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
        $('.creat-li').mousemove(function(){
            e=arguments.callee.caller.arguments[0] || window.event;
            remindNeed($('.add-li'),e,'生成菜单');
        })
        $('.creat-li').mouseout(function(){
            $('.tip-remind').remove();
        });
        $('.delmenu').click(function(){
            var id = $(this).attr('id');
            var lid = $(this).attr('lid');
            if(lid == 0){
                showConfirm('您确定要删除吗,将删除所有子菜单',function () {
                    delmenu(id,lid)
                });
            }else{
                showConfirm('您确定要删除吗',function () {
                    delmenu(id,lid)
                });
            }
        })

    })
    function delmenu(id,lid){
        $.ajax({
            url:"<?php echo U('Wx/delmenu');?>",
            type:"POST",
            data:{id:id,lid:lid},
            success: function(data) {
                var data = data;
                if(data.code == 0){
                    showSuccess(data.message);
                    if(lid == 0){
                        $("#menu_"+id).remove();
                        $(".menulid_"+id).remove();
                    }else{
                        $("#menu_"+id).remove();
                    }
                }else{
                    showError(data.message);
                }
            }
        });
    }
    function disp_prompt()
    {
       location.href="{:U('Wx/menuSend')}";
    }
</script>
</body>
</html>
