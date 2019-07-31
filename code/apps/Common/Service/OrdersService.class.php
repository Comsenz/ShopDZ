<?php
namespace Common\Service;
use Think\Model;
use  Common\Service\SmsService;
use  Common\Service\CouponService;
class OrdersService {
	static $order_state = array(0=>'已取消',10=>'未付款',20=>'已支付',30=>'已发货',40=>'已收货');
	static $pay_state = array('wx'=>'微信','alipay'=>'支付宝');
		
	//进入订单表
	static function addOrder($memberinfo,$baskets,$address,$coupon =array(),$order_message= '') {
		$model = new Model();
		$model->master(true);
		$model->startTrans();
		
		$order_sn = \Common\Helper\ToolsHelper::getOrderSn('order');
		$total_price = 0;//运费generate
		$flag = 1;
		$baskets_id = $_fee = $good = array();
		$voucher_price = $coupon ?  $coupon['rpacket_price'] : 0;
		$voucher_code = $coupon ?  $coupon['rpacket_code'] : 0;
		$voucher_limit = $coupon ? $coupon['rpacket_limit'] : 0;//coupon 优惠券
		$rpacket_t_id = $coupon ? $coupon['rpacket_t_id'] : 0;//coupon 优惠券
		$id = $model->table(C('DB_PREFIX').'generate')->master(true)->add(array('id'=>null));//预留分表
		foreach($baskets as $k => $v) {
			$goods_id = $v['goods_id'];
			$goods_num = $v['goods_num'];
			$goods = GoodsService::getGood($goods_id);
			$goods['goods_num'] = $v['goods_num'];
			$_fee[] = self::getOneLogistics($goods);
			$goods_storage = $goods['goods_storage'];
			if($goods_num > $goods_storage) {
				return array('code'=>3,'order_id'=>0);//库存不足
			}
			$orderGoodinfo[] = array(
					'order_id'=>$id,
					'goods_id'=>$goods_id,
					'goods_name'=>$v['goods_name'],
					'goods_price'=>$goods['goods_price'],
					'goods_num'=>$v['goods_num'],
					'goods_image'=>$v['goods_image'],
					'goods_pay_price'=>0,
					'buyer_id'=>$memberinfo['member_id'],
					'goods_spec'=>$goods['spec_name'],
					'gc_id'=>$goods['gc_id'],
					'add_time'=>TIMESTAMP,
					'goods_common_id'=>$goods['goods_common_id'],
			);
			$total_price += (($goods['goods_price']*100)*$goods_num)/100;
			$baskets_id[] = $v['id'];
			
			$goodResult = $model->table(C('DB_PREFIX').'goods')->master(true)->where('goods_id=%d and goods_storage>=%d',array($goods_id,$v['goods_num']))->setDec('goods_storage',$v['goods_num']);
			if(!$goodResult) {//减库存失败
				$model->rollback();
				return array('code'=>1,'order_id'=>0,'order_sn'=>0);//库存不足
			}
			D('Goods')->delSkuCache($goods_id);
		}
		//
		if($coupon && $voucher_limit > $total_price) {
			$model->rollback();
			return array('code'=>2,'order_id'=>0,'order_sn'=>0);//优惠券非法
		}
		
		$shipping_fee = self::getExpense($total_price,$_fee);
		
		$order = array(
			'order_id'=>$id,
			'order_sn'=>$order_sn,
			'pay_sn'=>$order_sn,
			'buyer_id'=>$memberinfo['member_id'],
			'buyer_name'=>$memberinfo['member_username'],
			'buyer_email'=>$memberinfo['member_email'],
			'buyer_phone'=>$memberinfo['member_mobile'],
			'add_time'=>TIMESTAMP,
			'goods_amount'=>$total_price,//商品总价格
			'order_amount'=>0.01,//订单总价格
			// 'order_amount'=>$total_price - $voucher_price + $shipping_fee['shipping_fee'],//订单总价格
			'shipping_fee'=>$shipping_fee['shipping_fee'],//订单运费
		);
		
		$common = array(
			'order_id'=>$id,
			'voucher_price'=>$voucher_price,
			'voucher_code'=>$voucher_code,
			'reciver_name'=>$address['true_name'],
			'reciver_info'=>serialize($address),
			'reciver_province_id'=>$address['area_id'],
			'reciver_city_id'=>$address['city_id'],
			'promotion_total'=>0,//订单总优惠金额
			'order_message'=>$order_message,//订单留言
		);

		$id_str = implode(',',$baskets_id);
		$ordersResult = $model->table(C('DB_PREFIX').'orders')->master(true)->add($order);
		$orderGoodsResult = $model->table(C('DB_PREFIX').'order_goods')->master(true)->addAll($orderGoodinfo);
		$orderCommon = $model->table(C('DB_PREFIX').'order_common')->master(true)->add($common);
		$basketResult = $model->table(C('DB_PREFIX').'basket')->master(true)->delete("$id_str");
		if($orderGoodsResult && $ordersResult && $basketResult && $orderCommon) {
			if(!empty($coupon)) {
				$resultCoupon = self::_changeCoupon($model,$coupon,$memberinfo['member_id'],$id);
				if(!$resultCoupon) {
					$flag = 1;
					$model->rollback();
					return array('code'=>$flag,'order_id'=>0,'order_sn'=>0);//库存不足
				}
			}
			$model->commit();
			if($memberinfo['member_mobile']){
				$smsdata = array('order_sn' => $order_sn,'time' => 30);
				SmsService::insert_sms_notice($memberinfo['member_id'],$memberinfo['member_mobile'],$smsdata,'orderpayment');
			}
			$flag = 0;
		}else{
			$flag = 1;
			$model->rollback();
		}
		return array('code'=>$flag,'order_id'=>!$flag ? $id :0,'order_sn'=>$order_sn);//库存不足
	}
	
