<?php
namespace Common\Model;
use Think\Model;
class SpreadModel extends Model {
	public function getSpreadByUidAndOrderId($uid,$order_id = 0) {
		if(empty($uid) || empty($order_id)) {
			return array();
		}
		$where['member_id']= $uid;
		$where['order_id']= $order_id;
		return $this->where($where)->find();
	}
	
 
}
?>