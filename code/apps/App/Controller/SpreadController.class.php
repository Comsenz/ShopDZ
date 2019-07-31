<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\SpreadService;
use Common\Service\BankService;
use Common\Service\SmsService;
use Common\Service\IndexService;
use Common\Service\MemberService;
use Common\PayMent\WxPay\WxRefundUser;
use Admin\Model\SettingModel;
/*
url = ApiUrl+'/member/sendbankseccode';
$type=5;
*/
class SpreadController extends BaseController {
	public $wap_url = '';
	public function _initialize() {
		$this->getMember();
		$this->wap_url= C('WAP_URL');

	}
	
	//发送注册短信验证码
	public function sendSeccode() {
		$mobile = I('param.mobile');
		$member_mobile = $this->member_info['member_mobile'];
		if($mobile != $member_mobile) {
			$this->returnJson(1,'手机号码不对');
		}
		$verify_status =IndexService::getSettings('verify_status');
		if($verify_status) {
			$r = $this->checkVerifyCode();
			if(!$r) {
				$this->returnJson(1,'图形验证码错误');
			}
		}
	    if(empty( $mobile )) {
	    	$this->returnJson(1,'手机号不存在');
	    } else {
			$this->sendSMS(5);
	    }
	}

	/*
	*微信提现申请
	*/
	
	public function WxUserGetRefund(){

		$cash_amount = I('param.cash_amount');
		$key = I('param.key');
		$this->getopenid($key,$cash_amount);
	}
	//用户提现
	public function usergetwx($openid,$cash_amount,$cash_sn){
		$wxpay = new  WxRefundUser($openid,$cash_amount,$cash_sn);
        $res =$wxpay->UserRefund();
		if($res['result_code'] == 'SUCCESS'){
        	$withdraw = SpreadService::getWithdrawdDtail($cash_sn,$this->member_info['member_id']);
        	$data = array();
        	$data['status'] = 3;
        	$data['cash_id'] = $withdraw['cash_id'];
        	$data['remark'] = '自动支付:'.date('Y-m-d H:i:s',TIMESTAMP);
        	$data['notify_mark'] = '微信自动提现';
        	$data['enddate'] = TIMESTAMP;
        	$data['cash_amount'] = $cash_amount;
        	$savewithdraw = SpreadService::UpdateWithdraw($this->member_info['member_id'],$data);
        	if($savewithdraw){
        		return true;
        	}else{
        		return false;
        	}
        }else{
			return false;
        }
	}
	public function getopenid($key,$cash_amount){
		$model =new SettingModel();
		$appid = $model->getSetting('wx_AppID');
		$scheme = strtolower($_SERVER['HTTPS']) == "on" ? 'https' : 'http';
		$server_name = $scheme.'://'.$_SERVER["SERVER_NAME"];
		$REDIRECT_URI = $server_name.'/api.php/spread/getcode?key='.$key.'&cash_amount='.$cash_amount;
		//$scope='snsapi_base';
		$scope = 'snsapi_userinfo';//需要授权
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . urlencode($REDIRECT_URI) . '&response_type=code&scope=' . $scope . '&state=STATE#wechat_redirect';
		//$url = 'weixin://open.weixin.qq.com/connect/qrconnect?appid='.$appid.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state=STATE#wechat_redirect';
		header("Location:" . $url);
		die;

	}
	public function getcode(){
		$code = $_GET["code"];
		$cash_amount = $_GET["cash_amount"];
		$key = $_GET["key"];
		$model =new SettingModel();
		$appid = $model->getSetting('wx_AppID');
		$appsecret =$model->getSetting('wx_AppSecret');
		$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
		$userinfo = http_send_post($get_token_url);
		$userinfo = json_decode($userinfo,true);
		$openid = $userinfo['openid'];

		if(empty($cash_amount) || !is_numeric($cash_amount)){
			$this->sendto('error','参数错误');
		}

		$setting_spread = IndexService::getSetting('spread');
		$setting_spread = $setting_spread ? unserialize($setting_spread):'';
		$min_spread = $setting_spread['minprice'];
		$max_spread = $setting_spread['maxprice'];
		if( $cash_amount < $min_spread || $cash_amount > $max_spread || $cash_amount < 1){
			$this->sendto('error','请按照提现说明填写提现金额');
		}
		$account = SpreadService::account($this->member_info['member_id']);
		$settlement_price = $account['settlement_price'];
		if( $settlement_price < $cash_amount ) {
			$this->sendto('error','可提现金额不足');
			
		}
		$member_info = MemberService::getMemberInfo(array('weixin_openid' => $openid));
		$data = array();
		$data['member_uid'] = $this->member_info['member_id'];
		$data['member_name'] = $this->member_info['member_truename'];
		$data['member_mobile'] = $this->member_info['member_mobile'];
		if(!empty($member_info)){
			if($member_info['member_username'] == $data['member_username']){
				$this->wxGetCash($cash_amount,$openid);
			}else{
				$this->sendto('error','对不起该微信账号已经与其他账号绑定，请用与该微信号绑定的账户登录！或登录原绑定账号解除绑定，与该账户重新绑定即可');
			}
		}else{
			$up_data = array();
			$up_data['member_uid'] = $this->member_info['member_id'];
			$up_data['member_name'] = $this->member_info['member_truename'];
			$up_data['weixin_openid'] = $openid;
			if(MemberService::editMember($up_data)){
				if($member_info['member_username'] == $data['member_username']){
					$this->wxGetCash($cash_amount,$openid);
				}else{
					$this->sendto('error','对不起该微信账号已经与其他账号绑定，请用与该微信号绑定的账户登录！或登录原绑定账号解除绑定，与该账户重新绑定即可');
				}
			}else{
				$this->sendto('error','绑定微信失败');
			}

		}

	}

