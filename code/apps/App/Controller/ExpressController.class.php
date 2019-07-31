<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\ExpressService;
use Common\Service\OrdersService;
class ExpressController extends BaseController {
    
    
	public function express_detail() {
 	    $order_sn  = I('order_sn',0,'htmlspecialchars');
 		if(empty($order_sn)){
		    $this->returnJson(1,'参数错误');
 		}
		//需要根据 订单id 去查询物流id 和物流单号  
		$order = OrdersService::getOrderByOrderSn($order_sn);
		if(empty($order))
			$this->returnJson(1,'订单不存在');
		$order_id = $order['order_id'];
		$order_common = OrdersService::getOrderCommon($order_id);
		$shipping_express_id = $order_common['shipping_express_id'];
		$express = OrdersService::getExpress($shipping_express_id);
		if(empty($express)) 
			$this->returnJson(1,'没有物流信息');
		$express_code =  $express['code'] ;
		$express_sn  =   $order['shipping_code']; 
		$result = ExpressService::query_express($express_code, $express_sn);
		if(empty($result)){
		    $data = array();
		    $data[0] = array(
		        'AcceptTime'=>date('Y-m-d H:i:s'),
		        'AcceptStation'=>'暂无物流信息',       
		    );
		}elseif($result['msg']){
			$data = array();
		    $data[0] = array(
		        'AcceptTime'=>date('Y-m-d H:i:s'),
		        'AcceptStation'=>$result['msg'],       
		    );
		}else{
			$data = $result;
		}
		$return_data = array();
		$return_data['express_name'] = $express['name'];
		$return_data['express_code'] =  $express_code;
		$return_data['express_sn'] =   $express_sn;
		$return_data['express_detail'] = $data;
		$this->returnJson(0,'success',$return_data);
	}
}