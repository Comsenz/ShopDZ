
        <!--<div class="tip-remind">收起提示</div>-->
        <div class="tipsbox radius3">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.请填写快递查询接口申请的id和key 我们集成的是快递鸟，官网<a target="_blank" href="http://www.kdniao.com/">http://www.kdniao.com/</a>。</li>
                <li>2.申请成功之后联系快递鸟的客服把订阅接收通知的接口地址配置成 http://您商城后台访问域名/index.php/Notify/kuaidiniao。</li>
            </ol>
        </div
        <!--内容开始-->
        <div class="iframeCon">
            <ul class="transverse-nav">
                <li class="activeFour"><a href="javascript:;"><span>快递设置</span></a></li>
                <li><a href="javascript:;"><span>快递公司</span></a></li>
            </ul>
            <div class="white-bg ">
                
                <div class="tab-conbox">
                    <form method="post" class="form-horizontal" name="settingForm" action="{:U('Admin/Logistics/query_setting')}" enctype="multipart/form-data">
                    <input name="form_submit"   type="hidden"  value="submit">
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>API_ID：</dt>
                                <dd class="left text-l">
                                    <input type="text" class="com-inp1 radius3 boxsizing" value="{$config.express_query_id}" name="express_query_id" id="express_query_id" required="">
                                    <p class="remind1">快递鸟登录之后个人中心中显示的商户id</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>API_KEY：</dt>
                                <dd class="left text-l">
                                    <input type="text" class="com-inp1 radius3 boxsizing" value="{$config.express_query_key}" name="express_query_key" id="express_query_key" required=""> 
                                    <p class="remind1">快递鸟登录之后个人中心中显示的商户API_KEY</p>
                                </dd>
                            </dl>
                        </div>
                        <div class="btnbox3 boxsizing">
                            <input type="submit" id="query_submit" class="btn1 radius3 btn2-btnmargin"  value="{$Think.lang.submit_btn}">
                        </div>
                    </form>
                </div>
                <div class="tab-conbox none">
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
        </div>  
        <!--内容结束-->

<script type="text/javascript">
    $('#query_submit').click(function(){
        flag=checkrequire('query_setting');
        if(!flag){
            $('#query_setting').submit();
        }
    });
</script>

