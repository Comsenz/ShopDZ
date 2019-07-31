<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\GroupService;
use Common\Service\OrdersService;
use Common\Service\MemberService;
use Common\Service\GoodsService;
class GroupController extends BaseController {
	//首页
	public function grouplist(){
		$page = I('post.page',1,'intval');
		$prepage = I('param.prepage',10,'intval');
		$time = TIMESTAMP;
		$where['starttime'] = array('elt',$time);
		$where['endtime'] = array('egt',$time);
		$list = GroupService::getGroupList($where, $page, $prepage);
		$this->returnJson(0,'sucess',$list);
	}
	//生成订单前一步，结算页面
	public function grouptlement() {
		
		$active_id = I('param.active_id',0,'intval');
		$group_id = I('param.group_id',0,'intval');
		if( !intval($active_id) ) {
			$this->returnJson(1,'数据错误，请重试');
		}
		$this->getMember();
		$group = GroupService::getGroup($active_id);
		if(empty($group)) {
			$this->returnJson(1,'拼团活动不存在');
		}
		$goods['goods_id'] = $group['goods_id'];
		$goods['goods_price'] = $group['group_price'];

		$goods['goods_num'] = 1;
		$goods['member_uid'] = $this->member_info['member_id'];
		$return = OrdersService::getgoodsFromBasket(array($goods),array('is_shipping'=>$group['is_shipping'],'isgroup'=>1));
		$address = MemberService::getAddressList(array('member_id'=>$this->member_info['member_id']));
		//$coupon = CouponService::getRedpacketList($this->member_id,0,$state =1);
		$member = array(
			'username'=>$this->member_info['member_truename'],
		);
		$result = array(
			'goodsinfo'=>$return['goods_info'],
			'memberinfo'=>$member,
			'address'=>$address,
			'coupon'=>array(),//优惠券
			'shipping_fee'=>$return['shipping_fee'],
		);
		$this->returnJson(0,'success',$result);
	}
	