	//更新优惠券
	private static function _changeCoupon($model,$coupon,$member_id,$order_id) {
			$t_result =  $model->table(C('DB_PREFIX').'redpacket_template')->master(true)->where(array('rpacket_t_id' => $coupon['rpacket_t_id']))->setInc('rpacket_t_used');
			$where = array(
				  'rpacket_owner_id'  => $member_id,
				  'rpacket_id'        => $coupon['rpacket_id'],
			);
			$data = array(
				'rpacket_used_date' =>  TIMESTAMP,
				'rpacket_state'     =>  2,
				'rpacket_order_id'=>$order_id
			);
			$redpacket =  $model->table(C('DB_PREFIX').'redpacket')->master(true)->where($where)->save($data);
			$change_wx = true;
			if($coupon['rpacket_code'] && $coupon['rpacket_t_wx_card_id']){
				if($redpacket && $t_result)
				 CouponService::use_card ($coupon['rpacket_code'],$coupon['rpacket_t_wx_card_id']);
			}
		return $t_result && $redpacket && $change_wx;
	}
	
	static function buy($memberinfo,$goodsinfo,$goods_num=1,$address,$coupon =array(),$order_message='') {
		$model = new Model();
		$model->master(true);
		$model->startTrans();
		$order_sn = \Common\Helper\ToolsHelper::getOrderSn('order');
		$total_price = 0;
		$baskets_id = $_fee = array();
		$goods_id = $goodsinfo['goods_id'];
		$goods_storage = $goodsinfo['goods_storage'];
		$voucher_price = $coupon ?  $coupon['rpacket_price'] : 0;
		$voucher_code = $coupon ?  $coupon['rpacket_code'] : 0;
		$voucher_limit = $coupon ? $coupon['rpacket_limit'] : 0;
		if($goods_num > $goods_storage) {
			return array('code'=>3,'order_id'=>$ordersResult);
		}
		$id = $model->table(C('DB_PREFIX').'generate')->master(true)->add(array('id'=>null));
		$orderGoodinfo = array(
				'order_id'=>$id,
				'goods_id'=>$goods_id,
				'goods_name'=>$goodsinfo['goods_name'],
				'goods_price'=>$goodsinfo['goods_price'],
				'goods_num'=>$goods_num,
				'goods_image'=>$goodsinfo['goods_image_real'],
				'goods_pay_price'=>0,
				'buyer_id'=>$memberinfo['member_id'],
				'goods_spec'=>$goodsinfo['spec_name'],
				'gc_id'=>$goodsinfo['gc_id'],
				'add_time'=>TIMESTAMP,
				'goods_common_id'=>$goodsinfo['goods_common_id'],
		);
		$total_price += (($goodsinfo['goods_price']*100)*$goods_num)/100;
		//$baskets_id[] = $v['id'];
		$goodsinfo['goods_num'] = $goods_num;
		$goodResult = $model->table(C('DB_PREFIX').'goods')->master(true)->where('goods_id=%d and goods_storage>=%d',array($goods_id,$goods_num))->setDec('goods_storage',$goods_num);
		D('Goods')->delSkuCache($goods_id);
				//
		if($coupon && $voucher_limit > $total_price) {
			$model->rollback();
			return array('code'=>2,'order_id'=>0,'order_sn'=>0);//优惠券非法
		}

		if(!$goodResult) {//减库存失败
			$model->rollback();
			return array('code'=>1,'order_id'=>0);//库存不足
		}
		$_fee[] = self::getOneLogistics($goodsinfo);
		$shipping_fee = self::getExpense($total_price,$_fee);
		$order = array(
			'order_id'=>$id,
			'order_sn'=>$order_sn,
			'pay_sn'=>$order_sn,
			'buyer_id'=>$memberinfo['member_id'],
			'buyer_name'=>$memberinfo['member_username'],
			'buyer_email'=>$memberinfo['member_email'],
			'buyer_phone'=>!empty($memberinfo['member_mobile']) ? $memberinfo['member_mobile'] : 0,
			'add_time'=>TIMESTAMP,
			'goods_amount'=>$total_price,//商品总价格
			'order_amount'=>0.01,//订单总价格
			// 'order_amount'=>$total_price - $voucher_price + $shipping_fee['shipping_fee'],//订单总价格
			'shipping_fee'=>$shipping_fee['shipping_fee'],//
		);
		$common = array(
			'order_id'=>$id,
			'voucher_price'=>$voucher_price,
			'voucher_code'=>$voucher_code,
			'reciver_name'=>$address['true_name'],
			'reciver_info'=>serialize($address),
			'reciver_province_id'=>$address['area_id'],
			'reciver_city_id'=>$address['city_id'],
			'promotion_total'=>0,//订单总优惠金额
			'order_message'=>$order_message,//订单留言
		);
		$ordersResult = $model->table(C('DB_PREFIX').'orders')->master(true)->add($order);
		$orderGoodsResult = $model->table(C('DB_PREFIX').'order_goods')->master(true)->add($orderGoodinfo);
		$orderCommon = $model->table(C('DB_PREFIX').'order_common')->master(true)->add($common);
		if($orderGoodsResult && $ordersResult && $orderCommon) {
			if(!empty($coupon)) {
				$resultCoupon = self::_changeCoupon($model,$coupon,$memberinfo['member_id'],$id);
				if(!$resultCoupon) {
					$flag = 1;
					$model->rollback();
					return array('code'=>$flag,'order_id'=>0,'order_sn'=>0);//库存不足
				}
			}
			$model->commit();
			if($memberinfo['member_mobile']){
				$smsdata = array('order_sn' => $order_sn,'time' => 30);
				SmsService::insert_sms_notice($memberinfo['member_id'],$memberinfo['member_mobile'],$smsdata,'orderpayment');
			}
			$flag = 0;
		}else{
			$flag = 1;
			$model->rollback();
		}
		return array('code'=>$flag,'order_id'=>!$flag ? $id :0,'order_sn'=>$order_sn);
	}
	
