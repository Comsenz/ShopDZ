<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>

<!--
	1.添加绿色边框时，给当前元素添加类green-bor
	2.若需要文字变成绿色，则给当前元素添加类green-font
-->



<ul class="uploadbox boxsizing">
	<li class="left uploadbox-li boxsizing">
		<div class="img-style">
			<img src="../../static/img/uploadimg.jpg" alt="" class="uploadimg boxsizing"/>
		</div>
		<div class="asDefault-box asDefault-box-cli">
			<a href="#" class="img-dele green-font green-bor" title="移除">X</a>
			<p class="asDefault boxsizing green-font"><i class="font-face icon-ok-circle blue-i i-padding green-font"></i>默认主图</p>
		</div>
		<div class="operationbox boxsizing">
			<p class="left sort">排序<input type="text" name="" id="" placeholder="2" class="sort-inp"/></p>
			<p class="left upload-p">
				<input type="file" class="upload-inp2" hidefocus="true"/>
				<span class="inp2-cover boxsizing"><i class="font-face icon-upload-alt up-icon"></i>上传</span>
			</p>
		</div>
	</li>
	<li class="left uploadbox-li boxsizing">
		<div class="img-style">
			<img src="../../static/img/uploadimg.jpg" alt="" class="uploadimg boxsizing"/>
		</div>
		<div class="asDefault-box">
			<a href="#" class="img-dele" title="移除">X</a>
			<p class="asDefault boxsizing"><i class="font-face icon-ok-circle blue-i i-padding"></i>默认主图</p>
		</div>
		<div class="operationbox boxsizing">
			<p class="left sort">排序<input type="text" name="" id="" placeholder="2" class="sort-inp"/></p>
			<p class="left upload-p">
				<input type="file" class="upload-inp2" hidefocus="true"/>
				<span class="inp2-cover boxsizing"><i class="font-face icon-upload-alt up-icon"></i>上传</span>
			</p>
		</div>
	</li>
	<li class="left uploadbox-li boxsizing">
		<div class="img-style">
			<img src="../../static/img/uploadimg.jpg" alt="" class="uploadimg boxsizing"/>
		</div>
		<div class="asDefault-box">
			<a href="#" class="img-dele" title="移除">X</a>
			<p class="asDefault boxsizing"><i class="font-face icon-ok-circle blue-i i-padding"></i>默认主图</p>
		</div>
		<div class="operationbox boxsizing">
			<p class="left sort">排序<input type="text" name="" id="" placeholder="2" class="sort-inp"/></p>
			<p class="left upload-p">
				<input type="file" class="upload-inp2" hidefocus="true"/>
				<span class="inp2-cover boxsizing"><i class="font-face icon-upload-alt up-icon"></i>上传</span>
			</p>
		</div>
	</li>
	<li class="left uploadbox-li boxsizing">
		<div class="img-style">
			<img src="../../static/img/uploadimg.jpg" alt="" class="uploadimg boxsizing"/>
		</div>
		<div class="asDefault-box">
			<a href="#" class="img-dele" title="移除">X</a>
			<p class="asDefault boxsizing"><i class="font-face icon-ok-circle blue-i i-padding"></i>默认主图</p>
		</div>
		<div class="operationbox boxsizing">
			<p class="left sort">排序<input type="text" name="" id="" placeholder="2" class="sort-inp"/></p>
			<p class="left upload-p">
				<input type="file" class="upload-inp2" hidefocus="true"/>
				<span class="inp2-cover boxsizing"><i class="font-face icon-upload-alt up-icon"></i>上传</span>
			</p>
		</div>
	</li>
	<li class="left uploadbox-li boxsizing">
		<div class="img-style">
			<img src="../../static/img/uploadimg.jpg" alt="" class="uploadimg boxsizing"/>
		</div>
		<div class="asDefault-box">
			<a href="#" class="img-dele" title="移除">X</a>
			<p class="asDefault boxsizing"><i class="font-face icon-ok-circle blue-i i-padding"></i>默认主图</p>
		</div>
		<div class="operationbox boxsizing">
			<p class="left sort">排序<input type="text" name="" id="" placeholder="2" class="sort-inp"/></p>
			<p class="left upload-p">
				<input type="file" class="upload-inp2" hidefocus="true"/>
				<span class="inp2-cover boxsizing"><i class="font-face icon-upload-alt up-icon"></i>上传</span>
			</p>
		</div>
	</li>
</ul>

<script type="text/javascript" src="__PUBLIC__/js/jquery.min.js"></script>
<script type="text/javascript">
	$(function(){
		//鼠标滑过时，添加样式
		$('.asDefault-box').bind('click',function(){
			$(this).addClass('asDefault-box-cli').parents('li').siblings().find('.asDefault-box').removeClass('asDefault-box-cli')
			$(this).children().addClass('green-font').parents('li').siblings().children().children().removeClass('green-font');
			$(this).children('.img-dele').addClass('green-bor').parents('li').siblings().children().children().removeClass('green-bor');
			$(this).find('i').addClass('green-font').parents('li').siblings().children().find('i').removeClass('green-font');
			
		})
 		
		$('.upload-inp2').hover(function(){
			$(this).next().css('background','#f0f0f0')
		},function(){
			$(this).next().css('background','#f5f5f5')
		})
	})
</script>



