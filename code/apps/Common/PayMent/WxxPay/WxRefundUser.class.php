<?php

namespace Common\PayMent\WxxPay;
use Think\Controller;
use Common\PayMent\WxxPay\WxPayDataBase;
class WxRefundUser extends WxPayDataBase{
     public $openid = '';   //用户openid
     public $amount = '';   //付款金额   
     public $partner_trade_no = ''; //商户订单号
     public $data = array();

     public function __construct($openid,$amount,$partner_trade_no){
       
        $this->openid = $openid;
        $this->amount = $amount*100;
        $this->partner_trade_no = $partner_trade_no;

        $this->values['amount']=$this->amount;
        $this->values['check_name']='NO_CHECK';
        $this->values['desc']='微信打款';//描述
        $this->values['mch_appid']=get_wxxpay_config('appid');//公众账号appid
        // $this->values['mch_appid']='wxe16254750a10c454';//公众账号appid
        $this->values['mchid']=get_wxxpay_config('mchid');//商户号
        // $this->values['mchid']='1530684301';//商户号
        $this->values['nonce_str']='qyzf'.rand(100000, 999999);//随机数;
        $this->values['openid']= $this->openid;
        $this->values['partner_trade_no']=$this->partner_trade_no;//商户订单号
        $this->values['re_user_name']='企业付款';
        $this->values['spbill_create_ip']='192.168.0.1';
        $this->values['sign']=$this->MakeSign();
        $this->data = $this->ToXml();
        
    }
    public function UserRefund(){
        return  $this->curl_post_ssl($this->data);

    }

 
  public function curl_post_ssl($data) {
        $ch = curl_init ();
        $MENU_URL="https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
        curl_setopt ( $ch, CURLOPT_URL, $MENU_URL );
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
        $zs1= APP_ROOT.'/data/Attach/'.get_wxxpay_config('sslcert_path');
        // $zs1= APP_ROOT.'/data/Attach/Pem/2019-07-10/5d255ac6ea363.pem';
        $zs2= APP_ROOT.'/data/Attach/'.get_wxxpay_config('sslkey_path');
        // $zs2= APP_ROOT.'/data/Attach/Pem/2019-07-10/5d255acc718fb.pem';
        curl_setopt($ch,CURLOPT_SSLCERT,$zs1);
        curl_setopt($ch,CURLOPT_SSLKEY,$zs2);
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        $info = curl_exec ( $ch );
        if (curl_errno ( $ch )) {
            echo 'Errno' . curl_error ( $ch );
        }
        return $this->FromXml($info);
        curl_close ($ch);
    }
}