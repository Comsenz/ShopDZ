<style type="text/css">
	.img-cover {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: #000;
		opacity: .2;
		display: none;
	}
	.edit-box1:hover div.img-cover {
		display: block;
	}
	.edit-btn1 {
		width: 90px;
		height: 28px;
		line-height: 28px;
		font-size: 14px;
		/*display: block;*/
		left: 150px;
		top: 130px;
		border-radius: 3px;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		padding: 0;
		box-shadow: 0 0 5px #999;
	}
	.add-btn {
		position: absolute;
		width: 90px;
		height: 28px;
		line-height: 28px;
		font-size: 14px;
		left: 210px;
		top: 200px;
		border-radius: 3px;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		padding: 0;
		background: #00d4b7;
		cursor: pointer;
		
	}
	.add-btn a {
		color: #fff;
		display: block;
	}
    body select {
        border-radius: 3px;
        height: 28px;
        width: 100px;
        min-width: 100px;
        color: #666;
        border: 1px solid #e0e0e0;
        background: #fafafa;
        padding-right: 14px;
    }
	.moren {
		width:500px;
		height: 300px;
		background: #e0e0e0;
	}
	.picbg {
		display: inline-block;
		margin-top: 80px;
	}
</style>



<if condition="!$edit">
<!--焦点图轮播开始-->
<div class="swiper-container" style="margin-top: 48px;" data-type="adv_list">
    <div class="swiper-wrapper">
        <foreach name="vo.data" item="vo1">
            <div class="swiper-slide"><img src="__ATTACH_HOST__{$vo1.img}"/></div>
        </foreach>
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
</div>
<!--焦点图轮播结束-->
<else />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/extends.css?v=1" />
    <!--<div class="tip-remind">收起提示</div>-->
    <div class="tipsbox radius3">
        <div class="tips boxsizing radius3">
            <div class="tips-titbox">
                <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                <span class="open-span span-icon"><i class="open-icon"></i></span>
            </div>
        </div>
        <ol class="tips-list" id="tips-list">
            <li>1.点击上传图片按钮可以添加新的幻灯片图片。</li>
            <li>2.鼠标移动到已有的幻灯片图片上点击出现的删除按钮可以删除对应的幻灯片图片。</li>
            <li>3.操作完成后点击确认提交按钮进行保存。</li>
            <li>4.建议使用700*400像素大小jpg、gif、png格式的图片。</li>
        </ol>
    </div>
    <div class="iframeCon">
        <div class="iframeMain">
            <ul class="transverse-nav">
                <li class="activeFour"><a href="javascript:;"><span>幻灯管理</span></a></li>
            </ul>
            <div class="white-bg ">
                <div class="tab-conbox">
                    <div class="jurisdiction boxsizing">
                	 
                    <foreach name="item_data" item="vo">
                        <div class="edit-box1 radius3" style="margin-left: 100px;">
                        	<div class="img-cover"></div>
                            <div class="edit-btn1" style="left: 260px;"><a href='javascript:delItem("{$key}");'>删除</a></div>
                            <div class="edit-btn1" style="right:60px"><a class='alert-btn1' href='javascript:editItem("{$key}");'>编辑</a></div>
                            <img alt="image" src="__ATTACH_HOST__{$vo.img}" class="edit-img1">
                        </div>
                    
                       
                    </foreach>
                    <?php if(count($item_data) < 8){ ?>
                    <div class="edit-box1 radius3" style="margin-left: 100px;">
                        <div class="moren">
                            <img src="__PUBLIC__/admin/images/picbg.png" class="picbg"/>
                             <div class="add-btn"><a type="button" class='alert-btn' onclick='$("#img").val("");$("#type").val("");$("#data").val("");'>添加</a></div>
                        </div>
                    </div>
                    <?php } ?>
                    </div>
                </div>
                <div class="btnbox-c" style="padding: 40px 0;">
               	 	<a type="button" class="btn1 radius3 marginT10 btn3-btnmargin" style="margin-left: 300px;" href="{:U('setting/personnel')}">返回上页</a>
                    <!--<a type="button" class="alert-btn btn1 radius3" onclick='$("#img").val("");$("#type").val("");$("#data").val("");'>上传图片</a>-->
           		</div>
            </div>
        </div>
    </div>
