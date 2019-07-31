<script>
	var category = '<?php echo $category;?>';
	var category = eval('('+category+')');
	var searchurl = "<?php echo U('Search/searchspu'); ?>";
	var uploadurl = "<?php echo U('Upload/common'); ?>";
	var attach_dir = '__ATTACH_HOST__';
</script>
<script type="text/javascript" src="__PUBLIC__/admin/js/goods_search.js"></script>
<div class="tipsbox">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>操作提示</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		<li>1.数据调用商品规则为SPU，即一种商品包含所有规格。</li>
		<li>2.商品添加如果不选择图片则默认为主图。</li>
	</ol>
</div>
		<!--内容开始-->
		<form method="post" class="memberForm" name="memberForm" id="memberForm" action="{:U('Cms/UserGetDate')}" enctype="multipart/form-data">
		<input type="hidden" name="id" value="{$dateinfo.id}">
			<div class="iframeCon">

				<div class="iframeMain">
					<ul class="transverse-nav">
						<li class="activeFour"><a href="#"><span>修改数据</span></a></li>
					</ul>
					<div class="white-bg">
						<div class="tab-conbox">
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>模块名称：</dt>

						<dd class="left text-l">
							<input type="text" name="modename"  value="{$dateinfo.modename}" class="com-inp1 radius3 boxsizing" localrequired=""/>
							<p class="remind1">模块名称不能为空</p>
						</dd>
					</dl>
					<dl class="juris-dl boxsizing data-dl">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>数据调用商品：</dt>
						<dd class="left text-l">
							<table class="data-table" id="showtable">
								<thead>
									<tr>
										<th width="40">排序</th>
										<th colspan="2">商品信息</th>
										<th width="60">SPU</th>
										<th width="80">价格</th>
										<th width="60">库存</th>
										<th colspan="2">显示图片</th>
									</tr>
								</thead>
								<tbody id="addshops">
								<foreach name="goodsdata" item="data">
									<tr id="additem_{$key}" class="checkis_{$data.goodsid}">
										<td>
											<input type="text" name="imgorder[]" value="{$data.imgorder}" class="data-sort">
										</td>
										<td width="50" class="text-c">
											<a href="#"><img src="{$data.new_img}" alt="" class="order-goodsImg" /> </a>
										</td>
										<td width="218" class="text-l">
											<div class="data-goods-det">{$data.goods_name}</div>
										</td>
										 <td>{$data.goodsid}</td>
										<td>{$data.price}</td>
										<td>{$data.storage}</td>
										 <td width="262">
											<div class="input-group data-group">
												 <span class="previewbtn">
													<img src="__PUBLIC__/admin/images/imgGray.png" class="viewimg data-viewIcon imgshow" id="imgshowload_{$key}" url="{$data.new_img}"/>
												</span>
												 <input type="hidden" name="goods_id[]" value="{$data.goodsid}">
												 <input type="hidden" name="old_img[]" value="{$data.new_img}">
												 <input type="text" name="new_img[]"  class="form-control data-inp com-inp1 radius3 boxsizing"   id="imgload_{$key}">
												 <input type="file"  class="form-control" style="display: none;"  >
												 <span class="input-group-btn left"> <input type="button"  value="选择文件" class="upload-btn search-btn data-upBtn" onclick="$('input[id=imgload_{$key}]').click();"/> </span>
												 </div>
											</td>
										<td><i class="data-dele-icon" onclick="delselectshops({$key})" style="cursor: pointer"></i></td>
										 </tr>
									<script type="text/javascript">
										$(function(){
											uploadshopimg({$key})
										});
									</script>
								</foreach>
								</tbody>
							</table>
							<input type="button" value="添加商品" class="group-choice-btn showsearchbtn"/>
							<p class="remind1 paddingB3">点击添加商品你要添加的商品</p>
						</dd>
						<div class="clear"></div>
					</dl>
					<dl class="juris-dl boxsizing">
						<dt class="left text-r boxsizing"><span class="redstar">*</span>状态：</dt>
						<dd class="left text-l">
							<div class="switch-box">
								<input type="checkbox"  name="state" id="switch-radio" class="switch-radio" <if condition="$dateinfo['state'] eq '1'">checked="true" </if>/>
								<span class="switch-half switch-open">ON</span>
								<span class="switch-half switch-close close-bg">OFF</span>
							</div>
							<p class="remind1">此模块是否开启</p>
						</dd>
					</dl>
				</div>
				 <div class="btnbox3 boxsizing">
                    <a type="button" id="shops_get" class="btn1 radius3 marginT10 btn3-btnmargin">确认提交</a>
                    <a type="button" id="base_setting" href="{:U('Cms/UserGetDateList')}" class="btn1 radius3 marginT10">返回列表</a>
					 <span style="display: none" class="itemnum" >{$dateinfo.modename}</span>
                </div>
			</div>
		</div>
				</div>
			</div>
		</form>
		<!--内容结束-->
		<!-- 选择商品搜索 S -->
		<div id='showsearch' style="display:none">
			<div class="icon-alert">
				<h1 class="details-tit icon-alert-tit">{$Think.lang.select_goods}<i class="alert-close"></i></h1>
				<div class="group-choice-classify">
					<p class="group-classify-tit">{$Think.lang.goods_class}</p>
					<select name="gc_id_1" id="gc_id_1" class="group-sele">
						<option value="0">{$Think.lang.select_goods_class}</option>
					</select>
					<select name="gc_id_2" id="gc_id_2" class="group-sele">
						<option value="0">{$Think.lang.select_goods_class}</option>
					</select>
					<input type="text" id='search_text' name="search_text" class="com-inp1 group-inp" placeholder="{$Think.lang.select_goods_tips_spu}"/>
					<input type="button" value="{$Think.lang.search}" id='group-search-btn' search_url="<?php echo U('Search/searchspu'); ?>" class="group-choice-btn group-search-btn"/>
				</div>
				<ul class="icon-list fight-icon-list">
				</ul>
				<div class="pagination boxsizing icon-page">
				</div>
				<div class="btn-box-center">
					<input type="button" id='searchok' class="btn1 radius3 " value="{$Think.lang.ok}">
				</div>
			</div>
			<div class="icon-cover"></div>
		</div>
		<!-- 选择商品搜索  E-->
