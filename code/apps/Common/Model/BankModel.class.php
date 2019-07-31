<?php
namespace Common\Model;
use Think\Model;
class BankModel extends Model {
	function getList($status = 0) {
		$where['status'] = 0;
		return $this->where($where)->select();
	}
	
	function getBankById($id) {
		if(empty($id)) return array();
		return $this->where('id=%d',$id)->find();
	}
	
	function getMyBankInfoByUid($id){
		if(empty($id)) return array();
		$bank  = D('SpreadBank');
		$res = $bank->where('member_uid=%d',$id)->find();
		return $res ? $res : array();
	}
	
	function createMemberBankInfo($data) {
		if(empty($data)) return false;
		$bank  = D('SpreadBank');
		if($data['id']){
			$res = $bank->save($data);
		}else{
			$res = $bank->add($data);
		}
		return $res ? $res : array();
	}
}
?>