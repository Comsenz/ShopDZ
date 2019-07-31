<?php
namespace Common\Model;
use Think\Model;
/*
*
*/
class SpreadWithdrawCashModel extends Model {
	private $status = array(1=>'审核中',2=>'已拒绝', 3=>'已同意',4=>'审核中');
	private $level = array(
			1=>'一级',
			2=>'二级',
			3=>'三级',
		);
	private $spread_state = array(
			0=>'未结算',
			1=>'已结算',
		);
	/*
	*前台-提现申请
	*/
	public function addSpreadCrash($data) {
		if(empty($data) || empty($data['cash_amount'])) return 1;/* 1:表示失败 */
		$flag = 0;
		$model = new model();
		$model->master(true);
		$model->startTrans();
		$cash_amount = $data['cash_amount'];//要提现的金额
		$uid = $data['member_uid'];
		$account = $model->table(C('DB_PREFIX').'spread_account') -> where("member_uid=$uid and settlement_price >=$cash_amount") -> find();
		if(empty($account)) {
			$flag = 2;
			$model->rollback();
			return $flag;
		}
		//$all_price = $account['all_price'];//累计奖励，不进行操作
		$settlement_price = $account['settlement_price'];//已经结算的，是可以提现的，会进行减操作
		$account_data['settlement_price'] = $settlement_price - $cash_amount;
		$account_data['frozen_price'] = $account['frozen_price'] + $cash_amount;
		$account_data['account_id'] = $account['account_id'] ;
		$account_res = $model->table(C('DB_PREFIX').'spread_account') -> where("member_uid=$uid and settlement_price >=$cash_amount")->save($account_data);
		$cash_res = $model->table(C('DB_PREFIX').'spread_withdraw_cash') -> add($data);
		if(false !=$cash_res && false !=$account_res){ 
			$model->commit();
			$flag = 0;
		}else{
			$flag = 1;
			$model->rollback();
		}
		return $flag ;
	}

	/*
	*前台-提现详情
	*/
	public function getWithdrawdDtail($cash_sn,$uid) {
		if(empty($cash_sn) || empty($uid)) return array();
		$data = $this->where('cash_sn='.$cash_sn.' and member_uid='.$uid)->find();
		$data['bank_no'] = $this->_hide_middle_str($data['bank_no']);
		$data['status_text'] = $this->status[$data['status']];
		$data['add_time_text'] = date('Y-m-d H:i:s',$data['add_time']);
		return $data ? $data : array();
	}
	/*
	*前台-提现申请记录
	*/
	function getMyWithdraw($uid,$type=null,$page = 1,$prepage = 20) {
		$m = M('spread_withdraw_cash'); 
		$where = array();
		$where['member_uid'] = $uid;
		!is_null($status) && $where['status'] = $type;
		$start =( $page-1 ) * $prepage;
		$lists = $m->field('cash_id,cash_sn,add_time,status,remark,enddate,cash_amount,notify_mark')->where($where)->order('add_time desc')->limit($start.','.$prepage)->select();
		foreach($lists as $k => $v) {
			if($lists[$k]['type'] == 2){
				$lists[$k]['bank_no'] = $this->_hide_middle_str($lists[$k]['bank_no']);
			}
			$lists[$k]['status_text'] = $this->status[$v['status']];
			$lists[$k]['add_time_text'] = date('Y-m-d H:i:s',$v['add_time']);
			$lists[$k]['enddate_text'] = date('Y-m-d H:i:s',$v['enddate']);
		}
		return $lists ? $lists : array();
	}
	