	public function wxGetCash($cash_amount,$openid){
		$setting_spread = IndexService::getSetting('spread');
		$setting_spread = $setting_spread ? unserialize($setting_spread):'';
		$random = \Common\Helper\LoginHelper::random(2,$numeric=1);
		$cash_sn = date('YmdHis').$random;
		$data['member_uid'] = $this->member_info['member_id'];
		$data['cash_sn'] = $cash_sn;
		$data['status'] = 1;
		$data['cash_amount'] = $cash_amount;
		$data['add_time'] = TIMESTAMP;
		$data['bank_no'] = $openid;
		$data['bank'] = '微信钱包';
		$data['bank_name'] = $this->member_info['member_truename'];
		$data['type'] = 2;
		$res =  SpreadService::withdraw($data);
		switch($res) {
			case 0:
				/* 判断微信提现设置 */
				if($setting_spread['wx_withdraw_status'] == 1 && $setting_spread['wx_withdraw_audit_status'] == 0){
					$wx = $this->usergetwx($openid,$cash_amount,$cash_sn);
					if($wx){
						$this->sendto('success','提现成功资金已到账');
					}else{
						$this->sendto('error','提现失败，余额不足请联系管理员');

					}
				}else{
					$this->sendto('success','申请成功，请等待管理员审核');
				}
				break;
			case 1:
				$this->sendto('error','未知错误，请联系管理员！');

				break;
			case 2:
				$this->sendto('error','可提现奖励不足');

				break;
		}
	}
	/*
	*提现申请
	*/

	public function withdraw() {

		$cash_amount = I('param.cash_amount');
		if(empty($cash_amount) || !is_numeric($cash_amount))
			$this->returnJson(1,'缺少参数');
		$setting_spread = IndexService::getSetting('spread');
		$setting_spread = $setting_spread ? unserialize($setting_spread):'';
		$min_spread = $setting_spread['minprice'];
		$max_spread = $setting_spread['maxprice'];
		if( $cash_amount < $min_spread || $cash_amount > $max_spread ){
			$this->returnJson(1,'请按照提现说明填写提现金额');
		}
		$account = SpreadService::account($this->member_info['member_id']);
		$settlement_price = $account['settlement_price'];
		if( $settlement_price < $cash_amount ) {
			$this->returnJson(1,'可提现金额不足');
		}

		$data['member_uid'] = $this->member_info['member_id'];
		$data['member_name'] = $this->member_info['member_username'];
		$data['member_mobile'] = $this->member_info['member_mobile'];
		$random = \Common\Helper\LoginHelper::random(2,$numeric=1);
		$cash_sn = date('YmdHis').$random;
		$data['cash_sn'] = $cash_sn;
		$data['status'] = 1;
		$data['cash_amount'] = $cash_amount;
		$data['add_time'] = TIMESTAMP;
		$mybankinfo = BankService::getMyBankInfoByUid($this->member_info['member_id']);
		if(empty($mybankinfo)) {
			$this->returnJson(2,'您还未绑定银行卡，快去绑定银行卡去吧');
		}
		$data['bank_no'] = $mybankinfo['bank_no'];
		$data['bank'] = $mybankinfo['name'];
		$data['bank_name'] = $mybankinfo['bank_name'];
		$res =  SpreadService::withdraw($data);
		switch($res) {
			case 0:
					$this->returnJson(0,'sucess',111);
				break;
			case 1:
					$this->returnJson(1,'提现失败，请联系管理员！');
				break;
			case 2:
					$this->returnJson(1,'可提现奖励不足');
				break;
		}
	}
	public function sendto($type,$content){
		header('Location: '.$this->wap_url.'refundsuccess.html?content='.$content.'&t='.$type);
		exit;
	}
	
