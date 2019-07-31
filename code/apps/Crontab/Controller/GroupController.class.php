<?php
namespace Crontab\Controller;
use Think\Controller;
use Common\Model\ReturnModel;
use  plugins\group\GroupModel;
use Think\Model;
set_time_limit(0);
class GroupController extends Controller {
	public $model = null; // model对象
    public function __construct(){
        parent::__construct();
        if(!IS_CLI){
            die('permisson dined');
        }
		$this->model = new GroupModel();
        define('NO_PAY_ORDER_TIME_OUT_TIME',3600); //订单超时未支付取消时间  1小时
    }
	
    public function index(){
	    $this->deleteGroupJoinPerson();//拼团未支付过期
       $this->groupRetfundBack();	//拼团失败 退款
       $this->groupBackStorage();	//拼团失败 退库存
       $this->groupBackPay();	//拼团失败 退款
       $this->groupRetfundFail();//拼团失败继续退款
    }
	//拼团失败 先更新团的状态为失败，第二部在异步跑退款
	public function groupRetfundBack() {
		$time = TIMESTAMP;
		$where['starttime'] = array('elt',$time);
		$where['active_end_time'] = array('gt',$time);
		$model =$this->model;
		$returnModel =  new ReturnModel();
		$groupLists = $model->getGroupList($where,$page=1,$prepage = 500);
		$startTrans = new Model();
		$startTrans->master(true);
		$startTrans->startTrans();
		foreach( $groupLists as $k => $groupList ) {
			$active_id = $groupList['id'];
			$group_hour = $groupList['group_hour'];
			$group_person_num = $groupList['group_person_num'];
			$group_price = $groupList['group_price'];
			$limit_time = $time - $group_hour*3600;
			$groupGroupWhere['add_time'] = array('lt',$limit_time);
			$groupGroupWhere['status'] = 0;//
			$groupGroupWhere['num'] =array('neq',$group_person_num);//num 人员不满
			$getGroupGroups = $model->getGroupGroups($groupGroupWhere);
			foreach( $getGroupGroups as $gk => $getGroupGroup ) {
					$groupid = $getGroupGroup['id'];
					$group_group_save = array(
						'id'=>$groupid,
						'status'=>-1,
					);
					$group_group_res = $startTrans->table(C('DB_PREFIX').'group_group')->save($group_group_save);
					if( $group_group_res ) {
						$startTrans->commit();
					}else{
						$startTrans->rollback();
					}
			}
		}
	}
	//status =-1 拼团失败的时候，查出退款失败的用户,退库存
	public function groupBackStorage() {
			$whereJoin['status'] = -1;//拼团活动失败
			$whereJoin['invisible'] = 0;//支付状态
			$whereJoin['refund_status'] = array('in','0,2');
			$joins = $this->model->getgroupByJoin($whereJoin);
			$startTrans = new Model();
			$startTrans->master(true);
			$startTrans->startTrans();
			$group_id = array();
			foreach( $joins as $gk => $join ) {
				$flag = false;
				$groupInfo = $this->model-> getGroup($join['active_id']);//获取商品id	
				$goods_num = 1;
				$save = $where = array();
				if(empty($join['trade_no'])) {
					$save = array(
						'id' => $join['id'],
						'invisible' => -1,//直接取消
					);
					$group_join_res = $startTrans->table(C('DB_PREFIX').'group_join')->save($save);
					$goodResult = $startTrans->table(C('DB_PREFIX').'goods')->master(true)->where('goods_id=%d ',array($groupInfo['goods_id']))->setInc('goods_storage',$goods_num);
					if($group_join_res  && $goodResult) {
						$flag = true;
					}
				}else{
					
					$goodResult = $startTrans->table(C('DB_PREFIX').'goods')->master(true)->where('goods_id=%d ',array($groupInfo['goods_id']))->setInc('goods_storage',$goods_num);
					$save = array(
							'refund_status' => -2,//待退款
						);
					$where['id'] = $join['id'];
					$where['refund_status'] = array('not in','-2,1');//预防group的计划任务字段失败防止重复
					$group_join_res = $startTrans->table(C('DB_PREFIX').'group_join')->where($where)->save($save);
					if($group_join_res  && $goodResult) {
						$flag = true;
					}
				}
				if($flag) {
					$startTrans->commit();
				}else{
					$startTrans->rollback();
				}
			}
	}
	//status =-1 拼团失败的时候，查出退款失败的用户,退钱
	public function groupBackPay() {
			$startTrans = new Model();
			$startTrans->master(true);
			$startTrans->startTrans();
			$whereJoin['refund_status'] = -2;//待退款的状态
			$whereJoin['trade_no'] =array('EXP','is not null');
			$whereJoin['status'] = -1;//拼团活动失败
			$joins = $this->model->getgroupByJoin($whereJoin);
			$time = TIMESTAMP;
			$returnModel =  new ReturnModel();
			foreach( $joins as $gk => $join ) {
					$groupInfo = $this->model-> getGroup($join['active_id']);
					$random = \Common\Helper\LoginHelper::random(2,$numeric=1);
					$doc = substr($join['order_sn'],-2);
					$refund_sn = date('YmdHis').$random.$doc;
					//$refund_sn = \Common\Helper\ToolsHelper::getOrderSn('group');//退款
					$save = array(
						'id' => $join['id'],
						'refund_order_sn' => $refund_sn,
						'refund_time' => $time,
					);
					$group_join_res = $startTrans->table(C('DB_PREFIX').'group_join')->save($save);
					if( $group_join_res ) {
						$startTrans->commit();
						$re_data = array(
							'batch_no'=>$refund_sn,
							'trade_no'=>$join['trade_no'],
							'total_fee'=>$join['order_amount'],
							'mark'=>'拼团失败自动退款',
							'remark'=>'拼团失败自动退款',
							'order_amount'=>$join['order_amount'],
							'returnurl'=>'',
							'payment_code'=>$join['payment_code'],
							'return_type'=>'1',
							'return_id'=>'',
							'buyer_id'=>'',
							'buyer_phone'=>'',
							'order_add_time'=>$join['add_time'],
						);
						$router_res = $returnModel->router( $re_data );
					}else{
						$startTrans->rollback();
					}
			}
	}
	
	
	
