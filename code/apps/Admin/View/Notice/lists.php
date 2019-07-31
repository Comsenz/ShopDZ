
<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">
        <li>1.消息模板列表的展示。</li>
        <li>2.可对模板进行编辑操作，修改模板内容，或开启关闭模板状态。</li>

    </ol>
</div>

<div class="iframeCon">

<div class="iframeMain">
<ul class="transverse-nav">
       <li   class="activeFour"   >
             <a href="<?php echo U('Notice/template');?>"><span>消息模板</span></a>
         </li>
	   <li >
             <a href="<?php echo U('Notice/send_notice');?>"><span>发送通知</span></a>
         </li>
</ul>
    <div class="white-bg">
        <div class="table-titbox">
            <h1 class="table-tit left boxsizing">微信消息通知模板</h1>
        </div>
        <div class="comtable-box boxsizing">
            <table class="com-table">
                <thead>
                <tr>
                    <th width="90">操作</th>
                    <th width="100" class="text-l">模板名称</th>
                    <th width="240" class="text-l">模板标题</th>
                    <th width="480" class="text-l">模板内容</th>
                    <th width="120">状态</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <empty name="notice">
                    <tr class="tr-minH">
                        <td colspan="3">暂无数据！</td>
                        <td></td>
                    </tr>
                <else />
                <foreach name="notice" item="no">
                    <tr>
                        <td>
                            <div class="table-iconbox">
                                <div class="edit-iconbox left edit-sele marginR10">
                                    <a class="edit-word table-icon-a" href="<?php echo U('Notice/noticeedit', array('id'=>$no['id']));?>"><i class="table-com-icon table-edit-icon"></i><span class="gap">编辑</span></a>
                                </div>
                            </div>
                        </td>
                        <td ><div class="td-word classify-name text-l">{$no.name}</div></td>
                        <td ><div class="td-word classify-name text-l"><div class="td-word" title="{$no.title}">{$no.web_title}</div></div></td>
                        <td ><div class="td-word classify-name text-l"><div class="td-word" title="{$no.content}">{$no.web_content}</div></div></td>
                        <td>
                            <if condition="$no.web_states eq '1'">
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

    })

</script>
