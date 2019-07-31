<div class="tipsbox radius3">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		<li>1.商品分类将会对应到前台的商品分类列表里，添加新商品时可选择添加到某分类下。</li>
		<li>2.上级分类默认为顶级分类（一级分类），上级分类选择一级类分类则新建的分类为二级分类。</li>
		<li>3.分类描述会显示在商城分类列表的顶部。</li>
		<li>4.可对分类设置开启或关闭显示。</li>
		<li>5.可填写分类的排序值来调节分类的显示顺序，分类值越大显示的优先级越高。</li>
	</ol>
</div>

<!--内容开始-->
<div class="iframeCon">
<div class="iframeMain">
    <ul class="transverse-nav">
        <li class="activeFour"><a href="<?php echo U('Commodity/category_add');?>"><span>添加商品分类</span></a></li>
    </ul>
	<div class="white-bg">
	    <form  method="post"  autocomplte="off"  action="<?php echo U('Commodity/category_add');?>"   method="post"  id="main_form"  >  
		<div class="tab-conbox">
			<div class="jurisdiction boxsizing">
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>分类名称：</dt>
					<dd class="left text-l">
					    <input type="text" name="gc_name"   localrequired="localrequired"  class="com-inp1 radius3 boxsizing" />
						<p class="remind1">请输入商品分类名称</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>上级分类：</dt>
					<dd class="left text-l">
					<div class=" boxsizing radius3 inline borderR-none">
						<select   class="left addFocus-sele"  name="gc_parent_id" id="gc_parent_id">
							<option value="0">顶级分类</option>
							<?php if(!empty($parent)){  ?>
                                <?php foreach($parent as $one){ ?>
                                <option value="<?php echo $one['gc_id'];?>"     <?php if(intval($_GET['gc_id']==$one['gc_id'])){ echo 'selected="selected"';}?> ><?php echo $one['gc_name'];?></option>
                                <?php } ?>
                            <?php }  ?>
						</select>
	                </div>
					<p class="remind1">请选择上级分类 </p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing">分类描述：</dt>
					<dd class="left text-l">    
						<input type="text" name="desc" class="com-inp1 radius3 boxsizing" />
						<p class="remind1">请输入商品分类描述</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
                    <dt class="left text-r boxsizing"><span class="redstar">*</span>是否显示：</dt>
                    <dd class="left text-l">
                        <div class="switch-box">
                            <input type="checkbox" name="is_show" id="switch-radio" class="switch-radio"  value="1"  checked="checked" />
                            <span class="switch-half switch-open">显示</span>
                            <span class="switch-half switch-close close-bg">隐藏</span>
                        </div>
                        <p class="remind1">是否在前台显示</p>
                    </dd>
                </dl>
                <dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing">排序值：</dt>
					<dd class="left text-l">    
						<input type="text" name="listorder" class="com-inp1 radius3 boxsizing" />
						<p class="remind1">请输入排序值，商品分类将按照这个值降序排列</p>
					</dd>
				</dl>
				
				
				
				
				 <dl id="gc_image_dl"  class="juris-dl boxsizing  <?php if(intval($_GET['gc_id'])==0){ echo 'none';}?>">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>分类图片：</dt>
					<dd class="left text-l"> 
			            <ul class="uploadbox boxsizing">
							<li class="left uploadbox-li boxsizing">
								<div class="img-style boxsizing">
									<img  shopdz-action="upload_image" src="__PUBLIC__/admin/images/uploadimg.png" alt="" class="uploadimg boxsizing"/>
								</div>
								<i  shopdz-action="upload_delete"  class="up-icon dele-icon"></i>
								<div class="operationbox boxsizing">
									<p class="upload-p">
									    <input shopdz-action="upload_value"  type="hidden"  name="gc_image"  >
										<input type="file" id="gc_image_file" class="upload-inp2" hidefocus="true"/>
										<span class="inp2-cover boxsizing"><i class="up-icon upload-icon"></i>上传</span>
									</p>
								</div>
							</li>
							<li class="left uploadbox-li boxsizing">
								<div class="icon-library-box pointer" onclick="getIcons('all')">
									<i class="icon-library"></i>
									<p class="icon-library-word">素材库</p>
								</div>
							</li>
						</ul>   
						<p class="remind1">请上传分类图片</p>
					</dd>
				</dl>
				
				
			</div>
			<div class="btnbox3  submit_div  boxsizing  <?php if(intval($_GET['gc_id'])==0){ echo 'borderT-none';}?>">
				<input type="hidden" id="icon_url" />
				<a type="button" id="submit_button" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
				<a class="btn1 radius3 marginT10 " href="{:U('Commodity/category')}">返回列表</a>
			</div>
		</div>
		</form>
	</div>
</div>
</div>
<div class="icon-alert none"></div>
<div class="icon-cover none"></div>
<script>
var  has_image_tag   = false;

 function getIcons(type) {
   	 $.ajax({
        url:"<?php echo U('Commodity/category_icon');?>",
        type:"POST",
        data:'type='+type,
        success: function(response) {
            $('.icon-alert').html(response);
        	$('.icon-cover').show();
        	$('.icon-alert').show();
		 	$('#icon_type_'+type).addClass("green-font2").parent().siblings().find("a").removeClass("green-font2"); 
        }
    });
}

$(function(){
	$('#gc_parent_id').change(function(){
        var parent_id = parseInt($(this).val());
        if(parent_id == 0){
            $('#gc_image_dl').hide();
            $('.submit_div').addClass('borderT-none');
        }else{
      	  $('#gc_image_dl').show();
        	$('.submit_div').removeClass('borderT-none');
        }
    });
	$('#submit_button').click(function(){
        flag=checkrequire('main_form');
        if(!flag){
            $('#main_form').submit();
        }
    });
    $('title').html('添加商品分类');

    $('.uploadbox>li').mouseenter(function(){
		$(this).find('.asDefault-box-cover').addClass('block');
		if(has_image_tag){
		    $(this).find('.dele-icon').addClass('block');
		}
		$(this).find('.inp2-cover').addClass('inp2-cover-hover');
	})
	$('.uploadbox>li').mouseleave(function(){
		$(this).find('.asDefault-box-cover ').removeClass('block');
		$(this).find('.dele-icon').removeClass('block');
		$(this).find('.inp2-cover').removeClass('inp2-cover-hover');
	})

	   var uploader = new plupload.Uploader({
           runtimes: 'html5,html4,flash,silverlight',
           browse_button: 'gc_image_file',
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
              	 $("#gc_image_file").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/img/loading.gif');
               },
               FileUploaded: function (up, file, res) {
                   var resobj = eval('(' + res.response + ')');
                   if(resobj.status){
                	   has_image_tag = true;
                	   $("#gc_image_file").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__ATTACH_HOST__/'+resobj.data);
                	   $("#gc_image_file").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val(resobj.data);
                   }
               },
               Error: function (up, err) {
                   alert('err');
               }
           }
       });
       uploader.init();
       //删除图片按钮
       $('[shopdz-action="upload_delete"]').click(function(){
    	   $("#gc_image_file").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/admin/images/uploadimg.png');
    	   $("#gc_image_file").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val('');   
    	   has_image_tag = false;  
       });
});
</script>
