<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
   <ol class="tips-list">
		<li>{$Think.lang.points_list_tips}</li>
	</ol>
</div>

<!--内容开始-->
<div class="iframeCon">
<div class="iframeMain">
	<ul class="transverse-nav">
		<li class="activeFour"><a href="#"><span>{$Think.lang.points_detail}</span></a></li>
		<li><a  href="{:U('Member/points_setting')}"><span>{$Think.lang.points_setting}</span></a></li>
		<li><a  href="{:U('Member/points_add')}"><span>{$Think.lang.points_add}</span></a></li>
	</ul>
	<div class="white-bg">
		<div class="table-titbox">
			<h1 class="table-tit left boxsizing">{$Think.lang.points_detail_list}</h1>
			
			<form action="<?=U('/member/points')?>" method="get" id='formid'>
			<div class="search-box1 right">
				<div class="search-boxcon boxsizing radius3 left">
					<select class="sele-com1 search-sele boxsizing" name="type" id="search_select">
                		<option {if condition="$_GET['type'] eq 'pl_membername'"} selected {/if} value="pl_membername">{$Think.lang.member_name}</option>
                		<option  {if condition="$_GET['type'] eq 'pl_adminname'"} selected {/if}  value="pl_adminname">{$Think.lang.manager}</option>
                		<option {if condition="$_GET['type'] eq 'pl_stage'"} selected {/if} value="pl_stage">{$Think.lang.points_stage}</option>
                	</select>
					<input type="text" name="search_text" value="{$search_text}" class="search-inp-con boxsizing"/>
				</div>
				<button type="button" class="search-btn right radius3" onclick="javascript:document.getElementById('formid').submit();">{$Think.lang.search}</button>
				
			</div>
			</form>
		</div>
		<div class="comtable-box boxsizing">
			<table class="com-table">
				<thead>
					<tr>
						<th width="90">{$Think.lang.operation}</th>
						<th width="50">{$Think.lang.member_ID}</th>
						<th width="120">{$Think.lang.member_name}</th>
						<th width="100" class="text-l">{$Think.lang.points}</th>
						<th width="150" class="text-l">{$Think.lang.points_stage}</th>
						<th width="120">{$Think.lang.operation_time}</th>
						<th width="120">{$Think.lang.manager}</th>
						<th class="text-l">{$Think.lang.operation_descrip}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<empty name="points_log_list">
                        <tr class="tr-minH">
                            <td colspan="8">暂无数据！</td>
                            <td></td>
                        </tr>
                    <else />
					<foreach name="points_log_list" item="points">
					<tr>
						<td>--</td>
						<td>{$points.pl_memberid}</td>
						<td>{$points.pl_membername}</td>
						<td class="text-l">{$points.pl_points}</td>
						<td class="text-l">{$points_stage[$points['pl_stage']]}</td>
						<td>{$points.pl_addtime|date="Y-m-d H:i:s",###}</td>
						<td>{$points.pl_adminname}</td>
						<td class="text-l"><div class="word-overflow" title="{$points.pl_desc}">{$points.pl_desc}</div></td>
						<td></td>
					</tr>
					</foreach>
					</empty>
				</tbody>
			</table>
		</div>
		{$page}
	</div>
</div>	
</div>	
<!--内容结束-->
