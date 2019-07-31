<?php
namespace Admin\Controller;
use Admin\Model\SettingModel;
use Think\Controller;
use Think\Model;
class SpreadController extends BaseController {
	public function bank() {
		$id = I('param.id');
		if($this->checksubmit()){
			$bank_name = I('post.bank_name');
			$data['name'] = $bank_name;
			if(empty($bank_name)) 
				$this->showmessage('error','银行名称不能为空');

			if(empty($id)){
				$data['add_time'] = TIMESTAMP;
				$res = D('Bank')->add($data);
			}else{
				$data['id'] = $id;
 				$res = D('Bank')->save($data);
			}
			if(false !=$res) 
				$this->showmessage('success','操作成功',U('Spread/banklist'));
			else
				$this->showmessage('error','操作失败请重试');
		}else{
			if(!empty($id)){
				$data = D('Bank')->find($id);
				$this->assign('data', $data);
			}
			$this->display('bank');
		}
	}
	
	public function banklist() {
		$list = D('Bank')->getList();
		$this->assign('list', $list);
		$this->display('banklist');
	}
	
	public function delbank() {
		$id = I('param.id');
		$res = D('Bank')->where('id=%d',array($id))->delete();
		if($res) 
			$this->showmessage('success','操作成功');
		else
			$this->showmessage('error','操作失败请重试');
	}
	/*
	*推广设置
	*/
	public function spread() {
		$model = new SettingModel();
		if($this->checksubmit()){
			$maxprice =  I('post.maxprice');
			$minprice =  I('post.minprice');
			$content =  I('post.content');
			if(empty($maxprice) || empty($minprice) ||  !is_numeric($maxprice) ||  !is_numeric($minprice) )
				$this->showmessage('error','提现额度只能是数值');
				$update_array['spread'] = serialize(array(
					'maxprice'=>$maxprice,
					'minprice'=>$minprice,
					'content'=>$content,
				));
			$model->Settings($update_array);
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($update_array,true),'action'=>'推广设置-提现设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			$this->showmessage('success','操作成功');
		}else{
			$spread = $model->getSetting('spread');
			$spread = unserialize($spread);
			$this->assign('data', $spread);
		}
		$this->display('spread');
	}
	/*
	*奖励设置
	*/
	function reward() {
		$model = new SettingModel();
		if($this->checksubmit()){
			$reward_1 =  I('post.reward_1',0,'intval,abs');
			$reward_2 =  I('post.reward_2',0,'intval,abs');
			$reward_3 =  I('post.reward_3',0,'intval,abs');
		
			if(empty($reward_1) || empty($reward_2) || empty($reward_3)) {
				$this->showmessage('error','奖励比例不能设置为空');
			}
			$all_reward = $reward_1 + $reward_2 + $reward_3;
			if($all_reward > 50){
				$this->showmessage('error','三级合计奖励比例不得超过50%');
			}
			$update_array['spread_reward'] = serialize(array(
					'reward_1'=>$reward_1,
					'reward_2'=>$reward_2,
					'reward_3'=>$reward_3,
				));
			$model->Settings($update_array);
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($update_array,true),'action'=>'推广设置-奖励设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			$this->showmessage('success','操作成功');
		}else{
			$spread_reward = $model->getSetting('spread_reward');
			$spread_reward = unserialize($spread_reward);
			$this->assign('data', $spread_reward);
			$this->display('reward');
		}
	}
	/*
	*会员奖励明细
	*/
	function presales() {
		$type_array = array(
			'member_mobile'=>'member_mobile',
			'member_uid'=>'member_uid',
			'order_sn'=>'order_sn',
		);
		$type = I('get.type');
		$text = I('get.search_text');
		$where = array();
		if($type && in_array($type,$type_array)) {
			$where["$type_array[$type]"] = array("like", "%".$text."%");
		}
		
		$model = D('SpreadWithdrawCash');
		$count = $model ->getPresalesCount($where);
		$page  = new \Common\Helper\PageHelper($count);
		$list = $model ->presales($where,$page);
		$this->assign('type', $type);
		$this->assign('search_text', $text);
		$this->assign('list', $list);
		$this->assign('page',$page->show());
		$this->display('presales');
	}
	/*
	*后台提现记录
	*/
	function withdraw() {
		$type_array = array(
			'cash_sn'=>'cash_sn',
			'member_mobile'=>'member_mobile',
			'member_uid'=>'member_uid',
			'user_name'=>'user_name',
		);
		$type = I('get.type');
		$status = I('get.status');
		$text = I('get.search_text');
		$where = array();
		if($type && in_array($type,$type_array)) {
			$where["$type_array[$type]"] = array("like", "%".$text."%");
		}
		$status && $where['status'] = $status;
		$model = D('SpreadWithdrawCash');
		$count = $model ->getAllWithdrawCount($where);
		$page  = new \Common\Helper\PageHelper($count);
		$list = $model ->getAllWithdraw($where,$page);
		$this->assign('page',$page->show());
		$this->assign('type', $type);
		$this->assign('status', $status);
		$this->assign('search_text', $text);
		$this->assign('list', $list);
		$this->display('withdraw');
	}
	
	function edit() {
	
		$id = I('param.id');
		$model = D('SpreadWithdrawCash');
		$data = $model->withdrawById($id,$status=1);
		if(empty($data)){
			$this->showmessage('error','提现数据不存在或已经审核过');
		}
		$status_array = array(1=>3,2=>2);
		if($this->checksubmit()){
			$status = I('post.agree');
			$remark = I('post.remark');
			if(empty($status_array[$status])){
				$this->showmessage('error','请选择同意或者拒绝');
			}
			$data['status'] = $status_array[$status];
			$data['remark'] = $remark;
			$data['enddate'] = TIMESTAMP;
			$data['user_id'] = $this->admin_user['uid'];
			$data['user_name'] = $this->admin_user['username'];
			$res = $model->optionWithdraw($this->admin_user['uid'],$data);
			if(false != $res) {
				\Common\Helper\LogHelper::adminLog(array('content'=>var_export($data,true),'action'=>'推广设置-奖励设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
				$this->showmessage('success','操作成功',U('Spread/withdraw',array('status'=>1)));
			}else{
				$this->showmessage('error','操作失败请重试');
			}
		}else{
			$this->assign('data', $data);
			$this->display('edit');
		}
	}
	
	function show() {
		$id = I('param.id');
		$model = D('SpreadWithdrawCash');
		$data = $model->withdrawById($id);
		if(empty($data)){
			$this->showmessage('error','提现数据不存在或已经审核过');
		}
		$this->assign('data', $data);
		$this->display('show');
	}
}