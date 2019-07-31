<?php
/**
 * this file is not freeware
 * User: juan
 * DATE: 2016/4/15
 */
namespace Admin\Model;
use Think\Model;
class OrdersModel extends Model{
	
	protected $cache_pre = "OrdersModel_";
    protected $_validate = array();
	public $order_state = array(0=>'已取消',10=>'未付款',20=>'待发货',30=>'已发货',40=>'已收货');
	public $payment_code = array('wx'=>'微信','alipay'=>'支付宝');
	function getRefundByRefundSn($refund_sn,$field="*") {
		return $this->field($field)->where('refund_sn=%d',array($refund_sn))->find();
	}
    /**
     * 获取地址列表
     *
     * @return mixed
     */
    public function getList($condition = array(), $page) {
		$table_pre =  C('DB_PREFIX');
		$lists =  $this->join(" {$table_pre}order_common on {$table_pre}orders.order_id={$table_pre}order_common.order_id ")->field("{$table_pre}orders.*,{$table_pre}order_common.reciver_name")->where($condition)->order("{$table_pre}orders.order_id desc")->limit($page->firstRow.','.$page->listRows)->select();
		foreach($lists as $k => $v) {
			$lists[$k]['order_state_text'] = $this->order_state[$v['order_state']];
			$lists[$k]['payment_code'] = $this->payment_code[$v['payment_code']];
			$lists[$k]['payment_time_text'] =$v['payment_time'] ? date('Y-m-d H:i:s',$v['payment_time']) : '';
			$lists[$k]['add_time_text'] = date('Y-m-d H:i:s',$v['add_time']);
		}
		return $lists;
    }
    public function getListCount($condition = array()) {
		$table_pre =  C('DB_PREFIX');
		return $count =  $this->join(" {$table_pre}order_common on {$table_pre}orders.order_id={$table_pre}order_common.order_id ")->field("{$table_pre}orders.*")->where($condition)->count();
	}
	public function getOrderDetial($order_id,$field='*'){
		if(empty($order_id)) return;
		$where['order_id'] = $order_id;
		 $lists = $this->field($field)->where($where)->find();
		 if(!empty($lists)) {
			$lists['order_state_text'] = $this->order_state[$lists['order_state']];
			$lists['payment_code'] = $this->payment_code[$lists['payment_code']];
			$lists['add_time'] =$lists['add_time'] ? date('Y-m-d H:i:s',$lists['add_time']) : '';
			$lists['payment_time'] =$lists['payment_time'] ? date('Y-m-d H:i:s',$lists['payment_time']) :'';
			$lists['rpacket_detail'] = M('redpacket')->field('rpacket_title,rpacket_t_id,rpacket_price')->where(array('rpacket_order_id'=>$order_id))->find();
			}
		return $lists;
	}
	public function getOrdersGoodsList($order_id,$field="*") {
		$lists = array();
		if(!empty($order_id)) {
			$m = M('orderGoods');
			$lists = $m->field($field)->where("order_id=$order_id")->select();
			foreach($lists as $k => $v) {
					$lists[$k]['goods_image'] = $v['goods_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['goods_image'] :'';
			}
		}
		return $lists;
	}
	function getOrderCommon($order_id,$field="*") {
			$m = M('orderCommon'); 
			$data =  $m->field($field)->find($order_id);
			if(!empty($data) && $data['reciver_info']) {
				$data['reciver_info'] = unserialize($data['reciver_info']);
				$data['shipping_time_text'] =$data['shipping_time'] ? date('Y-m-d H:i:s',$data['shipping_time']) : '';
			}
		return $data;
	}
	
	function setstorage(array $data) {
		if(empty($data)) return false;
		$model = new Model();
		$model->master(true);
		$model->startTrans();
		$flag = 0;
		$update = array('shipping_code'=>$data['shipping_code'],'order_id'=>$data['order_id'],'delay_time'=>TIMESTAMP,'order_state'=>$data['order_state']);
		$result = $model->table(C('DB_PREFIX').'orders')->master(true)->where('order_id=%d',array($data['order_id']))->save($update);
		unset($data['shipping_code']);
		if( 0 !== $result || false !== $result ) {
			unset($data['order_state']);
			$order_common =  $model->table(C('DB_PREFIX').'order_common')->master(true)->where('order_id=%d',array($data['order_id']))->save($data);
			if(0 !== $order_common || false !== $order_common) {
				$flag = 1;
				$model->commit();
			}else{
				$model->rollback();
			}
		}else{
			$model->rollback();
		}
		return $flag;
	}
	
	function delOrder($order_id,$uid = 0) {
		$model = new Model();
		$model->master(true);
		$model->startTrans();
		$flag = 0;
		$where = array();
		$where['order_id'] = $order_id;
		$uid && $where['buyer_id'] = $uid;
		$ordersResult = $model->table(C('DB_PREFIX').'orders')->master(true)->where($where)->delete();
		if($ordersResult){
			$orderGoodsResult = $model->table(C('DB_PREFIX').'order_goods')->master(true)->where("order_id='%s'",array($order_id))->delete();
			$orderCommonResult = $model->table(C('DB_PREFIX').'order_common')->master(true)->where("order_id='%s'",array($order_id))->delete();
			if($orderGoodsResult && $orderCommonResult) {
				$model->commit();
				$flag = 1;
			}else{
				$flag = 0;
				$model->rollback();
			}
		}else{
			$flag = 0;
			$model->rollback();
		}
		return $flag;
	}
	
	function opOrder($order_id,$order_state) {
		$m = M('orders'); 
		return $m->where("order_id=%d ",array($order_id))->setField('order_state',$order_state);
	}
}