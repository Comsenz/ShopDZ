<?php
namespace Common\Service;
use Think\Model;
use Common\Service\SendSms;
use Common\Service\IndexService;
use Admin\Model\SettingModel;
use Common\Wechat\WxapiController;
class SmsService {

    /**
     * 手机号找回密码获取短信动态码 JUAN
     */
    public function get_sms_new($phone,$log_type){

        $res['error'] = '';
        if (strlen($phone) == 11){
            $model_sms_log = M('sms_log');
            $condition = array();
            $condition['log_ip'] = get_client_ip();
            $condition['log_type'] = $log_type;
            $sms_log = $model_sms_log->where($condition)->find();
            if(!empty($sms_log) && ($sms_log['add_time'] > TIMESTAMP-600)) {
            //同一IP十分钟内只能发一条短信
                //$res['error'] = '同一IP地址十分钟内，请勿多次获取动态码！';
               // return $res;
            }
            $condition = array();
            $condition['log_phone'] = $phone;
            $condition['log_type'] = $log_type;
            $sms_log = $model_sms_log->where($condition)->find();
            if(!empty($sms_log) && ($sms_log['add_time'] > TIMESTAMP-600)) {//同一手机号十分钟内只能发一条短信
                //$res['error'] = '同一手机号十分钟内，请勿多次获取动态码！';
                //return $res;
            }
            $time24 = TIMESTAMP-60*60*24;
            $condition = array();
            $condition['log_phone'] = $phone;
            $condition['add_time'] = array('egt',$time24);
            $num = $model_sms_log->where($condition)->count();
            if($num >= 5) {//同一手机号24小时内只能发5条短信
                // $res['error'] = '同一手机号24小时内，请勿多次获取动态码！';
                // return $res;
            }
            $condition = array();
            $condition['log_ip'] = get_client_ip();
            $condition['add_time'] = array('egt',$time24);
            $num = $model_sms_log->where($condition)->count();
            if($num >= 20) {//同一IP24小时内只能发20条短信
                // $res['error'] = '同一IP24小时内，请勿多次获取动态码！';
             //     return $res;
            }
            $log_array = array();
            $member_id = 0;
            $model_member = M('member');
            $member = $model_member->where(array('member_mobile'=> $phone))->find();
            if($log_type == 1){//注册
                if(!empty($member)){
                    $res['error'] = '当前手机号已注册。';
                    return $res;
                }
            } else {
                if($log_type != 4){ //微信注册
                    if(empty($member)) {//检查手机号是否已绑定会员
                        $res['error'] = '当前手机号未注册，请检查号码是否正确。';
                        return $res;
                    }
                }
                $member_id = $member['member_id'];
            }

            $captcha = rand(100000, 999999);
            $site_name = IndexService::getSetting('shop_name');

           // $sms_arr = array(1=> 'memberlogin', 2=>)
            $type_arr = array(1 => 'memberlogin',2 => 'pwdedit',3 => 'pwdfind',4 => 'memberlogin');

            $type_arr[$log_type]='memberlogin';
            $smsid = self::getSmsTemp($type_arr[$log_type]);

            if($smsid['code'] != 200){
                $res['error'] = '短信发送失败！';
                return $res;
            }
            $cap_temp = $smsid['temp']['message_content'];
            $new_temp = self::replace_sms_key($cap_temp,array($captcha,3));
           //发送短信
            $smsdata = SendSms::sendTemplateSMSTo($phone, $new_temp);
            if($smsdata['code'] != 200) {
                $res['error'] = '手机短信发送失败 Error'.$smsdata['msg'];
                $settingmodel = new SettingModel();
                $sms_account = $settingmodel->getSetting('sms_account');
                if(empty($sms_account)){
                    $res['error'] = '手机短信发送失败！验证码为：'.$captcha;
                }
            }
            $result = 1;
            if($result){
                $condition = array(
                        'log_phone' => $phone,
                        'log_type'  => $log_type,
                        'is_use'    => 0
                    );
                $model_sms_log->where($condition)->save(array('is_use' => 1));
                $log_array['member_id'] = $member_id;
                $log_array['log_phone'] = $phone;
                $log_array['log_captcha'] = $captcha;
                $log_array['log_ip'] = get_client_ip();
                $log_array['log_text'] = $new_temp;
                $log_array['log_type'] = $log_type;
                $log_array['add_time'] = time();
                $model_sms_log->add($log_array);

                //log
                $send_array = array();
                $send_array['member_id'] = $member_id;
                $send_array['send_phone'] = $phone;
                $send_array['send_ip'] = get_client_ip();
                $send_array['send_type'] = $type_arr[$log_type];
                $send_array['send_text'] = $new_temp;
                $send_array['send_data'] = serialize(array($captcha,3));
                $send_array['add_time'] = time();
                $send_array['is_send'] = $smsdata['code'] == 200 ? 1 : 3;
                $send_array['send_time'] = time();
                $result = M('send_msg')->add($send_array);
            } else {
                $res['error'] = '手机短信发送失败';
                return $res;
            }
       
        } else {
            $res['error'] = '手机号填写错误';
            return $res;
        }
        $res['code'] = $captcha;
        return $res;
    }
    /**
     * 验证注册动态码
     * $type (短信类型:1为注册,2为修改密码,3为找回密码)
     */
    public function check_sms($phone, $type, $captcha, $s = 0){
        $res['error'] = '';
        if (strlen($phone) == 11 && strlen($captcha) == 6){
            $state = 'true';
            $condition = array();
            $condition['log_phone'] = $phone;
           // $condition['log_captcha'] = $captcha;
            $condition['log_type'] = $type;
            
            $model_sms_log = M('sms_log');
            $sms_log = $model_sms_log->where($condition)->order('add_time desc')->find();

            if(empty($sms_log) ||$sms_log['log_captcha'] != $captcha   || $sms_log['is_use'] == 1 || ($sms_log['add_time'] < TIMESTAMP-180)) {//半小时内进行验证为有效
               $res['error'] = '验证码错误或已失效';
            }
        } else {
            $res['error'] = '验证码错误';
            return $res;
        }
        if(!$res['error'] && $s) {//验证码通过设为失效
            $log_array['log_phone'] = $phone;
            $log_array['log_captcha'] = $captcha;
            $log_array['log_type'] = $type;
            $model_sms_log->where($log_array)->save(array('is_use' => 1));
        }
        return $res;
    }

