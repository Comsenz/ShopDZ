        <div class="tipsbox">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.优惠券模板详情。</li>
                <li>2.点击优惠券数量可查看已发放的已使用或未使用优惠卷列表。</li>
            </ol>
        </div>
        <div class="iframeCon">
        <div class="iframeMain">
            <div class="white-shadow2">
                <div class="details-box">
                    <h1 class="details-tit">优惠券信息</h1>
                    <div class="jurisdiction boxsizing marginT0">
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">优惠券名称：</dt>
                            <dd class="left text-l">
                                {$info.rpacket_t_title}
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">优惠卷面额：</dt>
                            <dd class="left text-l">
                                {$info.rpacket_t_price}元
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">订单消费金额：</dt>
                            <dd class="left text-l">
                                {$info.rpacket_t_limit}元
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">兑换积分：</dt>
                            <dd class="left text-l">
                                {$info.rpacket_t_points}
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">优惠卷数量：</dt>
                            <dd class="left text-l">
                                {$info.rpacket_t_total}&nbsp;(<a href='{:U("coupon/rep_list/status/giveout/id/$info[rpacket_t_id]")}' style="color: #00a2d4">已领{$info.rpacket_t_giveout}张 </a>，<a href='{:U("coupon/rep_list/status/used/id/$info[rpacket_t_id]")}' style="color: #00a2d4">已使用{$info.rpacket_t_used}张</a>)
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">每人限领：</dt>
                            <dd class="left text-l">
                               {$info.rpacket_t_eachlimit}张
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">优惠券状态：</dt>
                            <dd class="left text-l">
                               <?php if($info['rpacket_t_state'] == 1){ echo "启用";}elseif($info['rpacket_t_state'] == 2){ echo "停用";} ?>
                            </dd>
                        </dl>
                        <dl class="juris-dl boxsizing details-dl">
                            <dt class="left text-r boxsizing">有效期：</dt>
                            <dd class="left text-l">
                               {$info.start_time}<span class="redstar" >&nbsp; 至 &nbsp;</span>{$info.end_time}
                            </dd>
                        </dl>
                    </div>
                    <div class="btnbox3 boxsizing">
                        <a href='{:U("coupon/lists")}' type="button" class="btn1 radius3 btn3-btnmargin">返回列表</a>
                    </div>
                </div>
            </div>
        </div>
        </div>