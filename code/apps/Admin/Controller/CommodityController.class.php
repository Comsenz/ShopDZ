<?php
namespace Admin\Controller;
use Think\Controller;
class CommodityController extends BaseController {
    private  $goods_category_model;
    private  $goods_model;
    public  function __construct(){
         parent::__construct();   
         $this->goods_category_model = D('GoodsCategory');
         $this->goods_model = D('Goods');
    }
    
    public function  create_all_goods_qcode(){
        $dir =  C('TMPL_PARSE_STRING.__UPLOAD__').'goods_qcode/';
        $goods_common_ids =M('goods_common')->field('goods_common_id')->limit(false)->select();
        if(!empty($goods_common_ids)){
            foreach($goods_common_ids as $v){
                $path = $dir.($v['goods_common_id']%100).'/'.$v['goods_common_id'].'.png';
                if(!is_dir($dir.($v['goods_common_id']%100).'/')){
                    createFolder($dir.($v['goods_common_id']%100));
                }
                $url =  C('WAP_URL').'goods_detail.html?id='.$v['goods_common_id']; 
                \Common\Helper\QRcode::png($url,$path);
                echo $path,'<br>',$url,'<br>';   
            }
        }
    }
    
    public function goods_qcode($goods_common_id =  0){
        $goods_common_id = intval($goods_common_id);
        $dir =  C('TMPL_PARSE_STRING.__UPLOAD__').'goods_qcode/';
        $path = $dir.($goods_common_id%100).'/'.$goods_common_id.'.png';
        if(!is_dir($dir.($goods_common_id%100).'/')){
            createFolder($dir.($goods_common_id%100));
        }
        $url =  C('WAP_URL').'goods_detail.html?id='.$goods_common_id;
        \Common\Helper\QRcode::png($url,$path);
    }

    function isImage($filename){
	    $types = '.gif|.jpeg|.jpg|.png|.bmp';//定义检查的图片类型
	    $ext = substr($filename,strrpos($filename, '.')); 
        return stripos($types,$ext);
	}


    //读取商品分类图库路径图片
    function get_filetree($path, $dir){

    	$tree = array();
    	if (is_dir($path)){
			if ($dh = opendir($path)){
				while (($file = readdir($dh))!= false){
					//文件名的全路径 包含文件名
					if($file == '.' || $file == '..') continue;
					if(is_dir($path.'/'.$file)){
						$tree = array_merge($tree,$this->get_filetree($path.'/'.$file, $dir.'/'.$file));
					}
					else{
						if($this->isImage($file)!==false){
							$tree[] = $dir.'/'.$file;
						}
					}
				}
				closedir($dh);
			}
		}
		return $tree;
	} 



    /**
    * 读取商品分类图库
    */
    public function category_icon(){

    	layout(false);
    	$type = I('post.type');
    	$files = array();

    	$icon_types = array(
    		'all'		=> '全部',
    		'home' 		=> '居家',
    		'diet' 		=> '饮食',
    		'bedding' 	=> '床品',
    		'personal' 	=> '个护',
    		'baby' 		=> '母婴',
    		'travel' 	=> '出行',
    		'kitchen' 	=> '厨房',
    		'outfit' 	=> '服饰内衣',
    		'bags'		=> '箱包',
    		'book'		=> '图书',
    		'phone'		=> '手机',
    		'luxury_goods'	=> '奢侈品',
    		'gift'		=> '礼品',
    		'wedding'	=> '婚庆',
    		'musical'	=> '玩具乐器',
    		'office_equipment'	=> '办公设备',
    		'sports_health'		=> '运动健康',
    		'fresh'		=> '生鲜',
    		'living_appliances'	=> '生活电器',
    		'kitchen_electric_big'	=> '厨卫大电',
    		'kitchen_electric_small'=> '厨房小电',
    		'large_house_electri'	=> '大家电',
    		'computer_office'=> '电脑办公',
    		'vehicle'	=> '整车',
    		'shoes'		=> '鞋靴',
    		'clocks'	=> '钟表',
    		'automobile'=> '汽车用品',
    		'Jewellery'	=> '珠宝',
    		'stationery'	=> '文具耗材',
    		'photography'	=> '摄影摄像',
    		'digital'	=> '数码配件',
    		'intelligent_equipment'	=> '智能设备',
    		'video'	=> '影音娱乐',
    	);
		$this->assign('icon_types',$icon_types);

    	if($icon_types[$type]) {
    		$type = ($type == 'all') ? '': '/'.$type;
	    	//$path = RUNTIME_PATH.'Attach/CategoryIcon'.$type;
        	$path =  C('TMPL_PARSE_STRING.__CATEGORY_ICON__').$type;
        	$dir = 'CategoryIcon'.$type;

	    	if (is_dir($path)){
	    		$files = $this->get_filetree($path, $dir);
	    		//print_r($files);exit();
	    	}
			$this->assign('category_iconfiles',$files);
    	}
    	
    	$this->display('iconAlert');
    }
    
