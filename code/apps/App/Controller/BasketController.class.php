<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\BasketService;
use Common\Service\GoodsService;
use Common\Service\MemberService;
use Common\Helper\ToolsHelper;
class BasketController extends BaseController {
	 static $goods_num = 99;
	function addBasket() {
		$this->getMember();
		$member_id = $this->member_info['member_id'];
		$goods_id = I("param.id");
		$type = I("param.type");//来自购物车的修改，还是详情页的修改
		$num = I("param.num");//购买的数量
		$goods_id = intval($goods_id);
		$total_num = 0;
		if(empty($goods_id))
			$this->returnJson(1,'缺少商品参数');
		if(empty($num))
			$this->returnJson(1,'请选择购买数量');
		$id = \Common\Helper\LoginHelper ::getNOloginSid();
		if(!empty($member_id)) {
			$basket = BasketService::getLoginBasket($member_id);
		}else{
			$basket = BasketService::getNoLoginBasket($id);
		}

		$key = ToolsHelper::exists_seach_key($goods_id,'goods_id',$basket);
		if($key !== false){
			$basket_good_num = $basket[$key]['goods_num'];//购物车已经存在的数量
			if($type !='basket'){
				$total_num = $basket_good_num + $num;
			}else{
				$total_num = $basket_good_num;
			}
		}
		 //如果存在则返回数组的key
		if($key !== false && count($basket)  > self::$goods_num) {
			$this->returnJson(1,'超过购物车最大值存放商品数量',array('goods_num'=>self::$goods_num));
		}
		$goodinfo = GoodsService::getGood($goods_id);
		if(empty($goodinfo))
			$this->returnJson(1,'商品不存在');
		$goods_storage  = $goodinfo['goods_storage'];
		$shownum = $goods_storage >= self::$goods_num ? self::$goods_num : $goods_storage;
		if($goods_storage < $num) {//超过库存,把库存复制给num，购物车页面
			$num = $goods_storage;
			$flag = 1;
		}
		if($total_num > $goods_storage || $total_num > self::$goods_num ) {//详情页加入购物车
			$num = $shownum;
			$type = 'basket';
		}
		if($num > self::$goods_num) {
			$flag = 2;
			$num = self::$goods_num;
			$type = 'basket';
		}
		if(!empty($member_id)) 
			$return = BasketService::loginAddBasket($member_id,$num,$goodinfo,$type);
		else
			$return = BasketService::noLoginAddBasket($id,$num,$goodinfo,$type);
		
		if($return !== false){
			if($flag){
				$msg = $flag ==1 ? '商品库存不足' : '超过商品购买上限';
				$this->returnJson(1,$msg,array('num'=>($key!== false ? count($basket) : count($basket)+1 ),'goods_num'=>$num));
			}else{
				$this->returnJson(0,'sucess',array('num'=>($key!== false ? count($basket) : count($basket)+1 ),'goods_num'=>$num));
			}
		}else{
			$this->returnJson(1,'添加失败请重试',$data);
		}
	}
	
	function delBasket() {
		$this->getMember();
		$member_id = $this->member_info['member_id'];
		$loginid = \Common\Helper\LoginHelper ::getNOloginSid();
		$id = I("param.id");
		
		if(empty($id))
			$this->returnJson(1,'缺少参数');
		if(!empty($member_id)) 
			$return = BasketService::loginDelBasket($id,$member_id);
		 else
			$return = BasketService::noLoginDelBasket($id,$loginid);
		if($return)
			$this->returnJson(0,'sucess');
		else
			$this->returnJson(1,'失败请刷新重试');
	}
	function getMember() {
		$key = I('param.key');
		if(empty($key))
			return;
        $user_token_info = MemberService::getUserTokenInfoByToken($key);
        if(!empty($user_token_info)) {
           $this->member_info = $user_token_info;
        }
	}
	
	function getBasket() {
		$data = array();
		$this->getMember();
		$member_id = $this->member_info['member_id'];
		if(empty($member_id))
			$loginid = \Common\Helper\LoginHelper ::getNOloginSid();
		if(!empty($member_id)) 
			$data = BasketService::getLoginBasket($member_id);
		else
			$data = BasketService::getNoLoginBasket($loginid);

		foreach( $data as $k => $v ) {//物品状态 2 ：已下架 1超库存 0：正常  3已售罄
			$goods = array();
			$data[$k]['goods_image'] = $v['goods_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['goods_image'] : '';
			$goods = GoodsService::getGood($v['goods_id']);
			$data[$k]['status'] = $goods ? ($goods['goods_storage'] ?($goods['goods_storage'] < $v['goods_num']? 1:0) :3 ) : 2;
		}
		$this->returnJson(0, $this->member_info ? 'login': 'nologin',$data);
	}
	
	function goodssum() {
		$data = array();
		$this->getMember();
		$member_id = $this->member_info['member_id'];
		if(empty($member_id))
			$loginid = \Common\Helper\LoginHelper ::getNOloginSid();
		if(!empty($member_id)) 
			$data = BasketService::getLoginBasket($member_id);
		else
			$data = BasketService::getNoLoginBasket($loginid);
		$result = array('num'=>count($data),'list'=>$data);
		$this->returnJson(0, $this->member_info ? 'login': 'nologin',$result);
	}
}