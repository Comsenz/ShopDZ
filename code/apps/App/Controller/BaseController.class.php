<?php
namespace App\Controller;
use Think\Controller;
use Common\Helper;
use Common\Service\MemberService;
use Common\Service\IndexService;
use Common\Service\SmsService;

class BaseController extends Controller {
	public $member_info = array();
	public static  $check_xss = true;//开启防止xss攻击
	public function _initialize() {
		$type = array('close_reason','web_status');
		$value = IndexService::getSetting($type);
		if(empty($value['web_status']))
			$this->returnJson(20002,$value['close_reason']);
		if(static::$check_xss)
			$this->_xss_check();
		//cookie写入用户唯一id
		 \Common\Helper\LoginHelper ::setNOloginSid();
	}
	public function returnJson($code, $msg='', $data=array()) {
		returnJson($code, $msg , $data);
	}

    public function getMember() {
		$key = I('param.key');
		if(empty($key))
			 $this->returnJson(1,'请登录');
        $user_token_info = MemberService::getUserTokenInfoByToken($key);
        if(empty($user_token_info)) {
            $this->returnJson(1,'请登录');
        }
        if(TIMESTAMP-$user_token_info['login_time'] > C('AUTH_COOKIE_TIME')) {
        	$condition = array('token' => $key);
	    	MemberService::delMbUserToken($condition);
        	$this->returnJson(1,'已过期，请重新登录');
        }
        $this->member_info = MemberService::getMemberInfo(array('member_id' => $user_token_info['member_id']));
        if(empty($this->member_info)) {
			setcookie('key','');
            $this->returnJson(1,'请登录');
        } else {
            $this->member_info['client_type'] = $user_token_info['client_type'];
            $this->member_info['openid'] = $user_token_info['openid'];
            $this->member_info['token'] = $user_token_info['token'];
            $this->member_info['member_id'] = $user_token_info['member_id'];
        }
    }

	private function _xss_check() {
		static $check = array('"', '>', '<', '\'', '(', ')', 'CONTENT-TRANSFER-ENCODING');

		if($_SERVER['REQUEST_METHOD'] == 'GET' ) {
			$temp = $_SERVER['REQUEST_URI'];
		} else {
			$temp = '';
		}

		if(!empty($temp)) {
			$temp = strtoupper(urldecode(urldecode($temp)));
			foreach ($check as $str) {
				if(strpos($temp, $str) !== false) {
					$this->returnJson(1,'您当前的访问请求当中含有非法字符，已经被系统拒绝');
				}
			}
		}

		return true;
	}


//发送短信验证码
	public function sendSMS($type = 1) {
		$mobile = I('post.mobile');
		//$type = I('post.type');
		if(!$mobile){
	        $this->returnJson(1,'参数错误');
	    }

	     //如果传入key参数，则是登录状态，比对手机号是否正确。
	    if(I('param.key')){
	    	$this->getMember();
	    	if($this->member_info['member_mobile'] != $mobile) {
	    		$this->returnJson(1,'参数错误');
	    	}
	    }
		//$code = \Common\Helper\SecCodeHelper ::makeSeccode('dfdfd');
		$res = SmsService::get_sms_new($mobile,$type);
		if($res['error']) {
			$this->returnJson(1,$res['error']);
		}
		$this->returnJson(0,'success');
	}

	//校验短信验证码
	public function checkSMS($type = 1) {

		$mobile = I('post.mobile');
		$seccode = I('post.seccode');
		//$type = I('post.type');
		if(!$mobile){
	        $this->returnJson(1,'参数错误');
	    }
	    if(!$seccode){
	        $this->returnJson(1,'请输入验证码');
	    }
	    //如果传入key参数，则是登录状态，比对手机号是否正确。
	    if(I('param.key')){
	    	$this->getMember();
	    	if($this->member_info['member_mobile'] != $mobile) {
	    		$this->returnJson(1,'参数错误');
	    	}
	    }
		$res = SmsService::check_sms($mobile,$type,$seccode);
		if($res['error']) {
	    	$this->returnJson(1,$res['error']);
		}
	    $this->returnJson(0,'success');
	}

	//图形验证码
    public function showVerifyCode(){
         $verify=new \Org\Helper\VerifyHelper();
         $verify->getVerifyCode();
    }

    //图形验证码验证
    public function checkVerifyCode(){
    	$verifyCode = I('param.verifyCode');
    	$verify=new \Org\Helper\VerifyHelper();
       	$res = $verify->checkCode($verifyCode);
       	if(I('param.ajax')) {
       		$code = $res?0:1;
       		$this->returnJson($code);
       	} else {
       		return $res;
       	}
    }

//获取设置值。
	public function getSetting($type) {
		$type_arr = array('shop_logo','shop_login','shop_member','record_number','shop_logo','spread');
		if(!in_array($type, $type_arr)) {
			$this->returnJson(1,'参数错误');
		}
		$value = IndexService::getSetting($type);
		$this->returnJson(0,'success', $value);
	}

	function cutstr($string, $length, $dot = ' ...') {
		if(strlen($string) <= $length) {
			return $string;
		}
		$pre = chr(1);
		$end = chr(1);
		$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), $string);
		$strcut = '';
		if(strtolower(CHARSET) == 'utf-8') {
			$n = $tn = $noc = 0;
			while($n < strlen($string)) {
				$t = ord($string[$n]);
				if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
					$tn = 1; $n++; $noc++;
				} elseif(194 <= $t && $t <= 223) {
					$tn = 2; $n += 2; $noc += 2;
				} elseif(224 <= $t && $t <= 239) {
					$tn = 3; $n += 3; $noc += 2;
				} elseif(240 <= $t && $t <= 247) {
					$tn = 4; $n += 4; $noc += 2;
				} elseif(248 <= $t && $t <= 251) {
					$tn = 5; $n += 5; $noc += 2;
				} elseif($t == 252 || $t == 253) {
					$tn = 6; $n += 6; $noc += 2;
				} else {
					$n++;
				}
				if($noc >= $length) {
					break;
				}
			}
			if($noc > $length) {
				$n -= $tn;
			}
			$strcut = substr($string, 0, $n);
		} else {
			$_length = $length - 1;
			for($i = 0; $i < $length; $i++) {
				if(ord($string[$i]) <= 127) {
					$strcut .= $string[$i];
				} else if($i < $_length) {
					$strcut .= $string[$i].$string[++$i];
				}
			}
		}
		$strcut = str_replace(array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

		$pos = strrpos($strcut, chr(1));
		if($pos !== false) {
			$strcut = substr($strcut,0,$pos);
		}
		return $strcut.$dot;
	}
    //生成二维码
    public function createQrCode($url,$path ='qrcode'){
        $filedir = \Common\Helper\ToolsHelper::create_path($path);
        $filename = $filedir.time().rand().'.png';
        \Common\Helper\QRcode::png($url,$filename,'L',100,1);
		return $filename;
    }

}