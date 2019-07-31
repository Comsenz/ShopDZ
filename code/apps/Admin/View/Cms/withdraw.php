<!--内容开始-->
   		<div class="tipsbox radius3">
			<div class="tips boxsizing radius3">
				<div class="tips-titbox">
					<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
					<span class="open-span span-icon"><i class="open-icon"></i></span>
				</div>
			</div>
			<ol class="tips-list" id="tips-list">
				<li>1.提现设置后将会在用户提取现金时显示。</li>
			</ol>
		</div>
		<div class="iframeCon">
		<div class="iframeMain">
			<ul class="transverse-nav">
				<li  <?php if($status ==''){ ?> class="activeFour" <?php }?> ><a href="<?=U('Cms/withdraw');?>"><span>全部</span></a></li>
				<li <?php if($status ==1){ ?> class="activeFour" <?php }?> ><a href="<?=U('Cms/withdraw',array('status'=>1));?>"><span>待审核</span></a></li>
				<li <?php if($status ==3){ ?> class="activeFour" <?php }?>><a  href="<?= U('Cms/withdraw',array('status'=>3));?>"><span>已同意</span></a></li>
				<li <?php if($status ==2){ ?> class="activeFour" <?php }?>><a   href="<?= U('Cms/withdraw',array('status'=>2));?>"><span>已拒绝</span></a></li>
			</ul>
			<div class="white-bg ">
				<div class="table-titbox">
					<div class="option" >
						<h1 class="table-tit left boxsizing">提现记录</h1>
						<ul class="operation-list left" >
							<li class="refresh-li"><a href="javascript:window.location.reload();"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
							<li class="export-li"><a href="{:U('Admin/Export/withdraw',$_GET)}"><span><i href="#" class="operation-icon export-icon"></i></span></a></li>
						</ul>
					</div>
					<form action="<?=U('/Cms/withdraw',array('status'=>$status))?>" method="get" id='formid'>
					<div class="search-box1 right">
						<div class="search-boxcon boxsizing radius3 left">
							<select class="sele-com1 search-sele boxsizing" name="type" id="search_select">
                        		<option value="cash_sn"  <?php if($type =='cash_sn'){ ?> selected='selected' <?php }?> >申请编号</option>
                        		<option value="member_name" <?php if($type =='member_name'){ ?> selected='selected' <?php }?>>会员账号</option>
                        		<option value="member_uid" <?php if($type =='member_uid'){ ?> selected='selected' <?php }?>>会员ID</option>
                        		<option value="user_name" <?php if($type =='user_name'){ ?> selected='selected' <?php }?>>管理员</option>
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
								<th width="90">操作</th>
								<th width="100">申请编号</th>
								<th width="100">会员ID</th>
								<th width="150">会员账号</th>
								<th class='text-l' width="100">提现金额</th>
								<th width="150">申请时间</th>
								<th class='text-l' width="100">收款银行</th>
								<th class='text-l' width="180">收款账号</th>
								<th class='text-l' width="100">开户姓名</th>
								<th class='text-l' width="90">管理员</th>
								<th width="150">操作时间</th>
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
							<?php foreach($list as $k => $v) {?>
							<tr>
								<td>
									<div class="table-iconbox">
										<?php if($status  == 1 || $status == 4) {?>
											<div class="edit-iconbox left edit-sele marginR10">
												<a class="edit-word table-icon-a" href="<?=U('/Cms/edit',array('id'=>$v['cash_id'],'status'=>$status)) ?>"><i class="table-com-icon table-handle-icon"></i><span class="gap">待审核</span></a>
											</div>
										<?php }else if(in_array($status,array(2,3))){?>
											<div class="edit-iconbox left edit-sele marginR10">
												<a class="edit-word table-icon-a" href="<?=U('/Cms/show',array('id'=>$v['cash_id'],'status'=>$status)) ?>"><i class="table-com-icon table-look-icon"></i><span class="gap">查看</span></a>
											</div>
										<?php }else{?>
											<?php if($v['status'] == 1 || $v['status'] == 4) {?>
											<div class="edit-iconbox left edit-sele marginR10">
												<a class="edit-word table-icon-a" href="<?=U('/Cms/edit',array('id'=>$v['cash_id'],'status'=>$status)) ?>"><i class="table-com-icon table-handle-icon"></i><span class="gap">待审核</span></a>
											</div>
											<?php }else{?>
												<div class="edit-iconbox left edit-sele marginR10">
													<a class="edit-word table-icon-a" href="<?=U('/Cms/show',array('id'=>$v['cash_id'],'status'=>$status)) ?>"><i class="table-com-icon table-look-icon"></i><span class="gap">查看</span></a>
											</div>
											<?php }?>
										<?php }?>
									</div>
								</td>
								<td><?=$v['cash_sn']?></td>
								<td><?=$v['member_uid']?></td>
								<td><?=$v['member_name']?></td>
								<td class='text-l'><?=$v['cash_amount']?></td>
								<td><?=$v['add_time_text']?></td>
								<td class='text-l'><?=$v['bank']?></td>
								<td class='text-l'><?=$v['bank_no']?></td>
								<td class='text-l'><?=$v['bank_name']?></td>
								<td class='text-l'><?=$v['user_name']?></td>
								<td><?=$v['enddate_text']?></td>
								<td></td>

							</tr>
							<?php }?>
							</empty>
						</tbody>
					</table>
					
				</div>
				{$page}
			</div>
		</div>	
	</div>	
		<!--内容结束-->
		