<?php
namespace Common\PayMent\WxPay;
use Common\PayMent\WxPay\WxPayApi;
use Common\PayMent\WxPay\WxPayOrderQuery;
/**
 * 
 * 回调基础类
 * @author widyhu
 *
 */
class WxPayNotify extends \Common\PayMent\WxPay\WxPayDataBase
{	
	function __construct($payment_config) {
		define('APPID',$payment_config['appid']);
		define('MCHID',$payment_config['mchid']);
		define('KEY',$payment_config['key']);
		define('APPSECRET',$payment_config['appsecret']);
	}
	
	function wxpayNotify() {
		$this->Handle();
		if($this->values['return_code'] == "SUCCESS"){
			return array('order_sn'=>$this->values['out_trade_no'],'trade_no'=>$this->values['transaction_id']);
		}
		return array('order_sn'=>$this->values['out_trade_no'],'trade_no'=>'');
	}

	/**
	 * 
	 * 回调入口
	 * @param bool $needSign  是否需要签名输出
	 */
	final public function Handle($needSign = true)
	{
		$msg = "OK";
		//当返回false的时候，表示notify中调用NotifyCallBack回调失败获取签名校验失败，此时直接回复失败
		$result = WxpayApi::notify(array($this, 'NotifyCallBack'), $msg);
		if($result == false){
			$this->SetReturn_code("FAIL");
			$this->SetReturn_msg($msg);
			$this->ReplyNotify(false);
			return;
		} else {
			//该分支在成功回调到NotifyCallBack方法，处理完成之后流程
			$this->SetReturn_code("SUCCESS");
			$this->SetReturn_msg("OK");
		}
		$this->ReplyNotify($needSign);
	}
	
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		//Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}

	/**
	 * 
	 * 回调方法入口，子类可重写该方法
	 * 注意：
	 * 1、微信回调超时时间为2s，建议用户使用异步处理流程，确认成功之后立刻回复微信服务器
	 * 2、微信服务器在调用失败或者接到回包为非确认包的时候，会发起重试，需确保你的回调是可以重入
	 * @param array $data 回调解释出的参数
	 * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
	 * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
	 */
	public function NotifyProcess($data, &$msg)
	{
		//TODO 用户基础该类之后需要重写该方法，成功的时候返回true，失败返回false
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
	
	/**
	 * 
	 * notify回调方法，该方法中需要赋值需要输出的参数,不可重写
	 * @param array $data
	 * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
	 */
	final public function NotifyCallBack($data)
	{
		$msg = "OK";
		$result = $this->NotifyProcess($data, $msg);
		
		if($result == true){
			$this->values['out_trade_no'] = $data['out_trade_no'];
			$this->values['transaction_id'] = $data['transaction_id'];
			$this->SetReturn_code("SUCCESS");
			$this->SetReturn_msg("OK");
		} else {
			$this->SetReturn_code("FAIL");
			$this->SetReturn_msg($msg);
		}
		return $result;
	}
	
	/**
	 * 
	 * 回复通知
	 * @param bool $needSign 是否需要签名输出
	 */
	final private function ReplyNotify($needSign = true)
	{
		//如果需要签名
		if($needSign == true && 
			$this->GetReturn_code($return_code) == "SUCCESS")
		{
			$this->SetSign();
		}
		WxpayApi::replyNotify($this->ToXml());
	}

	/**
	 * 
	 * 设置错误码 FAIL 或者 SUCCESS
	 * @param string
	 */
	public function SetReturn_code($return_code)
	{
		$this->values['return_code'] = $return_code;
	}
	
	/**
	 * 
	 * 获取错误码 FAIL 或者 SUCCESS
	 * @return string $return_code
	 */
	public function GetReturn_code()
	{
		return $this->values['return_code'];
	}

	/**
	 * 
	 * 设置错误信息
	 * @param string $return_code
	 */
	public function SetReturn_msg($return_msg)
	{
		$this->values['return_msg'] = $return_msg;
	}
	
	/**
	 * 
	 * 获取错误信息
	 * @return string
	 */
	public function GetReturn_msg()
	{
		return $this->values['return_msg'];
	}
	
	/**
	 * 
	 * 设置返回参数
	 * @param string $key
	 * @param string $value
	 */
	public function SetData($key, $value)
	{
		$this->values[$key] = $value;
	}
}