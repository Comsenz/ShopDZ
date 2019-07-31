<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\FavoritesService;
use Common\Service\GoodsService;
class FavoritesController extends BaseController {
	/*
	*获取收藏
	*/
	function getfavorites() {
		$page = I('param.page',1,'intval');
		$prepage = I('param.prepage',8,'intval');
		$type = I('param.fav_type',null);
		$this->getMember();
		$uid= $this->member_info['member_id'];
		$list = FavoritesService::getFavorites($uid,$type,$page,$prepage);
		$this->returnJson(0,'success',array('list'=>$list));
	}
	//判断是否收藏过该商品
	function hasfavorites() {
		$type = I('param.type','goods');
		$fav_id = I('param.fav_id','0');//common_id
		if(empty($fav_id))
			$this->returnJson(1,'数据错误，请重试');
		$this->getMember();
		$uid= $this->member_info['member_id'];
		$goods = FavoritesService::isFavoritesById_type($uid,$fav_id,$type);
		$this->returnJson(0,'success',$goods);
	}
	/*
	*保存收藏
	*/
	function favorites() {
		$fav_type = I('param.type','goods');
		$fav_id = I('param.fav_id','0');
		$status = I('param.favorites_status','0');
		if(empty($fav_id))
			$this->returnJson(1,'数据错误，请重试');
		$this->getMember();
		$goods = GoodsService::getGoodsCommon($fav_id);
		if(empty($goods))
			$this->returnJson(1,'数据错误，请重试');
		$uid= $this->member_info['member_id'];
		if($status == 'is_favorites'){
			$result = FavoritesService::delFavoritesById($uid,$fav_id);
			if($result){
				$this->returnJson(0,'取消收藏成功~');
			}else{
				$this->returnJson(1,'数据错误，请重试');
			}
		}
		$good = FavoritesService::isFavoritesById_type($uid,$fav_id,$fav_type);
		if($good)
			$this->returnJson(1,'已经收藏过，请勿重复收藏');
		$data = array(
			'member_id'=>$uid,
			'fav_type'=>$fav_type,
			'fav_time'=>TIMESTAMP,
			'fav_id'=> $fav_id,
			'goods_name'=>$goods['goods_name'],
			'goods_image'=>$goods['goods_image_real'],
			'log_price'=>$goods['goods_price'],
			'member_name'=>$this->member_info['member_mobile'],
		);
		$result = FavoritesService::favorites($data);
		if($result)
			$this->returnJson(0,'收藏成功~',array('list'=>$list));
		$this->returnJson(1,'数据错误，请重试');
	}
	/*
	*删除收藏
	*/
	function delfavorites() {
		$fav_id = I('param.fav_id','0');
		if(empty($fav_id))
			$this->returnJson(1,'数据错误，请重试');
		$this->getMember();
		$uid= $this->member_info['member_id'];
		$result = FavoritesService::delFavoritesById($uid,$fav_id);
		if($result)
			$this->returnJson(0,'success');
		$this->returnJson(1,'数据错误，请重试');
	}
	
	function getfavoritescount() {
		$this->getMember();
		$uid= $this->member_info['member_id'];
		$count = FavoritesService::getFavoritesCount($uid);
		$this->returnJson(0,'success',array('count'=>$count));
	}
	
}