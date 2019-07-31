
        <!--<div class="tip-remind">收起提示</div>-->
        <div class="tipsbox radius3">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.设置订单免运费额度。</li>
                <li>2.设置运费的计算方式，累加计费或记订单中最大运费的商品的运费。</li>
            </ol>
        </div
        <!--内容开始-->
        <div class="iframeCon">
		<div class="iframeMain">
            <ul class="transverse-nav">
                <li class="activeFour"><a href="javascript:;"><span>运费设置</span></a></li>
            </ul>
            <div class="white-bg ">
                
                <div class="tab-conbox">
                    <form method="post" class="form-horizontal" id="settingForm" name="settingForm" action="<?=U('Order/expense')?>" enctype="multipart/form-data">
                    <input name="form_submit"   type="hidden"  value="submit">
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar"></span>免运费额度：</dt>
                                <dd class="left text-l">
                                    <input type="text" class="com-inp1 radius3 boxsizing" value="{$expense.price}" name="price" id="price">
                                    <p class="remind1">默认为 0，表示不设置免运费额度，大于0表示购买金额超出该值后将免运费。</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar"></span>运费规则：</dt>
                                <dd class="left text-l">
                                    <div class="button-holder" localrequired=''>
                                        <p class="radiobox"><input  type="radio" id="radio-1-2"  value="1" name="secure" class="regular-radio" <if condition="$expense.type eq 1 ">checked="checked" <else /> </if>/><label for="radio-1-2"></label><span class="radio-word">累加计费</span></p>
                                        <p class="radiobox"><input  type="radio" id="radio-1-3"  value="0" name="secure" class="regular-radio" <if condition="$expense.type eq 0 ">checked="checked" <else /> </if>/><label for="radio-1-3"></label><span class="radio-word">只计最大运费</span></p>
                                    </div>
                                    <p class="remind1">累加运费为订单内各类商品运费累计增加计算总运费，只计最大运费为订单内的运费最高单品的运费即为订单总运费。</p>
                                </dd>
                            </dl>
                        </div>
                        <div class="btnbox3 boxsizing">
                            <!-- <input type="submit" class="btn1 radius3 btn3-btnmargin"  value="确认提交"> -->
                            <a type="button" id="message_sub" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
                        </div>
                        </form>
                </div>
            </div>
        </div>  
       </div>  
        <!--内容结束-->
        <script type="text/javascript">
            $('#message_sub').click(function(){
               $('#settingForm').submit();
            });
        </script>
