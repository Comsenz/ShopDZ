<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\IndexService;
use Common\Helper\ToolsHelper;
use Admin\Model\SettingModel;

class AuthorizeController  {
	function change_license() {
        	$radio = true;
        	$json = array();
                $result = json_decode(ORIZE(urldecode(I('post.code')),'DECODE'),true);
                if(empty($result)){
                	$radio = false;
                        $a = 0;
                }
                if($radio === true){
                	$setting = array(
        			'license_code'		=> $result['license_code'],
        			'license_status'	=> $result['license_status'],
        			'license_time'		=> $result['license_time'],
                    'license_secret'    => $result['license_secret'],
                    'sms_account'       => $result['sms_account'],
                    'sms_password'      => $result['sms_password'],
                    'sms_login_password'=> $result['sms_password'],
        		      );
                	$model = new SettingModel();
					$model -> Settings($setting);
                        $a = 1;
                }
                echo $a;
                exit;
	}

	function back_authorize(){
			$json = array(
						'code' => 1,
						'server'    => $_SERVER['HTTP_HOST'],
					);
			echo json_encode($json);
			exit;
	}
	
	function license() {
		$url = 'http://saas.shopdz.cn/api.php/index/install';
		$post['ip'] =  get_client_ip();
		$post['host'] = trim(SITE_URL,'/');
		$return = send_post($url,$post);
		$json = json_decode($return,true);
		if(($json['code']))
		 returnJson(0,$json['data']['msg']); 
		  returnJson(1,'激活失败',$json);
		exit;
	}
	
	function select() {
		$url = 'saas.shopdz.cn/api.php/license/select_license?jsonp_callback='.I('get.jsonp_callback');
		$post['license_server'] = trim(SITE_URL,'/');
		$return = send_post($url,$post);
		echo $return;
		exit;
	}
}
