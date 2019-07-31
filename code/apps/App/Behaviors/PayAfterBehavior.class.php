<?php
/*
	支付成功后执行特定的行为
*/
namespace App\Behaviors;
class PayAfterBehavior {
	/*
		array (
		'is_stytem_order' => false,//是否是系统订单，true 是，false 是插件订单
		'order_sn' => '201701051916009300',//订单编号
		'trade_no' => '4001502001201701055312356595',//交易流水号
	)
	*/
    public function run(&$arg){
       file_put_contents(APP_ROOT.'./be.txt',var_export($arg,true));
    }
}