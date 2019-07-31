
        <!--<div class="tip-remind">收起提示</div>-->
        <div class="tipsbox radius3">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.请选择需要开启的快递公司。</li>
            </ol>
        </div
        <!--内容开始-->
        <div class="iframeCon">
		<div class="iframeMain">
            <ul class="transverse-nav">
                <li class="activeFour"><a href="{:U('Order/query_setting')}"><span>快递公司</span></a></li>
            </ul>
            <div class="white-bg ">
                <div class="tab-conbox">
                    <form method="post" class="form-horizontal" id="query_setting2" action="{:U('/Order/query_setting')}?active=1" enctype="multipart/form-data">
                    <input name="form_submit"   type="hidden"  value="submit">
                        <div class="express-tablebox">
                            <table class="express-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="checkbox-holder express-checkbox">
                                                <p class="radiobox checkbox-box"><input type="checkbox" id="radio-1-1" name="radio-1-set" class="regular-radio" /><label for="radio-1-1"></label><span class="radio-word">选择所有</span></p>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php foreach ($companylist as $k => $v) { ?>
                                            <div class="checkbox-holder left express-checkbox">
                                                <p class="radiobox checkbox-box"><input type="checkbox" id="<?php echo $v['code'] ?>" name="companys[<?php echo $v['id'] ?>]" class="regular-radio" <?php if ($v['status'] ==1 ){ ?> checked="checked" <?php }?>/><label for="<?php echo $v['code'] ?>"></label><span class="radio-word"><?php echo $v['name'] ?></span></p>
                                            </div>
                                            <?php } ?>
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="btnbox2 boxsizing">
                            <!-- <input type="submit" class="btn1 radius3 btn2-btnmargin"  value="确认提交"> -->
                            <a type="button" id="query_submit2" class="btn1 radius3 marginT10  btn2-btnmargin">{$Think.lang.submit_btn}</a>

                        </div>
                    </form>
                </div>
            </div>
        </div>  
       </div>  
        <!--内容结束-->

<script type="text/javascript">
    $('#query_submit2').click(function(){
        $('#query_setting2').submit();
    });
</script>

