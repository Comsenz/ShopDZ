<?php
namespace Common\Service;
use Common\Helper\ToolsHelper;
class BasketService {
   //  登录用户添加到购物车
   static  function loginAddBasket($id,$num,array $goodinfo,$type='basket') {
		$m = M('basket'); 
		$goods_id = $goodinfo['goods_id'];
		$data = $m->where("member_uid ='%s' and goods_id=%s",array($id,$goods_id))->find();
		if(!empty($data)) {
			if($type == 'basket')
				$data['goods_num'] = $num;
			else
				$data['goods_num'] = $data['goods_num']+$num;//来自详情页的
				
			$data['add_time'] = TIMESTAMP;
			$result = $m ->save($data);
		}else{
			$info['member_uid'] = $id;
			$info['goods_id'] = $goods_id;
			$info['goods_common_id'] = $goodinfo['goods_common_id'];
			$info['spec_name'] = $goodinfo['spec_name'];
			$info['goods_name'] = $goodinfo['goods_name'];
			$info['goods_image'] = $goodinfo['goods_image_real'];
			$info['goods_num'] = $num;
			$info['goods_price'] = $goodinfo['goods_price'];
			$info['add_time'] = TIMESTAMP;
			$result = $m ->add($info);
		}
		return $result;
   }
   //未登录用户添加到临时表
   static  function noLoginAddBasket($id,$num,array $goodinfo,$type='basket') {
		$m = M('basketNologin'); 
		$goods_id = $goodinfo['goods_id'];
		$data = $m->where("sid ='%s' and goods_id=%s",array($id,$goods_id))->find();
		if(!empty($data)) {
			if($type == 'basket')
				$data['goods_num'] = $num;
			else
				$data['goods_num'] = $data['goods_num']+$num;//来自详情页的
			$data['add_time'] = TIMESTAMP;
			$result = $m ->save($data);
		}else{
			$info['sid'] = $id;
			$info['goods_id'] = $goods_id;
			$info['goods_common_id'] = $goodinfo['goods_common_id'];
			$info['spec_name'] = $goodinfo['spec_name'];
			$info['goods_name'] = $goodinfo['goods_name'];
			$info['goods_num'] = $num;
			$info['goods_image'] = $goodinfo['goods_image_real'];
			$info['goods_price'] = $goodinfo['goods_price'];
			$info['add_time'] = TIMESTAMP;
			$result = $m ->add($info);
		}
		return $result;
   }
   
   static  function noLoginDelBasket($basketid,$sid) {
		$m = M('basketNologin'); 
		$data = $m->where("id=%d and sid='%s'",array($basketid,$sid))->find();
		if(empty($data))
			return false;
		$return = $m->where('id=%d',array($basketid))->delete();
		return $return;
   }
   
   static  function loginDelBasket($basketid,$member_id) {
		$m = M('basket'); 
		$data = $m->where("id =%d and member_uid=%d",array($basketid,$member_id))->find();
		if(empty($data))
			return false;
		$return = $m->where('id=%d',array($basketid))->delete();
		return $return;
   }
   
   static  function getLoginBasket($id) {
		$m = M('basket'); 
		$goods = array();
		$data = $m->where("member_uid=%d",array($id))->order('id desc')->select();
		foreach($data as $k => $v) {
			if($v['goods_id']){
				$where = $aaa = array();
			    $where['goods_id'] = $v['goods_id'];
			    $where['goods_state'] = 1;
			    $goods = M('goods')->master(true)->where($where)->find();
			    if(!empty($goods)){
			    	$v['goods_price'] = $goods['goods_price'];
			    }
			}
			$good[] = $v;
		}
		return $good ? $good: array();
   }
   
   static  function getNoLoginBasket($id) {
		$m = M('basketNologin'); 
		$goods = array();
		$data = $m->where("sid='%s'",array($id))->order('id desc')->select();
		foreach($data as $k => $v ) {
			if($v['goods_id']){
				$g  = D('Goods');
       			$goods =  $g->getSpuById($v['goods_id']);
			}
			$v['goods_price'] = $goods['goods_price'];
			$good[] = $v;
		}
		return $good ? $good: array();
   }
   
   static function getNoLoginBasketGoodsSum($id) {
		$m = M('basketNologin'); 
		return $data = $m->where("sid='%s'",array($id))->sum('goods_num');
   }
   
   static function getLoginBasketGoodsSum($id) {
		$m = M('basket'); 
		return $data = $m->where("member_uid=%d",array($id))->sum('goods_num');
   }
   
   static function getGoodsInfo( $basketid = array()) {
		if(empty($basketid)) return;
		$basketids = implode(',',$basketid);
		$m = M('basket'); 
		$where['id'] = array('in',$basketids);
		return $data = $m->where($where)->select();
   }
   
   static function syncLoginbasket($sid,$uid) {
		$basketNoLogin = self::getNoLoginBasket($sid);
		$basket = self::getLoginBasket($uid);
		$m = M('basket'); 
		$basketNologin = M('basketNologin'); 
		foreach($basketNoLogin as $k => $v ) {
			$basket_= array(
				'member_uid'=>$uid,
				'goods_id'=>$v['goods_id'],
				'goods_name'=>$v['goods_name'],
				'goods_num'=>$v['goods_num'],
				'goods_image'=>$v['goods_image'],
				'goods_price'=>$v['goods_price'],
				'goods_common_id' => $v['goods_common_id'],
				'spec_name' => $v['spec_name'],
			);
			$key = ToolsHelper::exists_seach_key($v['goods_id'],'goods_id',$basket);//如果存在则返回数组的key

			if( false === $key ) {//不存在入库
				$result = $m->add($basket_);
			}else{
				$current_basket = $basket[$key];
				$basket_ = array(
					'id'=>$current_basket['id'],
					'goods_num'=>$current_basket['goods_num']+$v['goods_num'],
				);
				$result = $m->save($basket_);
			}
		}
		if($result !== false) {
			$basketNologin = M('basketNologin'); 
			$basketNologin->where("sid='%s'",array($sid))->delete();
		}
			
   }
}