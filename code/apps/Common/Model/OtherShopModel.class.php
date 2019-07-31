<?php
namespace Common\Model;
use Think\Model;
class OtherShopModel extends Model {
	
	public function getShopConfig($where){
		$data = $this->where($where)->find();
		$data['shop_config'] = unserialize($data['shop_config']);
		return $data;
	}
	public function setShopConfig($data,$shop_id){
		$res = $this->where('shop_id='.$shop_id)->save($data);
		return $res;
	}
}
?>