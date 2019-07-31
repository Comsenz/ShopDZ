<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\PaymentService;
use Common\Service\OrdersService;
class APaymentController extends BaseController {
	protected $payment_code = '';
	protected $payment_config = array();
    public function __construct() {
		parent::__construct();
		$payment_code = I('payment_code','','htmlspecialchars');
        if(!empty($payment_code )) {
            if(!in_array($payment_code, array('wx','alipay'), true)) {
                $payment_code = 'alipay';
            }

            $condition = array();
            $condition['payment_code'] = $payment_code;
			$mb_payment_info = PaymentService::getPaymentOpenList($condition);
			$mb_payment_info = current($mb_payment_info);
            if(!$mb_payment_info) {
               $this->returnJson(1,'支付方式未开启');
            }
			
            $this->payment_code = $payment_code;
            $this->payment_config =$mb_payment_info ? unserialize($mb_payment_info['payment_config']) :'';
        }
	}
    //获取开启的支付方式列表
    public function get_payment_list(){
        $data = PaymentService::getPaymentOpenList();
        $payment_array = array();
        if(!empty($data)) {
            foreach ($data as $value) {
                $payment_array[] = $value['payment_code'];
            }
        }
        if(!$payment_array){
            $this->returnJson(1,'支付方式未开启');
        }
        $this->returnJson(0,'sucess',array('payment_list' => $payment_array));
    }

    //订单支付
    public function pay(){
        $pay_sn = I('pay_sn','','htmlspecialchars');
		$order_pay_info = OrdersService::getOrderByOrderSn($pay_sn);
        if(empty($order_pay_info)) {
			$this->returnJson(1,'订单不存在');
        }
		switch($this->payment_code) {
			case 'alipay':
                /*实例化支付宝类*/
                $alipay = new \Common\PayMent\AliPay\Alipays();
                /*渲染建立请求的页面*/
                $this->show($alipay->aliRequest($order_pay_info));
                break;
		}
    }
    
}