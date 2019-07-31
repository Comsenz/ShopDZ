<?php
/**
 * this file is not freeware
 * User: al_bat
 * DATE: 2016/4/15
 */
namespace Admin\Model;
use Think\Model;
class PaymentModel extends Model{

    protected $_validate = array();

    public function getPayment(){
        $pays = $this -> select();
        $return = array();
        foreach($pays as $p){
            $config = unserialize($p['payment_config']);
            $tmp = array_merge($p,$config);
            $return[$p['payment_code']] = $tmp;
        }
        return $return;
    }
  //查询开启的支付方式列表
  public function  getPaymentOpenList($condition = array()){
		$payment_array = array();
		$data = $this->where($condition)->select();
		foreach ($data as $value) {
			$payment_array['payment'][$value['payment_code']] = $value['payment_state'];
		}
		return $payment_array;
  }
    public function addPayment($data){
        if(empty($data)){
            return true;
        }
        $names = $data['payment_code'];
        $condtion = array();
        $condtion['payment_code'] = array("in",$names);
        $insert = array();
        $this -> where($condtion) -> delete();
        return $this -> add($data);
    }
}