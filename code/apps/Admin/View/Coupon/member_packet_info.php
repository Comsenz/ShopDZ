        <div class="tipsbox">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.用户优惠券详情。</li>
                <li>1.可查看优惠券信息和用户优惠券使用信息。</li>
            </ol>
        </div>
        <div class="iframeCon">
        <div class="iframeMain">
            <div class="white-shadow2">
                <div class="details-box">
                    <h1 class="details-tit">优惠券信息</h1>
                    <div class="jurisdiction boxsizing marginT0">
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">优惠券编码：</dt>
                            <dd class="left text-l">
                                {$info.rpacket_code}
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">优惠卷名称：</dt>
                            <dd class="left text-l">
                                {$info.rpacket_title}
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">优惠卷模版编号：</dt>
                            <dd class="left text-l">
                                {$info.rpacket_t_id}
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">领取日期：</dt>
                            <dd class="left text-l">
                                {$info.active_date}
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">订单限额：</dt>
                            <dd class="left text-l">
                                {$info.rpacket_limit}元
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">优惠券面额：</dt>
                            <dd class="left text-l">
                               {$info.rpacket_price}张
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">优惠卷有效期：</dt>
                            <dd class="left text-l">
                               {$info.start_date}<span class="redstar" >&nbsp; 至 &nbsp;</span>{$info.end_date}
                            </dd>
                        </dl>
                    </div>

                    <h1 class="details-tit">用户信息</h1>
                    <div class="jurisdiction boxsizing marginT0">
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">所有者名称：</dt>
                            <dd class="left text-l">
                                {$info.rpacket_owner_name}
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">优惠卷状态：</dt>
                            <dd class="left text-l">
                                <?php if($info['rpacket_state'] == 1){ echo '未使用';}elseif($info['rpacket_state'] == 2){ echo '已使用';}elseif($info['rpacket_state']){ echo '已过期';} ?>
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing remarks-dl">
                            <dt class="left text-r boxsizing">订单支付单号：</dt>
                            <dd class="left text-l">
                                <div class="remarks">{$info.order_id}</div>
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing remarks-dl">
                            <dt class="left text-r boxsizing">使用时间：</dt>
                            <dd class="left text-l">
                                <div class="remarks">{$info.used_date}</div>
                            </dd>
                        </dl>
                    </div>

                    <div class="btnbox3 boxsizing">
                        <a href='{:U("coupon/member_packet")}' type="button" class="btn1 radius3 btn3-btnmargin">返回列表</a>
                    </div>
                </div>
            </div>
        </div>
        </div>