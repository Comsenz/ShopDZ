<div class="tipsbox">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>操作提示</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		<li>1.所有商品管理。</li>
		<li>2.可以对商品下架，上架，编辑，移动分类，加入回收站。</li>
		<li>3.只有回收站中的商品才可以被彻底删除，且不可恢复。</li>
	</ol>
</div>

<?php 
if(!$_GET['goods_state']){
    $_GET['goods_state'] = 'online';
}

?>

<!--弹框开始-->
<div class="cover dialog_element none"></div>
<div class="alert dialog_element none  showAlert radius3 addSpec-alert specialAlert">
	<i class="close-icon" ></i>
	<h1 class="special-tit">移动分类</h1>
	<form  id=""   autocomplete="off"   class="form-horizontal"  action="<?php echo   U('Commodity/spec_value_add');?>"  method="post" >
	<div class="special-con addSpec-con">
		<span class="special-con-left left"><span class="redstar">*</span>选择分类</span>
		<input id="remove_class_goods_id" type="hidden" name="goods_common_ids[]"  value="0" /> 
		<select  name="gc_id" class="addFocus-sele com-sele search-sele left">
		 <option value="0" >请选择分类</option>
            <?php if(!empty($category_list)){ ?>
                    <?php foreach($category_list as $one){  ?>
                        <?php if(!empty($one['child'])){ ?>
                         <optgroup label="<?php  echo $one['gc_name'];?>">
                             <?php foreach($one['child'] as $two){ ?>
                                 <option value="<?php echo $two['gc_id'];?>" >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $two['gc_name']?></option>
                             <?php } ?>
                          </optgroup>
                        <?php }else{ ?>
                        <option    value="<?php echo $one['gc_id'];?>" ><?php echo $one['gc_name']?></option>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
    	</select>
		<div class="clear"></div>
		<p class="special-remind">&nbsp;</p>
	</div>
	<div class="alert-btnbox boxsizing">
		<a   id="remove_class_btn"  href="javascript:;"  class="btn1 radius3">{$Think.lang.submit_btn}</a>
	</div>
	</form>
</div>
<!--弹窗结束-->

<div class="iframeCon">
	<?php if(!empty($small_nav)){ ?>
    <ul class="transverse-nav">
        <?php foreach($small_nav  as $nav_key=>$nav_url){ ?>
         <li    <?php if($nav_key==$small_nav_key){ ?>class="activeFour" <?php } ?>  >
             <a href="<?php echo  $nav_url?>"><span><?php echo $nav_key;?></span></a>
         </li>
        <?php } ?>
    </ul>
    <?php } ?>
	<!--{
		选项卡注意事项：
		1，把 white-shadow tab-content 或者 white-shadow2 类去掉改为white-bg
		2，复制nav.php下的 class="option" 放置到<div class="table-titbox"> 下面
	}-->
	
	<div class="white-bg">
		<div class="table-titbox">
			<h1 class="table-tit left boxsizing">商品列表</h1>
			<ul class="operation-list left">
				<li class="add-li"  title="添加商品"><a  href="<?php echo U('Commodity/goods_add_step1');?>"><span><i  class="operation-icon add-icon"></i></span></a></li>
				<li class="refresh-li"><a href="javascript:;" onclick="window.location.reload();"><span><i  class="operation-icon refresh-icon"></i></span></a></li>
			</ul>
			<div class="search-box1 right">
            <form  method="get"  autocomplete="off" class="form-horizontal"   action="" name="Search_form"  >
                <div class="search-boxcon boxsizing radius3 left">
                    <select  id="field" name="field"  class="sele-com1 search-sele boxsizing">
                	    <option  <?php if($_GET['field'] == 'goods_name'){ echo  'selected="selected"';}?> value="goods_name">商品名称</option>
		                        		<option  <?php if($_GET['field'] == 'goods_common_id'){ echo  'selected="selected"';}?>  value="goods_common_id" >SPU ID</option>
                	</select>
                    <input type="text" name="q" value="<?php echo $_GET['q']; ?>" class="search-inp-con boxsizing"/>
                </div>
                <input type="submit"  value="搜索" class="search-btn right radius3"/>
            </form>
            </div>
		</div>
		<div class="comtable-box" >
		<table class="commodity-table">
			<thead>
				<tr>
					<th>商品名称</th>
					<th width="90">价格</th>
					<th width="180" >商品分类</th>
					<th width="100">发布时间</th>
					<th width="90">商品运费</th>
					<th width="90">库存</th>
					<th width="90">二维码</th>
				</tr>
			</thead>
			<tbody>
			<empty name="lists">
                <tr class="tr-minH">
                    <td colspan="7">暂无数据！</td>
                </tr>
            <else />
			<?php foreach($lists as $v){ ?>
				<tr class="operation-tr">
					<td colspan="7"  class="posiR">
						<h1 class="Spu-tit left boxsizing">SPU  <?php echo $v['goods_common_id']?></h1>
				        <ul class="table-ope-list left">
									<li class="op-setting-li ope-list-li">
										<a href="#"><i href="#" class="table-ope-icon op-setting-icon"></i></a>
										<ul class="ope-setting-list">
										 <?php if($_GET['goods_state']=='offline'){?>
                					        <li><a shopdz-id=<?php echo $v['goods_common_id']; ?> shopdz-action="sell"  href="javascript:;">上架</a></li>
                							<li><a shopdz-id=<?php echo $v['goods_common_id']; ?> shopdz-action="move_class"  href="javascript:;">移动</a></li>
                							<li><a shopdz-id=<?php echo $v['goods_common_id']; ?> shopdz-action="recycle"  href="javascript:;">回收站</a></li>
                					    <?php }else if($_GET['goods_state']=='recycle'){ ?>
                							<li><a shopdz-id=<?php echo $v['goods_common_id']; ?> shopdz-action="move_class"  href="javascript:;">移动</a></li>
                							<li><a shopdz-id=<?php echo $v['goods_common_id']; ?> shopdz-action="remove_recycle"  href="javascript:;">还原</a></li>
                							<li><a shopdz-id=<?php echo $v['goods_common_id']; ?> shopdz-action="delete" >删除</a></li>
                					    <?php }else if($_GET['goods_state']=='draft'){ ?>
                							<li><a shopdz-id=<?php echo $v['goods_common_id']; ?> shopdz-action="delete"  >删除</a></li>
                					    <?php }else{ ?>
                							<li><a shopdz-id=<?php echo $v['goods_common_id']; ?> shopdz-action="un_sell"  href="javascript:;">下架</a></li>
                							<li><a shopdz-id=<?php echo $v['goods_common_id']; ?> shopdz-action="move_class"  href="javascript:;">移动</a></li>
                							<li><a shopdz-id=<?php echo $v['goods_common_id']; ?> shopdz-action="recycle"  href="javascript:;">回收站</a></li>
                						<?php } ?>
										</ul>
									</li>
									<li class="op-edit-li ope-list-li">
										<a href="javascript:;"><i  class="table-ope-icon op-edit-icon"></i></a>
										<ul class="ope-setting-list ope-setting-list2">
											
											<?php if(in_array($_GET['goods_state'],array('online','recycle','offline'))){ ?>
											<li><a href="<?php echo U('Commodity/goods_edit_step1',array('goods_common_id'=>$v['goods_common_id'],'edit'=>1,'save'=>1));?>">基本信息</a></li>
											<li><a href="<?php echo U('Commodity/goods_edit_step2',array('goods_common_id'=>$v['goods_common_id'],'edit'=>1,'save'=>1));?>">规格库存</a></li>
											<li><a href="<?php echo U('Commodity/goods_add_step3',array('goods_common_id'=>$v['goods_common_id'],'edit'=>1,'save'=>1));?>">商品运费</a></li>
											<li><a href="<?php echo U('Commodity/goods_add_step4',array('goods_common_id'=>$v['goods_common_id'],'edit'=>1,'save'=>1));?>">商品参数</a></li>
											<?php }else{ ?>
											<li><a href="<?php echo U('Commodity/goods_add_step1',array('goods_common_id'=>$v['goods_common_id'],'edit'=>1));?>">基本信息</a></li>
											<?php } ?>
										</ul>
									</li>
								</ul>
					</td>
				</tr>
				<tr class="detail-tr">
					<td class="text-l">
						<div class="commodity-box">
							<i shopdz-goods_common_id="<?php echo $v['goods_common_id'];?>" class="sku-jt     sku_btn"></i>
							<div><img src="__ATTACH_HOST__<?php echo $v['goods_image'];?>"  url="__ATTACH_HOST__<?php echo $v['goods_image'];?>"   alt="" class="commodity-pic pre_view_img"/></div>
							<h6 class="commodity-tit commodity-word"><?php if($v['goods_state']==1){ echo  '<a target="_blank" href="'.C('WAP_URL').'goods_detail.html?id='.$v['goods_common_id'].'">'.$v['goods_name'].'</a>';}else{echo $v['goods_name'];}?></h6>
						</div>
						
					</td>
					<td>
						<?php echo $v['goods_price'];?>
					</td>
					<td class="">
						<?php echo $category_names[$v['gc_id_1']];?><?php if($v['gc_id_2']){ ?> > <?php echo $category_names[$v['gc_id_2']];?><?php } ?>
					</td>
					<td>
						<?php echo date('Y-m-d',$v['add_time']);?>
					</td>
					<td>
						<?php echo $v['freight'];?>
					</td>
					<td>
						<?php echo $v['goods_storage'];?>
					</td>
					<?php if($_GET['goods_state']=='online'){ ?>
					<td class="posiR">
						<img src="__ATTACH_HOST__goods_qcode/<?php echo ($v['goods_common_id']%100).'/'.$v['goods_common_id'].'.png'; ?>"  url="__ATTACH_HOST__goods_qcode/<?php echo ($v['goods_common_id']%100).'/'.$v['goods_common_id'].'.png'; ?>"  class="pre_view_img  breviary-code " />
					</td>
					<?php }else{  ?>
					<td class="posiR">
						--
					</td>
					<?php } ?>
				</tr>
				<?php }  ?>
			</empty>
			</tbody>
		</table>
		</div>
		{$page}
	</div>
</div>
	<script type="text/javascript">
		$(function(){
			//鼠标悬停出大图
			$(document).posi({class:'pre_view_img'});
			var c= null;
			$('.ope-list-li').mouseenter(function(){
				$('.ope-list-li').children('a').removeClass('op-setting-li-hover');
				$(this).children('a').addClass('op-setting-li-hover');
				$('.ope-setting-list').hide();
				$(this).find('.op-setting-icon').addClass('op-setting-icon-hover');
				$(this).find('.ope-setting-list').show();
			});

			$('.ope-list-li').mouseleave(function(){
				var  o = this;
				 c = setTimeout(function(){
					$(o).find('.ope-setting-list').hide();
					$(o).children('a').removeClass('op-setting-li-hover');
					$(o).find('.op-setting-icon').removeClass('op-setting-icon-hover');
				},200)
				
				
			});
			$('.ope-setting-list').mouseenter(function(){
				clearTimeout(c);
			});
			
			$('.ope-setting-list').mouseleave(function(){
				$(this).prev('ul').children('.ope-list-li').children('a').removeClass('op-setting-li-hover');
			//	$(this).hide();
				$(this).prev('ul').children('.ope-list-li').find('.op-setting-icon').removeClass('op-setting-icon-hover');
			});
			$('.ope-setting-list>li').bind('click',function(){
				$(this).parent('ul').hide();
				$(this).parent('ul').prev('ul').children('.ope-list-li').children('a').removeClass('op-setting-li-hover');
				$(this).parent('ul').prev('ul').children('.ope-list-li').find('.op-setting-icon').removeClass('op-setting-icon-hover');
			})
			
			
			$('.breviary-code').mouseenter(function(){
				$(this).next('.qrcode-show').show();
			});
			$('.breviary-code').mouseleave(function(){
				$(this).next('.qrcode-show').hide();
			});
			var skulist = {};
			//点击spu查看sku
	        $('.sku_btn').click(function(){
	        	
		        var _this = this;
			    if(!$(this).hasClass("sku-jtT")){
	    	        var goods_common_id   = parseInt($(this).attr('shopdz-goods_common_id'));
	    	        $('.sku_btn').removeClass('sku-jtT');
	    	        $('.detail-tr').removeClass('borderB-none');
	    	        if(typeof  skulist[goods_common_id] =='undefined'){
	        	        $.get('<?php echo U('Commodity/sku_list')?>',{"goods_common_id":goods_common_id},function(data){
	        	        	var sku_listtr = $(data),
	        	        		sku_listtrwidth = (sku_listtr.find(".sku-list li").length -sku_listtr.find(".sku-release-list li").length) * 126;
	        	        	
	        	        	sku_listtr.find(".sku-list").width(sku_listtrwidth);
	        	        	sku_listtr.find(".sku-listbox").width($(".detail-tr").width() - 40);

	        	        	$(_this).parents('tr').after(sku_listtr);   
	        		    },'html');
	        	        skulist[goods_common_id]  = 1;
	        		    $('.sku-tr').hide();
	        	        $('#spu_tr_'+goods_common_id).show();
	    	        }else{
	    	        	$('.sku-tr').hide();
	    	            $('#spu_tr_'+goods_common_id).show();
	    		    }
	    		    $(this).addClass('sku-jtT');
	    		    $(this).parents('.detail-tr').addClass('borderB-none');
			    }else{
			    	$('.sku-tr').hide();
			    	$('.detail-tr').removeClass('borderB-none');
	    		    $(this).removeClass('sku-jtT');    
			    }
			});
			//每个商品后面的操作按钮事件
	        $('[shopdz-action]').click(function(){
				var op = $(this).attr('shopdz-action');
				var  goods_common_id =  $(this).attr('shopdz-id');
				if(op=='move_class'){   //移动分类
					$("#remove_class_goods_id").val(goods_common_id);
					$('.dialog_element').removeClass('none');
					return  true;
						    
				}
				var post_data =  {"op":op,"goods_common_ids[0]":goods_common_id};
				if(op  == 'delete'){  //彻底删除
				     showConfirm('您确定要删除吗？删除后将无法恢复',function(){
				    	$.post('<?php echo U('Commodity/goods_operation')?>',post_data,function(data){
						        if(data.status == 1){
						         showSuccess(data.info,function(){
					    			    window.location.reload();
				                  });
							    }else{
							    	showError(data.info);
								}
							},'json')	    
					 });	
				    return;
				}
			    $.post('<?php echo U('Commodity/goods_operation')?>',post_data,function(data){
			        if(data.status == 1){
			            showSuccess(data.info,function(){
		    			    window.location.reload();
		    			});
				    }else{
				    	showError(data.info);
					}
				},'json')
		    });

	        $('#remove_class_btn').click(function(){
	        	$('.dialog_element').addClass('none');
			    $.post('<?php echo U('Commodity/goods_move_class'); ?>',$('#remove_class_goods_id,[name="gc_id"]').serialize(),function(data){
			    	 if(data.status == 1){
				    	  showSuccess(data.info,function(){
			    			    window.location.reload();
		                  });
					    }else{
					    	showError(data.info);
						}
			    },'json');

			});
		})
	</script>