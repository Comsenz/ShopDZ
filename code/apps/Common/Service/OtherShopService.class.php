<?php
namespace Common\Service;
class OtherShopService {
	static function getShopConfig($shop_code) {
		$shop  = D('OtherShop');
		$where['shop_code'] = $shop_code;
		$where['shop_state'] = 1;
		$res = $shop->getShopConfig($where);
		return $res;
	}
	static function setShopConfig($data, $shop_id){
		$shop = D('OtherShop');
		$res = $shop->setShopConfig($data,$shop_id);
		return $res;
	}
	
}