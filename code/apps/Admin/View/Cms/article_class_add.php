
        <!--<div class="tip-remind">收起提示</div>-->
        <div class="tipsbox radius3">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                {$Think.lang.class_add_tips}
            </ol>
        </div
        <!--内容开始-->
        <div class="iframeCon">
		<div class="iframeMain">
            <ul class="transverse-nav">
                <li class="activeFour"><a href="javascript:;"><span><if condition="$class_array.class_id eq ''">{$Think.lang.class_add}<else/>{$Think.lang.articla_class_edit}</if></span></a></li>
            </ul>
            <div class="white-bg ">
                
                <div class="tab-conbox">
                    <form method="post" class="form-horizontal" action="{:U('Cms/article_class_add')}" enctype="multipart/form-data" id="class_setting">
                    <input name="form_submit"   type="hidden"  value="submit"> 
                    <input name="id"   type="hidden"  value="{$class_array.class_id}">
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.class_name}：</dt>
                                <dd class="left text-l">
                                    <input type="text" class="com-inp1 radius3 boxsizing" value="{$class_array.class_name}" name="class_name" id="class_name" localrequired="">
                                    <p class="remind1">{$Think.lang.class_name_tips}</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.sequence}：</dt>
                                <dd class="left text-l">
                                    <input type="text" class="com-inp1 radius3 boxsizing" localrequired="" value="<?php if(!empty($class_array['class_sort'])){echo $class_array['class_sort'];}else{echo 0;} ?>" name="class_sort" id="class_sort"> 
                                    <p class="remind1">{$Think.lang.class_sequence_tips}</p>
                                </dd>
                            </dl>
                        </div>
                        <div class="btnbox3 boxsizing">
                            <a type="button" id="class_submit" class="btn1 radius3 marginT10  btn3-btnmargin" href="javascript:;">{$Think.lang.submit_btn}</a>
                            <a type="button" class="btn1 radius3 marginT10" href="{:U('Cms/article_class_list')}">{$Think.lang.return_list}</a>
                        </div>
                        </form>
                </div>
            </div>
        </div>  
        </div>  
        <!--内容结束-->

<script type="text/javascript">
    $('#class_submit').click(function(){
        flag=checkrequire('class_setting');
        if(!flag){
            $('#class_setting').submit();
        }
    });
</script>
