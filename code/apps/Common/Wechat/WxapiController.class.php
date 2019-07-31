<?php

namespace Common\Wechat;
use \Exception;
use Think\Controller;
use \Admin\Model\SettingModel;

class WxapiController{
    public $token;//我们站的token值
    public $db;
    public $appid=false;
    public  $appkey=false;
    public $access_token = false;
    public function __construct($appid,$appkey,$clean = 0){

        $this->appid = $appid;
        $this->appkey = $appkey;
        $time = time();
        if(!($appid&&$appkey)){
            die('获取access_token的时候appid和appsecret为空');
        }
        $setting = D('Setting');
        $setingMoudel = new SettingModel();
        if(!$clean){
            $access_token = $setingMoudel->getSetting('wx_access_token');
            $access_token =trim( $access_token);
        }

        $wx_timeout = $setingMoudel->getSetting('wx_access_token_timeout');

        if(empty($access_token)){
            $access_token = $this->getaccesstoken($this->appid,$this->appkey);
            if(!$access_token){
                die('获取accesstoken失败');
            }
            $this->access_token = $access_token;
            $setting->where(array('name'=>'wx_access_token_timeout'))->save(array('value'=>$time));
            $setting->where(array('name'=>'wx_access_token'))->save(array('value'=>$this->access_token));
        }else if($time-$wx_timeout>6000){
            $access_token = $this->getaccesstoken($this->appid,$this->appkey);
            if(!$access_token){
                throw new \Exception('获取accesstoken失败');
            }
            $this->access_token = $access_token;
            $setting->where(array('name'=>'wx_access_token_timeout'))->save(array('value'=>$time));
            $setting->where(array('name'=>'wx_access_token'))->save(array('value'=>$this->access_token));
            S('DB_CONFIG_DATA',null);
        }else{
            $this->access_token = $access_token;
        }
        if(!$this->access_token){
            throw new \Exception('Wxapi这个文件出bug了');
        }

    }

    /**
     * 主动给用户发消息
     */
    public function sendmessage($data){

        $ch = curl_init();//初始化curl
        curl_setopt($ch,CURLOPT_URL,'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->access_token);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(json_encode($data)));
        $result= curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    /**
     * 微信二维码
     */
    public function getcode($getcode,$type=1){
        $type  = $type==2?2:1;
        $data = array(
            '1'=>'{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$getcode.'}}}',
            '2'=>'{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$getcode.'}}}',
        );
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->access_token;
        $result = $this->post($url,$data[$type]);
        return json_decode($result,true);
    }
    /**
     * 生成菜单
     * */
    public function createMenu($menu){
        // print_r($menu);exit;
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->access_token;
        $result =$this->post($url,$menu);
        return json_decode($result,true);
    }

    /**
     * 发送模板微信
     */
    public function sendNews($data){
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->access_token;
        $result =$this->post($url,$data);
        return json_decode($result,true);

    }

  
        /**
     * 发送微信通知
     */
    public function send_wx_notice($mobile,$content){
        if(!empty($mobile) && !empty($content)){
            $member_info = D('member')->where(array('member_id'=>$mobile))->field('weixin_openid')->find();
            if(!empty($member_info['weixin_openid'])){
                $data['touser'] = $member_info['weixin_openid'];
                $data['msgtype'] = 'text';
                $data['text']['content'] = urlencode($content);
                $re = $this->sendmessage($data);
            }
        }
    }
    public function send_wx_admin($openid,$content){
        $data['touser'] = $openid;
        $data['msgtype'] = 'text';
        $data['text']['content'] = urlencode($content);
        $re = $this->sendmessage($data);
    }
    /**
     * 微信素材库上传图片
     */
    public function uploadCardLogo($file)
    {
        $url =  'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->access_token.'&type=image';
        $rootfile =    str_replace('\\','/',realpath(dirname(__FILE__). '/../../../') . '/');
        $file	=	"@".$rootfile."data/Attach/wxuser/1.jpg";
        $fields	=	array("media" => $file);
        $result	= $this->post($url,$fields);
        dump($result);
    }
    /**
     * 获取单用户信息
     */
    public function getinfo($openid){
        $access_token = $this->access_token;
        if(!$access_token||!$openid){
            return false;
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $userinfo = $this->post($url);
        return json_decode($userinfo,true);
    }
    /**
     * 获取批量用户信息
     */
    public function getmroeinfo($data){
        $access_token = $this->access_token;
        if(!$access_token||!$data){
            return false;
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token='.$access_token;
        $userinfo = $this->post($url,$data);
        return json_decode($userinfo,true);
    }
    /**
     * 公众号可通过本接口来获取帐号的关注者列表
     */
    public function getfollownum($openid=''){
        $access_token = $this->access_token;
        if(!$access_token){
            return false;
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token.'';
        $result = $this->post($url);
        $userid =  json_decode($result,true);
        $userinfo = array();
        if(!in_array('errcode',$userid)){
            $data = array();
            foreach($userid['data']['openid'] as $k=>$v){
                $data['user_list'][] = array('openid'=>$v,'lang'=>'zh-CN');
            }
            $userinfo =  $this->getmroeinfo($data);
        }
        return $userinfo;
    }
    /**
     * 获取微信素材库图片
     */
    public function GetWxImgText($type= 'image',$offset = 0,$count = 20){
        $access_token = $this->access_token;
        if(!$access_token){
            return false;
        }
        $data= array('type'=>$type,'offset'=>$offset,'count'=>$count);
        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$access_token;
        $result = $this->post($url,$data);
        return json_decode($result,true);
    }

    //js接口
    public function getSignPackage($url) {
        $jsapiTicket = $this->getJsApiTicket();
        // 注意 URL 一定要动态获取，不能 hardcode.
        //$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url =$url;

        $timestamp = (string)time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId"     => $this->appid,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $setting = D('Setting');
        $setingMoudel = new SettingModel();
        $wx_JsApiTicket = $setingMoudel->getSetting('wx_JsApiTicket');
        $wx_expire_time = $setingMoudel->getSetting('wx_expire_time');
        if ($wx_expire_time < time()) {
            $accessToken = $this->access_token;
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $expire_time = time() + 6000;
                $jsapi_ticket = $ticket;
                $setting->where(array('name'=>'wx_expire_time'))->save(array('value'=>$expire_time));
                $setting->where(array('name'=>'wx_JsApiTicket'))->save(array('value'=>$jsapi_ticket));
            }
        } else {
            $ticket = $wx_JsApiTicket;
        }
        return $ticket;
    }

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    /**
     * 发起post请求
     * @param unknown_type $url
     * @param unknown_type $data
     * @return boolean|mixed
     */
    public function  post($url,$data){
        if(!$url){
            return  false;
        }
        $ch = curl_init();//初始化curl
        curl_setopt($ch,CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode_ex($data));
        $result= curl_exec($ch);
        return $result;
    }

    public function getaccesstoken($appid,$appkey){
        if(!$appid||!$appkey){
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appkey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        $result = json_decode($output,true);
        if($result['expires_in']==7200){
            return $result['access_token'];
        }else{
            return false;
        }
    }
}