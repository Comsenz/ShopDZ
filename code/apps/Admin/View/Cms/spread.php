
   		<div class="tipsbox radius3">
			<div class="tips boxsizing radius3">
				<div class="tips-titbox">
					<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
					<span class="open-span span-icon"><i class="open-icon"></i></span>
				</div>
			</div>
			<ol class="tips-list" id="tips-list">
				<li>1.提现设置后将会在用户提取现金时显示。</li>
			</ol>
		</div>
        <!--内容开始-->
        <div class="iframeCon">
		<div class="iframeMain">
			<ul class="transverse-nav">
				<li class="activeFour"><a href="javascript:;"><span>提现设置</span></a></li>
			</ul>
          <div class="white-bg ">
                
                <div class="tab-conbox">
                    <form method="post" class="form-horizontal" action="{:U('Cms/spread')}" enctype="multipart/form-data" id="article_setting">
                    <input name="form_submit"   type="hidden"  value="submit">
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>微信提现状态：</dt>
                                <dd class="left text-l">
                                    <div class="switch-box">
                                        <input type="checkbox" name="wx_withdraw_status" class="switch-radio" <if condition="$data.wx_withdraw_status eq 1 ">checked="checked"</if>/>
                                        <span class="switch-half switch-open">开启</span>
                                        <span class="switch-half switch-close close-bg">关闭</span>
                                    </div>
                                    <p class="remind1">开启：前台用户可以选择微信提现。关闭：直接进入银行卡提现。</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>微信提现审核：</dt>
                                <dd class="left text-l">
                                    <div class="switch-box">
                                        <input type="checkbox" name="wx_withdraw_audit_status" class="switch-radio" <if condition="$data.wx_withdraw_audit_status eq 1 ">checked="checked"</if>/>
                                        <span class="switch-half switch-open">开启</span>
                                        <span class="switch-half switch-close close-bg">关闭</span>
                                    </div>
                                    <p class="remind1">开启：提现申请需要管理员审核，审核通过后才打款。关闭：直接打款，不需要审核。</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>提现最小金额：</dt>
                                <dd class="left text-l">
                                    <input name="minprice" class="com-inp1 radius3 boxsizing" localrequired=""  placeholder="请输入文章标题" type="text" value="{$data.minprice}"> 
                                    <p class="remind1">提现最小金额，限制用户每次最少提现额度,最低额度是1元</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing">
                                <span class="redstar">*</span>
                                提现最大金额：
                                </dt>
                                <dd class="left text-l">
                                <input localrequired="" class="com-inp1 radius3 boxsizing" type="text" name="maxprice" value="<?php if(!empty($data['maxprice'])){echo $data['maxprice'];}else{echo 0;} ?>" placeholder="请输入文章排序">
                                <p class="remind1">提现最大金额，限制用户每次最多提现额度</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>提现说明：</dt>
                                <dd class="left text-l">
                                    <script id="desc" name="article_content" type="text/plain"><?php echo htmlspecialchars_decode(stripslashes($data['content']));?></script>
        <script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
         <script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.all.js"></script>
                                    <script type="text/javascript">
                                        var ue = UE.getEditor('desc',{
                                            'initialFrameWidth':750,
                                            'initialFrameHeight':500,
                                            'toolbars':[[
                            'fullscreen', 'source', '|', 'undo', 'redo', '|',
                            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                            'directionalityltr', 'directionalityrtl', 'indent', '|',
                            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                            'link', 'unlink', '|', 'simpleupload'
                        ]],
                                            });
                                    </script>
                                    <p class="remind1">请输入文章内容</p>
                                </dd>
                            </dl>
                        </div>
                        <div class="btnbox3 boxsizing">
                            <a type="button" id="spread_submit" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
                        </div>
                        </form>
                </div>
            </div>
        </div>  
        </div>  
        <!--内容结束-->
<div id="append_parent"></div>
<script type="text/javascript">
    $('#spread_submit').click(function(){
        flag=checkrequire('article_setting');
        if(!flag){
            $('#article_setting').submit();
        }
    });

</script>