<?php
function getcount(){
	$data = S('statistics_data');
	if($data === false){
		//系统消息
		$smsmodel = M('sms');
		$ordermodel = D('Admin/orders');
		$refundmodel = D('Admin/Refund');
		$feedmodel = M('feedback');
		$list = array();
		$model = M();
		$where = array(
				'type'		=> 0,
				'status'	=> 0,
			);
		$sms_count = $smsmodel->where($where)->count();
		//暂无跳转链接
		if(!empty($sms_count))
			$list[] = array('name'=>'总共','link'=>''.$sms_count.'条新消息');
		$smsdata = $smsmodel->where($where)->select();
		// 1.发货提醒——有1个订单需要发货。
		$shipping_condition = array(
				'order_type'	=> 1,
				'order_state'	=> 20,
				'refund_state'	=> 0,
			);
		$shipping_count = $ordermodel->getListCount($shipping_condition);
		if(!empty($shipping_count))
			$list[] = array('name'=>'订单发货','link'=>'<a class="thenewurl list-num" uri="'.U('Admin/Order/lists').'?type=20" >'.$shipping_count.'</a><a class="thenewurl" uri="'.U('Admin/Order/lists').'?type=20" >个订单需要发货</a>');
		// 2.退款提醒——有1个退款申请需要处理。
		$refund_condition = array(
				C('DB_PREFIX').'refund.status' => 1,
			);
		$refund_count = $refundmodel->_getRefundsCount($refund_condition);
		if(!empty($refund_count))
			$list[] = array('name'=>'退款申请','link'=>'<a class="thenewurl list-num" uri="'.U('Presales/refunds/s/1').'" >'.$refund_count.'</a><a class="thenewurl" uri="'.U('Presales/refunds/s/1').'" >个退款申请需要处理</a>');
		// 3.退货提醒——有1个退货申请需要处理。
		$return_condition = array(
				C('DB_PREFIX').'returngoods.status' => 1,
			);
		$return_count = $refundmodel->_getReturnsCount($return_condition);
		if(!empty($return_count))
			$list[] = array('name'=>'退货申请','link'=>'<a class="thenewurl list-num" uri="'.U('Presales/returns/s/1').'" >'.$return_count.'</a><a class="thenewurl" uri="'.U('Presales/returns/s/1').'" >个退货申请需要处理</a>');
		// 4.意见反馈——有1条意见反馈需要处理。
		$feed_condition = array(
				'status' => 0,
			);
		$feed_count = $feedmodel->where($feed_condition)->count();
		if(!empty($feed_count))
			$list[] = array('name'=>'意见反馈','link'=>'<a class="thenewurl list-num" uri="'.U('Presales/feedbacks/s/1').'" >'.$feed_count.'</a><a class="thenewurl" uri="'.U('Presales/feedbacks/s/1').'">条意见反馈需要处理</a>');
		// 5.提现申请——有1条提现申请需要处理。
		//暂无提现申请
		$plusnum = intval($sms_count+$shipping_count+$refund_count+$return_count+$feed_count);
		$data = array('plusnum' => $plusnum,'list' => $list);
		S('statistics_data',$data,60);
	}
	return $data;
}
// 支付完成后的操作
function afterNotify($orderdata) {
	$insert_arr['pl_memberid'] = $orderdata['buyer_id'];
	$insert_arr['pl_membername'] = $orderdata['member_username'];
	$insert_arr['order_sn'] = $orderdata['order_sn'];
	$insert_arr['orderprice'] = $orderdata['order_amount'];
	$result  = \Common\Helper\PointsHelper::addPoints($insert_arr,'order_rate');
	
	$insert['order_id'] = $orderdata['order_id'];
	$insert['order_add_time'] = $orderdata['add_time'];
	$insert['add_time'] = TIMESTAMP;
	$insert['cron_type'] = 0;
	Common\Service\SpreadOrderCronService::addCron($orderdata['buyer_id'],$insert);
	$smsdata = array('order_sn' => $orderdata['order_sn']);
	Common\Service\SmsService::sendWxAdmin($smsdata);
	Common\Service\SmsService::insert_sms_notice($orderdata['buyer_id'],$orderdata['buyer_phone'],$smsdata,'paymentsuccess');
}

function send_post($url,$data){
    if(!$url){
        return  false;
    }
    $ch = curl_init();//初始化curl
    curl_setopt($ch,CURLOPT_URL,$url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result= curl_exec($ch);
    return $result;
}

function flushWebSetingCache() {
	$filename = Common\Helper\CacheHelper::getCachePre('web_setting');
	$web_setting = D('Setting')->getSettings();
	$pay =D('payment')->getPaymentOpenList();
	$web_setting = array_merge($pay,$web_setting);
	S($filename,$web_setting);
}
