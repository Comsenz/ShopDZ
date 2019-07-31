
        <!--<div class="tip-remind">收起提示</div>-->
        <div class="tipsbox radius3">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>拼团设置</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.如果上传视频请上传MP4格式视频</li>
                <li>2.上传视频最好不要超过30M</li>
            </ol>
        </div
        <!--内容开始-->
        <div class="iframeCon">
		<div class="iframeMain">
			  <ul class="transverse-nav">
				<li ><a href="{:U('Plugin/admin',array('module'=>'group','controller'=>'admin','method'=>'group'))}"><span>活动列表</span></a></li>
				<li ><a href="{:U('Plugin/admin',array('module'=>'group','controller'=>'admin','method'=>'grouplist'))}"><span>开团列表</span></a></li>
				<li ><a href="{:U('Plugin/admin',array('module'=>'group','controller'=>'admin','method'=>'joinlist'))}"><span>参团列表</span></a></li>
				<li class="activeFour"><a href="{:U('Plugin/admin',array('module'=>'group','controller'=>'admin','method'=>'groupset'))}"><span>拼团设置</span></a></li>
			</ul>
            <div class="white-bg ">
                <div class="tab-conbox">
                    <form method="post" class="form-horizontal" action="{:U('Plugin/admin',array('module'=>'group','controller'=>'admin','method'=>'groupset'))}" enctype="multipart/form-data" id="article_setting">
                    <input name="form_submit"   type="hidden"  value="submit">
                    <input name="id"   type="hidden"  value="{$data.article_id}">
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar"></span>拼团封面：</dt>
                                <dd class="left text-l">
                                    <div class="input-group">
                                        <span class="previewbtn"><img src="__PUBLIC__/admin/images/imgGray.png" class="viewimgbtn1 viewimg view_img" url="__ATTACH_HOST__{$group_set['group_img']}" id="logo_img"/></span>
                                        <input type="text" name="shop_logo" id="logotext" value="{$group_set['group_img']}" class="form-control upload-inp com-inp1 radius3 boxsizing" >
                                        <input type="file"   id="shop_logo" class="form-control" style="display: none;">
                                        <span class="input-group-btn left">
                                            <input type="button"  value="选择文件" class="upload-btn search-btn" id="up" onclick="$('input[id=shop_logo]').click();"/>
                                        </span>
                                    </div>
                                    <p class="remind1">请使用宽高比为4:1的jpg/gif/png格式的图片。建议尺寸104*26。</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>拼团玩法：</dt>
                                <dd class="left text-l">
                                    <script id="desc" name="article_content" type="text/plain"><?php echo htmlspecialchars_decode(stripslashes($group_set['group_content']));?></script>
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
    $(function(){
        var uploader2 = new plupload.Uploader({
            runtimes: 'html5,html4,flash,silverlight',
            browse_button: 'shop_logo',
            url: "<?php echo U('Plugin/admin',array('module'=>'group','controller'=>'admin','method'=>'upLogoFile'));?>",
            filters: {

                mime_types: [{
                    title: "Image files",
                    extensions: "jpg,gif,png,jpeg",
                    prevent_duplicates: true
                }]
            },
            init: {
                PostInit: function () {
                },
                FilesAdded: function (up, files) {
                    uploader2.start();
                },
                UploadProgress: function (up, file) {
                    //alert('这里可以做进度条');
                },
                FileUploaded: function (up, file, res) {
                    var resobj = eval('(' + res.response + ')');
                    if(resobj.status){
                        $("#logotext").val(resobj.data);
                        var url = "__ATTACH_HOST__"+resobj.data;
                        $("#logo_img").attr("url",url);
                    }
                },
                Error: function (up, err) {
                    alert('err');
                }
            }

        });
        uploader2.init();
    })
    $('#article_submit').click(function(){
        flag=checkrequire('article_setting');
        if(!flag){
            $('#article_setting').submit();
        }
    });
    $("#logo_img").posi({default_img:"DEFAULT_LOGO_IMAGE"});
</script>
