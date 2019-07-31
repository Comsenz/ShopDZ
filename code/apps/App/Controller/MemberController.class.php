<?php
namespace App\Controller;
use Think\Controller;
use Admin\Model\SettingModel;
use Common\Service\MemberService;
use Common\Service\BasketService;
use Common\Service\IndexService;
use Common\Service\SmsService;
use Common\Service\NoticeService;
use Common\Wechat\WxapiController;
use \Admin\Model\MemberModel;
use Common\Service\CouponService;
class MemberController extends BaseController {
	public $cookietime = '';
	public $wap = '';
	public function __construct() {
		parent::__construct();
		$this->cookietime = time() + C('AUTH_COOKIE_TIME');
		$this->wap = C('WAP_URL');
	}
    
    public function isExist($member_username){
    	$res = array('is_exist' => false, 'member_info' => array());
    	if(!$member_username){
	        return $res;
	    }
		$ismobile = preg_match("/^1\d{10}$/",$member_username);
		if($ismobile) {
			$where = array('member_mobile' => $member_username);
		}else{
			$where = array('member_username' => $member_username);
		}
    	$member_info = MemberService::getMemberInfo($where);
		if(empty($member_info) || empty($member_info['member_id'])){
		    return $res;
		}
		$res['is_exist'] = true;
		$res['member_info'] = $member_info;
		return $res;
	}

	public function getMemberInfo() {
		$key = I('param.key');
		$user_token_info = MemberService::getUserTokenInfoByToken($key);
        if(empty($user_token_info)) {
			setcookie('key','');
            $this->returnJson(1,'登录失效，重新登录。');
        }
        $member_info = MemberService::getMemberInfo(array('member_id' => $user_token_info['member_id']));
		empty($member_info) && setcookie('key','');
		if($member_info){
			$sign = MemberService::is_sign_in_today($user_token_info['member_id']);
			$member_info['is_sign'] = $sign ? 1 : 0; 
		}
        $this->returnJson(0,'sucess',$member_info);
	}

	/**
	 * 编辑用户信息
	 */
    public function editMember() {
    	$data = I("post.");
    	$data['member_truename'] = mb_substr($data['member_truename'],0,21);
    	if(!$data['member_username']) {
    		$this->returnJson(1,'参数错误');
    	}
		$this->getMember();
		if($this->member_info['member_usrname'] != $data['member_usrname']) {
			$this->returnJson(1,'参数错误');
		}

		$data['member_id'] = $this->member_info['member_id'];
	    $res = MemberService::editMember($data);
    	if($res['error']){
    		$this->returnJson(1,$res['error']);
        }

		$this->returnJson(0,'success',$res['address_id']);
    }

