
        <!--<div class="tip-remind">收起提示</div>-->
        <div class="tipsbox radius3">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.微信分享设置，会在微信分享时显示配置内容。</li>
                <li>2.更新或者开启后请更新网站缓存。</li>
            </ol>
        </div>
        <!--内容开始-->
        <div class="iframeCon">
		<div class="iframeMain">
            <ul class="transverse-nav">
                <li class="activeFour"><a href="javascript:;"><span>微信分享</span></a></li>
            </ul>
            <div class="white-bg">
                
                <div class="tab-conbox">
                    <form method="post" class="form-horizontal" id="setting_form1" enctype="multipart/form-data" action="{:U('Admin/Wx/wxShare')}">
                    <input name="form_submit"   type="hidden"  value="submit">
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>是否开启：</dt>
                                <dd class="left text-l">

                                    <div class="switch-box">
                                        <input type="checkbox" localrequired="" name="wx_share" id="switch-radio" class="switch-radio" <if condition="$config['wx_share'] eq 'on'">checked="true" </if>/>
                                        <span class="switch-half switch-open">ON</span>
                                        <span class="switch-half switch-close close-bg">OFF</span>
                                    </div>
                                    <p class="remind1">开启后微信分享使用配置信息</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>分享标题：</dt>
                                <dd class="left text-l">
                                    <input type="text" localrequired="" name="wx_sharetitle" class="com-inp1 radius3 boxsizing" value="{$config['wx_sharetitle']}">
                                        <p class="remind1">分享标题，微信分享标题</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>分享描述：</dt>
                                <dd class="left text-l">
                                    <textarea type="text" name="wx_sharedesc" class="com-textarea1 radius3 boxsizing"  localrequired="">{$config['wx_sharedesc']}</textarea>
                                        <p class="remind1">分享描述，将显示在前台微信分享中</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing remarks-dl">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>分享图片：</dt>
                                <dd class="left text-l">
                                    <div class="input-group">
                                        <span class="previewbtn"><img src="__PUBLIC__/admin/images/imgGray.png" class="viewimgbtn2 viewimg view_img" url="__ATTACH_HOST__{$config['wx_shareimg']}" id="logo_img"/></span>
                                        <input type="text" name="wx_shareimg" id="logotext" value="{$config['wx_shareimg']}" class="form-control upload-inp com-inp1 radius3 boxsizing">
                                        <input type="file"   id="shop_logo" class="form-control" style="display: none;">
                                        <span class="input-group-btn left">
                                            <input type="button"  value="选择文件" class="upload-btn search-btn" id="up" onclick="$('input[id=shop_logo]').click();"/>
                                        </span>
                                    </div>

                                    <p class="remind1">请使用100*100像素jpg/gif/png格式的图片。</p>
                                </dd>
                            </dl>

                        </div>
                        <div class="btnbox3 boxsizing">
                            <a type="button" id="wx_submit" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
                        </div>
                        </form>
                </div>

            </div>
        </div>  
        </div>  
        <!--内容结束-->
<script>
    $('#wx_submit').click(function(){
        flag=checkrequire('setting_form1');
        if(!flag){
            $('#setting_form1').submit();
        }
    });
    $(function(){
        $("#logo_img").posi({default_img:"DEFAULT_LOGO_IMAGE"});

    //图片上传;
    var uploader1 = new plupload.Uploader({
        runtimes: 'html5,html4,flash,silverlight',
        browse_button: 'shop_logo',
        url: "{:U('upLogoFile')}",
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
                uploader1.start();
            },
            UploadProgress: function (up, file) {
                //alert('这里可以做进度条');
            },
            FileUploaded: function (up, file, res) {
                var resobj = eval('(' + res.response + ')');
                if(resobj.status){
                    $("#logotext").val(resobj.data);
                }
            },
            Error: function (up, err) {
                alert('err');
            }
        }
        
    });
    uploader1.init(); 
    var uploader2 = new plupload.Uploader({
        runtimes: 'html5,html4,flash,silverlight',
        browse_button: 'shop_login',
        url: "{:U('upLogoFile')}",
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
                    $("#logintext").val(resobj.data);
                }
            },
            Error: function (up, err) {
                alert('err');
            }
        }
        
    });
    uploader2.init();
    
    $('.input-group').mouseenter(function(){
        $(this).find('.viewimg').attr('src','__PUBLIC__/admin/images/imgGreen.png');
    })
    $('.input-group').mouseleave(function(){
        $(this).find('.viewimg').attr('src','__PUBLIC__/admin/images/imgGray.png');
    })
    })

</script>
<script language="javascript" type="text/javascript">
    function isNum(a)
    {
        var reg =/^\d*?$/;
        return reg.test(a);
    }

    $('#enterprise_contact').blur(function(){
        var str = $('#enterprise_contact').val();
        if (!isNum(str)){
            showError('输入错误','只允许输入数字，请重新输入！');
            $('#enterprise_contact').val('');
        }
    })
</script>

