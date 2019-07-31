<ul class="release-tab">
	<li class="radius5"><a href="<?php echo  U('Commodity/goods_add_step1',array('goods_common_id'=>$goods_common['goods_common_id'],'edit'=>1));?>" >1.&nbsp;基本信息</a></li>
	<li class="radius5 activeRelease">2.&nbsp;规格设置</li>
	<li class="radius5">3.&nbsp;运费设置</li>
	<li class="radius5">4.&nbsp;参数设置</li>
	<li class="radius5">5.&nbsp;发布成功</li>
</ul>

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
	    <li class="activeFour"><a href="javascript:;"><span>规格设置</span></a></li>
	</ul>
	<div class="white-bg">
		<form class="form-horizontal" id="main_form" autocomplete="off"  method="post"  action="<?php echo  U('Commodity/goods_add_step2');?>">
        <input name="goods_common_id"   type="hidden"  value="<?php  echo intval($goods_common['goods_common_id']);?>"   > 
		<div class="jurisdiction boxsizing release-juris">
			<dl class="juris-dl boxsizing release-dl">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>请选择规格：</dt>
				<dd class="left text-l">
					<div class="checkbox-holder"><?php if(!empty($spec_list)){?><?php foreach($spec_list  as $v){ ?><p class="radiobox release-check"><input  class="regular-radio spec_lists"  type="checkbox" value="<?php echo $v['spec_id']?>"  id="spec_lists_<?php echo $v['spec_id']?>"   name="spec_ids[]"/><label for="spec_lists_<?php echo $v['spec_id']?>"></label><span  title="<?php echo $v['spec_name'];?>" class="radio-word"><?php echo $v['spec_name'];?></span></p><?php } ?><?php } ?>
					</div>
					<p class="remind1">请选择商品规格 至少要选一个 最多不允许超过2个
					</p>
				</dd>
			</dl>
			<dl id="spec_value_after"  class="juris-dl boxsizing release-dl">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>价格设置：</dt>
				<dd class="left text-l" id="goods_list_div">
				</dd>
			</dl>
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
<!--内容结束-->
<script type="text/javascript">
var  goods_common_price =  '<?php  echo  $goods_common['goods_price']?>';
	$(function(){
		$('#submit_button').click(function(){
// 	        flag=checkrequire('main_form');
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
	    
		//批量 设置价格的显示
		$(document).on('click','.edit-icon',function(){
			$(this).toggleClass('uploadimg-active ');
			if($(this).parents('th').find('.edit-spec').css("display")=="none"){
				$(this).parents('th').find('.edit-spec').show();
			} else {
				$(this).parents('th').find('.edit-spec').hide();
			}
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
					var  html= '<p class="radiobox release-check"><input  data-spec_id="'+spec_value.spec_id+'" id="spec_value_lists_'+spec_value.spec_value_id+'" type="checkbox"  value="'+spec_value.spec_value_id+'" name="spec_value['+spec_value.spec_id+'][]"  class="regular-radio spec_value_lists" /><label for="spec_value_lists_'+spec_value.spec_value_id+'"  class="spec_lists_label"></label><span title="'+spec_value.spec_value+'"  class="radio-word">'+spec_value.spec_value+'</span></p>';
                    $('#spec_value_div_'+spec_value.spec_id+' .checkbox-holder').append(html);
            		//绑定点击事件
                    $('#add_spec_value_id').val(0);
                    $('#add_spec_value_text').val('');
                    $('.spec_add_element').addClass('none');
    				showSuccess('添加成功');
                }else{
                     showError(response.info);
                }
            },'json');
        });
        $(document).on('click','.spec_value_lists',re_compute_spec_value);
	})
</script>
<script src="__PUBLIC__/js/goods_add.js"></script>