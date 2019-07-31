<?php
namespace Common\Service;
class SpreadService {
	static function withdraw($data) {
		$cash  = D('SpreadWithdrawCash');
		$res = $cash->addSpreadCrash($data);
		return $res;
	}
	
	static function getWithdrawdDtail($cash_sn,$uid) {
		$cash  = D('SpreadWithdrawCash');
		$res = $cash->getWithdrawdDtail($cash_sn,$uid);
		return $res;
	}
	
	static function getMyWithdraw($uid,$type=null,$page = 1,$prepage = 20) {
		$cash  = D('SpreadWithdrawCash');
		$res = $cash->getMyWithdraw($uid,$type,$page,$prepage);
		return $res;
	}
	
	static function account($uid) {
		$cash  = D('SpreadWithdrawCash');
		$res = $cash->account($uid);
		return $res;
	}
	
	static function getMyPresales($uid,$spread_level=null,$page = 1,$prepage = 20) {
		$cash  = D('SpreadWithdrawCash');
		$res = $cash->getMyPresales($uid,$spread_level,$page,$prepage);
		return $res;
	}

	static function UpdateWithdraw($uid,$data) {
		$cash  = D('SpreadWithdrawCash');
		$res = $cash->optionWithdraw($uid,$data);
		return $res;
	}
}