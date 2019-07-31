<?php
namespace Crontab\Controller;
use Think\Controller;
use Think\Model;
use Common\Service\OrdersService;
/*
价格按照整数处理
*/
class HourController extends Controller {
    public function __construct(){
        parent::__construct();
        if(!IS_CLI){
            die('permisson dined');
        }
    }
    public function index(){
        $this->spresales_start();//入库
        $this->spresales_day_30();//30
		$this->spresales_day_60();//30
   //     $this->respresales_day_60();//60
	//	$this->account();//未结算
    }
	
	public function spresales_day_30() {
		$SpreadOrderCron  = D('SpreadOrderCron');
		$day_30 = 86400*30;
		$end_time= strtotime(date('Y-m-d',time())) - $day_30;
		$where['cron_state'] = 0;
		$where['spread_state'] = 0;
		$where['order_add_time'] = array('elt',$end_time);//先去掉
		$list = $SpreadOrderCron->getPresalesList($where ,$limit = 100);//获取执行的 spread表
		$this->spresales_process($list);
	}
	
	public function spresales_day_60() {
		$SpreadOrderCron  = D('SpreadOrderCron');
		$day_60 = 86400*30*2;
		$end_time= strtotime(date('Y-m-d',time())) - $day_60;
		$where['cron_state'] = 1;
		$where['spread_state'] = 0;
		$where['order_add_time'] = array('elt',$end_time);//先去掉
		$list = $SpreadOrderCron->getPresalesList($where ,$limit = 100);//获取执行的
		$this->spresales_process($list);
	}
	
