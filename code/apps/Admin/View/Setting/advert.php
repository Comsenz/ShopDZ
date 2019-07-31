
    <meta charset="utf-8">
    <title>广告列表</title>
    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css" />
    <link rel="stylesheet" href="__PUBLIC__/css/bootstrap-datetimepicker.css" />
    <link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
    <link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
    <link href="__PUBLIC__/css/style.css" rel="stylesheet"/>


<!--content开始-->
<div class="content">
    <div class="tablebox1">
        <div class="table-tit">
            <h1 class="tabtit1 left">广告列表</h1>
            <div class="btnbox1 right">
                <a class="btn1 radius3 btn-margin" href="{:U('Adv/add')}">新增广告</a>
            </div>
        </div>
      
        <table class="tablelist1 check-table">
            <tr>
                <th style="width: 20%;">标题</th>
                <th style="width: 20%;">位置</th>
                <th style="width: 20%;">有效期</th>
                <th style="width: 20%;">状态</th>
                <th style="width: 20%;">操作</th>
            </tr>
            <foreach name="advlist" item="ad">
            <tr>
                <td>{$ad.title}</td>
                <td>{$ad.positionstr}</td>
                <td>{$ad.starttime|date="Y-m-d",###} - {$ad.endtime|date="Y-m-d",###}</td>
                <td>

				<div class="td-icon-box">
                    <if condition="$ad.state eq 1 ">
                    	<span class="yes"><i class="fa fa-check-circle"></i>{$ad.statestr}</span> 
                    <else />
                    	<span class="no"><i class="fa fa-ban"></i>{$ad.statestr}</span>
                    </if>
                </div>
                </td>
                <td>
                    <div class="icon-box-com">
                        <div class="icon-box left">
                            <a class="fontface3 fa-edit icon-img" title="编辑" href='javascript:edit("{$ad.id}");'></a>
                        </div>
                        <div class="icon-box left">
                            <a class="fontface3 fa-trash-o icon-img" title="删除" href='javascript:showConfirm("提示","确认删除？",function(isConfirm){if(isConfirm){deladv("{$ad.id}");}else{}});'></a>
                        </div>
                    </div>
                </td>
            </tr>
            </foreach>
        </table>
        {$page}
    </div>
</div>
<!--content结束-->
<script>
    function deladv(aid){
        $.post("{:U('Adv/delAdv')}",{"aid":aid},function(data){
            getResultDialog(data);
        });
    }

    function edit(aid){
        window.location.href = "{:U('Adv/edit')}"+'?aid='+aid;
    }
</script>