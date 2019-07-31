<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\CouponService;
use Common\Wechat\WxcardController;
use Admin\Model\SettingModel;
use  Common\Helper\LoginHelper;
use Common\Service\MemberService;
use Common\Wechat\WxapiController;

class CouponWxController extends BaseController {
	public $cookietime = '';
	public $wap = '';
	public function __construct()
	{
		parent::__construct();
		$this->cookietime = time() + C('AUTH_COOKIE_TIME');
		$this->wap = C('WAP_URL');
	}
//优惠券详情页
	public function getcoupon(){
		$rpacket_t_id = intval(I('get.id'));
		if(!$rpacket_t_id){
			$this->returnJson(1,'参数错误！');
		}
		$info = CouponService::getRedpacketTempList($rpacket_t_id);
		if(!$info){
			$this->returnJson(1,'参数错误！');
		}
		$info['start'] =  date('Y.m.d ',$info['rpacket_t_start_date']);
		$info['end'] =  date('Y.m.d ',$info['rpacket_t_end_date']);
		if($info['rpacket_t_eachlimit'] != 0){
			$info['limit_num'] = '限领'.$info['rpacket_t_eachlimit'].'张';
			$info['status'] = 0;//0表示可以领取，1表示不能领取
		}else{
			$info['limit_num'] = '不限领取';
			$info['status'] = 0;
		}
		$data['info'] = $info;
		$this->returnJson(0,'sucess',$data);
	}
	public function  GetCodeSign(){
		$setingMoudel = new SettingModel();
		$wx_AppID = $setingMoudel->getSetting('wx_AppID');
		$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
		$jssdk = new WxCardController($wx_AppID,$wx_AppSecret);
		$id = I('post.id');
		$info = CouponService::getRedpacketTempList($id);
		$cardticket = (String)$jssdk->getcardticket();
		$code =LoginHelper::random(12,true);
		$time = (String)time();
		$nonce_str = LoginHelper::random(10);
		$extparam = array(
				'timestamp'=>$time,
				'api_ticket'=> $cardticket,
				'code'=>$code,
				'card_id'=>$info['rpacket_t_wx_card_id'],
				'nonce_str'=>$nonce_str,
		);
		$ext['code'] = $extparam['code'];
		$ext['timestamp'] = $extparam['timestamp'];
		$ext['nonce_str'] = $extparam['nonce_str'];
		$ext['signature']   =  $jssdk->getCardSign($extparam);//卡券签名
		$signPackage['ext'] = json_encode($ext);
		$signPackage['card_id'] = $info['rpacket_t_wx_card_id'];
		$this->returnJson(0,'sucess',$signPackage);
	}
	public function lists(){

// 1：未使用  2：已使用  3：已失效  4：全部
		$status = I('request.status') ? intval(I('request.status')) : 4;
		$page = intval(I('post.page')) ? intval(I('post.page')) : 1;
		$limit = 10;
		$start = ($page-1)*$limit;
		$num = CouponService::getRedpacketTempCount('',array('rpacket_t_wx'=>1));
		$havepage = ceil($num/$limit);
		$list = CouponService::getRedpacketTempList('',array('rpacket_t_wx'=>1),$start,$limit);

		if($list){
			foreach($list as $k => $v){
				$arr_id[] = $v['rpacket_t_id'];
			}
			$whereid = array('in',implode(',',$arr_id));
			$number = CouponService::getCouponCount($this->member_info['member_id'],$whereid);
			foreach ($number as $key => $value) {
				$newnumber[$value['rpacket_t_id']] = $value['number'];
			}
			foreach($list as $k => $v){
				$list[$k]['number'] = $newnumber[$v['rpacket_t_id']]?$newnumber[$v['rpacket_t_id']]:0;
				$list[$k]['t_start'] = date('Y.m.d',$v['rpacket_t_start_date']);
				$list[$k]['t_end'] = date('Y.m.d',$v['rpacket_t_end_date']);
				if($v['rpacket_t_eachlimit'] != 0){
					$list[$k]['limit_num'] = '限领'.$v['rpacket_t_eachlimit'].'张';
					$list[$k]['status'] = $newnumber[$v['rpacket_t_id']]<$v['rpacket_t_eachlimit']?0:1;//0表示可以领取，1表示不能领取
				}else{
					$list[$k]['limit_num'] = '不限领取';
					$list[$k]['status'] = 0;
				}

			}

		}else{
			$list = array();
		}
		$data['list'] = $list;
		$data['status'] = $status;
		$data['havepage'] = $havepage;
		$data['page'] = $page;
		$this->returnJson(0,'sucess',$data);
	}
	public function CheckCodeSign(){
		$key = I('param.key');
		$rpacket_t_id = intval(I('post.id'));
		if(!$rpacket_t_id){
			$this->returnJson(1,'参数错误！');
		}
		$info = CouponService::getRedpacketTempList($rpacket_t_id);
		if(!empty($info['rpacket_t_wx_card_id'])){

			$this->returnJson(0,'sucess',0);
		}else{
			$this->returnJson(1,'正常跳转！');
		}
	}
	//登录用户绑定微信
	public function UserBindWx(){
		$member_uid = $_COOKIE['uid'];
		$id = I('get.id');
		$type= I('get.type');
		if($type == 'wx'){
			$redirect =  empty($id) ? $this->wap.'coupon-wxlist.html?typewx': $this->wap.'coupon-wxlist.html?type=wx&wxCoupid='.$id;
		}else{
			$redirect =  empty($id) ? $this->wap.'coupon-all.html': $this->wap.'coupon-all.html?wxCoupid='.$id;

		}
		if(!empty($member_uid)){
			$member_info = MemberService::getMemberInfo(array('member_id' => $member_uid));
			if(empty($member_info['weixin_openid'])){
				$openid =  I('get.openid') ? I('get.openid') : $this->getopenid('UserBindWx',$id);
				$data = array();
				if(!empty($openid)){
					$data['weixin_openid'] = $openid;
					$data['member_id'] = $member_info['member_id'];
					if(empty($member_info['member_truename'])){
						$data['member_truename'] = $this->getwxinfo($openid,'nickname');
					}
					if(MemberService::editMember($data)){
						header('Location: '.$redirect);
					}
				}
			}else{
				header('Location: '.$redirect);
			}
		}else{
			header('Location: '.$redirect);
		}


	}
	public function getopenid($action,$id){
		$model =new SettingModel();
		$appid = $model->getSetting('wx_AppID');
		$scheme = strtolower($_SERVER['HTTPS']) == "on" ? 'https' : 'http';
		$server_name = $scheme.'://'.$_SERVER["SERVER_NAME"];
		$REDIRECT_URI = $server_name.'/api.php/CouponWx/getcode?action='.$action.'&id='.$id;
		$scope = 'snsapi_userinfo';//需要授权
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . urlencode($REDIRECT_URI) . '&response_type=code&scope=' . $scope . '&state=STATE#wechat_redirect';
		header("Location:" . $url);
		die;

	}
	public function getcode(){
		$code = $_GET["code"];
		$action = $_GET["action"];
		$id = $_GET["id"];
		$model =new SettingModel();
		$appid = $model->getSetting('wx_AppID');
		$appsecret =$model->getSetting('wx_AppSecret');
		$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
		$userinfo = http_send_post($get_token_url);
		$userinfo = json_decode($userinfo,true);
		$openid = $userinfo['openid'];
		cookie('wx_openid', $openid);
		header("Location:" . '/api.php/CouponWx/'.$action.'?openid='.$openid.'&id='.$id);
		exit;

	}
	//获取微信用户信息
	public function getwxinfo($openid,$type){
		$setingMoudel = new SettingModel();
		$wx_AppID = $setingMoudel->getSetting('wx_AppID');
		$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
		$wxapi = new WxapiController($wx_AppID,$wx_AppSecret);
		$wxinfo = $wxapi->getinfo($openid);

		return $wxinfo[$type];
	}
}