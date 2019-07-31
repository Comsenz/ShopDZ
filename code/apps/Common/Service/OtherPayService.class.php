<?php
namespace Common\Service;
use Think\Model;

class OtherPayService {
	
	static $pay_state = array('wx'=>'微信','alipay'=>'支付宝','newalipay'=>'支付宝');
	/**
	 * 查询扫码支付数据
	 */
	static public function getOtherPaySn($order_sn){
        $model  = D('OtherPay');
		return $model->getOtherPaySn($order_sn);
	}
	/**
	 * 插入扫码支付数据
	 */
	static public function addOtherPayData($param){
        $data = array(
        	'order_sn'		=> $param['order_sn'],
        	'payment_code'		=> self::$pay_state[$param['payment_code']],
        	'order_amount'	=> $param['order_amount'],
        	'pay_starttime' => TIMESTAMP
        );
		$model = D('OtherPay');
		$res = $model->addOtherPayData($data);
		return $res;
	}
	/**
	 * 修改扫码支付数据
	 */
	static public function saveOtherPayData($data){
		$param = array(
        	'trade_no'=>$data['trade_no']
        );
		$model = D('OtherPay');

		$res = $model->saveOtherPayData($param, $data['order_sn']);
		return $res;
	}
}