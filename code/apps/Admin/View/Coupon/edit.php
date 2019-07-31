
<div class="tipsbox">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
    <ol class="tips-list" id="tips-list">
        <li>1.平台优惠券添加。</li>
        <li>2.带有红色“<span class="redstar">*</span>”为必填项。</li>
        <li>3.部分字段要求为数字类型，请认真填写。</li>
        <li>4.微信优惠券订单金额不能修改。</li>
        <li>5.已发布商城优惠券不能修改成微信卡券。</li>
    </ol>
</div>
<div class="iframeCon">
    <ul class="transverse-nav">
        <li class="activeFour"><a  href="javascript:;"><span>修改优惠券</span></a></li>
    </ul>
    <div class="white-bg text-c">
        <div class="coupon-con">
            <div class="coupon-left">
                <div class="phoneH"><h2 class="phone-tit">优惠券</h2></div>


                <div class="coupon-box sc-coupon" <?php if($info['rpacket_t_wx'] == 1){ ?>style="display:none"<?php }?>>
                    <div class="coupon-box-det sc-coupon-det">
                        <div class="coupon-box-top">
                            <div class="coupon-top-con">
                                <div class="zoom">
                                    <p class="coupon-subtit left change-title" change-default="">{$info.rpacket_t_title}</p>
                                    <p class="count-limit right">每人限领<span class="change-eachlimit" change-default="xx">{$info.rpacket_t_eachlimit}</span>张</p>
                                </div>
                                <div class="zoom couponMoney-box">
                                    <div class="left">
                                        <span class="money-unit">¥</span>
                                        <span class="money-num change-price"  change-default="xxx" >{$info.rpacket_t_price}</span>
                                    </div>
                                    <div class="right">
                                        <p class="coupon-share ">
                                            <span class="change-points coupon-red" change-default="xxx积分兑换">{$info.rpacket_t_points}</span>积分兑换
                                        <p>
                                            <p class="coupon-points-none  coupon-share" style="display: none">免费领取</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="coupon-box-bottom">
                            <p class="coupon-bottom-tit">订单满<span class="change-limit coupon-bottom-tit" change-default="xxx">{$info.rpacket_t_limit}</span>元（含运费）</p>
                            <p class="coupon-remind2">有效期：<span class="change-start coupon-remind2" change-default="20xx-xx-xx"><?php echo $info['rpacket_t_start_date'] ? @date('Y-m-d',$info['rpacket_t_start_date']) : '';?></span>-<span class="change-end coupon-remind2" change-default="20xx-xx-xx"><?php echo $info['rpacket_t_end_date'] ?  @date('Y-m-d',$info['rpacket_t_end_date']) : '';?></span></p>
                        </div>
                        <p class="coupon-word"><?php echo $site_name; ?>优惠券</p>
                    </div>
                    <span class="left-circles coupon-circles"></span>
                    <span class="right-circles coupon-circles"></span>
                </div>


                <div class="coupon-box wx-coupon" <?php if($info['rpacket_t_wx'] == 0){ ?>style="display:none"<?php }?>>
                    <div class="coupon-box-det wx-coupon-det">
                        <div class="coupon-box-top coupon-box-top_wx">
                            <div class="coupon-top-con">
                                <div class="zoom">
                                    <p class=" coupon-subtit left change-title" change-default="">{$info.rpacket_t_title}</p>

                                    <p class="count-limit right">每人限领<span class="change-eachlimit" change-default="xx">{$info.rpacket_t_eachlimit}</span>张</p>
                                </div>
                                <div class="zoom couponMoney-box">
                                    <div class="left">
                                        <span class="money-unit">¥</span>
                                        <span class="money-num change-price"  change-default="xxx">{$info.rpacket_t_price}</span>
                                    </div>
                                    <div class="right">
                                    <img src="__PUBLIC__/admin/images/wx_statu.png" alt="" class="coupon-statu"/>
                                        <!-- <input type="button" value="分享" class="coupon-share"/> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="coupon-box-bottom">
                            <p class="coupon-bottom-tit">订单满<span class="change-limit coupon-bottom-tit" change-default="xxx">{$info.rpacket_t_limit}</span>元（含运费）</p>
                            <p class="coupon-remind2">有效期：<span class="change-start coupon-remind2" change-default="20xx-xx-xx"><?php echo $info['rpacket_t_start_date'] ? @date('Y-m-d',$info['rpacket_t_start_date']) : '';?></span> - <span class="change-end coupon-remind2" change-default="20xx-xx-xx"><?php echo $info['rpacket_t_end_date'] ? @date('Y-m-d',$info['rpacket_t_end_date']): '';?></span></p>
                        </div>
                        <p class="coupon-word"><?php echo $site_name; ?>优惠券</p>
                    </div>
                    <span class="left-circles coupon-circles"></span>
                    <span class="right-circles coupon-circles"></span>
                </div>
            </div>


            <div class="coupon-right" >
                <div class="couponR-con">
                    <div class="coupon-edit-box">
                        <form method="post" class="form-horizontal" name="redpacke_form" id="redpacke_form" action="{:U('Coupon/edit')}">
                            <input type="hidden" name='form_submit' value="submit" />
                            <h1 class="soupon-edit-tit">优惠券基础信息</h1>
                            <table class="coupon-table">
                                <tr>
                                    <td width="100"><span class="redstar">*</span>优惠券名称</td>
                                    <td><input type="text" class="coupon-inp changenow" change-key="title" localrequired="empty;limit:50" name="rpacket_t_title"  value="{$info.rpacket_t_title}" id="rpacket_t_title" />
                                        <span class="tips-tag" tips="优惠券名称不能大于50个字符"><i class="tips-icon"></i></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="redstar">*</span>发放总量</td>
                                    <td>
                                        <div class="release-price coupon-table-num">
                                            <input type="text" class="price-inp left" localrequired="empty;intval" name="rpacket_t_total"  value="{$info.rpacket_t_total}" id="rpacket_t_total" />
                                            <input type="hidden" class="price-inp left"  name="id"  value="{$info.rpacket_t_id}"  />
                                            <span class="price-unit left">张</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="redstar">*</span>面值</td>
                                    <td>
                                        <div class="release-price coupon-table-num">
                                            <input type="text" class="price-inp left changenow" change-key="price" localrequired="empty;intval" name="rpacket_t_price"  value="{$info.rpacket_t_price}" id="rpacket_t_price" />
                                            <span class="price-unit left">元</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="redstar">*</span>订单金额满</td>
                                    <td>
                                        <div class="coupon-full">
                                            <div class="release-price coupon-table-num left full-num">
                                                <input type="text" class="price-inp left changenow" change-key="limit" localrequired="intval;empty;gt:rpacket_t_price" name="rpacket_t_limit"  value="{$info.rpacket_t_limit}" id="rpacket_t_limit" <?php if($info['rpacket_t_wx'] == 1){ ?>readonly="true" <?php }?>/>
                                                <span class="price-unit left">元</span>
                                            </div>
                                            <span class="left">可使用</span>
                                            <span class="tips-tag" tips="必须大于优惠券面值"><i class="tips-icon"></i></span>
                                        </div>
                                    </td>
                                </tr>

                                <tr <?php if($info['rpacket_t_wx'] == 0){ ?>style="display: none"<?php }?>>
                                    <td class="alignT2 relativeT5">微信卡券</td>
                                    <td>
                                        <div class="button-holder coupon-holder marginT5">
                                            <p class="radiobox"><input type="radio" id="radio-1-2" data-type="rpacket_t_wx" name="rpacket_t_wx" value="1" class="regular-radio one"  <?php if($info['rpacket_t_wx'] == 1){ ?>checked="checked" <?php } ?> disabled="true" ><label for="radio-1-5"></label><span class="radio-word black-font">启用</span></p>
                                        </div>
                                        <p class="coupon-remind">需要先开通微信公众号卡券权限，同步至微信卡包后，需等待微信审核通过，才能领取。</p>
                                    </td>
                                </tr>
                                <tr id="wxcolor" <?php if($info['rpacket_t_wx'] != 1){ ?>style="display: none" <?php }?>>
                                    <td><span class="redstar">*</span>卡券颜色</td>
                                    <td class="posiR">
                                        <p class="color-click"><span class="color-span"></span><span class="color-click-span"><i class="color-icon"></i></span></p>
                                        <div class="color-show-box">
                                            <input type="hidden" id="rpacket_t_color" name="rpacket_t_color" value="Color010">
                                            <ul class="color-show-list">
                                                <li class="Color010 pointer" name="Color010" value="#63b359"></li>
                                                <li class="Color020 pointer" name="Color020" value="#2c9f67"></li>
                                                <li class="Color030 pointer" name="Color030" value="#509fc9"></li>
                                                <li class="Color040 pointer" name="Color040" value="#5885cf"></li>
                                                <li class="Color050 pointer" name="Color050" value="#9062c0"></li>
                                                <li class="Color060 pointer" name="Color060" value="#d09a45"></li>
                                                <li class="Color070 pointer" name="Color070" value="#e4b138"></li>
                                                <li class="Color080 pointer" name="Color080" value="#ee903c"></li>
                                                <li class="Color090 pointer" name="Color090" value="#dd6549"></li>
                                                <li class="Color100 pointer" name="Color100" value="#cc463d"></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <h1 class="soupon-edit-tit">基本规则</h1>
                            <table class="coupon-table">
                                <tr>
                                    <td width="100"><span class="redstar">*</span>每人限领</td>
                                    <td>
                                        <input type="text" class="coupon-inp changenow" localrequired="intval;empty;lt:50" change-key="eachlimit" name="rpacket_t_eachlimit"  value="{$info.rpacket_t_eachlimit}" id="rpacket_t_eachlimit" /><span class="tips-tag" tips="最多50张"><i class="tips-icon"></i></span>

                                    </td>
                                </tr>

                                <tr>
                                    <td><span class="redstar">*</span>生效时间</td>
                                    <td>
                                        <p class="time-box coupon-time-box"><input type="text" localrequired="empty;time" class="coupon-inp changenow" readonly="" change-key="start" name="start" <?php echo $info['rpacket_t_start_date'] ? 'value="'.@date('Y-m-d H:i:s',$info['rpacket_t_start_date']).'"' : '';?> id="start"  ><i class="timeinp-icon"></i></p>
                                        <span class="tips-tag" tips="优惠券启用时间"><i class="tips-icon"></i></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="redstar">*</span>过期时间</td>
                                    <td>
                                        <p class="time-box coupon-time-box"><input type="text" localrequired="empty;time:start" class="coupon-inp changenow" readonly="" change-key="end1" name="end" <?php echo $info['rpacket_t_end_date'] ? 'value="'.@date('Y-m-d H:i:s',$info['rpacket_t_end_date']).'"' : '';?> id="end" ><i class="timeinp-icon"></i></p>
                                        <span class="tips-tag" tips="优惠券过期时间，必须大于启用时间"><i class="tips-icon"></i></span>
                                    </td>
                                </tr>
                                <tr <?php if($info['rpacket_t_wx'] == 1){ ?>style="display: none"<?php }?>>
                                    <td><span class="redstar" >*</span>兑换积分</td>
                                    <td>
                                        <input type="text" class="coupon-inp changenow" change-key="points" localrequired="intval;empty" name="rpacket_t_points"  value="{$info.rpacket_t_points}" id="rpacket_t_points"/><span class="tips-tag" tips="兑换优惠券所需积分，0为免费领取"><i class="tips-icon"></i></span>
                                    </td>
                                </tr>
                                <tr <?php if($info['rpacket_t_wx'] == 1){ ?>style="display: none"<?php }?>>
                                    <td>状态</td>
                                    <td>
                                        <div class="button-holder coupon-holder">
                                            <p class="radiobox coupon-radiobox"><input type="radio" id="radio-1-7" class="regular-radio" name="rpacket_t_state" value="1" <?php if($info['rpacket_t_state'] == 1 || empty($info['rpacket_t_state'])){ echo 'checked="checked"';} ?>><label for="radio-1-7"></label><span class="radio-word black-font">开启</span></p>

                                            <p class="radiobox coupon-radiobox"><input type="radio" id="radio-1-8" name="rpacket_t_state" class="regular-radio" name="rpacket_t_state" value="2" <?php if($info['rpacket_t_state'] != 1 && !empty($info['rpacket_t_state'])){ echo 'checked="checked"';} ?> ><label for="radio-1-8"></label><span class="radio-word black-font">关闭</span></p>
                                        </div>
                                        <span class="tips-tag" tips="启用或关闭优惠卷"><i class="tips-icon top0"></i></span>
                                    </td>
                                </tr>
                            </table>

                        </form>
                    </div>
                </div>
                <s>
                    <i class="bg-jt"></i>
                </s>
            </div>
        </div>
        <div class="btn-box-center borderT-none">
            <a class="btn1 radius3" id="form_submitadd" href="javascript:;">{$Think.lang.submit_btn}</a>
            <a class="btn1 radius3 marginL5" href='{:U("coupon/lists")}'>返回列表</a>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(function(){
        $("#start").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel:true,
            dateFormat: 'yy-mm-dd',
            showAnim:"fadeIn",//淡入效果
        });
        $("#end").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel:true,
            dateFormat: 'yy-mm-dd',
            showAnim:"fadeIn",//淡入效果
        });
        $('#form_submitadd').click(function(){
            flag=checkrequire('redpacke_form');
            if(!flag){
                $('#redpacke_form').submit();
            }
        });


        //颜色选择器
        $('.color-click').bind('click',function(e){
            $('.color-click-span').children('i').toggleClass('color-active');
            $('.color-show-box').toggle();

            $(document).one("click", function(){
                $('.color-click-span').children('i').removeClass('color-active');
                $('.color-show-box').hide();
            });
            e.stopPropagation();
        });
        $(".color-show-box").on("click", function(e){
            e.stopPropagation();
        });


        $('.color-span').css('background','#63b359');
        $('.color-show-list li').bind('click',function(){
            var color=$(this).attr("value");
            $('#rpacket_t_color').val($(this).attr("name"));
            //alert(color);
            $('.color-show-box').hide();
            $('.color-click-span').children('i').removeClass('color-active');
            $('.color-span').css('background',color);
            $('.coupon-box-top_wx').css('background',color);
        });
        $('.couponR-con span.tips-tag').each(function() {
            var _this = this;
            $(_this).bind(
                {
                    mousemove:function(){
                        e=arguments.callee.caller.arguments[0] || window.event;
                        tip = $(_this).attr('tips');
                        remindNeed($(_this),e,tip);
                    },
                    mouseout:function() {
                        $('.tip-remind').remove();
                    }
                }
            );
        });

        $("input[class='regular-radio one']").each(function(i){
            $(this).click(function(){
                _this = this;
                if($(_this).val() == '1'){
                    $(_this).val("0");
                    $(_this).removeAttr("checked");
                    $(_this).parent('.radiobox').find('.radio-word').removeClass('black-font');
                    if($(_this).attr('data-type') == 'rpacket_t_wx'){
                        $('.wx-coupon').hide();
                        $('#wxcolor').hide();
                        $('.sc-coupon').show();
                        $('#rpacket_t_points').parent().parent().show();
                    }
                }else{
                    $(_this).val("1");
                    $(_this).attr("checked","checked");
                    $(_this).parent('.radiobox').find('.radio-word').addClass('black-font');
                    if($(_this).attr('data-type') == 'rpacket_t_wx'){
                        $('.wx-coupon').show();
                        $('#wxcolor').show();
                        $('.sc-coupon').hide();
                        $('#rpacket_t_points').val('0');
                        $('.change-'+$('#rpacket_t_points').attr('change-key')).parent().parent().find('.coupon-points-none').show();
                        $('.change-'+$('#rpacket_t_points').attr('change-key')).parent().hide();
                        $('#rpacket_t_points').parent().parent().hide();
                    }
                }
            })
        })

        $("input[class='regular-radio one']").each(function(i){
            _this = this;
            if($(_this).attr('checked')){
                $(_this).val("1");
                $(_this).attr("checked","checked");
                $(_this).parent('.radiobox').find('.radio-word').addClass('black-font');
            } else {
                $(_this).val("0");
                $(_this).removeAttr("checked");
                $(_this).parent('.radiobox').find('.radio-word').removeClass('black-font');
            }
        });

        $('.changenow').bind('keydown keyup change foucs',function(){

            var change_key = $(this).attr('change-key');
            var _change = $(this).val();
            $('.change-'+change_key).each(function(i){
                _this = this;
                if(_change != ''){
                    if(change_key == 'points'){
                        if( _change == 0){

                            $(_this).parent().parent().find('.coupon-points-none').show();
                            $(_this).parent().hide();
                        }else{
                            $(_this).parent().parent().find('.coupon-points-none').hide();
                            $(_this).html(_change);
                            $(_this).parent().show();
                        }
                    }else{
                        $(_this).html(_change);
                    }
                }else{
                    $(_this).html($(_this).attr('change-default'));
                }
            });
        });

    })

</script>