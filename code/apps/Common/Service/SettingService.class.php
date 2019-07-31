<?php
namespace Common\Service;
class SettingService {
	static function getList() {
		$bank  = D('Bank');
		$res = $bank->getList();
		return $res;
	}
}