<?php
/**
 * 插件需要执行的方法 逻辑定义  
 */
use plugins\group\GroupModel;
use Common\Base\Pay\PayBase;
use Common\Service\OrdersService;
class pay extends PayBase
{
    public $model = null; // model对象
    /**
     * 析构流函数
     */
    public function  __construct() {
		$this->model = new GroupModel();
    }

	public function selectOrderBySn($order_sn){
		$data =  $this->model->getGroupJoinBySnByPay($order_sn);
		return $data;
	}
	
	public function saveOrder($orderinfo){
		$result =  $this->model->opGroupJion($orderinfo);
		return $result;
	}
	
	public function selectOrderByReturnSn($return_sn){
		return $this->model->getGroupJoinByReturnSn($return_sn);
	}
	
	public function saveReturnOrder($data,$issuccess){
		return $this->model->refund_pay_callback($data,$issuccess);
	}
	
	public function payresult($group_sn) {
		$data =$this->model->getGroupJoinBySn($group_sn);
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
		//首页
	public function getFileDir(){
		return dirname(__FILE__);
	}
}