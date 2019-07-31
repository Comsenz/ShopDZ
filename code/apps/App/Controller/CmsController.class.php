<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\CmsService;
use Common\Service\IndexService;
class CmsController extends BaseController {
	function getregisttitle() {
		$article_title = IndexService::getSetting('article_title');
		$data['title'] = $article_title;
		$this->returnJson(0,'sucess',$data);
	}
	function getcms() {
 
		$cms_id = I('param.cms_id',0,'intval');
		$type = I('param.type');//区别用户注册协议
		$data = array();
		if($type == 'register') {
			$article_title = IndexService::getSetting('article_title');
			$article_content = IndexService::getSetting('article_content');
			if($article_content){
				$data['article_id'] = 1;
				$data['article_title'] = $article_title;
				$data['article_content'] = htmlspecialchars_decode($article_content);
				$data['article_publish_time'] = 1;
				$this->returnJson(0,'sucess',$data);
			}
			$this->returnJson(1,'文章不存在',$data);
		}else{
			if(empty($cms_id)) 
				$this->returnJson(1,'缺少参数');
			$info = CmsService::getCmsById($cms_id);
			if($info){
				$data['article_id'] = $info['article_id'];
				$data['article_title'] = $info['article_title'];
				$class_name =  CmsService::getClassName($info['class_id']);
				$data['article_class_name'] = $class_name['class_name'];
				$data['article_content'] = htmlspecialchars_decode($info['article_content']);
				$data['article_publish_time'] = date('Y-m-d',$info['article_time']);
				$this->returnJson(0,'sucess',$data);
			}
			$this->returnJson(1,'文章不存在',$data);
		}
	}
	//获取帮助分类下的文章列表
	function gethelpcms(){
		$data = CmsService::getHelpCms();
		$this->returnJson(0,'sucess',array('help_list' => $data));

	}
}