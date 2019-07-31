<if condition="!$edit">
    <div style="clear:both;padding-top:10px;">
        <!-- {$vo.item_data} -->
        <?php echo stripslashes($vo['item_data']); ?>
    </div>
    
<else />
    <style type="text/css">
        .picbg {
            display: block;
            margin: 50px auto 0 auto;
        }
    </style>

    <div class="tipsbox radius3">
        <div class="tips boxsizing radius3">
            <div class="tips-titbox">
                <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                <span class="open-span span-icon"><i class="open-icon"></i></span>
            </div>
        </div>
        <ol class="tips-list" id="tips-list">
            <li>1.在下拉框中选择想要的效果，文本框中会出现相应的内容。</li>
            <li>2.可以对html文本进行相应的修改。</li>
            <li>3.操作完成后点击确认提交按钮进行保存。</li>
        </ol>
    </div>
    <div class="iframeCon">
        <div class="iframeMain">
            <ul class="transverse-nav">
                <li class="activeFour"><a href="javascript:;"><span>预置通栏模板</span></a></li>
            </ul>
            <div class="white-bg ">
                <div class="tab-conbox">
                <form method="post" class="form-horizontal" id="dataform" action="{:U('itemSave')}">
                <input name="form_submit"   type="hidden"  value="submit">
                <input type="hidden" name="item_id" id="item_id"  value="{$edit}">
                <input type="hidden" name="item_type" id="item_type"  value="{$item_type}">
                    <div class="jurisdiction boxsizing">
                        <dl class="juris-dl boxsizing">
                            <dt class="left text-r boxsizing">通栏模板选择：</dt>
                            <dd class="left text-l">
                                <select class="w140" name="style" id="style">
                                    <option value="0" >请选择样式</option>
                                    <?php if(!empty($tpl_info) && is_array($tpl_info)){ ?>
                                    <?php foreach($tpl_info as $v){  ?>
                                    <option value="{$v.tpl_id}">{$v.tpl_name}</option>
                                    <?php } ?>
                                    <?php } ?>

                                </select>
                                                                    
                                <p class="remind1">请选择预设置样式</p>
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing">
                            <dt class="left text-r boxsizing paddingT10"><span class="redstar">*</span>通栏html：</dt>
                            <dd class="left text-l paddingT10">
                                <div class="left">
                                    <textarea type="text" id="html_content" class="com-textarea1 radius3 boxsizing banner-text" readonly="true"></textarea>
                                    <p class="remind1">参考模板代码</p>
                                </div>
                                <div class="left preview-jt">
                                    <i class="preview-jtR"></i>
                                </div>
                                <div class="preview-box left">
                                    <div class="preview-img-box" id="html_pic">
                                        <img src="__PUBLIC__/admin/images/picbg.png" class="picbg" />
                                    </div>
                                    <p class="preview-word">通栏样式预览</p>
                                </div>
                                <div class="clear"></div>
                                <div class="copy-box">
                                    <i class="copy-icon"></i>
                                    <p class="copy-remind">点击复制至下方</p>
                                </div>
                                <textarea type="text" class="com-textarea1 radius3 boxsizing banner-text" name="adv_content" id="adv_content" localrequired="">{$item_data}</textarea>
                                <p class="remind1">通栏样式代码，支持HTML</p>
                            </dd>
                        </dl>
                    </div>
                    <div class="btnbox3 boxsizing">                        
                        <a type="button" id="html_submint" class="btn1 radius3 marginT10 btn3-btnmargin" href="javascript:;">确认提交</a>
                        <a type="button" class="btn1 radius3 marginT10" href="{:U('setting/personnel')}">返回上页</a>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
 
<script type="text/javascript">
    $('#html_submint').click(function(){
        flag=checkrequire('dataform');
        if(!flag){
            $('#dataform').submit();
        }
    });
    $('#style').change(function(){
        style_change($(this).find('option:selected').val());
    })
    // simulateSelect('style',200,function(th){
    //     var id = $(th).attr('val');
    //     style_change(id);
    // });

function style_change(id){
    $.ajax({
        type:'POST',
        url:"{:U('html')}",
        data:'tpl_id='+id,
        success:function(result){
            if(result.status===0){
                // showError("请选择样式！");
                $('#html_content').val("");
                var html_pic = '<div class="preview-img-box" id="html_pic"><img src="__PUBLIC__/admin/images/picbg.png" class="picbg"/></div>';
                $('#html_pic').replaceWith(html_pic);

            }else{
                $('#html_content').val(""+result.data.html+"");
                var html_pic = '<img src="__PUBLIC__/web/'+result.data.intro_pic+'" class="preview-imgW" id="html_pic"/>';
                $('#html_pic').replaceWith(html_pic);
                
            }
        },
        dataType:'json'
    });
}

$(".copy-icon").click(function(){
    var html_content = $('#html_content').val();
    if(html_content != ''){
        $('#adv_content').val(""+html_content+"");
    }else{
        showError("通栏html为空！");
    }
})

</script>
</if>