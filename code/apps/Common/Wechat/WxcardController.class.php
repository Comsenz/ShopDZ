<?php
namespace Common\Wechat;
use Think\Controller;
use \Admin\Model\SettingModel;
class  WxcardController  extends  WxapiController{
    public function __construct($appid, $appkey){
        parent::__construct($appid, $appkey);
    }
    /**
     * 上传微信logo
     */
    public function uploadCardLogo($file=''){
        $url =  'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$this->access_token;
        $data['buffer'] ='@'.$file;
        $data['access_token '] = $this->access_token;
        $result = $this->post_img($url, $data);
        $data = json_decode($result,true);
        if(empty($data)){
            return  false;
        }
        if(trim($data['url'])){
            return  $data['url'];
        }
        return false;
    }

    
    /**
     * 创建卡券模板
     * @param unknown_type $card
     */
    public function  createCardTemplate($card){
        $url =  'https://api.weixin.qq.com/card/create?access_token='.$this->access_token;
        $result = $this->post($url,$card);
        $result = json_decode($result,true);
        if($result['errcode']==0 && $result['card_id']){
            return $result['card_id'];
        }else{
            return false;
        }
    }

    /**
     * 修改卡券信息
     * @param unknown_type $card
     */
    public function  editCardInfo($card){
        $url =  'https://api.weixin.qq.com/card/update?access_token='.$this->access_token;
        $result = $this->post($url,$card);
        $result = json_decode($result,true);
        if($result['errcode']== 0 ){
            return $result;
        }else{
            return $result;
        }
    }
    /**
     * 更新卡券库存
     * */
    public function  editCardTotal($cardid,$total){
        $url =  'https://api.weixin.qq.com/card/modifystock?access_token='.$this->access_token;
        $card =array();
        $card['card_id'] = $cardid;
        if($total > 0){
            $card['increase_stock_value'] = $total;
        }else{
            $card['reduce_stock_value'] = abs($total);
        }
        $result = $this->post($url,$card);
        $result = json_decode($result,true);
        if($result['errcode']== 0 ){
            return $result;
        }else{
            return $result;
        }
    }
    /**
    *删除卡券
     */
    public function delCard($cardid){
        $url =  'https://api.weixin.qq.com/card/delete?access_token='.$this->access_token;
        $card['card_id'] = $cardid;
        $result = $this->post($url,$card);
        $result = json_decode($result,true);
        if($result['errcode']== 0 ){
            return $result;
        }else{
            return $result;
        }
    }
    /**
     * 自定义code 设置用户优惠券失效
     * */
    public function setUserCardUnavailable($cardid,$code){
        $url =  'https://api.weixin.qq.com/card/code/unavailable?access_token='.$this->access_token;
        $card['card_id'] = $cardid;
        $card['code'] = $code;
        $result = $this->post($url,$card);
        $result = json_decode($result,true);
        if($result['errcode']== 0 ){
            return $result;
        }else{
            return $result;
        }
    }
    public function  getStoreList(){
        $url =      'https://api.weixin.qq.com/cgi-bin/poi/getpoilist?access_token='.$this->access_token;
        $res = $this->post($url, '{"begin":0,"limit":10}');
        p(json_decode($res,trrue));
        p($res);
        
    }
    public function  post_img($url,$strPOST){
        $oCurl = curl_init ();
        curl_setopt ( $oCurl, CURLOPT_SAFE_UPLOAD, false);
        if (stripos ( $url, "https://" ) !== FALSE) {
            curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
            curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );
        }

        curl_setopt ( $oCurl, CURLOPT_URL, $url );
        curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $oCurl, CURLOPT_POST, true );
        curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $strPOST );
        $sContent = curl_exec ( $oCurl );
        $aStatus = curl_getinfo ( $oCurl );
        curl_close ( $oCurl );
        if (intval ( $aStatus ["http_code"] ) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }
    
    /**
     * 获取 jssdk微信卡券的ticket
     */
    public function  getcardticket(){
        if(!$this->access_token){
            return false;
        }

       $setingMoudel = new SettingModel();
       $oldticket = $setingMoudel->getSetting('cardapi_ticket');
       $lastrefushtime = $setingMoudel->getSetting('cardapi_ticket_time');
       if($oldticket&&(time()-$lastrefushtime<6000)){
           return  $oldticket;
       }else{
            $data = file_get_contents('http://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$this->access_token.'&type=wx_card');
            $data =  json_decode($data,true);
            if(empty($data)){
                return false;
            }
            if($data['errcode']!=0){
                return false;
            }
            $update_array['cardapi_ticket'] = $data['ticket'];
           $update_array['cardapi_ticket_time'] = time();
            $setingMoudel -> Settings($update_array);
            return    $data['ticket'];

      }
            
    }

    public function getCardSign($param){
        sort($param,SORT_STRING);
        $str = '';
        $first = 1;
        foreach($param as $k=>$v){
            $str.="$v";
        }
        return    sha1($str);

    }

    //核销卡券
    public function  use_card($code,$card_id){
        $url = 'https://api.weixin.qq.com/card/code/consume?access_token='.$this->access_token;
        if(!$code||!$card_id){
            return false;
        }
        $data  = array(
                'code'=>$code,
                'card_id'=>$card_id,
                );
        $result = $this->post($url,$data);
        $result =  json_decode($result,true);
        if($result['errcode']==0&&$result['errmsg']=='ok'){
             return true;
        }else{
            return false;
        }
    }
    
    
}