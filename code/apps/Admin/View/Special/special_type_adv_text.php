<if condition="!$edit">
    <div style="clear:both;height: {$vo.data.height}px;text-align: center;line-height: 30px;">
        {$vo.data.adv_content}
    </div>
<else />
<link href="__PUBLIC__/css/common.css?v=1" rel="stylesheet">
<link href="__PUBLIC__/css/style.css?v=1" rel="stylesheet">
<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
<style type="text/css">
	.ibox-content{
		padding: 0;
		border: none;
	}
</style>
<div class="content" id="listsdiv"><!--这个是大框-->
    <!--提示-->
    <div class="alertbox1">
        <div class="alert-con radius3">
            <button class="closebtn1">×</button>
            <div class="alert-con-div">
                <h1 class="fontface alert-tit1">&nbsp;{$Think.lang.operation_tips}</h1>
                <ol style="list-style-type:none;">
                    <li>1.在文本框中输入要设定的文字栏的占位高度</li>
                    <li>2.在文本框中输入要设定的文字栏的文字内容</li>
                    <li>3.操作完成后点击确认提交按钮进行保存</li>
                </ol>
                </div>
        </div>
    </div>
   
        <div class="table-tit" style="width: 98%;margin: 0 1%;">
            <h1 class="tabtit1 left">通栏文字广告</h1>
            <div class="btnbox1 right">
                <a class="btn1 radius3 btn-margin" href="{:U('setting/personnel')}">返回上页</a>
            </div>
        </div>
        <div class="ibox-content">
            <form method="post" class="form-horizontal" id="dataform" action="{:U('itemSave')}">
                <input type="hidden" name="item_id" id="item_id"  value="{$edit}">
                <input type="hidden" name="item_type" id="item_type"  value="{$item_type}">
                <div class="jurisdiction" style="padding: 0;">
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing">通栏高度：</dt>
                        <dd class="left text-l">
                            <input type="text" name="height" class="com-inp1 radius3 boxsizing" placeholder="请输入数字" value="{$item_data['height']}" localrequired="">
                            <p class="remind1">高度单位（px）</p>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing">通栏内容：</dt>
                        <dd class="left text-l">
                            <textarea type="text" name="adv_content" style="height: 220px;" class="com-textarea1 radius3 boxsizing"  placeholder="请输入文本" localrequired="">{$item_data['adv_content']}</textarea>
                            <p class="remind1">支持HTML</p>
                        </dd>
                    </dl>
                </div>
                <div class="btnbox3">
                    <button class="btn1 radius3 btn-margin" type="submit">{$Think.lang.submit_btn}</button>
                </div>
            </form>
        </div>
 
<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
</if>