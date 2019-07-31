
	

	<!--提示-->
	<div class="tipsbox">
	    <div class="tips boxsizing radius3">
	        <div class="tips-titbox">
	            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
	            <span class="open-span span-icon"><i class="open-icon"></i></span>
	        </div>
	    </div>
		<ol class="tips-list" id="tips-list">
			<li>1.提示提示提示提示提示提示提示。</li>
		</ol>
	</div>
	<div class="iframeCon">
		<div class="iframeMain">
			<ul class="transverse-nav">
				<li class="activeFour"><a  href="#"><span>新建运费模板</span></a></li>
			</ul>
			<div class="white-bg">
				<div class="tab-conbox">
					<div class="jurisdiction boxsizing">
						<dl class="juris-dl boxsizing paddingB20">
							<dt class="left text-r boxsizing">模板名称：</dt>
							<dd class="left text-l">
								<input type="text" class="com-inp1 radius3 boxsizing" name="" value=""/>
							</dd>
						</dl>
						<dl class="juris-dl boxsizing paddingB30 paddingT20">
							<dt class="left text-r boxsizing">模板设置：</dt>
							<dd class="left text-l">
								<div class="freight-btnbox">
									<table class="com-table freTable">
										<thead>
											<tr>
												<th>售卖区域</th>
												<th width="270">运费规则</th>
												<th width="60">操作</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<div class="freArea">
														北京(北京市(东城区,西城区,朝阳区,丰台区,石景山区,海淀区,门头沟区,房山区,通州区,通州区,昌平区,大兴区,怀柔区,平谷区,密云县,延庆县,其他))
													</div>
													</td>
												<td>
													<div class="freRule">免运费</div>
												</td>
												<td><i class="data-dele-icon"></i></td>
											</tr>
											<tr>
												<td>
													<div class="freArea">
														北京(北京市(东城区,西城区,朝阳区,丰台区,石景山区,海淀区,门头沟区,房山区,通州区,通州区,昌平区,大兴区,怀柔区,平谷区,密云县,延庆县,其他))
													</div>
													</td>
												<td>
													<div class="freRule">固定运费：<span>10.00</span>元</div>
												</td>
												<td><i class="data-dele-icon"></i></td>
											</tr>
											<tr>
												<td>
													<div class="freArea">
														北京(北京市(东城区,西城区,朝阳区,丰台区,石景山区,海淀区,门头沟区,房山区,通州区,通州区,昌平区,大兴区,怀柔区,平谷区,密云县,延庆县,其他))
													</div>
													</td>
												<td>
													<div class="freRule">按体积计费：单件商品体积<span>N</span>立方米<br/>首<span>N</span>立方米<span>N</span>元，每增加<span>N</span>立方米增加<span>N</span>元</div>
												</td>
												<td><i class="data-dele-icon"></i></td>
											</tr>
											<tr>
												<td>
													<div class="freArea">
														北京(北京市(东城区,西城区,朝阳区,丰台区,石景山区,海淀区,门头沟区,房山区,通州区,通州区,昌平区,大兴区,怀柔区,平谷区,密云县,延庆县,其他))
													</div>
													</td>
												<td>
													<div class="freRule">按件计费：首<span>N</span>件<span>N</span>元，每增加<span>N</span>件增加<span>N</span>元</div>
												</td>
												<td><i class="data-dele-icon"></i></td>
											</tr>
											<tr>
												<td>
													<div class="freAlert-click freArea-click">
														设置售卖区域
													</div>	
													
												</td>
												<td>
													<div class="freAlert-click freRule-click">
													设置运费规格
													</div>
												</td>
												<td>
													<i class="data-dele-icon"></i>
												</td>
											</tr>
										</tbody>
									</table>
									<input type="button" value="添加售卖区域和规则" class="freight-btn group-choice-btn"/>
								</div>
							</dd>
						</dl>
					</div>
					<div class="btnbox3 boxsizing">
						<a type="button" id="areaform_setting" class="btn1 radius3 marginT10  btn3-btnmargin">确认提交</a>
						<a class="btn1 radius3 marginT10" href="#">返回列表</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="cover none"></div>
	
	
	<!--区域设置-->
	<div class="alert areaAlert">
        <i class="close-icon"></i>
		<h1 class="special-tit">运费规则</h1>
        <div class="areaAlert-con T-Level">
	        <div class="checkBox area">
	          	<div class="check-box A-level">
		            <input  type="checkbox" class="check-inp J_Area" disable="false" name="area_id" value="jdjjjj "/>
		            <label class="check-label">北京市</label>
		            <span class="jtIcon-box"><i class="icon jt-icon"></i></span>
	          	</div>
	          	<div class="Four-Level">
		            <ul class="fourLevel-list">
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区地区地区地区地区</label>
		              	</li>
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区地区区</label>
		              	</li>
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区地区地地区</label>
		              	</li>
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区地区</label>
		              	</li>
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区地区地区地区</label>
		              	</li>
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区区地区</label>
		              	</li>
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区</label>
		              	</li>
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区地区区地区</label>
		              	</li>
			            <li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区</label>
		              	</li>
		            	<li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区地区</label>
		              	</li>
		            </ul>
		            <div class="areaSure">
		            	<input type="button" name="" id="" value="确定" class="areaSure-btn"/>
		            </div>
	          	</div>
	        </div>
	        <div class="checkBox area">
	          	<div class="check-box A-level">
		            <input  type="checkbox" class="check-inp J_Area" disable="false" name="area_id" value="jdjjjj "/>
		            <label class="check-label">北京市</label>
		            <span class="jtIcon-box"><i class="icon jt-icon"></i></span>
	          	</div>
	          	<div class="Four-Level">
		            <ul class="fourLevel-list">
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区地区</label>
		              	</li>
			            <li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区地区地区</label>
		              	</li>
		            	<li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地</label>
		              	</li>
		            </ul>
		             <div class="areaSure">
		            	<input type="button" name="" id="" value="确定" class="areaSure-btn"/>
		            </div>
	          	</div>
	        </div>
	        <div class="checkBox area">
	          	<div class="check-box A-level">
		            <input  type="checkbox" class="check-inp J_Area" disable="false" name="area_id" value="jdjjjj "/>
		            <label class="check-label">新疆省</label>
		            <span class="jtIcon-box"><i class="icon jt-icon"></i></span>
	          	</div>
	          	<div class="Four-Level">
		            <ul class="fourLevel-list">
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区地区</label>
		              	</li>
			            <li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区地区地区</label>
		              	</li>
		            
		            	<li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区</label>
		              	</li>
		            </ul>
		             <div class="areaSure">
		            	<input type="button" name="" id="" value="确定" class="areaSure-btn"/>
		            </div>
	          	</div>
	        </div>
	        <div class="checkBox area">
	          	<div class="check-box A-level">
		            <input  type="checkbox" class="check-inp J_Area" disable="false" name="area_id" value="jdjjjj "/>
		            <label class="check-label">内蒙古自治区</label>
		            <span class="jtIcon-box"><i class="icon jt-icon"></i></span>
	          	</div>
	          	<div class="Four-Level">
		            <ul class="fourLevel-list">
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区地区地区</label>
		              	</li>
			            <li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区</label>
		              	</li>
		            
		            	<li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区全部区</label>
		              	</li>
		            </ul>
		             <div class="areaSure">
		            	<input type="button" name="" id="" value="确定" class="areaSure-btn"/>
		            </div>
	          	</div>
	        </div>
	        <div class="checkBox area">
	          	<div class="check-box A-level">
		            <input  type="checkbox" class="check-inp J_Area" disable="false" name="area_id" value="jdjjjj "/>
		            <label class="check-label">宁夏回族自治区</label>
		            <span class="jtIcon-box"><i class="icon jt-icon"></i></span>
	          	</div>
	          	<div class="Four-Level">
		            <ul class="fourLevel-list">
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区其它</label>
		              	</li>
			            <li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区地区地区</label>
		              	</li>
		            	<li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区地区地区</label>
		              	</li>
		            </ul>
		             <div class="areaSure">
		            	<input type="button" name="" id="" value="确定" class="areaSure-btn"/>
		            </div>
	          	</div>
	        </div>
	        <div class="checkBox area rowLast">
	          	<div class="check-box A-level">
		            <input  type="checkbox" class="check-inp J_Area" disable="false" name="area_id" value="jdjjjj "/>
		            <label class="check-label">广西壮族自治区</label>
		            <span class="jtIcon-box"><i class="icon jt-icon"></i></span>
	          	</div>
	          	<div class="Four-Level">
		            <ul class="fourLevel-list">
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区地区</label>
		              	</li>
			            <li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区地区地区</label>
		              	</li>
		            	<li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区地</label>
		              	</li>
		            </ul>
		             <div class="areaSure">
		            	<input type="button" name="" id="" value="确定" class="areaSure-btn"/>
		            </div>
	          	</div>
	        </div>
	        <div class="checkBox area">
	          	<div class="check-box A-level">
		            <input  type="checkbox" class="check-inp J_Area" disable="false" name="area_id" value="jdjjjj "/>
		            <label class="check-label">广西壮族自治区</label>
		            <span class="jtIcon-box"><i class="icon jt-icon"></i></span>
	          	</div>
	          	<div class="Four-Level">
		            <ul class="fourLevel-list">
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">uuuuuuuuu</label>
		              	</li>
			            <li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区</label>
		              	</li>
		            	<li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区</label>
		              	</li>
		            </ul>
		             <div class="areaSure">
		            	<input type="button" name="" id="" value="确定" class="areaSure-btn"/>
		            </div>
	          	</div>
	        </div>
	        <div class="checkBox area">
	          	<div class="check-box A-level">
		            <input  type="checkbox" class="check-inp J_Area" disable="false" name="area_id" value="jdjjjj "/>
		            <label class="check-label">广西壮族自治区</label>
		            <span class="jtIcon-box"><i class="icon jt-icon"></i></span>
	          	</div>
	          	<div class="Four-Level">
		            <ul class="fourLevel-list">
		              	<li class="check-box community">
		                	<input  type="checkbox" class="check-inp J_Community" disable="false" name="community_id" value="jkdfhsjkfhdj "/>
		                	<label class="check-label">地区地区</label>
		              	</li>
			            <li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区地区</label>
		              	</li>
		            	<li class="check-box">
		                	<input  type="checkbox" class="check-inp J_Community" value="0"/>
		                	<label class="check-label">地区地区</label>
		              	</li>
		            </ul>
		             <div class="areaSure">
		            	<input type="button" name="" id="" value="确定" class="areaSure-btn"/>
		            </div>
	          	</div>
	        </div>
	        <div class="clear"></div>
        </div>
        <div class="alert-btnbox boxsizing">
			<a href="javascript:;" class="btn1 radius3">确认</a>
			<a href="javascript:;" class="btn1 radius3 alertCancel">取消</a>
		</div>
	</div>
	<!--区域设置-->
	
	
	
	
	<!--运费规则-->
	<div class="alert freAlert">
		<i class="close-icon"></i>
		<h1 class="special-tit">运费规则</h1>
		<div class="addSpec-con freAlert-con">
			<div class="LRStructure paddingB30">
				<span class="special-con-left left">计费方式</span>
				<div class="button-holder left">
					<p class="radiobox"><input type="radio" id="radio-1-1" name="radio-1-set" class="regular-radio" checked="checked"><label for="radio-1-1"></label><span class="radio-word radio-word-black">固定运费</span></p>
					<p class="radiobox"><input type="radio" id="radio-1-2" name="radio-1-set" class="regular-radio" checked="checked"><label for="radio-1-2"></label><span class="radio-word radio-word-black">计件</span></p>
					<p class="radiobox"><input type="radio" id="radio-1-3" name="radio-1-set" class="regular-radio" checked="checked"><label for="radio-1-3"></label><span class="radio-word radio-word-black">重量</span></p>
					<p class="radiobox"><input type="radio" id="radio-1-4" name="radio-1-set" class="regular-radio" checked="checked"><label for="radio-1-4"></label><span class="radio-word radio-word-black">体积</span></p>
				</div>	
			</div>
			<div class="LRStructure">
				<span class="special-con-left left">运费金额</span>
				<!--<div class="relese-table-box freTable2-box left">
					<table class="com-table relese-table freTable2">
						<tbody>
							<tr>
								<td width="80" class="text-r">商品单件体积</td>
								<td width="110" class="text-l">
									<div class="release-price release-table-price boxsizing radius3">
										<input type="text" name="goods_volume" value="0" class="price-inp left">
										<span class="price-unit left">立方米</span>
									</div>
								</td>
								<td width="80" class="text-r">一立方内运费</td>
								<td width="110" class="text-l">
									<div class="release-price release-table-price boxsizing radius3">
										<input name="freight" value="0" type="text" class="price-inp left">
										<span class="price-unit left">元</span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="text-r">每增加</td>
								<td class="text-l">
									<div class="release-price release-table-price boxsizing radius3">
										<input type="text" name="freight_step_num" value="0" class="price-inp left">
										<span class="price-unit left">立方</span>
									</div>
								</td>
								<td class="text-r">增加运费</td>
								<td class="text-l">
									<div class="release-price release-table-price boxsizing radius3">
										<input name="freight_step_fee" value="0" type="text" class="price-inp left">
										<span class="price-unit left">元</span>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>-->
				<div class="release-price boxsizing radius5 left freTable2-box">
					<input type="text" name="freight" value="0" class="price-inp left">
					<span class="price-unit left">元</span>
				</div>
			</div>
			<p class="special-remind alertRemind">同种商品无论购买多少件都是这个运费，<span class="font-yellow">0为免运费</span></p>
			
		</div>
		<div class="alert-btnbox boxsizing">
			<a href="javascript:;" class="btn1 radius3">确认</a>
			<a href="javascript:;" class="btn1 radius3 alertCancel">取消</a>
		</div>
	</div>
	<!--运费规则-->
	<script type="text/javascript">
		$(function(){
		    $('.jtIcon-box').on('click',function(event){
		        var fLever = $(this).parent('div').siblings('.Four-Level');
		        var checkBoxs= $(this).parents('.checkBox').siblings();
		        $(this).find('i').toggleClass('jt-iconRotate');
		        $(this).parent('div').toggleClass('check-clickBg');
		        fLever.toggle();
		        checkBoxs.find('.Four-Level').hide();
		        checkBoxs.find('i').removeClass('jt-iconRotate');
		        checkBoxs.find('.A-level').removeClass('check-clickBg');
		    });
		    $('.areaSure-btn').on('click',function(event){
		        if($(this).parents('.Four-Level').css('display') == 'block'){
		            $('.jt-icon').removeClass('jt-iconRotate');
		            $('.A-level').removeClass('check-clickBg');
		            $(this).parents('.Four-Level').hide();
		        }
		    });
			
			
			$('.freRule-click').on('click',function(){
				$('.cover').show();
				$('.freAlert').show();
			});
			$('.freArea-click').on('click',function(){
				$('.cover').show();
				$('.areaAlert').show();
			});
			$('.close-icon').on('click',function(){
				$('.cover').hide();
				$(this).parent('.alert').hide();
			})
		})
	</script>