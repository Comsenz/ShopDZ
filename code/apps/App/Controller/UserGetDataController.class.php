<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\GoodsService;
use Common\Helper\CacheHelper;
class UserGetDataController extends BaseController {
    /**
     * 商品详情
     */
	public function getData(){
		$getcode = I('param.getcode');
		$preCash = CacheHelper::getCachePre('user_getdata').$getcode;
		$cashdata = json_decode(S($preCash),true);
		if(!empty($cashdata)){//获取缓存
			return $this->returnJson(1,'success',$cashdata);
		}
		if(!empty($getcode)){
			$dateinfo = M('user_getdata')->where(array('getcode'=>$getcode))->find();
			$datalist = unserialize($dateinfo['shopdata']);
			if(!empty($dateinfo)){
				$goodsdata = array();
				$goodsdata['modename'] = $dateinfo['modename'];
				$goodsdata['goodsnum'] = $dateinfo['goodsnum'];
				foreach($datalist as $k =>$v){
					$goodsinfo = M('goods_common')->where(array('goods_common_id'=>$v['goodsid']))->find();
					$goodsdata['goodsinfo'][$k]['goods_id'] = $v['goodsid'];
					$goodsdata['goodsinfo'][$k]['goods_imgsrc'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['url'];
					$goodsdata['goodsinfo'][$k]['goods_order'] = $v['imgorder'];
					$goodsdata['goodsinfo'][$k]['goods_price'] = $goodsinfo['goods_price'];
					$goodsdata['goodsinfo'][$k]['goods_name'] = $goodsinfo['goods_name'];
					$goodsdata['goodsinfo'][$k]['goods_stock '] = M('goods')->where(array('goods_common_id'=>$v['goodsid']))->sum('goods_storage');
				}
				$preCash = CacheHelper::getCachePre('user_getdata').$getcode;
				S($preCash,json_encode($goodsdata)); //添加缓存
				return $this->returnJson(0,'success',$goodsdata);
			}else{
				return $this->returnJson(0,'success',array());
			}
		}else{
			return $this->returnJson(1,'参数错误！');
		}
	}

}