	function exportAllWithdraw($where = array(),$limit = 1000) {
		$lists = $this->where($where)->limit($limit)->order('add_time desc')->select();
		foreach($lists as $k => $v) {
			if($lists[$k]['type'] == 2){
				$lists[$k]['bank_no'] = ($lists[$k]['bank_no']);
			}
			$lists[$k]['status_text'] = $this->status[$v['status']];
			$lists[$k]['add_time_text'] = date('Y-m-d H:i:s',$v['add_time']);
			$lists[$k]['enddate_text'] = $v['enddate'] ? date('Y-m-d H:i:s',$v['enddate']) :'';
		}
		return $lists ? $lists : array();
	}
	/*
	*后台提现记录
	*/
	function getAllWithdraw($where = array(),$page) {
		$lists = $this->where($where)->limit($page->firstRow.','.$page->listRows)->order('add_time desc')->select();
		foreach($lists as $k => $v) {
			if($lists[$k]['type'] == 2){
				$lists[$k]['bank_no'] = $this->_hide_middle_str($lists[$k]['bank_no']);
			}
			$lists[$k]['status_text'] = $this->status[$v['status']];
			$lists[$k]['add_time_text'] = date('Y-m-d H:i:s',$v['add_time']);
			$lists[$k]['enddate_text'] = $v['enddate'] ? date('Y-m-d H:i:s',$v['enddate']) :'';
		}
		return $lists ? $lists : array();
	}
	
	function getAllWithdrawCount($where=array()) {
		return $count = $this->where($where)->count();
	}
	/*
	*审核提现申请
	*/
	function optionWithdraw($uid,$data = array()) {
		if(empty($data) || empty($uid)) return array();
		$status = $data['status'];
		$cash_id = $data['cash_id'];
		$cash_amount = $data['cash_amount'];
		unset($data['cash_amount']);
		switch($status) {
			case 4://审核通过，打款失败
				return $this->save($data);
			break;
			case 3://通过
				$flag = false;
				$model = new model();
				$model->master(true);
				$model->startTrans();
				$account = $model->table(C('DB_PREFIX').'spread_account') -> where("member_uid=$uid") -> find();
			//	$account_data['all_price'] = $account['all_price'] + $cash_amount;
				$account_data['frozen_price'] = $account['frozen_price'] - $cash_amount;
				$account_res = $model->table(C('DB_PREFIX').'spread_account') -> where("member_uid=$uid")->save($account_data);
				$cash_res = $model->table(C('DB_PREFIX').'spread_withdraw_cash')->where('cash_id='.$cash_id)->save($data);
				if(false !==$cash_res && false !== $account_res){
					$model->commit();
					$flag = true;
				}else{
					$model->rollback();
					$flag = false;
				}
				return $flag;
			break;
			case 2://拒绝 冻结账户钱打回到结算账户
				$flag = false;
				$model = new model();
				$model->master(true);
				$model->startTrans();
				$account = $model->table(C('DB_PREFIX').'spread_account') -> where("member_uid=$uid and frozen_price >='$cash_amount'") -> find();
				if(empty($account)) {
					$flag = false;
					$model->rollback();
					return $flag;
				}
				$settlement_price = $account['settlement_price'];//结算账户
				$account_data['settlement_price'] = $settlement_price + $cash_amount;
				$account_data['frozen_price'] = $account['frozen_price'] - $cash_amount;
				$account_data['account_id'] = $account['account_id'] ;
				$account_res = $model->table(C('DB_PREFIX').'spread_account') -> where("member_uid=$uid")->save($account_data);
				$cash_res = $model->table(C('DB_PREFIX').'spread_withdraw_cash')->where('cash_id='.$cash_id)->save($data);
				if(false !=$cash_res && false != $account_res){
					$model->commit();
					$flag = true;
				}else{
					$model->rollback();
					$flag = false;
				}
				return $flag;
			break;
		}

	}
	
		
	function getPresalesList($where= array(),$limit=100) {
		$model = D('spread');
		$lists = $model->where($where)->order('order_add_time asc')->limit($limit)->select();
		foreach($lists as $k => $v) {
			$lists[$k]['add_time_text'] =  date('Y-m-d H:i:s',$v['add_time']);
			$lists[$k]['order_add_time_text'] = date('Y-m-d H:i:s',$v['order_add_time']);
			$lists[$k]['spread_level_text'] = $this->level[$v['spread_level']];
			$lists[$k]['spread_state_text'] = $this->spread_state[$v['spread_state']];
		}
		
		return $lists ? $lists : array();
	}
	/*
	*前台-收入明细
	*/
	
