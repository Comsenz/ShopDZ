<?php
namespace Admin\Controller;
use Think\Controller;
class AdvController extends BaseController {
    protected $tableName = "adv";

    public function delAdv(){
        $aid = intval(I('post.aid'));
        if(!$aid){
            $this -> ajaxReturn(array('status'=>0,'info'=>'参数错误'));
        }
        M($this -> tableName) -> where(array("id={$aid}")) -> delete();
        $this -> ajaxReturn(array('status'=>1));
    }

    public function edit(){
        $aid = I('get.aid');
        $ajax = I("get.ajax");
        if($ajax){
            $post = I("post.");
            $post['starttime'] = strtotime($post['starttime']);
            $post['endtime'] = strtotime($post['endtime']);
            if($post['endtime'] <= time() || $post['endtime'] <= $post['starttime']){
                $this -> ajaxReturn(array('status'=>0,'info'=>'时间区间不合理'));
            }
            $res = M($this->tableName)->where("id={$aid}")->save($post);
            if($res === false){
                $this -> ajaxReturn(array('status'=>0));
            }
            $this -> ajaxReturn(array('status'=>1));
        }
        $adv = M($this -> tableName) -> where("id={$aid}")->find();
        $this -> assign("adv",$adv);
        $this -> assign("do","edit");
        $this -> display("add");
    }

    public function add(){
        $ajax = I("get.ajax");
        if($ajax){
            $post = I("post.");
            $post['starttime'] = strtotime($post['starttime']);
            $post['endtime'] = strtotime($post['endtime']);
            if($post['endtime'] <= time() || $post['endtime'] <= $post['starttime']){
                $this -> ajaxReturn(array('status'=>0,'info'=>'时间区间不合理'));
            }
            $res = M($this->tableName)->add($post);
            if(!$res){
                $this -> ajaxReturn(array('status'=>0));
            }
            $this -> ajaxReturn(array('status'=>1,'data'=>$res));
        }
        //设置起始时间
        $adv = array();
        $adv['starttime'] = $adv['endtime'] = time();
        $this -> assign("adv",$adv);
        $this -> display("add");
    }
}