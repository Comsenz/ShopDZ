<?php
namespace Common\Model;
use Think\Model;
class SmsModel extends Model {

	  /**
	  	*	统计个人站内信
		*	@param  $member_id : 接收用户id
		*			$from_id : 发送人  
		*			$status : 消息状态（0：未读   1:已读    2:草稿箱'）
		*			$type : 消息属性（1：系统提示（对固定用户）     2：会员间站内信      3：系统消息'） 
		*			如统计未读站内信，传$member_id即可
		**/
	function count_notice($member_id=false,$from_id=false,$status=0,$type=false){
		$where = array();
		if($member_id !== false) $where['to_id'] = $member_id;
		if($from_id !== false) $where['from_id'] = $from_id;
		if($type == false) {
			$where['type'] = array('in',array(1,3));
		}else{
			$where['type'] = $type;
		}
		$where['status'] = $status;
		return $this->where($where)->count();
	}

	/**
	  	*	个人站内信list
		*	@param  $member_id : 接收用户id
		*			$from_id : 发送人  
		*			$status : 消息状态（0：未读   1:已读    2:草稿箱'）
		*			$type : 消息属性（1：系统提示（对固定用户）     2：会员间站内信      3：系统消息'） 
		*			$page : 第几页
		*			$prepage : 每页多少条
		*			如统计未读站内信，传$member_id即可
		**/
	function get_notice($member_id=false,$from_id=false,$status=0,$type=3,$page = 1,$prepage = 20){
		$where = array();
		if($member_id !== false) $where['to_id'] = $member_id;
		if($from_id !== false) $where['from_id'] = $from_id;
		if($status !== false) $where['status'] = $status;
		// $where['status'] = $status;
		// $where['type'] = $type;
		if($type == 1 || $type == 3){
			$where['type'] = array('in',array(1,3));
		}
		$start = ( $page-1 ) * $prepage;
		return $this->where($where)->order('dateline desc')->limit($start.','.$prepage)->select();
	}

	function update_notice($where,$update){
		return $this->where($where)->save($update);
	}

	function del_notice($where){
		return $this->where($where)->delete();
	}
}

?>