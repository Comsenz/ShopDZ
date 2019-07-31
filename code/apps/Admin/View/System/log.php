<div class="tipsbox">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
	    {$Think.lang.operation_log_tips}
	</ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
	<div class="white-bg">
		<div class="table-titbox">
			<h1 class="table-tit left boxsizing">{$Think.lang.operation_log}</h1>
			<ul class="operation-list left">
				<li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
				<li class="dele-li del" onclick="del('ids',this)" href="javascript:;" rel-href="<?=U('/system/log/type/ids')?>" ><a href="#"><span><i href="#" class="operation-icon op-dele-icon"></i></span></a></li>
				<li class="dele-li-ago del" onclick="del('createtime',this)" rel-href="<?=U('/system/log/type/createtime')?>"><a href="#"><span><i href="#" class="operation-icon op-dele-icon-half"></i></span></a></li>
			</ul>
			<div class="search-box1 right">
			<form action="<?=U('/system/log')?>" method="get" id='formid'>
				<!-- <div class="form-group left time2">
	            	<label class="font-noraml left time-label">时间范围</label>
	                <div class='input-group date left time-width' data-date="2012-02-20" data-date-format="yyyy-mm-dd">
	                    <input type='text' class="form-control datetimepicker2"/>
	                    <input type="text" class="form-control com-inp1 datetimepicker2 time-inp1" name="start" value="<?=$_GET['start']?>" />
                        <span class="left timeto">--</span>
                        <input type="text" class="form-control com-inp1 datetimepicker3 time-inp1" name="end" value="<?=$_GET['end']?>" />
	                    
	                </div>
	            </div> -->
				<div class="search-boxcon boxsizing radius3 left">
                	<select name="type" class="sele-com1 search-sele boxsizing" id="search_select">
                		<option <?php if($_GET['type'] == 'username') { ?> selected <?php }?> value="username">{$Think.lang.operator}</option>
                		<option <?php if($_GET['type'] == 'action') { ?> selected <?php }?>  value="action">{$Think.lang.operation_log_stage}</option>
                		<option  <?php if($_GET['type'] == 'ip') { ?> selected <?php }?> value="ip">IP</option>
                	</select>
					<input type="text" name="search_text"  value="<?=$_GET['search_text']; ?>" class="search-inp-con boxsizing"/>
				</div>
				<input type="submit"  value="{$Think.lang.search}" class="search-btn right radius3"/>
			</form>
			</div>
		</div>
		
		<div class="comtable-box boxsizing">
			<table class="com-table">
				<thead>
					<tr>
						<th width="100" class="text-l">
							<div class="button-holder">
								<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-1" name="radio-1-set" class="regular-radio" /><label for="radio-1-1"></label><span class="radio-word">{$Think.lang.select_all}</span></p>
							</div>
						</th>
						<th width="90">{$Think.lang.operation}</th>
						<th class='text-l' width="120">{$Think.lang.operator}</th>
						<th  class='text-l' width="200">{$Think.lang.operation_log_stage}</th>
						<th  class='text-l' width="200">{$Think.lang.operation_content}</th>
						<th width="180">{$Think.lang.operation_time}</th>
						<th width="120">IP</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<empty name="lists">
	                    <tr class="tr-minH">
	                        <td colspan="7">{$Think.lang.no_data}</td>
	                        <td></td>
	                    </tr>
	                <else />
					<?php foreach( $lists as $k => $v)  { ?>
					<tr>
						<td class="text-l">
							<div class="button-holder">
								<p class="radiobox table-radioMar"><input type="checkbox" id="radio-1-{$k+2}" name="subCheck[]" class="regular-radio" value="<?=$v['id']?>" /><label for="radio-1-{$k+2}"></label><span class="radio-word"></span></p>
							</div>
						</td>
						<td>
							<div class="table-iconbox">
								<div class="edit-iconbox left edit-sele marginR10">
									<a class="edit-word table-icon-a btn-del" rel-href ="<?=U('/system/log/type/ids/ids/'.$v['id'])?>"><i class="table-com-icon table-dele-icon"></i><span class="gap">{$Think.lang.delete}</span></a>
								</div>
							</div>
						</td>
						<td  class='text-l'><?=$v['username']?></td>
		                <td  class='text-l'><?=$v['action']?></td>
		                <td  class='text-l'><div class="td-word"><?=$v['content']?></div></td>
						<td><?=date('Y-m-d H:i:s',$v['createtime'])?></td>
						<td><?=$v['ip']?></td>
						<td></td>
					</tr>
					<?php }?>
					</empty>
				</tbody>
			</table>
			<?=$page;?>
		</div>
		
	</div>
</div>
</div>
<script type="text/javascript">
	$(function(){
		//添加会员提示
		$('.dele-li-ago').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.dele-li-ago'),e,'删除半年前');
		})
		$('.dele-li-ago').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
		$('.refresh-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.refresh-li'),e,'{$Think.lang.refresh}');
		})
		$('.refresh-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
		$('.td-word').bind(
			{
				mousemove:function(){
					e=arguments.callee.caller.arguments[0] || window.event; 
					tip = $(this).html();
					remindNeed($(this),e,tip);
				},
				mouseout:function() {
					$('.tip-remind').remove();
				}
			}
		);
		$('.export-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
	    $('.dele-li').mousemove(function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.dele-li'),e,'批量删除');
		})
		$('.dele-li').mouseout(function(){
	     	$('.tip-remind').remove();
	    });
			
	})
	$('.btn-del').click(function() {
		url = $(this).attr('rel-href');
        showConfirm("确认要删除么？",function(){
            $.get(url,{checksubmit:'yes'},function(data){
                if(data.status == 1){
                   showSuccess(data.info,function(){
                        window.location.reload();
                  });
                }else{
                    showError(data.info);
                }
            },'json')
        });
	});

	 
	function del(type,myself) {
		var ids = new Array();
		url = $(myself).attr('rel-href');
		$(".regular-radio").each(function(index){
			if($(this).prop("checked"))
			ids.unshift($(this).val())
		});
        showConfirm("确认要删除么？",function(){
            $.post(url,{checksubmit:'yes',ids:ids},function(data){
                if(data.status == 1){
                   showSuccess(data.info,function(){
                        window.location.reload();
                  });
                }else{
                    showError(data.info);
                }
            },'json')
        }); 
	}
</script>
