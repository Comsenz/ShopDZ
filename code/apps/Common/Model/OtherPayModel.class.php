<?php
namespace Common\Model;
use Think\Model;
class OtherPayModel extends Model {
	
	public function getOtherPaySn($order_sn){
		$res = $this->where('order_sn='.$order_sn)->find();
		$data = array(
			'pay_sn'		=> $res['order_sn'],
			'order_sn'		=> $res['order_sn'],
			'trade_no'		=> $res['trade_no'],
			'order_amount'	=> $res['order_amount'],
		);
		return $data;
	}
	public function addOtherPayData($data){
		$data = $this->add($data);
		return $data;
	}
	public function saveOtherPayData($data,$order_sn){
		$res = $this->where('order_sn='.$order_sn)->save($data);
		return $res;
	}
	public function getAllOtherPayCount($where=array()) {
		return $count = $this->where($where)->count();
	}
	public function getAllOtherPay($where = array(),$page) {
		$lists = $this->where($where)->limit($page->firstRow.','.$page->listRows)->order('pay_starttime desc')->select();
		foreach($lists as $k => $v) {
			$lists[$k]['time_text'] = date('Y-m-d H:i:s',$v['pay_starttime']);
		}
		return $lists ? $lists : array();
	}
}
?>