<?php  
/**
 * this file is not freeware
 * User: chenkeke
 * DATE: 2016/5/23
 */
namespace Admin\Model;
use Think\Model;
class RefundModel extends Model{

	

	/**
	 * 拼装数据库表名
	 * @return array
	 */
	private function _maketable($table){
		$pre = C('DB_PREFIX');
		foreach($table as $k=>$v){
			$table[$k] = $pre.$v;
		}
		return implode(',',$table);
	}

	/**
	 * 获取数据库数据
	 * @return object
	 */
	public function _getFind( $table=array('refund r','causes c') , $field=array('r.*','c.causes_name') , $where=array() ){
        $where[] = 'r.causes_id = c.causes_id';
        $id = intval(I('get.id'));
        $where['r.refund_id'] = $id;
		$res = array();
    	$res = M()->table($this->_maketable($table))
    			->where($where)
    			->field(implode(',',$field))
    			->find();
    	$images = explode("\t",$res['refund_images']);
        $res['refund_images'] = array();
        foreach($images as $k => $image) {
            $image && $res['refund_images'][] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').str_replace('.thumb.jpeg','',$image);
        }
        return $res;
    }

    public function _getData( $table=array() , $field=array() , $where=array() , $order=''){
    	$t = $this->_maketable($table);
    	$count = M()->table($t)->where($where)->count();

		$page = new \Common\Helper\PageHelper($count,20);
		$res = array();
        $res = M()->table($t)
        		->field(implode(',',$field))
    			->where($where)
    			->limit($page->firstRow.','.$page->listRows)
    			->order($order)
    			->select();
    	$res && $res['page'] = $page->show();
        return $res;
    }

    public function _getRefundsCount($where=array()){
    	$refund = M('refund');
        $table_pre =  C('DB_PREFIX');
        $count =  $refund->join(" {$table_pre}causes on {$table_pre}causes.causes_id={$table_pre}refund.causes_id ")
        				->join(" {$table_pre}member on {$table_pre}member.member_id={$table_pre}refund.member_uid ")
        				->where($where)
        				->count();
        return $count;
    }
    public function _getRefunds($where=array(),$page=1,$rp=20,$orderby='dateline desc'){
    	$refund = M('refund');
        $table_pre =  C('DB_PREFIX');
        $res = $refund->join(" {$table_pre}causes on {$table_pre}causes.causes_id={$table_pre}refund.causes_id ")
        			->join(" {$table_pre}member on {$table_pre}member.member_id={$table_pre}refund.member_uid ")
        			->field("{$table_pre}refund.*,{$table_pre}causes.causes_name,{$table_pre}member.member_mobile")
        			->where($where)
        			->order("{$table_pre}refund.{$orderby}")
        			->limit(($page-1).','.$rp)
        			->select();
        return $res;
    }

    public function _getReturnsCount($where=array()){
        $return = M('returngoods');
        $table_pre =  C('DB_PREFIX');
        $count =  $return->join(" {$table_pre}order_goods on {$table_pre}order_goods.rec_id={$table_pre}returngoods.rec_id ")
        				->join(" {$table_pre}member on {$table_pre}member.member_id={$table_pre}returngoods.member_uid ")
        				->where($where)
        				->count();
        return $count;
    }

