<!--家居商品列表开始-->
    		<div class="con-list">
    			<div class="con1">
    				<!--<div class="con-tit">{$vo.item_data.title}</div>-->
    				<div class="listbox1">
    					<foreach name="vo.goods_info" item="goods">
                            <a href="#">
	    					<dl class="list-dl">
	    						<dt><img src="__ATTACH_HOST__{$goods.goods_image}"/></dt>
	    						<dd>
	    							<p class="dl-tit">{$goods.goods_name}</p>
	    							<p class="price1"><span>¥</span>{$goods.goods_price}</p>
	    						</dd>
	    					</dl>
                            </a>
    					</foreach>
    				</div>
    				<!--<div class="more-box1"><a href="{$vo.item_data.more_url}" class="morebtn1">查看更多</a></div>-->
    			</div>
    		</div>
    		<!--家居商品列表结束-->