	/*
		格式化从购物车返回的数据
		$baskets 
	*/
	static function getgoodsFromBasket($baskets,$groupinfo = array()) {
		$goodinfo = $_fee = array();
		$total = $send_fee = 0;
		$isgroup = $groupinfo['isgroup'];
		$is_shipping = $groupinfo['is_shipping'];
		foreach($baskets as $k => $v) {
			$has = 1;
			$goods_id = $v['goods_id'];
			$goods_num = $v['goods_num'];
			$goods = GoodsService::getGood($goods_id);
			$goods['goods_num'] = $v['goods_num'];
			$_fee[] = self::getOneLogistics($goods);
			$goods_storage = $goods['goods_storage'];
			if($goods_num > $goods_storage) {
				$has = 0;//库存不足
			}
			$goods['goods_price'] = $isgroup ? $v['goods_price'] : $goods['goods_price'];
			$total += $goods['goods_price'];
			$goodinfo[] = array(
				'id'=>$goods['goods_id'],
				'member_uid'=>$v['member_uid'],
				'goods_id'=>$goods['goods_id'],
				'goods_name'=>$goods['goods_name'],
				'goods_price'=>$goods['goods_price'],//重新从库存里取价格
				'goods_num'=>$v['goods_num'],
				'spec_name'=>$goods['spec_name'],
				'goods_image'=>$goods['goods_image'],
				'goods_has'=>$has,
			);
		}
		if(!empty($is_shipping)){
			$expense = self::getExpense($total,$_fee);
		}else{
			$expense = array('expense'=>0,'shipping_fee'=>0);
		}
		return array('goods_info'=>$goodinfo,'shipping_fee'=>$expense);
	}
	//获取订单支付类型 0 最大运费  1累加计费
	static function getExpense($total,array $_fee) {
		$m = M('setting'); 
		$data = $m->where('name="expense"')->master(true)->field('value')->find();
		 $expense = $data ? unserialize($data['value']) : array();
		 $send_fee = 0;
		if(!empty($expense)) {
			$price = $expense['price'];
			$type = $expense['type'];// 0 最大运费  1累加计费
			if($price && $total >= $price) {
				$send_fee = 0;
			}else{
				switch($type) {
					case 0://最大运费
						rsort($_fee);
						$send_fee = $_fee[0];
						break;
					case 1:
						$send_fee = array_sum($_fee);
						break;
				}
			}
		}
		return array('expense'=>$price,'shipping_fee'=>$send_fee);
	}
	
