<!--<div class="tip-remind">收起提示</div>-->
<div class="tipsbox">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		<li>1.应用中心插件列表页。</li>
	</ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
    <div class="white-bg">
        <div class="table-titbox">
            <div class="option">
                <h1 class="table-tit left boxsizing">插件列表</h1>
                <ul class="operation-list left">
                    <li class="refresh-li" onclick="location.reload();"><a href="#"><span><i href="#" class="operation-icon refresh-icon"></i></span></a></li>
                </ul>
            </div>            
        </div>
        
        <div class="comtable-box boxsizing">
            <table class="com-table">
                <thead>
                    <tr>
						<th width="180">操作</th>
                        <th width="50">Icon</th>
                        <th width="150">插件名称</th>
						<th width="110">插件版本</th>
						<th width="210">插件描述</th>
                        <th width="10"></th>
                    </tr>
                </thead>
                <tbody>
                	<empty name="function">
						<tr class="tr-minH">
                            <td colspan="5">暂无数据！</td>
                            <td></td>
                        </tr>
					<else />
                    <foreach name="function" item="vo">
                    <tr>
						<td>
						<if condition="$vo.status != 1">
							<a href=" {:U('Plugin/install',array('install'=>1,'type'=>'function','code'=>$vo['code']))}">安装</a>
						<else />
							<a href=" {:U('Plugin/admin',array('module'=>$vo['code'],'controller'=>'admin','method'=>$vo['code']))}">管理</a>
						</if>
						</td>
                        <td>
						<if condition="$vo.status != 1">
							<a href=" {:U('Plugin/install',array('install'=>1,'type'=>'function','code'=>$vo['code']))}" class="tablehref"> <img class="tablehref-img" src="/plugins/{$vo.code}/{$vo.icon}"></a>
						<else />
							<a href=" {:U('Plugin/admin',array('module'=>$vo['code'],'controller'=>'admin','method'=>$vo['code']))}" class="tablehref"> <img class="tablehref-img" src="/plugins/{$vo.code}/{$vo.icon}"></a>
						</if>
                        </td>
                        <td>{$vo.name}</td>
						<td>{$vo.version}</td>
						<td>{$vo.desc}</td>						
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