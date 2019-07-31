<!--内容开始-->
   		<div class="tipsbox radius3">
			<div class="tips boxsizing radius3">
				<div class="tips-titbox">
					<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
					<span class="open-span span-icon"><i class="open-icon"></i></span>
				</div>
			</div>
			<ol class="tips-list" id="tips-list">
				<li>1.扫描下方二维码，前往支付页面，支付金额可自己输入。</li>
			</ol>
		</div>
		<div class="iframeCon">
		<div class="iframeMain">
			<ul class="transverse-nav">
				<li <if condition="$status eq ''">class="activeFour"</if>><a href="<?= U('Cms/otherpay');?>"><span>二维码</span></a></li>
				<li <if condition="$status eq 1">class="activeFour"</if>><a href="<?= U('Cms/otherpay',array('status'=>1));?>"><span>支付列表</span></a></li>
			</ul>
			<if condition="$status eq ''">
			<div class="white-bg ">
				<div  style="margin:20px;">
					<div id="qrcodeCanvas"></div>
					<!-- <img src="__PUBLIC__/admin/images/otherpay.png" alt="" title="扫码支付二维码" > -->
					<h1 class="table-tit boxsizing down left" >右键另存为，下载二维码</h1>
				</div>
				
				
			</div>
			<else/>
			<div class="white-bg ">
				<div class="table-titbox">
					<div class="option" >
						<h1 class="table-tit left boxsizing">支付记录</h1>
						<ul class="operation-list left" >
							<li class="refresh-li"><a href="javascript:window.location.reload();"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
							
						</ul>
					</div>
					<form action="<?=U('/Cms/otherpay',array('status'=>$status))?>" method="get" id='formid'>
					<div class="search-box1 right">
						<div class="search-boxcon boxsizing radius3 left">
							<select class="sele-com1 search-sele boxsizing" name="type" id="search_select">
                        		<option value="order_sn"  <?php if($type =='order_sn'){ ?> selected='selected' <?php }?> >订单编号</option>
                        		<option value="trade_no" <?php if($type =='trade_no'){ ?> selected='selected' <?php }?>>交易流水号</option>
                        		<option value="payment_code" <?php if($type =='payment_code'){ ?> selected='selected' <?php }?>>支付方式</option>
                        	</select>
							<input type="text" name="search_text" value="<?=$search_text?>" class="search-inp-con boxsizing"/>
						</div>
						<button type="button" class="search-btn right radius3" onclick="javascript:document.getElementById('formid').submit();">搜索</button>
						
					</div>
					</form>
				</div>
				<div class="comtable-box boxsizing">
					<table class="com-table">
						<thead>
							<tr>
								<th width="200">订单编号</th>
								<th width="300">交易流水号</th>
								<th class='text-l' width="150">支付方式</th>
								<th class='text-l' width="100">支付金额</th>
								<th width="150">申请时间</th>
								<th width=""></th>
							</tr>
						</thead>
						<tbody>
							<empty name="list">
		                        <tr class="tr-minH">
		                            <td colspan="11">暂无数据！</td>
		                            <td></td>
		                        </tr>
		                    <else />
								<foreach name='list' item='v'>
									<tr>
										<td>{$v['order_sn']}</td>
										<td>{$v['trade_no']}</td>
										<td class='text-l'>{$v['payment_code']}</td>
										<td class='text-l'>{$v['order_amount']}</td>
										<td>{$v['time_text']}</td>
										<td></td>
									</tr>
								</foreach>
							</empty>
						</tbody>
					</table>
				</div>
				{$page}
			</div>
			</if>
		</div>	
	</div>
		<!--内容结束-->
	<script type="text/javascript" src="__PUBLIC__/admin/js/qrcode/utf.js"></script>
	<script type="text/javascript" src="__PUBLIC__/admin/js/qrcode/jquery.qrcode.js"></script>
	<script>
		$(document).ready(function() {
	        $("#qrcodeCanvas").qrcode({
	            render : "canvas",    //设置渲染方式，有table和canvas，使用canvas方式渲染性能相对来说比较好
	            text : "{$qrcode_url}",    //扫描二维码后显示的内容,可以直接填一个网址，扫描二维码后自动跳向该链接
	            width : "200",               //二维码的宽度
	            height : "200",              //二维码的高度
	            background : "#ffffff",       //二维码的后景色
	            foreground : "#000000",        //二维码的前景色
	            src: "{$logo_url}"             //二维码中间的图片
	        });
	        
	    });

	</script>
	