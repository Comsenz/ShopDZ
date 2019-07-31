
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
            <div class="white-bg">
                <div class="tab-conbox">
                    <form method="post" class="form-horizontal" id="spread_form" action="<?=U('/Spread/spread')?>">
                    <input type="hidden" name="paytype" value="alipay">
                    <input type="hidden" name="sub" value="1">
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>提现最小金额</dt>
                                <dd class="left text-l">
                                    <input type="text" class="com-inp1 radius3 boxsizing" name="minprice"  value="{$data['minprice']}" localrequired=""/>
									<p class="remind1">提现最小金额，限制用户每次最少提现额度</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>提现最大金额：</dt>
                                <dd class="left text-l">
                                    <input type="text" class="com-inp1 radius3 boxsizing" name="maxprice"  value="{$data['maxprice']}" localrequired=""/>
									<p class="remind1">提现最大金额，限制用户每次最多提现额度</p>
                                </dd>
                            </dl>
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>提现说明：</dt>
                                <dd class="left text-l">
                                   <script id="content" name="content" type="text/plain"><?php echo htmlspecialchars_decode(stripslashes($data['content']));?></script>
								   	<p class="remind1">提现说明，显示在用户提现页面，说明提现规则</p>
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
		<script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
		<script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.all.js"></script>
		<script type="text/javascript">
                        var ue = UE.getEditor('content',{
                            'initialFrameWidth':"100%",
                            'initialFrameHeight':350,
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
        <script type="text/javascript">
            $(function(){
                $('#form_submitalipay').click(function(){
					flag = checkrequire('spread_form');
					if(!flag)
						$('#spread_form').submit();
                });
                $('#form_submitwx').click(function(){
                    $('#pay_formwx').submit();
                })
            })
        </script>