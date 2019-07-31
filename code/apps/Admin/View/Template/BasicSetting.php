
	<title>基本设置</title>
  	<link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css" />
  	<link rel="stylesheet" href="__PUBLIC__/css/style.min862f.css" />
  	<link href="__PUBLIC__/css/plugins/switchery/switchery.css" rel="stylesheet">
  	<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
  	<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
  	<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>
	<div class="content">
        <!--切换内容-->
		<div id="sidebar-tab" class="sidebar-tab"> 
			<div id="tab-title" class="tab-title"> 
				<!--<h3><span class="selected">最新评论</span><span>近期热评</span><span>随机文章</span></h3> -->
				<ul>
					<li class="selected">商城设置</li>
					<li>图片设置</li>
				</ul>
			</div> 
			<div id="tab-content" class="sidebar-con"> 
				<div>
					<div class="jurisdiction">
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>网站名称：</dt>
							<dd class="left text-l">
								<input type="text" class="com-inp1 radius3 boxsizing"/>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>网站底部信息：</dt>
							<dd class="left text-l">
								<input type="text" class="com-inp1 radius3 boxsizing"/>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>ICP证书号：</dt>
							<dd class="left text-l">
								<input type="text" class="com-inp1 radius3 boxsizing"/>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>企业名称：</dt>
							<dd class="left text-l">
								<input type="text" class="com-inp1 radius3 boxsizing"/>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>企业地址：</dt>
							<dd class="left text-l">
								<input type="text" class="com-inp1 radius3 boxsizing"/>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>企业联系方式：</dt>
							<dd class="left text-l">
								<input type="text" class="com-inp1 radius3 boxsizing"/>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>第三方流量统计代码：</dt>
							<dd class="left text-l">
								<textarea type="text" class="com-textarea1 radius3 boxsizing"></textarea>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>站点状态：</dt>
							<dd class="left text-l">
								<div class="switch">
		                            <div class="onoffswitch">
		                                <input type="checkbox" checked class="onoffswitch-checkbox" id="example1">
		                                <label class="onoffswitch-label" for="example1">
		                                    <span class="onoffswitch-inner"></span>
		                                    <span class="onoffswitch-switch"></span>
		                                </label>
		                            </div>
		                        </div>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>关闭原因：</dt>
							<dd class="left text-l">
								<textarea type="text" class="com-textarea1 radius3 boxsizing"></textarea>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
					</div>
					<div class="btnbox3">
						<a class="btn1 radius3 btn-margin">确认提交</a>
					</div>
				</div> 
				<div class="hide2">
					<div class="jurisdiction">
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>网站LOGO：</dt>
							<dd class="left text-l">
								<div class="input-group">
		                            <span class="input-group-btn"><button type="button" class="btn btn-primary viewimgbtn1 left com-btn1">预览</button></span>
		                            <input type="text" name="shop_logo" id="logotext" value="{$config['shop_logo']}" class="form-control upload-inp com-inp1">
		                            <input type="file"   id="shop_logo" class="form-control" style="display: none;">
                                    <span class="input-group-btn left">
                                        <button class="btn btn-white com-btn1" id="up" type="button" onclick="$('input[id=shop_logo]').click();">选择文件</button>
                                    </span>
		                        </div>
		                        <div class="viewdiv">
									<div class="viewdiv-tit boxsizing">
										<h2 class="left view-tit">图片预览</h2>
										<div class="fontface3 fa-times right view-close"></div>
									</div>
									<img src="__PUBLIC__/img/p_big1.jpg" class="view-img"/>
								</div>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
						
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>登录页图片：</dt>
							<dd class="left text-l">
								<div class="input-group m-b">
		                            <span class="input-group-btn"><button type="button" class="btn btn-primary viewimgbtn2 left com-btn1">预览</button></span>
		                            <input type="text" name="shop_logo" id="logotext" value="{$config['shop_logo']}" class="form-control upload-inp com-inp1">
		                            <input type="file" id="shop_logo" class="form-control" style="display: none;">
                                    <span class="input-group-btn left">
                                        <button class="btn btn-white com-btn1" id="up" type="button" onclick="$('input[id=shop_logo]').click();">选择文件</button>
                                    </span>
		                        </div>
		                        <div class="viewdiv">
									<div class="viewdiv-tit boxsizing">
										<h2 class="left view-tit">图片预览</h2>
										<div class="fontface3 fa-times right view-close"></div>
									</div>
									<img src="__PUBLIC__/img/p_big1.jpg" class="view-img"/>
								</div>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>会员默认头像：</dt>
							<dd class="left text-l">
								<div class="input-group m-b">
		                            <span class="input-group-btn"><button type="button" class="btn btn-primary viewimgbtn3 left com-btn1">预览</button></span>
		                            <input type="text" name="shop_logo" id="logotext" value="{$config['shop_logo']}" class="form-control upload-inp com-inp1">
		                            <input type="file"   id="shop_logo" class="form-control" style="display: none;">
                                    <span class="input-group-btn left">
                                        <button class="btn btn-white com-btn1" id="up" type="button" onclick="$('input[id=shop_logo]').click();">选择文件</button>
                                    </span>
		                        </div>
		                        <div class="viewdiv">
									<div class="viewdiv-tit boxsizing">
										<h2 class="left view-tit">图片预览</h2>
										<div class="fontface3 fa-times right view-close"></div>
									</div>
									<img src="__PUBLIC__/img/p_big1.jpg" class="view-img"/>
								</div>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing">
							<dt class="left text-r boxsizing"><span class="redstar">*</span>默认商品图片：</dt>
							<dd class="left text-l">
								<div class="input-group m-b">
		                            <span class="input-group-btn"><button type="button" class="btn btn-primary viewimgbtn4 left com-btn1">预览</button></span>
		                            <input type="text" name="shop_logo" id="logotext" value="{$config['shop_logo']}" class="form-control upload-inp com-inp1">
		                            <input type="file"   id="shop_logo" class="form-control" style="display: none;">
                                    <span class="input-group-btn left">
                                        <button class="btn btn-white com-btn1" id="up" type="button" onclick="$('input[id=shop_logo]').click();">选择文件</button>
                                    </span>
		                        </div>
		                        <div class="viewdiv">
									<div class="viewdiv-tit boxsizing">
										<h2 class="left view-tit">图片预览</h2>
										<div class="fontface3 fa-times right view-close"></div>
									</div>
									<img src="__PUBLIC__/img/p_big1.jpg" class="view-img"/>
								</div>
								<p class="remind1">这是提示文字</p>
							</dd>
						</dl>
					</div>
					<div class="btnbox3">
						<a class="btn1 radius3 btn-margin">确认提交</a>
					</div>
				</div> 
			</div> 
		</div>
	</div>
	<!--content结束-->