	/**
	 * 手机验证码登录/注册
	 * 用户存在自动登录
	 * 用户不存在注册用户
	 */
	public function mregNext() {
		//自动登录
		$mobile = I('post.mobile');
		$seccode = I('post.seccode');
		$res = $this->isExist($mobile);
		if($res['is_exist']) {
			$res = SmsService::check_sms($mobile,4,$seccode);
			if($res['error']) {
				$this->returnJson(1,$res['error']);
			}
			$member_info = MemberService::getMemberInfo(array('member_mobile' => $mobile));
			$data = $this->autoLogin($member_info);
			if($data) {
				$this->returnJson(200,'sucess',$data);
			}
		}else{
			//注册
			$res = SmsService::check_sms($mobile,1,$seccode);
			if($res['error']) {
				$this->returnJson(1,$res['error']);
			}
			$post['member_mobile'] = $mobile;
			$post['member_username'] = $mobile;
			$post['member_passwd'] = '';
			$fromid = !empty($_COOKIE['fromid']) ? $_COOKIE['fromid'] : 0;
			if(!empty($fromid)){
				$post['fromid'] = $fromid;
			}
			$res = MemberService::wxaddMember($post); //注册新账户
			if(!$res['member_id']) {
				$this->addPoints($res['member_id'],$post['member_username']);
				$this->returnJson(1, $res['error']);
			}else{
				$member_info = MemberService::getMemberInfo(array('member_id' => $res['member_id']));
				$data = $this->autoLogin($member_info);
				$this->returnJson(200,'sucess',$data);
			}

		}
	}
	/**
	*注册送积分
	 */
	public function addPoints($uid,$username){
		if(!empty($uid) && !empty($username)){
			$insert_arr['pl_memberid'] = $uid;
			$insert_arr['pl_membername'] = $username;
			\Common\Helper\PointsHelper::addPoints($insert_arr,'register');
		}
	}
	/**
	 * 微信自动登录
	 * 注册账户
	 * 登录后绑定微信
	 */
	public function wxlogin(){
		$openid =  I('get.openid') ? I('get.openid') : $this->getopenid('wxlogin');
		$member_uid = $_COOKIE['uid'];
		$card_id = I('card_id');
		$type = I('get.type') ;
		if(empty($member_uid) || !empty($card_id)) {
			//用户自动登录
			$redirect =  I('get.redirect') ? I('get.redirect') : $_COOKIE['redirect'];
			if(!empty($card_id)){
				$redirect = $this->wap;
			}
			if (!empty($openid)) {
				$member_info = MemberService::getMemberInfo(array('weixin_openid' => $openid));
				if (!empty($member_info)) {
					//自动登录
					$fromid = !empty($_COOKIE['fromid']) ? $_COOKIE['fromid'] : 0;
					if(!empty($fromid) && empty($member_info['fromid'])){
						$this->updateFromid($member_info['member_id'],$fromid);
					}
					$this->autoLogin($member_info);
					header('Location: ' . $redirect);
				} else {
					//注册
					$post['member_passwd'] = '';
					$post['member_username'] = 'wx'. \Common\Helper\LoginHelper::random(9,1);
					$post['weixin_openid'] = $openid;
					$member_truename = $this->getwxinfo($openid,'nickname');
					if($member_truename){
						$nickname = json_encode($member_truename);
						$nickname = preg_replace("#(\\\ud[0-9a-f]{3})|(\\\ue[0-9a-f]{3})#ie","",$nickname);
						$member_truename = json_decode($nickname, true);
						$post['member_truename'] = $member_truename;//将emoji的unicode置为空，其他不动  
					}
					$fromid = !empty($_COOKIE['fromid']) ? $_COOKIE['fromid'] : 0;
					if(!empty($fromid)){
						$post['fromid'] = $fromid;
					}
					$res = MemberService::wxaddMember($post); //注册新账户
					$member_info = MemberService::getMemberInfo(array('member_id' => $res['member_id']));
					if($member_info) {
						$this->addPoints($member_info['member_id'],$member_info['member_username']);
						$this->autoLogin($member_info);
						if(empty($redirect)){
							$redirect = $this->wap.'member.html';
						}
						header('Location: ' . $redirect);
					}
				}
			}
		}else{
			if($type == 'menu'){
				$redirect = $this->wap.'member.html';
				header('Location: '.$redirect);
				exit;
			}
			//用户登录后绑定
			$member_info = MemberService::getMemberInfo(array('member_id' => $member_uid));
			$data['weixin_openid'] = $openid;
			$is_set = MemberService::getMemberInfo(array('weixin_openid' => $openid));
			if(empty($is_set)){
				$data['member_id'] = $member_info['member_id'];
				if(empty($member_info['member_truename'])){
					$data['member_truename'] = $this->getwxinfo($openid,'nickname');
				}
				$redirect =  I('get.redirect') ? I('get.redirect'): $this->wap.'changemember.html';
				$card_id = I('card_id');
				if(!empty($card_id)){
					$redirect = $this->wap;
				}
				CouponService::WxCardSynchroWeb($openid,$member_info['member_id']);
				if(MemberService::editMember($data)){
					header('Location: '.$redirect);
				}
			}else{

			}

		}

	}

