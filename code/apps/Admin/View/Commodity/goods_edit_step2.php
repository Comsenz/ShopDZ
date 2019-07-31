<ul class="release-tab">
	<li class="radius5"><a href="<?php echo  U('Commodity/goods_add_step1',array('goods_common_id'=>$goods_common['goods_common_id'],'edit'=>1));?>" >1.&nbsp;基本信息</a></li>
	<li class="radius5 activeRelease">2.&nbsp;规格设置</li>
	<li class="radius5">3.&nbsp;运费设置</li>
	<li class="radius5">4.&nbsp;参数设置</li>
	<li class="radius5">5.&nbsp;发布成功</li>
</ul>
<script>
var  spec_id_list =  [];
var  spec_value_id_v = {};
var   spec_value_id_spec_id  = {};
var  spec_id_name  = {};
var  goods_list =  {};
</script>


<!--弹框开始-->
<div class="cover spec_add_element none"></div>
<div class="alert spec_add_element none  showAlert radius3 addSpec-alert specialAlert">
	<i class="close-icon" onclick="$('.spec_add_element').addClass('none');" ></i>
	<h1 class="special-tit">添加规格值</h1>
	<form  id="spec_value_add_form"   autocomplete="off"   class="form-horizontal"  action="<?php echo   U('Commodity/spec_value_add_form');?>"  method="post" >
	<div class="special-con addSpec-con">
		<span class="special-con-left left">规格值</span>
		<input id="add_spec_value_id"  name="spec_id" class="form-control"  value=""  type="hidden"> 
		<input type="text" name="spec_value" class="com-inp1 radius3 boxsizing left" id="add_spec_value_text" >
		<div class="clear"></div>
		<p class="special-remind">请输入规格值名称</p>
	</div>
	<div class="alert-btnbox boxsizing">
		<a   id="spec_value_submit"  class="btn1 radius3">{$Think.lang.submit_btn}</a>
	</div>
	</form>
</div>
		