	function getMyPresales($uid,$spread_level=null,$page = 1,$prepage = 20) {
		if(empty($uid)) return array();
		$model = D('spread');
		$where = array();
		$where['member_uid'] = $uid;
		$where['spread_state'] = 1;//显示已经结算的
		$where['spread_comminssion_rate'] = array('neq',0);//只展示有分成比例的，0是没有分成比例
		!is_null($spread_level) && $where['spread_level'] = $spread_level;
		$start =( $page-1 ) * $prepage;
		$lists = $model->field('id,order_add_time,reward_amount,refund_amount,spread_level,spread_comminssion_rate')->where($where)->order('add_time desc')->limit($start.','.$prepage)->select();
		foreach($lists as $k => $v) {
			$lists[$k]['order_add_time_text'] = date('Y-m-d H:i:s',$v['order_add_time']);
			//按比例计算退款金额 。
			$refund_amount = ($v['spread_comminssion_rate']*$v['refund_amount']*100)/10000;
			$lists[$k]['refund_amount'] = $refund_amount;
			$lists[$k]['last_amount'] = ($v['reward_amount']*100 - $refund_amount*100)/100;
			unset($lists[$k]['spread_comminssion_rate']);
			//$lists[$k]['enddate_text'] = date('Y-m-d H:i:s',$v['spread_level']);
		}
		return $lists ? $lists : array();
	}
	/*
	*后台分成统计列表
	*/
	function presales($where,$page){
		$model = D('spread');
		$lists = $model->where($where)->limit($page->firstRow.','.$page->listRows)->order('order_add_time desc')->select();
		foreach($lists as $k => $v) {
			$lists[$k]['add_time_text'] =  date('Y-m-d H:i:s',$v['add_time']);
			$lists[$k]['order_add_time_text'] = date('Y-m-d H:i:s',$v['order_add_time']);
			$lists[$k]['spread_level_text'] = $this->level[$v['spread_level']];
			$lists[$k]['spread_state_text'] = $this->spread_state[$v['spread_state']];
		}
		return $lists ? $lists : array();
	}
	
	function getPresalesCount($where) {
		$model = D('spread');
		return $count = $model->where($where)->count();
	}
	
	/*
	*前台-个人账号显示
	*/
	function account($uid) {
		$model = D("SpreadAccount");
		$path = 'qrcode';
		$data = $model->where("member_uid=$uid")->find();
		if(!empty($data)){
			if(empty($data['qrcode']) || !is_file($data['qrcode'])){
				$_path = \Common\Helper\ToolsHelper::create_path($path);
				$filename = $_path.$uid.'.png';
				$url = C('WAP_URL').'?fromid='.$uid;
				\Common\Helper\QRcode::png($url,$filename);
				list($dir,$qrcode) = explode($path,$filename);
				$update = array(
					'account_id'=>$data['account_id'],
					'qrcode'=>$qrcode,
				);
				$data['qrcode'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$path.$qrcode;
				$data['qrcode_url'] = $url;
				$model->save($update);
			}else{
				$data['qrcode'] =  C('TMPL_PARSE_STRING.__ATTACH_HOST__').$path.'./'.$data['qrcode'];
				$data['qrcode_url'] = $url;
			}
		}
		return $data ? $data : array();
	}
	
	
	
	function withdrawById($id,$status = 0) {
		if(empty($id)) return array();
		$where['cash_id'] = $id;
		$status && $where['status'] = $status;
		$data = $this->where($where)->find();
		if($data) {
			$data['status_text'] = $this->status[$data['status']];
		}
		return $data ? $data : array();
	}
	public function _hide_middle_str($str){
		$len = strlen($str);
		$newstr = '';
		if($len > 0){
			$newstr = substr($str,0,4);
			$newstr .= str_repeat('*',4);
			$newstr .= substr($str,$len-4,$len);
		}
		return $newstr;
	}
	
}
?>