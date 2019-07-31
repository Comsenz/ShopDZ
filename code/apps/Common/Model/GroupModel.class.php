<?php
namespace Common\Model;
use Think\Model;
class GroupModel extends Model {
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
	function findById($id) {
		$res = array();
		$id = intval($id);
		if($id)
			$res = $this->where("id=%d",$id)->find();
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
			return $this->where('id=%d',array($id))->delete();
		}
		return false;
	}
	
	//前台ajax查询
	function getGroupList($con = array(),$page = 1, $prepage = 20 ) {
	
		$res = $this->where($con)->order('add_time desc')->limit(($page-1)*$prepage,$prepage)->select();
		if($res){
			foreach($res as $k => $v){
				$res[$k]['starttime_text'] = date('Y/m/d',$v['starttime']);
				$res[$k]['endtime_text'] = date('Y/m/d',$v['endtime']);
				$res[$k]['group_image'] = $v['group_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['group_image'] : '';
			}
		}
		return $res;
	}
	
	function getGroupListCount($con = array() ) {
		$res = $this->where($con)->count();
		return $res;
	}
	
	function getAdminGroupGroupListCount($con = array()) {
		$table_pre =  C('DB_PREFIX');
		$res = $this->join(" {$table_pre}group_group on {$table_pre}group.id={$table_pre}group_group.active_id")->where($con)->count();
		return $res;
	}
	
	function getAdminGroupGroupList($con = array(),$page) {
		$table_pre =  C('DB_PREFIX');
		$res = $this->join(" {$table_pre}group_group on {$table_pre}group.id={$table_pre}group_group.active_id")->field("{$table_pre}group.*,{$table_pre}group_group.active_id,{$table_pre}group_group.id as group_id,{$table_pre}group_group.num,{$table_pre}group_group.status,{$table_pre}group_group.buyer_phone,{$table_pre}group_group.buyer_name,{$table_pre}group_group.add_time as group_add_time")->where($con)->order("group_add_time desc")->limit($page->firstRow.','.$page->listRows)->select();
		if($res){
			foreach($res as $k => $v){
				$res[$k]['starttime_text'] = date('Y/m/d H:i:s',$v['group_add_time']);
				$res[$k]['endtime_text'] = date('Y/m/d H:i:s',$v['group_add_time']+$v['group_hour']*3600);
				$res[$k]['status_text'] =$this->group_group_status[$v['status']];
				$res[$k]['group_image'] = $v['group_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['group_image'] : '';
			}
		}
		return $res;
	}
	
	function getAdminGroupJoinListCount($con = array()) {
		$table_pre =  C('DB_PREFIX');
		$model = D('GroupJoin');
		$res = $model->join("left join {$table_pre}group on {$table_pre}group.id={$table_pre}group_join.active_id")->count();
		return $res;
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
		return $res;
	}
	//后台台
	function getAdminGroupList($con = array(),$page) {
	
		$res = $this->where($con)->order('add_time desc')->limit($page->firstRow.','.$page->listRows)->select();
		if($res){
			foreach($res as $k => $v){
				$res[$k]['starttime_text'] = date('Y/m/d',$v['starttime']);
				$res[$k]['endtime_text'] = date('Y/m/d',$v['endtime']);
				$res[$k]['group_image'] = $v['group_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['group_image'] : '';
			}
		}
		return $res;
	}
	
	function getGroup($id) {
		$id = intval($id);
		$res = array();
		if($id){
			$res = $this->where('id=%d',array($id))->find();
		}
		if(!empty($res)) {
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
	
	function getGroupJoin($where = array(),$page = 1,$prepage = 100,$order = ' add_time asc ') {
		$start =( $page-1 ) * $prepage;
		$res = D('GroupJoin')->field('id,group_id,active_id,order_sn,pay_sn,buyer_id,add_time,invisible,buyer_id,buyer_name,buyer_email,buyer_phone, goods_amount,order_amount,order_from,invisible,shipping_fee,trade_no, payment_code, refund_order_sn')->where($where)->order($order)->limit($start.','.$prepage)->select();
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
		$res = $this->where($where)->select();
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
		$where['id'] = $info['group_id'];
		//$groupinfo = self::getGroupGroup($where);
		//$groupCount = $groupinfo['num'];
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
			if($groupResult){
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
}
?>