<!--遮罩层-->
<div class="cover none"></div>
<!--弹框开始-->
<div class="alertcon alert showAlert radius3 addFocus-alert specialAlert none">
    <i class="close-icon"></i>
    <h1 class="special-tit">添加幻灯图片</h1>
    <form role="form" id="dataform" action="{:U('itemSave')}" autocomplete="off">
    <input name="form_submit"   type="hidden"  value="submit">
        <div class="special-con addSpec-con">
            <span class="special-con-left left">图片：</span>
            <div class="input-group">
                <span class="previewbtn"><img src="__PUBLIC__/admin/images/imgGray.png" class="viewimgbtn3 viewimg view_img" url="" id="upload_img"/></span>
                <input type="text" name="img" id="img" class="form-control upload-inp com-inp1 radius3 boxsizing" localrequired="">
                <input type="hidden" name="item_id" id="item_id"  value="{$edit}">
                <input type="hidden" name="item_type" id="item_type"  value="{$item_type}">
                <input type="hidden" name="dotype" id="dotype"  value="">
                <input type="hidden" name="num" id="num"  value="">
                <input type="file"   id="img_adv" class="form-control" style="display: none;">
                <span class="input-group-btn left">
                    <input type="button"  value="选择文件" class="upload-btn search-btn" id="up" onclick="$('input[id=img_adv]').click();"/>
                </span>
            </div>
            <div class="clear"></div>
            <div class="alerrt-sele-box">
                <span class="special-con-left left">操作：</span>
                <select name="type" id="type" class="left addFocus-sele" localrequired="">
                    <option value="url">链接</option>
                    <option value="goods">SPU ID</option>
                    <option value="category">商品分类</option>
                    <!-- <option value="redpacket">优惠券</option> -->
                </select>
                <input type="text" class="com-inp1 radius3 boxsizing left addFocus-inp" name="data" id="data" localrequired="">

                <div class="clear"></div>
            </div>
        </div>
        <div class="alert-btnbox boxsizing">
            <a class="btn1 radius3" id="alert_submit" type="button" href="javascript:;">确认</a>
        </div>
    </form>