    //拼团退款失败 继续退款
    public function groupRetfundFail() {
        $time = TIMESTAMP;
		$model = $this->model;
        $returnModel =  new ReturnModel();
        $startTrans = new Model();
        $startTrans->master(true);
        $startTrans->startTrans();
        
       // $groupWhere['invisible'] = 0;
        $groupWhere['refund_status'] = -1;
        $groupWhere['trade_no'] = array('EXP','is not null');
        $joins = $model->getGroupJoin($groupWhere);

        foreach( $joins as $jk => $join ) {
           // $refund_sn = \Common\Helper\ToolsHelper::getOrderSn('group');//退款
                $re_data = array(
                    'batch_no'=>$join['refund_order_sn'],
                    'trade_no'=>$join['trade_no'],
                    'total_fee'=>$join['order_amount'],
                    'mark'=>'拼团失败自动退款',
                    'remark'=>'拼团失败自动退款',
                    'order_amount'=>$join['order_amount'],
                    'returnurl'=>'',
                    'payment_code'=>$join['payment_code'],
                    'return_type'=>'1',
                    'return_id'=>'',
                    'buyer_id'=>'',
                    'buyer_phone'=>'',
                    'order_add_time'=>$join['add_time'],
                );
                $router_res = $returnModel->refundQuery( $re_data );
				if($router_res['result_code'] == 'SUCCESS')  {
					$save = array(
						'id' => $join['id'],
						'refund_status' => 1,
						//'refund_order_sn' => $refund_sn,
						//'refund_time' => $time,
					);
					$group_join_res = $startTrans->table(C('DB_PREFIX').'group_join')->save($save);
					if( $group_join_res ) {
						$startTrans->commit();
					}else{
						$startTrans->rollback();
					}
				}
        }
        
    }
	//删除用户参与拼团但是没有付款的情况
	function deleteGroupJoinPerson() {
			$time = TIMESTAMP;
			$groupGroupWhere['trade_no'] = array('EXP','is null');//未支付
			$groupGroupWhere['invisible'] = 0;
			$groupGroupWhere['add_time'] = array('ELT',$time-5*60);
			$getGroupJoins = $this->model->getGroupJoin($groupGroupWhere);
			$model = new Model();
			$model->master(true);
			$model->startTrans();

			foreach( $getGroupJoins as $gk => $join ) {
				$groupInfo = $this->model-> getGroup($join['active_id']);
				$buyer_id = $join['buyer_id'];
				$group_id = $join['group_id'];
				$groupgroupwhere['id'] = $group_id;
				$groupgroupwhere['buyer_id'] = $buyer_id;
				$groupgroupInfo = $this->model-> getGroupGroup($groupgroupwhere);
				
				$save = array(
					'id'=>$join['id'],
					'invisible'=>-1,
				);
				//setField
				
				$joinres = $model->table(C('DB_PREFIX').'group_join')->master(true)->save($save);
				if($groupgroupInfo){
					$group_save = array(
						'num'=>0,
						'status'=>-1,
					);
					$group_group = $model->table(C('DB_PREFIX').'group_group')->master(true)->lock(true)->where('id=%d',array($join['group_id']))->save($group_save);
				}else{
					$group_group = $model->table(C('DB_PREFIX').'group_group')->master(true)->lock(true)->where('id=%d',array($join['group_id']))->setDec('num',1);
				}
				$goods_num = 1;
				$goodResult = $model->table(C('DB_PREFIX').'goods')->master(true)->where('goods_id=%d ',array($groupInfo['goods_id']))->setInc('goods_storage',$goods_num);
				if($joinres && $group_group && $goodResult) {
					$model->commit();
				}else{
					$model->rollback();
				}
			}
	}

}
