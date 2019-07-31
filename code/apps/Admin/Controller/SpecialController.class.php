<?php
namespace Admin\Controller;
use Admin\Model\SpecialModel;
use Think\Controller;
class SpecialController extends BaseController {
    public function itemAdd(){
        $post = I("post.");
        if(is_array($post) && !empty($post)){
            $name2value = array(
                'adv_list' => '幻灯模块',
                'adv_img' => '通栏图片广告',
                'adv_html' => '预置通栏模板',
                'adv_nav' => '图文导航',
                'goods' => '商品展示',
            );
            $post['item_usable'] = 0;
            $post['item_sort'] = 255;
            $post['item_name'] = $name2value[$post['item_type']];
            $model = new SpecialModel();
            $itemid = $model -> addItem($post);
            if($itemid){
               $this -> ajaxReturn(array('status'=>1,'data'=>$itemid,'info'=>'添加成功'));
            }
            $this -> ajaxReturn(array('status'=>0,'data'=>$itemid,'info'=>'添加失败'));
        }
    }

    public function itemEdit(){
         //取所有有效优惠劵列表
        $time = time();
        $where = "`rpacket_t_start_date` < {$time} AND `rpacket_t_end_date` > {$time} AND `rpacket_t_state`=1 AND `rpacket_t_total` > `rpacket_t_giveout`";
        $rpacket_list = M('redpacket_template')->field('rpacket_t_id,rpacket_t_title')->where($where)->order('rpacket_t_save_time DESC')->limit()->select();
        $this->assign('rpacket_list',$rpacket_list);
        //取所有分类
        $goods_category_model = D('GoodsCategory');
        $category_list = $goods_category_model->getShowCategoryTree();
        // p($category_list);die;
        $this->assign('category_list',$category_list);

        $model = new SpecialModel();
        $itemid = I("item_id");
        $item_info = $model -> where(array("item_id"=>$itemid)) -> find();
        if(!$item_info){
            $this->showmessage('error','参数错误',U("Admin/Special/itemList"));
        }
        $type = $item_info['item_type'];
        $specialid = $item_info['special_id'];
        if($type=='adv_html'){
            $item_data = stripslashes($item_info['item_data']);
        }else{
            $item_data = unserialize($item_info['item_data']);
        }
        //如果是商品模块则需要取出商品的信息
        if($type == 'goods'){
            $goodsid = array();
            foreach($item_data['goods'] as $g){
                $goodsid[] = $g;
            }
            if($goodsid && !empty($goodsid)){
                $goods_info = M("goods_common") -> where(array('goods_common_id'=>array('in',$goodsid))) -> select();
                $newgoods_info=array();
                foreach ($goods_info as $key => $value) {
                    $index = array_search($value['goods_common_id'],$goodsid);
                    $newgoods_info[$index] = $value;
                }
                ksort($newgoods_info);
            }
        }
        if($type == 'goods'){
            $this -> assign("goods",$newgoods_info);            
        }
        //如果是预置通栏模板则需要取出模板的信息
        if($type == 'adv_html'){    
            $tpl_info = M("banner_template") -> select();
        }

        if($type == 'adv_html' && !empty($tpl_info)){
            $this -> assign("tpl_info",$tpl_info);
        }
        $this -> assign("item_info",$item_info);
        $this ->assign("edit",$itemid);
        $this ->assign("specialid",$specialid);
        $this ->assign("item_data",$item_data);
        $this ->assign("item_type",$type);
        $this -> display("special_type_".$type);
    }

    public function imgEdit(){
      $model = new SpecialModel();
        $itemid = I("item_id");
        $item_info = $model -> where(array("item_id"=>$itemid)) -> find();
        if(!$item_info){
            $this->showmessage('error','参数错误');
        }
        $type = $item_info['item_type'];
        $specialid = $item_info['special_id'];
        $item_data = unserialize($item_info['item_data']);
        if(!$item_data){
            $this->showmessage('error','数据为空');
        }else{
            $this -> ajaxReturn(array('status'=>1,'data'=>$item_data[I('img')],'info'=>'查询成功'));
        }
    }

    public function itemList(){
        $model = new SpecialModel();
        $specialid = I("spid");
        $itemlist = array();
        if(!$specialid){
            //进入专题设置界面
            $condition = array();
            $condition['specila_id'] = $specialid;
            $itemlist = $model -> getItemList($condition);
        }else{
            //进入首页设置
            $condition = array();
            $condition['specila_id'] = 0;
            $itemlist = $model -> getItemList($condition,'*',0,null);
        }
        foreach($itemlist as &$a){
            $a['data'] = unserialize($a['item_data']);
            if($a['item_type'] == 'goods'){
                $goodsid = array();
                foreach($a['data']['goods'] as $g){
                    $goodsid[] = $g;
                }
                if($goodsid && !empty($goodsid)){
                    $a['goods_info'] = M("goods_common") -> where(array('goods_common_id'=>array('in',$goodsid))) -> select();
                    $newgoods_info=array();
                    foreach ($a['goods_info'] as $key => $value) {
                        $index = array_search($value['goods_common_id'],$goodsid);
                        $newgoods_info[$index] = $value;
                    }
                    ksort($newgoods_info);
                    $a['goods_info'] = $newgoods_info;
                }
            }
        }
        $this ->assign("item_list",$itemlist);
        $this -> display('Setting/personnel');
    }

