
   		<div class="tipsbox radius3">
			<div class="tips boxsizing radius3">
				<div class="tips-titbox">
					<h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
					<span class="open-span span-icon"><i class="open-icon"></i></span>
				</div>
			</div>
			<ol class="tips-list" id="tips-list">
				<li>1.添加银行名字。</li>
			</ol>
		</div>
        <!--内容开始-->
        <div class="iframeCon">
		<div class="iframeMain">
            <div class="white-shadow tab-content">
                <div class="tab-conbox">
                    <form method="post" class="form-horizontal" id="spread_form" action="<?=U('/Cms/bank')?>">
                        <div class="jurisdiction boxsizing">
                            <dl class="juris-dl boxsizing">
                                <dt class="left text-r boxsizing"><span class="redstar">*</span>银行名字</dt>
                                <dd class="left text-l">
                                    <input type="text" class="com-inp1 radius3 boxsizing" name='bank_name' value="<?=$data['name'];?>"  localrequired=""/>
									<p class="remind1">银行名字将会在用户设置银行信息时显示</p>
                                </dd>
                            </dl>
                        </div>
                        <div class="btnbox3 boxsizing">
							<input type='hidden' name='form_submit' value ="submit" />
							<input type='hidden' name='id' value ="<?=$data['id']?>" />
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
                $('#form_submitwx').click(function(){
                    $('#pay_formwx').submit();
                })
            })
        </script>