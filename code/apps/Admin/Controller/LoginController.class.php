<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {

    public function index(){
        $this->checklogin();
        C('LAYOUT_ON',false);
        $this->display("login");
    }

    public function login(){
		$this->checklogin();
		$verify_code = I('post.verify');
		$name = I('post.name');
		$password = I('post.pwd');
		$verify=new \Org\Helper\VerifyHelper();
		
		$verify=$verify->checkCode($verify_code);
		if(empty($verify)){
		    $this->error('验证码错误');
		}
		$passwordmd5 = preg_match('/^\w{32}$/', $password) ? $password : md5($password);
		$condition['username'] = trim($name);
		$result=M('Admin')->where($condition)->find();
		if(empty($result)) {
			$this->error(L('login_name_error'));
		}
		$salt = $result['salt'];
		$password =  \Common\Helper\LoginHelper::passwordMd5($passwordmd5,$salt);
		 if($result['password'] != $password) {
			$this->error(L('passwd_error'));
		 }

		if(!empty($result)){
			$result['lastdateline'] = TIMESTAMP;
			$result['ip'] = get_client_ip();
			M('Admin')->save($result);
			$_SESSION['admin_user'] = $result;
			\Common\Helper\LogHelper::adminLog(array('content'=> L('login'),'username'=>$result['username'],'uid'=>$result['uid']));
		}
		$this->success(L('login_name_error'), U("/index/index"));
    }

    public function checklogin(){
        if (!empty($_SESSION['admin_user'])) {
            $this->redirect('index/index');
        }
    }

    public function logout(){
		if($_SESSION['admin_user']){
			\Common\Helper\LogHelper::adminLog(array('content'=> L('logout')));
		unset($_SESSION['admin_user']);
		}
        $this->redirect('/login/index');
    }

    public function showVerifyCode(){
         $verify=new \Org\Helper\VerifyHelper();
         $verify->getVerifyCode();
    }

}