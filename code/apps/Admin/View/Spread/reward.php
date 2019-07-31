
   		<div class="tipsbox radius3">
			<div class="tips boxsizing radius3">
				<div class="tips-titbox">
					<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
					<span class="open-span span-icon"><i class="open-icon"></i></span>
				</div>
			</div>
			<ol class="tips-list" id="tips-list">
				<li>1.会员推广商城奖励设置</li>
				<li>2.三级奖励合计不得超过50%</li>
			</ol>
		</div>
        <!--内容开始-->
        <div class="iframeCon">
		<div class="iframeMain">
			<ul class="transverse-nav">
				<li class="activeFour"><a href="javascript:;"><span>奖励设置</span></a></li>
			</ul>
            <div class="white-bg">
                <div class="tab-conbox">
                    <form method="post" class="form-horizontal" id="spread_form" action="<?=U('/Spread/reward')?>">
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>一级奖励比例</dt>
                                <dd class="left text-l">
                                    <input type="text" class="com-inp1 radius3 boxsizing" name='reward_1' value="<?=$data['reward_1'];?>"  localrequired=""/>%
									<p class="remind1">一级奖励的分成比例，请填写正整数值</p>
                                </dd>
                            </dl>
                        </div>
						<div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>二级奖励比例</dt>
                                <dd class="left text-l">
                                    <input type="text" class="com-inp1 radius3 boxsizing" name='reward_2' value="<?=$data['reward_2'];?>"  localrequired=""/>%
									<p class="remind1">二级奖励的分成比例，请正填写整数值</p>
                                </dd>
                            </dl>
                        </div>
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>三级奖励比例</dt>
                                <dd class="left text-l">
                                    <input type="text" class="com-inp1 radius3 boxsizing" name='reward_3' value="<?=$data['reward_3'];?>"  localrequired=""/>%
									<p class="remind1">三级奖励的分成比例，请填写正整数值</p>
                                </dd>
                            </dl>
                        </div>
			
                        <div class="btnbox3 boxsizing">
							<input type='hidden' name='form_submit' value ="submit" />
                            <a class="btn1 radius3 btn3-btnmargin" id="form_submitalipay" href="javascript:;">{$Think.lang.submit_btn}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
        </div>  
        <!--内容结束-->
        <script type="text/javascript">
            $(function(){
                $('#form_submitalipay').click(function(){
					flag = checkrequire('spread_form');
					if(!flag)
						$('#spread_form').submit();
                });
            })
        </script>