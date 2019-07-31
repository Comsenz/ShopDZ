<?php
/**
 * 插件需要执行的方法 逻辑定义  
 */
use App\Controller\BaseController;
use plugins\group\GroupModel;
use plugins\group\pay;
use Common\Service\OrdersService;
use Common\Service\MemberService;
use Common\Service\GoodsService;
use Common\Helper\ToolsHelper;
use Common\Model\GoodsModel;


class group extends BaseController
{
    public $model = null; // model对象
    public $conf = null; // 插件配置对象
    /**
     * 析构流函数
     */
	public function __construct($conf) {
		$this->model = new GroupModel();
		$this->conf = $conf;
	}
	//首页
	public function grouplist(){
		$page = I('post.page',1,'intval');
		$prepage = I('param.prepage',4,'intval');
		$time = TIMESTAMP;
		$where['starttime'] = array('elt',$time);
		$where['endtime'] = array('egt',$time);
		$list = $this->model->getGroupList($where, $page, $prepage);
		unset($where);
		$all_goods_id = $list['all_goods_id'];
		unset($list['all_goods_id']);
		$goods_where['goods_id'] = array('in',implode(",",$all_goods_id));
		$goods_info = GoodsService::getGoods($goods_where);
		$goods_info_new = ToolsHelper::group_same_key($goods_info,'goods_id');
		unset($goods_info);
		foreach($list as $k => $v) {
			$list[$k]['goods_price'] = $goods_info_new[$v['goods_id']]['goods_price'];
		}

		$this->returnJson(0,'sucess',$list);
	}
	private function  _checkgroup($member_id,$group,$group_id =0) {
		$table_pre =  C('DB_PREFIX');
		$whereJoin["{$table_pre}group_join".'.buyer_id'] = $member_id;
		$whereJoin["{$table_pre}group_join".'.active_id'] = $group['id'];
		$whereJoin['invisible'] = 0;
		$whereJoin['status'] = array('in','0,1');//已成功和未支付
		$groupJoin = $this->model->getgroupByJoin($whereJoin);//获取所有的已支付和未支付的
		$groupids = $groupidTrade = $already_pay =  array();
		$status = array('status' => 0,'tips'=>'数据正常');
		foreach( $groupJoin as $k => $v ) {
			$groupids[$v['group_id']] = $v['group_id'];
			if($v['trade_no']) {//支付过钱
				$groupidTrade[$v['group_id']] = $v['group_id'];
			}
			if($v['trade_no'] && $v['refund_status'] == 2) {
				$already_pay[] = $v['order_sn'];
			}//已经付款
		}
		$joinCount = count($groupids);
		if($group_id && $groupids && in_array($group_id,$groupids)){//参团,并且有该活动的参团记录
			//$status = -1;//您已经参与过该团
			$status = array('status' => 1,'tips'=>'您已经参与过该团');
		}
		if($group['add_num']){
			if($already_pay ){
				$status = array('status' => 3,'tips'=>'您已经达到参团上限');//您已经达到参团上限
			}
		}
		if($joinCount && count($groupidTrade) != $joinCount) {	//参与过该活动下的团购，有未支付的，提示支付
			$status = array('status' => 2,'tips'=>'您已经参与过该活动，请您支付');
		}
		return $status;
	}
	//拼团详情
	public 	function detail() {

		$active_id = I('param.active_id',0,'intval');
		$key = I('param.key');
		if( empty($active_id) ) {
			$this->returnJson(1,'数据错误，请重试');
		}
		$group_model = new GroupModel();
		$group = $this->model->getGroup($active_id);
	
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
		$mode = new GoodsModel();
		$goods_detail= $mode->getGoodsDetail($goods['goods_common_id']);
		$goods_datail = htmlspecialchars_decode(stripslashes($goods_detail['goods_detail']));
		if( $goods['goods_storage'] < 1 ) {
			$status = array('status' => 4,'tips'=>'库存不足');
		}
		$goods_images = GoodsService::getGoodsImages($goods['goods_common_id']);
		$group && $group['status'] = $status;
		$data = array(
			'group'=>$group,
			'goods'=>array('goods_images'=>$goods_images,'goods_price'=>$goods['goods_price'],'goods_common_id'=>$goods['goods_common_id'],'goods_detail'=>$goods_datail,'spec_name'=>$goods['spec_name']),
		);
		unset($group,$goods,$goods_images);
		$this->returnJson(0,'success',$data);
	}

//拼团邀请
	public function groupjoin() {

		$active_id = I('param.active_id',0,'intval');
		$group_id = I('param.group_id',0,'intval');
		$key = I('param.key');
		if( empty($active_id) || empty($group_id) ) {
			$this->returnJson(1,'数据错误，请重试');
		}

		$group = $this->model->getGroup($active_id);
	
		if(empty($group)) {
			$this->returnJson(1,'拼团活动不存在');
		}
		$where['id'] = $group_id;
		$where['active_id'] = $active_id;
		$groupinfo = $this->model->getGroupGroup($where);
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
		$groupJoin = $this->model->getGroupJoin($joinwhere);
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
		$joinlist = $this->model->getGroupJoin($where_join);
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
		$mode = new GoodsModel();
	
		$goods_detail= $mode->getGoodsDetail($goods['goods_common_id']);
		$goods['goods_detail']= htmlspecialchars_decode(stripslashes($goods_detail['goods_detail']));

		/* 组团结束时间 */
		$groupinfo['group_end_time'] = $groupinfo['add_time'] + $group['group_hour']*3600;
		$group && $group['status'] = $status;
		//$group['group_image'] = $goods['goods_image'];
		$groupinfo['join_num'] = $this->model->getGroupJionPaySuccess($group_id);
		$data = array(
			'order_sn'=>$order_sn,
			'group'=>$group,
			'groupinfo'=>$groupinfo,
			'groupjoin'=>$groupJoin,
			'goods'=>$goods
		);
		unset($group,$groupJoin,$uids,$avatars);
		$this->returnJson(0,'success',$data);
	}

