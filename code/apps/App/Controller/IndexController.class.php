<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\IndexService;
use Common\Service\ExpressService;

class IndexController extends BaseController {

	//首页
	public function index(){
		$data = IndexService::getSpecialItem();
        if(!$data){
            $this->returnJson(1,'首页内容未设置');
        }
      
        $this->returnJson(0,'sucess',array('special_item_list' => $data));

	}

    public function getSetting() {
        $settings = array();
        $settings['footer_setting'] =IndexService::getSetting('record_number');
        $settings['shop_logo'] = IndexService::getSetting('shop_logo');
        if(empty($settings['shop_logo'])){
            $settings['shop_logo'] = DEFAULT_LOGO_IMAGE;
        }
        if(!$settings){
            // $this->returnJson(1,'获取失败。');
        }
        $this->returnJson(0,'success', $settings);
    }
    public function getSettings() {
        $setting = array('wx_sharedesc','wx_share','wx_sharetitle','shop_name','web_introduce','shop_logo','shop_member','record_number','enterprise_contact','shop_login','statistics_code','footer_info','wx_shareimg','wx_login','wx_share','verify_status','smg_login','spread','payment');
        $result =IndexService::getSettings($setting);
        if(!empty($result['shop_logo'])){
            $result['shop_logo'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$result['shop_logo'];
        } else {
            $result['shop_logo'] = C('TMPL_PARSE_STRING.DEFAULT_LOGO_IMAGE');
        }
        if(!empty($result['wx_shareimg'])){
            $result['wx_shareimg'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$result['wx_shareimg'];
        } else {
            $result['wx_shareimg'] = C('TMPL_PARSE_STRING.DEFAULT_LOGO_IMAGE');
        }
        if(!empty($result['shop_login'])){
            $result['shop_login'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$result['shop_login'];
        } else {
            $result['shop_login'] = C('TMPL_PARSE_STRING.DEFAULT_LOGIN_IMAGE');
        }
        if($result['statistics_code']){
            $result['statistics_code'] = htmlspecialchars_decode($result['statistics_code']);
        }
        $result['spread'] = unserialize($result['spread']);
        $this->returnJson(0,'success', $result);

    }

}