	function group () {
		$active_id = I('param.active_id',0,'intval');
		$group_id = I('param.group_id',0,'intval');
		$coupon_id = I('param.coupon_id',0,'intval');
		$address_id = I('param.address_id');
		$isWX = isWeixinBrowser();
		if( empty($active_id) || empty($address_id) ) {
			$this->returnJson(1,'数据错误，请重试');
		}
		$this->getMember();
		$group = GroupService::getGroup($active_id);
		if(empty($group)) {
			$this->returnJson(1,'拼团活动不存在');
		}
		$add_num = $group['add_num'];//每人参团限制
		$group_person_num = $group['group_person_num'];//成团人数
		$group_hour = $group['group_hour'];//组团时限
		if($group_id){//参团
			$where['id'] = $group_id;
			$where['active_id'] = $active_id;
			$groupinfo = GroupService::getGroupGroup($where);
			if(!empty($groupinfo['status'])){
				$this->returnJson(1,'该团已经结束');
			}
			if($groupinfo['num'] >= $group_person_num) {
				$this->returnJson(1,'该团人数已满');
			}
			
			if($groupinfo['add_time'] + $group_hour*3600 <= TIMESTAMP) {
				$this->returnJson(1,'该团已经结束');
			}
			$where_join['id'] = $group_id;
			$where_join['active_id'] = $active_id;
			$where_join['buyer_id'] = $groupinfo['buyer_id'];
			$where_join['refund_status'] = 2;//0 默认，1退款成功 -1 退款失败 2 支付成功
			$joinlist = GroupService::getGroupJoin($where_join);
			if(empty($joinlist)){
				$this->returnJson(1,'该团长还没有支付成功');
			}
			
			/////////////////////////////////////////////
			$iscreategrouop = false;
		}else{//创建团 ,
			if(!($group['starttime'] <= TIMESTAMP && TIMESTAMP <= $group['endtime']) ) {
				$this->returnJson(1,'拼团活动暂未开始');
			}
			if( TIMESTAMP >= $group['endtime'] ) {
				$this->returnJson(1,'拼团活动已经结束');
			}
			/*
			$groupWhere['active_id'] = $active_id;
			$groupWhere['status'] = array('neq','-1');//状态0 进行中 1 已成功 -1失败
			$getgroupgroupcount = GroupService::getGroupGroupCount($groupWhere);
			$max_group = $group['max_group'];//最大开团数量
			if($getgroupgroupcount && ($getgroupgroupcount >= $max_group)) {
				$this->returnJson(1,'开团数量已达到活动上线');
			}*/
			
			//最大成团数量
			//开团限制 他的团进行中，不能再开团，能参团
			
			$iscreategrouop = true;
		}
		
		$status = $this->_checkgroup($this->member_info['member_id'],$group,$group_id);
		if(!empty($status['status'])){
			$this->returnJson($status['status'],$status['tips']);
		}
		
		$address = MemberService::getAddressInfo(array('address_id'=>$address_id,'member_id'=>$this->member_info['member_id']));
		if(empty($address))
			$this->returnJson(1,'地址信息有误，请重试');
		$goods = GoodsService::getGood($group['goods_id']);
		unset($goods['goods_price']);
		$goods['group_person_num'] = $group['group_person_num'];
		$goods['is_shipping'] = $group['is_shipping'];
		$goods['active_id'] = $group['id'];
		//$goods['max_group'] = $group['max_group'];
		$goods['goods_price'] = $group['group_price'];//团购的价格
		$result = OrdersService::group($this->member_info,$goods,$goods_num=1,$address,$iscreategrouop,$groupinfo);
		
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
			case 4:
				$this->returnJson(1,'已经达到活动的成团数量',$result);
		}
	}
	
	private function  _checkgroup($member_id,$group,$group_id =0) {
		$table_pre =  C('DB_PREFIX');
		$whereJoin["{$table_pre}group_join".'.buyer_id'] = $member_id;
		$whereJoin["{$table_pre}group_join".'.active_id'] = $group['id'];
		$whereJoin['invisible'] = 0;
		$whereJoin['status'] = array('in','0,1');//已成功和未支付
		$groupJoin = GroupService::getgroupByJoin($whereJoin);//获取所有的已支付和未支付的
		$groupids = $groupidTrade = array();
		$status = array('status' => 0,'tips'=>'数据正常');
		foreach( $groupJoin as $k => $v ) {
			$groupids[$v['group_id']] = $v['group_id'];
			if($v['trade_no']) {//支付过钱
				$groupidTrade[$v['group_id']] = $v['group_id'];
			}
		}
		$joinCount = count($groupids);
		if($group_id && $groupids && in_array($group_id,$groupids)){//参团,并且有该活动的参团记录
			//$status = -1;//您已经参与过该团
			$status = array('status' => 1,'tips'=>'您已经参与过该团');
		}
		
		if($joinCount && $joinCount >= $group['add_num']){
			$status = array('status' => 3,'tips'=>'您已经达到参团上限');//您已经达到参团上限
		}
		if($groupidTrade && count($groupidTrade) != $joinCount) {	//参与过该活动下的团购，有未支付的，提示支付
			$status = array('status' => 2,'tips'=>'您已经参与过该活动，请您支付');
		}
		
		return $status;
	}
	
	function detail() {
		$active_id = I('param.active_id',0,'intval');
		$key = I('param.key');
		if( empty($active_id) ) {
			$this->returnJson(1,'数据错误，请重试');
		}
		$group = GroupService::getGroup($active_id);
		if(empty($group)) {
			$this->returnJson(1,'拼团活动不存在');
		}
		$status = array('status' => 0,'tips'=>'数据正常');
		if(!empty($key)){
			$user_token_info = MemberService::getUserTokenInfoByToken($key);
			if(!empty($user_token_info)) {
				$this->member_info = $user_token_info;
				$status = $this->_checkgroup($this->member_info['member_id'],$group,$groupid=0);
			}
		}
		
		$goods = GoodsService::getGood($group['goods_id']);
		if( $goods['goods_storage'] < 1 ) {
			$status = array('status' => 4,'tips'=>'库存不足');
		}
		$goods_images = GoodsService::getGoodsImages($goods['goods_common_id']);
		$group && $group['status'] = $status;
		$data = array(
			'group'=>$group,
			'goods'=>array('goods_images'=>$goods_images,'goods_price'=>$goods['goods_price'],'goods_common_id'=>$goods['goods_common_id']),
		);
		unset($group,$goods,$goods_images);
		$this->returnJson(0,'success',$data);
	}
	
	function groupjoin() {
		$active_id = I('param.active_id',0,'intval');
		$group_id = I('param.group_id',0,'intval');
		$key = I('param.key');
		if( empty($active_id) || empty($group_id) ) {
			$this->returnJson(1,'数据错误，请重试');
		}
		$group = GroupService::getGroup($active_id);
		if(empty($group)) {
			$this->returnJson(1,'拼团活动不存在');
		}
		$where['id'] = $group_id;
		$where['active_id'] = $active_id;
		$groupinfo = GroupService::getGroupGroup($where);
		if(empty($groupinfo)) {
			$this->returnJson(1,'拼团活动不存在');
		}
		if(!empty($key)){
			$user_token_info = MemberService::getUserTokenInfoByToken($key);
			if(!empty($user_token_info)) {
			   $this->member_info = $user_token_info;
			}
		}
		$joinwhere['group_id'] = $group_id;
		$joinwhere['invisible'] = 0;
		$order_sn = 0;
		$groupJoin = GroupService::getGroupJoin($joinwhere);
		$status = array('status' => 0,'tips'=>'数据正常');
		foreach( $groupJoin as $k =>$v ) {
			if($v['buyer_id'] == $this->member_info['member_id']) {
				if(empty($v['trade_no'])) {
					$status = array('status' => 2,'tips'=>'您已经参与过该活动，请您支付');
				}else{
					$status = array('status' => 5,'tips'=>'已经支付');
				}
				$order_sn = $v['order_sn'];
			}
			$uids[] = $v['buyer_id'];
		}


		$where_join['id'] = $groupinfo['id'];
		$where_join['active_id'] = $active_id;
		$where_join['buyer_id'] = $groupinfo['buyer_id'];
		$where_join['refund_status'] = 0;//0 默认，1退款成功 -1 退款失败 2 支付成功
		$joinlist = GroupService::getGroupJoin($where_join);
		if(!empty($joinlist) && $this->member_info['member_id'] == $groupinfo['buyer_id']){
			$status = array('status' => 6,'tips'=>'团长还没有支付成功');// 团长登陆
		}elseif(!empty($joinlist)){
			$status = array('status' => 7,'tips'=>'该团长还没有支付成功');// 游客登陆
		}

		
		$avatars = MemberService::getAvatars($uids);
		foreach( $groupJoin as $k =>$v ) {
			$groupJoin[$k]['member_avatar'] = $avatars[$v['buyer_id']];
		}
		$goods = GoodsService::getGood($group['goods_id']);
		/* 组团结束时间 */
		$groupinfo['group_end_time'] = $groupinfo['add_time'] + $group['group_hour']*3600;
		$group && $group['status'] = $status;
		$group['group_image'] = $goods['goods_image'];
		$data = array(
			'order_sn'=>$order_sn,
			'group'=>$group,
			'groupinfo'=>$groupinfo,
			'groupjoin'=>$groupJoin,
		);
		unset($group,$groupJoin,$uids,$avatars);
		$this->returnJson(0,'success',$data);
	}
	
	function mygroup() {
		$page = I('param.page',1,'intval');
		$prepage = I('param.prepage',10,'intval');
		$this->getMember();
		$uid = $this->member_info['member_id'];
		$where['buyer_id'] = $uid;
		$where['invisible'] = 0;
		$groupJoin = GroupService::getGroupJoin($where,$page,$prepage,$order = ' add_time desc ');
		if(empty($groupJoin)){
			$this->returnJson(0,'暂无参加拼团信息','');
		}
		foreach( $groupJoin as $k => $v ) {
			$active_ids[$v['active_id']] = $v['active_id'];
			$group_ids[$v['group_id']] = $v['group_id'];
		}
		$group = GroupService::getGroupByIds($active_ids);
		foreach( $group as $gk => $gv ) {
			$goods_id[$v['goods_id']] = $gv['goods_id'];
		}
		$wheregroup['goods_id'] = array('in', implode(',',$goods_id));
		$goods_image = GoodsService::getGoods($wheregroup);
		foreach($goods_image as $ik =>$iv ) {
			$goods_images[$iv['goods_id']] = $iv;
		}
		$getids = implode(',', $group_ids);
		$groupwhere['id'] =  array ('in',$getids);
		$group_group = GroupService::getGroupGroups($groupwhere);
		foreach( $groupJoin as $k => $v ) {
			$group[$v['active_id']]['group_image'] = $goods_images[$group[$v['active_id']]['goods_id']]['goods_image'];
			$groupJoin[$k]['group'] = $group[$v['active_id']];
			$groupJoin[$k]['group_group'] = $group_group[$v['group_id']];
		}
		unset($group,$group_group);
		$this->returnJson(0,'success',$groupJoin);
	}
	
	function grouppayinfo() {
		$this->getMember();
		//$group_id = I('param.group_id',0,'intval');
		$order_sn = I('param.order_sn');
		if(empty($order_sn) ) {
			$this->returnJson(1,'数据错误，请重试');
		}
		$uid = $this->member_info['member_id'];
		$group_join = GroupService::getGroupJoinBySn($order_sn,$uid);
		$groupinfo = GroupService::getGroupGroup(array('id'=>$group_join['group_id']));
		$common = OrdersService::getOrderCommon($group_join['id']);
		$group = GroupService::getGroup($group_join['active_id']);
		
		$common['join_time'] = $group_join['add_time_text'];
		$common['order_sn'] = $group_join['order_sn'];
		$data = $group_join;
		$data['gooddetails'] = $group;
		$data['buyinfo'] = $common;
		$data['group_group'] = $groupinfo;
		unset($groupinfo,$common,$group,$group_join);
		$this->returnJson(0,'success',$data);
	}
}