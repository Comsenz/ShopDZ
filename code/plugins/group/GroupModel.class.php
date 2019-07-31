<?php
/**
 */
namespace plugins\group;
use Think\Model;
use Common\Service\OrdersService;
/**
 * 插件需要执行的方法 逻辑定义  
 */

class GroupModel extends Model
{
    /**
     * 析构流函数
     */
	public $db_name = '';
    public function  __construct() {
		$this->db_name = D('Group');
    }
	public $join_refund_status = array(
			'0'=>'待付款',
			'2'=>'已付款',
			'1'=>'已退款',
			'-1'=>'退款失败',
			'-100'=>'已取消',
	);
	public $group_group_status = array(
			'0'=>'进行',
			'1'=>'成功',
			'-1'=>'失败',
	);
	function getData() {
		$m = M('orderGoods');
		return $lists = $m->limit(1)->select();
	}

	function findById($id) {
		$res = array();
		$id = intval($id);
		if($id)
			$res = $this->db_name->where("id=%d",$id)->find();
		if($res){
			$res['starttime_text'] = date('Y/m/d',$res['starttime']);
			$res['group_image_text'] = $res['group_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$res['group_image'] : '';
			$res['endtime_text'] = date('Y/m/d',$res['endtime']);
		}
		return $res;
	}

	function delById($id) {
		$res = array();
		$id = intval($id);
		if($id){
			return $this->db_name->where('id=%d',array($id))->delete();
		}
		return false;
	}

	//前台ajax查询
	function getGroupList($con = array(),$page = 1, $prepage = 20 ) {

		$res = $this->db_name->where($con)->order('add_time desc')->limit(($page-1)*$prepage,$prepage)->select();
		if($res){
			$all_goods_id = array();
			foreach($res as $k => $v){
				$res[$k]['head_welfare_text'] = $this->formatWelfare($v);
				$res[$k]['starttime_text'] = date('Y/m/d',$v['starttime']);
				$res[$k]['endtime_text'] = date('Y/m/d',$v['endtime']);
				$res[$k]['group_image'] = $v['group_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['group_image'] : '';
				$all_goods_id[] = $v['goods_id']; 
			}
			$res['all_goods_id'] = $all_goods_id;
		}
		return $res ? $res : array();
	}
	
	function formatWelfare($group) {
		$str = '';
		switch($group['head_welfare_type']) {
			case 'jf':
				$str = '赠送'.$group['head_num'].'积分';
			break;
			case 'gj':
				$str = '购买立减'.$group['head_num'].'元';
			break;
			case 'zk':
				$str = $group['head_num'].'折购买';
			break;
				$str = '';
			case 'none':
			break;
		}
		return $str;
	}

	function getGroupListCount($con = array() ) {
		$res = $this->db_name->where($con)->count();
		return $res ? $res : 0;
	}

	function getAdminGroupGroupListCount($con = array()) {
		$table_pre =  C('DB_PREFIX');
		$res = $this->db_name->join(" {$table_pre}group_group on {$table_pre}group.id={$table_pre}group_group.active_id")->where($con)->count();
		return $res ? $res : 0;
	}

	function getAdminGroupGroupList($con = array(),$page) {
		$table_pre =  C('DB_PREFIX');
		$res = $this->db_name->join(" {$table_pre}group_group on {$table_pre}group.id={$table_pre}group_group.active_id")->field("{$table_pre}group.*,{$table_pre}group_group.active_id,{$table_pre}group_group.id as group_id,{$table_pre}group_group.num,{$table_pre}group_group.status,{$table_pre}group_group.buyer_phone,{$table_pre}group_group.buyer_name,{$table_pre}group_group.add_time as group_add_time")->where($con)->order("group_add_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if($res){
			foreach($res as $k => $v){
				$res[$k]['starttime_text'] = date('Y/m/d H:i:s',$v['group_add_time']);
				$res[$k]['endtime_text'] = date('Y/m/d H:i:s',$v['group_add_time']+$v['group_hour']*3600);
				$res[$k]['status_text'] =$this->group_group_status[$v['status']];
				$res[$k]['group_image'] = $v['group_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['group_image'] : '';
			}
		}
		return $res ? $res : array();
	}

	function getAdminGroupJoinListCount($con = array()) {
		$table_pre =  C('DB_PREFIX');
		$model = D('GroupJoin');
		$res = $model->join("left join {$table_pre}group on {$table_pre}group.id={$table_pre}group_join.active_id")->count();
		return $res ? $res : 0;
	}

	function getAdminGroupJoinList($con = array(),$page) {
		$table_pre =  C('DB_PREFIX');
		$model = D('GroupJoin');
		$res = $model->join("left join {$table_pre}group on {$table_pre}group.id={$table_pre}group_join.active_id")->field("{$table_pre}group.*,{$table_pre}group_join.* ")->where($con)->order("{$table_pre}group_join.add_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if($res){
			foreach($res as $k => $v){
				$res[$k]['starttime_text'] = date('Y/m/d',$v['starttime']);
				$res[$k]['refund_status_text'] = $v['invisible'] && empty($v['refund_status']) ? $this->join_refund_status['-100'] :$this->join_refund_status[$v['refund_status']];
				$res[$k]['endtime_text'] = date('Y/m/d',$v['endtime']);
				$res[$k]['group_image'] = $v['group_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['group_image'] : '';
			}
		}
		return $res ? $res : array();
	}
	//后台台
	function getAdminGroupList($con = array(),$page) {

		$res = $this->db_name->where($con)->order('add_time desc')->limit($page->firstRow.','.$page->listRows)->select();
		if($res){
			foreach($res as $k => $v){
				$res[$k]['starttime_text'] = date('Y/m/d',$v['starttime']);
				$res[$k]['endtime_text'] = date('Y/m/d',$v['endtime']);
				$res[$k]['group_image'] = $v['group_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['group_image'] : '';
			}
		}
		return $res ? $res : array();
	}

	function getGroup($id) {
		$id = intval($id);
		$res = array();
		if($id){
			$res = $this->db_name->where('id=%d',array($id))->find();
		}
		if(!empty($res)) {
			$res['head_welfare_text'] = $this->formatWelfare($res);
			$res['starttime_text'] = date('Y/m/d',$res['starttime']);
			$res['endtime_text'] = date('Y/m/d',$res['endtime']);
			$res['group_image'] = $res['group_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$res['group_image'] : '';
		}
		return $res ? $res : array();
	}

	function getGroupGroup($where) {
		if($where){
			$res = D('GroupGroup')->field('id,active_id,buyer_id,buyer_name,status,num,version,add_time')->where($where)->find();
			if($res) {
				$res['status_text'] = $this->group_group_status[$res['status']];
				$res['group_end_time'] = $res['add_time'] + $res['group_hour']*3600;
			}
		}
		return $res ? $res : array();
	}
	function getGroupJionPaySuccess($group_id){
		$res = D('group_join')->where(array("refund_status"=>"2","group_id"=>$group_id))->count();
		return $res;

	}
	function getGroupJoin($where = array(),$page = 1,$prepage = 100,$order = ' add_time asc ') {
		$start =( $page-1 ) * $prepage;
		$res = D('GroupJoin')->field('id,group_id,active_id,order_sn,pay_sn,buyer_id,add_time,invisible,buyer_id,buyer_name,buyer_email,buyer_phone, goods_amount,order_amount,order_from,invisible,shipping_fee,trade_no, payment_code, refund_order_sn,refund_status')->where($where)->order($order)->limit($start.','.$prepage)->select();
		foreach( $res as $k =>$v ) {
			$res[$k]['add_time_text'] = date('Y-m-d H:i:s',$v['add_time']);
		}
		return $res ? $res : array();
	}

	function getGroupGroupCount($where= array()) {
		$res = D('GroupGroup')->where($where)->count();
		return $res ? $res : 0;
	}

	function getGroupJoinCount($where = array()) {
		$res = D('GroupJoin')->where($where)->count();
		return $res ? $res : 0;
	}

	function getGroupJoinByReturnSn($return_sn) {
		if(empty($return_sn)) return array();
		$where['refund_order_sn'] = $return_sn;
		$where['invisible'] = 0;
		$res = D('GroupJoin')->field('id,group_id,active_id,order_sn,pay_sn,buyer_id,add_time,invisible,goods_amount,order_amount,payment_code,trade_no,shipping_fee')->where($where)->find();
		if($res){
			/* 格式化添加时间 */
			$res['add_time_text'] = date('Y-m-d H:i:s',$res['add_time']);
			/* 支付方式 */
			$res['payment_code_text'] = \Common\Service\OrdersService::$pay_state[$res['payment_code']];
		}
		return array('refund_info'=>$res,'order_info'=>'');
	}

	function getGroupJoinBySn($sn,$uid=0) {
		if(empty($sn)) return array();
		$where['order_sn'] = $sn;
		$uid && $where['buyer_id'] = $uid;
		$res = D('GroupJoin')->field('id,group_id,active_id,order_sn,pay_sn,buyer_id,add_time,invisible,goods_amount,order_amount,payment_code,trade_no,shipping_fee,payment_time,refund_status,buyer_name')->where($where)->find();
		if($res){
			$res['coupon_amount'] = $res['goods_amount'] - $res['order_amount'];
			/* 格式化添加时间 */
			$res['add_time_text'] = date('Y-m-d H:i:s',$res['add_time']);
			$res['payment_time_text'] = $res['payment_time'] ?date('Y-m-d H:i:s',$res['payment_time']) : '';
			/* 支付方式 */
			$res['payment_code_text'] = \Common\Service\OrdersService::$pay_state[$res['payment_code']];
		}
		return $res ? $res : array();
	}

	//支付单用
	function getGroupJoinBySnByPay($sn) {
		if(empty($sn)) return array();
		$groupinfo = $res = $grouwhere = array();
		$where['order_sn'] = $sn;
		$where['invisible'] =0;
		$res = D('GroupJoin')->field('id,group_id,active_id,order_sn,pay_sn,buyer_id,add_time,invisible,goods_amount,order_amount,payment_code,trade_no,shipping_fee,payment_time,buyer_name,buyer_phone')->where($where)->find();
		if($res) {
			$grouwhere['id'] = $res['group_id'];
			$grouwhere['status'] = 0;
			$groupinfo = $this->getGroupGroup($grouwhere);
		}
		unset($where,$grouwhere);
		return $res && $groupinfo ? $res : array();
	}

	function getGroupByIds($ids){
		$res = $data = array();
		if(empty($ids)) return $res;
		$getids = implode(',', $ids);
		$where['id'] =  array ('in',$getids);
		$res = $this->db_name->where($where)->select();
		foreach($res as $k => $v){
			$res[$k]['goods_id'] =  $v['goods_id'];
			$res[$k]['starttime_text'] = date('Y/m/d',$v['starttime']);
			$res[$k]['endtime_text'] = date('Y/m/d',$v['endtime']);
			$res[$k]['group_image'] = $v['group_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['group_image'] : '';
			$data[$v['id']] = $res[$k];
		}
		unset($res);
		return $data ? $data : array();
	}

	function getGroupGroups($where) {
		$res = $data = array();
		//if(empty($ids)) return $res;
		//	$getids = implode(',', $ids);
		//$where['id'] =  array ('in',$getids);
		$res = D('GroupGroup')->field('id,active_id,buyer_name,status,num,version,add_time')->where($where)->select();
		foreach($res as $k => $v) {
			$data[$v['id']] = $v;
		}
		unset($res);
		return $data ? $data : array();
	}

	function updateGroupJoin(array $where,$data) {
		if(empty($data) || empty($where) ) return false;
		return $res = D('GroupJoin')->where($where)->save($data);
	}

	function updateGroupgroup(array $where,$data) {
		if(empty($data) || empty($where) ) return false;
		return $res = D('GroupGroup')->where($where)->save($data);
	}

	function getgroupByJoin($con,$order = ' group_id desc ') {
		$table_pre =  C('DB_PREFIX');
		$model = D('GroupJoin');
		$res = $model->join("left join {$table_pre}group_group on {$table_pre}group_group.id={$table_pre}group_join.group_id")->field("{$table_pre}group_group.status,{$table_pre}group_group.num,{$table_pre}group_join.order_sn,{$table_pre}group_join.trade_no, {$table_pre}group_join.invisible,{$table_pre}group_join.group_id,{$table_pre}group_join.id,{$table_pre}group_join.active_id,{$table_pre}group_join.add_time,{$table_pre}group_join.payment_code,{$table_pre}group_join.order_amount,refund_status")->where($con)->order($order)->select();
		return $res ? $res : array();
	}

	function refund_pay_callback( $returnInfo,$issuccess ) {
		$refund_sn = $returnInfo['batch_no'];
		$data = array(
				'refund_back_time'=>TIMESTAMP,
				'refund_status'=>$issuccess ? 1 : -1,
		);
		return $res = $this->updateGroupJoin(array('refund_order_sn'=>$refund_sn,'refund_status'=>array('NEQ',1)),$data);
	}

	function opGroupJion(array $orderinfo) {
		$flag = 0;
		/*
		$orderinfo = array(
            'order_sn'=>$data['order_sn'],
            'payment_code'=>'alipay',
            'payment_time'=>TIMESTAMP,
            'order_state'=>20,
            'trade_no'=>$data['trade_no']
        );
		*/
		unset($orderinfo['order_state']);
		$order_sn = $orderinfo['order_sn'];
		$info = $this->getGroupJoinBySn($order_sn);

		if(empty($info)) return $flag;
		$orderinfo['refund_status'] = 2;//订单支付成功
		$result = D('GroupJoin')->where('id=%d',array($info['id']))->save($orderinfo);

		if(!$result) {
			return $flag;
		}else{
			$flag = 1;
		}
		$group = $this->getGroup($info['active_id']);
		$head_welfare_type = $group['head_welfare_type'];
		$welfare_head_num = $group['head_num'];
		$where['id'] = $info['group_id'];
		$groupinfo = $this->getGroupGroup($where);
		$create_group_uid = $groupinfo['buyer_id'];
		$create_group_name = $groupinfo['buyer_name'];
		$joinWhere['invisible'] = 0;
		$joinWhere['refund_status'] = 2;
		$joinWhere['group_id'] = $info['group_id'];
		$groupCount = $this->getGroupJoinCount($joinWhere);
		$group_person_num = $group['group_person_num'];
		$flag = 1;
		if($groupCount >= $group_person_num ){
			$join_where['invisible'] = 0;
			$join_where['group_id'] = $info['group_id'];
			$joins = $this->getGroupJoin($join_where);
			$model = new Model();
			$model->master(true);
			$model->startTrans();
			$group_group = array(
					'status' =>1,
			);
			$groupResult = $model->table(C('DB_PREFIX').'group_group')->master(true)->where('id=%d and status=0',array($info['group_id']))->save($group_group);
			$member_result = $points_result = true;
			if($head_welfare_type == 'jf' && !empty($welfare_head_num)){//开团成功后赠送积分
				$insertarr['pl_memberid'] = $create_group_uid;
				$insertarr['pl_membername'] = $create_group_name;
				$insertarr['pl_desc'] = '拼团福利';
				$insertarr['pl_stage'] = 'plugin';
				$insertarr['pl_points'] = $welfare_head_num;
				$insertarr['pl_addtime'] = TIMESTAMP;
				$points_result = $model->table(C('DB_PREFIX').'points_log')->master(true)->add($insertarr);
	            //更新用户积分
				$upmember_array = array();
				$upmember_array['member_points'] = array('exp','member_points+'.$insertarr['pl_points']);
				$member_result = $model->table(C('DB_PREFIX').'member')->master(true)->where(array('member_id'=>$insertarr['pl_memberid']))->save($upmember_array);
			}
			if($groupResult && $member_result && $points_result){
				foreach($joins as $k => $join){
					$order[] = array(
							'order_id'=>$join['id'],
							'order_sn'=>$join['order_sn'],
							'pay_sn'=>$join['order_sn'],
							'buyer_id'=>$join['buyer_id'],
							'buyer_name'=>$join['buyer_name'],
							'buyer_email'=>$join['buyer_email'],
							'buyer_phone'=>$join['buyer_phone'],
							'add_time'=>TIMESTAMP,
							'order_state'=>20,
							'goods_amount'=>$join['goods_amount'],//商品总价格
							'order_amount'=>$join['order_amount'],//订单总价格
							'shipping_fee'=>$join['shipping_fee'],//订单运费
					);
				}
				$ordersResult = $model->table(C('DB_PREFIX').'orders')->master(true)->addAll($order);
				if($ordersResult) {
					$flag = 1;
					$model->commit();
				}else{
					$flag = 0;
					$model->rollback();
				}
			}else{
				$flag = 0;
				$model->rollback();
			}
		}
		return $flag ;
	}
	
	function updateOrderCommonByOrderId($order,$address) {
		$common = array(
			'order_id'=>$order['order_id'],
			'reciver_name'=>$address['true_name'],
			'reciver_info'=>serialize($address),
			'reciver_province_id'=>$address['area_id'],
			'daddress_id'=>$address['address_id'],
			'reciver_city_id'=>$address['city_id'],
		);
		$result =   M('orderCommon')->save($common);
		$flag = $result !== false ? 0 : 1;
		return array('code'=>$flag,'order_id'=>!$flag ? $order['order_id'] :0,'order_sn'=>$order['order_sn']);
	}
	/*
		开团
	*/
	function group($memberinfo,$goodsinfo,$address,$iscreategrouop = false,$groupinfo,$conf = array()) {
		$model = new Model();
		$model->master(true);
		$model->startTrans();
		$random = \Common\Helper\LoginHelper::random(2,$numeric=1);
		$order_sn = date('YmdHis').$random.$conf['order_type_sn'];
		$total_price = 0;
		$baskets_id = $_fee = array();
		$goods_id = $goodsinfo['goods_id'];
		$goods_storage = $goodsinfo['goods_storage'];
		$goods_num=1;
		if($goods_num > $goods_storage) {
			return array('code'=>3,'order_id'=>0);
		}
		$memberinfo['member_mobile'] = $memberinfo['member_mobile'] ? $memberinfo['member_mobile'] : 0;
		$id = $model->table(C('DB_PREFIX').'generate')->master(true)->add(array('id'=>null));
		$total_price += (($goodsinfo['goods_price']*100)*$goods_num)/100;

		if( $iscreategrouop ){//如果是开团
			$group = array(
				'id'=>$id,
				'active_id' =>$goodsinfo['active_id'],
				'buyer_id' =>$memberinfo['member_id'],
				'buyer_name' =>$memberinfo['member_username'],
				'buyer_email' =>$memberinfo['member_email'],
				'buyer_phone' =>$memberinfo['member_mobile'],
				'num' =>$goods_num,
				'status' =>0,
				'add_time' =>TIMESTAMP,
			);
			$groupResult = $model->table(C('DB_PREFIX').'group_group')->master(true)->add($group);
			$groupid = $id;
		}else{
			$group = array(
				'num' =>$groupinfo['num'] + 1,
				'version' =>$groupinfo['version'] + 1,//
			);
			
			$groupResult = $model->table(C('DB_PREFIX').'group_group')->master(true)->where('id=%d and version=%d and num<%d',array($groupinfo['id'],$groupinfo['version'],$goodsinfo['group_person_num']))->save($group);
			$groupid = $groupinfo['id'];
		}
		if(!$groupResult){
			$model->rollback();
			$flag = 1;
			return array('code'=>$flag,'order_id'=>0,'order_sn'=>0);
		}
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
				'goods_common_id'=>$goodsinfo['goods_common_id'],
		);
		$goodsinfo['goods_num'] = $goods_num;
		$goodResult = $model->table(C('DB_PREFIX').'goods')->master(true)->where('goods_id=%d and goods_storage>=%d',array($goods_id,$goods_num))->setDec('goods_storage',$goods_num);
		D('Goods')->delSkuCache($goods_id);
		if(!$goodResult) {//减库存失败
			$model->rollback();
			return array('code'=>3,'order_id'=>0);//库存不足
		}
				//
		if($iscreategrouop){
			$welfare_price = $this->welfare_price($groupinfo['welfare'],$total_price);
		}else{
			$welfare_price = $total_price;
		}
		if(!empty($goodsinfo['is_shipping'])){
			$_fee[] = OrdersService::getOneLogistics($goodsinfo);
			$shipping_fee = OrdersService::getExpense($welfare_price,$_fee);
			$shipping_fee_price = $shipping_fee['shipping_fee'];
		}else{
			$shipping_fee_price = 0;
		}

		$groupJoin = array(
			'id'=>$id,
			'group_id'=>$groupid,
			'active_id' =>$goodsinfo['active_id'],
			'order_sn' =>$order_sn,
			'pay_sn' =>$order_sn,
			'buyer_id' =>$memberinfo['member_id'],
			'buyer_name' =>$memberinfo['member_username'],
			'buyer_email' =>$memberinfo['member_email'],
			'invisible' =>0,
			'buyer_phone' =>$memberinfo['member_mobile'],
			'add_time' =>TIMESTAMP,
			'goods_amount'=>$total_price,//商品总价格
			'order_amount'=>0.1,//订单总价格
			//'order_amount'=>$welfare_price + $shipping_fee_price,//订单总价格
			'shipping_fee'=>$shipping_fee_price,//
		);
		if($groupJoin['order_amount'] <=0 ) {
			return array('code'=>5,'order_id'=>0);//库存不足
		}
	
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
			'order_amount'=>0.1,//订单总价格
		//	'order_amount'=>$welfare_price + $shipping_fee_price,//订单总价格
			'shipping_fee'=>$shipping_fee_price,//
		);
		$common = array(
			'order_id'=>$id,
			'voucher_price'=>$voucher_price,
			'voucher_code'=>$voucher_code,
			'daddress_id'=>$address['address_id'],
			'reciver_name'=>$address['true_name'],
			'reciver_info'=>serialize($address),
			'reciver_province_id'=>$address['area_id'],
			'reciver_city_id'=>$address['city_id'],
			'promotion_total'=>0,//订单总优惠金额
		);

		$group_join = $model->table(C('DB_PREFIX').'group_join')->master(true)->add($groupJoin);
		if( false !== $group_join){
			$orderGoodsResult = $model->table(C('DB_PREFIX').'order_goods')->master(true)->add($orderGoodinfo);
			$orderCommon = $model->table(C('DB_PREFIX').'order_common')->master(true)->add($common);
			if($orderGoodsResult  && $orderCommon) {
				$model->commit();
				$flag = 0;
			}else{
				$flag = 1;
				$model->rollback();
			}
		}else{
			$flag = 1;
			$model->rollback();
		}
		return array('code'=>$flag,'order_id'=>!$flag ? $id :0,'order_sn'=>$order_sn);
	}
	
	function welfare_price($groupinfo,$total_price) {
		$welfare_price = $total_price;
		switch($groupinfo['head_welfare_type']) {
			case 'gj':
				$welfare_price = $total_price -  $groupinfo['head_num'];
			break;
			case 'zk':
				$welfare_price = ($groupinfo['head_num']*($total_price*100))/1000;
			break;
			case 'none':
			case 'jf':
			break;
		}
		return $welfare_price;
	}
}