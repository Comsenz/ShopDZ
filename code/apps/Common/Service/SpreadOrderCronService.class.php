<?php
namespace Common\Service;
class SpreadOrderCronService {
	static function addCron($uid,$data,$model =null) {
		$model  = D('SpreadOrderCron');
		return $model->addCron($uid,$data,$model);
	}
	
}