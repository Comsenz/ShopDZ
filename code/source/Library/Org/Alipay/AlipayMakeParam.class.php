<?php
nameSpace Org\Alipay;
use Org\Alipay\common\AlipayRequestParam;
/**
 * 构造参数 条码、扫码、手机网站
 */

class AlipayMakeParam extends AlipayRequestParam{

	//支付宝网关地址	https://openapi.alipay.com/gateway.do
	public $gateway_url = "https://openapi.alipay.com/gateway.do";

	//支付宝公钥地址
	public $alipay_public_key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';

	//商户私钥地址
	public $private_key_path;

	//应用id
	public $appid;

	//PID
	public $sellerId;

	//编码格式
	public $charset = "UTF-8";
	
	//重试次数
	public $MaxQueryRetry = '10';
	
	//重试间隔
	public $QueryDuration = '3';

	//返回数据格式
	public $format = "json";

	// (推荐使用，相对时间) 支付超时时间，5m 5分钟
    public $timeExpress = '5m';

	//公共参数 数组
	public $mainParas = array();


	/**
	 * 初始化支付主配置
	 * @param $appid	应用ID
	 * @param $charset	字符集
	 * @param $MaxQueryRetry	重试次数
	 * @param $QueryDuration	重试间隔
	 * @param $notify_url	回调地址
	 */
	public function __construct($alipay_config){
		$this->appid = $alipay_config['app_id'];
		$this->sellerId = $alipay_config['seller_id'];
		$this->private_key_path = $alipay_config['private_key_path']?$alipay_config['private_key_path']:dirname(__FILE__).DIRECTORY_SEPARATOR."app_private_key.pem";
		//$this->alipay_public_key_path = dirname(__FILE__).DIRECTORY_SEPARATOR."app_public_key.pem";
		$alipay_config['charset'] && $this->charset = $alipay_config['charset'];
		$alipay_config['MaxQueryRetry'] && $this->MaxQueryRetry = $alipay_config['MaxQueryRetry'];
		$alipay_config['QueryDuration'] && $this->QueryDuration = $alipay_config['QueryDuration'];
		$alipay_config['notify_url'] && $this->notifyUrl = $alipay_config['notify_url'];

		if(empty($this->appid)||trim($this->appid)==""){
			throw new Exception("appid should not be NULL!");
		}
		if(empty($this->sellerId)||trim($this->sellerId)==""){
			throw new Exception("seller_id should not be NULL!");
		}
		if(empty($this->private_key_path)||trim($this->private_key_path)==""){
			throw new Exception("private_key_path should not be NULL!");
		}
		if(empty($this->alipay_public_key)||trim($this->alipay_public_key)==""){
			throw new Exception("alipay_public_key_path should not be NULL!");
		}
		if(empty($this->charset)||trim($this->charset)==""){
			throw new Exception("charset should not be NULL!");
		}
		if(empty($this->QueryDuration)||trim($this->QueryDuration)==""){
			throw new Exception("QueryDuration should not be NULL!");
		}
		if(empty($this->gateway_url)||trim($this->gateway_url)==""){
			throw new Exception("gateway_url should not be NULL!");
		}
		if(empty($this->MaxQueryRetry)||trim($this->MaxQueryRetry)==""){
			throw new Exception("MaxQueryRetry should not be NULL!");
		}
		$this->AlipayMainParam();
	}

	/**
	 * 拼装公共参数 数组
	 */
	public function AlipayMainParam(){
		$this->mainParas['app_id'] = $this->appid;//开发者APPID
		$this->mainParas['format'] = 'JSON';//接口名称
		$this->mainParas['charset'] = 'UTF-8';//编码格式
		$this->mainParas['sign_type'] = 'RSA';//加密类型
		$this->mainParas['timeExpress'] = $this->timeExpress;//支付超时时间
		$this->mainParas['version'] = '1.0';//调用的接口版本，固定为：1.0
	}
	/**
	 * 获取auto_Token 数组
	 */
	public function getAlipayToken($code,$status = 1){
		$this->setApiMethodName('alipay.open.auth.token.app');
		$this->mainParas['app_id'] = $this->appid;//开发者APPID
		$this->mainParas['method'] = 'alipay.open.auth.token.app';//开发者APPID
		$this->mainParas['format'] = 'JSON';//接口名称
		$this->mainParas['charset'] = 'UTF-8';//编码格式
		$this->mainParas['sign_type'] = 'RSA';//加密类型
		$this->mainParas['timestamp'] = time();//请求时间
		$this->mainParas['version'] = '1.0';//调用的接口版本，固定为：1.0
		$this->bizParas['grant_type'] = $status?'authorization_code':'refresh_token';//
		if($status){
			$this->bizParas['code'] = $code;
		}else{
			$this->bizParas['refresh_token'] = $code;
		}
		$this->setBizContent();//业务参数
	}
	/**
	 * 获取公共参数 数组
	 */
	public function getAlipayMainParas(){

		return $this->mainParas;
	}