	//计算奖励
	public function spresales_process($list) {
			if(empty($list)) return;
			$SpreadOrderCron  = D('SpreadOrderCron');
		$order =  D('Admin/Orders');
		$Member =  D('Admin/Member');
		foreach($list as $k => $v) {
			$level_all = $member = $data = array();
			$id = $v['id'];
			$member_uid = $v['member_uid'];
			$type = $v['cron_type'];
			$order_id = $v['order_id'];
			$spread_comminssion_rate = $v['spread_comminssion_rate'];//分成比例
			$refund_amount = $v['refund_amount'];//订单退款金额  
			$reward_amount = $v['reward_amount'];//奖励金额,除以100得的结果
			$order_info = $order->getOrderDetial($order_id);
			$order_amount = $order_info['order_amount'];//订单总金额
			$surplus_amount = ($order_amount - $refund_amount);//订单总金额减去退款获取奖励金额
			if($order_info['refund_state']) {
				$refund = OrdersService::getRefundByRefundSn($order_info['refund_sn']);
				if($refund['status'] ==3){
				}if($refund['status'] !=3){
					$where['id'] = $id;
					$update['cron_state'] = 1;
					$SpreadOrderCron->upPresaleState($where,$update);//如果出现退款不成功的情况，放置到下次计划任务
					continue;
				}
			}
			$details = $order->getOrdersGoodsList($order_id);
			foreach($details as $de=>$dv) {
				if(!empty($dv['rg_id'])) {
					//continue;//如果出现退款不成功的情况，放置到下次计划任务
					$returngoods = OrdersService::getReturnGoodsByRgId($dv['rg_id']);
					if($returngoods['status'] ==3){
					}elseif($returngoods['status'] !=3){
						$where['order_id'] = $order_id;
						$update['cron_state'] = 1;
						$SpreadOrderCron->upPresaleState($where,$update);
						break 2;//跳出 2级循环
						continue;//如果出现退款不成功的情况，放置到下次计划任务
					}
				}
			}
				$data = array(
					'id'=>$id,//主键
					'cron_state'=>3,
					'cron_time'=>TIMESTAMP,
					'spread_state'=>1,
				);
				$model = new Model();
				$model->master(true);
				$model->startTrans();
				$res_sp = $model->table(C('DB_PREFIX').'spread')->save($data);
				
				$member_account = $model->table(C('DB_PREFIX').'spread_account')->where('member_uid=%d',array($member_uid))->find();
				$account = array(
					'all_price'=>$member_account['all_price']+(($surplus_amount*100)*$spread_comminssion_rate)/10000 ,
					'no_settlement_price'=>$member_account['no_settlement_price'] - ($order_amount*100*$spread_comminssion_rate)/10000,
					'settlement_price'=>$member_account['settlement_price']+ ((100*$surplus_amount)*$spread_comminssion_rate)/10000,
				);
				$spread_account_sp = $model->table(C('DB_PREFIX').'spread_account')->where('member_uid=%d',array($member_uid))->save($account);
				if(false !== $res_sp && false !== $spread_account_sp) {
					$model->commit();
				}else{
					$model->rollback();
				}
		}
	}
	/**
     * 写日志
     */
    public function writeLog($text) {
        $month = date('Y-m');
        file_put_contents ( APP_ROOT."/data/Logs/admin_pay/".$month.".txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
    }
	public function spresales_start () {
		$SpreadOrderCron  = D('SpreadOrderCron');
		// $day_30 = 86400*30;
		// $end_time= strtotime(date('Y-m-d',time())) - $day_30;
		$where['cron_state'] = 0;
		// $where['order_add_time'] = array('elt',$end_time);
		$list = $SpreadOrderCron->getList($where ,$limit = 100);//获取执行的
		$commission = $this->_getCommission();
		$order =  D('Admin/Orders');
		$Member =  D('Admin/Member');
		foreach($list as $k => $v) {
			$level_all = $member = $data = array();
			$type = $v['cron_type'];
			$order_id = $v['order_id'];
			$order_info = $order-> getOrderDetial($order_id);
			$total_price = $order_info['order_amount'];
			$buyer_id = $order_info['buyer_id'];
			$order_sn = $order_info['order_sn'];
			/* 获取订单类型(00:正常，01:拼团，02:退货，03:退款) */
			$order_type = \Common\Helper\ToolsHelper::getOrderSnType($order_sn);
			if($order_type !='order') {//只有正常订单才能进入，拼团等活动订单不参加
				$SpreadOrderCron->upState($v['id'],3);//垃圾数据
				continue;
			}
			$member = $Member->getMemberByUid($buyer_id);
			$fromid = $member['fromid'];
			if(empty($fromid) ) {
				$SpreadOrderCron->upState($v['id'],3);//垃圾数据
				continue;
			}
			switch($type) {
				case '0'://新订单
					$level_all = $this->_getLevel($fromid);
					$model = new Model();
					$model->master(true);
					$model->startTrans();
					$data = array();
					foreach($level_all as $k =>$memberinfo) {
						if(empty($memberinfo['info'])){
							continue;
						}
						$tmp = array(
							'member_uid'=>$memberinfo['info']['member_id'],
							//'member_mobile'=>$memberinfo['info']['member_mobile'],
							'member_truename'=>$memberinfo['info']['member_username'],
							'add_time'=>TIMESTAMP,
							'order_id'=>$order_id,
							'order_add_time'=>$v['add_time'],
							'order_sn'=>$order_info['order_sn'],
							'spread_state'=>0,
							'spread_state_time'=>TIMESTAMP,
							'cron_time'=>TIMESTAMP,
							'spread_type'=>0,
							'order_amount'=>$order_info['order_amount'],
							'spread_level'=>$memberinfo['level'],
							'spread_comminssion_rate'=>$commission[$memberinfo['level']],
							'reward_amount'=>($commission[$memberinfo['level']]*$order_info['order_amount']*100)/10000,
						);
						$data[] = $tmp;
						$member_account = $model->table(C('DB_PREFIX').'spread_account')->where('member_uid=%d',array($memberinfo['info']['member_id']))->find();
						$account = array(
							'no_settlement_price'=>$member_account['no_settlement_price'] + $tmp['reward_amount'],
						);
						$spread_account_sp = $model->table(C('DB_PREFIX').'spread_account')->where('member_uid=%d',array($memberinfo['info']['member_id']))->save($account);
						if(false ===$spread_account_sp){
							$model->rollback();
							continue;
						}
					}
				
					$res_sp = $model->table(C('DB_PREFIX').'spread')->addAll($data);
						$save['id'] = $v['id'];
						$save['cron_state'] = 1;
						$save['cron_time'] = TIMESTAMP;
					$res_cron = $model->table(C('DB_PREFIX').'spread_order_cron')->save($save);
					if($res_sp && $res_cron) {
						$model->commit();
					}else{
						$model->rollback();
					}
				 break;
				case '1'://退款订单
					$model = new Model();
					$model->master(true);
					$model->startTrans();
					$data['refund_amount'] = $order_info['order_amount'];
					$res_sp = $model->table(C('DB_PREFIX').'spread') ->where('order_id =%d',array($order_info['order_id'])) -> save($data);
						$save['id'] = $v['id'];
						$save['cron_state'] = 1;
						$save['cron_time'] = TIMESTAMP;
					$res_cron = $model->table(C('DB_PREFIX').'spread_order_cron')->save($save);
					if($res_sp && $res_cron) {
						$model->commit();
					}else{
						$model->rollback();
					}
				 break;
				case '2'://退货订单
					$model = new Model();
					$model->master(true);
					$model->startTrans();
					$return_amount = $model->table(C('DB_PREFIX').'returngoods') ->where('order_id =%d and status=%d',array($order_info['order_id'],3)) -> sum('return_amount');
					$data['refund_amount'] = $return_amount;
					$res_sp = $model->table(C('DB_PREFIX').'spread') ->where('order_id =%d',array($order_info['order_id'])) -> save($data);
						$save['id'] = $v['id'];
						$save['cron_state'] = 1;
						$save['cron_time'] = TIMESTAMP;
					$res_cron = $model->table(C('DB_PREFIX').'spread_order_cron')->save($save);
					if($res_sp && $res_cron) {
						$model->commit();
					}else{
						$model->rollback();
					}
				 break;
			}
		}
	}
	
	private function _getLevel($fromid) {
		$Member =  D('Admin/Member');
			$level_1 = $Member->getMemberByUid($fromid);
			$level_all[$fromid] = array('level'=>1,'info'=>$level_1);
	
			if($level_1 && $level_1['fromid']){
				$level_2 = $Member->getMemberByUid($level_1['fromid']);
				$level_all[$level_1['fromid']] = array('level'=>2,'info'=>$level_2);
			}

			if($level_2 && $level_2['fromid']){
				$level_3 = $Member->getMemberByUid($level_2['fromid']);
				$level_all[$level_2['fromid']] = array('level'=>3,'info'=>$level_3);
			}
		return $level_all;
	}
	//获取佣金设置
	private function _getCommission() {
	    $data = D('setting')->field('value')->where("name='spread_reward'")->find();
        $data = unserialize($data['value']);
		
		$array = array(
			1=>$data['reward_1'] ? $data['reward_1'] : 0,
			2=>$data['reward_2'] ? $data['reward_2'] : 0 ,
			3=>$data['reward_3'] ? $data['reward_3'] : 0,
		);
		return $array;
	}
}
