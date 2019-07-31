<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
	    <div class="col-sm-12">
	        <div class="ibox float-e-margins">
	            <div class="ibox-title">
	                <h5>新增会员 </h5>
	            </div>
	            <div class="ibox-content">
	                <form method="post" class="form-horizontal" name="settingForm" action="{:U('Admin/Setting/editEmail')}" enctype="multipart/form-data">

					    <div class="form-group">
					        <label class="col-sm-3 control-label">会员名称：</label>
					        <div class="col-sm-9">
					            <input name="" class="form-control" placeholder="请输入会员名称" type="text">
					        </div>
					    </div>
					    <div class="form-group">
					        <label class="col-sm-3 control-label">密码：</label>
					        <div class="col-sm-9">
					            <input class="form-control" name="password" placeholder="请输入密码" type="password">
					        </div>
					    </div>
					    <div class="form-group">
					        <label class="col-sm-3 control-label">状态：</label>
					        <div class="col-sm-9">
					            <label class="radio-inline">
					                <input checked="" value="option1" id="optionsRadios1" name="optionsRadios" type="radio">正常</label>
					            <label class="radio-inline">
					                <input value="option2" id="optionsRadios2" name="optionsRadios" type="radio">禁用</label>
					        </div>
					    </div>
					    <div class="form-group">
					        <label class="col-sm-3 control-label">真实姓名：</label>
					        <div class="col-sm-9">
					            <input name="" class="form-control" placeholder="请输入真实姓名" type="text">
					        </div>
					    </div>
					    <div class="form-group">
					        <label for="-NaN" class="col-sm-3 control-label">性别：</label>
					        <div class="col-sm-9">
					            <label for="-NaN" class="radio-inline">
					                <input checked="" value="option1" id="-NaN" name="optionsRadios" type="radio">保密</label>
					            <label for="-NaN" class="radio-inline">
					                <input value="option2" id="-NaN" name="optionsRadios" type="radio">男</label>
					            <label for="-NaN" class="radio-inline">
					                <input value="option3" id="-NaN" name="optionsRadios" type="radio">女</label>
					        </div>
					    </div>
					    <div class="form-group">
					        <label class="col-sm-3 control-label">QQ：</label>
					        <div class="col-sm-9">
					            <input name="" class="form-control" placeholder="请输入QQ" type="text">
					        </div>
					    </div>
					    <div class="form-group">
					        <label class="col-sm-3 control-label">头像：</label>
					        <div class="col-sm-9">
					            <input name="" class="form-control" type="file">
					        </div>
					    </div>
					    <div class="form-group">
					        <label class="col-sm-3 control-label">积分：</label>
					        <div class="col-sm-9">
					            <p class="form-control-static">5000积分</p>
					        </div>
					    </div>
					    <div class="form-group">
					        <label class="col-sm-3 control-label">收货地址：</label>
					        <div class="col-sm-9">
					            <p class="form-control-static">这里是纯文字信息</p>
					            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                添加新地址
                            </button>
					        </div>
					    </div>
					    <div class="hr-line-dashed"></div>
	                    <div class="form-group">
	                        <div class="col-sm-4 col-sm-offset-2">
	                            <button class="btn btn-primary" type="submit">保存内容</button>
	                            
	                        </div>
	                    </div>
					</div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>
</div>
<!--收货地址添加页面-->
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">添加收货地址</h4>
                <small class="font-bold">会员名称：aaa
                </div>
                <div class="modal-body">
                    <div class="form-group"><label>Email</label> <input type="email" placeholder="请输入您的Email" class="form-control"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary">保存</button>
                </div>
            </div>
        </div>
    </div>

</div>