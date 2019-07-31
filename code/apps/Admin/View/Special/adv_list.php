<!--焦点图轮播开始-->
		<div class="swiper-container m-p" style="margin-top: 48px;width: 100%;">
	        <div class="swiper-wrapper m-p">
	        	<foreach name="vo.item_data" item="vo1" key="k">
		            <div class="swiper-slide m-p"><img src="__ATTACH_HOST__{$vo1.img}"/></div>
		        </foreach>
	        </div>
        	<!-- Add Pagination -->
        	<div class="swiper-pagination"></div>
    	</div>
    	<!--焦点图轮播结束-->