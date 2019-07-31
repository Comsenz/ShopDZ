<?php
namespace Common\Model;
use Think\Model;
use Common\Helper\CacheHelper;
class GoodsCategoryModel extends Model {

	public function test(){
		echo    'GoodsCategoryModel';
	}
	
	
	
	/**
	 *
	 * @param array  $data
	 * @return  int $insert_id  || false
	 */
	public function  addCategory($data){
	    $categoryid  =  $this->add($data);
	    if($categoryid){
	        $this->reCachAllCategoryTree();//更改分类缓存
	        return $categoryid;
	    }else{
	        return  false;
	    }
	}
	
	/**
	 * 获取 所有分类id 需要传入叶子 栏目id
	 * @param unknown $child_id
	 */
	public function  getCategoryPosition($child_id){
	    $return  = array();
	    if(!$child_id){  return array();}
	    $child = $this->where(array('gc_id'=>$child_id))->find();
	    if(empty($child)){
	        return  array();
	    }
	    if($child['gc_parent_id'] > 0){
	        $parent =   $this->where(array('gc_id'=>$child['gc_parent_id']))->find();
	        if(empty($parent)){
	            return  array();
	        }
	        if($parent['gc_parent_id']>0){
	            $grand_father =   $this->where(array('gc_id'=>$parent['gc_parent_id']))->find();
	            if(empty($grand_father)){
	                return  array();
	            }
	            $return = array(
	                    'gc_id'=>$child_id,
	                    'gc_name'=>$child['gc_name'],
	                    'gc_id_1'=>$grand_father['gc_id'],
	                    'gc_name_1'=>$grand_father['gc_name'],
	                    'gc_id_2'=>$parent['gc_id'],
	                    'gc_name_2'=>$parent['gc_name'],
	                    'gc_id_3'=>$child['gc_id'],
	                    'gc_name_3'=>$child['gc_name'],
	            );
	            return  $return;
	        }else{
	            $return = array(
	                    'gc_id'=>$child_id,
	                    'gc_name'=>$child['gc_name'],
	                    'gc_id_1'=>$parent['gc_id'],
	                    'gc_name_1'=>$parent['gc_name'],
	                    'gc_id_2'=>$child['gc_id'],
	                    'gc_name_2'=>$child['gc_name'],
	                    'gc_id_3'=>0,
	                    'gc_name_3'=>'',
	            );
	            return  $return;
	        }              
	    }else{
	        $return = array(
	                'gc_id'=>$child_id,
	                'gc_name'=>$child['gc_name'],
	                'gc_id_1'=>$child['gc_id'],
	                'gc_name_1'=>$child['gc_name'],
	                'gc_id_2'=>0,
	                'gc_name_2'=>'',
	                'gc_id_3'=>0,
	                'gc_name_3'=>'',
	        );
	        return  $return;
	    }
	    
	}
	public function  editCategory($data=array(),$condition=array()){
	    if(empty($data) || empty($condition)){
	        return false;
	    }
	    $data['update_admin_id'] =  $_SESSION['admin_user']['uid'];
	    $data['update_time'] = time();
	    $result =   $this->where($condition)->save($data);
	    if($result){
	        $this->reCachAllCategoryTree();//更改分类缓存
	    }
	    return  $result;
	}
	/* 单条删除 */
	public function deleteCategory($gc_id) {
		$result = $this->delete($gc_id);
		if($result) {
			$this->reCachAllCategoryTree();//更改分类缓存
		}
		return  $result;
	}
	/**
	 * 获取单条分类信息
	 */
	public function getCategory($gc_id){
        $category =  $this->where(array('gc_id'=>$gc_id))->find();
        if(empty($category)){
            return  array();
        }
        return $category;  
	}

	/**
	 * 获取某分类下的子分类信息
	 */
	public function getChildCategory($gc_id) {
		$all_category =  $this->where(array('gc_parent_id'=>$gc_id))->select();
		if(empty($all_category)){
            return  array();
        }
        return $all_category;
	}
	
	
	/**
	 * 获取所有的商品分类 树状  三维数组
	 * @return multitype:
	 */
	public function  getAllCategoryTree(){
		$key = CacheHelper::getCachePre('get_all_category');
		$all_category =  S($key);
		if(false === $all_category) {
			$all_category =  $this->order('listorder desc')->select();
			$all_category  = $this->unlimitedForLayer($all_category,0);
		   S($key,$all_category,1800);
		}
        return $all_category;  
	}
	
	/**
	 * 只获取所允许显示的商品分类 树状  三维数组
	 * @return multitype:
	 */
	public function  getShowCategoryTree(){
		$key = CacheHelper::getCachePre('get_show_category');
		$all_category =  S($key);
		if(false === $all_category) {
			$all_category =  $this->where(array('is_show'=>1))->order('listorder desc')->select();
			$all_category  = $this->unlimitedForLayer($all_category,0);
		   S($key,$all_category,1800);
		}
	    return $all_category;
	}
	
	public function  reCachAllCategoryTree(){
	    //所有分类缓存
	    $all_category =  array();
		$show_key = CacheHelper::getCachePre('get_show_category');
		$all_key = CacheHelper::getCachePre('get_all_category');
		 
	    $all_category =  $this->order('listorder desc')->select();
	    if(!empty($all_category)){
	        $all_category  = $this->unlimitedForLayer($all_category,0);
	    }
	     S($all_key,$all_category,1800);
	    
	    //显示的分类的缓存
	    $all_category =  array();
	    $all_category =  $this->where(array('is_show'=>1))->order('listorder desc')->select();
	    if(!empty($all_category)){
	        $all_category  = $this->unlimitedForLayer($all_category,0);
	    }
		 S($show_key,$all_category,1800);
	    return $all_category;
	}
	
    /**
     * 无限极分类把一维数组搞成多维数组
     * @param unknown $cate
     * @param string $name
     * @param number $pid
     * @return multitype:multitype:unknown
     */	
	public function unlimitedForLayer ($cate,$parent_id = 0) {
	    $arr = array();
	    foreach ($cate as $v) {
	        if ($v['gc_parent_id'] == $parent_id) {
	            $v['child'] = self::unlimitedForLayer($cate,$v['gc_id']);
	            $arr[] = $v;
	        }
	    }
	    return $arr;
	}
	
	
}
?>