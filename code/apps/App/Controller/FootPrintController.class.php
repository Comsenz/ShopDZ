<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\FootPrintService;
use Common\Service\GoodsService;
class FootPrintController extends BaseController {
	/*
	*保存收藏
	*/
	function footprint() {
		$common_id = I('param.common_id','0');
		if(empty($common_id))
			$this->returnJson(1,'数据错误，请重试');
		$this->getMember();
		$goods = GoodsService::getGoodsCommon($common_id);
		if(empty($goods))
			$this->returnJson(1,'数据错误，请重试');
		$uid= $this->member_info['member_id'];
		$data = array(
			'member_id'=>$uid,
			'add_time'=>TIMESTAMP,
			'common_goods_id'=> $common_id,
			'goods_name'=>$goods['goods_name'],
			'goods_image'=>$goods['goods_image_real'],
			'log_price'=>$goods['goods_price'],
			'member_name'=>$this->member_info['member_mobile'],
		);
		$result = FootPrintService::FootPrint($data);
		$this->returnJson(0,'添加成功~');
	}
	
	function getFootPrint() {
		$this->getMember();
		$uid= $this->member_info['member_id'];
		$result = FootPrintService::getFootPrint($uid,$page = 1,$prepage=10);
		$this->returnJson(0,'success',array('list'=>$result));
	}
}