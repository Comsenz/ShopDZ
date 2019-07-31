<?php
namespace Common\Service;
use Common\Helper\ToolsHelper;
class CmsService {
	static function getRegisterCms() {
		$cms_article  = D('CmsArticle');
		$data = $cms_article->getRegisterCms();
		return $data;
	}
	
	static function getCmsById($cms_id) {
		$cms_article  = D('CmsArticle');
		$data = $cms_article->getCmsById($cms_id);
		return $data;
	}
	
	static function getClassName($class_id) {
		$cms_article  = D('CmsArticle');
		$data = $cms_article->getClassName($class_id);
		return $data;
	}

	static function getHelpCms(){
		$cms_article  = D('CmsArticle');
		$help_cms = $cms_article -> getHelpClass();
		$data = $cms_article ->getArticleList($help_cms['class_id']);
		return $data;
	}
}