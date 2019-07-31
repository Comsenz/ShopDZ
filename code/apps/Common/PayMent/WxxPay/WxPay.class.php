<?php
namespace Common\PayMent\WxxPay;
use Common\PayMent\WxxPay\JsApiPay;
use Common\PayMent\WxxPay\WxPayDataBase; 
use Common\PayMent\WxxPay\WxPayUnifiedOrder;
use Common\PayMent\WxxPay\WxPayNativePay;
use Common\PayMent\WxxPay\WxPayApi;
class WxPay {
	protected $orderInfo = array();
	public $timeExpire;
	protected $config = array(
				'finishUrl'=>"",
				'undoneUrl'=>"",
		);
	function __construct($payment_config,$urlConfig = array()) {
		define('APPID',$payment_config['appid']);
		define('MCHID',$payment_config['mchid']);
		define('KEY',$payment_config['key']);
		define('APPSECRET',$payment_config['appsecret']);
		$this->config = array(
				'finishUrl'=>!$urlConfig['finishUrl'] ? SITE_URL ."wap/ordersuccess.html" : $urlConfig['finishUrl'],
				'undoneUrl'=>!$urlConfig['undoneUrl'] ? SITE_URL ."wap/orderfail.html" : $urlConfig['undoneUrl'],
		);
	}
	
	function setOrderInfo($info) {
		$this->orderInfo = $info;
	}
	/**
	 * 生成签名
	 * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
	 */
	public function MakeSign($data)
	{
		//签名步骤一：按字典序排序参数
		ksort($data);
		foreach ($data as $k => $v)
		{
			if($k != "sign" && $v != "" && !is_array($v)){
				$string .= $k . "=" . $v . "&";
			}
		}
		
		$string = trim($string, "&");
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=".get_wxxpay_config('key');
		// $string = $string . "&key=shopDzxiaochengxuzhifu01appzhuan";
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		return $result;
	}
	public function Vitify($data,$sign){
		$newsign = $this->MakeSign($data);
		if($sign == $newsign){
			return true;
		}
		return false;
	}

	/**
     * 写日志
     */
    public function WriteLog($text) {
        $month = date('Y-m');
        file_put_contents ( APP_ROOT."/data/Logs/admin_pay/".$month.".txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
    }

	function JsApi(){
		$input = new WxPayUnifiedOrder();
		$input->SetBody('商品订单'.$this->orderInfo['order_sn']);
		$input->SetAttach("test");
		$input->SetOut_trade_no($this->orderInfo['order_sn']);
		$input->SetTotal_fee($this->orderInfo['order_amount'] * 100);
		$input->SetTime_start(date("YmdHis",TIMESTAMP));
		$input->SetTime_expire(date("YmdHis", TIMESTAMP + $this->timeExpire));
		$input->SetGoods_tag($this->orderInfo['order_sn']);
		$input->SetNotify_url('https://shopdz.shopdz.cn/api.php/Paymentwx/wxxpayNotify');
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($_GET['oid']);
		$result = WxPayApi::unifiedOrder($input);
		// $this->WriteLog(var_export($input,true));
		// $this->WriteLog(var_export($result,true));
		// 验证签名
		$data = array(
			'appid'			=> APPID,
			'mch_id'		=> MCHID,
			'nonce_str'		=> $result['nonce_str'],
			'prepay_id'		=> $result['prepay_id'],
			'result_code'	=> $result['result_code'],
			'return_code'	=> $result['return_code'],
			'return_msg'	=> $result['return_msg'],
			'trade_type'	=> $input->GetTrade_type()
		);
		$res = $this->Vitify($data,$result['sign']);
		$return = array();
		if($res || true){
			// 重拼参数，加签、
			$return['appId'] = APPID;
			$return['nonceStr'] = WxPayApi::getNonceStr();//生成随机字符串
			$return['package'] = "prepay_id=" . $result['prepay_id'];
			$timeStamp = time();
			$return['timeStamp'] = "$timeStamp";
			$return['signType'] = "MD5";
			$return['paySign'] = $this->MakeSign($return);
		}
		return $return;
		
	}
	function nativepay($pay_sn = "123456789"){
		$notify = new WxPayNativePay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody('商品订单'.$this->orderInfo['order_sn']);
		$input->SetAttach("test");
		$input->SetOut_trade_no($this->orderInfo['order_sn']);
		$input->SetTotal_fee($this->orderInfo['order_amount'] * 100);
		$input->SetTime_start(date("YmdHis",TIMESTAMP));
		$input->SetTime_expire(date("YmdHis", TIMESTAMP + $this->timeExpire));
		$input->SetGoods_tag($this->orderInfo['order_sn']);
		$input->SetNotify_url(SITE_URL .'api.php/Payment/wxpayNotify');
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id($this->orderInfo['order_sn']);
		$result = $notify->GetPayUrl($input);
		// 验证签名
		$data = array(
			'appid'			=> APPID,
			'code_url'		=> $result['code_url'],
			'mch_id'		=> MCHID,
			'nonce_str'		=> $result['nonce_str'],
			'prepay_id'		=> $result['prepay_id'],
			'result_code'	=> $result['result_code'],
			'return_code'	=> $result['return_code'],
			'return_msg'	=> $result['return_msg'],
			'trade_type'	=> $input->GetTrade_type()
		);
		$res = $this->Vitify($data,$result['sign']);
		$return = '';
		if($res){
			$return = array('code_url'=>$result['code_url']);
		}
		return $return;
	}

	function pay($openId = '') {
		$tools = new JsApiPay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody('商品订单'.$this->orderInfo['order_sn']);
		$input->SetOut_trade_no($this->orderInfo['order_sn']);
		$input->SetTotal_fee($this->orderInfo['order_amount'] * 100);
		$input->SetTime_start(date("YmdHis",TIMESTAMP));
		$input->SetTime_expire(date("YmdHis", TIMESTAMP + $this->timeExpire));
		//$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
		$input->SetNotify_url(SITE_URL .'api.php/Payment/wxpayNotify');
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = WxPayApi::unifiedOrder($input);
		file_put_contents(__ROOT__.'./wxorder.txt',var_export($order,true),FILE_APPEND);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		
		//获取共享收货地址js函数参数
		//$editAddress = $tools->GetEditAddressParameters();
		    return <<<EOB
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信支付</title>
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			{$jsApiParameters},
			function(res){
				WeixinJSBridge.log(res.err_msg);
				if(res.err_msg == "get_brand_wcpay_request:ok"){
					/*成功的操作*/
					location.href = "{$this->config['finishUrl']}?order_sn={$this->orderInfo['order_sn']}";
				} else {
					location.href = "{$this->config['undoneUrl']}?order_sn={$this->orderInfo['order_sn']}";
				}
			}
		);
	}
	window.onload = function(){
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	};
	</script>
</head>
<body>
</body>
</html>
EOB;
	}
}