	public function grouptlement() {
		$active_id = I('param.active_id',0,'intval');
		$group_id = I('param.group_id',0,'intval');
		$order_sn = I('param.order_sn',0,'trim');
		$this->getMember();
		if($order_sn) {
			$this->ordersnbuy();
		}else{
			$this->buy();
		}
	}
		//已有订单前一步，结算页面
	private function ordersnbuy() {
		$order_sn = I('param.order_sn',0,'trim');
		if( !intval($order_sn) ) {
			$this->returnJson(1,'数据错误，请重试');
		}
		$joininfo = $this->model->getGroupJoinBySn($order_sn,$this->member_info['member_id']);
		if(empty($joininfo) || !empty($joininfo['invisible']) || !empty($joininfo['refund_status'])) {
			$this->returnJson(1,'订单不存在');
		}
		if($joininfo['trade_no'] || $joininfo['invisible'] == -1 ) {
			$this->returnJson(5,'订单已支付或已取消');
		}
		$active_id = $joininfo['active_id'];
		$order_id = $joininfo['id'];
		$group = $this->model->getGroup($active_id);
		$goods['goods_id'] = $group['goods_id'];
		$goods['goods_price'] = $group['group_price'];
		$goods['goods_num'] = 1;
		$goods['member_uid'] = $this->member_info['member_id'];
		$accept_address = OrdersService::getOrderCommon($order_id);
		$daddress_id = $accept_address['daddress_id'];
		$return = OrdersService::getgoodsFromBasket(array($goods),array('is_shipping'=>$group['is_shipping'],'isgroup'=>1));
		$address = MemberService::getAddressList(array('member_id'=>$this->member_info['member_id']));
		//$coupon = CouponService::getRedpacketList($this->member_id,0,$state =1);
		$member = array(
			'username'=>$this->member_info['member_truename'],
		);
		$order = array('order_sn'=>$joininfo['order_sn']);
		$index = Common\Helper\ToolsHelper :: exists_seach_key($daddress_id,'address_id',$address);
		$current_address = $address[$index];
		unset($address[$index]);
		array_unshift($address,$current_address);
		$result = array(
			'goodsinfo'=>$return['goods_info'],
			'memberinfo'=>$member,
			'address'=>$address,
			'coupon'=>array(),//优惠券
			'shipping_fee'=>$return['shipping_fee'],
			'orderinfo'=>$order,
		);
		$this->returnJson(0,'success',$result);
	}
	
		//生成订单前一步，结算页面
	private function buy() {
		$active_id = I('param.active_id',0,'intval');
		$group_id = I('param.group_id',0,'intval');
		$order_sn = I('param.order_sn',0,'trim');
		$this->getMember();

		if( !intval($active_id) ) {
			$this->returnJson(1,'数据错误，请重试');
		}
		$group = $this->model->getGroup($active_id);
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
		if(empty($group_id)) {
			foreach($return['goods_info'] as $k => $v) {
				$return['goods_info'][$k]['goods_price'] = $this->model->welfare_price($group,$return['goods_info'][$k]['goods_price'] );
			}
		}
		$result = array(
			'goodsinfo'=>$return['goods_info'],
			'memberinfo'=>$member,
			'address'=>$address,
			'coupon'=>array(),//优惠券
			'shipping_fee'=>$return['shipping_fee'],
			'orderinfo'=>array(),
		);
		$this->returnJson(0,'success',$result);
	}
	