	//获某类商品取物流费用
	static function getOneLogistics($goodinfo) {
		$total = 0;
		if(empty($goodinfo)) return $total;
		$freight_type = $goodinfo['freight_type'];
		switch($freight_type) {
			case 'fixed':
				$total = $goodinfo['freight'];
				break;
			case 'num':
				$total = $goodinfo['freight'] +(ceil(($goodinfo['goods_num']-1)/$goodinfo['freight_step_num'])*($goodinfo['freight_step_fee']*100))/100;
				break;
			case 'weight':
				$total_weight = $goodinfo['goods_weight']*$goodinfo['goods_num'];//总重量
				if($total_weight <1){//小区1公斤
					$total = $goodinfo['freight'];
				}else{
					$total = $goodinfo['freight'] +(ceil(($total_weight-1)/$goodinfo['freight_step_num'])*($goodinfo['freight_step_fee']*100))/100;
				}
				break;
			case 'volume':
				$total_volume = $goodinfo['goods_volume']*$goodinfo['goods_num'];//总重量
				if($total_volume <1){//小区1公斤
					$total = $goodinfo['freight'];
				}else{
					$total = $goodinfo['freight'] +(ceil(($total_volume-1)/$goodinfo['freight_step_num'])*($goodinfo['freight_step_fee']*100))/100;
				}
				break;
		}
		return $total;
	}

	static function getReturnGoodsByRgId($rg_id,$field="*") {
		$m = M('returngoods');
		return $m->field($field)->where('rg_id=%d',array($rg_id))->find();
	}
	
	static function getRefundByRefundSn($refund_sn,$field="*") {
		$m = M('refund');
		return $m->field($field)->where('refund_sn=%d',array($refund_sn))->find();
	}
	
	static function getRefundByOrderId($order_id,$field="*") {
		$m = M('refund');
		return $m->field($field)->where('order_id=%d',array($order_id))->order('dateline desc')->limit(1)->select();
	}
	
	static function getMyOrders($uid,$type=null,$page = 1,$prepage = 20) {
		$m = M('orders'); 
		$where = array();
		$uid && $where['buyer_id'] = $uid;
		!is_null($type) && $where['order_state'] = $type;
		$where['delete_state'] = 0;
		$start =( $page-1 ) * $prepage;
		$lists = $m->field('order_id,order_sn,refund_sn,trade_no,shipping_code,order_state,add_time,order_amount,refund_state,return_state,lock_state')->where($where)->order('add_time desc')->limit($start.','.$prepage)->select();
		foreach($lists as $k => $v) {
			$lists[$k]['order_state_text'] = self::$order_state[$v['order_state']];
		}
		return $lists ? $lists : array();
	}
	
