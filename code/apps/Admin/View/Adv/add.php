

<head>
	<meta charset="UTF-8">
	<title>设置广告</title>

  	<link rel="stylesheet" href="__PUBLIC__/css/bootstrap-datetimepicker.css" />
	<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
	<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>
	<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
	<style type="text/css">
		.juris-dl {overflow: auto;}
		.time-inp-m{
			margin-bottom:10px;
		}
		dt{
			font-weight: normal !important;
		}
	</style>
</head>
<div class="content">
	<form method="post" class="form-horizontal" name="advform" id="advform"
enctype="multipart/form-data">
    <input type="hidden" id="do" value="{$do}" />
    <input type="hidden" id="aid" value="{$adv.id}" />
	<div class="jurisdiction">
		<dl class="juris-dl boxsizing">
			<dt class="left text-r boxsizing"><span class="redstar">*</span>标题：</dt>
			<dd class="left text-l">
				<input type="text" value="{$adv.title}" name="title" id="title" class="com-inp1 radius3 boxsizing"/>
				<p class="remind1">设置名称</p>
			</dd>
		</dl>
		<dl class="juris-dl boxsizing">
			<dt class="left text-r boxsizing"><span class="redstar">*</span>位置：</dt>
			<dd class="left text-l">
				<select class="com-sele radius3 juris-dl-sele" name="position">
					<option value="top" <if condition="$adv['position'] eq 'top'">  selected="selected" </if>>顶部浮层</option>
                    <option value="bottom" <if condition="$adv['position'] eq 'bottom'">  selected="selected" </if> >底部浮层</option>
                    <option value="window" <if condition="$adv['position'] eq 'window'">  selected="selected" </if> >弹窗浮层</option>
				</select>
				<p class="remind1">广告方式</p>
			</dd>
		</dl>
		<dl class="juris-dl boxsizing">
			<dt class="left text-r boxsizing"><span class="redstar">*</span>图片选择：</dt>
			<dd class="left text-l">
                <div class="input-group">
                    <span class="input-group-btn"><button type="button" class="btn btn-primary viewimgbtn1 left com-btn1">预览</button></span>
                    <input type="text" name="img" id="img" value="{$adv['img']}" class="form-control upload-inp com-inp1">
                    <input type="file" id="img_adv" class="form-control" style="display: none;">
                    <span class="input-group-btn left">
                        <button class="btn2 test-btn com-btn1 upload-btn2" id="up" type="button" onclick="$('input[id=img_adv]').click();">选择文件</button>
                    </span>
                </div>
                <div class="viewdiv">
                    <div class="viewdiv-tit boxsizing">
                        <h2 class="left view-tit">图片预览</h2>
                        <div class="fontface3 fa-times right view-close"></div>
                    </div>
                    <if condition="$adv['img'] neq '' ">
                        <img src="__ATTACH_HOST__{$adv['img']}" class="view-img"/>
                    <else />
                        <img src="__PUBLIC__/img/default.png" class="view-img"/>
                     </if>
                        
                </div>
                <p class="remind1"></p>
            </dd>
		</dl>
		

		<dl class="juris-dl boxsizing">
			<dt class="left text-r boxsizing"><span class="redstar">*</span>操作类型：</dt>
			<dd class="left text-l">
				<select class="com-sele radius3 juris-dl-sele" name="clickevent" style="width:auto;">
                    <option value="url" <if condition="$adv['clickevent'] eq 'url'">  selected="selected" </if>>链接</option>
                    <option value="goods" <if condition="$adv['clickevent'] eq 'goods'">  selected="selected" </if>>商品ID</option>
                    <option value="category" <if condition="$adv['clickevent'] eq 'category'">  selected="selected" </if>>商品分类ID</option>
                    <option value="redpacket" <if condition="$adv['clickevent'] eq 'redpacket'">  selected="selected" </if>>优惠券ID</option>
                </select>
                <input type="text" class="com-inp1 radius3 boxsizing" value="{$adv.extendfield}" name="extendfield" id="extendfield">
			</dd>
		</dl>
		
		<dl class="juris-dl boxsizing" style="overflow: visible;height: 90px;">
			<dt class="left text-r boxsizing"><span class="redstar">*</span>选择时间</dt>
			<dd class="left text-l">
				<div class='input-group date left time-width' data-date="2012-02-20" data-date-format="yyyy-mm-dd">
					<input type="text" placeholder="开始日期" class="datetimepicker2 com-inp1 radius3 boxsizing layer-date time-inp-m" name="starttime" value="{$adv.starttime|date='Y-m-d H:i',###}"/>
                    <input type="text" placeholder="结束日期" class="datetimepicker3 com-inp1 radius3 boxsizing layer-date time-inp-m" name="endtime" value="{$adv.endtime|date='Y-m-d H:i',###}"/>
                </div>
			</dd>
		</dl>
		
	</div>
	<div class="btnbox3">
		<button class="btn1 radius3 footbtn" type="submit">{$Think.lang.submit_btn}</button>
		<a class="btn1 radius3 footbtn" href="{:U('setting/advert')}">返回列表</a>
	</div>
	</form>
</div>

<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script src="__PUBLIC__/js/moment.js" charset="UTF-8"></script>
<script src="__PUBLIC__/js/moment-with-locales.js" charset="UTF-8"></script>
<script src="__PUBLIC__/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript">
	$(document).ready(function() {  
		 $('.datetimepicker2').datetimepicker({
		 	format: 'YYYY-MM-DD hh:mm',
		 	useCurrent: false,
            locale: 'zh-CN'
           
        });
        $('.datetimepicker3').datetimepicker({
        	format: 'YYYY-MM-DD hh:mm',
        	useCurrent: false,
            locale: 'zh-CN'
            
        });
	}); 

	$(".viewimgbtn1").click(function(){
       Size(400,200);
       event=arguments.callee.caller.arguments[0] || window.event; 
       Posi1(event);
    });
    //点击关闭图片预览 
        $('.view-close').bind('click',function(){
			$(this).parents('.viewdiv').css('display','none');
		})

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

    $(function(){
        var n = $("#do").val() ? $("#do").val() : 'add';
        var aid = $("#aid").val() ? $("#aid").val() : '0';
        if(n == 'edit'){
            $("#advform").ajaxForm({
                url:"{:U('Adv/edit')}"+'?ajax=1&aid='+aid,
                dataType: 'json',
                success: function(response) {
                    getResultDialog(response);
                }
            });
        }else{
            $("#advform").ajaxForm({
                url:"{:U('Adv/add')}"+'?ajax=1',
                dataType: 'json',
                success: function(response) {
                    getResultDialog(response);
                }
            });
        }
    });
    //图片上传;
    var uploader = new plupload.Uploader({
        runtimes: 'html5,html4,flash,silverlight',
        browse_button: 'img_adv',
        url: "{:U('Upload/common')}",
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
                    $("#img").val(resobj.data);
                }
            },
            Error: function (up, err) {
                alert('err');
            }
        }
    });
    uploader.init();
</script>