	function mygroup() {
		$page = I('param.page',1,'intval');
		$prepage = I('param.prepage',4,'intval');
		$this->getMember();
		$uid = $this->member_info['member_id'];
		$where['buyer_id'] = $uid;
		$where['invisible'] = 0;
		$groupJoin = $this->model->getGroupJoin($where,$page,$prepage,$order = ' add_time desc ');
		if(empty($groupJoin)){
			$this->returnJson(0,'暂无参加拼团信息','');
		}
		foreach( $groupJoin as $k => $v ) {
			$active_ids[$v['active_id']] = $v['active_id'];
			$group_ids[$v['group_id']] = $v['group_id'];
		}
		$group = $this->model->getGroupByIds($active_ids);
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
		$group_group = $this->model->getGroupGroups($groupwhere);
		foreach( $groupJoin as $k => $v ) {
			$group[$v['active_id']]['spec_name'] = $goods_images[$group[$v['active_id']]['goods_id']]['spec_name'];
			$group[$v['active_id']]['goods_price'] = $goods_images[$group[$v['active_id']]['goods_id']]['goods_price'];
			$groupJoin[$k]['group'] = $group[$v['active_id']];
			$groupJoin[$k]['group_group'] = $group_group[$v['group_id']];
		}
		unset($group,$group_group);
		$this->returnJson(0,'success',$groupJoin);
	}
	
	function groupgroup() {
		$this->getMember();
		$order_sn = I('param.order_sn',0,'trim');
		if(!empty($order_sn)) {
			$this->updategroup();
		}else{
			$this->creategroup();//创建 活动
		}
	}
	//
	private function updategroup() {
		$order_sn = I('param.order_sn',0,'trim');
		$joininfo = $this->model->getGroupJoinBySn($order_sn,$this->member_info['member_id']);
		$address_id = I('param.address_id');
		if( empty($address_id) ) {
			$this->returnJson(1,'数据错误，请重试');
		}
		if(empty($joininfo)) {
			$this->returnJson(1,'订单不存在');
		}
		if(($joininfo['trade_no'] && empty($joininfo['invisible']) )) {
			$this->returnJson(5,'订单已支付或已取消');
		}
		$address = MemberService::getAddressInfo(array('address_id'=>$address_id,'member_id'=>$this->member_info['member_id']));
		if(empty($address))
			$this->returnJson(1,'地址信息有误，请重试');
		$order_id = $joininfo['id'];
		$order = array(
			'order_id'=>$joininfo['id'],
			'order_sn'=>$order_sn,
		);
		$result = $this->model->updateOrderCommonByOrderId($order,$address);
		$code = $result['code'];//返回状态码
		if(empty($code)) {
			$this->returnJson(0,'success',$result);
		}
		$this->returnJson(1,'请稍后重试',$result);
	}
	//开团
	private function  creategroup () {
		$active_id = I('param.active_id',0,'intval');
		$group_id = I('param.group_id',0,'intval');
		$coupon_id = I('param.coupon_id',0,'intval');
		$address_id = I('param.address_id');
		$isWX = isWeixinBrowser();
		if( empty($active_id) || empty($address_id) ) {
			$this->returnJson(1,'数据错误，请重试');
		}
	
		$group = $this->model->getGroup($active_id);
		if(empty($group)) {
			$this->returnJson(1,'拼团活动不存在');
		}
		$add_num = $group['add_num'];//每人参团限制
		$group_person_num = $group['group_person_num'];//成团人数
		$group_hour = $group['group_hour'];//组团时限
		if($group_id){//参团
			$where['id'] = $group_id;
			$where['active_id'] = $active_id;
			$groupinfo = $this->model->getGroupGroup($where);
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
			$joinlist = $this->model->getGroupJoin($where_join);
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
		$goods['goods_price'] = $group['group_price'];//团购的价格
		$groupinfo['welfare'] = array(
			'head_welfare_type'=>$group['head_welfare_type'],
			'head_num'=>$group['head_num'],
			'head_welfare '=>$group['head_welfare'],
		);
		$result = $this->model->group($this->member_info,$goods,$address,$iscreategrouop,$groupinfo,$this->conf);
		
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
			case 5:
				$this->returnJson(1,'订单价格非法',$result);
		}
	}
	