	/**
	 * 立即购买注册
	 * 立即购买已有自动登录
	 * 新账户 注册用户 添加地址 自动登录
	 */
	public function orderNow(){
		$data = I("post.");
		$member_info = MemberService::getMemberInfo(array('member_mobile' => $data['tel_phone']));
		$seccode = $data['seccode'];
		$mobile = $data['tel_phone'];
		if($member_info){//已有账户 添加地址 自动登录
			$res = SmsService::check_sms($mobile,4,$seccode);
			if($res['error']) {
				$this->returnJson(1,$res['error']);
			}
			$data['member_id'] = $member_info['member_id'];
			unset($data['seccode']);
			$res = MemberService::saveAddress($data);
			if($res['address_id']){
				$this->autoLogin($member_info);
				$this->returnJson(0,'success',$res['address_id']);
			}else{
				$this->returnJson(1,$res['error'],'添加地址失败');
			}
		}else{ //新账户 注册用户 添加地址 自动登录
			$res = SmsService::check_sms($mobile,1,$seccode);
			if($res['error']) {
				$this->returnJson(1,$res['error']);
			}
			$post['member_mobile'] = $mobile;
			$post['member_username'] = $mobile;
			$post['member_passwd'] = '';
			$fromid = !empty($_COOKIE['fromid']) ? $_COOKIE['fromid'] : 0;
			if(!empty($fromid)){
				$post['fromid'] = $fromid;
			}
			$res = MemberService::wxaddMember($post); //注册新账户
			if($res['member_id']) {
				$new_member_info = MemberService::getMemberInfo(array('member_id' => $res['member_id']));
				if($new_member_info){
					$data['member_id'] = $new_member_info['member_id'];
					unset($data['seccode']);
					$this->addPoints($new_member_info['member_id'],$new_member_info['member_username']);
					$res = MemberService::saveAddress($data);
					if($res['address_id']){
						$this->autoLogin($new_member_info);
						$this->returnJson(0,'success',$res['address_id']);
					}else{
						$this->returnJson(1,$res['error'],'地址添加失败');
					}
				}else{
					$this->returnJson(1, $res['error'],'查询用户失败');
				}
			}else{
				$this->returnJson(1, $res['error'],'注册新账户失败！');
			}
		}

	}
	/**
	 * 自动登录公用
	 */
	public function autoLogin($member_info){
		$notice = new NoticeService();
		$notice -> send_template_notice($member_info['member_id'],'member_register',array());
		$token = $this->_get_token($member_info['member_id'], $member_info['member_username'], 'wap');
		$time = time() + 86400;
		setcookie('uid', $member_info['member_id'], $this->cookietime, '/');
		setcookie('key', $token, $this->cookietime, '/');
		$loginid = \Common\Helper\LoginHelper::getNOloginSid();
		BasketService::syncLoginbasket($loginid,$member_info['member_id']);
		if(!empty($member_info['weixin_openid'])){
			CouponService::WxCardSynchroWeb($member_info['weixin_openid'],$member_info['member_id']); //同步微信卡券
		}
		$data = array('member_username' => $member_info['member_username'], 'member_id' => $member_info['member_id'], 'key' => $token);
		return $data;
	}
	/**
	 * 普通注册
	 */
	public function register(){
		$post['member_mobile'] = I("post.mobile");
		$post['member_passwd'] = I("post.passwd");
		$post['confirm_password'] = I("post.repasswd");
		$fromid = !empty($_COOKIE['fromid']) ? $_COOKIE['fromid'] : 0;
		$post['fromid'] =  $fromid;

		$seccode = I("post.seccode");
		if($post['member_passwd'] != $post['confirm_password']) {
			$this->returnJson(1,'两次密码不一致');
		}
		//$type = I("post.type");
		$type = 1;
		$res = SmsService::check_sms($post['member_mobile'],$type,$seccode,1);
		if($res['error']) {
			$this->returnJson(1,$res['error']);
		}
		$res = MemberService::addMember($post);
		if(!$res['member_id']) {
			$this->returnJson(1, $res['error']);
		}
		$notice = new NoticeService();
		$notice -> send_template_notice($post['member_mobile'],'member_register',array());
		$this->addPoints($res['member_id'],$post['member_mobile']);
		$token = $this->_get_token($res['member_id'], $post['member_mobile'], 'wap');
		if($token) {
			setcookie('key', $token, $this->cookietime, '/');
			setcookie('uid', $res['member_id'], $this->cookietime, '/');
			$data = array( 'member_id' => $res['member_id'], 'key' => $token);
			$loginid = \Common\Helper\LoginHelper ::getNOloginSid();
			BasketService::syncLoginbasket($loginid,$res['member_id']);
			$this->returnJson(0,'sucess',$data);
		}
	}
	/**
	 * 普通登录
	 */
	public function login(){

		$member_username = I('param.mobile');
		$passwd = $_REQUEST['passwd'];
		if(!$member_username){
			$this->returnJson(1,'参数错误');
		}
		$res = $this->isExist($member_username);
		if($res['is_exist']) {
			$member_info = $res['member_info'];

			//会员关闭状态不能登录
			if(!$member_info['member_state']) {
				$this->returnJson(1,'您的账户被禁止登录，请联系管理员。');
			}
			$passwordmd5 = preg_match('/^\w{32}$/', $passwd) ? $passwd : md5($passwd);
			$password =  \Common\Helper\LoginHelper::passwordMd5($passwordmd5,$member_info['salt']);

			if($member_info['member_passwd'] != $password) {
				$this->returnJson(1,'账号或密码错误');
			}
			$token = $this->_get_token($member_info['member_id'], $member_info['member_mobile'], $_POST['client']);
			if($token) {
				$fromid = !empty($_COOKIE['fromid']) ? $_COOKIE['fromid'] : 0;

				if(!empty($fromid) && empty($member_info['fromid'])){
					$this->updateFromid($member_info['member_id'],$fromid);
				}
				$data = array('member_mobile' => $member_info['member_mobile'], 'member_id' => $member_info['member_id'], 'key' => $token);
				setcookie('key', $token,$this->cookietime, '/');
				setcookie('uid', $member_info['member_id'], $this->cookietime, '/');
				$loginid = \Common\Helper\LoginHelper ::getNOloginSid();
				BasketService::syncLoginbasket($loginid,$member_info['member_id']);
				$this->returnJson(0,'sucess',$data);
			} else {
				$this->returnJson(1,'登录失败');
			}
		} else {
			$this->returnJson(1,'账号或密码错误!');
		}
	}
	/**
	 * 微信解绑/修改密码 公用方法
	 * 微信解绑
	 * 修改密码
	 */
	public function  wxOutbing(){
		$member_mobile = $_COOKIE['uid'];
		$member_info = MemberService::getMemberInfo(array('member_id' => $member_mobile));
		$changpwd = I('post.changpwd');
		if(empty($changpwd)) {
			if (!empty($member_info)) {
				if (!empty($member_info['member_passwd'])) {
					$data['weixin_openid'] = '';
					$data['member_id'] = $member_info['member_id'];
					if (MemberService::editMember($data)) {
						$this->returnJson(1, '解绑成功');
					} else {
						$return['message'] = '解绑失败';
						$this->returnJson(0, 'error', $return);
					}
				} else {
					$this->returnJson(403, 'sucess');
				}
			}
		}else{
			$member_uid = $_COOKIE['uid'];
			$member_passwd = I("post.passwd");
			$confirm_password = I("post.repasswd");
			if($member_passwd != $confirm_password) {
				$this->returnJson(1,'两次密码不一致');
			}
			$post['member_id'] = $member_uid;
			$post['member_passwd'] = $member_passwd;
			$post['weixin_openid'] = '';
			$res = MemberService::editMember($post);
			if($res['error']) {
				$this->returnJson(1, '解绑失败');
			}
			$this->returnJson(0, '解绑成功');
		}
	}