	/**
	 * 条码支付公共参数
	 */
	public function AlipayBarPayMainParam($notifyUrl = ''){
		$this->setApiMethodName('alipay.trade.pay');
		$this->setNotifyUrl($notifyUrl);
		$this->mainParas['method'] = 'alipay.trade.pay';//接口名称 
		$this->mainParas['notify_url'] = $notifyUrl;//回调路径
	}

	/**
	 * 扫码支付公共参数
	 */
	public function AlipayScanPayMainParam($notifyUrl = ''){
		$this->setApiMethodName('alipay.trade.precreate');
		$this->setNotifyUrl($notifyUrl);
		$this->mainParas['method'] = 'alipay.trade.precreate';//接口名称 
		$this->mainParas['notify_url'] = $notifyUrl;//回调路径
	}

	/**
	 * 手机网站支付公共参数
	 */
	public function AlipayWapPayMainParam($returnUrl = '',$notifyUrl = ''){
		$this->setApiMethodName('alipay.trade.wap.pay');
		$notifyUrl && $this->setNotifyUrl($notifyUrl);
		$returnUrl && $this->setReturnUrl($returnUrl);
		$this->mainParas['method'] = 'alipay.trade.wap.pay';//接口名称
		$returnUrl && $this->mainParas['return_url'] = $returnUrl;//前台回跳地址
		$notifyUrl && $this->mainParas['notify_url'] = $notifyUrl;//回调路径
	}

	/**
	 * 设置面对面业务参数 数组
	 */
	public function setBizParas($order_info,$goods_info){
		$this->bizParas['seller_id'] = $this->sellerId;//商户签约账号对应的支付宝用户ID
		$this->bizParas['out_trade_no'] = $order_info['out_trade_no'];//商户订单号
		$this->bizParas['subject'] = $order_info['subject'];//订单标题
		$this->bizParas['total_amount'] = $order_info['total_amount'];//订单总金额
		$this->bizParas['discountable_amount'] = $order_info['discountable_amount'];//参与优惠计算的金额 
		$this->bizParas['undiscountable_amount'] = $order_info['undiscountable_amount'];//不参与优惠计算的金额
		$this->bizParas['body'] = $order_info['body'];//订单描述
		$this->bizParas['operator_id'] = $order_info['operator_id'];//商户操作员编号
		$this->bizParas['store_id'] = $order_info['store_id'];//商户门店编号
		$this->bizParas['terminal_id'] = $order_info['terminal_id'];//商户机具终端编号
		$this->bizParas['alipay_store_id'] = $order_info['alipay_store_id'];//支付宝的店铺编号
		$this->bizParas['goods_detail'] = $goods_info;
		// array(
		// 	'goods_id' => $goods_info['goods_id'],//商品的编号
		// 	'goods_name' => $goods_info['goods_name'],//商品名称
		// 	'quantity' => $goods_info['quantity'],//商品数量
		// 	'price' => $goods_info['price'],//商品单价，单位为元
		// 	'show_url' => $goods_info['show_url'],//商品的展示地址  商品主图地址
		// );//订单包含的商品列表信息
	}

	/**
	 * 设置手机业务参数 数组
	 */
	public function setWapBizParas($order_info){
		$this->bizParas['out_trade_no'] = $order_info['out_trade_no'];//商户订单号
		$this->bizParas['subject'] = $order_info['subject'];//订单标题
		$this->bizParas['seller_id'] = $this->sellerId;//商户签约账号对应的支付宝用户ID
		$this->bizParas['total_amount'] = $order_info['total_amount'];//订单总金额
		$this->bizParas['body'] = $order_info['body'];//订单描述
		$this->bizParas['product_code'] = $order_info['out_trade_no'];
		$this->setBizContent();//业务参数
	}
	/**
	 * 条码支付业务参数
	 * @param $auto_code  支付授权码
	 */
	public function barPayParam($auto_code = ''){
		$this->bizParas['scene'] = 'bar_code';//支付场景 条码支付 
		$this->bizParas['auth_code'] = $auto_code;//支付授权码
		if(empty($this->bizParas['auth_code'])||trim($this->bizParas['auth_code'])==""){
			throw new Exception("auth_code should not be NULL!");
		}
	}

	/**
	 * 扫码支付业务参数
	 */
	public function scanPayParam(){
		
		$this->bizParas['buyer_logon_id'] = '';//买家支付宝账号
	}

	/**
	 * 手机网站支付业务参数
	 */
	public function wapPayParam(){

		$this->bizParas['buyer_logon_id'] = '';//买家支付宝账号
	}

	
}