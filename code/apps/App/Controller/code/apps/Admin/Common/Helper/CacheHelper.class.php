<?php
namespace Common\Helper;

class CacheHelper{
	  static $cache_pre_list = array(
		'get_all_category'=>'good_all_category',//分类
		'get_show_category'=>'good_show_category',//分类
		'get_special_item'=>'get_special_item',//首页
		'get_setting'=>'get_setting_',//商城配置
		'getmuti_good_list'=>'getmuti_good_list_%d_%d_%s',//列表页和搜索页缓存，1分钟
		'web_setting'=>'web_setting',
		'user_getdata'=>'user_getdata_',//数据调用
	  );
     public static function  getCachePre( $type ){
        return array_key_exists($type,self::$cache_pre_list) ? self::$cache_pre_list[$type] : null;
    }
}