
<link href="__PUBLIC__/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>


<div class="content">
	<button class="alert-btn" style="background: #f0f0f0;padding: 5px; margin: 10px;">弹框按钮</button>
	<!--弹框一-->
	<!--遮罩层-->
	<div class="cover alert-hide"></div>
	<!--弹框开始-->
	<div class="alertcon radius10 alert-hide">
		<div class="alert-tit boxsizing">
			<h2 class="left">标题标题</h2>
			<button class="closebtn2 right"></button>
		</div>
		<div class="alertcon-box">
			<!--内容-->
			<div class="jurisdiction2">
				<dl class="juris-dl2 boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>登录名：</dt>
					<dd class="left text-l">
						<input type="text" class="com-inp1 radius3 boxsizing"/>
						<p class="remind1">这是提示文字</p>
					</dd>
				</dl>
				<dl class="juris-dl2 boxsizing">
					<dt class="left text-r boxsizing"><span class="redstar">*</span>权限组：</dt>
					<dd class="left text-l">
						<select class="com-sele radius3 juris-dl-sele">
							<option>权限一</option>
							<option>权限二</option>
							<option>权限三</option>
							<option>权限四</option>
						</select>
						<p class="remind1">这是提示文字</p>
					</dd>
				</dl>
			</div>
			<div class="btnbox2">
				<a class="btn1 radius3 btn-margin2">取消</a>
				<a class="btn1 radius3 btn-margin2">确定</a>
			</div>
		</div>
		
	</div>
	<!--弹框结束-->
	<!--弹框一-->
	
	
	
	<!--弹框二-->
	<div class="row text-center">

        <div class="col-sm-6 h-100 p-lg">
            <p>基本消息</p>
            <button class="btn btn-primary btn-sm demo1">打开示例</button>
        </div>
        <div class="col-sm-6 h-100 p-lg">
            <p>成功提示 </p>
            <button class="btn btn-success btn-sm demo2">打开示例</button>
        </div>
        <div class="col-sm-6 h-100 p-lg">
            <p>警告信息</p>
            <button class="btn btn-warning btn-sm demo3">打开示例</button>
        </div>
        <div class="col-sm-6 h-100 p-lg">
            <p>通过传参可以自定义取消按钮 </p>
            <button class="btn btn-danger btn-sm demo4">打开示例</button>
        </div>





    </div>
	
	
	
	<!--弹框二-->
	
</div>

<script src="__PUBLIC__js/plugins/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/alertbox.js"></script>

<script>
        $(document).ready(function () {

            $('.demo1').click(function () {
                swal({
                    title: "欢迎使用SweetAlert",
                    text: "Sweet Alert 是一个替代传统的 JavaScript Alert 的漂亮提示效果。"
                });
            });

            $('.demo2').click(function () {
                swal({
                    title: "太帅了",
                    text: "小手一抖就打开了一个框",
                    type: "success"
                });
            });

            $('.demo3').click(function () {
                swal({
                    title: "您确定要删除这条信息吗",
                    text: "删除后将无法恢复，请谨慎操作！",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "删除",
                    closeOnConfirm: false
                }, function () {
                    swal("删除成功！", "您已经永久删除了这条信息。", "success");
                });
            });

            $('.demo4').click(function () {
                swal({
                        title: "您确定要删除这条信息吗",
                        text: "删除后将无法恢复，请谨慎操作！",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "是的，我要删除！",
                        cancelButtonText: "让我再考虑一下…",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            swal("删除成功！", "您已经永久删除了这条信息。", "success");
                        } else {
                            swal("已取消", "您取消了删除操作！", "error");
                        }
                    });
            });


        });
    </script>







