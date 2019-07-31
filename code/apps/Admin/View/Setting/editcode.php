<form action="" method="post" id="code_form">
<script id="desc" name="code" type="text/plain">
{$content}
</script>
<input type="hidden" name="edit" value="1">
<!--<button class="btn btn-primary btn-sm" type="submit">保存源码</button>-->
<button class="btn btn-primary btn-sm" type="button" onclick="buildIndex();">生成首页</button>
</form>
<script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.all.js"></script>
<script type="text/javascript">
    var ue = UE.getEditor('desc',{
        'initialFrameWidth':800,
        'initialFrameHeight':500,
    });
    $(function(){
        $('#code_form').ajaxForm({
            dataType: 'json',
            success: function(response) {
                getResultDialog(response);
            }
        });
    });

    function buildIndex(){
        var html=UE.getEditor('desc').getPlainTxt();
        console.log(html);
        $.post("{:U('Setting/bulidIndexPage')}",{content:html,build:true},function(data){
            if(data.status){
                showSuccess("提示","生成首页成功");
            }else{
                showError("提示","生成首页预览失败");
            }
            return true;
        });

    }
</script>