	/**
	 * 微信 已有用户绑定手机号
	 */
	public function wxregNext() {
		$mobile = I('post.mobile');
		$seccode = I('post.seccode');
		$res = $this->isExist($mobile);
		if(!$res['is_exist']) {
			$res = SmsService::check_sms($mobile,1,$seccode);
			if($res['error']) {
				$this->returnJson(1,$res['error']);
			}
			$key = I('post.key');
			$user_token_info = MemberService::getUserTokenInfoByToken($key);
			$member_info = MemberService::getMemberInfo(array('member_id' => $user_token_info['member_id']));
			if($member_info){
				$data['member_mobile'] = $mobile;
				$data['member_id'] = $member_info['member_id'];
				if(MemberService::editMember($data)){

					$this->returnJson(0,'success','绑定成功');
				}
			}else{
				$this->returnJson(1,'error','参数错误！');
			}
		}else {
			$this->returnJson(1,$res['error'],'手机号已存在');
		}
	}
	/**
	 * 注册
	 * 手机号绑定
	 * 验证短信验证码
	 */

	public function sendRegSeccode() {
		$mobile = I('param.mobile');
		if(!$mobile){
	        $this->returnJson(1,'参数错误');
	    }
		$verify_status =IndexService::getSettings('verify_status');
		if($verify_status){
			$r = $this->checkVerifyCode();
			if(!$r) {
				$this->returnJson(1,'图形验证码错误');
			}
		}
	    $res = $this->isExist($mobile);
	    if($res['is_exist']) {
	    	$this->returnJson(1,'手机号已存在');
	    } else {

			//$this->returnJson(0,'success');
		$this->sendSMS();
	    }

	}

