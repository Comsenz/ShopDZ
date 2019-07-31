<script>
var category = '<?php echo $category;?>';
var category = eval('('+category+')');
var searchurl = "<?php echo U('Search/searchspu'); ?>";
var uploadurl = "<?php echo U('Upload/common'); ?>";
var attach_dir = '__ATTACH_HOST__';
</script>
<style>
.switch-half {z-index:1;}
</style>
<!-- 积分兑换相关的表单是否显示 -->
<style>
    .credits_show_only{display:none;}
   .btnbox3{border-top:none;}
</style>
		<div class="iframeCon">
		 	<ul class="transverse-nav">
	        	<li class="activeFour"><a href="javascript:;"><span>礼品快速发布</span></a></li>
	   		</ul>
			<div class="white-bg">
			    <form class="form-horizontal" id="main_form" autocomplete="off"  method="post">
				<div class="jurisdiction boxsizing">
				
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>礼品名称：</dt>
						<dd class="left text-l">
							<input name="gift_name" localrequired="empty"    class="com-inp1 radius3 boxsizing" value=""   type="text"> 
							<p class="remind1">请输入礼品名称,30个汉字以内</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>礼品价格：</dt>
						<dd class="left text-l">
							<input type="text"  localrequired="empty"   name="gift_price"  value=""  class="com-inp1 radius3 boxsizing"/>
							<p class="remind1">请输入礼品价格 精确到2位小数</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>礼品库存：</dt>
						<dd class="left text-l">
							<input type="text" localrequired="empty"      name="gift_storage"  value="0"  class="com-inp1 radius3 boxsizing"/>
							<p class="remind1">请输入礼品库存  应为大于0的整数  如果不填或者填入负数按0库存</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing">排序值：</dt>
						<dd class="left text-l">
							<input type="text" localrequired="empty"      name="listorder"  value="0"  class="com-inp1 radius3 boxsizing"/>
							<p class="remind1">排序值,在需要排序的地方按照这个值倒序排  应为大于0的整数  如果不填或者填入负数按0库存</p>
						</dd>
					</dl>

				
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>礼品图片：</dt>
						<dd class="left text-l">
							<!--<textarea type="text" class="com-textarea1 radius3 boxsizing"></textarea>-->
							<ul class="uploadbox boxsizing">
								<li class="left uploadbox-li boxsizing">
									<div class="img-style boxsizing">
										<img shopdz-action="upload_image"   src="__PUBLIC__/img/default.png"  class="uploadimg boxsizing"/>
									</div>
									<i   shopdz-action="upload_delete"  class="up-icon dele-icon"></i>
									<div class="operationbox boxsizing">
										<p class="upload-p">
											<input type="file"   id="gift_image_file"  class="upload-inp2" hidefocus="true"/>
											<input shopdz-action="upload_value"   type="hidden"  name="gift_image"  value=""  >  
											<span class="inp2-cover boxsizing "><i class="up-icon upload-icon"></i>上传</span>
										</p>
									</div>
								</li>
							</ul>
							<p class="remind1 remind-mar">请上传图片,建议使用640*640像素或1:1大小jpg、gif、png格式的图片。</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing borderB-none">
						<dt class="left text-r boxsizing">礼品介绍：</dt>
						<dd class="left text-l ">
						    <textarea type="text" name="gift_description" class="com-textarea1 radius3 boxsizing" ></textarea>
        					
							<p class="remind1 remind-mar">请输入礼品详细介绍</p>
						</dd>
				    </dl>
				</div>
				<h1 class="details-tit">积分兑换</h1>
				<div class="jurisdiction boxsizing">
					<dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>积分兑换：</dt>
                        <dd class="left text-l">
                            <div class="switch-box">
                                <input name="credits_exchange" id="switch-radio" class="switch-radio"  type="checkbox">
                                <span class="switch-half switch-open ">开启</span>
                                <span class="switch-half switch-close close-bg">关闭</span>
                            </div>
                            <p class="remind1">是否开启积分兑换,如果开启,会员可以使用积分兑换该礼品。</p>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing  credits_show_only">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>兑换积分：</dt>
						<dd class="left text-l">
							<input type="text"       name="gift_points"  value="0"  class="com-inp1 radius3 boxsizing"/>
							<p class="remind1">兑换礼品需要的积分数量，请填写大于0的整数。</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing credits_show_only">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>每人限兑：</dt>
						<dd class="left text-l">
							<input type="text"        name="limit_num"  value="0"  class="com-inp1 radius3 boxsizing"/>&nbsp;&nbsp;&nbsp;件
							<p class="remind1">0为不限制兑换数量。</p>
						</dd>
					</dl>
					
					<dl class="juris-dl boxsizing credits_show_only">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>生效时间：</dt>
						<dd class="left text-l">
						<div class="left" style="width:100%;">
    						<p class="time-box">
    						    <input type="text" class="com-inp1 radius3 boxsizing" id="ostime" name="start_time" readonly=""    placeholder="选择起始时间"/><i class="timeinp-icon"></i>
    						</p>
                            <span class="left time-line">—</span>
                            <p class="time-box">
                                <input type="text" class="com-inp1 radius3 boxsizing" id="oetime" name="end_time" readonly=""  placeholder="选择结束时间" /><i class="timeinp-icon"></i>
                            </p>
                        </div>
						<p class="remind1">礼品兑换上架时间。</p>
						</dd>
					</dl>
					
					<dl class="juris-dl boxsizing credits_show_only">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>关联商品：</dt>

						<dd class="left text-l">
						    <input type="text"  id="goods_common_id"   name="goods_common_id"  readonly="readonly" value="0"  style="display: none"/>
						    <input type="text" id="goods_name"  name="goods_name"  readonly="readonly" value="" style="display: none"/>
							<div class="data-operBox">
								<div class="group-choice-again" style="display: none" id="showedit">
									<p class="fight-pro-name data-pro-name" ><a href="#" class="proName" id="goodsnameshow"></a></p>
									<input type="button" name="" id="" value="修改" class="group-choice-btn2   showsearchbtn ">
								</div>

							</div>
                			<input type="button" value="选择商品" class="group-choice-btn firstshow showsearchbtn" id="showbtt"/>
                			<div class="group-choice-again secondshow" style="display:none">
                				<p class="fight-pro-name ellipsis"></p>
                				<input  type="button" name="" id="" value="重新选择" class="group-choice-btn   showsearchbtn "/>
                			</div>
							<p class="remind1">与正式商品关联，买家积分不足时，可以在积分兑换页面进入正式商品页面直接购买。</p>
						</dd>
					</dl>
				</div>
				<div class="btnbox3 boxsizing">
					<input type="button"        class="submit_button btn1 radius3 btn3-btnmargin" value="立即发布">
                    <input class=" btn1 radius3 marginT10" value="返回列表" onclick="window.location.href='<?php echo U('Coupon','gift_list');?>'" type="button">
				</div>
				</div>
			</div>
			</form>
		</div>
		
		<!-- 选择商品搜索 S -->
		<div id='showsearch' style="display:none">
    		<div class="icon-alert">
    			<h1 class="details-tit icon-alert-tit">{$Think.lang.select_goods}<i class="alert-close"></i></h1>
    			<div class="group-choice-classify">
    				<p class="group-classify-tit">{$Think.lang.goods_class}</p>
    				<select name="gc_id_1" id="gc_id_1" class="group-sele">
    					<option value="0">{$Think.lang.select_goods_class}</option>
    				</select>
    				<select name="gc_id_2" id="gc_id_2" class="group-sele">
    					<option value="0">{$Think.lang.select_goods_class}</option>
    				</select>
    				<input type="text" id='search_text' name="search_text" class="com-inp1 group-inp" placeholder="{$Think.lang.select_goods_tips_spu}"/>
    				<input type="button" value="{$Think.lang.search}" id='group-search-btn' search_url="<?php echo U('Search/searchspu'); ?>" class="group-choice-btn group-search-btn"/>
    			</div>
    			<ul class="icon-list fight-icon-list">
    			</ul>
    			<div class="pagination boxsizing icon-page">
    			</div>
    			<div class="btn-box-center">
    				<input type="button" id='searchok' class="btn1 radius3 " value="{$Think.lang.ok}">
    				
    			</div>
    		</div>
    		<div class="icon-cover"></div>
    	</div>
    	<!-- 选择商品搜索  E--> 
		<!--内容结束-->
		<script type="text/javascript" src="__PUBLIC__/admin/js/goods_search.js"></script>
		<script type="text/javascript">
			$(function(){

				//确认
				$('#searchok').click(function() {
					var info = $('.green-border').find('p');
					if(info.length){
						var goods_name = info.html();
						var goods_storage = info.attr('goods_storage');
						var goods_images = info.attr('goods_images');
						var goods_common_id = info.attr('goods_common_id');
						var sign_price = info.attr('sign_price');
						var img_src = info.find('img').attr('src');
						$('#goods_common_id').val(goods_common_id);
						$('#goods_name').val(goods_name);
						$('.alert-close').click();
						$('#goodsnameshow').text(goods_name);
						$('#showedit').show();
						$('#showbtt').hide();

					}
				});
				$('.submit_button').click(function(){
			        flag=checkrequire('main_form');
			        if(!flag){
				        $.post($('.main_form').attr('action'), $('#main_form').serialize(),function(data){
				        	if(data.status==1){
			                	showSuccess(data.info,function(){
				                	window.location.href = '<?php echo U('Coupon/gift_list');?>' ;    
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
				
				
				//礼品发布五张图
				var uploader = new plupload.Uploader({
		            runtimes: 'html5,html4,flash,silverlight',
		            browse_button: 'gift_image_file',
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
		                	$("#gift_image_file").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/img/loading.gif');
		                },
		                FileUploaded: function (up, file, res) {
		                    var resobj = eval('(' + res.response + ')');
		                    if(resobj.status){
		                    	$("#gift_image_file").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__ATTACH_HOST__/'+resobj.data);
			                 	$("#gift_image_file").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val(resobj.data);
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
		     	   $(this).parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/admin/images/uploadimg.png');
		     	   $(this).parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val('');   
		        });

		        $("#ostime").datepicker({
		            changeMonth: true,
		            changeYear: true,
		            showButtonPanel:true,
		            dateFormat: 'yy-mm-dd',
		            showAnim:"fadeIn",//淡入效果
		        });
		        $("#oetime").datepicker({
		            changeMonth: true,
		            changeYear: true,
		            showButtonPanel:true,
		            dateFormat: 'yy-mm-dd',
		            showAnim:"fadeIn",//淡入效果
		        });

		        function search(p) {
		        	  p = p ? p : "1";
		        	gc_id_1 = $('#gc_id_1').val();
		        	gc_id_2 = $('#gc_id_2').val();
		        	search_text = $('#search_text').val();
		        	data = {
		        		gc_id_1:gc_id_1,
		        		gc_id_2:gc_id_2,
		        		content:search_text,
		        		p:p,
		        	};
		        	getdata(searchurl,data,function(data) {
		        		good = data.data.goods;
		        		page = data.data.page;
		        		li ='';
		        		$('.fight-icon-list').html(li);
		        		for(i in good){
		        			var goods_images_first = good[i].goods_images[0];
		        			li +='<li  ><a href="#"><img src="'+good[i].goods_image+'" alt="" class="icon-img"/><p class="icon-name" goods_id="'+good[i].goods_id+'" sign_price="'+good[i].goods_price+'" goods_storage="'+good[i].goods_storage+'" goods_images="'+goods_images_first+'">'+good[i].goods_name+'</p></a></li>';
		        		}
		        		$('.fight-icon-list').html(li);
		        		$('.pagination').html(page);
		        		var aobjs = $(".pagination").find(".page");
		        		$(aobjs).each(function(i){

		        			var currentpage = $(this).data('page');
		        			//绑定点击事件
		        			$(this).bind('click',function(){
		        				search(currentpage);//重新调用该函数查询
		        			});
		        		});
		        	});
		        }


		        // 积分兑换关闭开启 隐藏显示积分相关
		        $('#switch-radio').click(function(){
		        	 if(this.checked){
		        		 $('.credits_show_only').show();
		        		 $('.btnbox3').css('border-top','1px solid #ededed');
		        		 $('.credits_show_only input[type="text"]').attr('localrequired','empty');  
			         }else{
			        	 $('.credits_show_only').hide();
			        	 $('.btnbox3').css('border-top','none');
			        	 $('.credits_show_only input[type="text"]').removeAttr('localrequired');	
				     }
				});
		       
		       
		    });
		</script>