	function groupcheck() {
		$active_id = I('param.active_id',0,'intval');
		$group_id = I('param.group_id',0,'intval');
		$coupon_id = I('param.coupon_id',0,'intval');

		$isWX = isWeixinBrowser();
		if(!$isWX){
			$this->returnJson(1,'请在微信浏览器中使用！');
		}
		if( empty($active_id)) {
			$this->returnJson(1,'数据错误，请重试');
		}
		$this->getMember();
		$group = $this->model->getGroup($active_id);
		if(empty($group)) {
			$this->returnJson(1,'拼团活动不存在');
		}
		$goods = GoodsService::getGood($group['goods_id']);
		$add_num = $group['add_num'];//是否限购
		$group_person_num = $group['group_person_num'];//成团人数
		$group_hour = $group['group_hour'];//组团时限
		if(!($group['starttime'] <= TIMESTAMP && TIMESTAMP <= $group['endtime']) ) {
			$this->returnJson(1,'拼团活动暂未开始');
		}
		if( TIMESTAMP >= $group['endtime'] ) {
			$this->returnJson(1,'拼团活动已经结束');
		}
		if( $goods['goods_storage'] < 1) {
			$this->returnJson(1,'库存不足');
		}
		if($group_id){//参团
			$where['id'] = $group_id;
			$where['active_id'] = $active_id;
			$groupinfo = $this->model->getGroupGroup($where);
			if(!empty($groupinfo['status'])){
				$this->returnJson(1,'该团已经结束');
			}
			if($groupinfo['num'] >= $group_person_num) {
				$this->returnJson(4,'对不起，您来晚了。请重新开团');
			}
			if($groupinfo['add_time'] + $group_hour*3600 <= TIMESTAMP) {
				$this->returnJson(1,'该团已经结束');
			}
			$where_join['id'] = $group_id;
			$where_join['active_id'] = $active_id;
			$where_join['invisible'] = array('neq',-1);
			$where_join['buyer_id'] = $this->member_info['member_id'];
			$joinlist =  $this->model->getGroupJoin($where_join); //是否有该参加活动
		}else{
			$where_join['active_id'] = $active_id;
			$where_join['invisible'] = array('neq',-1);
			$where_join['buyer_id'] = $this->member_info['member_id'];
			$joinlist =  $this->model->getGroupJoin($where_join); //是否有该参加活动
		}
		file_put_contents(__ROOT__.'./a.txt',var_export($joinlist,true),FILE_APPEND);
		$group_join = $already_pay = array();
		foreach($joinlist as $k => $v){
			if( empty($v['invisible']) && empty($v['refund_status']) ){
				$group_join[] = $v['order_sn'];
			}//未付款
			if($v['trade_no']  && $v['refund_status'] ==2) {
				$already_pay[] = $v['order_sn'];
			}//已经付款
		}
		if($add_num){
			if(!empty($already_pay)){
				$this->returnJson(1,'对不起，您已达到活动参与上限。');
			}
		}
		if($group_join){//当前活动是否有待支付的
			$this->returnJson(3,'订单待支付。',array('order_sn'=>$group_join[0]));
		}

		$join = array(
			'invisible'=>0,
			'refund_status'=>0,
			'active_id'=>array('NEQ',$active_id),
			'buyer_id'=>$this->member_info['member_id']
			);
		$join_nopay =  $this->model->getGroupJoin($join);
		if(!empty($join_nopay)){
			$this->returnJson(2,'对不起，您已有拼团订单待支付。',array('order_sn'=>$join_nopay[0]['order_sn']));
		}


		//此处为开团或参团代码
		if($group_id){
			$this->returnJson(6,'参团成功');
		}else{
			$this->returnJson(7,'开团成功');
		}

	}
	
	//支付成功或失败的调用接口
	function grouppayinfo() {
		$this->getMember();
		//$group_id = I('param.group_id',0,'intval');
		$order_sn = I('param.order_sn');
		if(empty($order_sn) ) {
			$this->returnJson(1,'数据错误，请重试');
		}
		$uid = $this->member_info['member_id'];
		$group_join = $this->model->getGroupJoinBySn($order_sn,$uid);
		$groupinfo = $this->model->getGroupGroup(array('id'=>$group_join['group_id']));
		$common = OrdersService::getOrderCommon($group_join['id']);
		$group = $this->model->getGroup($group_join['active_id']);
		
		$common['join_time'] = $group_join['add_time_text'];
		$common['order_sn'] = $group_join['order_sn'];
		$data = $group_join;
		$data['gooddetails'] = $group;
		$data['buyinfo'] = $common;
		$data['group_group'] = $groupinfo;
		unset($groupinfo,$common,$group,$group_join);
		$this->returnJson(0,'success',$data);
	}
	public function getGroupSet(){
		$group_set = 	M('group_set')->find();
		$group_set['group_content'] = htmlspecialchars_decode(stripslashes($group_set['group_content']));
		$group_set['group_img'] = !empty ($group_set['group_img']) ?  C('TMPL_PARSE_STRING.__ATTACH_HOST__').$group_set['group_img'] : '';
		$this->returnJson(0,'success',$group_set);
	}
}