	static function getMyOrdersCount($uid,$type=null) {
		$m = M('orders'); 
		$where = array();$count =0;
		$uid && $where['buyer_id'] = $uid;
		$where['delete_state'] = 0;
		!is_null($type) && $where['order_state'] = $type;
		return $count = $m->where($where)->count();
	}
	
	static function getOrderByOrderSn($order_sn,$uid = 0) {
		$where['order_sn'] = $order_sn;
		$uid && $where['buyer_id'] = $uid;
		$where['delete_state'] = 0;
		$m = M('orders'); 
		 $lists = $m->field('order_id,order_sn,trade_no,buyer_id,buyer_phone,shipping_code,order_state,add_time,payment_code,shipping_fee,order_amount,goods_amount,lock_state,return_state,refund_state,refund_sn,buyer_id,buyer_phone,buyer_name')->where($where)->find();
		 if(!empty($lists)){
			$lists['order_state_text'] = self::$order_state[$lists['order_state']];
			$lists['payment_code_text'] = self::$pay_state[$lists['payment_code']];
			$lists['rpacket'] = (100*$lists['goods_amount'] +  100*$lists['shipping_fee'] - 100*$lists['order_amount'])/100;
			}
		return $lists ? $lists : array();
	}
	
	static function getOrderPayResult($order_sn){
		$data = self::getOrderByOrderSn($order_sn);
		if(empty($data))
			return false;
		$buyinfo = self::getOrderCommon($data['order_id'],'reciver_name,reciver_info');
		$buy_info['tel_phone'] = preg_replace('/([\d]{3})([\d]{4})([\d]{4})/i','$1****$3',$buyinfo['reciver_info']['tel_phone']);
		$buy_info['reciver_name'] = $buyinfo['reciver_name'];
		$buy_info['area_info'] = $buyinfo['reciver_info']['area_info'].$buyinfo['reciver_info']['address'];
		$data['buyinfo'] = $buy_info;
		unset($buyinfo);
		return $data;
	}

	static function getOrdersGoodsListCount($order_id) {
		$m = M('orderGoods');
		return $m->where("order_id=$order_id")->count();
	}
	
