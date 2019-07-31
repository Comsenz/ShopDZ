
<h1 class="details-tit icon-alert-tit">图标库<i class="alert-close"></i></h1>
<div class="type-box">
	<p class="icon-type">类型</p>
	<ul class="icon-type-list">
	<foreach name="icon_types" key="k" item="type">
		<li><a href="#"  onclick="getIcons('{$k}')" id="icon_type_{$k}">{$type}</a></li>
	</foreach>
	</ul>
</div>
<ul class="icon-list">
	<foreach name="category_iconfiles" item="file">
	<li>
		<a href="#">
			<img src="__ATTACH_HOST__{$file}" alt="" class="icon-img"/>
			<input shopdz-action="icon_url_value" type="hidden" value="{$file}">
		</a>
	</li>
	</foreach>
</ul>
<div class="btn-box-center">
	<input type="button" class="btn1 radius3" value="确认提交" id="choise_icon">	
</div>
<script type="text/javascript">
	$(function(){
		$('.icon-type-list li a').bind('click',function(){
			 $(this).addClass("green-font2").parent().siblings().find("a").removeClass("green-font2"); 
		});
		$('.icon-list li').bind('click',function(){
			$(this).addClass('green-border');
			var	typeIcon = "<i class='type-icon'></i>";
			$(this).children('a').append(typeIcon);
			$(this).siblings().find('.type-icon').remove();
			$(this).siblings().removeClass('green-border');
			var img_url = $(this).children('a').find('[shopdz-action="icon_url_value"]').val();
			$('#icon_url').val(img_url);
		});
		$('.alert-close').bind('click',function(){
        	$('#icon_url').val('');
			$('.icon-alert').hide();
			$('.icon-cover').hide();
		})

		$('#choise_icon').click(function(){
			var img_url = $('#icon_url').val();
			if(!img_url) {
				showError('未选中任何图标');
			} else {
				has_image_tag = true;
        	   	$("#gc_image_file").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__ATTACH_HOST__/'+img_url);
        	   	$("#gc_image_file").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val(img_url);
        	   	$('#icon_url').val('');
				$('.icon-alert').hide();
				$('.icon-cover').hide();

			}
		});
	})
</script>