
        <!--<div class="tip-remind">收起提示</div>-->
        <div class="tipsbox radius3">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.网站全局基本设置，商城及其他模块相关内容在其各自栏目设置项内进行操作。</li>
                <li>2.保存好基本设置后，务必要更新商城缓存。</li>
            </ol>
        </div>
        <!--内容开始-->
        <div class="iframeCon">
		<div class="iframeMain">
          <include file="nav" />
            <div class="white-bg ">
			   <div class="tab-conbox3">
                    <form class="form-horizontal" method="post" id="setting_form3" enctype="multipart/form-data" action="{:U('Admin/Setting/base')}">
                    <input name="form_submit"   type="hidden"  value="submit">
                    <input name="sub" type="hidden" value="3">
						   <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>{$Think.lang.verify_input_text}</dt>
                                <dd class="left text-l">
                                    <div class="switch-box">
                                        <input type="checkbox" name="verify_status" id="switch-radio" class="switch-radio" <if condition="$config['verify_status'] eq 1 ">checked="checked" <else /> </if> <if condition="$config['verify_status'] eq '' ">checked="checked" <else /> </if>/>
                                        <span class="switch-half switch-open">ON</span>
                                        <span class="switch-half switch-close close-bg">OFF</span>
                                    </div>
                                    <p class="remind1">{$Think.lang.verify_input_tip_text}</p>
                                </dd>
                            </dl>
                        <div class="btnbox3 boxsizing">
                            <a type="button" id="verify_status" class="btn1 radius3 marginT10 btn3-btnmargin">{$Think.lang.submit_btn}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
        </div>  
        <!--内容结束-->
<script>
    $(function(){
        // $(".viewimgbtn1").mouseenter(function(){
        //    Size(600,100);
        //    event=arguments.callee.caller.arguments[0] || window.event; 
        //    Posi1(event);
        // });
        // $(".viewimgbtn1").mouseleave(function(){
        //     $(this).parents('.input-group').next().hide();
        // })
        // $(".viewimgbtn2").mouseenter(function(){
        //    Size(300,300);
        //   event=arguments.callee.caller.arguments[0] || window.event; 
        //    Posi2(event);
        // });
        // $(".viewimgbtn2").mouseleave(function(){
        //     $(this).parents('.input-group').next().hide();
        // })
    
     //点击关闭图片预览 
    // $('.view-close').bind('click',function(){
    //     $(this).parents('.viewdiv').css('display','none');
    // })
    // function Size(wid,heig){
    //     $('.viewdiv').find('.view-img').css({'width':wid,'height':heig});
    //     $('.viewdiv').css({'width':wid+24,'height':heig+24});
    // }
    // function Posi1(event){
    //     var x =++event.pageX+'px';
    //     var y =++event.pageY+'px';
    //     $('.viewimgbtn1').parents('.input-group').next().toggle();
    //     $('.viewimgbtn1').parents('.input-group').next().css("left",x);
    //     $('.viewimgbtn1').parents('.input-group').next().css("top",(y-70)+'px');
    // }
    // function Posi2(event){
    //     var x =++event.pageX+'px';
    //     var y =++event.pageY+'px';
    //     $('.viewimgbtn2').parents('.input-group').next().toggle();
    //     $('.viewimgbtn2').parents('.input-group').next().css("left",x);
    //     $('.viewimgbtn2').parents('.input-group').next().css("top",(y-70)+'px');
    // }
    
    
    // function viewBlock(){
    //     $('.viewdiv').each(function(){
    //     _this = this;
    //     if($(_this).is(':visible')== true){
    //         alert('显示');
    //         $(_this).siblings().hide();
    //     }
    // })
    // }
    
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
	$('#verify_status').click(function() {
		  $('#setting_form3').submit();
	});
    $("#logo_img").posi({default_img:"DEFAULT_LOGO_IMAGE"});
    $("#login_img").posi({default_img:"DEFAULT_LOGIN_IMAGE"});

</script>

