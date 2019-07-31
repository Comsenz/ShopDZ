<?php
namespace Admin\Controller;
use Think\Controller;
class DistrictController extends BaseController {
    private  $area_model;
    public  function __construct(){
         parent::__construct();   
         $this->area_model = D('Area');
    }

    //删除自己以及子地区
    public function delArea(){
        $area_ids = I('post.area_ids');
        if(!$area_ids){
            $this -> ajaxReturn(array('status'=>0,'info'=>'参数错误，未选中任何信息。'));
        }
        $area_ids = explode(',',trim($area_ids,','));
        foreach ($area_ids as $v) {
            $area_ids = array_merge($area_ids,$this->area_model->getChildrenIDs($v));
        }
        $con['area_id'] = array('IN',$area_ids);
        $this->area_model-> where($con) -> delete();
        $this -> ajaxReturn(array('status'=>1));
    }
    public function add(){
        $parent_id = intval(I('get.parent_id'));
        if ($parent_id) {
            $info = $this->area_model->getAreaInfo(array('area_id'=>intval($parent_id)));
            $data = array();
            $data['area_parent_id'] = $info['area_id'];
            $data['area_deep'] = $info['area_deep']+1;
            $data['area_parent_name'] = $this->area_model->getTopAreaName($parent_id);
            $this -> assign("info",$data);
        }
        $this -> display("add");

    }
    public function edit() {
        $area_id = intval(I('get.area_id'));
        if($area_id) {
            $info = $this->area_model -> where("area_id={$area_id}")->find();
            $info['area_parent_name'] = $this->area_model->getTopAreaName($info['area_parent_id']);
            $this -> assign("info",$info);
        }
        $this -> display("add");
    }
    public function save(){
        $info = array();
        $post = I("post.");
        $area_id = $post['area_id'];
        $data = array();
        $data['area_name'] = $post['area_name'];
        $data['area_region'] = $post['area_region'];
        if(!$post['area_name']) {
            $this -> ajaxReturn(array('status'=>0,'info'=>'地区名不能为空'));
        }
        if ($post['area_id']) {
            $result = $this->area_model->where("area_id={$area_id}")->save($data);
        } else {
            $data['area_parent_id'] = $post['parent_id'];
            $data['area_deep'] = intval($post['area_deep']);
            $area_id = $this->area_model->add($data);
        }
        if(!$area_id){
            $this->error('数据保存失败，请重试。');
        }
        $this->success('保存成功', U('/Setting/District'));
    }

      /**
     * json输出地址数组 原data/resource/js/area_array.js
     */
    public function json_area()
    {
        $_GET['src'] = $_GET['src'] != 'db' ? 'cache' : 'db';
        echo $_GET['callback'].'('.json_encode($this->area_model->getAreaArrayForJson($_GET['src'])).')';
    }

    /**
     * 根据ID返回所有父级地区名称
     */
    public function json_area_show()
    {
        $area_info['text'] = $this->area_model->getTopAreaName(intval($_GET['area_id']));
        echo $_GET['callback'].'('.json_encode($area_info).')';
    }
}