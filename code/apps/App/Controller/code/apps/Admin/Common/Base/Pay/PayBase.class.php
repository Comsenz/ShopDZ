<?php
namespace Common\Base\Pay;
/*
*插件类支付回调基类
*
*/
abstract class PayBase{
	private function pluginwap() {
		$root  =  static :: getFileDir();//获取子类的文件路径
		list(,$module) = explode('plugins',$root);
		return '/plugins/'.trim($module,'/,\\').'/wap/';
	}
	function successurl() {//支付成功跳转地址
		$wap = $this->pluginwap();
		return $wap.'success.html';
	}
	function failurl() {
		$wap = $this->pluginwap();
		return $wap.'fail.html';//失败地址
	}
	function detailurl() {
		$wap = $this->pluginwap();
		return $wap.'detail.html';//失败地址
	}
	abstract function selectOrderBySn($order_sn); //查询订单接口
	abstract function saveOrder($orderinfo); //支付成功后回调保存订单接口
	abstract function payresult($group_sn);//支付成功返回成功页面后的 查询订单联系人信息接口
	abstract function selectOrderByReturnSn($return_sn) ; //根据退款单号查询信息
	abstract function saveReturnOrder($data,$return_type) ; //根据退款单号查询信息 return_type 退款成返回的状态 true 成功
	
	abstract function getFileDir();
	/*子类实现如下
	*	public function getFileDir(){
			return dirname(__FILE__);
		}
	*
	*
	*
	*/
}