<!--内容开始-->
<div class="iframeCon">
    <ul class="transverse-nav">
        <li class="activeFour"><a href="#"><span>规格设置</span></a></li>
    </ul>
	<div class="white-bg">
        <form class="form-horizontal" id="main_form" autocomplete="off"  method="post" autocomplete="off"  method="post" action="<?php echo  U('Commodity/goods_edit_step2',array('edit'=>1));?>" >
        <input name="goods_common_id"   type="hidden"  value="<?php  echo intval($goods_common['goods_common_id']);?>"   > 
	    <div class="jurisdiction boxsizing release-juris">
	        <dl class="juris-dl boxsizing release-dl">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>请选择规格：</dt>
				<dd class="left text-l">
					<div class="checkbox-holder">
					<?php if(!empty($spec_list)){?><?php foreach($spec_list  as $v){ ?> <p class="radiobox release-check"><input   <?php if(array_key_exists($v['spec_id'], $goods_common['spec'])){ echo  'checked="checked"';} ?>   class="regular-radio spec_lists"  type="checkbox" value="<?php echo $v['spec_id']?>"  id="spec_lists_<?php echo $v['spec_id']?>"   name="spec_ids[]"/><label for="spec_lists_<?php echo $v['spec_id']?>"></label><span  title="<?php echo $v['spec_name'];?>" class="radio-word"><?php echo $v['spec_name'];?></span></p><?php } ?><?php } ?>
					</div>
					<p class="remind1">请选择商品规格 至少要选一个 最多不允许超过2个
					</p>
				</dd>
			</dl>
			<?php foreach($goods_spec_list as $spec_id=>$spec_info){ ?>
    		<script>
    	    spec_id_list.push(<?php echo $spec_id;?>);
    	    spec_id_name[<?php echo $spec_id;?>]   = '<?php echo  $spec_info['spec_name'];?>';
    		</script>
            <dl class="juris-dl boxsizing release-dl"  id="spec_value_div_<?php echo $spec_id;?>"  >
            <dt class="left text-r boxsizing"><span class="redstar">*</span><?php echo $spec_info['spec_name'];?>：</dt>
            <dd class="left text-l">
            <div class="checkbox-holder">
                <?php foreach($spec_info['spec_value_list'] as $spec_value){ ?>
    			<script>
    			spec_value_id_v[<?php echo $spec_value['spec_value_id'];?>]  = '<?php echo $spec_value['spec_value'];?>';
    			spec_value_id_spec_id[<?php echo $spec_value['spec_value_id'];?>]  = <?php echo $spec_id;?>;
    			</script>
                <p class="radiobox release-check">
                    <input    <?php if(in_array($spec_value['spec_value_id'], $goods_common['spec'][$spec_id])){ echo  'checked="checked" ';} ?> data-spec_id="<?php echo  $spec_id; ?>" id="spec_value_lists_<?php echo  $spec_value['spec_value_id'] ?>"   class="regular-radio spec_value_lists"  type="checkbox" value="<?php echo  $spec_value['spec_value_id'];?>" name="spec_value[<?php echo $spec_id;?>][]"/>
                    <label for="spec_value_lists_<?php echo $spec_value['spec_value_id'];?>"></label>
                    <span title="<?php echo $spec_value['spec_value'];?>"  class="radio-word"><?php echo $spec_value['spec_value'];?></span>
                </p>    
                <?php } ?>
		 	</div>
		 	<div class="add-release release-icon radius3">
				<span class="re-add-span left"><i class="release-addicon"></i></span>
				<span  data-spec_id="<?php echo $spec_id;?>"   class=" spec_value_add_btn re-add-word left">添加</span>
			</div>
				<p class="remind1">请选择<?php echo  $spec_info['spec_name'];?>规格值 至少要选一个</p>
			</dd>
			</dl>    
    		<?php } ?>
    		<!-- 价格设置 s -->
    		<dl id="spec_value_after"  class="juris-dl boxsizing release-dl price-dl">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>价格设置：</dt>
				<dd class="left text-l" id="goods_list_div">
					<table class="com-table spec-table">
						<thead>
							<tr>
							    <?php  foreach($goods_spec_list as $v){   ?>
							    <th width="100"><?php echo $v['spec_name'];?></th>
                    	        <?php } ?>
								<th width="103" class="posiR">
                    				<div class="release-icon spec-icon">
                    					价格
                    					<i class="edit-icon price-icon"></i>
                    				</div>
                    				<div class="edit-spec radius5">
                    					<div class="edit-con">
                    						<p class="edit-tit">批量设置价格</p>
                    						<div class="edit-price release-inp boxsizing radius3">
                    							<input type="text" class="price-inp left release-inp"
                    								id="price_text">
                    							<span class="price-unit left release-inp" id="price_button">设置</span>
                    						</div>
                    					</div>
                    					<s class="jt-top">
                    						<i class="jt-top-icon"></i>
                    					</s>
                    				</div>
                    			</th>
                    			<th width="103" class="posiR">
                    				<div class="release-icon spec-icon">
                    					库存
                    					<i class="edit-icon stock-icon"></i>
                    				</div>
                    				<div class="edit-spec radius5">
                    					<div class="edit-con">
                    						<p class="edit-tit">批量设置库存</p>
                    						<div class="edit-price release-inp boxsizing radius3">
                    							<input type="text" class="price-inp left release-inp"
                    								id="storage_text">
                    							<span class="price-unit left release-inp" id="storage_button">设置</span>
                    						</div>
                    					</div>
                    					<s class="jt-top">
                    						<i class="jt-top-icon"></i>
                    					</s>
                    				</div>
                    			</th>
                    			<th width="130">商家货号</th>
                    			<th width="239" class="posiR">
                    				<div class="release-icon spec-icon">
                    					图片
                    					<i class="edit-icon uploadimg-icon"></i>
                    				</div>
                    				<div class="edit-spec radius5 edit-uploadImg">
                    					<div class="edit-con">
                    						<p class="edit-tit">批量上传图片</p>
                    						<div
                    							class="edit-uploadbox release-price release-table-price boxsizing radius3 left"
                    							shopdz-action="upload_group">
                    							<input type="text" class="price-inp left edit-inp1"
                    								shopdz-action="upload_value" id="image_text">
                    							<input type="file" class="spec-file release-inp"
                    								shopdz-action="upload_file" id="all_file" style="z-index: 1;">
                    							<input type="button" value="批量上传"
                    								class="price-unit left spec-uploadbtn"
                    								onclick="$(this).parents('[shopdz-action=upload_group]').find('[shopdz-action=upload_file]').click();">
                    							<div id="html5_1aq92fc9sb911vm81vlp46u173a44_container"
                    								class="moxie-shim moxie-shim-html5"
                    								style="position: absolute; top: 0px; left: 0px; width: 0px; height: 0px; overflow: hidden; z-index: 0;">
                    								<input type="file" accept="image/jpeg,image/gif,image/png"
                    									multiple=""
                    									style="font-size: 999px; opacity: 0; position: absolute; top: 0px; left: 0px; width: 100%; height: 100%;"
                    									id="html5_1aq92fc9sb911vm81vlp46u173a44">
                    							</div>
                    						</div>
                    						<button class="edit-alertBtn left radius3" id="image_button"
                    							type="button">保存</button>
                    					</div>
                    					<s class="jt-top">
                    						<i class="jt-top-icon"></i>
                    					</s>
                    				</div>
                    			</th>
							</tr>
						</thead>
						<tbody>
							<?php  foreach($goods_list  as $goods){ ?>
            		        <?php $id_key =  $goods['spec_value_id_key'];?>
							<tr id="goods_list_tr_<?php echo $id_key;  ?>" >
                    			<?php foreach($goods['goods_spec'] as $v){ ?>
                    			<td><div title="<?php  echo $v;?>"  class="td-word"><?php  echo $v;?></div></td>
                    			<?php } ?>
                    			<td>
                    				<div class=" release-price release-inp release-table-price boxsizing radius3">
                    					<input type="text"   value="<?php echo $goods['goods_price'];?>" name="goods_list[<?php  echo $id_key;?>][goods_price]" class="price-inp left release-inp sku_price">
                    					<span class="price-unit left release-inp">元</span>
                    				</div>
                    			</td>
                    			<td>
                    				<input type="text" class="sku_storage com-inp1 radius3 boxsizing release-inp table-inp-relative spec-inp1" value="<?php echo $goods['goods_storage'];?>" name="goods_list[<?php echo $id_key;?>][goods_storage]">
                    			</td>
                    			<td>
                    				<input  type="text" class="com-inp1 radius3 boxsizing release-inp table-inp-relative spec-inp2"  value="<?php echo $goods['goods_barcode'];?>" name="goods_list[<?php echo $id_key;?>][goods_barcode]">
                    			</td>
                    			<td>
                    			<div  shopdz-action="upload_group"   class="spec-uploadbox release-price release-table-price boxsizing radius3">
                    			    <input type="text"  name="goods_list[<?php  echo $id_key;?>][goods_image]"  value="<?php echo $goods['goods_image'];?>"   shopdz-action="upload_value"   class=" sku_image price-inp left spec-inp3"/>
                    			    <input type="file"   id="upload_file_<?php  echo $id_key;?>"  shopdz-action="upload_file"  class="spec-file release-inp" hidefocus="true"/><input type="button"   onclick="$(this).parents('[shopdz-action=upload_group]').find('[shopdz-action=upload_file]').click();"  class="price-unit left spec-uploadbtn"  value="选择图片"/>
                    			</div>
                    			</td>
                    		</tr>
                    		<script>
                    		    goods_list['<?php echo $id_key;?>']  =   '<tr id="goods_list_tr_<?php echo $id_key;  ?>" >'+$('#goods_list_tr_<?php echo $id_key;?>').html()+'</tr>';   
                    		    var uploader_list_<?php echo $id_key; ?> = new plupload.Uploader({
                                    runtimes: 'html5,html4,flash,silverlight',
                                    browse_button: 'upload_file_<?php echo $id_key;?>',
                                    url: __BASE__+'Upload/common',
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
                                            uploader_list_<?php echo $id_key;?>.start();
                                        },
                                        UploadProgress: function (up, file) {
                                        },
                                        FileUploaded: function (up, file, res) {
                                            var resobj = eval('(' + res.response + ')');
                                            if(resobj.status){
                                            	$('#upload_file_<?php echo $id_key; ?>').parents('[shopdz-action="upload_group"]').find('[shopdz-action="upload_value"]').val(resobj.data);
                                            }
                                        },
                                        Error: function (up, err) {
                                            alert('err');
                                        }
                                    }
                                });
                    		    uploader_list_<?php echo $id_key; ?>.init();
                    		</script>
                    		<?php } ?>
						</tbody>
					</table>
				</dd>
			</dl>
    		<!-- 价格设置e -->
    		
	 		</div>
		<div class="btnbox3 boxsizing">
		    <?php if($_GET['save']==1){ ?>
		    <a type="button" id="submit_button" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
		    <a class="btn1 radius3 marginT10 " href="{:U('Commodity/goods_list')}">返回列表</a>
			<?php }else{  ?>
			<a id="submit_button" class="btn1 radius3 btn3-btnmargin release-nextbtn"><span class="next-remind boxsizing">下一步</span><span class="next-con">运费设置</span></a>
		    <?php } ?>
		</div>
		</form>
	</div>
