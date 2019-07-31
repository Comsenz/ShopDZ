
        <div class="tipsbox radius3">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.清除数据缓存。</li>
                <li>2.什么都不选择就代表全选。</li>
            </ol>
        </div>
        <div class="iframeCon">
		<div class="iframeMain">
			<ul class="transverse-nav">
                <li class="activeFour"><a href="javascript:;"><span>清理缓存</span></a></li>
            </ul>
            <div class="white-bg">
                <form method="post" class="form-horizontal" id="clean_form" name="settingForm" action="{:U('Admin/Setting/clean_cache')}" enctype="multipart/form-data">
                    <div class="express-tablebox">
                        <table class="express-table">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox-holder express-checkbox">
                                            <p class="radiobox checkbox-box"><input type="checkbox" id="radio-1-1" name="radio-1-set" class="regular-radio" checked="checked"/><label for="radio-1-1"></label><span class="radio-word">选择所有</span></p>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="checkbox-holder left express-checkbox">
                                            <p class="radiobox checkbox-box"><input type="checkbox" value="get_special_item" name="cleans[]" id="radio-1-2" class="regular-radio"><label for="radio-1-2"></label><span class="radio-word">商城首页</span></p>
                                        </div>
                                        <div class="checkbox-holder left express-checkbox">
                                            <p class="radiobox checkbox-box"><input type="checkbox" value="web_setting" name="cleans[]" id="radio-1-3" class="regular-radio"><label for="radio-1-3"></label><span class="radio-word">商城配置</span></p>
                                        </div>
                                        <div class="checkbox-holder left express-checkbox">
                                            <p class="radiobox checkbox-box"><input type="checkbox" value="get_all_category" name="cleans[]" id="radio-1-4" class="regular-radio"><label for="radio-1-4"></label><span class="radio-word">商品分类</span></p>
                                        </div>
                                        <div class="checkbox-holder left express-checkbox">
                                            <p class="radiobox checkbox-box"><input type="checkbox" value="spu" name="cleans[]" id="radio-1-5" class="regular-radio"><label for="radio-1-5"></label><span class="radio-word">商品详情</span></p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="btnbox2 boxsizing">
                        <!-- <input type="submit" class="btn1 radius3 btn2-btnmargin"  value="确认提交"> -->
                        <a type="button" id="clern_cache" class="btn1 radius3 marginT10 btn2-btnmargin">{$Think.lang.submit_btn}</a>
                    </div>
                </form>
            </div>
        </div>
        </div>
<script type="text/javascript">
    $('#clern_cache').click(function(){
       $('#clean_form').submit();
    });
</script>
