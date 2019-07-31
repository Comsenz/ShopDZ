<?php
namespace Common\Model;
use Think\Model;
class InstallClientModel extends Model {
	function addClient($data) {
		if(empty($data)) return false;
		$res = $this->add($data);
		return $res;
	}
}
?>