    public function itemSort(){
        $model = new SpecialModel();
        $item_id_string = I('item_id_string');
        $special_id = I('special_id');
        if(!empty($item_id_string)) {
            $item_id_array = explode(',', $item_id_string);
            $index = 0;
            foreach ($item_id_array as $item_id) {
                $result = $model->itemSort(array('item_sort' => $index), $item_id, $special_id);
                $index++;
            }
        }
        $this -> ajaxReturn(array('status'=>1));
    }

    public function itemStatus(){
        $model = new SpecialModel();
        $result = $model->itemStatus(I('usable'), I('item_id'), I('special_id'));
        if($result === false){
            $this -> ajaxReturn(array('status'=>0));
        }
        $this -> ajaxReturn(array('status'=>1));
    }

    public function itemDel(){
        $model = new SpecialModel();
        $condition = array();
        $condition['item_id'] = I('item_id');
        $result = $model->itemDel($condition);
        if($result) {
            $this -> ajaxReturn(array('status'=>1));
        } else {
            $this -> ajaxReturn(array('status'=>0,'info'=>'删除失败'));
        }
    }

    public function itemSave(){
        $itemid = I("item_id");
        if(!$itemid){
        	if(IS_AJAX) {
        		$this -> ajaxReturn(array('status'=>0,'info'=>'参数错误'));
        	}else{
            	$this -> error('参数错误');
        	}
        }
        $type = I('item_type');
        switch($type){
            case "adv_list":
                $this -> saveAdvList();
                break;
            case "adv_img":
                $this -> saveAdvList(1);
                break;
            case "goods":
                $this -> saveGoodsList(1);
                break;
            case "adv_html":
                $this -> saveAdvHtml();
                break;
            case "adv_nav":                
                $this -> saveAdvNav();
                break;
            default:
                break;
        }
        $model = new SpecialModel();
    }

    public function itemTypeAdd(){
        $type = I("get.type");
        $this -> display();
    }

    public function saveAdvList($limit = 0){
        $post = I("post.");

        $model = new SpecialModel();
        $itemid = I("item_id");
        $item_info = $model -> where(array("item_id"=>$itemid)) -> find();
        $data = unserialize($item_info['item_data']);
        // var_dump($data);
        //提示上限
        if(is_array($data) && $limit && $limit <= count($data)  && I('dotype') != 'del'){
            $this -> ajaxReturn(array('status'=>0,'info'=>'只能上传一张广告图片，修改内容需要先删除之前的广告！'));
        }

        if(is_array($data) && 8 <= count($data)  && I('dotype') != 'del' && I('dotype') != 'edit'){
            $this -> ajaxReturn(array('status'=>0,'info'=>'只能上传8张轮播图片，更新图片请删除之前的图片！'));

        }

        if(I("dotype") == 'del'){
            unset($data[I('img')]);
        }elseif(I("dotype") == 'edit'){
            if($post['img'] == ''){
                // $data[I('num')]['type']=$post['type'];
                // $data[I('num')]['data']=$post['data'];
                $this -> ajaxReturn(array('status'=>0,'info'=>'上传图片不能为空！'));
            }else{
                $data[I('num')]['img']=$post['img'];
                $data[I('num')]['type']=$post['type'];
                $data[I('num')]['data']=$post['data'];
            }

        }else{
            if($post['img'] == ''){
                $this -> ajaxReturn(array('status'=>0,'info'=>'上传图片不能为空！'));

            }else{
                $data[] = array(
                    'img' => $post['img'],
                    'type' => $post['type'],
                    'data' => $post['data'],
                );
            }
        }
        $item_info['item_data'] = serialize($data);
        $res = $model -> where("item_id={$itemid}") -> save($item_info);
        if($res !== false){
            $this -> ajaxReturn(array('status'=>1,'info'=>'操作成功！'));
        }
        $this -> ajaxReturn(array('status'=>0,'info'=>'操作失败！'));
    }

    public function saveAdvHtml(){
        $model = new SpecialModel();
        $itemid = I("item_id");
        // $save = array(
        //     'adv_content' => $_POST["adv_content"],
        // );
        $save = $_POST["adv_content"];
        $res = $model -> where(array("item_id"=>$itemid)) -> save(array('item_data'=>$save));
        if($res !== false){
            $this -> success('保存成功');
        } else {
        	$this -> error('保存失败');
        }
    }

