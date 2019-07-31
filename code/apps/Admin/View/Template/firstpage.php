		<style type="text/css">
			.page-upload-list {
				width: 600px;
			}
			.page-upload-list>li {
				width: 100%;
				border: 1px dashed #e0e0e0;
				padding: 10px;
				overflow: hidden;
				zoom: 1;
			}
			.first-upload-left {
				max-width: 160px;
			}
			.first-upload-right {
				min-width: 420px;
				height: 100%;
				min-height: 165px;
				background: #f0f0f0;
			}
		</style>
		<!--<ul class="release-tab">
			<li class="activeRelease radius5">1.&nbsp;基本信息</li>
			<li class="radius5">2.&nbsp;规格设置</li>
			<li class="radius5">3.&nbsp;运费设置</li>
			<li class="radius5">4.&nbsp;参数设置</li>
			<li class="radius5">5.&nbsp;发布成功</li>
		</ul>-->
		<!--内容开始-->
		<div class="iframeCon">
			<div class="white-bg">
				<ul class="page-upload-list">
					<li>
						<ul class="uploadbox boxsizing left first-upload-left">
							<li class="left uploadbox-li boxsizing">
								<div class="img-style boxsizing">
									<img src="images/images/uploadimg.png" alt="" class="uploadimg boxsizing"/>
								</div>
								<div class="asDefault-box-cover boxsizing">
								</div>
								<i class="up-icon dele-icon"></i>
								<div class="operationbox boxsizing">
									<p class="upload-p">
										<input type="file" class="upload-inp2" hidefocus="true"/>
										<span class="inp2-cover boxsizing"><i class="up-icon upload-icon"></i>上传</span>
									</p>
								</div>
							</li>
						</ul>
						<div class="first-upload-right right"></div>
					</li>
					<li>
						<ul class="uploadbox boxsizing left first-upload-left">
							<li class="left uploadbox-li boxsizing">
								<div class="img-style boxsizing">
									<img src="images/images/uploadimg.png" alt="" class="uploadimg boxsizing"/>
								</div>
								<div class="asDefault-box-cover boxsizing">
								</div>
								<i class="up-icon dele-icon"></i>
								<div class="operationbox boxsizing">
									<p class="upload-p">
										<input type="file" class="upload-inp2" hidefocus="true"/>
										<span class="inp2-cover boxsizing"><i class="up-icon upload-icon"></i>上传</span>
									</p>
								</div>
							</li>
						</ul>
						<div class="first-upload-right right"></div>
					</li>
					<li>
						<ul class="uploadbox boxsizing left first-upload-left">
							<li class="left uploadbox-li boxsizing">
								<div class="img-style boxsizing">
									<img src="images/images/uploadimg.png" alt="" class="uploadimg boxsizing"/>
								</div>
								<div class="asDefault-box-cover boxsizing">
								</div>
								<i class="up-icon dele-icon"></i>
								<div class="operationbox boxsizing">
									<p class="upload-p">
										<input type="file" class="upload-inp2" hidefocus="true"/>
										<span class="inp2-cover boxsizing"><i class="up-icon upload-icon"></i>上传</span>
									</p>
								</div>
							</li>
						</ul>
						<div class="first-upload-right right"></div>
					</li>
				</ul>
						
			</div>
		</div>
		<!--内容结束-->
		<script type="text/javascript">
			$(function(){
				//图片上传
		 		$('.uploadbox>li').mouseenter(function(){
		 			$(this).find('.asDefault-box-cover').addClass('block');
	 				$(this).find('.dele-icon').addClass('block');
		 			$(this).find('.inp2-cover').addClass('inp2-cover-hover');
		 			//$(this).find('.asDefault').addClass('asdefault-green');
		 		})
		 		$('.uploadbox>li').mouseleave(function(){
		 			$(this).find('.asDefault-box-cover ').removeClass('block');
		 			$(this).find('.dele-icon').removeClass('block');
		 			$(this).find('.inp2-cover').removeClass('inp2-cover-hover');
		 			//$(this).find('.asDefault').addClass('asdefault-green');
		 		})
				$('.uploadbox>li').bind('click',function(){
					//alert('666666666666');
					$(this).find('.asDefault-box-cover').addClass('block2').parents('.uploadbox-li').siblings().find('.asDefault-box-cover').removeClass('block2');
					$(this).find('.asDefault').addClass('asdefault-green').parents('.uploadbox-li').siblings().find('.asDefault').removeClass('asdefault-green');
					$(this).find('.inp2-cover').addClass('inp2-cover-hover2').parents('.uploadbox-li').siblings().find('.inp2-cover').removeClass('inp2-cover-hover2');
					
				})
				
				//商品发布步骤
				$(".release-tab li").click(function(){
					$(this).addClass("activeRelease").siblings().removeClass('activeRelease');
					
				})
			})
		</script>

