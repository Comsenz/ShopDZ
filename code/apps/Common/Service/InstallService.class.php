<?php
/**
 * 快递查询  对接的是快递鸟的接口
 * @author Administrator
 */
namespace Common\Service;
use Think\Model;

class InstallService {
    static  function  addClient($data){
		$model  = D('InstallClient');
		$res = $model->addClient($data);
		return $res;
    }
}