</div>
<script>
var  goods_common_price =  '<?php  echo  $goods_common['goods_price']?>';
var  spec_num  =  <?php echo intval(count($goods_common['spec']));?>;
$(function(){

    //禁用 超过两个的规格值项
    if(spec_num>=2){
    	$('.spec_lists').each(function(){
			if(!$(this).prop('checked')){
				$(this).attr('disabled','disabled'); 
			}
		});
	}
	
	//主表单提交
	$('#submit_button').click(function(){
        $.post($('#main_form').attr('action'),$('#main_form').serialize(),function(data){
       	if(data.status==1){
       	    showSuccess(data.info,function(){
       	    	<?php if($_GET['save']==1){ ?>
            	window.location.href = '<?php echo U('Commodity/goods_list');?>' ;    
            	<?php }else{?>
                window.location.href = data.url ;
        	    <?php } ?>
           	});
        }else{
             showError(data.info);
        }
        },'json');
    });	
    
	//规格值添加按钮的点击事件
	$(document).on('click','.spec_value_add_btn',function(){
		var _spec_id =    $(this).attr('data-spec_id');
		$('#add_spec_value_id').val(_spec_id);
		$('.spec_add_element').removeClass('none');
	})
	//规格值添加
    $('#spec_value_submit').click(function(){
        $.post('<?php echo   U('Commodity/spec_value_add_form');?>',$('#spec_value_add_form').serialize(),function(response){
        	if(response.status==1){
            	var  spec_value =  response.info;
            	spec_value_id_v[spec_value.spec_value_id]  = spec_value.spec_value;
				spec_value_id_spec_id[spec_value.spec_value_id]  =  spec_value.spec_id;
				var  html= '<p class="radiobox release-check"><input  data-spec_id="'+spec_value.spec_id+'" id="spec_value_lists_'+spec_value.spec_value_id+'" type="checkbox"  value="'+spec_value.spec_value_id+'" name="spec_value['+spec_value.spec_id+'][]"  class="regular-radio spec_value_lists" /><label for="spec_value_lists_'+spec_value.spec_value_id+'"  class="spec_lists_label"></label><span title="'+spec_value.spec_value+'" class="radio-word">'+spec_value.spec_value+'</span></p>';
                $('#spec_value_div_'+spec_value.spec_id+' .checkbox-holder').append(html);
        		//绑定点击事件
                $('#close_spec_value_add_form').click();
                $('#add_spec_value_id').val(0);
                $('#add_spec_value_text').val('');
                $('.spec_add_element').addClass('none');
				showSuccess('添加成功');
            }else{
                 showError(response.info);
            }
        },'json');
    });
	//批量 设置价格的显示
	$(document).on('click','.edit-icon',function(){
		$(this).toggleClass('uploadimg-active ');
		if($(this).parents('th').find('.edit-spec').css("display")=="none"){
			$(this).parents('th').find('.edit-spec').show();
		} else {
			$(this).parents('th').find('.edit-spec').hide();
		}
    });
	//规格值被选中事件
	$(document).on('click','.spec_value_lists',re_compute_spec_value); 

	//批量设置 图片的上传事件
    uploader_all = new plupload.Uploader({
        runtimes: 'html5,html4,flash,silverlight',
        browse_button: 'all_file',
        url: __BASE__+'Upload/common',
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
                uploader_all.start();
            },
            UploadProgress: function (up, file) {
            },
            FileUploaded: function (up, file, res) {
                var resobj = eval('(' + res.response + ')');
                if(resobj.status){
                    $('#all_file').parents('[shopdz-action="upload_group"]').find('[shopdz-action="upload_value"]').val(resobj.data);
                }
            },
            Error: function (up, err) {
                shwoError('err');
            }
        }
    });
    uploader_all.init();
});
</script>
<script src="__PUBLIC__/js/goods_edit.js"></script>