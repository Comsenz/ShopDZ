
        <!--<div class="tip-remind">收起提示</div>-->
        <div class="tipsbox radius3">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">{$Think.lang.article_add_tips}</ol>
        </div
        <!--内容开始-->
        <div class="iframeCon">
		<div class="iframeMain">
            <ul class="transverse-nav">
                <li class="activeFour"><a href="javascript:;"><span><if condition="$data.article_id eq ''">{$Think.lang.article_add}<else/>{$Think.lang.article_edit}</if></span></a></li>
            </ul>
            <div class="white-bg ">
                
                <div class="tab-conbox">
                    <form method="post" class="form-horizontal" action="{:U('Cms/article_add')}" enctype="multipart/form-data" id="article_setting">
                    <input name="form_submit"   type="hidden"  value="submit">
                    <input name="id"   type="hidden"  value="{$data.article_id}">
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.article_title}：</dt>
                                <dd class="left text-l">
                                    <input name="article_title" class="com-inp1 radius3 boxsizing" localrequired=""  type="text" value="{$data.article_title}"> 
                                    <p class="remind1">{$Think.lang.article_title_tips}</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.articla_category}：</dt>
                                <dd class="left text-l">
                                    
                                        <select id="field" class="com-sele radius3 juris-dl-sele" name="class_id" localrequired="">
                                            <option value="0" >{$Think.lang.select_article_class}</option>
                                            <?php if(!empty($list) && is_array($list)){ ?>
                                            <?php foreach($list as $v){  ?>
                                            <option <?php if($data['class_id'] == $v['class_id']){  ?> selected<?php } ?> value="<?php echo $v['class_id'];?>"><?php echo $v['class_name'];?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    
                                        
                                    <p class="remind1">{$Think.lang.select_article_class}</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.article_display}：</dt>
                                <dd class="left text-l">
                                    <div class="switch-box">
                                        <input type="checkbox" name="article_show" id="switch-radio" class="switch-radio" <if condition="$data['article_show'] eq 1 ">checked="checked" <else /> </if> <if condition="$data['article_show'] eq '' ">checked="checked" <else /> </if>/>
                                        <span class="switch-half switch-open">ON</span>
                                        <span class="switch-half switch-close close-bg">OFF</span>
                                    </div>
                                    <p class="remind1">{$Think.lang.article_is_open_tips}</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing">
                                <span class="redstar">*</span>
                                {$Think.lang.sequence}：
                                </dt>
                                <dd class="left text-l">
                                <input localrequired="" class="com-inp1 radius3 boxsizing" type="text" name="article_sort" value="<?php if(!empty($data['article_sort'])){echo $data['article_sort'];}else{echo 0;} ?>">
                                <p class="remind1">{$Think.lang.article_sequence_tips}</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.article_content}：</dt>
                                <dd class="left text-l">
                                    <script id="desc" name="article_content" type="text/plain"><?php echo htmlspecialchars_decode(stripslashes($data['article_content']));?></script>
        <script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
         <script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.all.js"></script>
                                    <script type="text/javascript">
                                        var ue = UE.getEditor('desc',{
                                            'initialFrameWidth':"100%",
                                            'initialFrameHeight':500,
                                            'zIndex':1,
                                            'toolbars':[[
                            'fullscreen', 'source', '|', 'undo', 'redo', '|',
                            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                            'directionalityltr', 'directionalityrtl', 'indent', '|',
                            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                            'link', 'unlink', '|', 'simpleupload', 'insertvideo',
                                            ]],
                                            });
                                    </script>
                                    <p class="remind1">{$Think.lang.article_content_tips}</p>
                                </dd>
                            </dl>
                        </div>
                        <div class="btnbox3 boxsizing">
                            <a type="button" id="article_submit" class="btn1 radius3 marginT10  btn3-btnmargin" href="javascript:;">{$Think.lang.submit_btn}</a>
                            <a type="button" class="btn1 radius3 marginT10" href="{:U('Cms/article_list')}">{$Think.lang.return_list}</a>
                        </div>
                        </form>
                </div>
            </div>
        </div>  
       </div>  
        <!--内容结束-->
<div id="append_parent"></div>
<script type="text/javascript">
    $('#article_submit').click(function(){
        flag=checkrequire('article_setting');
        if(!flag){
            $('#article_setting').submit();
        }
    });
</script>
