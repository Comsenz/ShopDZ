<?php
namespace Common\Model;
use Think\Model;
class CmsArticleModel extends Model {
	
	public function getCmsById($cms_id,$article_show = 1) {
		$data = array();
		if(empty($cms_id)) return $data;
		$model = new model();
		$data = $model->table(C('DB_PREFIX').'article')->where("article_id=%d and article_show=%d",array($cms_id,$article_show))->find();
		return $data;
	}
	
	public function getClassName($class_id) {
		$data = array();
		if(empty($class_id)) return $data;
		$model = new model();
		$data = $model->table(C('DB_PREFIX').'cms_article_class ')->where("class_id=%d ",array($class_id))->find();
		return $data;
	}

	public function getHelpClass(){
		$where['class_code'] = array('NEQ','0');
		$model = new model();
		$data = $model->table(C('DB_PREFIX').'cms_article_class ')->where($where)->find();
		return $data;
	}

	public function getArticleList($class_id){
		$data = array();
		if(empty($class_id)) return $data;
		$model = new model();
		$where['class_id'] = $class_id;
		$where['article_show'] = 1;
		$data = $model->table(C('DB_PREFIX').'article ')->where($where)->order('article_sort desc,article_time desc')->select();
		return $data;
	}
}
?>