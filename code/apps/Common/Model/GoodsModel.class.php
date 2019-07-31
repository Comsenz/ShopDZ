<?php
namespace Common\Model;
use Think\Model;
class GoodsModel extends Model {

	

	public function  test(){
		echo  'GoodsModel';
	}
	
	/**
	 * 更新goods_common表的spu信息
	 * @param unknown $data
	 * @param unknown $condition
	 * @return boolean
	 */
	public function updateGoodsCommonBase($data,$condition){
	    if(empty($data)||empty($condition))  return false;
	    $data['update_time'] = time();
	    $result =  M('goods_common')->where($condition)->save($data);
	    if($result){
	        //更新商品基础表商品名称等
	    }
	    return $result;
	}
	
	public function getGoodSImages($goods_common_id) {
		$goods_image = $goods_common = array();
		if(empty($goods_common_id)) return $goods_image;
		$goods_image = M('goods_images')->field('image_url')->where(array('goods_common_id'=>$goods_common_id))->order('is_default desc,listorder desc')->select();
	   if(!empty($goods_image)){
		   foreach($goods_image as $v){
				if(!empty($v['image_url'])){
					$goods_common[] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['image_url'];
				}
		   }
	   }
	   if(empty( $goods_common)){
			$deafult_img = M('setting')->where(array('name' => 'shop_goods'))->find();
			if(!empty($deafult_img['value'])){
				$goods_common[] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$deafult_img['value'];
			}else{
				$goods_common[] = C('TMPL_PARSE_STRING.DEFAULT_GOODS_IMAGE');
			}
	   }
	   return $goods_common;
	}

