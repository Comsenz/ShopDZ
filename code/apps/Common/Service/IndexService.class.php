<?php
namespace Common\Service;
use \Admin\Model\SettingModel;
use Common\Helper\CacheHelper;
class IndexService {
    
  static  function  getSpecialItem($condition = array(), $fields = '*',$order = " item_sort asc "){
  		$key = CacheHelper::getCachePre('get_special_item');
		$data =  S($key);
		if(false === $data){
			$special_model  = D('Special_item');
			$condition['item_usable'] = 1;
			$data = $special_model->where($condition)->field($fields)->order($order)->select();
			foreach ($data as $k => $v) {
			  if($v['item_type'] != 'adv_html'){
					$data[$k]['item_data']=unserialize($v['item_data']);

					//处理图片路径
					if($v['item_type'] != 'goods'){
					  foreach ($data[$k]['item_data'] as $key => $value) {
					  	if($value['img']){
							$data[$k]['item_data'][$key]['img'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$value['img'] ;
						}
					  	// var_dump($value['img']);
					  }
					}
				}else{
					$data[$k]['item_data']=stripslashes($v['item_data']);
				}
			  //如果是商品模块则需要取出商品的信息
			  if($v['item_type'] == 'goods'){
				  $goodsid = array();
				  foreach($data[$k]['item_data']['goods'] as $g){
					  $goodsid[] = $g;
				  }
				  // $newgoods=array();
				  // foreach ($goodsid as $key => $value) {
					 //  $newgoods[$value] = $key;
				  // }
				  if($goodsid && !empty($goodsid)){
					  $goods_info = M("goods_common") -> where(array('goods_common_id'=>array('in',$goodsid))) -> select();
					  $newgoods_info=array();
					  foreach ($goods_info as $kk => $vv) {
						$goods_info[$kk]['goods_image']=C('TMPL_PARSE_STRING.__ATTACH_HOST__').$vv['goods_image'] ;
						
					  }
					  foreach ($goods_info as $ke => $va) {
					  	$index = array_search($va['goods_common_id'],$goodsid);
                        $newgoods_info[$index] = $va;
					  }
					  ksort($newgoods_info);
					  $data[$k]['goods_info'] = $newgoods_info;
				  }
			  	}
			}
		   S($key,$data,1800);
		}
      return $data;
  }

  //$type 最好只传单字符串    例如   shop_logo
  static function getSetting($type = array()) {
		$key = CacheHelper::getCachePre('web_setting');
		$value =  S($key);
		$return = array();

		$type_arr = array('shop_logo' => 'DEFAULT_LOGO_IMAGE','shop_login' =>'DEFAULT_LOGIN_IMAGE','shop_member' =>'DEFAULT_MEMBER_IMAGE');
		if(is_array($type)){
			foreach ($type as $k => $v) {
				if($type_arr[$v]){
					if($value[$v]) {
						$return[$v] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$value[$v];
					} else {
						$return[$v] = C('TMPL_PARSE_STRING.'.$type_arr[$v]);
					}
				}else{
					$return[$v] = $value[$v];
				}
			}
		}else{
			if($type_arr[$type]){
				if($value[$type]) {
					$return = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$value[$type];
				} else {
					$return = C('TMPL_PARSE_STRING.'.$type_arr[$type]);
				}
			}else{
				$return = $value[$type];
			}
		}
		return $return;
  }

  //$type不要随意传递参数
  static function getSettings($type) {
	$key = CacheHelper::getCachePre('web_setting');
	$value =  S($key);
	
	$return = array();

	if(is_array($type)){
		foreach($type as $v){
			$return[$v] = $value[$v] ;
		}
	}else{
		return $value[$type];
	}
    return $return;
  }

   
}