<script type="text/javascript">
	$(function(){
		$('#searchok').click(function() {
			var info = $('.green-border').find('p');
			var itemnum =  parseInt($( '.itemnum' ).text())+parseInt(1);
			if(info.length){
				var goods_name = info.html();
				var goods_storage = info.attr('goods_storage');
				var goods_images = info.attr('goods_images');
				var goods_common_id = info.attr('goods_common_id');
				var sign_price = info.attr('sign_price');
				var loadimg = itemnum;
				if($('.checkis_'+goods_common_id).size() >0){
					showError('该商品已经选择过，请重新选择');
					return false;
				}
				var addconte = '<tr id="additem_'+itemnum+'" class="checkis_'+goods_common_id+'">' +
						'<td><input type="text" name="imgorder[]" value="0" class="data-sort"></td>' +
						'<td width="50" class="text-c"> <a href="#">' +
						'<img src="'+goods_images+'" alt="" class="order-goodsImg" /> </a> ' +
						'</td>' +
						' <td width="218" class="text-l"> <div class="data-goods-det">'+goods_name+'</div> </td>' +
						' <td>'+goods_common_id+'</td> <td>'+sign_price+'</td> <td>'+goods_storage+'</td>' +
						' <td width="262"> ' +
							'<div class="input-group data-group">' +
							' <span class="previewbtn">' +
							'<img src="__PUBLIC__/admin/images/imgGray.png" class="viewimg data-viewIcon imgshow" id="imgshowload_'+itemnum+'" url="'+goods_images+'"/>' +
							'</span>' +
							' <input type="hidden" name="goods_id[]" value="'+goods_common_id+'"><input type="hidden" name="old_img[]" value="'+goods_images+'"><input type="text" name="new_img[]"  class="form-control data-inp com-inp1 radius3 boxsizing"   id="imgload_'+itemnum+'">' +
							' <input type="file"  class="form-control" style="display: none;">' +
							' <span class="input-group-btn left"> <input type="button"  value="选择文件" class="upload-btn search-btn data-upBtn" onclick="$(\'input[id=imgload_'+loadimg+']\').click();"/> </span>' +
							' </div> ' +
						'</td> ' +
						'<td><i class="data-dele-icon" onclick="delselectshops('+loadimg+')" style="cursor: pointer"></i></td>' +
						' </tr>';
				$('#addshops').append(addconte);
				$( '.itemnum' ).text(parseInt(itemnum)+parseInt(1));
				$('.alert-close').click();
				$('#showtable').show();
				uploadshopimg(loadimg);
			}
		});
		$('#shops_get').click(function(){
			flag=checkrequire('memberForm');
			if(!flag) {
				if($("#addshops tr").length >0){
					$('#memberForm').submit();
				}else{
					showError('请选择商品数据');
				}
			}
		});
	});
	function Rotate(){
		$('.data-operation').mouseenter(function(){
			$('.data-icon').addClass('jt-rotate');
			$(this).parent().next().show();
		});
		$('.simulate-sele').mouseleave(function(){
			$(this).hide();
			$('.data-icon').removeClass('jt-rotate');
		});
	}

	Rotate();
function delselectshops(bt){

	$('#additem_'+bt).remove();
}
function uploadshopimg(bt){

	var uploader2 = new plupload.Uploader({
		runtimes: 'html5,html4,flash,silverlight',
		browse_button: 'imgload_'+bt,
		url: "{:U('upLogoFile')}",
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
				uploader2.start();
			},
			UploadProgress: function (up, file) {
				//alert('这里可以做进度条');
			},
			FileUploaded: function (up, file, res) {
				var resobj = eval('(' + res.response + ')');
				if(resobj.status){
					$("#imgload_"+bt).val(resobj.data);
					$("#imgshowload_"+bt).attr('url',resobj.allurl);
				}
			},
			Error: function (up, err) {
				alert('err');
			}
		}

	});
	uploader2.init();
}
	$(document).posi({class:'imgshow',default_img:"DEFAULT_LOGO_IMAGE"});
</script>