    public function saveAdvNav(){
        $model = new SpecialModel();
        $itemid = I("item_id");

        //图文数据存储处理
        $save  = array();
        for($i = 1 ; $i<=4;$i++){
            $data = array();
            $img = I('img_'.$i,'','trim,addslashes,htmlspecialchars');
            if($img){
                $data['img'] =   $img;
                $data['title'] =  I('title_'.$i,'','trim,addslashes,htmlspecialchars');
                $data['type'] = I('type_'.$i,'','trim,addslashes,htmlspecialchars');
                $data['data'] = I('data_'.$i,'','trim,addslashes,htmlspecialchars');
                 
                    
            }else{
                $data['img'] =   '';
                $data['title'] =  I('title_'.$i,'','trim,addslashes,htmlspecialchars');
                $data['type'] = I('type_'.$i,'','trim,addslashes,htmlspecialchars');
                $data['data'] = I('data_'.$i,'','trim,addslashes,htmlspecialchars');
            }

            $save[] = $data;
        }
        $res = $model -> where(array("item_id"=>$itemid)) -> save(array('item_data'=>serialize($save)));
        if($res !== false){
            $this -> success('保存成功');
        } else {
            $this -> error('保存失败');
        }
    }

    public function saveGoodsList(){
        $post = I("post.");
        $model = new SpecialModel();
        $itemid = I("item_id");
        $save = array(
            // 'title'=>$post['item_data']['title'],
            // 'more_url'=>$post['item_data']['more_url'],
            'goods' => $post['item_data']['item'],
        );
        $res = $model -> where(array("item_id"=>$itemid)) -> save(array('item_data'=>serialize($save)));
        if($res !== false){
            $this -> success('保存成功');
        } else {
        	$this -> error('保存失败');
        }
    }

    //查询通栏广告是否为一张图片
    public function isItemExist(){
        $itemid = I("item_id");
        $itemtype = I('item_type');
        if(!$itemid){    
            $this -> ajaxReturn(array('status'=>0,'info'=>'参数错误'));
        }
        $model = new SpecialModel();
        $itemid = I("item_id");
        $item_info = $model -> where(array("item_id"=>$itemid)) -> find();
        $data = unserialize($item_info['item_data']);
        //提示上限
        if($itemtype == 'adv_img'){
            if(is_array($data) && 1 <= count($data)){
                $this -> ajaxReturn(array('status'=>0,'info'=>'只能上传一张广告图片，修改内容需要先删除之前的广告！'));
            }
        }else{
            if(is_array($data) && 8 <= count($data)){
                $this -> ajaxReturn(array('status'=>0,'info'=>'只能上传8张轮播图片，更新图片请删除之前的图片！'));
            }
        }
        $this -> ajaxReturn(array('status'=>1)); 
        

    }

    //查询通栏模板的html
    public function html(){
        $tpl_id = I("post.tpl_id");
        if(!$tpl_id){
            $this -> ajaxReturn(array('status'=>0,'info'=>'参数错误'));
        }
        $html = M("banner_template") -> field('html,intro_pic') -> where("tpl_id = $tpl_id") -> find();
        $this -> ajaxReturn(array('status'=>1,'data'=>$html,'info'=>'true'));
        
    }

    //首页预览
    public function showIndex(){
        $model = new SpecialModel();
        $condition = array();
        $condition['item_usable'] = 1;
        $special_item_list = $model->getItemList($condition);
        foreach ($special_item_list as $k => $v) {
            if($v['item_type'] != 'adv_html'){
                $special_item_list[$k]['item_data']=unserialize($v['item_data']);
            }
            //如果是商品模块则需要取出商品的信息
            if($v['item_type'] == 'goods'){
                $goodsid = array();
                foreach($special_item_list[$k]['item_data']['goods'] as $g){
                    $goodsid[] = $g;
                }
                $newgoods=array();
                foreach ($goodsid as $key => $value) {
                    $newgoods[$value] = $key;
                }
                if($goodsid && !empty($goodsid)){
                    $goods_info = M("goods_common") -> where(array('goods_common_id'=>array('in',$goodsid))) -> select();
                    $newgoods_info=array();
                    foreach ($goods_info as $key => $value) {
                        $newgoods_info[] = $goods_info[$newgoods[$value['goods_common_id']]];
                    }
                    $special_item_list[$k]['goods_info'] = $newgoods_info;
                }
            }
            //如果是轮播图片，只取前8张
            // if($v['item_type'] == 'adv_list'){
            //     $num = 0;
            //     $adv_list = array();
            //     foreach ($special_item_list[$k]['item_data'] as $key => $value) {
            //         if($num >= 8){
            //             break;
            //         }
            //         $adv_list[$key] = $value;
            //         $num++;
                  
            //     }
            //     $special_item_list[$k]['item_data'] = $adv_list;
            // }
        }
        // var_dump($special_item_list);die;
        $this ->assign("special_item_list",$special_item_list);
        $this -> display("show_index");

    }
}