	/**
	 * 获取spu详情 前台展示用
	 * @param spu_id $goods_common_id
	 * @return multitype:|string
	 */
	public function  getSpuById($goods_common_id){
	   $goods_common_id = intval($goods_common_id);
	   if(!$goods_common_id){
	       return false;
	   }
	   $cache =  S($goods_common_id,'',array('prefix'=>''));
	   $cache= array();
	   if(!empty($cache)){
	       return   $cache;
	   }else{
    	   $condition = array();
           $condition['goods_common_id'] = $goods_common_id;
           $condition['goods_state']  = 1;
    	   $goods_common = array();
    	   $goods_common   = M('goods_common')->where($condition)->find();
    	   if(empty($goods_common)){
    	       return  array();
    	   }
    	   $goods_common['spec']   = unserialize($goods_common['spec']);
    	   if(empty( $goods_common['spec'] )){
    	       return array();
    	   }
    	   $goods_spec_list =  array();
    	   foreach($goods_common['spec'] as  $spec_id=>$v){
    	       $goods_spec_list[$spec_id]['spec_name'] = M('spec')->where(array('spec_id'=>$spec_id))->getField('spec_name');
    	       $temp  =    M('spec_value')->where(array('spec_id'=>$spec_id))->select();
    	       $new_temp = array();
    	       foreach($temp as $ts){
    	           $new_temp[$ts['spec_value_id']]  = $ts;
    	       }
    	       $goods_spec_list[$spec_id]['spec_value_list'] = $new_temp;
    	   }
    	   $all_spec_ids   =  array_keys($goods_common['spec']);
    	   $all_spec_value_ids = array();
    	   foreach($goods_common['spec'] as $v){
    	      $all_spec_value_ids = array_merge($all_spec_value_ids,$v);
    	   }
    	   $all_spec_list = M('spec')->field('spec_id,spec_name')->where(array('spec_id'=>array('in',$all_spec_ids)))->select();
    	   $all_spec_list = key_convert('spec_id',$all_spec_list);
    	   $all_spec_value_list =  M('spec_value')->field('spec_value_id,spec_id,spec_value')->where(array('spec_value_id'=>array('in',$all_spec_value_ids)))->select();
    	   $all_spec_value_list = key_convert('spec_value_id',$all_spec_value_list);
    	   
    	   $new_spec  =  array();
    	   foreach($goods_common['spec']  as $spec_id=>$spec_value_ids){
    	       $temp  = array();
    	       $temp['spec_id']   = $spec_id;
    	       $temp['spec_name'] =  $all_spec_list[$spec_id]['spec_name'];
    	       foreach($spec_value_ids as $spec_value_id){
    	           $temp['spec_value'][$spec_value_id]  = $all_spec_value_list[$spec_value_id]['spec_value'];
    	       }
    	       $new_spec[$spec_id] = $temp;
    	   }
    	   $goods_common['spec']  =  $new_spec;
    	   $goods_list =   M('goods')->field('goods_id,goods_name,goods_state,goods_price,goods_storage,goods_image,spec_value_id_key,goods_spec')->where(array('goods_common_id'=>$goods_common['goods_common_id']))->select();
    	   $deafult_img = M('setting')->where(array('name' => 'shop_goods'))->find();
    	   $goods_storage =  0;
    	   foreach($goods_list as $goods_info){
    	       $goods_storage+=  intval($goods_info['goods_storage']);
    	       $goods_spec_value_ids =    unserialize($goods_info['goods_spec']);
    	       $goods_spec_values =  array();
    	       foreach($goods_spec_value_ids as $v){
    	           $goods_spec_values[] = $all_spec_value_list[$v]['spec_value'];
    	       }
    	       $goods_info['spec_goods_name']   =  implode('，', $goods_spec_values);
    	       if(empty($goods_info['goods_image'])){
    	       		if(!empty($deafult_img['value'])){
    	       			$img_url1 = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$deafult_img['value'];
    	       		}else{
    	       			$img_url1 = C('TMPL_PARSE_STRING.DEFAULT_GOODS_IMAGE');
    	       		}
    	       }else{
    	       		$img_url1 = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$goods_info['goods_image'];
    	       }
    	       $goods_info['goods_image']   = $img_url1 ; 
    	       $goods_info['goods_spec']   = unserialize($goods_info['goods_spec']);
    	       $goods_common['goods_list'][$goods_info['spec_value_id_key']]  = $goods_info;
    	   }
    	   $goods_common['goods_storage']  =  $goods_storage;
    	   //商品图片
    	   $goods_image = M('goods_images')->field('image_url')->where(array('goods_common_id'=>$goods_common['goods_common_id']))->order('is_default desc,listorder desc')->select();
    	   if(!empty($goods_image)){
    	       foreach($goods_image as $v){
    		       	if(!empty($v['image_url'])){
    		            $goods_common['goods_images'][] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['image_url'];
    		       	}
    	       }
    	   }
    	   if(empty( $goods_common['goods_images'])){
    	   		if(!empty($deafult_img['value'])){
           			$goods_common['goods_images'][] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$deafult_img['value'];
           		}else{
           			$goods_common['goods_images'][] = C('TMPL_PARSE_STRING.DEFAULT_GOODS_IMAGE');
           		}
    	   }
    	   //商品参数
    	   if(empty($goods_common['goods_image'])){
           		if(!empty($deafult_img['value'])){
           			$img_url2 = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$deafult_img['value'];
           		}else{
           			$img_url2 = C('TMPL_PARSE_STRING.DEFAULT_GOODS_IMAGE');
           		}
           }else{
           		$img_url2 = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$goods_common['goods_image'];
           }
    	   $goods_common['goods_image']   = $img_url2 ;
    	   $param = M('goods_param')->field('param_name,param_value')->where(array('goods_common_id'=>$goods_common['goods_common_id']))->order('listorder desc')->select();
    	   $goods_common['goods_param']   = $param;
    	   $goods_common['goods_detail'] = htmlspecialchars_decode(stripslashes($goods_common['goods_detail']));
    	   $goods_list  = $goods_common['goods_list'];
    	   $sell_goods_list  = array();
    	   foreach($goods_list as $v){
    	       if($v['goods_storage'] > 0){
    	           $temp = array();
    	           foreach($v['goods_spec'] as $spev){
    	               $temp[]  = $spev;
    	           }
    	           $sell_goods_list[] = $temp;
    	       }
    	   }
    	   $goods_common['sell_goods_list']  = $sell_goods_list;
		   $path = 'goods_qcode';
		   $goods_common['qr_code'] =  C('TMPL_PARSE_STRING.__ATTACH_HOST__').$path.'/'.fmod($goods_common_id,100).'/'.$goods_common_id.".png";
    	   $cache =  S($goods_common_id,$goods_common,array('prefix'=>'','expire'=>1800));
    	   return $goods_common;
	   }
	}
	
	/**
	 * 删除spu缓存
	 * @param int $goods_common_id
	 */
	public function delSpuCache($goods_common_id){
	    S($goods_common_id,null,array('prefix'=>'','expire'=>1800));
	    return true;
	}
	public  function delSkuCache($goods_id){
	    $goods_id =  intval($goods_id);
	    if(!goods_id){
	        return false;
	    }
	    $goods_common_id = M('goods')->where(array('goods_id'=>$goods_id))->getField('goods_common_id');
	    $this->delSpuCache($goods_common_id);
	    return true;
	}
	
	public function searchGood($condition,$page) {
		$list =  M('goods')->where($condition)
            ->limit($page->firstRow.','.$page->listRows)->select();
		foreach($list as $k => $v) {
			$list[$k]['goods_image'] = $img_url2 = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['goods_image'];
		}
		return $list ? $list : array();
	}
	
	public function getGoodById($id){
		$id = intval($id);
		$data = array();
		if($id)
			$data = $this->where('goods_id=%d',$id)->find();
		return $data;
	}
	
	public function searchGoodCount($condition) {
		$count = M('goods') ->where($condition)->count();
	}
    public function getGoodsDetail($goods_common_id){
        $result = array();
        if(!empty($goods_common_id)){
            $result = D('goods_common')->where(array('goods_common_id'=>$goods_common_id))->field('goods_detail')->find();
        }
       // return htmlspecialchars_decode(stripslashes($result['goods_detail']));
        return $result;

    }
}
?>