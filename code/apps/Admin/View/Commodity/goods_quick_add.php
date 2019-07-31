
		<div class="iframeCon">
		 	<ul class="transverse-nav">
	        	<li class="activeFour"><a href="javascript:;"><span>商品快速发布</span></a></li>
	   		</ul>
			<div class="white-bg">
				<div class="jurisdiction boxsizing">
				<form class="form-horizontal" id="main_form" autocomplete="off"  method="post">
	            <input name="goods_common_id"   type="hidden"  value=""   > 
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>商品名称：</dt>
						<dd class="left text-l">
							<input name="goods_name" localrequired="localrequired"    class="com-inp1 radius3 boxsizing" value=""   type="text"> 
							<p class="remind1">请输入商品名称，不要带规格词 比如 红色 XL等</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing">商品卖点：</dt>
						<dd class="left text-l">
							<input name="goods_jingle" class="com-inp1 radius3 boxsizing"  value=""  type="text">
							<p class="remind1">请输入商品卖点  广告词</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>商品价格：</dt>
						<dd class="left text-l">
							<input type="text"  localrequired="localrequired"   name="goods_price"  value=""  class="com-inp1 radius3 boxsizing"/>
							<p class="remind1">请输入商品价格 精确到2位小数</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>商品库存：</dt>
						<dd class="left text-l">
							<input type="text" localrequired="localrequired"      name="goods_storage"  value="0"  class="com-inp1 radius3 boxsizing"/>
							<p class="remind1">请输入商品库存  应为大于0的整数  如果不填或者填入负数按0库存</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>商品运费：</dt>
						<dd class="left text-l">
							<input type="text"   localrequired="localrequired"     name="freight"  value="0.00"  class="com-inp1 radius3 boxsizing"/>
							<p class="remind1">请输入商品运费不填默认为0</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>商品规格：</dt>
						<dd class="left text-l">
							<input type="text"   name="default_spec_value"  value="<?php  echo $default_spec_value['spec_value'];?>"  class="com-inp1 radius3 boxsizing"/>
							<p class="remind1">请输入商品规格值 默认为<?php echo $default_spec_value['spec_value']; ?> 若无必要请勿修改。</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>商品分类：</dt>
						<dd class="left text-l">
							<select    class="com-sele radius3 juris-dl-sele addFocus-sele" name="gc_id">
                                <option value="0" >请选择商品分类</option>
                                <?php if(!empty($category_list)){ ?>
                                    <?php foreach($category_list as $one){  ?>
                                        <?php if(!empty($one['child'])){ ?>
                                         <optgroup label="<?php  echo $one['gc_name'];?>">
                                             <?php foreach($one['child'] as $two){ ?>
                                                 <option  <?php if($goods_common['gc_id']==$two['gc_id']){echo 'selected="selected" ';} ?>   value="<?php echo $two['gc_id'];?>" >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $two['gc_name']?></option>
                                             <?php } ?>
                                          </optgroup>
                                        <?php }else{ ?>
                                        <option   <?php if($goods_common['gc_id']==$one['gc_id']){echo 'selected="selected" ';} ?>   value="<?php echo $one['gc_id'];?>" ><?php echo $one['gc_name']?></option>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </select>
							<p class="remind1">请选择商品分类，只有最后一层分类允许添加商品</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>商品图片：</dt>
						<dd class="left text-l">
							<!--<textarea type="text" class="com-textarea1 radius3 boxsizing"></textarea>-->
							<ul class="uploadbox boxsizing">
								<li class="left uploadbox-li boxsizing">
									<div class="img-style boxsizing">
										<img shopdz-action="upload_image"   src="<?php   if($images[0]['image_url']){ ?>__ATTACH_HOST__{$images[0]['image_url']}<?php }else{ ?>  __PUBLIC__/img/default.png<?php } ?>" alt="" class="uploadimg boxsizing"/>
									</div>
									<div class="asDefault-box-cover block2  boxsizing">
									</div>
									<i   shopdz-action="upload_delete"  class="up-icon dele-icon"></i>
									<p class="asDefault boxsizing asdefault-green"><i class="up-icon default-icon"></i>默认主图</p>
									<div class="operationbox boxsizing">
										<p class="upload-p">
										     <input type="radio"  style="display:none;"   checked="checked"  name="is_default" value="1" />
											<input type="file"   id="goods_image_file_1"  class="upload-inp2" hidefocus="true"/>
											 <input shopdz-action="upload_value"   type="hidden"  name="goods_image_1"  value="<?php echo $images[0]['image_url']; ?>"  >  
											<span class="inp2-cover boxsizing "><i class="up-icon upload-icon"></i>上传</span>
										</p>
									</div>
								</li>
								<li class="left uploadbox-li boxsizing">
									<div class="img-style boxsizing">
										<img shopdz-action="upload_image"   src="<?php   if($images[1]['image_url']){ ?>__ATTACH_HOST__{$images[1]['image_url']}<?php }else{ ?>  __PUBLIC__/img/default.png<?php } ?>" alt="" class="uploadimg boxsizing"/>
									</div>
									<!--<div class="asDefault-box">
										<p class="asDefault boxsizing"><i class="up-icon default-icon"></i>默认主图</p>
									</div>-->
									<div class="asDefault-box-cover boxsizing">
									</div>
									<i   shopdz-action="upload_delete"  class="up-icon dele-icon"></i>
									<p class="none asDefault boxsizing"><i class="up-icon default-icon"></i>默认主图</p>
									<div class="operationbox boxsizing">
										<p class="upload-p">
										    <input type="radio"  style="display:none;"   name="is_default" value="2" />
											<input type="file" class="upload-inp2"  id="goods_image_file_2"  hidefocus="true"/>
											<input shopdz-action="upload_value"  type="hidden"  name="goods_image_2"  value="<?php echo $images[1]['image_url']; ?>"  >  
											<span class="inp2-cover boxsizing"><i class="up-icon upload-icon"></i>上传</span>
										</p>
									</div>
								</li>
								<li class="left uploadbox-li boxsizing">
									<div class="img-style boxsizing">
										<img shopdz-action="upload_image"    src="<?php   if($images[2]['image_url']){ ?>__ATTACH_HOST__{$images[2]['image_url']}<?php }else{ ?>  __PUBLIC__/img/default.png<?php } ?>" alt="" class="uploadimg boxsizing"/>
									</div>
									<div class="asDefault-box-cover boxsizing">
									</div>
									<i   shopdz-action="upload_delete"  class="up-icon dele-icon"></i>
									<p class="none asDefault boxsizing"><i class="up-icon default-icon"></i>默认主图</p>
									<div class="operationbox boxsizing">
										<p class="upload-p">
										    <input type="radio"  style="display:none;"   name="is_default" value="3" />
											<input type="file" class="upload-inp2"  id="goods_image_file_3"  hidefocus="true"/>
											<input shopdz-action="upload_value"  type="hidden"  name="goods_image_3"  value="<?php echo $images[2]['image_url']; ?>"  >  
											<span class="inp2-cover boxsizing"><i class="up-icon upload-icon"></i>上传</span>
										</p>
									</div>
								</li>
								<li class="left uploadbox-li boxsizing">
									<div class="img-style boxsizing">
										<img  shopdz-action="upload_image"  src="<?php   if($images[3]['image_url']){ ?>__ATTACH_HOST__{$images[3]['image_url']}<?php }else{ ?>  __PUBLIC__/img/default.png<?php } ?>" alt="" class="uploadimg boxsizing"/>
									</div>
									<div class="asDefault-box-cover boxsizing">
									</div>
									<i   shopdz-action="upload_delete"  class="up-icon dele-icon"></i>
									<p class="none asDefault boxsizing"><i class="up-icon default-icon"></i>默认主图</p>
									<div class="operationbox boxsizing">
										<p class="upload-p">
										    <input type="radio"  style="display:none;"   name="is_default" value="4" />
											<input type="file"   id="goods_image_file_4"  class="upload-inp2" hidefocus="true"/>
											<input shopdz-action="upload_value"  type="hidden"  name="goods_image_4"  value="<?php echo $images[3]['image_url']; ?>"  >  
											<span class="inp2-cover boxsizing"><i class="up-icon upload-icon"></i>上传</span>
										</p>
									</div>
								</li>
								<li class="left uploadbox-li boxsizing">
									<div class="img-style boxsizing">
										<img shopdz-action="upload_image"   src="<?php   if($images[4]['image_url']){ ?>__ATTACH_HOST__{$images[4]['image_url']}<?php }else{ ?>  __PUBLIC__/img/default.png<?php } ?>" alt="" class="uploadimg boxsizing"/>
									</div>
									<div class="asDefault-box-cover boxsizing">
									</div>
									<i   shopdz-action="upload_delete"  class="up-icon dele-icon"></i>
									<p class="none asDefault boxsizing"><i class="up-icon default-icon"></i>默认主图</p>
									<div class="operationbox boxsizing">
										<p class="upload-p">
										     <input type="radio"  style="display:none;"   name="is_default" value="5" />
											<input type="file" class="upload-inp2"  id="goods_image_file_5"  hidefocus="true"/>
											<input shopdz-action="upload_value"  type="hidden"  name="goods_image_5"  value="<?php echo $images[4]['image_url']; ?>"  >  
											<span class="inp2-cover boxsizing"><!--<i class="font-face icon-upload-alt up-icon"></i>--><i class="up-icon upload-icon"></i>上传</span>
										</p>
									</div>
								</li>
							</ul>
							<p class="remind1 remind-mar">请上传图片,建议使用640*640像素或1:1大小jpg、gif、png格式的图片。</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing borderB-none">
						<dt class="left text-r boxsizing">商品详情：</dt>
						<dd class="left text-l ">
							<script id="desc" name="goods_detail" type="text/plain"><?php echo htmlspecialchars_decode(stripslashes($goods_common['goods_detail']));?></script>
        					<script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
                            <script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.all.js"></script>
                            <script type="text/javascript">
                                var ue = UE.getEditor('desc',{
                                    'initialFrameWidth':792,
                                    'initialFrameHeight':500,    
                                	});
                            </script>
							<p class="remind1 remind-mar">请输入商品详细介绍</p>
						</dd>
					</dl>
				</div>
				<div class="btnbox3 boxsizing">
					<input type="button" shopdz-goods_state="1"     class="submit_button btn1 radius3 btn3-btnmargin" value="立即发布">
                    <input type="button" shopdz-goods_state="0"   class="submit_button btn1 radius3 marginT10" value="放入仓库">
				</div>
				</form>
			</div>
		</div>
		<!--内容结束-->
		<script type="text/javascript">
			$(function(){

				$('.submit_button').click(function(){
			        flag=checkrequire('main_form');
			        if(!flag){
				        $.post($('.main_form').attr('action'), $('#main_form').serialize()+'&goods_state='+$(this).attr('shopdz-goods_state'),function(data){
				        	if(data.status==1){
			                	showSuccess(data.info,function(){
				                	window.location.href = '<?php echo U('Commodity/goods_list');?>' ;    
			                    });
			                }else{
			                   showError(data.info);
			                }    
					    },'json');
			        }
			    });

			    
				//图片上传
		 		$('.uploadbox>li').mouseenter(function(){
			 		var  img_value =  $(this).find('[shopdz-action="upload_value"]').val();
		 			$(this).find('.asDefault-box-cover').addClass('block');
		 			if(img_value){
		 			    $(this).find('.dele-icon').addClass('block');
		 			}
		 			$(this).find('.inp2-cover').addClass('inp2-cover-hover');
		 			$(this).find('.asDefault').removeClass('none');
		 		})
		 		$('.uploadbox>li').mouseleave(function(){
		 			$(this).find('.asDefault-box-cover ').removeClass('block');
		 			$(this).find('.dele-icon').removeClass('block');
		 			$(this).find('.inp2-cover').removeClass('inp2-cover-hover');
		 			if(!$(this).find('[name="is_default"]').prop('checked')){
		 				$(this).find('.asDefault').addClass('none');
				    }
		 		})
				$('.uploadbox>li .asDefault-box-cover').bind('click',function(){
					$(this).parents('.uploadbox-li').find("[type='radio']").click();
					$(this).parents('.uploadbox-li').find('.asDefault-box-cover').addClass('block2').parents('.uploadbox-li').siblings().find('.asDefault-box-cover').removeClass('block2');
					$('.asDefault').addClass('none');
					$(this).parents('.uploadbox-li').find('.asDefault').addClass('asdefault-green').removeClass('none').parents('.uploadbox-li').siblings().find('.asDefault').removeClass('asdefault-green');
					$(this).parents('.uploadbox-li').find('.inp2-cover').addClass('inp2-cover-hover2').parents('.uploadbox-li').siblings().find('.inp2-cover').removeClass('inp2-cover-hover2');
					
				})
				
				//商品发布五张图
				var uploader1 = new plupload.Uploader({
		            runtimes: 'html5,html4,flash,silverlight',
		            browse_button: 'goods_image_file_1',
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
		                    uploader1.start();
		                },
		                UploadProgress: function (up, file) {
		                	$("#goods_image_file_1").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/img/loading.gif');
		                },
		                FileUploaded: function (up, file, res) {
		                    var resobj = eval('(' + res.response + ')');
		                    if(resobj.status){
		                    	$("#goods_image_file_1").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__ATTACH_HOST__/'+resobj.data);
			                 	$("#goods_image_file_1").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val(resobj.data);
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
		            browse_button: 'goods_image_file_2',
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
		                    uploader2.start();
		                },
		                UploadProgress: function (up, file) {
		                	$("#goods_image_file_2").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/img/loading.gif');
		                },
		                FileUploaded: function (up, file, res) {
		                    var resobj = eval('(' + res.response + ')');
		                    if(resobj.status){
		                    	$("#goods_image_file_2").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__ATTACH_HOST__/'+resobj.data);
			                 	$("#goods_image_file_2").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val(resobj.data);
		                    }
		                },
		                Error: function (up, err) {
		                    alert('err');
		                }
		            }
		        });
		        uploader2.init();
			})
			
			
			var uploader3 = new plupload.Uploader({
		            runtimes: 'html5,html4,flash,silverlight',
		            browse_button: 'goods_image_file_3',
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
		                    uploader3.start();
		                },
		                UploadProgress: function (up, file) {
		                	$("#goods_image_file_3").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/img/loading.gif');
		                },
		                FileUploaded: function (up, file, res) {
		                    var resobj = eval('(' + res.response + ')');
		                    if(resobj.status){
		                    	$("#goods_image_file_3").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__ATTACH_HOST__/'+resobj.data);
			                 	$("#goods_image_file_3").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val(resobj.data);
		                    }
		                },
		                Error: function (up, err) {
		                    alert('err');
		                }
		            }
		        });
		        uploader3.init();


		        var uploader4 = new plupload.Uploader({
		            runtimes: 'html5,html4,flash,silverlight',
		            browse_button: 'goods_image_file_4',
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
		                    uploader4.start();
		                },
		                UploadProgress: function (up, file) {
		                	$("#goods_image_file_4").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/img/loading.gif');
		                },
		                FileUploaded: function (up, file, res) {
		                    var resobj = eval('(' + res.response + ')');
		                    if(resobj.status){
		                    	$("#goods_image_file_4").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__ATTACH_HOST__/'+resobj.data);
			                 	$("#goods_image_file_4").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val(resobj.data);
		                    }
		                },
		                Error: function (up, err) {
		                    alert('err');
		                }
		            }
		        });
		        uploader4.init();

		        var uploader5 = new plupload.Uploader({
		            runtimes: 'html5,html4,flash,silverlight',
		            browse_button: 'goods_image_file_5',
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
		                    uploader5.start();
		                },
		                UploadProgress: function (up, file) {
		                	$("#goods_image_file_5").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/img/loading.gif');
		                },
		                FileUploaded: function (up, file, res) {
		                    var resobj = eval('(' + res.response + ')');
		                    if(resobj.status){
		                    	$("#goods_image_file_5").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__ATTACH_HOST__/'+resobj.data);
			                 	$("#goods_image_file_5").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val(resobj.data);
		                    }
		                },
		                Error: function (up, err) {
		                    alert('err');
		                }
		            }
		        });
		        uploader5.init();

			      //删除图片按钮
		        $('[shopdz-action="upload_delete"]').click(function(){
		     	   $(this).parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/admin/images/uploadimg.png');
		     	   $(this).parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val('');   
		        });
		</script>