	function getcashamount() {
		$uid = $this->member_info['member_id'];
		$data = SpreadService::account($uid);
		$this->returnJson(0,'sucess',$data);
	}
	/*
	*提现详情
	*/
	function getwithdrawdetail() {
		$cash_sn = I('param.cash_sn');
		if(empty($cash_sn))
			$this->returnJson(1,'缺少参数');
		$uid = $this->member_info['member_id'];
		$data = SpreadService::getWithdrawdDtail($cash_sn,$uid);
		$this->returnJson(0,'sucess',array('withdraw'=>$data));
	}
	/*
	*提现列表
	*/
	function getwithdraw() {
		$uid = $this->member_info['member_id'];
		$page = I('param.page',1,'intval');
		$prepage = I('param.prepage',8,'intval');
		$data = SpreadService::getMyWithdraw($uid,$status = null,$page,$prepage);
		$this->returnJson(0,'sucess',array('list'=>$data));
	}
	/*
	*设置个人银行
	*/
	function setbank() {
		$seccode = I('param.seccode');
		$name = I('param.name');
		$bank_no = I('param.bank_no');
		$bank_name = I('param.bank_name');
		if(empty($name) || empty($bank_no) || empty($bank_name) || empty($seccode)){
			$this->returnJson(1,'缺少参数');
		}
		$res = SmsService::check_sms($this->member_info['member_mobile'],$type = 5,$seccode);
		if($res['error']) {
			$this->returnJson(1,$res['error']);
		}
/* 		$bank_info = BankService::getBankById($bank_id);
		if(empty($bank_info)){
			$this->returnJson(1,'请选择银行');
		} */
		$uid = $this->member_info['member_id'];
		$mybankinfo = BankService::getMyBankInfoByUid($uid);
		$data = array(
			'id' =>$mybankinfo['id'],
			'bank_id' =>0,
			'bank_no' =>$bank_no,
			'name' =>$name,
			'bank_name' =>$bank_name,
			'member_uid' =>$uid,
			'add_time' =>TIMESTAMP,
		);
		
		$res = BankService::createMemberBankInfo($data);
		if($res)
			$this->returnJson(0,'sucess',array('bank'=>$data));
		$this->returnJson(1,'请重试');
	}
	
	function getbank() {
		$uid = $this->member_info['member_id'];
		$mybank = BankService::getMyBankInfoByUid($uid);
		$member['member_mobile'] = $this->member_info['member_mobile'];
		/* 获取提现系统设置 */
		$value = IndexService::getSetting('spread');
		$spread = unserialize($value);
		$spread = $spread ? $spread : array();
		$setting = array(
				'wx_withdraw_status'=>$spread['wx_withdraw_status'],
				'wx_withdraw_audit_status'=>$spread['wx_withdraw_audit_status'],
				'maxprice'=>$spread['maxprice'],
				'minprice'=>$spread['minprice']
			);
		/* 查询上次使用的提现方式 */
		$bank_mode = M('spread_withdraw_cash')
					->where('member_uid=%d',$this->member_info['member_id'])
					->order('cash_id desc')
					->limit(1)
					->select();
		/* 拼装上次提现方式的数据 */
		$bankinfo = array();
		if(!empty($bank_mode)){
			$bankinfo['type'] = $bank_mode[0]['type'];
			if($bankinfo['type'] == 1){
				$bankinfo['name'] = $mybank['name'];
				$bankinfo['bank_no'] = $mybank['bank_no'];
			}elseif($bankinfo['type'] == 2){
				$bankinfo['name'] = '微信钱包';
				$bankinfo['bank_no'] = '';
			}
		}
		$this->returnJson(0,'sucess',array('member'=>$member,'bankinfo'=>$bankinfo,'bank'=>$mybank,'banksetting'=>$setting));
	}
	/*
	*推广中心
	*/
	function account() {
		$uid = $this->member_info['member_id'];
		

		$data = SpreadService::account($uid);
		if(empty($data)){
			$this->returnJson(1,'success',$data);
		}else{
			$data['member_mobile'] = $this->member_info['member_mobile'];
			$this->returnJson(0,'success',$data);
		}
	}
	/*
	*收入明细
	*/
	function getmypresales() {
		$uid = $this->member_info['member_id'];
		$page = I('param.page',1,'intval');
		$prepage = I('param.prepage',8,'intval');
		$data = SpreadService::getMyPresales($uid,null,$page,$prepage);
		$this->returnJson(0,'sucess',$data);
	}
	
	function explain() {
		$value = IndexService::getSetting('spread');
		$spread = unserialize($value);
		$spread = $spread ? $spread : array();
		$data= array(
				'maxprice'=>$spread['maxprice'],
				'minprice'=>$spread['minprice'],
				'content'=>htmlspecialchars_decode($spread['content']),
			);
		$this->returnJson(0,'sucess',$data);
	}
}