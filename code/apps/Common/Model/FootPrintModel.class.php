<?php
namespace Common\Model;
use Think\Model;
class FootPrintModel extends Model {
	public function getFootPrint($uid,$page = 1,$prepage = 20) {
		$where = $lists = array();
		if(empty($uid)) return $lists;
		$where['member_id'] = $uid;
		if($type != null)
			$where['fav_type'] = $type;
		$start =( $page-1 ) * $prepage;
		$lists = $this->where($where)->order('add_time desc')->limit($start.','.$prepage)->select();
		foreach($lists as $k => $v) {
			$lists[$k]['goods_image'] = $v['goods_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['goods_image'] : '';
		}
		return $lists ? $lists : array();
	}
 
	
	public function FootPrint($data) {
		if(empty($data)) return false;
		return $this->add($data,array(),true);
	}
}
?>