    /**
     * 商品分类列表页
     */
    public function category(){
        $no_child =  I('no_child',0,'intval');
        //页面小导航
        $small_nav = $this->nav_menu('category');
        $this->assign('small_nav',$small_nav);
        if($no_child){
            $this->assign('small_nav_key','只看当层');
            $category_list = $this->goods_category_model->where(array('gc_parent_id'=>I('gc_parent_id',0,'intval')))->order('listorder desc')->select();
        }else{
            $category_list = $this->goods_category_model->getAllCategoryTree();
            $this->assign('small_nav_key','所有分类');
        }
        $this->assign('no_child',$no_child);
        $this->assign('category_list',$category_list);
        $this->display('category');
    }
    /**
     * 商品分类添加
     */
    public function category_add(){
        if(!empty($_POST)){
            $category = array();
            $category['gc_name']  = I('gc_name','','htmlspecialchars,addslashes,trim');
            $category['gc_parent_id']  = I('gc_parent_id',0,'intval');
            $category['desc']  = I('desc','','htmlspecialchars,addslashes,trim');
            $category['is_show']  = I('is_show','1','intval');
            $category['listorder']  = I('listorder',0,'intval');
            $category['add_admin_id'] = $_SESSION['admin_user']['uid'];
            $category['add_time'] =  time();
            $category['gc_image']  = I('gc_image','','trim,addslashes,htmlspecialchars,trim');
            $parent = array();
            if($category['gc_parent_id'] > 0){
                $parent = $this->goods_category_model->where(array('gc_id'=>$category['gc_parent_id']))->find();
                if(empty($parent)){
                    $this->error('您选择的上级分类不存在');
                }
                if($parent['gc_level'] > 1){
                    $this->error('当前分类下不允许再添加子分类');
                }
                $category['gc_level'] = $parent['gc_level'] + 1;
            }
            if(!$category['gc_name']){
                $this->error('分类名称不允许为空');
            }
            if($category['gc_parent_id']>0){
                if(!$category['gc_image']){
                    $this->error('分类图片不允许为空');
                }
            }
            $category_name_check =    $this->goods_category_model->where(array('gc_parent_id'=>$category['gc_parent_id'],'gc_name'=>$category['gc_name']))->find();
            if(!empty($category_name_check)){
                $this->error('分类名称重复');
            }
            $new_gc_id =  $this->goods_category_model->addCategory($category);
            if($new_gc_id){
                if(!empty($parent) && ($parent['is_leaf'] ==1) ){
                    $this->goods_category_model->editCategory(array('is_leaf'=>0,'update_mark'=>'下面增加子分类被更改'),array('gc_id'=>$parent['gc_id']));
                }
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($category,true),'action'=>'商品分类添加','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                $this->success('添加成功',U('Commodity/category'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $parent = $this->goods_category_model->getAllCategoryTree();
            $this->assign('parent',$parent);
            $this->display();
        }
    }
    

     public function category_edit(){
        $gc_id = I('gc_id',0,'intval');
        if(!empty($_POST)){
            $category = array();
            $category['gc_name']  = I('gc_name','','htmlspecialchars,addslashes');
            if(mb_strlen($category['gc_name'],"utf-8") > 10){
                $category['gc_name'] = mb_substr($category['gc_name'],0,15,"utf-8");
            }
            $category['gc_parent_id']  = I('gc_parent_id',0,'intval');
            $category['desc']  = I('desc','','htmlspecialchars,addslashes');
            $category['is_show']  = I('is_show');
            $category['listorder']  = I('listorder',0,'intval');
            $category['add_admin_id'] = $_SESSION['admin_user']['uid'];
            $category['add_time'] =  time();
            $category['gc_image']  = I('gc_image','','trim,addslashes,htmlspecialchars');

            $parent = array();
            if($category['gc_parent_id'] > 0){
                $parent = $this->goods_category_model->where(array('gc_id'=>$category['gc_parent_id']))->find();
                if(empty($parent)){
                    $this->error('您选择的上级分类不存在');
                }
                if($parent['gc_level'] > 1){
                    $this->error('当前分类下不允许再添加子分类');
                }
                $category['gc_level'] = $parent['gc_level'] + 1;
            }
            if(!$category['gc_name']){
                $this->error('分类名称不允许为空');
            }
            if($category['gc_parent_id']>0){
                if(!$category['gc_image']){
                    $this->error('分类图片不允许为空');
                }
            }
            $category_name_check =    $this->goods_category_model->where(array('gc_parent_id'=>$category['gc_parent_id'],'gc_name'=>$category['gc_name']))->find();
            if(!empty($category_name_check) && $category_name_check['gc_id'] != $gc_id){
                $this->error('分类名称重复');
            }
            $new_gc_id =  $this->goods_category_model->editCategory($category,array('gc_id'=> $gc_id));

            if($new_gc_id){
                if(!empty($parent) && ($parent['is_leaf'] ==1) ){
                    $this->goods_category_model->editCategory(array('is_leaf'=>0,'update_mark'=>'下面增加子分类被更改'),array('gc_id'=>$parent['gc_id']));
                }
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($category,true),'action'=>'编辑商品分类','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                $this->success('编辑成功',U('Commodity/category'));
            }else{
                $this->error('编辑失败');
            }
        }else{
            $parent = $this->goods_category_model->getAllCategoryTree();
            $this->assign('parent',$parent);
            $category_info = $this->goods_category_model->getCategory($gc_id);
            $this->assign('category_info',$category_info);
            $this->display();
        }
    }
    
    public function category_del() {
        $gc_id = I('gc_id',0,'intval');
        $data['status']  = 1;
        $child_cgory = $this->goods_category_model->getChildCategory($gc_id);
        if(count($child_cgory)) {
            $this->error('分类下有子分类，不能删除');
        }
        $goods_count_where = "gc_id=$gc_id or gc_id_1=$gc_id  or  gc_id_2=$gc_id";
        $goods_count = M('goods_common')->where($goods_count_where)->count();
        if($goods_count){
            $this->error('该分类下有商品不允许删除');
        }
        $res = $this->goods_category_model->deleteCategory($gc_id);
        if(!$res) {
            $this->error('数据执行失败，请重新操作。');
        }
        \Common\Helper\LogHelper::adminLog(array('content'=>"gc_id=".$gc_id,'action'=>'删除商品分类','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
        $this->success('删除成功');
    }

    
    /**
     * 全部商品列表
     */
    public function goods_list(){
        //页面小导航
        $small_nav = $this->nav_menu('goods');
        $this->assign('small_nav',$small_nav);
       
        
        $goods_common_model =  M('goods_common');
        $where = array();
        $min_date = $max_date =  0;
        if($_GET['min_date']){
            $min_date =  strtotime(($_GET['min_date']).' 00:00:00');
        }
        if($_GET['max_date']){
            $max_date =  strtotime(($_GET['max_date']).' 23:59:59');
        }
        
        if($min_date && !$max_date){
            $where['add_time'] = array('egt',$min_date);    
        }
        
        if($max_date && !$min_date){
            $where['add_time'] = array('elt',$max_date);
        }
        
        if($min_date&&$max_date){
            $where['add_time'] = array('BETWEEN',array($min_date,$max_date));
        }
        $field  = I('field','','trim,addslashes,htmlspecialchars');
        if(in_array($field, array('goods_common_id','goods_name','goods_barcode'))){
            $q  = I('q','','trim,addslashes,htmlspecialchars');
            if($q){
                $where[$field] =   array('like','%'.$q.'%');
            }
        }
        $goods_state =   I('goods_state','online','trim,addslashes,htmlspecialchars');
        if($goods_state == 'online'){
            $where['goods_state'] = 1;
            $this->assign('small_nav_key','上架中的商品');
        }else if($goods_state == 'offline'){
            $where['goods_state'] = 0;
            $this->assign('small_nav_key','仓库中的商品');
        }else if($goods_state == 'draft'){
            $where['goods_state'] = 3;
            $this->assign('small_nav_key','草稿箱');
        }else if($goods_state == 'recycle'){
            $where['goods_state'] = 4;
            $this->assign('small_nav_key','回收站');
        }else{
            $this->assign('small_nav_key','全部商品');
        }
        
        $count = $goods_common_model->where($where)->count();
        
        
        $page  = new \Common\Helper\PageHelper($count,10);
        $lists = $goods_common_model->field('*')->where($where)->limit($page->firstRow.','.$page->listRows)->order('goods_common_id desc')->select();
        
        
        if(!empty($lists)){
           $cat_ids = array();
           foreach($lists as $v){
               $cat_ids[] = $v['gc_id_1'];
               $cat_ids[] = $v['gc_id_2'];
           }
           $cat_ids  = array_unique($cat_ids);
           if(!empty($cat_ids)){
               $temp_category_names = M('goods_category')->field('gc_id,gc_name')->where(array('gc_id'=>array('in',$cat_ids)))->select();
           }
           $category_names  = array();
           if(!empty($temp_category_names)){
               foreach($temp_category_names as $v){
                   $category_names[$v['gc_id']]  = $v['gc_name'];
               }
           }
           $this->assign('category_names',$category_names);
        }
        $status =  array(
                0=>'下架',
                1=>'上架',
                3=>'草稿',
                4=>'回收站',
                
        );
        if(!empty($lists)){
            foreach($lists as $k=>$v){
                $lists[$k]['gc_name'] = M('goods_category')->where(array('gc_id'=>$v['gc_id']))->getField('gc_name');
                $lists[$k]['goods_state_name'] =  $status[$v['goods_state']];
                $lists[$k]['goods_storage'] =  intval(M('goods')->where(array('goods_common_id'=>$v['goods_common_id']))->sum('goods_storage'));
            }
        }
      
        $this->assign('page',$page->show());
        $this->assign('lists',$lists);
        $category_list = $this->goods_category_model->getShowCategoryTree();
        $this->assign('category_list',$category_list);
        $this->display('goods_list');
    }
    
    /**
     * sku 列表
     */ 
    public function sku_list(){
        $goods_common_id =  I('goods_common_id',0,'intval');
        $goods_common =   M('goods_common')->where(array('goods_common_id'=>$goods_common_id))->find();
        $goods_list =  M('goods')->where(array('goods_common_id'=>$goods_common_id))->select();
        $spec_common =    unserialize($goods_common['spec']);
        $spec_ids =  array_keys($spec_common);
        if(!empty($spec_ids)){
            $spec_list  =  M('spec')->field('spec_id,spec_name')->where(array('spec_id'=>array('in',$spec_ids)))->select();
            $spec_list =  key_convert('spec_id', $spec_list);
        }
        $spec_value_id_list = array();
        foreach($spec_common as $spec){
            foreach($spec as $v){
                $spec_value_id_list[] = $v;
            }
        }
        $temp_spec_value_list = $spec_value_list  =  array();
        if(!empty($spec_value_id_list)){
            $temp_spec_value_list = M('spec_value')->where(array('spec_value_id'=>array('in',$spec_value_id_list)))->select();
            if(!empty($temp_spec_value_list)){
                foreach($temp_spec_value_list as $v){
                    $spec_value_list[$v['spec_value_id']] = $v['spec_value'];
                }
            }
        }
        if(!empty($goods_list)){
            foreach($goods_list as $k=>$v){
                $spec =   unserialize($v['goods_spec']);
                $new_spec = array();
                foreach ($spec as $spec_id => $spec_value_id){
                    $new_spec[$spec_list[$spec_id]['spec_name']]  = $spec_value_list[$spec_value_id];
                }
                $goods_list[$k]['spec'] = $new_spec;
            }
        }
        layout('null_layout');
        $this->assign('sku_list',$goods_list);
        $this->display('sku_list');
    }
    
    /**
     * 规格列表
     */
    public function spec(){
        //页面小导航
        $small_nav = $this->nav_menu('spec');
        $this->assign('small_nav_key','规格');
        $this->assign('small_nav',$small_nav);
        $where = array();
        $field  = I('field','','trim,addslashes,htmlspecialchars');
        if(in_array($field, array('spec_id','spec_name'))){
            $q  = I('q','','trim,addslashes,htmlspecialchars');
            if($q){
                $where[$field] =   array('like','%'.$q.'%');
            }
        }
        $count = M('spec')->where($where)->count();
        $page  = new \Common\Helper\PageHelper($count,20);
        $lists = M('spec')->where($where)->order('spec_id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('lists',$lists);
        $this->assign('page',$page->show());
        $this->display('spec');
    }
    
    
    /**
     * 添加规格值
     */
    public function spec_add(){
        if(!empty($_POST)){
            $spec_name = I('spec_name','','trim,htmlspecialchars,addslashes');
            $spec_value = I('spec_value','','trim,htmlspecialchars,addslashes');
            if(!$spec_name){
                $this->error('规格名称不允许为空');
            }
            if(!$spec_value){
                $this->error('规格值不允许为空');
            }
            $temp =  explode(' ', $spec_value);
            $spec_values = array();
            foreach($temp as $v){
                if($v){
                    $spec_values[]  = $v;
                }
            }
            if(empty($spec_values)){
                $this->error('规格值不允许为空');
            }
            $model  = M();
            $spec_values  =  array_unique($spec_values);
            $spec_name_check  =  M('spec')->master(true)->where(array('spec_name'=>$spec_name))->find();
            if(!empty($spec_name_check)){
                $this->error('规格名称重复');
            }
            $model->startTrans();
            $spec_id = M('spec')->master(true)->add(array('spec_name'=>$spec_name));
            if($spec_id){
                foreach($spec_values as $v){
                    $insert  =  array();
                    $insert_id =  0;
                    $insert['spec_id']  = $spec_id;
                    if(mb_strlen($v,"utf-8") > 15){
                        $name = mb_substr($v,0,15,"utf-8");
                    }else{
                        $name = $v;
                    }
                    $insert['spec_value']  = $name;
                    $insert_id = M('spec_value')->master(true)->add($insert);
                    if(!$insert_id){
                        $model->rollback();
                        $this->error('添加失败');
                    }
                }
                $model->commit();
                \Common\Helper\LogHelper::adminLog(array('content'=>$spec_name,'action'=>'规格添加','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                $this->success('操作成功',U('Commodity/spec'));
            }else{
                $model->rollback();
                $this->error('添加失败');
            }    
        }else{
            $this->display('spec_add');
        }    
    }
    
    /**
     * 规格编辑
     */
    public function spec_edit(){
        $spec_id = I('spec_id',0,'intval');
        if(!$spec_id){
            $this->error('参数错误',U('Commodity/spec'));
        }
        $spec_info =  M('spec')->getby_spec_id($spec_id);
        if(empty($spec_info)){
            $this->error('规格不存在',U('Commodity/spec'));
        }   
        if(!empty($_POST)){
            $spec_name = I('spec_name','','trim,htmlspecialchars,addslashes');
            if(!$spec_name){
                $this->error('规格名称不允许为空');
            }
            $spec_name_check  =  M('spec')->where(array('spec_name'=>$spec_name))->getField('spec_id');
            if($spec_name_check && $spec_name_check!=$spec_id){
                $this->error('规格名称');
            }
            $result = M('spec')->where(array('spec_id'=>$spec_id))->save(array('spec_name'=>$spec_name));
            if($result!==false){
                $log_data  = array(
                        'spec_id'=>$spec_id,
                        'spec_name'=>$spec_name,
                );
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($log_data,true),'action'=>'规格编辑','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                $this->success('操作成功',U('Commodity/spec'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $this->assign('spec_info',$spec_info);
            $this->display('spec_edit');
        }
    }
    
    /**
     * 规格删除
     */
    public function spec_delete(){
        $spec_id = I('spec_id',0,'intval');
        if(!$spec_id){
            $this->error('参数错误',U('Commodity/spec'));
        }
        $model = M();
        //判断下面是否有商品
        $commond_ids = M('goods_spec')->master(true)->field('distinct(goods_common_id)')->where(array('spec_id'=>$spec_id))->select();
        if(!empty($commond_ids)){
            $temp = array();
            foreach($commond_ids as $v){
                $temp[]  = $v['goods_common_id'];
            }
            $idstr =  implode(',', $temp);
            $this->error('该规格正在被'.$idstr.'使用,请先删除或编辑商品',U('Commodity/spec'));
        }
        $model->startTrans();
        $spec_del = M('spec')->master(true)->where(array('spec_id'=>$spec_id,'bult_in'=>0))->delete();
        if(!$spec_del){
            $this->error('删除失败',U('Commodity/spec'));
        }
        $spec_value_del  = M('spec_value')->master(true)->where(array('spec_id'=>$spec_id))->delete();
        if($spec_value_del===false){
            $model->rollback();
            $this->error('删除失败',U('Commodity/spec'));
        }
        $model->commit();
        $log_data  = array(
                'spec_id'=>$spec_id,
        );
        \Common\Helper\LogHelper::adminLog(array('content'=>var_export($log_data,true),'action'=>'规格删除','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
        $this->success('删除成功',U('Commodity/spec'));
    }
    
    
    /**
     * 规格值管理
     */
    public function spec_value(){
        //页面小导航
        $small_nav = $this->nav_menu('spec');
        $this->assign('small_nav_key','规格值');
        $this->assign('small_nav',$small_nav);
        $temp_spec_list =  M('spec')->field('spec_id,spec_name')->limit(false)->select();
        $spec_list = array();
        foreach($temp_spec_list as  $v){
            $spec_list[$v['spec_id']] = $v['spec_name'];
        }
        $where = array();
        $spec_id =     I('spec_id',0,'intval');
        $q  = I('q','','trim,addslashes,htmlspecialchars');
        if($q){
            if($_GET['field']=='spec_name'){
                $where['spec_name'] =   array('like','%'.$q.'%');
            }else{
                $where['spec_value'] =   array('like','%'.$q.'%');
            }
            
        }
        $spec_table   = C('DB_PREFIX').'spec';
        $spec_value_table =  C('DB_PREFIX').'spec_value';
        if($spec_id){
            $where[ $spec_value_table.'.spec_id'] = $spec_id;
        }
        $count = M('spec_value')->where($where)->count();
        $page  = new \Common\Helper\PageHelper($count,20);
        
        
        $lists = M('spec_value')->field("$spec_value_table.*,$spec_table.spec_name")->join("LEFT JOIN $spec_table  ON    $spec_value_table.spec_id = $spec_table.spec_id")->where($where)->limit($page->firstRow.','.$page->listRows)->order('spec_id desc,spec_value_id desc')->select();
        $this->assign('spec_list',$spec_list);
        $this->assign('lists',$lists);
        $this->assign('page',$page->show());
        $this->display('spec_value');
    }
    
    
    /**
     * 规格值添加   
     */
    public function spec_value_add_form(){
        if(!empty($_POST)){
            $spec_value = I('spec_value','','trim,htmlspecialchars,addslashes');
            $spec_id   =  I('spec_id',0,'intval');
            if(!$spec_value){
                $this->error('规格值不允许为空');
            }
            if(!$spec_id){
               $this->error('参数错误');
            }
            
            $spec_value_check  =  M('spec_value')->where(array('spec_value'=>$spec_value,'spec_id'=>$spec_id))->find();
            if(!empty($spec_value_check)){
                $this->error('规格值重复');
            }
            $spec_value_id = M('spec_value')->add(array('spec_value'=>$spec_value,'spec_id'=>$spec_id));
            if($spec_id){
                $log_data = array(
                        'spec_value'=>$spec_value,
                );
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($log_data,true),'action'=>'规格值添加','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                
                if (IS_AJAX){
                    $data = array(
                        'spec_id'=>$spec_id,
                        'spec_value_id'=>$spec_value_id,
                        'spec_value'=>$spec_value,
                    );
                    $this->success($data,U('Commodity/spec_value',array('spec_id'=>$spec_id)));
                } else {
                    $this->success('添加成功',U('Commodity/spec_value',array('spec_id'=>$spec_id)));
                }
                
            }else{
                $this->error('添加失败');
            }    
        }else{
            $temp_spec_list =  M('spec')->field('spec_id,spec_name')->limit(false)->select();
            $spec_list = array();
            foreach($temp_spec_list as  $v){
                $spec_list[$v['spec_id']] = $v['spec_name'];
            }
            $this->assign('spec_list',$spec_list);
            $this->display('spec_value_add_form'); 
        }   
    }
    
    public function spec_value_edit(){
        $spec_value_id = I('spec_value_id',0,'intval');
        if(!$spec_value_id){
            $this->error('参数错误',U('Commodity/spec_value'));
        }
        $spec_value_info =  M('spec_value')->getby_spec_value_id($spec_value_id);
        if(empty($spec_value_info)){
            $this->error('规格不存在',U('Commodity/spec_value'));
        }   
        if(!empty($_POST)){
            $spec_value = I('spec_value','','trim,htmlspecialchars,addslashes');
            if(!$spec_value){
                 $this->error('规格值不允许为空');
            }
            $spec_value_check  =  M('spec_value')->where(array('spec_value'=>$spec_value,'spec_id'=>$spec_value_info['spec_id']))->getField('spec_value_id');
            if($spec_value_check && $spec_value_check!=$spec_value_id){
                 $this->error('该规格值已存在');
            }
            $result = M('spec_value')->where(array('spec_value_id'=>$spec_value_id))->save(array('spec_value'=>$spec_value));
            if($result!==false){
                $log_data = array(
                        'spec_value_id'=>$spec_value_id,
                        'spec_value'=>$spec_value,
                );
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($log_data,true),'action'=>'规格值编辑','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                $this->success('编辑成功',U('Commodity/spec_value',array('spec_id'=>$_GET['spec_id'])));
            }else{
                 $this->error('修改失败');
            }
        }else{
            $this->assign('spec_value_info',$spec_value_info);
            $this->display('spec_value_edit');
        }
    }
   
    /**
     * 规格值删除
     */
    public function spec_value_delete(){
        $spec_value_id = I('spec_value_id',0,'intval');
        if(!$spec_value_id){
            $this->error('参数错误',U('Commodity/spec_value'));
        }
        $spec_id  = M('spec_value')->where(array('spec_value_id'=>$spec_value_id))->getField('spec_id');
        if(!$spec_id){
            $this->error('参数错误',U('Commodity/spec_value'));
        }
        $count = M('spec_value')->where(array('spec_id'=>$spec_id))->count();
        if($count<2){
            $this->error('每个规格至少要保留一个规格',U('Commodity/spec_value'));
        }
        
        //判断下面是否有商品
        $commond_ids = M('goods_spec')->field('distinct(goods_common_id)')->where(array('spec_value_id'=>$spec_value_id))->select();
        if(!empty($commond_ids)){
            $temp = array();
            foreach($commond_ids as $v){
                $temp[]  = $v['goods_common_id'];
            }
            $idstr =  implode(',', $temp);
            $this->error('该规格值正在被'.$idstr.'使用,请先删除或编辑商品',U('Commodity/spec'));
        }
        
        $spec_value_del  = M('spec_value')->where(array('spec_value_id'=>$spec_value_id))->delete();
        if(!$spec_value_del){
            $this->error('删除失败',U('Commodity/spec_value'));
        }
        $log_data = array(
                'spec_value_id'=>$spec_value_id,
        );
        \Common\Helper\LogHelper::adminLog(array('content'=>var_export($log_data,true),'action'=>'规格值删除','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
        $this->success('删除成功',U('Commodity/spec_value'));
    }
    
    /**
     * 商品快速发布
     */
    public function goods_quick_add(){
        $default_spec_value =   M('spec_value')->where(array('spec_value_id'=>3))->find();
        if(empty($default_spec_value)){
            $this->error('默认规格值被删除',U('Commodity/goods_quick_add'));
        } 
        if(!empty($_POST)){
            $new_goods_common = array();
            $name = I('goods_name','','trim,addslashes,htmlspecialchars');
            if(mb_strlen($name,"utf-8") > 20){
                $name = mb_substr($name,0,20,"utf-8");
            }
            $new_goods_common['goods_name']  = $name;
            $new_goods_common['goods_jingle']  = I('goods_jingle','','trim,addslashes,htmlspecialchars');
            $new_goods_common['goods_detail']  = I('goods_detail','','trim,addslashes,htmlspecialchars');
            $new_goods_common['goods_image']  = I('goods_image_1','','trim,addslashes,htmlspecialchars');
            $new_goods_common['goods_price']  = I('goods_price',0.00,'price_format');
            $new_goods_common['freight']  = I('freight',0.00,'price_format');
            $new_goods_common['gc_id']  = I('gc_id',0,'intval');
            if(!$new_goods_common['gc_id']){
                $this->error('请选择商品分类');
            }
            $gc_info = $this->goods_category_model->getCategoryPosition($new_goods_common['gc_id']);
            if(empty($gc_info)){
                $this->error('分类信息错误');
            }
            if($new_goods_common['goods_price']<=0){
                $this->error('商品价格错误');
            }
            if($new_goods_common['freight']<0){
                $this->error('商品运费金额错误');
            }
            $new_goods_common['gc_id_1'] = intval($gc_info['gc_id_1']);
            $new_goods_common['gc_id_2'] = intval($gc_info['gc_id_2']);
            $new_goods_common['gc_id_3'] = intval($gc_info['gc_id_3']);
            $new_goods_common['goods_state'] =  intval($_POST['goods_state']);
            if(!in_array($new_goods_common['goods_state'],array(0,1))){
                $new_goods_common['goods_state'] = 0;
            }
            //商品图片处理
            $image_list  = array();
            for($i = 1 ; $i<=5;$i++){
                $image = array();
                $image['image_url'] = I('goods_image_'.$i,'','trim,addslashes,htmlspecialchars');
                if($image['image_url']){
                    $image['listorder'] =   I('lister_order_'.$i,0,'intval,abs');
                    $image['is_default'] =  intval(I('is_default',1,'intval')==$i);
                    if($image['is_default']){
                        if($image['image_url']){
                            $new_goods_common['goods_image'] = $image['image_url'];
                        }else{
                            $this->error('商品默认主图不许为空');
                        }
                    }
                    $image_list[] = $image;
                }else{
                    continue;
                }
            }
            if(!$new_goods_common['goods_image']){
                $this->error('商品默认主图不许为空');
            }
            $post_spec_value =   I('default_spec_value','','trim,addslashes,htmlspecialchars');
            if(!$post_spec_value || $post_spec_value==$default_spec_value['spec_value']){
                $spec_value_id =  3;   
            }else{
                $spec_value_id  = intval(M('spec_value')->where(array('spec_id'=>3,'spec_value'=>$post_spec_value))->getField('spec_value_id'));
                if(!$spec_value_id){
                    $spec_value_id = M('spec_value')->add(array('spec_id'=>3,'spec_value'=>$post_spec_value));
                }
            }
            if(!$spec_value_id){
                $this->error('添加规格值出错');
            }
           
            $new_goods_common['spec'] = serialize(array(3=>array($spec_value_id)));
            $new_goods_common['add_time']  =  time();
            M()->startTrans();
            $new_goods_common_id = M('goods_common')->add($new_goods_common);
            if(!$new_goods_common_id){
                M()->rollback();
                $this->error('添加商品失败');
            }
            
            //goods表数据组织
            $goods_insert['goods_common_id']    =   $new_goods_common_id;
            $goods_insert['goods_name']    =   $new_goods_common['goods_name'];
            $goods_insert['spec_name']    =  M('spec_value')->where(array('spec_value_id'=>$spec_value_id))->getField('spec_value') ;
            $goods_insert['goods_state']    =  $new_goods_common['goods_state'];
            $goods_insert['gc_id']      =    $new_goods_common['gc_id'];
            $goods_insert['gc_id_1']    =    $new_goods_common['gc_id_1'] ;
            $goods_insert['gc_id_2']    =    $new_goods_common['gc_id_2'];
            $goods_insert['gc_id_3']    =    $new_goods_common['gc_id_3'];
            $goods_insert['goods_price']    = $new_goods_common['goods_price'] ;
            $goods_insert['goods_storage']    = abs(intval($_POST['goods_storage']));
            $goods_insert['goods_image']    =    $new_goods_common['goods_image'] ;
            $goods_insert['goods_barcode']    =  '' ;
            $goods_insert['add_time']    =  time() ;
            $goods_insert['update_time']    =   0 ;
            $goods_insert['spec_value_id_key']    =  $spec_value_id ;
            $goods_insert['goods_spec']    =  serialize(array('3'=>$spec_value_id)) ;
            $goods_insert['freight']    =   $new_goods_common['freight']   ;
            $goods_id = M('goods')->add($goods_insert);
            if(!$goods_id){
                M()->rollback();
                $this->error('添加商品失败');
            }
            if($new_goods_common_id){
                $this->goods_qcode($new_goods_common_id); //生成二维码
                foreach($image_list as $v){
                    $v['goods_common_id'] = $new_goods_common_id;
                    M('goods_images')->add($v);
                }
				M()->commit();
				unset($_POST['goods_detail']);
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'商品快速发布','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                $this->success('操作成功',U('Commodity/goods_add_step2',array('goods_common_id'=>$new_goods_common_id,'edit'=>$_GET['edit'])));
            
            }else{
                $this->error('添加失败');
            }
            
        }else{
            $small_nav = $this->nav_menu('goods');
            $this->assign('small_nav',$small_nav);
            $this->assign('small_nav_key','添加商品');
            $category_list = $this->goods_category_model->getShowCategoryTree();
            $this->assign('category_list',$category_list);
            $this->assign('default_spec_value',$default_spec_value);
            $this->display();
        }
    }
    
    public function goods_edit_step1(){
        $this->goods_add_step1();    
    }
    /**
     * 添加商品第一步    基本信息
     */
    public function goods_add_step1(){
        $goods_common_id =  I('goods_common_id',0,'intval');
        $goods_common  = array();
        if($goods_common_id){
            $goods_common  = M('goods_common')->where(array('goods_common_id'=>$goods_common_id))->find();
            if(empty($goods_common)){
                $this->error('参数错误',U('Commodity/goods_list',array('edit'=>$_GET['edit'])));
            }
            $images  =  M('goods_images')->where(array('goods_common_id'=>$goods_common_id))->order('is_default desc,listorder desc')->select();
            $this->assign('images',$images);  
        }
        if(!empty($_POST)){ 
            $new_goods_common = array();
            $name = I('goods_name','','trim,addslashes,htmlspecialchars');
            if(mb_strlen($name,"utf-8") > 20){;
                $name = mb_substr($name,0,20,"utf-8");
            }
            $new_goods_common['goods_name']  = $name;
            $new_goods_common['goods_jingle']  = I('goods_jingle','','trim,addslashes,htmlspecialchars');
            $new_goods_common['goods_detail']  = I('goods_detail','','trim,addslashes,htmlspecialchars');
            $new_goods_common['goods_image']  = I('goods_image_1','','trim,addslashes,htmlspecialchars');
            $new_goods_common['goods_price']  = I('goods_price',0.00,'price_format');
            $new_goods_common['gc_id']  = I('gc_id',0,'intval');
            $gc_info = $this->goods_category_model->getCategoryPosition($new_goods_common['gc_id']);
            if(!$new_goods_common['gc_id']){
                $this->error('请选择商品分类');
            }
            if(empty($gc_info)){
                $this->error('分类信息错误');
            }
            if($new_goods_common['goods_price']<=0){
                $this->error('商品价格错误');    
            }
            $new_goods_common['gc_id_1'] = intval($gc_info['gc_id_1']);
            $new_goods_common['gc_id_2'] = intval($gc_info['gc_id_2']);
            $new_goods_common['gc_id_3'] = intval($gc_info['gc_id_3']);
            $new_goods_common['goods_state'] =  3;
            //商品图片处理
            $image_list  = array();
            for($i = 1 ; $i<=5;$i++){
                $image = array();
                $image['image_url'] = I('goods_image_'.$i,'','trim,addslashes,htmlspecialchars');
                if($image['image_url']){
                    $image['listorder'] =   I('lister_order_'.$i,0,'intval,abs');
                    $image['is_default'] =  intval(I('is_default',1,'intval')==$i);
                    if($image['is_default']){
                        if($image['image_url']){
                            $new_goods_common['goods_image'] = $image['image_url'];
                        }else{ 
                            $this->error('商品默认主图不许为空');
                        }
                    }
                    $image_list[] = $image;    
                }else{
                    continue;
                }
            }
            if(!$new_goods_common['goods_image']){
                $this->error('商品默认主图不许为空');
            }
            if($goods_common_id){
                unset($new_goods_common['goods_state']); //编辑不更改商品状态 最后一步才更改
                $result =  $this->goods_model->updateGoodsCommonBase($new_goods_common,array('goods_common_id'=>$goods_common_id)); 
                if($result){
                    //更新图片 
                    M('goods_images')->where(array('goods_common_id'=>$goods_common_id))->delete();
                    foreach($image_list as $v){
                        $v['goods_common_id'] = $goods_common_id;
                        M('goods_images')->add($v);
                    }
                    $this->goods_model->delSpuCache($goods_common_id);
                    unset($_POST['goods_detail']);
                    \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'商品编辑第一步','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                    $this->success('操作成功',U('Commodity/goods_add_step2',array('goods_common_id'=>$goods_common_id,'edit'=>$_GET['edit'])));
                }else{
                    $this->error('操作失败');
                }
            }else{
                $new_goods_common['add_time']  =  time();
                $new_goods_common_id = M('goods_common')->add($new_goods_common);
                if($new_goods_common_id){
                    $this->goods_qcode($new_goods_common_id); //生成二维码
                    foreach($image_list as $v){
                        $v['goods_common_id'] = $new_goods_common_id;
                        M('goods_images')->add($v);
                    }
                    $this->goods_model->delSpuCache($new_goods_common_id);
                    unset($_POST['goods_detail']);
                    \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'商品添加第一步','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                    $this->success('操作成功',U('Commodity/goods_add_step2',array('goods_common_id'=>$new_goods_common_id,'edit'=>$_GET['edit'])));    
                }else{
                    $this->error('添加失败');
                }        
            }
        }else{ 
            $this->assign('goods_common',$goods_common);
            $small_nav = $this->nav_menu('goods');
            $this->assign('small_nav',$small_nav);
            $this->assign('small_nav_key','添加商品');
            $category_list = $this->goods_category_model->getShowCategoryTree();
            $this->assign('category_list',$category_list);
            $this->display('goods_add_step1');
        }
    }
    /**
     * 商品添加第二部    规格设置
     */
    public function goods_add_step2(){
        $goods_common_id =  I('goods_common_id',0,'intval');

        $goods_common  = array();
        if(!$goods_common_id){
            $this->error('参数错误',U('Commodity/goods_list'));
        }

        $goods_common  = M('goods_common')->master(true)->where(array('goods_common_id'=>$goods_common_id))->find();

        if(empty($goods_common)){
            $this->error('参数错误',U('Commodity/goods_list'));
        }
        //检测这个common_id下是否有商品
        $goods_check = M('goods')->master(true)->where(array('goods_common_id'=>$goods_common_id))->count();

        if($goods_check){

            if(!empty($_POST)){
                $this->error('参数错误',U('Commodity/goods_list'));
            }
            $this->goods_edit_step2();exit();
        }

        if(!empty($_POST)){
            $goods_common_spec  = $_POST['spec_value'];
            $this->goods_model->startTrans();
            if(empty($_POST['goods_list'])){
                $this->error('参数错误');    
            }
            $all_spec_value_ids = array();
            foreach($_POST['spec_value'] as $v){
                $all_spec_value_ids  = array_merge($all_spec_value_ids,$v);
            }
            $spec_value_list = M('spec_value')->master(true)->field('spec_value_id,spec_value,spec_id')->where(array('spec_value_id'=>array('in',$all_spec_value_ids)))->select();
            $spec_value_list  =  $this->key_convert('spec_value_id',$spec_value_list);
            foreach($_POST['goods_list'] as $idkey=>$v){
                $insert =  array();
                $insert['goods_common_id'] = $goods_common_id;   
                $goods_spec = array();
                $goods_spec_name =  '';
                $goods_spec_values_ids = array();
                $goods_spec_values_ids   = explode('_', $idkey);
                foreach($goods_common_spec as $spec_id=>$v2){
                    foreach($goods_spec_values_ids as $spec_value_id){
                        if(in_array($spec_value_id, $v2)){
                            $goods_spec[$spec_id] = $spec_value_id;
                            $goods_spec_name.= '、'.$spec_value_list[$spec_value_id]['spec_value'];
                        }
                    }    
                }
                $insert['goods_name'] = $goods_common['goods_name'];
                $insert['spec_name']   = trim($goods_spec_name,'、');
                $insert['goods_state'] = $goods_common['goods_state'];
                $insert['gc_id'] = $goods_common['gc_id'];
                $insert['gc_id_1'] = $goods_common['gc_id_1'];
                $insert['gc_id_2'] = $goods_common['gc_id_2'];
                $insert['gc_id_3'] = $goods_common['gc_id_3'];
                $insert['goods_price']  = price_format($v['goods_price']);
                $insert['goods_storage'] = intval($v['goods_storage']);
                $insert['goods_barcode']  = trim(htmlspecialchars(addslashes($v['goods_barcode'])));
                $insert['goods_image']  = trim(htmlspecialchars(addslashes($v['goods_image'])));
                $insert['goods_image'] = $insert['goods_image'] ? $insert['goods_image'] : $goods_common['goods_image'];
                $insert['add_time'] = time();
                $insert['spec_value_id_key'] = $idkey;
                $insert['goods_spec'] = serialize($goods_spec); 
                $id = M('goods')->add($insert);
                if(!$id){
                    $this->error('添加失败');
                }
            }
            $goods_spec_insert =  array();
            foreach($goods_common_spec as $spec_id=>$spec_value_ids){
                foreach($spec_value_ids as $spec_value_id){
                    $tempinsert  = array();
                    $tempinsert['goods_common_id'] = $goods_common_id;
                    $tempinsert['spec_id'] = $spec_id;
                    $tempinsert['spec_value_id'] = $spec_value_id;
                    $goods_spec_insert[]  =  $tempinsert;
                }
            }
            M('goods_spec')->where(array('goods_common_id'=>$goods_common_id))->delete();
            $goods_spec_insert_state = M('goods_spec')->addAll($goods_spec_insert);
            if(!$goods_spec_insert_state){
                $this->goods_model->rollback();
                $this->error('添加失败');
            }
            $update = array();
            $update['update_time'] =  time();
            $update['spec'] = serialize($goods_common_spec);
            $update_status =  M('goods_common')->where(array('goods_common_id'=>$goods_common_id))->save($update);
            if(!$update_status){
                $this->goods_model->rollback();
                $this->error('添加失败');
            }else{
                $this->goods_model->commit();
                $this->goods_model->delSpuCache($goods_common_id);
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'商品添加第二步','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                $this->success('操作成功',U('Commodity/goods_add_step3',array('goods_common_id'=>$goods_common_id,'edit'=>$_GET['edit'])));
            }
        }else{
            $spec_list = M('spec')->order(' listorder  desc')->limit(false)->select();
            $this->assign('goods_common',$goods_common);
            $this->assign('spec_list',$spec_list);
            $this->display();
        }
    }
    
    /**
     * 商品编辑第二部    规格设置
     */
    public function goods_edit_step2(){

        $goods_common_id =  I('goods_common_id',0,'intval');

        $goods_common  = array();
        if(!$goods_common_id){
         //   $this->error('参数错误',U('Commodity/goods_list'));
        }
    
        $goods_common  = M('goods_common')->where(array('goods_common_id'=>$goods_common_id))->find();
        
        $goods_common['spec'] =     unserialize($goods_common['spec']);
        ksort($goods_common['spec']);
        if(empty($goods_common)){
            $this->error('参数错误',U('Commodity/goods_list'));
        }
        //检测这个common_id下是否有商品
        $goods_check = M('goods')->where(array('goods_common_id'=>$goods_common_id))->count();

        if(!$goods_check){

            $this->goods_add_step2();exit();
        }
        if(!empty($_POST)){
            $goods_common_spec  = $_POST['spec_value'];
            $this->goods_model->startTrans();
            if(empty($_POST['goods_list'])){
                $this->error('参数错误');
            }
            $all_spec_value_ids = array();
            foreach($_POST['spec_value'] as $v){
                $all_spec_value_ids  = array_merge($all_spec_value_ids,$v);
            }
            $spec_value_list = M('spec_value')->master(true)->field('spec_value_id,spec_value,spec_id')->where(array('spec_value_id'=>array('in',$all_spec_value_ids)))->select();
            $spec_value_list  =  $this->key_convert('spec_value_id',$spec_value_list);
            $idkey_array =  array();
            foreach($_POST['goods_list'] as $idkey=>$v){
                $idkey_array[] = $idkey;
                $insert =  array();
                $insert['goods_common_id'] = $goods_common_id;
                $goods_spec = array();
                $goods_spec_name =  '';
                $goods_spec_values_ids = array();
                $goods_spec_values_ids   = explode('_', $idkey);
                foreach($goods_common_spec as $spec_id=>$v2){
                    foreach($goods_spec_values_ids as $spec_value_id){
                        if(in_array($spec_value_id, $v2)){
                            $goods_spec[$spec_id] = $spec_value_id;
                            $goods_spec_name.= ' '.$spec_value_list[$spec_value_id]['spec_value'];
                        }
                    }
                }
                $insert['goods_name'] = $goods_common['goods_name'];
                $insert['spec_name']  = $goods_spec_name;
                $insert['goods_state'] = $goods_common['goods_state'];
                $insert['gc_id'] = $goods_common['gc_id'];
                $insert['gc_id_1'] = $goods_common['gc_id_1'];
                $insert['gc_id_2'] = $goods_common['gc_id_2'];
                $insert['gc_id_3'] = $goods_common['gc_id_3'];
                $insert['goods_price']  = price_format($v['goods_price']);
                $insert['goods_storage'] = intval($v['goods_storage']);
                $insert['goods_barcode']  = trim(htmlspecialchars(addslashes($v['goods_barcode'])));
                $insert['goods_image']  = trim(htmlspecialchars(addslashes($v['goods_image'])));
                $insert['goods_image'] = $insert['goods_image'] ? $insert['goods_image'] : $goods_common['goods_image'];
                $insert['add_time'] = time();
                $insert['spec_value_id_key'] = $idkey;
                $insert['goods_spec'] = serialize($goods_spec);
                //存在就修改  不存在 就添加  
                $goods_id =  0 ;
                $condition  = array();
                $condition['goods_common_id'] = $goods_common_id;
                $condition['spec_value_id_key']  =  $idkey;
                $goods_id =  M('goods')->where($condition)->getField('goods_id');
                if($goods_id){
                    $insert['update_time']  = time();
                    $goods_update_state = M('goods')->where(array('goods_id'=>$goods_id))->save($insert);
                    if(!$goods_update_state){
                        $this->error('更新失败');
                    }    
                }else{
                    $id = M('goods')->add($insert);
                    if(!$id){
                        $this->error('更新失败');
                    }
                }
            }
            
            $goods_spec_insert =  array();
            foreach($goods_common_spec as $spec_id=>$spec_value_ids){
                foreach($spec_value_ids as $spec_value_id){
                    $tempinsert  = array();
                    $tempinsert['goods_common_id'] = $goods_common_id;
                    $tempinsert['spec_id'] = $spec_id;
                    $tempinsert['spec_value_id'] = $spec_value_id;
                    $goods_spec_insert[]  =  $tempinsert;
                }
            }
            M('goods_spec')->where(array('goods_common_id'=>$goods_common_id))->delete();
            $goods_spec_insert_state = M('goods_spec')->addAll($goods_spec_insert);
            if(!$goods_spec_insert_state){
                $this->goods_model->rollback();
                $this->error('更新失败');
            }
            
            //删除 不应存在的规格属性
            $delete_status  =  M('goods')->where(array('goods_common_id'=>$goods_common_id,'spec_value_id_key'=>array('notin',$idkey_array)))->delete();
            $update = array();
            $update['update_time'] =  time();
            $update['spec'] = serialize($goods_common_spec);
            $update_status =  M('goods_common')->where(array('goods_common_id'=>$goods_common_id))->save($update);
            if(!$update_status){
                $this->goods_model->rollback();
                $this->error('添加失败');
            }else{
                $this->goods_model->commit();
                $this->goods_model->delSpuCache($goods_common_id);
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'商品编辑第二步','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                $this->success('操作成功',U('Commodity/goods_add_step3',array('goods_common_id'=>$goods_common_id,'edit'=>$_GET['edit'])));
            }
        }else{
            $spec_list = M('spec')->order(' listorder  desc')->limit(false)->select();
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
            
            $goods_list   =  M('goods')->where(array('goods_common_id'=>$goods_common_id))->select();
            foreach($goods_list  as $k=>$v){
                $goods_s  =  unserialize($v['goods_spec']);
                ksort($goods_s);
                foreach($goods_s as $m=>$n){
                    $goods_s[$m]  = $goods_spec_list[$m]['spec_value_list'][$n]['spec_value'];
                 }
                $goods_list[$k]['goods_spec']   =  $goods_s;
            }
            
            $this->assign('spec_list',$spec_list);
            $this->assign('goods_common',$goods_common);
            $this->assign('goods_spec_list',$goods_spec_list);
            $this->assign('goods_list',$goods_list);
            $this->display('goods_edit_step2');
        }
    }
    
    
    public function goods_add_step3(){
        $goods_common_id =  I('goods_common_id',0,'intval');
        $goods_common  = array();
        if(!$goods_common_id){
            if(empty($_POST)){
                $this->error('参数错误',U('Commodity/goods_list'));
            }else{
                $this->error('参数错误');
            }
        }
    
        $goods_common  = M('goods_common')->master(true)->where(array('goods_common_id'=>$goods_common_id))->find();
        if(empty($goods_common)){
            if(empty($_POST)){
                $this->error('参数错误',U('Commodity/goods_list'));
            }else{
                $this->error('参数错误');
            }
        }
        //检测这个common_id下是否有商品
        $goods_check = M('goods')->master(true)->where(array('goods_common_id'=>$goods_common_id))->count();
        if(!$goods_check){
            if(empty($_POST)){
                $this->error('该商品还没有设置规格',U('Commodity/goods_add_step2',array('goods_common_id'=>$goods_common_id)));
            }else{
                $this->error('该商品还没有设置规格',U('Commodity/goods_add_step2',array('goods_common_id'=>$goods_common_id)));
            }
            
        }
        if(!empty($_POST)){
         $update  = array();
         $freight_type = I('freight_type','0','trim');
         $update['freight_type']  = $freight_type;
         switch ($freight_type){
             case  'fixed' :  //固定运费
                 $update['freight'] =   I('freight',0.00,'price_format');
                 break;
             case  'num':  //计件
                 $update['freight'] =   I('freight',0.00,'price_format');
                 $update['freight_step_num'] =  I('freight_step_num',0.00,'price_format');
                 $update['freight_step_fee'] =  I('freight_step_fee',0.00,'price_format');
                 
                 break;
             case  'weight': //按重量
                 $update['freight'] =   I('freight',0.00,'price_format');
                 $update['freight_step_num'] =  I('freight_step_num',0.00,'price_format');
                 $update['freight_step_fee'] =  I('freight_step_fee',0.00,'price_format');
                 $update['goods_weight'] =  I('goods_weight',0.00,'price_format');
                 break;
             case   'volume': //按体积
                 $update['freight'] =   I('freight',0.00,'price_format');
                 $update['freight_step_num'] =  I('freight_step_num',0.00,'price_format');
                 $update['freight_step_fee'] =  I('freight_step_fee',0.00,'price_format');
                 $update['goods_volume'] =  I('goods_volume',0.00,'price_format');
                 break;
             default:
                 $this->error('参数错误');
                 break;
         }
         $update['update_time']  = time();
         $this->goods_model->startTrans();
         $where = array('goods_common_id'=>$goods_common_id);
         $common_state = M('goods_common')->master(true)->where($where)->save($update);
         if(!$common_state){
             $this->goods_model->rollback();
             $this->error('保存失败');
         }
         $goods_state = M('goods')->master(true)->where($where)->save($update);
         if(!$goods_state){
             $this->goods_model->rollback();
             $this->error('保存失败');
         }
         $this->goods_model->commit();
         $this->goods_model->delSpuCache($goods_common_id);
         \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'商品添加第三步','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
         $this->success('操作成功',U('/Commodity/goods_add_step4',array('goods_common_id'=>$goods_common_id,'edit'=>$_GET['edit'])));
        }else{
            $this->assign('goods_common',$goods_common);
            $this->display();
        }
    }
    
    public function goods_add_step4(){
        $goods_common_id =  I('goods_common_id',0,'intval');
        $goods_common  = array();
        if(!$goods_common_id){
            if(empty($_POST)){
                $this->error('参数错误',U('Commodity/goods_list'));
            }else{
                $this->error('参数错误');
            }
        }
        $goods_common  = M('goods_common')->master(true)->where(array('goods_common_id'=>$goods_common_id))->find();
        if(empty($goods_common)){
            if(empty($_POST)){
                $this->error('参数错误',U('Commodity/goods_list'));
            }else{
                $this->error('参数错误');
            }
        }
        if(!empty($_POST)){
           M('goods_param')->where(array('goods_common_id'=>$goods_common_id))->delete();
           if(!empty($_POST['param_list'])){
               $insert = array();
               foreach($_POST['param_list'] as $v){
                   if(trim($v['param_name'])&&trim($v['param_value'])){
                       $temp = array();
                       $temp['goods_common_id'] = $goods_common_id;
                       $temp['param_name'] =  trim(htmlspecialchars(addslashes($v['param_name'])));
                       if(mb_strlen($temp['param_name'],"utf-8") > 20){
                            $temp['param_name'] = mb_substr($param_value,0,20,"utf-8");
                        }
                       $param_value = trim(htmlspecialchars(addslashes($v['param_value'])));
                        if(mb_strlen($param_value,"utf-8") > 50){
                            $name = mb_substr($param_value,0,50,"utf-8");
                        }else{
                            $name = $param_value;
                        }
                       $temp['param_value'] =  $name;
                       $temp['listorder'] = abs(intval($v['listorder']));
                       $insert[] = $temp;
                   }        
               }
               if(!empty($insert)){
                   $a = M('goods_param')->addAll($insert);
               }
           }
           $where = array('goods_common_id'=>$goods_common_id);
           $update = array();
           $update['update_time'] = time();
           $url =  '';
           if($_POST['pre_view']){  //预览
               $update['goods_state']  =  3; 
               $url = '/wap/goods_detail?id='.$goods_common_id; //预览的网址   
           }
           if($_POST['sell']){   //上架
               $update['goods_state']  = 1;
               $url = U('Commodity/goods_add_step5',array('goods_common_id'=>$goods_common_id));
           }
           if($_POST['unsell']){  //放入仓库
               $update['goods_state']  =  0;
               $url = U('Commodity/goods_add_step5',array('goods_common_id'=>$goods_common_id));
           }
           if($_POST['drafts']){  //存入草稿
               $update['goods_state']  =  3;
               $url = U('Commodity/goods_add_step5',array('goods_common_id'=>$goods_common_id));
           }
           $this->goods_model->startTrans();
           $common_state = M('goods_common')->where($where)->save($update);
           if(!$common_state){
               $this->goods_model->rollback();
               $this->error('保存失败');
           }
           $goods_state = M('goods')->where($where)->save($update);
           if(!$goods_state){
               $this->goods_model->rollback();
               $this->error('保存失败');
           }
           $this->goods_model->commit();
           $this->goods_model->delSpuCache($goods_common_id);
           \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'商品添加第四步','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
           $this->success('操作成功',$url);
           //更新商品状态
        }else{
            $this->assign('goods_common',$goods_common);
            $param_list  = M('goods_param')->where(array('goods_common_id'=>$goods_common_id))->order('listorder desc')->select();
            $this->assign('param_list',$param_list);
            $this->display();
        }
    }
    
    public function goods_add_step5(){
        $goods_common_id =  I('goods_common_id',0,'intval');
        $goods_common  = array();
        if(!$goods_common_id){
            if(empty($_POST)){
                $this->error('参数错误',U('Commodity/goods_list'));
            }else{
                $this->error('参数错误');
            }
        }
        $goods_common  = M('goods_common')->master(true)->where(array('goods_common_id'=>$goods_common_id))->find();
        if(empty($goods_common)){
            if(empty($_POST)){
                $this->error('参数错误',U('Commodity/goods_list'));
            }else{
                $this->error('参数错误');
            }
        }
		$this->goods_qcode($goods_common_id);
		 $path = 'goods_qcode';
		$qrcode =  C('TMPL_PARSE_STRING.__ATTACH_HOST__').$path.'/'.fmod($goods_common_id,100).'/'.$goods_common_id.".png";
		$goods_common['qrcode'] = $qrcode;
        $this->assign('goods_common',$goods_common);
        $this->display();
    } 
    
    /**
     * 商品操作
     */
    public function goods_operation(){
        $op =  I('op','','trim');
        if(!in_array($op,array('delete','recycle','remove_recycle','sell','un_sell','move_class'))){
            $this->error('参数错误');    
        }
        $this->{goods_.$op}();
    }
    
    /**
     * 加入回收站
     */
    public function goods_recycle(){
          if(empty($_POST['goods_common_ids'])){
              $this->error('没有选择任何商品');
          }
          $goods_common_ids = array();
          foreach($_POST['goods_common_ids'] as $v){
              $v = intval($v);
              if($v){
                  $goods_common_ids[] = $v;
              }
          }
          if(empty($goods_common_ids)){
              $this->error('没有选择任何商品');
          }
          
          $where = array();
          $where['goods_common_id'] = array('in',$goods_common_ids);
          $update = array();
          $update['goods_state'] = 4;
          $update['update_time'] = time();
          
          $this->goods_model->startTrans();
          $common_update = M('goods_common')->where($where)->save($update);
          if(!$common_update){
              $this->goods_model->rollback();
              $this->error('更改失败!');
          }
          $goods_update = M('goods')->where($where)->save($update);
          if($goods_update === false){
              $this->goods_model->rollback();
              $this->error('更改失败!');
          }
          $this->goods_model->commit();
          foreach($goods_common_ids as $goods_common_id){
              $this->goods_model->delSpuCache($goods_common_id);
          }
          \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'移动到商品回收站','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
          $this->success('操作成功');
    }
    
    
    /**
     * 回收站还原
     */
    public function goods_remove_recycle(){
        if(empty($_POST['goods_common_ids'])){
            $this->error('没有选择任何商品');
        }
        $goods_common_ids = array();
        foreach($_POST['goods_common_ids'] as $v){
            $v = intval($v);
            if($v){
                $goods_common_ids[] = $v;
            }
        }
        if(empty($goods_common_ids)){
            $this->error('没有选择任何商品');
        }
        $where = array();
        $where['goods_common_id'] = array('in',$goods_common_ids);
        $update = array();
        $update['goods_state'] = 0;
        $update['update_time'] = time();
        
        $this->goods_model->startTrans();
        $common_update = M('goods_common')->master(true)->where($where)->save($update);
        if(!$common_update){
            $this->goods_model->rollback();
            $this->error('更改失败!');
        }
        $goods_update = M('goods')->master(true)->where($where)->save($update);
        if($goods_update === false){
            $this->goods_model->rollback();
            $this->error('更改失败!');
        }
        $this->goods_model->commit();
        foreach($goods_common_ids as $goods_common_id){
            $this->goods_model->delSpuCache($goods_common_id);
        }
        \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'商品从回收站还原','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
        $this->success('操作成功');
    }
    
    public function goods_delete(){
        if(empty($_POST['goods_common_ids'])){
            $this->error('没有选择任何商品');
        }
        $goods_common_ids = array();
        foreach($_POST['goods_common_ids'] as $v){
            $v = intval($v);
            if($v){
                $goods_common_ids[] = $v;
            }
        }
        if(empty($goods_common_ids)){
            $this->error('没有选择任何商品');
        }
        $where = array();
        $where['goods_common_id'] = array('in',$goods_common_ids);
        
        $this->goods_model->startTrans();
        $common_delete = M('goods_common')->master(true)->where($where)->delete();
        if(!$common_delete){
            $this->goods_model->rollback();
            $this->error('删除失败!');
        }
        $goods_delete = M('goods')->master(true)->where($where)->delete();
        if($goods_delete === false){
            $this->goods_model->rollback();
            $this->error('删除失败!');
        }
        M('goods_images')->master(true)->where($where)->delete();
        M('goods_spec')->master(true)->where($where)->delete();
        $this->goods_model->commit();
        foreach($goods_common_ids as $goods_common_id){
            $this->goods_model->delSpuCache($goods_common_id);
        }
        \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'删除商品','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
        $this->success('操作成功');
    }
    
    
    public function goods_sell(){
        if(empty($_POST['goods_common_ids'])){
            $this->error('没有选择任何商品');
        }
        $goods_common_ids = array();
        foreach($_POST['goods_common_ids'] as $v){
            $v = intval($v);
            if($v){
                $goods_common_ids[] = $v;
            }
        }
        if(empty($goods_common_ids)){
            $this->error('没有选择任何商品');
        }
        $where = array();
        $where['goods_common_id'] = array('in',$goods_common_ids);
        $update = array();
        $update['goods_state'] = 1;
        $update['update_time'] = time();
        
        $this->goods_model->startTrans();
        $common_update = M('goods_common')->master(true)->where($where)->save($update);
        if(!$common_update){
            $this->goods_model->rollback();
            $this->error('更改失败!');
        }
        $goods_update = M('goods')->master(true)->where($where)->save($update);
        if($goods_update === false){
            $this->goods_model->rollback();
            $this->error('更改失败!');
        }
        $this->goods_model->commit();
        foreach($goods_common_ids as $goods_common_id){
            $this->goods_model->delSpuCache($goods_common_id);
        }
        \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'商品上架','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
        $this->success('操作成功');
    }
    public function goods_un_sell(){
        if(empty($_POST['goods_common_ids'])){
            $this->error('没有选择任何商品');
        }
        $goods_common_ids = array();
        foreach($_POST['goods_common_ids'] as $v){
            $v = intval($v);
            if($v){
                $goods_common_ids[] = $v;
            }
        }
        if(empty($goods_common_ids)){
            $this->error('没有选择任何商品');
        }
        $where = array();
        $where['goods_common_id'] = array('in',$goods_common_ids);
        $update = array();
        $update['goods_state'] = 0;
        $update['update_time'] = time();
        
        $this->goods_model->startTrans();
        $common_update = M('goods_common')->master(true)->where($where)->save($update);
        if(!$common_update){
            $this->goods_model->rollback();
            $this->error('更改失败!');
        }
        $goods_update = M('goods')->master(true)->where($where)->save($update);
        if($goods_update === false){
            $this->goods_model->rollback();
            $this->error('更改失败!');
        }
        $this->goods_model->commit();
        foreach($goods_common_ids as $goods_common_id){
            $this->goods_model->delSpuCache($goods_common_id);
        }
        \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'商品下架','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
        $this->success('操作成功');
    }
    
    public function goods_move_class(){
        if(empty($_POST['goods_common_ids'])){
            $this->error('没有选择任何商品');
        }
        $goods_common_ids = array();
        foreach($_POST['goods_common_ids'] as $v){
            $v = intval($v);
            if($v){
                $goods_common_ids[] = $v;
            }
        }
        if(empty($goods_common_ids)){
            $this->error('没有选择任何商品');
        }
        $gc_id = I('gc_id',0,'intval');
        if(!$gc_id){
            $this->error('请选择分类');
        }
        $gc_info = $this->goods_category_model->getCategoryPosition($gc_id);
        if(empty($gc_info)){
            $this->error('分类信息错误');
        }
        $where = array();
        $where['goods_common_id'] = array('in',$goods_common_ids);
        $update = array();
        $update['update_time'] = time();
        $update['gc_id'] = $gc_id;
        $update['gc_id_1'] = $gc_info['gc_id_1'];
        $update['gc_id_2'] = $gc_info['gc_id_2'];
        $update['gc_id_3'] = $gc_info['gc_id_3'];

        $this->goods_model->startTrans();
        $common_update = M('goods_common')->master(true)->where($where)->save($update);
        if(!$common_update){
            $this->goods_model->rollback();
            $this->error('更改失败!');
        }
        $goods_update = M('goods')->master(true)->where($where)->save($update);
        if($goods_update === false){
            $this->goods_model->rollback();
            $this->error('更改失败!');
        }
        $this->goods_model->commit();
        foreach($goods_common_ids as $goods_common_id){
            $this->goods_model->delSpuCache($goods_common_id);
        }
        \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'商品移动分类','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
        $this->success('操作成功');

    }
    
    
    /**
     * 把数组 变成拿某个字段做键的数组
     * @param unknown $key
     * @param unknown $array
     */
    public  function key_convert($key,$array){
        if(empty($array)){
            return array();
        } 
        $new_array  =  array();
        foreach($array as $v){
            $new_array[$v[$key]] = $v;
        }
        return $new_array;   
    }
    
    
    /**
     * 添加规格值
     */
    public function spec_value_add(){
        $spec_value = I('spec_value','','trim,htmlspecialchars,addslashes');
        $spec_id   =  I('spec_id',0,'intval');
        if(!$spec_value){
            $this->error('规格值不允许为空');
        }
        if(!$spec_id){
            $this->error('参数错误');
        }
        
        $spec_value_check  =  M('spec_value')->where(array('spec_value'=>$spec_value,'spec_id'=>$spec_id))->find();
        if(!empty($spec_value_check)){
            $this->error('规格值重复');
        }
        $spec_value_id = M('spec_value')->add(array('spec_value'=>$spec_value,'spec_id'=>$spec_id));
        if($spec_id){
            \Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'规格值添加','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            $this->success(array('spec_value_id'=>$spec_value_id,'spec_value'=>$spec_value,'spec_id'=>$spec_id));
        }else{
            $this->error('添加失败');
        }
    }
    public function get_spec(){
        str_ireplace(array('width','height'), array('wiidth','heiight'), $content);
        $spec_id = I('spec_id',0,'intval');
        if(!$spec_id){
            $this->error('规格信息获取失败');
        }
        $spec_info =   M('spec')->where(array('spec_id'=>$spec_id))->find();
        if(empty($spec_info)){
            $this->error('规格信息获取失败');
        }
        $spec_values =    M('spec_value')->where(array('spec_id'=>$spec_id))->select();
        if(empty($spec_values)){
            $spec_values = array();
        }
        $spec_info['spec_values'] =  $spec_values;
        $this->success($spec_info);    
    }
    private function nav_menu($key = ''){
        $menu = array();
        //商品分类的
        $menu['category']['所有分类'] = U('Commodity/category');
        $menu['category']['只看当层'] = U('Commodity/category',array('no_child'=>1));
        //商品规格
        //商品分类的
        $menu['spec']['规格'] = U('Commodity/spec');
        $menu['spec']['规格值'] = U('Commodity/spec_value');
        
        
        //商品相关的
//         $menu['goods']['全部商品'] = U('Commodity/goods_list');
        $menu['goods']['上架中的商品'] = U('Commodity/goods_list',array('goods_state'=>'online'));
        $menu['goods']['仓库中的商品'] = U('Commodity/goods_list',array('goods_state'=>'offline'));
        $menu['goods']['回收站'] = U('Commodity/goods_list',array('goods_state'=>'recycle'));
        $menu['goods']['草稿箱'] = U('Commodity/goods_list',array('goods_state'=>'draft'));
        return  $menu[$key];
    }
}