<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\MemberService;
use Common\Service\BasketService;
use Common\Service\NoticeService;
use Common\Wechat\WxapiController;
use Common\Service\CouponService;
class WeixinController extends BaseController {
  /**
   * 商品详情
   */
	public function getLogin(){
		$js_code = I('param.code');
    $nickname = I('param.nickname');
    $avatarUrl = I('param.avatarUrl');
		$data = json_decode($this->Visit($js_code));
    $openid = $data->openid;
    $session_key = $data->session_key;
    $this->wxlogin($openid,$nickname,$avatarUrl);
    // var_dump($data);
	}
	//发送
  public function Visit($js_code){
  	$appid = 'wxe16254750a10c454';
  	$secret = '8c7c33fb528168cd2c2929c540b294d7';
    /* 
     * POST发送https请求用户信息api
    */
    $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$js_code."&grant_type=authorization_code";
    // 4. 释放curl句柄
    curl_close($ch);
    //以'json'格式发送post的https请求
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($json)){
        curl_setopt($curl, CURLOPT_POSTFIELDS,$json);
    }
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json; charset=utf-8',
      'Content-Length: ' . strlen($json)
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl); // 关闭CURL会话
    return $output;
  }

  /**
   * 微信小程序自动登录
   * 注册账户
   * 登录后绑定微信
   */
  public function wxlogin($openid,$nickname,$avatarUrl){
    $openid =  $openid;
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
          $data = $this->autoLogin($member_info);
          $this->returnJson(0,'success',$data);
        } else {
          //注册
          $post['member_passwd'] = '';
          $post['member_username'] = 'wx'. \Common\Helper\LoginHelper::random(9,1);
          $post['weixin_openid'] = $openid;
          // $member_truename = $this->getwxinfo($openid,'nickname');
          $member_truename = $nickname;
          if($member_truename){
            $nickname = json_encode($member_truename);
            $nickname = preg_replace("#(\\\ud[0-9a-f]{3})|(\\\ue[0-9a-f]{3})#ie","",$nickname);
            $member_truename = json_decode($nickname, true);
            $post['member_truename'] = $member_truename;//将emoji的unicode置为空，其他不动  
          }
          if($avatarUrl){
            $post['member_avatar'] = $avatarUrl;
          }
          $fromid = !empty($_COOKIE['fromid']) ? $_COOKIE['fromid'] : 0;
          if(!empty($fromid)){
            $post['fromid'] = $fromid;
          }
          $res = MemberService::wxaddMember($post); //注册新账户
          $member_info = MemberService::getMemberInfo(array('member_id' => $res['member_id']));
          if($member_info) {
            $this->addPoints($member_info['member_id'],$member_info['member_username']);
            $data = $this->autoLogin($member_info);
            $this->returnJson(0,'success',$data);
          }
        }
      }
    }

  }

  /**
   * 获取微信用户信息
   */
  public function getwxinfo($openid,$type){
    $wx_AppID = 'wxe16254750a10c454';
    $wx_AppSecret = '8c7c33fb528168cd2c2929c540b294d7';
    $wxapi = new WxapiController($wx_AppID,$wx_AppSecret);
    $wxinfo = $wxapi->getinfo($openid);

    return $wxinfo[$type];
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
    $data = array('member_username' => $member_info['member_username'], 'member_id' => $member_info['member_id'],'weixin_openid'=>$member_info['weixin_openid'], 'key' => $token);
    return $data;
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
}