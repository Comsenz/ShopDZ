<?php
namespace Common\Service;
class GoodsService {
   /**
    * 获取商品详情   spu前台展示用
    * 跟据spu id
    * @param unknown $goods_common_id
    */ 
   static  function  getGoodsDetail($goods_common_id){
       $goods_model  = D('Goods');
       return $goods_model->getSpuById($goods_common_id);
   }
   
   /**
    * 获取单个商品sku信息
    * @param 商品id $goods_id
    * @return Ambigous <string, \Think\mixed, boolean, NULL, multitype:, unknown, mixed, object>
    */
   static function getGood($goods_id) {
       $where = $data = array();
       $where['goods_id'] = $goods_id;
       $where['goods_state']  = 1;
       $data = M('goods')->master(true)->where($where)->find();
		if(!empty($data)) {
			$data['goods_image_real'] = $data['goods_image'];
			$data['goods_image'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$data['goods_image'];
		}
		return $data;
   }
   
   static function getGoods($where) {
       $where = $data = array();
       $data = M('goods')->master(true)->where($where)->select();
		foreach( $data as $k => $v ) {
			$data[$k]['goods_image_real'] = $v['goods_image'];
			$data[$k]['goods_image'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['goods_image'];
		}
		return $data;
   }
   
    static function getListById($id) {
    	return $data = array('list'=>array('aa','bb','cc'));
    }
    
    static function getAllShowCategory(){

			$category_model  =D('GoodsCategory');
			$category  =  $category_model->getShowCategoryTree();
			if(!empty($category)){
				foreach($category as $k=>$v){
					unset($category[$k]['gc_level']);
					unset($category[$k]['is_leaf']);
					unset($category[$k]['is_show']);
					unset($category[$k]['listorder']);
					$category[$k]['gc_image'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['gc_image'];
					unset($category[$k]['add_admin_id']);
					unset($category[$k]['add_time']);
					unset($category[$k]['update_admin_id']);
					unset($category[$k]['update_time']);
					unset($category[$k]['update_mark']);
					if(!empty($v['child'])){
						foreach($v['child'] as $chk=>$chv){
							unset($category[$k]['child'][$chk]['gc_level']);
							unset($category[$k]['child'][$chk]['is_leaf']);
							unset($category[$k]['child'][$chk]['is_show']);
							unset($category[$k]['child'][$chk]['listorder']);
							unset($category[$k]['child'][$chk]['add_admin_id']);
							unset($category[$k]['child'][$chk]['add_time']);
							unset($category[$k]['child'][$chk]['update_admin_id']);
							unset($category[$k]['child'][$chk]['update_time']);
							unset($category[$k]['child'][$chk]['update_mark']);
							unset($category[$k]['child'][$chk]['child']);
							$category[$k]['child'][$chk]['gc_image'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$chv['gc_image'];
						}
					}
				}
		}
        return $category;
    }
    
    static function goods_list($condition=1,$field='*',$order='goods_common_id desc',$limit='0,20'){
        $goods_common  =   M('goods_common')->where($condition)->field($field)->order($order)->limit($limit)->select();   
        if(empty($goods_common)){
            return array();
        } 
        foreach($goods_common as $k=>$v){
            $goods_common[$k]['goods_image']  = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['goods_image'];  
        }
        return $goods_common;
    }
	
	static function getGoodsImages($goods_common_id) {
		$goods_model  = D('Goods');
		return $goods_model->getGoodsImages($goods_common_id);
	}
    
    /**
    * 获取单个商品信息
    * @param 商品common_id $goods_common_id
    * @return Ambigous <string, \Think\mixed, boolean, NULL, multitype:, unknown, mixed, object>
    */
    static function getGoodsCommon($goods_common_id){
        $where = $data = array();
		$where['goods_common_id'] = $goods_common_id;
		$data = M('goods_common')->master(true)->where($where)->find();
		if(!empty($data)) {
			$data['goods_image_real'] = $data['goods_image'];
			$data['goods_image'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$data['goods_image'];
		}
		return $data;
    }
    
}