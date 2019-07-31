<?php
/**
 * this file is not freeware
 * User: al_bat
 * DATE: 2016/4/15
 */
namespace Admin\Model;
use Think\Model;
class AccessModel extends Model{
    protected $tableName = "rbac_access";

    public function getAccessByRoleId($roleid){
		$roleid = intval($roleid);
		if(empty($roleid))
			return array();
		$pre = C('DB_PREFIX');
		$rs = $this->join("{$pre}rbac_node ON {$pre}rbac_access.node_id = {$pre}rbac_node.id")->where('role_id='.$roleid) ->select();
        foreach ($rs as $node){
            $access[$node['controller']][strtolower($node['name'])]	=	$node;
        };
		return $access;
    }
}