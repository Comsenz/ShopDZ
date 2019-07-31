<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\BasketService;
use Common\Service\GoodsService;
use Common\Service\OrdersService;
use Common\Service\MemberService;
use Common\Service\CouponService;
use Common\Service\PaymentService;
use Common\Helper\ToolsHelper;
use Common\Service\IndexService;
class OrdersController extends BaseController {
	protected $member_id = 0;
	protected $max_buy_num = 99;
	public function _initialize() {
		parent::_initialize();
		if(!in_array(ACTION_NAME,array('commentlist'))) {
			$this->getMember();
			$this->member_id = $this->member_info['member_id'];
		}
	}
	//生成订单
	function addOrder() {
		$basketids = I('param.id');
		$basketid = explode(',',$basketids);
		$coupon_id = I('param.coupon_id',0,'intval');
		$address_id = I('param.address_id');
		$order_message = I('param.order_message');
		$coupon = $address = array();
		if(empty($basketid) || empty($address_id))
			$this->returnJson(1,'数据错误，请重试');
		$random = \Common\Helper\LoginHelper::random(2,$numeric=1);
		$address = MemberService::getAddressInfo(array('address_id'=>$address_id,'member_id'=>$this->member_id));
		
		if(empty($address)) 
			$this->returnJson(1,'地址信息有误，请重试');
		$baskets = BasketService::getGoodsInfo($basketid);
		if(empty($baskets)) {
			$this->returnJson(1,'购物车没有商品');
		}
		$coupon_id && $coupon = CouponService::getRedpacketList($this->member_id,$coupon_id);
		if(($coupon=current($coupon)) && empty($coupon['rpacket_use_status'])) {
			$this->returnJson(1,'优惠券已经过期');
		}
		$resultData = OrdersService::addOrder($this->member_info,$baskets,$address,$coupon,$order_message);
		$code = $resultData['code'];//返回状态码
		switch($code) {
			case 0:
				$this->returnJson(0,'success',$resultData);
			case 1:
				$this->returnJson(1,'请稍后重试',$resultData);
			case 2:
				$this->returnJson(1,'非法优惠券',$resultData);
			case 3:
				$this->returnJson(1,'库存不足',$resultData);
		}
	}
	//立即购买
	function buy() {
		$goods_id = I('param.id',0,'intval');
		$goods_num = I('param.num',0,'intval');
		$coupon_id = I('param.coupon_id',0,'intval');
		$address_id = I('param.address_id',0,'intval');
		$order_message = I('param.order_message');
		$coupon = $address = array();
		if(!intval($goods_num) || !intval($goods_id) || empty($address_id)) {
			$this->returnJson(1,'数据错误，请重试');
		}
		$goods_num = $goods_num > $this->max_buy_num ? $this->max_buy_num :$goods_num;

		$goods = GoodsService::getGood($goods_id);
		if(empty($goods))
				$this->returnJson(1,'物品不存在');
		$member_id = $this->member_info['member_id'];
		$address = MemberService::getAddressInfo(array('address_id'=>$address_id,'member_id'=>$member_id));
		if(empty($address)) 
			$this->returnJson(1,'地址信息有误，请重试');
		$coupon_id && $coupon = CouponService::getRedpacketList($this->member_id,$coupon_id);
		if(($coupon=current($coupon)) && empty($coupon['rpacket_use_status'])) {
			$this->returnJson(1,'优惠券已经过期');
		}
		$result = OrdersService::buy($this->member_info,$goods,$goods_num,$address,$coupon,$order_message);
		$code = $result['code'];//返回状态码
		switch($code) {
			case 0:
				$this->returnJson(0,'success',$result);
			case 1:
				$this->returnJson(1,'请稍后重试',$result);
			case 2:
				$this->returnJson(1,'非法优惠券',$result);
			case 3:
				$this->returnJson(1,'库存不足',$result);
		}
	}
	