	/**
	 * 立即购买发送短信
	 * 手机登录验证短信
	 */
	public function sendNowSeccode() {
		$mobile = I('param.mobile');
		if(!$mobile){
			$this->returnJson(1,'参数错误');
		}
		$res = $this->isExist($mobile);
		if($res['is_exist']) {
			$send = SmsService::get_sms_new($mobile,4);
			if(!empty($send['error'])) {
				$this->returnJson(1,$send['error'].$send['code']);
			}else{
				$this->returnJson(0,'success');
			}

		} else {
			$send =  SmsService::get_sms_new($mobile,1);
			if(!empty($send['error'])) {
				$this->returnJson(1,$send['error'].$send['code']);
			}else{
				$this->returnJson(0,'success');
			}

		}

	}


	public function getopenid($action){
		$openid = I('get.openid') ? I('get.openid') : cookie('wx_openid');
		if(!$openid) {
			$model =new SettingModel();
			$appid = $model->getSetting('wx_AppID');
			$scheme = strtolower($_SERVER['HTTPS']) == "on" ? 'https' : 'http';
			$server_name = $scheme.'://'.$_SERVER["SERVER_NAME"];
			$REDIRECT_URI = $server_name.'/api.php/member/getcode?action='.$action;
			//$scope='snsapi_base';
			$scope = 'snsapi_userinfo';//需要授权
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . urlencode($REDIRECT_URI) . '&response_type=code&scope=' . $scope . '&state=STATE#wechat_redirect';
			//$url = 'weixin://open.weixin.qq.com/connect/qrconnect?appid='.$appid.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state=STATE#wechat_redirect';
			header("Location:" . $url);
			die;
		}
		return $openid;
	}
	public function getcode(){
		$code = $_GET["code"];
		$action = $_GET["action"];
		$model =new SettingModel();
		$appid = $model->getSetting('wx_AppID');
		$appsecret =$model->getSetting('wx_AppSecret');
		$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
		$userinfo = http_send_post($get_token_url);
		$userinfo = json_decode($userinfo,true);
		$openid = $userinfo['openid'];
		cookie('wx_openid', $openid);
		header("Location:" . '/api.php/member/'.$action.'?openid='.$openid);
		exit;

	}
	function checkPasswd() {
		$this->getMember();
		$mobile = I('post.mobile');
		if($this->member_info['member_mobile'] != $mobile){
			$this->returnJson(1,'参数错误');
		}
		$this->returnJson(0,'success');
	}

	/**
	 * 注册第一步，校验短信验证码
	  */
	public function regNext() {
		$mobile = I('post.mobile');
		$res = $this->isExist($mobile);
		if($res['is_exist']) {
			$this->returnJson(1,'手机号已存在');
		}
		$this->checkSMS(1);
		$this->returnJson(0,'success');
	}