    public function _getReturns($where=array()){
        $return = M('returngoods');
        $table_pre =  C('DB_PREFIX');
        $count = $this->_getReturnsCount($where);
        $page = new \Common\Helper\PageHelper($count,20);
        $res = array();
        $res = $return->join(" {$table_pre}order_goods on {$table_pre}order_goods.rec_id={$table_pre}returngoods.rec_id ")
        			->join(" {$table_pre}member on {$table_pre}member.member_id={$table_pre}returngoods.member_uid ")
        			->field("{$table_pre}returngoods.*,
        					{$table_pre}order_goods.rec_id,
        					{$table_pre}order_goods.goods_id,
        					{$table_pre}order_goods.goods_name,
        					{$table_pre}order_goods.goods_price,
        					{$table_pre}order_goods.goods_image,
        					{$table_pre}order_goods.goods_spec,
        					{$table_pre}order_goods.goods_returnnum,
        					{$table_pre}order_goods.goods_common_id,
        					{$table_pre}member.member_mobile")
        			->where($where)
        			->order("{$table_pre}returngoods.dateline desc")
        			->limit($page->firstRow.','.$page->listRows)
        			->select();
        foreach($res as $k =>$v) {
            $causes_id = $v['causes_id'];
            $result = M('causes')->field('causes_name')->where("causes_id=$causes_id")->find();
            $res[$k]['causes_name'] = $result['causes_name'];
        }
        $res && $res['page'] = $page->show();
        return $res;

    }
	
	function getReturnGoodsByRgId($rg_id,$field="*") {
		$m = M('returngoods');
		return $m->field($field)->where('rg_id=%d',array($rg_id))->find();
	}
  
   //退货操作
    function optionReturnGood($data) {

		$model = new Model();
		$model->master(true);
		$model->startTrans();
		$flag = 0;
		$data['dateline'] = TIMESTAMP;
		$causesResult = $model->table(C('DB_PREFIX').'returngoods')->master(true)->where('rg_id=%d',array($data['rg_id']))->save($data);
		if(false !== $causesResult) {
			$status = $data['status'];
			if(in_array($status ,array(4))) {//决绝退货
				$order = array('lock_state'=>0,'return_state'=>0);
				$ordersResult = $model->table(C('DB_PREFIX').'orders')->master(true)->where("order_id=%d",$data['order_id'])->save($order);
				$order_good = array('goods_returnnum'=>0);
				$order_goodsResult = $model->table(C('DB_PREFIX').'order_goods')->master(true)->where("rec_id=%d",$data['rec_id'])->save($order_good);
			}else {
				/*
					$goods =  M('order_goods')->master(true)->where("rec_id=%d",$data['rec_id'])->find();
				if(!empty($goods)){
						$order_goodsResult =1;
						$goods_update_state = false;
						$goods_id   = intval($goods['goods_id']);
						$goods_num   = intval($goods['goods_num']);
						$goods_update_state  = M('goods')->master(true)->where(array('goods_id'=>$goods_id))->setInc('goods_storage',$goods_num);
						if($goods_update_state ===false){
							$model->rollback();
							$order_goodsResult = 0;
						}
				}
				*/
				$order_goodsResult = 1;
			}
			if(false !== $ordersResult  && false !==  $order_goodsResult) {
				F($data['order_id'],null);
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
   
   //退款操作
    function optionRefund($data) {

   		$model = new Model();
		$model->master(true);
		$model->startTrans();
		$flag = 0;
	
		$causesResult = $model->table(C('DB_PREFIX').'refund')->master(true)->where('refund_id=%d',array($data['refund_id']))->save($data);
		if(false !== $causesResult ) {
			$status = $data['status'];
			if(in_array($status ,array(4))) {//决绝退款
				$order = array('refund_state'=>$data['status'],'refund_amount'=>0,'lock_state'=>0);//
				$ordersResult = $model->table(C('DB_PREFIX').'orders')->master(true)->where("order_id=%d",$data['order_id'])->save($order);
			}else{
				$order = array('refund_state'=>$data['status']);//
				$ordersResult = $model->table(C('DB_PREFIX').'orders')->master(true)->where("order_id=%d",$data['order_id'])->save($order);
				$order_goods =  M('order_goods')->master(true)->where(array('order_id'=>$data['order_id']))->select();
				if(!empty($order_goods)){
					$ordersResult =true;
					foreach($order_goods as $goods){
						$goods_condition   = array();
						$goods_update_state = false;
						$goods_id   = intval($goods['goods_id']);
						$goods_num   = intval($goods['goods_num']);
						$goods_update_state  = M('goods')->master(true)->where(array('goods_id'=>$goods_id))->setInc('goods_storage',$goods_num);
						if($goods_update_state ===false){
							//$model->rollback();
							$ordersResult = false;
							break;
						}
						D('Goods')->master(true)->delSkuCache($goods_id);
					}
				}
				$ordersResult =true;
			}
			if(false !== $ordersResult) {
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
   
    public function _makeData($data){
		
        foreach($data as $k=>$v){
            $data[$k]['status_code'] = $data[$k]['status'];
            switch($data[$k]['status']){
                case '1':
                    $data[$k]['status'] = '待审核';
                    break;
                case '2':
                    $data[$k]['status'] = '审核通过待退款';
                    break;
                case '3':
                    $data[$k]['status'] = '已退款';
                    break;
                case '4':
                    $data[$k]['status'] = '拒绝退款';
                    break;
                case '5':
                    $data[$k]['status'] = '原路退款失败';
                    break;
            }
        }
        return $data;
    }
	function getReturn($rg_id) {
		if(empty($rg_id)) return array();
	   	$table_pre =  C('DB_PREFIX');
		$model = M('returngoods');
	   	$lists =  $model->join("{$table_pre}order_goods on {$table_pre}returngoods.rec_id={$table_pre}order_goods.rec_id ")->field("{$table_pre}order_goods.goods_name,{$table_pre}order_goods.rec_id,{$table_pre}returngoods.*")->where("{$table_pre}returngoods.rg_id=%d",array($rg_id))->order("{$table_pre}returngoods.dateline desc")->find();
	   	$images = explode("\t",$lists['return_images']);
        $lists['return_images'] = array();
        foreach($images as $k => $image) {
            $image && $lists['return_images'][] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').str_replace('.thumb.jpeg','',$image);;
        }
		return $lists;
	}
}
?>