	function buytlement () {
		$goodsinfo = array();
		$goods_id = I('param.id',0,'intval');
		$goods_num = I('param.num',0,'intval');
		if(!intval($goods_num) || !intval($goods_id)) {
			$this->returnJson(1,'数据错误，请重试');
		}
		$goods_num = $goods_num > $this->max_buy_num ? $this->max_buy_num :$goods_num;
		$goods = GoodsService::getGood($goods_id);
		if(empty($goods)) {
			$this->returnJson(1,'物品不存在');
		}
		
		$goods['goods_num'] = $goods_num;
		$goods['member_uid'] = $this->member_id;
		$return = OrdersService::getgoodsFromBasket(array($goods),array('isgroup'=>0,'is_shipping'=>1));
		$address = MemberService::getAddressList(array('member_id'=>$this->member_id));
		$coupon = CouponService::getRedpacketList($this->member_id,0,$state =1);

		$member = array(
			'username'=>$this->member_info['member_truename'],
		);
		$result = array(
			'goodsinfo'=>$return['goods_info'],
			'memberinfo'=>$member,
			'address'=>$address,
			'coupon'=>$coupon,//优惠券
			'shipping_fee'=>$return['shipping_fee'],
		);
		$this->returnJson(0,'success',$result);
	}
	//生成订单前一步，结算页面
	function settlement() {
		$uid = $this->member_info['member_id'];
		$basketids = I('param.id');
		$basketid = explode(',',$basketids);
		if(empty($basketid))
			$this->returnJson(1,'数据错误，请重试');

		$goodsinfo = BasketService::getGoodsInfo($basketid);
		if(empty($goodsinfo)) {
			$this->returnJson(1,'物品不存在');
		}
		$return = OrdersService::getgoodsFromBasket($goodsinfo,array('isgroup'=>0,'is_shipping'=>1));
		$address = MemberService::getAddressList(array('member_id'=>$this->member_id));
		$coupon = CouponService::getRedpacketList($this->member_id,0,$state =1);
		$member = array(
			'username'=>$this->member_info['member_truename'],
		);
		$result = array(
			'goodsinfo'=>$return['goods_info'],
			'memberinfo'=>$member,
			'address'=>$address,
			'coupon'=>$coupon,//优惠券
			'shipping_fee'=>$return['shipping_fee'],
		);
		$this->returnJson(0,'success',$result);
	}
	
	function getmyorders() {
		$list = $details = array();
		$page = I('param.page',1,'intval');
		$prepage = I('param.prepage',8,'intval');
		$list = OrdersService::getMyOrders($this->member_id,$type=null,$page,$prepage);
		//$listcount = OrdersService::getMyOrdersCount($this->member_id,$type=null);
		//$list['goodcount'] = $listcount;
		foreach($list as $k => $v) {
			$details = OrdersService::getOrdersGoodsListOne($v['order_id']);
			$list[$k]['gooddetails'] = $details;
			$time = (C('ORDER_TIME')+$v['add_time']-TIMESTAMP);
			$list[$k]['ordertime'] = $time > 0 ? ceil($time/60) : 0;
			$list[$k]['goodcount'] = OrdersService::getOrdersGoodsListCount($v['order_id']);
		}
		if(empty($list)){
			$this->returnJson(1,'订单已全部显示！',array('orderlist'=>''));
		}
		$this->returnJson(0,'success',array('orderlist'=>$list));
	}
	 
	public function orderdetails () {
		$order_sn = I('param.order_sn');

		if(empty($order_sn))
			$this->returnJson(1,'数据错误，请重试');
		$data = OrdersService::getOrderByOrderSn($order_sn,$this->member_id);
		$order_id = $data['order_id'];
		
		if(empty($data))
			$this->returnJson(1,'订单已经删除或者不存在，请重试');
		$details = OrdersService::getOrdersGoodsList($order_id);
		foreach($details as $de=>$dv) {
			$returngoods = array();
			if(!empty($dv['rg_id'])) {
				$returngoods = OrdersService::getReturnGoodsByRgId($dv['rg_id']);
			}
			$status =  $returngoods ? $returngoods['status'] : 0;
			$details[$de]['status'] = $status;
			$details[$de]['return_sn'] = $returngoods ? $returngoods['return_sn'] : '';
		}
		/*
		if(!empty($data['refund_state'])) {
			$refund = OrdersService::getRefundByRefundSn($data['refund_sn']);
			$data['refund_sn'] = $refund['refund_sn'];
		}else{
			$data['refund_sn'] = '';
		}
		*/
		$data['goodcount'] = count($details);
		$time = (C('ORDER_TIME')+$data['add_time']-TIMESTAMP);
		$data['ordertime'] = $time > 0 ? ceil($time/3600) : 0;
		$data['add_time'] = date('Y-m-d H:i:s',$data['add_time']);
		$data['gooddetails'] = $details;
		$data['buyinfo'] = OrdersService::getOrderCommon($order_id,'reciver_info');

		$this->returnJson(0,'success',$data);
	}
	 
