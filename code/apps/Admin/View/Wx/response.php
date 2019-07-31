
        <!--<div class="tip-remind">收起提示</div>-->
        <div class="tipsbox radius3">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.设置用户关注微信公众号时回复的内容。</li>
                <li>2.设置用户输入特殊字符时默认回复的内容。</li>
            </ol>
        </div>
        <!--内容开始-->
        <div class="iframeCon">
		<div class="iframeMain">
            <ul class="transverse-nav">
                <li class="<?php if($_GET['active'] == ''){echo 'activeFour';}else{echo '';} ?>"><a href="{:U('Admin/Wx/wxrespons')}"><span>关注回复</span></a></li>
                <li class="<?php if($_GET['active'] == 1){echo 'activeFour';}else{echo '';} ?>"><a href="{:U('Admin/Wx/wxrespons')}?active=1"><span>默认回复</span></a></li>
            </ul>
            <div class="white-bg">
                <?php if($_GET['active'] == ''){ ?>
                <div class="tab-conbox">
                    <form method="post" class="form-horizontal" id="setting_form1" enctype="multipart/form-data" action="{:U('Admin/Wx/wxloginOp')}">
                    <input name="form_submit"   type="hidden"  value="submit">
                    <input name="url"   type="hidden"  value="wxRespons">
                    <input name="sub" type="hidden" value="1">
                        <div class="jurisdiction boxsizing">

                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing">关注回复语：</dt>
                                <dd class="left text-l">
                                    <textarea type="text" name="wx_lookresponse" class="com-textarea1 radius3 boxsizing" placeholder="" >{$config['wx_lookresponse']}</textarea>
                                        <p class="remind1">微信用户关注公众号时，回复的内容。</p>
                                </dd>
                            </dl>

                        </div>
                        <div class="btnbox3 boxsizing">
                            <a type="button" id="base_setting" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
                        </div>
                        </form>
                </div>
                <?php } ?>
                <?php if($_GET['active'] == 1){ ?>
                <div class="tab-conbox">
                    <form class="form-horizontal" method="post" id="setting_form2" enctype="multipart/form-data" action="{:U('Admin/Wx/wxloginOp')}?active=1">
                    <input name="form_submit"   type="hidden"  value="submit">
                        <input name="url"   type="hidden"  value="wxRespons">
                    <input name="sub" type="hidden" value="2">
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing">默认回复语：</dt>
                                <dd class="left text-l">
                                    <textarea type="text" name="wx_defaultresponse" class="com-textarea1 radius3 boxsizing" placeholder="" >{$config['wx_defaultresponse']}</textarea>
                                    <p class="remind1">微信用户输入一些特殊字符回复的内容</p>
                                </dd>
                            </dl>
                        </div>
                        <div class="btnbox3 boxsizing">
                            <a type="button" id="agreement" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
                        </div>
                    </form>
                </div>
                <?php } ?>
            </div>
        </div>  
        </div>  
        <!--内容结束-->
<script>
$('#agreement').click(function(){
   $('#setting_form2').submit();
});
$('#base_setting').click(function(){
   $('#setting_form1').submit();
});
    $(function(){
        $(".viewimgbtn1").mouseenter(function(){
           Size(600,100);
           event=arguments.callee.caller.arguments[0] || window.event; 
           Posi1(event);
        });
        $(".viewimgbtn1").mouseleave(function(){
            $(this).parents('.input-group').next().hide();
        })
        $(".viewimgbtn2").mouseenter(function(){
           Size(300,300);
          event=arguments.callee.caller.arguments[0] || window.event; 
           Posi2(event);
        });
        $(".viewimgbtn2").mouseleave(function(){
            $(this).parents('.input-group').next().hide();
        })
    
     //点击关闭图片预览 
    $('.view-close').bind('click',function(){
        $(this).parents('.viewdiv').css('display','none');
    })
    function Size(wid,heig){
        $('.viewdiv').find('.view-img').css({'width':wid,'height':heig});
        $('.viewdiv').css({'width':wid+24,'height':heig+24});
    }
    function Posi1(event){
        var x =++event.pageX+'px';
        var y =++event.pageY+'px';
        $('.viewimgbtn1').parents('.input-group').next().toggle();
        $('.viewimgbtn1').parents('.input-group').next().css("left",x);
        $('.viewimgbtn1').parents('.input-group').next().css("top",(y-70)+'px');
    }
    function Posi2(event){
        var x =++event.pageX+'px';
        var y =++event.pageY+'px';
        $('.viewimgbtn2').parents('.input-group').next().toggle();
        $('.viewimgbtn2').parents('.input-group').next().css("left",x);
        $('.viewimgbtn2').parents('.input-group').next().css("top",(y-70)+'px');
    }
    
    
    function viewBlock(){
        $('.viewdiv').each(function(){
        _this = this;
        if($(_this).is(':visible')== true){
            alert('显示');
            $(_this).siblings().hide();
        }
    })
    }
    
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

<script type="text/javascript">
    function isNum(a)
    {
        var reg =/^\d*?$/;
        return reg.test(a);
    }

    $('#enterprise_contact').blur(function(){
        var str = $('#enterprise_contact').val();
        if (!isNum(str)){
            showError('只允许输入数字，请重新输入！');
            $('#enterprise_contact').val('');
        }
    })
</script>

<script type="text/javascript">
    $('#base_setting').click(function(){
        flag=checkrequire('setting_form1');
        if(!flag){
            $('#setting_form1').submit();
        }
    });
    $('#agreement').click(function(){
        flag=checkrequire('setting_form2');
        if(!flag){
            $('#setting_form2').submit();
        }
    });
</script>

