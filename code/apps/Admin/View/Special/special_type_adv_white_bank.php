<if condition="!$edit">
    <div style="clear:both;height: {$vo.data.height}px;background-color:{$vo.data.color}" v="{$vo.data.line}">
        {$vo.data.adv_content}
    </div>
    <if condition="$vo[data][line] eq 1 ">
        <div style="border-bottom:1px dashed #ddd; margin-top:10px;"></div>
    <else />
    </if>
<else />
    <link href="__PUBLIC__/css/common.css?v=1" rel="stylesheet">
    <link href="__PUBLIC__/css/style.css?v=1" rel="stylesheet">
    <link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
    <link href="__PUBLIC__/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="__PUBLIC__/css/jquery.bigcolorpicker.css" type="text/css" />
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
                        <li>1.在文本框中输入要设定的空白栏的占位高度，选择设置的颜色，是否开启虚线</li>
                        <li>2.点击确认提交按钮进行保存</li>
                    </ol>
                    </div>
            </div>
        </div>
        <!--end提示 -->
            <div class="table-tit" style="width: 98%;margin: 0 1%;">
                <h1 class="tabtit1 left">通栏空白</h1>
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
                            <dt class="left text-r boxsizing">通栏背景色：</dt>
                            <dd class="left text-l">                                
                                <input type="text" name="color" id="bn4" class="com-inp1" placeholder="请输入颜色值" value="{$item_data['color']}" localrequired=""/>
                                <input f="bnn" data-target="bn4" type="button" class="color-btn"/>
                                <p class="remind1"></p>
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing">
                            <dt class="left text-r boxsizing">分割虚线：</dt>
                            <dd class="left text-l">                                
                                <div class="radio i-checks">
                                    <label>
                                        <input type="radio" value="1" name="line" <if condition="$item_data.line eq 1 ">checked="checked" <else /> </if>> <i></i> 启用</label>
                                    <label>
                                        <input type="radio" value="0" name="line" <if condition="$item_data.line eq 0 ">checked="checked" <else /> </if>> <i></i> 禁用 </label>
                                </div>
                                <p class="remind1"></p>
                            </dd>
                        </dl>
                    </div>
                <div class="btnbox3">
                    <button class="btn1 radius3 btn-margin" type="submit">{$Think.lang.submit_btn}</button>
                </div>
                </form>
            </div>
        
    </div>
    <script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
    <script src="__PUBLIC__/js/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/jquery.bigcolorpicker.js"></script>
    <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>
    <script type="text/javascript">
    $(function(){
            $("#f1").bigColorpicker("f1","L",10);
            
            $("#bn").bigColorpicker("f3");

            $("#img").bigColorpicker(function(el,color){
                $(el).css("backgroundColor",color);
            });

            $(":text[name='t']").bigColorpicker(function(el,color){
                $(el).val(color);
            });
            
            $(":input[f='bnn']").bigColorpicker(function(el,color){
                $("#" + $(el).attr("data-target"))
                             .val(color);
            });
            
            $("#f333").bigColorpicker("f3","L",6);
        });
    </script>
</if>