	public function changeorder() {
		$order_sn = I('param.order_sn');
		$op = I('param.op');
		if(empty($order_sn) || !in_array($op,array('del','ok','cancel','rubbish')))
			$this->returnJson(1,'数据错误，请重试');
		$data = OrdersService::getOrderByOrderSn($order_sn,$this->member_id);
		if(empty($data))
			$this->returnJson(1,'订单已经删除或者不存在，请重试');
		$order_id = $data['order_id'];
		switch($op) {
			case 'del':
				$result = OrdersService::delOrder($order_id,$this->member_id);
				break;
			case 'ok':
				$result = OrdersService::opOrder($order_id,$this->member_id,40);
				break;
			case 'cancel':
				$result = OrdersService::opOrder($order_id,$this->member_id,0);
				break;
			case 'rubbish':
				$result = OrdersService::opRubbish($order_id,$this->member_id,1);
				break;
		}
		if($result)
			$this->returnJson(0,'success');
		else
			$this->returnJson(1,'请重试');
	}
	 
	public function evaluate() {
		$message = I('param.message');
		$evaluate_images = I('param.evaluate_images');
		$rec_id = I('param.rec_id');
		if(empty($rec_id))
			$this->returnJson(1,'数据错误，请重试');
		$good = OrdersService::getOrderGoodsById($rec_id);
		if(empty($good))
			$this->returnJson(1,'订单已经删除或者不存在，请重试');
		if(!empty($message)) {//评论
			$evaluate_state = $good['evaluate_state'];
			if($evaluate_state >=1) 
				$this->returnJson(1,'您已评价，请勿重复评价');
			$count = count($evaluate_images);
			if($count > 4) {
				$evaluate_images = array_splice($evaluate_images,4);
			}
			$evaluate_images = ToolsHelper::format_url($evaluate_images,$path='Evaluate');
			$content['message'] = $this->cutstr($message,300,'');
			$content['evaluate_state'] = $evaluate_state;
			$content['geval_image'] = implode("\t",$evaluate_images);
			$result = OrdersService::evaluate($this->member_info,$good,$content);
		}else{
			$this->returnJson(0,'success',$good);
		}
		if($result)
			$this->returnJson(0,'success');
		else
			$this->returnJson(1,'请重试');
	}
	
	public function myevaluate() {
		$order_sn = I('param.order_sn');
		$rec_id = I('param.rec_id');
		if(empty($order_sn))
			$this->returnJson(1,'数据错误，请重试');
		$data = OrdersService::getOrderByOrderSn($order_sn,$this->member_id);
		if(empty($data))
			$this->returnJson(1,'数据错误，请重试');
		$order = OrdersService::getOrderGoodsById($rec_id);
		if(empty($order))
			$this->returnJson(1,'数据错误，请重试');
		$comment = OrdersService::getCommentByGoodId($data['order_id'],$rec_id);
		$this->returnJson(0,'success',array('order'=>$order,'comment'=>$comment));
	}
	
	public function commentlist() {
		$goods_common_id = I('param.goods_id');
		$page = I('param.page',0,'intval');
		$prepage = I('param.prepage',10,'intval');
		if(empty($goods_common_id))
			$this->returnJson(1,'数据错误，请重试');
		$list = OrdersService::getOrdersComment($goods_common_id,$member_id=0,$page,$prepage);
		$listcount = OrdersService::getOrdersCommentCount($goods_common_id);
		$this->returnJson(0,'success',array('orderlist'=>$list,'ordercount'=>$listcount));
	}
	
	function getordergoods() {
		$order_sn = I('param.order_sn');
		$rec_id = I('param.rec_id');
		if(empty($order_sn))
			$this->returnJson(1,'数据错误，请重试');
		$data = OrdersService::getOrderByOrderSn($order_sn,$this->member_id);
		if(empty($data))
			$this->returnJson(1,'数据错误，请重试');
		$rec_info = OrdersService::getOrderGoodsById($rec_id,'rec_id,goods_id,goods_name,goods_price,goods_num,goods_image,buyer_id');
		if($rec_info['buyer_id'] != $this->member_id)
			$this->returnJson(1,'数据错误，请重试');
		$this->returnJson(1,'success',$rec_info);
	}
}