    public function getSmsID($name) {
        $data = array();
        $type_arr = array('memberlogin','couponexpires','couponuse','paymentsuccess','orderpayment','goodsout','pwdfind','pwdedit','orderrefund','refundsuccess','returngoods','returngoodssuccess');
        if(!in_array($name, $type_arr)) {
            $data['code'] = 500;
            return $data;
        }
        $value = IndexService::getSetting('messagesetting');
        $temps = unserialize($value);
        $data['temp'] = $temps[$name]['temp'];
        $data['code'] = 200;
        return $data;
    }

    public function getSmsTemp($name)
    {
        $data = array();
        $type_arr = array('memberlogin','couponexpires','couponuse','paymentsuccess','orderpayment','goodsout','pwdfind','pwdedit','orderrefund','refundsuccess','returngoods','returngoodssuccess','delivergoods');
        if(!in_array($name, $type_arr)) {
            $data['code'] = 500;
            return $data;
        }
        $model = M('message');
        $sms = $model->where(array('code'=>$name))->find();
        $data['temp'] = $sms;
        $data['code'] = 200;
        return $data;
    }

    /**
      * 发送通知类短信
      * @param $member_id 用户ID
      * @param $phone 手机号码集合,用英文逗号分开
      * @param $datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
      * @param $type 短信类型
      */
    public function insert_sms_notice($member_id,$phone,$datas,$type) {
        $is_send = 0;
        $smstemp = self::getSmsTemp($type);
        if($type == 'orderpayment'){
            $is_send = 4;
        }
		if(empty($phone)){
            $phone = 'wx';
		}
    
        $send_text = '';
        if($smstemp['temp']['message_content'] != 1 && $smstemp['temp']['message_states'] == 1){
           $send_text =  self::replace_sms_key($smstemp['temp']['message_content'],$datas);
        }

        $send_wxtext = '';
        
        if($smstemp['temp']['wx_content'] != 1 && $smstemp['temp']['wx_states'] == 1){
           
           $send_wxtext =  self::replace_sms_wxweb($smstemp['temp']['wx_content'],$datas);
        }
        $send_webtext =  '';
        if($smstemp['temp']['web_content'] != 1 && $smstemp['temp']['web_states'] == 1){
          
           $send_webtext =  self::replace_sms_wxweb($smstemp['temp']['web_content'],$datas);
        }

        $m = M('send_msg');
        $log_array = array();
        $log_array['member_id'] = $member_id;
        $log_array['send_phone'] = $phone;
        $log_array['send_ip'] = get_client_ip();
        $log_array['send_type'] = $type;
        $log_array['send_text'] = $send_text;
        $log_array['send_wxtext'] = $send_wxtext;
        $log_array['send_webtext'] = $send_webtext;
        $log_array['send_data'] = serialize($datas);
        $log_array['add_time'] = time();
        $log_array['is_send'] = $is_send;
        $log_array['send_time'] = 0;
        $result = $m->add($log_array);
        if($result){
            return true;
        }else{
            return false;
        }
    }
  public function sendWxAdmin($datas){
      $type = 'delivergoods';
      $smstemp = self::getSmsTemp($type);
      $model = M('admin');
      $admin_info = $model->where(array('statues'=>'on'))->select();
      if($smstemp['temp']['wx_content'] != 1 && $smstemp['temp']['wx_states'] == 1){
          if(!empty($admin_info)){
              foreach($admin_info as $k=>$v){
                  if(!empty($v['open_id'])){
                      $wx_AppID = IndexService::getSetting('wx_AppID');
                      $wx_AppSecret = IndexService::getSetting('wx_AppSecret');
                      $wxapi = new WxapiController($wx_AppID,$wx_AppSecret);

                      $content = self::replace_sms_wxweb($smstemp['temp']['wx_content'],$datas);
                      $wxapi->send_wx_admin($v['open_id'],$content);
                  }
              }
          }
      }

      return true;
  }
  public  function replace_sms_wxweb($temp,$replace){
       
        $site_name = IndexService::getSetting('shop_name');
        $temp = str_replace('【shop_name】','【'.$site_name.'】',$temp);
         foreach($replace as $k => $v){
            $rep = '{'.$k.'}';
            $temp = str_replace($rep,$v,$temp);
        }

        return $temp;

    }
 