	/**
	 * 修改银行卡
	 */
	public function sendbankseccode() {
		$mobile = I('param.mobile');
		if(!$mobile){
	        $this->returnJson(1,'参数错误');
	    }
		$verify_status =IndexService::getSettings('verify_status');
		if($verify_status) {
			$r = $this->checkVerifyCode();
			if(!$r) {
				$this->returnJson(1,'图形验证码错误');
			}
		}
	    $res = $this->isExist($mobile);
	    if(!$res['is_exist']) {
	    	$this->returnJson(1,'手机号不存在');
	    } else {
			$this->sendSMS(5);
	    }

	}
	/**
	 * 找回密码发送短信验证码
	 */
	public function sendFindPasswdSeccode() {
		$mobile = I('param.mobile');
		if(!$mobile){
	        $this->returnJson(1,'参数错误');
	    }
		$verify_status =IndexService::getSettings('verify_status');
		if($verify_status) {
			$r = $this->checkVerifyCode();
			if(!$r) {
				$this->returnJson(1,'图形验证码错误');
			}
		}
	    $res = $this->isExist($mobile);
	    if(!$res['is_exist']) {
	    	$this->returnJson(1,'手机号不存在');
	    } else {
			$this->sendSMS(3);
	    }

	}
	/**
	 * 找回密码校验短信验证码
	 */
	public function FindPasswdNext() {
		$mobile = I('post.mobile');
	    $res = $this->isExist($mobile);
	    if(!$res['is_exist']) {
	    	$this->returnJson(1,'手机号不存在');
	    }
	    $this->checkSMS(3);
	    $this->returnJson(0,'success');
	}
	/**
	 * 退出
	 */
    public function logout() {
    	$client = I('param.client');
    	if( empty($client)) {
            $this->returnJson(1, '参数错误');
        }
		$model_mb_user_token = M('user_token');
		$this->getMember();
		$condition = array();
		$condition['member_id'] = $this->member_info['member_id'];
		$condition['client_type'] = $client;
		MemberService::delMbUserToken($condition);
		setcookie('key','');
		$this->returnJson(0,'sucess');
    }

    public function getpointslist(){
    	$list = $details = $con = array();
    	$this->getMember();
    	$con['pl_memberid'] = $this->member_info['member_id'];
		$page = I('param.page',1,'intval');
		$prepage = I('param.prepage',8,'intval');

		$start =( $page-1 ) * $prepage;
        $points_list = M("points_log") -> where($con)->order('pl_addtime desc') -> limit($start.','.$prepage) ->select();
        $pointsum = $this->member_info['member_points'];//M("points_log")->field('sum(pl_points) sum')->where($con)->select();
        foreach ($points_list as $key => $value) {
        	$points_list[$key]['pl_addtime_text'] = date('Y-m-d H:i:s',$points_list[$key]['pl_addtime']);
        	if($points_list[$key]['pl_points'] >= 0){
        		$points_list[$key]['pl_points'] = '+'.$points_list[$key]['pl_points'];
        	}
        }
	
		if(empty($points_list)){
			$this->returnJson(1,'积分明细已全部显示！',array('points_list'=>''));
		}
		$this->returnJson(0,'success',array('pointsum'=>$pointsum,'points_list'=>$points_list));
    }

	/**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client) {
        //重新登录后以前的令牌失效
		/*
	    $condition = array();
	    $condition['member_id'] = $member_id;
	    $condition['client_type'] = $client;
	    MemberService::delMbUserToken($condition);
		*/
        //生成新的token
        $user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $user_token_info['member_id'] = $member_id;
        $user_token_info['member_name'] = $member_name;
        $user_token_info['token'] = $token;
        $user_token_info['login_time'] = TIMESTAMP;
        $user_token_info['client_type'] = $client;

