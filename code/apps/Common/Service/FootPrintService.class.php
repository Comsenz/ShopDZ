<?php
namespace Common\Service;
class FootPrintService {

	
	static function FootPrint($data) {
		$footprintModel  = D('FootPrint');
		return $footprintModel->FootPrint($data);
	}
	
	static function getFootPrint($uid,$page,$prepage) {
		$footprintModel  = D('FootPrint');
		return $footprintModel->getFootPrint($uid,$page,$prepage);
	}
}