<ul class="release-tab">
	<li class="radius5"><a href="<?php echo  U('Commodity/goods_add_step1',array('goods_common_id'=>$goods_common['goods_common_id']));?>" >1.&nbsp;基本信息</a></li>
	<li class="radius5"><a href="<?php echo  U('Commodity/goods_add_step2',array('goods_common_id'=>$goods_common['goods_common_id']));?>" >2.&nbsp;规格设置</a></li>
	<li class="activeRelease radius5">3.&nbsp;运费设置</li>
	<li class="radius5">4.&nbsp;参数设置</li>
	<li class="radius5">5.&nbsp;发布成功</li>
</ul>
<script>
var dl={};
dl['fixed']  =  '';
dl['fixed']+=''
+'<dl shopdz-dltype="fixed" class="juris-dl boxsizing release-dl">'
	+'<dt class="left text-r boxsizing"><span class="redstar">*</span>运费金额：</dt>'
	+'<dd class="left text-l">'
		+'<div class="release-price boxsizing radius5">'
			+'<input type="text"  name="freight" value="<?php echo price_format($goods_common['freight']);?>" class="price-inp left"/>'
			+'<span class="price-unit left">元</span>'
		+'</div>'
		+'<p class="remind1 release-remind">请输入运费金额</p>'
	+'</dd>'
+'</dl>';

dl['num']  =  '';
dl['num']+=''			
			+'<dl  shopdz-dltype="num" class="juris-dl boxsizing release-dl">'
				+'<dt class="left text-r boxsizing"><span class="redstar">*</span>运费金额：</dt>'
				+'<dd class="left text-l">'
					+'<div class="relese-table-box">'
						+'<table class="com-table relese-table radius3">'
							+'<tr>'
								+'<td   width="80"  class="text-r">'
									+'单件运费'
								+'</td>'
								+'<td   width="120"  class="text-l">'
									+'<div class="release-price release-table-price boxsizing radius3">'
										+'<input type="text"  name="freight" value="<?php echo price_format($goods_common['freight']);?>" class="price-inp left"/>'
										+'<span class="price-unit left">元</span>'
									+'</div>	'
								+'</td>'
								+'<td  width="80" >&nbsp;</td>'
								+'<td  width="120" >&nbsp;</td>'
							+'</tr>'
							+'<tr>'
								+'<td class="text-r">'
									+'每增加'
								+'</td>'
								+'<td class="text-l" >'
									+'<div class="release-price release-table-price boxsizing radius3">'
										+'<input type="text"   name="freight_step_num" value="<?php echo price_format($goods_common['freight_step_num']);?>"   class="price-inp left"/>'
										+'<span class="price-unit left">件</span>'
									+'</div>'
								+'</td>'
								+'<td class="text-r">'
									+'增加运费'
								+'</td>'
								+'<td class="text-l">'
									+'<div class="release-price release-table-price boxsizing radius3">'
										+'<input type="text" name="freight_step_fee" value="<?php echo price_format($goods_common['freight_step_fee']);?>"    class="price-inp left"/>'
										+'<span class="price-unit left">元</span>'
									+'</div>'
								+'</td>'
							+'</tr>'
						+'</table>'
					+'</div>'
					+'<p class="remind1">请设置价格</p>'
				+'</dd>'
			+'</dl>';
dl['weight'] =  '';
dl['weight']+=''					
+'<dl  shopdz-dltype="weight"  class="juris-dl boxsizing release-dl">'
	+'<dt class="left text-r boxsizing"><span class="redstar">*</span>运费金额：</dt>'
	+'<dd class="left text-l">'
		+'<div class="relese-table-box">'
			+'<table class="com-table relese-table radius3">'
				+'<tr>'
					+'<td width="80" class="text-r">'
						+'商品单件重量'
					+'</td>'
					+'<td width="120" class="text-l">'
						+'<div class="release-price release-table-price boxsizing radius3">'
							+'<input type="text"  name="goods_weight" value="<?php echo price_format($goods_common['goods_weight']);?>"   class="price-inp left"/>'
							+'<span class="price-unit left">公斤</span>'
						+'</div>	'
					+'</td>'
					+'<td width="80" class="text-r">'
						+'一公斤内运费'
					+'</td>'
					+'<td width="120"  class="text-l">'
						+'<div class="release-price release-table-price boxsizing radius3">'
							+'<input name="freight" value="<?php echo price_format($goods_common['freight']);?>" type="text" class="price-inp left"/>'
							+'<span class="price-unit left">元</span>'
						+'</div>'	
					+'</td>'
				+'</tr>'
				+'<tr>'
					+'<td class="text-r">'
						+'每增加'
					+'</td>'
					+'<td class="text-l">'
						+'<div class="release-price release-table-price boxsizing radius3">'
							+'<input type="text"  name="freight_step_num" value="<?php echo price_format($goods_common['freight_step_num']);?>"   class="price-inp left"/>'
							+'<span class="price-unit left">公斤</span>'
						+'</div>'
					+'</td>'
					+'<td class="text-r">'
						+'增加运费'
					+'</td>'
					+'<td class="text-l">'
						+'<div class="release-price release-table-price boxsizing radius3">'
							+'<input  name="freight_step_fee" value="<?php echo price_format($goods_common['freight_step_fee']);?>"  type="text" class="price-inp left"/>'
							+'<span class="price-unit left">元</span>'
						+'</div>'
					+'</td>'
				+'</tr>'
			+'</table>'
		+'</div>'
		+'<p class="remind1">请设置价格</p>'
	+'</dd>'
