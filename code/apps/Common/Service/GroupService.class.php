<?php
/**
 * 快递查询  对接的是快递鸟的接口
 * @author Administrator
 */
namespace Common\Service;
use Think\Model;
use Common\Service\OrdersService;

class GroupService {
    static  function  getGroupList($where, $page = 1, $prepage = 20 ){
		$res = array();
		$model  = D('Group');
		$res = $model->getGroupList($where,$page,$prepage);
		return $res ? $res : array() ;
    }
	
	static function getGroup($id) {
		$res = array();
		$model  = D('Group');
		$res = $model->getGroup($id);
		return $res ? $res : array() ;
	}
	
	static function getgroupByJoin($where) {
		$res = array();
		$model  = D('Group');
		$res = $model->getgroupByJoin($where);
		return $res ? $res : array() ;
	}
	
	static function getGroupByIds(array $ids) {
		$res = array();
		$model  = D('Group');
		$res = $model->getGroupByIds($ids);
		return $res ? $res : array() ;
	}
	
	static function getGroupGroup($where) {
		$res = array();
		$model  = D('Group');
		$res = $model->getGroupGroup($where);
		return $res ? $res : array() ;
	}
	
	static function refund_pay_callback($returnInfo,$issuccess) {
		$res = array();
		$model  = D('Group');
		return $model->refund_pay_callback($returnInfo,$issuccess);
	}
	
	static function getGroupJoin($where = array(),$page=1,$prepage=100,$order) {
		$model  = D('Group');
		$res = $model->getGroupJoin($where,$page,$prepage,$order);
		return $res ? $res : array() ;
	}
	
	static function getGroupJoinByReturnSn($retufn_sn) {
		$model  = D('Group');
		return $model->getGroupJoinByReturnSn($retufn_sn);
	}
	
	static function getGroupJoinBySnByPay($sn) {
		$model  = D('Group');
		return $model->getGroupJoinBySnByPay($sn);
	}
	
	static function getGroupJoinBySn($sd,$uid=0) {
		$model  = D('Group');
		return $model->getGroupJoinBySn($sd,$uid);
	}
	
	static function getGroupPayResult($group_sn){
		$data = self::getGroupJoinBySn($group_sn);
		if(empty($data))
			return false;
		$buyinfo = OrdersService::getOrderCommon($data['id'],'reciver_name,reciver_info');
		$buy_info['tel_phone'] = preg_replace('/([\d]{3})([\d]{4})([\d]{4})/i','$1****$3',$buyinfo['reciver_info']['tel_phone']);
		$buy_info['reciver_name'] = $buyinfo['reciver_name'];
		$buy_info['area_info'] = $buyinfo['reciver_info']['area_info'].$buyinfo['reciver_info']['address'];
		$data['buyinfo'] = $buy_info;
		unset($buyinfo);
		return $data;
	}

	static function getGroupGroups(array $where) {
		$res = array();
		$model  = D('Group');
		$res = $model->getGroupGroups($where);
		return $res ? $res : array() ;
	}
	
	static function opGroupJion(array $orderinfo) {
		$res = array();
		$model  = D('Group');
		$res = $model->opGroupJion($orderinfo);
		return $res ;
	}
	
	static function getGroupGroupCount(array $where) {
		$res = array();
		$model  = D('Group');
		$res = $model->getGroupGroupCount($where);
		return $res ;
	}
}