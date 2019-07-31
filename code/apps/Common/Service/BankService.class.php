<?php
namespace Common\Service;
class BankService {
	static function getList() {
		$bank  = D('Bank');
		$res = $bank->getList();
		return $res;
	}
	
	static function getBankById($id) {
		$bank  = D('Bank');
		$res = $bank->getBankById($id);
		return $res;
	}
	
	static function createMemberBankInfo($data) {
		$bank  = D('Bank');
		$res = $bank->createMemberBankInfo($data);
		return $res;
	}
	
	static function getMyBankInfoByUid($uid) {
		$bank  = D('Bank');
		$res = $bank->getMyBankInfoByUid($uid);
		return $res;
	}
}