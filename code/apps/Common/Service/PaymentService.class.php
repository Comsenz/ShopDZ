<?php
namespace Common\Service;
class PaymentService {
   
  //查询全部支付方式列表 
  static  function  getPaymentList($condition = array()){
       $payment_model  = D('Payment');
       return $payment_model->where($condition)->select();
  }
  
  //查询开启的支付方式列表
  static  function  getPaymentOpenList($condition = array()){
      $payment_model  = D('Payment');
      $condition['payment_state'] = 1;
      return $payment_model->where($condition)->select();
  }

  //修改订单支付状态
  static function  setOrderPayStatus( $order = array() ){
      $orderpaystatus = D('orders');
      $order_sn = $order['order_sn'];
      unset($order['order_sn']);
      return $orderpaystatus->where('order_sn='.$order_sn)->save($order);
  }

}