	static function getOrdersGoodsListOne($order_id,$field='*') {
		$lists = array();
		if(!empty($order_id)) {
				$m = M('orderGoods'); 
				$lists = $m->field($field)->where("order_id=$order_id")->order('rec_id desc')->find();
				unset($lists['order_id']);
				$lists && $lists['goods_image'] = $lists['goods_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$lists['goods_image'] : '';
		}
		return $lists ? $lists : array();
	}
	
	static function getOrdersGoodsList($order_id,$field='*') {
		$lists = array();
		if(!empty($order_id)) {
			$lists = F($order_id);
			if($lists === false || 1) {
				$m = M('orderGoods'); 
				$lists = $m->field($field)->where("order_id=$order_id")->order('rec_id desc')->select();
				foreach($lists as $k => $v) {
						unset($lists[$k]['order_id']);
						$lists[$k]['goods_image'] = $v['goods_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['goods_image'] : '';
				}
				F($order_id,$lists);
			}
		}
		return $lists ? $lists : array();
	}
	static function getOrderGoodsById($rec_id,$field="*") {
		$m = M('orderGoods');
		$data =  $m->field($field)->find($rec_id);
		if(!empty($data) && $data['goods_image']) {
			$data['goods_image_real'] = $data['goods_image'];
			$data['goods_image'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$data['goods_image'];
		}
		return $data ? $data : array();
	}
	
	static function getOrderCommon($order_id,$field="*") {
		$m = M('orderCommon'); 
		$data =  $m->field($field)->find($order_id);
		if(!empty($data) && $data['reciver_info']) {
			$data['reciver_info'] = unserialize($data['reciver_info']);
		}
		return $data ? $data : array();
	}
	
	static function getExpress( $shipping_express_id) {
		$company = M('companylist');
		return $express = $company->find($shipping_express_id);
	}
	static function opOrder($order_id,$uid,$order_state) {
		if($order_state == 0){
			return self::changeOrderStateCancel($order_id,'buyer',$uid);
		}else{
			$m = M('orders'); 
			$order['order_state'] = $order_state;
			if( $order_state == 40 ){
				$order['finnshed_time'] = TIMESTAMP;
			}
			return $m->where("order_id=%d and buyer_id=%d",array($order_id,$uid))->save($order);
		}
	}
	
	static function opRubbish($order_id,$uid,$delete_state) {
			$m = M('orders'); 
			return $m->where("order_id=%d and buyer_id=%d and order_state=40",array($order_id,$uid))->setField('delete_state',$delete_state);
	}
	
	static function upPayStartTime($order_id) {
		if(empty($order_id)) return;
		$m = M('orders'); 
		$data = array('payment_starttime'=>TIMESTAMP);
		$result = $m->where('order_id=%d',array($order_id))->save($data);
		return $result;
	}
	
	static function saveOrder($data) {
		$m = M('orders'); 
		$order_sn = $data['order_sn'];
		$info = self::getOrderByOrderSn($order_sn);
		$result = false;
		if(empty($info)) return $result;
		$order_state = $info['order_state'];
		if($order_state == 10) {
		 $result = $m->where('order_id=%d',array($info['order_id']))->save($data);
		}
		return $result;
	}
	
	static function delOrder($order_id,$uid = 0) {
		if(empty($order_id) && empty($uid)) return;
		$m = M('orders'); 
		$data = array('delete_state'=>1,'order_state'=>0);
		$result = $m->where('order_id=%d and buyer_id=%d',array($order_id,$uid))->save($data);
		return $result;
	}
	
	static function evaluate($member,array $goodmessage,array$contentinfo) {
		$flag = 0;
		$model = new Model();
		$model->master(true);
		$model->startTrans();
		if(empty($contentinfo['evaluate_state'])) {
			$evaluate = array(
				'geval_orderid'=>$goodmessage['order_id'],
				//'geval_orderno'=>$goodmessage['order_sn'],
				'goods_spec'=>$goodmessage['goods_spec'],
				'geval_ordergoodsid'=>$goodmessage['rec_id'],
				'geval_goodsid'=>$goodmessage['goods_id'],
				'goods_common_id'=>$goodmessage['goods_common_id'],
				'geval_goodsname'=>$goodmessage['goods_name'],
				'geval_goodsprice'=>$goodmessage['goods_price'],
				'geval_goodsimage'=>$goodmessage['goods_image_real'],
				'geval_content'=>$contentinfo['message'],
				'geval_image'=>$contentinfo['geval_image'],
				'geval_addtime'=>TIMESTAMP,
				'geval_frommemberid'=>$member['member_id'],
				'geval_frommembername'=>$member['member_username'],
			);
			$evaluateResult = $model->table(C('DB_PREFIX').'evaluate_goods')->master(true)->add($evaluate);
			$orderGoodsResult = $model->table(C('DB_PREFIX').'order_goods')->master(true)->where('rec_id=%d',array($goodmessage['rec_id']))->setField('evaluate_state',1);
			
		}else{
			$evaluate = array(
				'geval_content_again'=>$contentinfo['message'],
				'geval_addtime_again'=>TIMESTAMP,
			);
			$evaluateResult = $model->table(C('DB_PREFIX').'evaluate_goods')->master(true)->where('geval_orderid=%d',$goodmessage['order_id'])->save($evaluate);
			$orderGoodsResult = $model->table(C('DB_PREFIX').'order_goods')->master(true)->where('rec_id=%d',array($goodmessage['rec_id']))->setField('evaluate_state',2);
		}
		if($evaluateResult && $orderGoodsResult) {
			F($goodmessage['order_id'],null);
			
			$insert_arr['pl_memberid'] = $member['member_id'];
			$insert_arr['pl_membername'] = $member['member_username'];
			$result  = \Common\Helper\PointsHelper::addPointsTrans($insert_arr,'order_comments',$model);
			$model->commit();
			$flag = 1;
		}else{
			$flag = 0;
			$model->rollback();
		}
		return $flag;
	}
	
	static function getCommentByGoodId($order_id,$rec_id) {
		if(empty($order_id) || empty($rec_id)) return;
		$m = M('evaluateGoods');


		$where['geval_ordergoodsid'] = $rec_id;
		$where['geval_orderid'] = $order_id;
		$data = $m->where($where)->find();
		if(!empty($data)) {
			$member = M('member');
			$member_info = $member->where(array('member_id' => $data['geval_frommemberid']))->find();
			$default_avatar = IndexService::getSetting('shop_member');
			$data['member_avatar'] = $member_info['member_avatar'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$member_info['member_avatar'] : $default_avatar;
			$data['geval_frommembername'] = \Common\Helper\ToolsHelper::simpleShow($member_info['member_username']);
			$images = explode("\t",$data['geval_image']);
			$geval_image = array();
			foreach($images as $ik=> $image) {
				$image && $geval_image[$ik] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$image;
			}
			$data['geval_image'] = $geval_image;
			$data['geval_addtime_again_text'] = $data['geval_addtime_again'] ? date('Y-m-d H:i:s',$data['geval_addtime_again']) : '';
			$data['geval_addtime_text'] = date('Y-m-d H:i:s',$data['geval_addtime']);
		}
		return $data ? $data : array();
	}
	
	static function getOrdersComment($goods_common_id,$member_id=0,$page=1,$prepage=20) {
		$m = M('evaluateGoods'); 
		$member = M('member');
		$where = array();
		if(!empty($member_id)) $where['geval_frommemberid'] = $member_id;
		$where['goods_common_id'] = $goods_common_id;
		$start =( $page-1 ) * $prepage;
		$lists = $m->field('geval_id,geval_orderid,geval_goodsid,geval_content,geval_addtime,geval_frommemberid,geval_frommembername,geval_explain,geval_image,geval_content_again,geval_addtime_again,geval_image_again,geval_explain_again,goods_common_id,goods_spec')->where($where)->order('geval_addtime DESC')->limit($start.','.$prepage)->select();
		$default_avatar = IndexService::getSetting('shop_member');
		foreach($lists as $k => $v) {
			$images = explode("\t",$v['geval_image']);
			$geval_image = array();
			foreach($images as $ik=> $image) {
				$image && $geval_image[$ik] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$image;
			}
			$geval_image_again = explode("\t",$v['geval_image_again']);
			foreach($geval_image_again as $ak=> $aimage) {
				$aimage && $geval_image_again[$ak] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$aimage;
			}
			$member_info = $member->where(array('member_id' => $v['geval_frommemberid']))->find();
			$lists[$k]['member_avatar'] = $member_info['member_avatar'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$member_info['member_avatar'] : $default_avatar;
			$lists[$k]['geval_frommembername'] = \Common\Helper\ToolsHelper::simpleShow($lists[$k]['geval_frommembername']);
			$lists[$k]['geval_image'] = $geval_image;
			$lists[$k]['geval_image_again'] = $geval_image_again;
			$lists[$k]['geval_addtime_again_text'] = $v['geval_addtime_again'] ? date('Y-m-d H:i:s',$v['geval_addtime_again']) : '';
			$lists[$k]['geval_addtime_text'] = date('Y-m-d H:i:s',$v['geval_addtime']);
		}
		return $lists ? $lists : array();
	}
	
	static function getOrdersCommentCount($goods_common_id) {
		$m = M('evaluateGoods'); 
		$where = array();$count =0;
		$where['goods_common_id'] = $goods_common_id;
		return $count = $m->where($where)->count();
	}
	
	/**
	 * 取消订单
	 * @param 订单id $order_id
	 * @param 操作人类型 $operater_type
	 * @param 操作人id $operator_id
	 * @param 操作人姓名 $operator_name
	 * @return boolean
	 */
	static function changeOrderStateCancel($order_id,$operater_type = '',$operator_id=0,$operator_name=''){
	    $operator_id = intval($operator_id);
	    if(!in_array($operater_type,array('system','seller','buyer'))){
	        return false;
	    }
	    if($operater_type =='system'){
	        $operator_id  = 0;
	        $operator_name = 'system';
	    }else if($operater_type=='seller'){
	        $operator_id  =$_SESSION['uid'];
	        $operator_name = $_SESSION['username'];
	    }
	    if($operater_type=='buyer' &&!$operator_id){
	        return  false;
	    }
	    $order_id =  intval($order_id);
	    if(!$order_id){
	        return false;
	    }
	    $condition = array();
	    $condition['order_state'] =  10;
		$condition['order_id'] = $order_id;
	    $order_info  =  M('orders')->master(true)->where($condition)->find();
	    if(empty($order_info)){
	        return  false;
	    }
	    if($operater_type != 'buyer'){
    	    //发起支付不到1小时不取消
    	    if($order_info['payment_starttime']>0  &&  (time()-$order_info['payment_starttime']<3600)){
    	        return  false;
    	    }
	    }

	    try{
			$model = new Model();
			$model->master(true);
			$model->startTrans();
	        $order_condition  =  array();
	        $order_condition['order_id'] = $order_id;
	        $order_condition['order_state'] = 10;
	        if($operater_type=='buyer'){
	            $order_condition['buyer_id']  = $operator_id;
	        }
	        $order_update = array();
	        $order_update['order_state'] =  0;
	        $order_update['finnshed_time'] = time();
	        $order_up_state = $model->table(C('DB_PREFIX').'orders')->master(true)->where($order_condition)->save($order_update);
	        if(!$order_up_state){
	            throw  new \Exception('更新订单状态失败');
	        }
	        $order_goods =  $model->table(C('DB_PREFIX').'order_goods')->master(true)->where(array('order_id'=>$order_id))->select();
	        if(!empty($order_goods)){
	            foreach($order_goods as $goods){
	                $goods_condition   = array();
	                $goods_update_state = false;
	                $goods_id   = intval($goods['goods_id']);
	                $goods_num   = intval($goods['goods_num']);
	                $goods_update_state  = $model->table(C('DB_PREFIX').'goods')->master(true)->where(array('goods_id'=>$goods_id))->setInc('goods_storage',$goods_num);
					D('Goods')->delSkuCache($goods_id);
	                if($goods_update_state ===false){
	                    throw  new \Exception('商品库存加1失败');
	                }
	            }
	        }
	        $model->commit();
	        $log = '取消订单成功';
	        order_log($order_id, 'orders',$log,$operater_type,$operator_id,$operator_name);
	        return  true;      
	    }catch (\Exception $e){
	        $model->rollback();
	        $log = '取消订单失败,失败原因:'.$e->getMessage();
	        order_log($order_id, 'orders',$log,$operater_type,$operator_id,$operator_name);
	        return false;    
	    }
	}
	
	/**
	 * 确认收货
	 * @param 订单id $order_id
	 * @param 操作人类型 $operater_type
	 * @param 操作人id $operator_id
	 * @param 操作人姓名 $operator_name
	 * @return boolean
	 */
	static function changeOrderStateReceive($order_id,$operater_type = '',$operator_id=0,$operator_name=''){
	    $operator_id = intval($operator_id);
	    if(!in_array($operater_type,array('system','buyer'))){
	        return false;
	    }
	    if($operater_type =='system'){
	        $operator_id  = 0;
	        $operator_name = 'system';
	    }
	    if($operater_type=='buyer' &&!$operator_id){
	        return  false;
	    }
	    $order_id =  intval($order_id);
	    if(!$order_id){
	        return false;
	    }
	    $condition = array();
	    $condition['order_state'] =  30;
	    $condition['order_id'] = $order_id;
	    $order_info  =  M('orders')->master(true)->where($condition)->find();
	    if(empty($order_info)){
	        return  false;
	    }
	    try{
			$model = new Model();
			$model->master(true);
			$model->startTrans();
	        $order_condition  =  array();
	        $order_condition['order_id'] = $order_id;
	        $order_condition['order_state'] = 30;
	        if($operater_type=='buyer'){
	            $order_condition['buyer_id']  = $operator_id;
	        }
	        $order_update = array();
	        $order_update['order_state'] =  40;
	        $order_update['finnshed_time'] = time();
	        $order_up_state = $model->table(C('DB_PREFIX').'orders')->master(true)->where($order_condition)->save($order_update);
	        if(!$order_up_state){
	            throw  new \Exception('更新订单状态失败');
	        }
			$model->commit();
	        $log = '订单确认收货成功';
	        order_log($order_id, 'orders',$log,$operater_type,$operator_id,$operator_name);
	        return  true;
	    }catch (\Exception $e){
	        $model->rollback();
	        $log = '订单确认收货失败,失败原因:'.$e->getMessage();
	        order_log($order_id, 'orders',$log,$operater_type,$operator_id,$operator_name);
	        return false;
	    }
	}
	
}