    public function replace_sms_key($temp,$replace){
        $notice = '';
        $site_name = IndexService::getSetting('shop_name');
        $temp = str_replace('【@】','【'.$site_name.'】',$temp);
        $need = array();
        $replace = array_values($replace);
       
        ksort($replace);
        foreach($replace as $k => $v){
            $need[$k] = '/@/';
        }
        $notice = preg_replace($need,$replace,$temp,1);
       
        return $notice;
    }

    public function get_overage(){
        $shop_name = IndexService::getSetting('shop_name');
        $model = new SettingModel();
        $sms_account = $model->getSetting('sms_account');
        $sms_password = $model->getSetting('sms_password');
        $url = 'https://dx.ipyy.net/smsJson.aspx';
        $data = array(
            'userid'    => $shop_name,
            'account'   => $sms_account,
            'password'  => strtoupper(md5($sms_password)),
            'action'    => 'overage',
        );
        $result = send_post($url,$data);
        $result = json_decode($result,true);
        if(strtolower($result['returnstatus']) == 'success'){
            $smsdata['code'] = 200;
            $smsdata['overage'] = $result['overage'];
            $smsdata['sendtotal'] = $result['sendTotal'];
        }else{
            $smsdata['code'] = 500;
            $smsdata['overage'] = '查询失败！';
            $smsdata['sendtotal'] = '查询失败！';
        }
        return $smsdata;
    }

    public function smslog_count($con=array()){
        $end = time();
        $start = strtotime(date('Y-m-d')) - 86400*15;
        $where = array();
        $where['is_send'] = 1;
        $where['send_time'] = array('between',array($start,$end));
        if(!empty($con)){
            $where = array_merge($where,$con);
        }
        $log = M('send_msg')->where($where)->count();
        return $log;
    }

    public function smslog($con=array(),$limit){
        $end = time();
        $start = strtotime(date('Y-m-d')) - 86400*15;
        $where = array();
        $where['is_send'] = 1;
        $where['send_time'] = array('between',array($start,$end));
        if(!empty($con)){
            $where = array_merge($where,$con);
        }
        $log = M('send_msg')->where($where)->limit($limit)->order('send_time DESC')->select();
        return $log;
    }
      
}