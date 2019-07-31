<?php
namespace Common\Model;
use Think\Model;
class FavoritesModel extends Model {
	public function getFavorites($uid,$type=null,$page = 1,$prepage = 20) {
		$where = $lists = array();
		if(empty($uid)) return $lists;
		$where['member_id'] = $uid;
		if($type != null)
			$where['fav_type'] = $type;
		$start =( $page-1 ) * $prepage;
		$lists = $this->field('fav_time,goods_name,goods_image,fav_id,fav_type,member_id,store_id,store_name,log_id,log_price')->where($where)->order('fav_time desc')->limit($start.','.$prepage)->select();
		foreach($lists as $k => $v) {
			$lists[$k]['goods_image'] = $v['goods_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['goods_image'] : '';
		}
		return $lists ? $lists : array();
	}
	
	public function getFavoritesCount($uid,$type=null) {
		$where['member_id'] = $uid;
		if($type != null)
			$where['fav_type'] = $type;
		$count = $this->where($where)->count();
		return $count ? $count : 0;
	}
	
	public function favorites($data) {
		if(empty($data)) return false;
		return $this->add($data);
	}
	
	public function delFavoritesById($uid,$fav_id){
		if(empty($fav_id) || empty($uid)) return false;
		$where ="member_id={$uid}";
		if(is_array($fav_id)){
			$where .= ' and fav_id in('.implode(',',$fav_id).')';
		}else{
			$where .= ' and fav_id='.$fav_id;
		}
		return $this->where($where)->delete();
	}

	public function isFavorites($uid,$id,$type="goods"){
		if(empty($id) || empty($uid)) return false;
		$where = array();
		$where['fav_id'] = $id;
		$where['member_id'] = $uid;
		$where['fav_type'] = $type;
		$result = $this->where($where)->find();
		if(!empty($result))
			return true;
		return false;
	}
}
?>