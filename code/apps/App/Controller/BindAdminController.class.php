<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\GoodsService;
use Common\Helper\CacheHelper;
use Admin\Model\SettingModel;
class BindAdminController extends BaseController {
	public function bingAdmin(){
		$uid = I('user');
		$model =new SettingModel();
		$appid = $model->getSetting('wx_AppID');
		if(!empty($appid)){
			$REDIRECT_URI = SITE_URL .'api.php/BindAdmin/getcode?key='.$uid;
			$scope='snsapi_base';
			//$scope = 'snsapi_userinfo';//需要授权
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . urlencode($REDIRECT_URI) . '&response_type=code&scope=' . $scope . '&state=STATE#wechat_redirect';
			//$url = 'weixin://open.weixin.qq.com/connect/qrconnect?appid='.$appid.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state=STATE#wechat_redirect';
			header("Location:" . $url);
			die;
		}else{
			$wap = C('WAP_URL');
			$redirect = $wap.'successBind.html?type=3';
			header('Location: '.$redirect);
		}


	}
	public function getcode(){
		$code = $_GET["code"];
		$key = $_GET["key"];
		$model =new SettingModel();
		$appid = $model->getSetting('wx_AppID');
		$appsecret =$model->getSetting('wx_AppSecret');
		$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
		$userinfo = http_send_post($get_token_url);
		$userinfo = json_decode($userinfo,true);
		$openid = $userinfo['openid'];
		if(!empty($openid)){
			$update = M('admin')->where(array('uid'=>$key))->save(array('open_id'=>$openid));
			$wap = C('WAP_URL');
			if($update !== false){
				$redirect = $wap.'successBind.html?type=1';
				header('Location: '.$redirect);
			}else{
				$redirect = $wap.'successBind.html?type=2';
				header('Location: '.$redirect);
			}
		}




	}




   }
