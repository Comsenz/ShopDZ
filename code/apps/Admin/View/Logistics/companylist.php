
        <div class="tipsbox radius3">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.选择开启物流公司，点击确认提交进行开启。</li>
            </ol>
        </div>
        <div class="iframeCon">
            <ul class="transverse-nav">
                <li class="activeFour"><a href="javascript:;"><span>关于快递</span></a></li>
            </ul>
            <div class="white-bg">
                <form method="post" class="form-horizontal" action="{:U('/Logistics/companyList')}" enctype="multipart/form-data">
                <input name="form_submit"   type="hidden"  value="submit">
                    <div class="express-tablebox">
                        <table class="express-table">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="button-holder express-checkbox">
                                            <p class="radiobox checkbox-box"><input type="checkbox" id="radio-1-1" name="radio-1-set" class="regular-radio" /><label for="radio-1-1"></label><span class="radio-word">选择所有</span></p>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php foreach ($companylist as $k => $v) { ?>
                                        <div class="button-holder left express-checkbox">
                                            <p class="radiobox checkbox-box"><input type="checkbox" id="<?php echo $v['code'] ?>" name="companys[<?php echo $v['id'] ?>]" class="regular-radio" <?php if ($v['status'] ==1 ){ ?> checked="checked" <?php }?>/><label for="<?php echo $v['code'] ?>"></label><span class="radio-word"><?php echo $v['name'] ?></span></p>
                                        </div>
                                        <?php } ?>
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="btnbox2 boxsizing">
                        <input type="submit" class="btn1 radius3 btn2-btnmargin"  value="{$Think.lang.submit_btn}">
                    </div>
                </form>
            </div>
        </div>
