<?php
namespace Admin\Controller;
use Think\Controller;


class SearchController extends BaseController {

    /*
     * 搜索商品
     * @param array $condition 搜索条件
     * @param int   $start     起始位置
     * @param int   $limit     搜索条数
     * @return array
     * * */
    public function searchGoods(){
        $condition = array();
        if($content = I("content")){
            $condition['goods_name'] = array('like',"%{$content}%");
        }
        $condition['goods_state'] = 1;
        $perpage = 10;
        $model = M('goods_common');
        $count = $model ->where($condition)->count();
        /*$count = $model
            ->alias('gc')
            ->where($condition)
            ->join('pre_goods as g ON g.goods_common_id = gc.goods_common_id')
            ->count();*/
        $page = $this -> page($count,$perpage);
        //这里获取当前页码
        $currentpage = I('p') - 1;
        $goodscommon = $model
            //->join('pre_goods as g ON g.goods_common_id = gc.goods_common_id')
            ->where($condition)
            ->limit(($perpage * $currentpage).','.$page->listRows)
            ->select();

        /*@TODO::这里不应该循环查。可是我很懒！！！！！*/
        // foreach($goodscommon as &$g){
        //     $field_info = M('goods') -> where(array('goods_common_id'=>$g['goods_common_id'])) -> limit(1) -> field('goods_price,goods_id,goods_image') -> find();
        //     $g['goods_price'] = $field_info['goods_price'] ? number_format($field_info['price'],2) : 0.00;
        //     $g['goods_id'] = $field_info['goods_id'] ? $field_info['goods_id'] : 0;
        //     $g['goods_image'] = $field_info['goods_image'] ? $field_info['goods_image'] : '';
        // }
        if($goodscommon){
            $this -> ajaxReturn(array('status'=>1,'info'=>$model->getLastSql(),'data'=>array('goods'=>$goodscommon,'count'=>$count,'page'=>$page->ajaxShow())));
        }else{
            $this -> ajaxReturn(array('status'=>0,'info'=>$model->getLastSql(),'data'=>array('goods'=>$goodscommon,'count'=>$count,'page'=>$page->ajaxShow())));    
        }
    }
	
	public function searchsku() {
        $condition = array();
		$content = I("content",'','htmlspecialchars');
		if($content) {
			!is_numeric($content) ?  $condition['goods_name'] = array('like',"%{$content}%") : $condition['goods_id'] =$content ;
		}

		$goods_id = I("goods_id",0,'intval');
		$goods_id && $condition['goods_id'] = $goods_id;
		$gc_id_1 = I("gc_id_1",0,'intval');
		$gc_id_1 && $condition['gc_id_1'] = $gc_id_1;
		$gc_id_2 = I("gc_id_2",0,'intval');
		$gc_id_2 && $condition['gc_id_2'] = $gc_id_2;
		$condition['goods_state'] = 1;
		
        $perpage = 21;
        $model = D('Goods');
		$count = $model ->where($condition)->count();
		$page = $this -> page($count,$perpage);
		$data = $model->searchGood($condition,$page);
		foreach($data as $k => $v) {
			$goods_common_id = $v['goods_common_id'];
			$images = $model->getGoodSImages($goods_common_id);
			$data[$k]['goods_images'] = $images;
		}
		$this -> ajaxReturn(array('status'=>0,'data'=>array('goods'=>$data,'count'=>$count,'page'=>$page->ajaxShow())));  
	}
	public function   searchspu(){
	    $condition = array();
	    $content = I("content",'','htmlspecialchars');
	    if($content) {
	        !is_numeric($content) ?  $condition['goods_name'] = array('like',"%{$content}%") : $condition['goods_common_id'] =$content ;
	    }
	    
	    $goods_id = I("goods_id",0,'intval');
	    $goods_id && $condition['goods_id'] = $goods_id;
	    $gc_id_1 = I("gc_id_1",0,'intval');
	    $gc_id_1 && $condition['gc_id_1'] = $gc_id_1;
	    $gc_id_2 = I("gc_id_2",0,'intval');
	    $gc_id_2 && $condition['gc_id_2'] = $gc_id_2;
	    $condition['goods_state'] = 1;
	    
	    $perpage = 21;
	    $model = M('goods_common');
	    $count = $model ->where($condition)->count();
	    $page = $this -> page($count,$perpage);
	    $data = $model->where($condition)
            ->limit($page->firstRow.','.$page->listRows)->select();
	    if(!empty($data)){
    	    foreach($data as $k => $v) {
    	        $data[$k]['goods_image'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['goods_image'];
    	        $data[$k]['goods_storage'] = M('goods')->where(array('goods_common_id'=>$v['goods_common_id']))->sum('goods_storage');
    	        $data[$k]['goods_id'] = 0;
    	        unset($data[$k]['goods_detail']);
    	        $images = D('Goods')->getGoodSImages($v['goods_common_id']);
    	        $data[$k]['goods_images'] = $images;
    	    }
	    }else{
	        $data  = array();
	    }
	    $this -> ajaxReturn(array('status'=>0,'data'=>array('goods'=>$data,'count'=>$count,'page'=>$page->ajaxShow())));
	}

}