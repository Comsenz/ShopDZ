<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\GoodsService;
use Common\Helper\CacheHelper;
class GoodsController extends BaseController {
    /**
     * 商品详情
     */
	public function goods_detail() {
	    $goods_common_id = intval($_REQUEST['id']);
	    if(!$goods_common_id){
	       $this->returnJson(1,'参数错误');
	    }  
		$data = GoodsService::getGoodsDetail($goods_common_id);
		if(empty($data) || empty($data['goods_list'])){
		    $this->returnJson(1,'该商品不存在或者已下架');
		}
		//增加浏览了
		M("goods_common")->where(array('goods_common_id'=>$goods_common_id))->setInc("view_number");
		$this->returnJson(0,'sucess',$data);
	}
	
	/**
	 * 获取所有显示的商品分类  及其子分类
	 */
	public function  all_category(){
	    $data = GoodsService::getAllShowCategory();
	    $this->returnJson(0,'sucess',array('category' => $data));
	}
	
	/**
	 * 商品列表页面接口
	 */
	public function  goods_list(){
	    $where  = array();
	    $q =  I('q','','trim,addslashes,htmlspecialchars'); 
	    $p =  I('p',1,'intval');
	    $order  =  I('order','','trim,addslashes,htmlspecialchars'); 
		$gc_id =  I('gc_id',0,'intval');
	    if($q){
	        $where['goods_name'] = array('like','%'.$q.'%');
	    }
  		$key = CacheHelper::getCachePre('getmuti_good_list');
		$key = sprintf($key,$gc_id,$p,$order);
		$data =  S($key);
		if(!empty($q) || false === $data){
			if($gc_id){
				$category = M('goods_category') -> where("gc_id = $gc_id") ->find();
				if(empty($category)){
					 $this->returnJson(1,'分类不存在或者已删除');
				}
				if($category['gc_parent_id'] == 0){
					$where['gc_id_1'] = $gc_id;
				}else{
					$where['gc_id'] = $gc_id;
				}
			}
			$orders = array();
			$orders['idA']   =      'goods_common_id ASC';
			$orders['idD']   =      'goods_common_id DESC';  //id
			$orders['priceA']   =      'goods_price ASC';
			$orders['priceD']   =      'goods_price DESC';   //价格
			$orders['hitsA']   =      'hits ASC';
			$orders['hitsD']   =      'hits DESC';          //浏览量
			$orders['salenumA']   =      'sale_num ASC';
			$orders['salenumD']   =      'sale_num DESC';  //销量
			$orders['evaluateA']   =      'evaluate_num  ASC';
			$orders['evaluateD']   =      'evaluate_num DESC';  //评论数
			$allow_orders  =  array_keys($orders) ;
			$order =  in_array($order, $allow_orders) ? $order  : 'idD';
			$order = $orders[$order];
			$where['goods_state'] = 1;
			$all = M('goods_common');
			$count = M('goods_common')->where($where)->count();
			$perpage = 10;
			$max_page = ceil($count/$perpage);
			if($max_page==0){
				$max_page = 1;
			}
			if($p>$max_page){
				$this->returnJson(1,'已经到底部了');
			}
			$data = array();
			$data['has_more'] =  $p<$max_page ? true : false;
			$data['max_page'] = $max_page;
			$data['categorys'] = M('goods_category')->where("gc_id = $gc_id")->field('gc_image,desc,gc_name')->find();
			$data['categorys'] = $data['categorys'] ? $data['categorys']: array();
			if(!empty($data['categorys']['gc_image'])){
				$data['categorys']['gc_image'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$data['categorys']['gc_image'];
			}
			$page  = new \Common\Helper\PageHelper($count,$perpage);
			$limit =  $page->firstRow.','.$page->listRows;
			$data['goods_list']  = GoodsService::goods_list($where,'*',$order,$limit);
			S($key,$data,60);
		}
	    $this->returnJson(0,'sucess',$data);
	}
}