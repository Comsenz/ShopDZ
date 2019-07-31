
        <!--<div class="tip-remind">收起提示</div>-->
        <div class="tipsbox">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.提交后，相同支付单号的未支付订单状态都变为已支付状态</li>
            </ol>
        </div>
        <!--内容开始-->
        <div class="iframeCon">
		<div class="iframeMain">
            <div class="white-shadow2">
                
                <div class="details-box">
                    <h1 class="details-tit">订单详细</h1>
                    <form id="signupForm" role="form" action="<?=U('/Order/settrade')?>"  method ="post" >
                    <input name="id" type="hidden" value="<?=$data['order_id'] ?>" >
                    <input  type="hidden" name="form_submit" value="submit" >
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing details-dl">
                                <dt class="left text-r boxsizing">订单号：</dt>
                                <dd class="left text-l">
                                    <?=$data['order_id']?>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing details-dl">
                                <dt class="left text-r boxsizing">支付单号：</dt>
                                <dd class="left text-l">
                                    <?=$data['order_sn']?>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing details-dl">
                                <dt class="left text-r boxsizing">订单金额：</dt>
                                <dd class="left text-l">
                                    <?=$data['order_amount']?>
                                </dd>
                            </dl>
                            </div>
                            <h1 class="details-tit">设置收款</h1>
                            <div class="jurisdiction boxsizing">
                                <dl class="juris-dl boxsizing">
                                    <dt class="left text-r boxsizing"><span class="redstar">*</span>付款时间：</dt>
                                    <dd class="left text-l ">
                                        <div class="left" style="width:100%;">
                                            <p class="time-box" style="margin-left: 0;"><input type="text" id="pay_time" name="pay_time" readonly="" value="<?php echo @date('Y-m-d',$data['payment_time']); ?>" localrequired="" class="com-inp1 radius3 boxsizing" /><i class="timeinp-icon"></i></p>
                                        </div>
                                        <p class="remind1">请确认付款时间</p>
                                    </dd>
                                </dl>
                                <dl class="juris-dl boxsizing">
                                    <dt class="left text-r boxsizing"><span class="redstar">*</span>付款方式：</dt>
                                    <dd class="left text-l">
                                        <div class="search-boxcon boxsizing radius3 inline borderR-none">
                                            <select id="field"  name="pay_code"  class="com-sele radius3 juris-dl-sele">
                                                <option value=0>默认</option>
                                                <?php foreach($paylist as $rk=>$rv) {?>
                                                <option value=<?=$rv['payment_code']?> <?php if($data['payment_code'] == $rv['payment_code']) {?> selected <?php } ?>  > <?=$rv['payment_name']?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <p class="remind1">请选择支付方式</p>
                                    </dd>
                                </dl>
                                <dl class="juris-dl boxsizing">
                                    <dt class="left text-r boxsizing"><span class="redstar">*</span>支付交易号：</dt>
                                    <dd class="left text-l">
                                        <input type="text" <?php if (empty($info['username'])) {?> localrequired="" <?php }?>   name="trade_no"  class="com-inp1 radius3 boxsizing" value="<?=$data['trade_no']?>" />
                                        <p class="remind1">请输入第三方支付平台交易号</p>
                                    </dd>
                                </dl>
                            </div>                            
                        
                        <div class="btnbox3 boxsizing">
                            <!-- <input type="submit" id="settrade"  value="{$Think.lang.submit_btn}" class="btn1 radius3 btn3-btnmargin"/> -->
                            <a class="btn1 radius3 btn3-btnmargin" id="settrade" href="javascript:;">{$Think.lang.submit_btn}</a>
                        </div>
                        </form>
                </div>
            </div>
        </div>  
       </div>  
        <!--内容结束-->

<script type="text/javascript">
    $( "#pay_time" ).datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel:true,
        dateFormat: 'yy-mm-dd',
        showAnim:"fadeIn",//淡入效果
    });
    $('#settrade').click(function(){
        flag=checkrequire('signupForm');
        if(!flag){
            $('#signupForm').submit();
        }
    });
</script>

