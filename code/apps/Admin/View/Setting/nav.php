<ul class="transverse-nav">
	<li <?php if($_GET['sub'] == 1 || empty($_GET['sub'])){?> class="activeFour" <?php }?>><a href="<?=U('Admin/Setting/base',array('sub'=>'1'))?>"><span>商城设置</span></a></li>
	<li <?php if($_GET['sub'] == 2){?> class="activeFour" <?php }?>><a href="<?=U('Admin/Setting/base',array('sub'=>'2'))?>" ><span>注册协议</span></a></li>
	<li <?php if($_GET['sub'] == 3){?> class="activeFour" <?php }?>><a href="<?=U('Admin/Setting/base',array('sub'=>'3'))?>"><span>验证码开关</span></a></li>
</ul>
<script>
$(".transverse-nav>li").unbind('click');
</script>