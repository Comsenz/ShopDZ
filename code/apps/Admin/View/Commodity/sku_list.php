<tr id="spu_tr_<?php echo $_GET['goods_common_id'];?>" class="sku-tr">
	<td colspan="7" class="text-l" >
	    <?php if(!empty($sku_list)){ ?>
	    <div class="sku-listbox">
		<ul class="sku-list">
		    <?php foreach($sku_list as $sku){  ?>
			<li>
				<div class="sku_img_div"><img src="__ATTACH_HOST__<?php echo  $sku['goods_image']?>" url="__ATTACH_HOST__<?php echo  $sku['goods_image']?>"     alt="" class="sku-pic  pre_view_img"/></div>
				<ul class="sku-release-list">
					<li>
						<p class="sku-release">SKU</p>
						<p class="sku-release sku-releaseTwo"><?php  echo $sku['goods_id'];?></p>
					</li>
					<li>
						<p class="sku-release">价格</p>
						<p class="sku-release sku-releaseTwo sku-orange"><?php echo $sku['goods_price'];?></p>
					</li>
					<li>
						<p class="sku-release">库存</p>
						<p class="sku-release sku-releaseTwo sku-blue"><?php echo $sku['goods_storage'];?></p>
					</li>
					<?php if($sku['goods_barcode']){ ?>
					<li>
						<p class="sku-release">货号</p>
						<p class="sku-release sku-releaseTwo"><?php echo  $sku['goods_barcode'];?></p>
					</li>
					<?php } ?>
					<?php foreach($sku['spec'] as $spname=>$spv){ ?>
					<li>
						<p class="sku-release" title="<?php echo $spname;?>" ><?php echo $spname;?></p>
						<p class="sku-release sku-releaseTwo sku-green" title="<?php echo $spv;?>" ><?php echo $spv;?></p>
					</li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
		</ul>
		</div>
        <?php }else{  ?>
        <p>该商品信息还不完整</p>
        <?php } ?>
	</td>
</tr>