<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/alert.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/tab.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/bootstrap.min.js"></script>
<script src="__PUBLIC__/js/plugins/switchery/switchery.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".viewimgbtn1").click(function(){
           Size(400,200);
           event=arguments.callee.caller.arguments[0] || window.event; 
           Posi1(event);
        });
        $(".viewimgbtn2").click(function(){
           Size(300,300);
          event=arguments.callee.caller.arguments[0] || window.event; 
           Posi2(event);
        });
        $(".viewimgbtn3").click(function(){
           Size(300,100);
          event=arguments.callee.caller.arguments[0] || window.event; 
           Posi3(event);
        });$(".viewimgbtn4").click(function(){
           Size(400,100);
          event=arguments.callee.caller.arguments[0] || window.event; 
           Posi4(event);
        });
        
         //点击关闭图片预览 
        $('.view-close').bind('click',function(){
			$(this).parents('.viewdiv').css('display','none');
		})
    });
		 //图片上传;
    var uploader = new plupload.Uploader({
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
                uploader.start();
            },
            UploadProgress: function (up, file) {
                //alert('这里可以做进度条');
            },
            FileUploaded: function (up, file, res) {
                var resobj = eval('(' + res.response + ')');
                if(resobj.status){
                    console.log(resobj.data);
                    $("#view").attr("src","__ATTACH_HOST__"+resobj.data);
                    $("#logotext").val(resobj.data);
                }
            },
            Error: function (up, err) {
                alert('err');
            }
        }
        
    });
    uploader.init();
    
 	

	//图片设置不同的尺寸
	function Size(wid,heig){
		$('.viewdiv').find('img').css({'width':wid,'height':heig});
		$('.viewdiv').css({'width':wid+24,'height':heig+54});
	}
	function Posi1(event){
		var x =++event.pageX+'px';
        var y =++event.pageY+'px';
        $('.viewimgbtn1').parents('.input-group').next().toggle();
        $('.viewimgbtn1').parents('.input-group').next().css("left",x);
        $('.viewimgbtn1').parents('.input-group').next().css("top",y);
	}
	function Posi2(event){
		var x =++event.pageX+'px';
        var y =++event.pageY+'px';
        $('.viewimgbtn2').parents('.input-group').next().toggle();
        $('.viewimgbtn2').parents('.input-group').next().css("left",x);
        $('.viewimgbtn2').parents('.input-group').next().css("top",y);
	}
	function Posi3(event){
		var x =++event.pageX+'px';
        var y =++event.pageY+'px';
        $('.viewimgbtn3').parents('.input-group').next().toggle();
        $('.viewimgbtn3').parents('.input-group').next().css("left",x);
        $('.viewimgbtn3').parents('.input-group').next().css("top",y);
	}
	function Posi4(event){
		var x =++event.pageX+'px';
        var y =++event.pageY+'px';
        $('.viewimgbtn4').parents('.input-group').next().toggle();
        $('.viewimgbtn4').parents('.input-group').next().css("left",x);
        $('.viewimgbtn4').parents('.input-group').next().css("top",y);
	}
    
</script>