        $result = MemberService::addUserToken($user_token_info);
        if($result) {
        	//储存登录信息
        	MemberService::loginMember($member_id);

        	//首次登陆送签到积分
        	//MemberService::sign_in($member_id);


            return $token;
        } else {
            return null;
        }
    }

	/**
     * 用户签到
     */
	 public function sign_in() {
    	$this->getMember();
    	$member_id = $this->member_info['member_id'];
	 	if(MemberService::sign_in($member_id)){
            $points = M('setting')->where("name='points_sign_in'")->getField("value");
	 		$this->returnJson(0,'签到成功',array('points' => $points));
	 	}
		$this->returnJson(1,'你已签到过');
	 }
	/**
	 * 获取当前用户的收货地址列表
	 */
    public function addressList() {
    	$this->getMember();
    	$condition['member_id'] = $this->member_info['member_id'];
    	$address['address_list'] = MemberService::getAddressList($condition);
    	$this->returnJson(0,'sucess',$address);

    }
	/**
	 * 添加编辑收货地址
	 */
    public function addressAdd() {
    	$data = I("post.");
		$this->getMember();
    	//添加编辑收货地址
		$data['member_id'] = $this->member_info['member_id'];
	    $res = MemberService::saveAddress($data);
    	if(!$res['address_id']){
    		$this->returnJson(1,$res['error']);
        }

		$this->returnJson(0,'success',$res['address_id']);

    }

	/**
	 * 设置默认地址
	 */
    public function addressSetDefault() {
    	$address_id = I("post.address_id");
    	$this->getMember();
    	$res = MemberService::address_default($this->member_info['member_id'], $address_id);
        if(!$res) {
        	$this->returnJson(1,'数据执行失败，请重新操作。');
        }
        $this->returnJson(0,'sucess',$res);
    }
	/**
	 * 地址删除
	 */
    public function addressDel() {
      
        $address_id = I('address_id',0,'intval');
	    $info = MemberService::getAddressInfo(array('address_id' => $address_id));
	    if(!count($info)) {
	    	$this->returnJson(1,'操作数据不存在');
	    }
	    if($info['is_default']) {
	    	$this->returnJson(1,'默认地址不能进行删除操作');
	    }
        $res = MemberService::delAddress($address_id);
        if(!$res) {
        	$this->returnJson(1,'数据执行失败，请重新操作。');
        }
        $this->returnJson(0,'sucess',$res);
    }

	/**
	 * 获取子地区列表
	 */
    public function getChildAreaList() {
    	$area_parent_id = I('area_parent_id',0,'intval');
    	if($area_parent_id < 0 ){
    		$this->returnJson(1,'参数错误');
    	}
    	$list = MemberService::getAreaList(array('area_parent_id' => $area_parent_id));
    	$this->returnJson(0,'sucess',$list);
    }
	/**
	 * 获取微信用户信息
	 */
	public function getwxinfo($openid,$type){
		$setingMoudel = new SettingModel();
		$wx_AppID = $setingMoudel->getSetting('wx_AppID');
		$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
		$wxapi = new WxapiController($wx_AppID,$wx_AppSecret);
		$wxinfo = $wxapi->getinfo($openid);

		return $wxinfo[$type];
	}

	public function  GetSignPackage(){
		$setingMoudel = new SettingModel();
		$wx_AppID = $setingMoudel->getSetting('wx_AppID');
		$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
		$jssdk = new WxapiController($wx_AppID,$wx_AppSecret);
		$url = I('get.url');
		$signPackage = $jssdk->GetSignPackage(urldecode($url));
		$this->returnJson(0,'sucess',$signPackage);
	}
	/**
	 * 已有账户更新 fromid
	 */

	public function  updateFromid($member_id,$fromid){
		if(!empty($fromid) && !empty($member_id) && $member_id != $fromid){
			$data['fromid'] = $fromid;
			$data['member_id'] = $member_id;
			if(MemberService::editMember($data)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//修改密码
	public function passwdEdit(){

		$member_mobile = I("post.mobile");
		$member_passwd = I("post.passwd");
		$confirm_password = I("post.repasswd");
		$seccode = I("post.seccode");
		//$type = I("post.type");
		$type = 3;
		if($member_passwd != $confirm_password) {
			$this->returnJson(1,'两次密码不一致');
		}
		$res = SmsService::check_sms($member_mobile,$type,$seccode,1);
		if($res['error']) {
			$this->returnJson(1,$res['error']);
		}
		$member_info = MemberService::getMemberInfo(array('member_mobile' => $member_mobile));
		if($member_info){
			$post['member_id'] = $member_info['member_id'];
			$post['member_passwd'] = $member_passwd;
			$res = MemberService::editMember($post);
			if($res['error']) {
				$this->returnJson(1, $res['error']);
			}
			$p['member_name'] = $member_info['member_username'];
			if(I('post.key')){
				$p['token'] = array('neq', I('post.key'));
			}
			M('user_token')->where($p)->delete();
			$this->returnJson(0, 'success');
		}
	}

}