</div>
<!--弹框结束-->
<script type="text/javascript">
$(document).posi({class:'view_img','z_index':'10001'});
$('#alert_submit').click(function(){
    flag=checkrequire('dataform');
    if(!flag){
        $.post("{:U('itemSave')}",$("#dataform").serialize(),function(data){
            if(data.status===0){
                showError(data.info);
            }else{                
                window.location.reload();
            }

        },'json');
    }
});
$(function(){
    $('.alert-btn').click(function(){
        $.ajax({
            type:'GET',
            url:"{:U('isItemExist')}",
            data:"item_id={$edit}&item_type={$item_type}",
            success:function(data){
                if(data.status===0){
                    showError(data.info);
                }else{
                    $('.cover').removeClass('none');
                    $('.alertcon').removeClass('none');
                    $('#dotype').val('');
                    $('#num').val('');
                    $('#type').val('url');
                    var strs = '<input type="text" class="com-inp1 radius3 boxsizing left addFocus-inp" name="data" id="data" localrequired="">';
                    $('#data').replaceWith(strs);
                    $('#data').val('');
                    $('#img').val('');
                    $('#upload_img').attr('url','');



                }
            },
            dataType:'json'
        });
    })

    $('.alert-btn1').click(function(){
        $('.cover').removeClass('none');
        $('.alertcon').removeClass('none');
    })
        
    $('.cover,.close-icon').click(function(){
        $('.cover').addClass('none');
        $('.alertcon').addClass('none');
    })
    
})
</script>
<script type="text/javascript">


    //删除图片
    function delItem(path) {
        showConfirm("将删除此图片，确认操作吗？",function(){
            var url = "{:U('itemSave')}"+'?img='+path+'&dotype=del&item_type='+$("#item_type").val()+'&item_id='+$("#item_id").val();
            $.get(url,function(data){
                if(data.status == 1){
                    showSuccess(data.info,function(){
                    window.location.reload();
                  });
                }else{
                    showError(data.info);
                }
            },'json')

        });
    }


    function editItem(path){

        var url="{:U('imgEdit')}"+'?img='+path+'&item_type='+$("#item_type").val()+'&item_id='+$("#item_id").val();
        $.get(url,function(result){
            var data = result.data;
            $("#type").val(data.type);
            $("#dotype").val('edit');
            $("#num").val(path);
            $('#img').val(data.img);
            $('#upload_img').attr('url',"__ATTACH_HOST__"+data.img);
            $('#upload_img').posi({'z_index':'10001','position':'fixed'});

            if(data.type == 'redpacket'){
                var str3 = '<select class="left addFocus-sele" name="data" id="data" localrequired=""><?php foreach ($rpacket_list as $v) {
                    echo '<option value="'.$v['rpacket_t_id'].'">'.$v['rpacket_t_title'].'</option>';
            }?></select>';
                $('#data').replaceWith(str3);
            }else if(data.type == 'category'){
            var str3 = '<select class="left addFocus-sele" name="data" id="data" localrequired=""><?php foreach ($category_list as $one) { if(!empty($one["child"])){echo '<option value="'.$one["gc_id"].'">'.$one["gc_name"].'</option>';foreach ($one["child"] as $two) { echo '<option value="'.$two["gc_id"].'">--'.$two["gc_name"].'</option>'; } }else{echo '<option value="'.$one["gc_id"].'">'.$one["gc_name"].'</option>';}}?></select>';

            $('#data').replaceWith(str3);
        }else{
                var str3 = '<input type="text" class="com-inp1 radius3 boxsizing left addFocus-inp" name="data" id="data" localrequired="">';
                $('#data').replaceWith(str3);
            }
            $('#data').val(data.data);

        })


    };
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
                    $('#upload_img').attr('url',"__ATTACH_HOST__"+resobj.data);
                }
            },
            Error: function (up, err) {
                alert('err');
            }
        }
    });
    uploader.init();

    $('.edit-box1').hover(function(){
     $(this).addClass('edit-box1-bor');
     $(this).children('.edit-btn1').css('display','block')
    },function(){
     $(this).removeClass('edit-box1-bor');
     $(this).children('.edit-btn1').css('display','none')
    });


   $(function(){
   	

    $('#type').change(function(){
        var types = $(this).val();

        if(types == 'redpacket'){
            var str_1 = '<select class="left addFocus-sele" name="data" id="data" localrequired=""><?php foreach ($rpacket_list as $v) {
                echo '<option value="'.$v['rpacket_t_id'].'">'.$v['rpacket_t_title'].'</option>';
        }?></select>';
            $('#data').replaceWith(str_1);
        }else if(types == 'category'){
            var str_2 = '<select class="left addFocus-sele" name="data" id="data" localrequired=""><?php foreach ($category_list as $one) { if(!empty($one["child"])){echo '<option value="'.$one["gc_id"].'">'.$one["gc_name"].'</option>';foreach ($one["child"] as $two) { echo '<option value="'.$two["gc_id"].'">--'.$two["gc_name"].'</option>'; } }else{echo '<option value="'.$one["gc_id"].'">'.$one["gc_name"].'</option>';}}?></select>';

            $('#data').replaceWith(str_2);
        }else{
            var str_3 = '<input type="text" class="com-inp1 radius3 boxsizing left addFocus-inp" name="data" id="data" localrequired="">';
            $('#data').replaceWith(str_3);
        }
    });
   })
</script>
</if>
