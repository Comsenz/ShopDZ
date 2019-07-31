        <div class="tipsbox">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.平台优惠券发放。</li>
                <li>2.选择要发放的优惠券。</li>
                <li>3.填写用户名（即用户手机号）进行发放。</li>
            </ol>
        </div>
<!--内容开始-->
<div class="iframeCon">
<div class="iframeMain">
    <ul class="transverse-nav">
        <li class="activeFour"><a href="javascript:;"><span>发放优惠券</span></a></li>
    </ul>
    <div class="white-bg">
        <div class="tab-conbox">
            <form method="post" class="form-horizontal" name="redpacket_form" id="redpacket_form" action='{:U("Coupon/grant")}'>
                <div class="jurisdiction boxsizing">
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>优惠券：</dt>
                        <dd class="left text-l">
                                <div class="search-boxcon boxsizing radius3 borderR-none" style="display: inline-block;">
                                    <select id="rpacket_t_id" name="rpacket_t_id">
                                    <foreach name="list" item="vo">
                                        <option value="{$vo.rpacket_t_id}">{$vo.rpacket_t_title}</option>
                                    </foreach>
                                    </select>
                                </div>
                            <p class="remind1">请选择要发放的优惠券</p>
                        </dd>
                    </dl>
                    <dl class="juris-dl boxsizing">
                        <dt class="left text-r boxsizing"><span class="redstar">*</span>用户名：</dt>
                        <dd class="left text-l">
                            <input type="text"  localrequired="" class="com-inp1 radius3 boxsizing" name="tel" value=""/>
                            <p class="remind1">请输入用户名</p>
                        </dd>
                    </dl>
                </div>
                <div class="btnbox3 boxsizing">
                    <input type="hidden" name='submitcheck' value="yes" />
                    <a class="btn1 radius3 btn3-btnmargin" id="form_submitadd" href="javascript:;">{$Think.lang.submit_btn}</a>
                    <a class="btn1 radius3" href='{:U("coupon/lists")}'>返回列表</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<!--内容结束-->
<script type="text/javascript">
    $(function(){
        $('#form_submitadd').click(function(){
            flag=checkrequire('redpacket_form');
            if(!flag){
                $('#redpacket_form').submit();
            }
        });
        $('.search_type').click(function(){
            $(this).addClass('active-sele').siblings().removeClass('active-sele');
            $('#rpacket_t_id').val($(this).attr('field-data'));
            $('#search_html').val($(this).html());
        })
        $('#rpacket_t_id').val($('.search_list').find('.active-sele').attr('field-data'));
        $('#search_html').val($('.search_list').find('.active-sele').html());
    })
</script>