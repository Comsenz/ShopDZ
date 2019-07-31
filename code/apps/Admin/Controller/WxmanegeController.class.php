<?php
// +----------------------------------------------------------------------
// | Comsenz
// +----------------------------------------------------------------------
// | Copyright (c) 2016 All rights reserved.
// +----------------------------------------------------------------------
// | Author: leiyu <171550539@vip.qq.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;
use Common\Wechat\WechatController;
use Admin\Model\SettingModel;
use Common\Service\CouponService;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class WxmanegeController   {

    /**
     * 首页
     */
	public $conf = array();
	public function __construct() {
		$model = new SettingModel();
		$this->conf = $model -> getSettings();
		$this->data = array();
	}

    public function index() {

    	$this->token = $_GET['token'];
    	$this->wechat  = new WechatController($this->token);
		//file_put_contents('/data/www/wx.tyckjgs.com/Application/Wechat/Controller/aaa.txt',var_export($this->data,true),FILE_APPEND);
		$this->data  = $this->wechat->request();
    	list($content, $type) = $this->reply();
    	$this->wechat->response($content, $type);

    }

   	public function  reply(){
		//事件回复
		if($this->data['MsgType'] == 'event'){
			switch ($this->data['Event'])
				{
				case 'subscribe':
					//file_put_contents('/data/www/html/shopdz/88888.txt',var_export($this->data,true),FILE_APPEND);
						//$str = '你的上级id是'.$this->data['EventKey'].'你的openid是'.$this->data['FromUserName'];
					 $str =  $this->comrep($this->data);
					 return  array($str,'text');
				 break;

				case  'user_get_card':
					//当用户领取了卡券的时候把这个卡券的状态改成 1.
					$openid = $this->data['FromUserName'];
					$CardId = $this->data['CardId'];
					$code = $this->data['UserCardCode'];
					$condition = array('rpacket_t_wx_card_id'=>$CardId);
					$temp =  CouponService::getRedpacketTempList(NULL,$condition);
					$getcard =  CouponService::getWxEachLimit($openid,$CardId,$code);
					$model = new SettingModel();
					$shop_name = $model->getSetting('shop_name');
					$urlTo = SITE_URL .'api.php/Member/wxlogin?openid='.$openid.'&redirect='.SITE_URL;
					if($getcard > 0){
						return array(' ','text');
					}else{
						$result = CouponService::giveRedpacketToWx($code,$openid,$temp[0]);
						return array('恭喜您获取了一张优惠券，请您在微信 我->卡包中查看。可以在<a  href="'.$urlTo.'">'.$shop_name.'中使用</a>','text');
					}
					break;
				case  'user_gifting_card':
					$openid = $this->data['FromUserName'];
					$CardId = $this->data['CardId'];
					$code = $this->data['UserCardCode'];
					$condition = array('rpacket_t_wx_card_id'=>$CardId);
					$temp =  CouponService::getRedpacketTempList(NULL,$condition);
					$getcard =  CouponService::getWxEachLimit($openid,$CardId,$code);
					$model = new SettingModel();
					$shop_name = $model->getSetting('shop_name');
					$urlTo = SITE_URL .'/api.php/Member/wxlogin?openid='.$openid.'&redirect='.SITE_URL;
					if($getcard > 0){  //禁止微信重复提交
						$result = CouponService::delRedpacketToWx($code,$openid);
						return array('您赠送给朋友一张优惠券','text');

					}else{
						return array(' ','text');
					}
					break;
				case  'user_del_card':
					//当用户领取了卡券的时候把这个卡券的状态改成 1.
					$openid = $this->data['FromUserName'];
					$code = $this->data['UserCardCode'];
					CouponService::delRedpacketToWx($code,$openid);
					break;

				case 'CLICK':
				return 	$this->kewordsrep($this->data['EventKey'],$this->data);
				 break;

				}
		} else if($this->data['MsgType'] == 'text') {
			return  $this->kewordsrep($this->data['Content'],$this->data);


		}else{
			return  array(SITE_URL.'wap','text');
		}


   	}

	//关注回复
	public function comrep(){
		return $this->conf['wx_lookresponse'] ? $this->conf['wx_lookresponse'] : '你好欢迎关注公众号';

	}
	public function kewordsrep($keywords,$data){
		$str =$this->conf['wx_defaultresponse'] ? $this->conf['wx_defaultresponse'] : '你好！';
		if($keywords == '点击购买'){
			return array($str,'text');
		}else if($keywords == '9'){
			return array('你好','transfer_customer_service');
		}else if($keywords == '10'){
			return array($str,'text');
		}else{
			$keywords_model = M('wx_keywords');
			$keywords_data = $keywords_model->where(array('keyword'=>$keywords))->find();
			if(!empty($keywords_data)){
				if($keywords_data['isimg'] == 1){
					$data = $this->imgtextrep($keywords_data['tid']);
					//return array(json_encode($data),'text');
					if($data){
						return array($data,'news');
					}else{
						return array('暂无素材','text');
					}
				}else{
					return array($keywords_data['content'],'text');
				}
			}else{
			  return array($str,'text');
			}
			return array($str,'text');
		}
	}
	public function imgtextrep($tid){
		$info = M('wx_imgtext')->where(array('tid'=>$tid))->find();
		if($info){
			$data = unserialize($info['content']);
			$datainfo = array();
			foreach($data as $k=>$v){
				$datainfo[] = array(
						'Title'=>$v['Title'],
						'Description'=>'',
						'PicUrl'=>C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['PicUrl'],
						'Url'=>$v['Url'],
				);
			}
			return $datainfo;
		}else{
			return array();
		}

	}
		

}