+'</dl>';
dl['volume'] =  '';
dl['volume']+=''				
+'<dl  shopdz-dltype="volume"  class="juris-dl boxsizing release-dl">'
	+'<dt class="left text-r boxsizing"><span class="redstar">*</span>运费金额：</dt>'
	+'<dd class="left text-l">'
		+'<div class="relese-table-box">'
			+'<table class="com-table relese-table radius3">'
				+'<tr>'
					+'<td width="80" class="text-r">'
						+'商品单件体积'
					+'</td>'
					+'<td width="120" class="text-l">'
					+'	<div class="release-price release-table-price boxsizing radius3">'
							+'<input type="text"  name="goods_volume" value="<?php echo price_format($goods_common['goods_volume']);?>"   class="price-inp left"/>'
							+'<span class="price-unit left">立方米</span>'
						+'</div>'
					+'</td>'
					+'<td width="80" class="text-r">'
						+'一立方内运费'
					+'</td>'
					+'<td width="120"  class="text-l">'
						+'<div class="release-price release-table-price boxsizing radius3">'
							+'<input name="freight" value="<?php echo price_format($goods_common['freight']);?>" type="text" class="price-inp left"/>'
							+'<span class="price-unit left">元</span>'
						+'</div>'	
					+'</td>'
				+'</tr>'
				+'<tr>'
					+'<td class="text-r">'
						+'每增加'
					+'</td>'
					+'<td class="text-l">'
						+'<div class="release-price release-table-price boxsizing radius3">'
							+'<input type="text"  name="freight_step_num" value="<?php echo price_format($goods_common['freight_step_num']);?>"   class="price-inp left"/>'
							+'<span class="price-unit left">立方</span>'
						+'</div>'
					+'</td>'
					+'<td class="text-r">'
						+'增加运费'
					+'</td>'
					+'<td class="text-l">'
						+'<div class="release-price release-table-price boxsizing radius3">'
							+'<input  name="freight_step_fee" value="<?php echo price_format($goods_common['freight_step_fee']);?>"  type="text" class="price-inp left"/>'
							+'<span class="price-unit left">元</span>'
						+'</div>'
					+'</td>'
				+'</tr>'
			+'</table>'
		+'</div>'
		+'<p class="remind1">请设置价格</p>'
	+'</dd>'
+'</dl>';

</script>
<!--内容开始-->
<div class="iframeCon">
	<ul class="transverse-nav">
	    <li class="activeFour"><a href="javascript:;"><span>运费设置</span></a></li>
	</ul>
	<div class="white-bg">
		<form class="form-horizontal" id="main_form" autocomplete="off"   action="<?php echo  U('Commodity/goods_add_step3');?>"  method="post" >
        <input name="goods_common_id"   type="hidden"  value="<?php  echo intval($goods_common['goods_common_id']);?>"   > 
		<div class="jurisdiction boxsizing release-juris">
			<dl class="juris-dl boxsizing release-dl">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>运费方式：</dt>
				<dd class="left text-l">
					<div class="button-holder">
						<p class="radiobox"><input type="radio"  <?php if($goods_common['freight_type']=='fixed'){ echo  'checked="checked"';}?> name="freight_type" value="fixed"  id="radio_fixed" class="freight_type regular-radio" /><label  for="radio_fixed"></label><span class="radio-word">固定运费</span></p>
						<p class="radiobox"><input type="radio"  <?php if($goods_common['freight_type']=='num'){ echo  'checked="checked"';}?> name="freight_type" value="num"  id="radio_num" class="freight_type regular-radio" /><label for="radio_num"></label><span class="radio-word">计件</span></p>
						<p class="radiobox"><input type="radio"  <?php if($goods_common['freight_type']=='weight'){ echo  'checked="checked"';}?> name="freight_type" value="weight" id="radio_weight"  class="freight_type regular-radio" /><label  for="radio_weight"></label><span class="radio-word">重量</span></p>
						<p class="radiobox"><input type="radio"  <?php if($goods_common['freight_type']=='volume'){ echo  'checked="checked"';}?> name="freight_type" value="volume"  id="radio_volume" class="freight_type regular-radio" /><label  for="radio_volume"></label><span class="radio-word">体积</span></p>
					</div>
					<p class="remind1 release-remind">请选择运费方式</p>
				</dd>
			</dl>
			<span id="price_dl_tag" ></span>
		</div>
		<div class="btnbox3 boxsizing" style="border-top:0;">
			<?php if($_GET['save']==1){ ?>
		    <a type="button" id="submit_button" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
		    <a class="btn1 radius3 marginT10 " href="{:U('Commodity/goods_list')}">返回列表</a>
			<?php }else{  ?>
			<a id="submit_button"  class="btn1 radius3 btn3-btnmargin release-nextbtn"><span class="next-remind boxsizing">下一步</span><span class="next-con">参数设置</span></a>
		    <?php } ?>
		</div>
		</form>
	</div>
</div>
<!--内容结束-->
<script>
$("#price_dl_tag").before(dl['<?php echo $goods_common['freight_type']?>']);
$(function(){
	$('[name="freight_type"]').click(function(){
		var new_type = $(this).val();
	    var old = $('[shopdz-dltype]').attr('shopdz-dltype');
	    if(new_type!=old){
	    	$('[shopdz-dltype]').remove();
	    	$("#price_dl_tag").before(dl[new_type]);
		}
	});
	$('#submit_button').click(function(){
    //        flag=checkrequire('main_form');
        $.post($('#main_form').attr('action'),$('#main_form').serialize(),function(data){
        	if(data.status==1){
            	showSuccess(data.info,function(){
             		<?php if($_GET['save']==1){ ?>
                	window.location.href = '<?php echo U('Commodity/goods_list');?>' ;    
                	<?php }else{ ?>
                    window.location.href = data.url;
            	    <?php } ?>
                });
            }else{
                showError(data.info);
            }
        },'json');
    });	
});
</script>
