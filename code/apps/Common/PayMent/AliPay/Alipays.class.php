<?php
namespace Common\PayMent\AliPay;
class Alipays {
	//商户网关网址路径
	private $paypath = '';
	//合作身份者ID，签约账号
	private $partner = '2088421270143336';
	// MD5密钥，安全检验码
	private $key = '';
	//用户请求的设备
	private $phone = '';
	//支付宝配置
	public $alipay_config = array();

	public function __construct($param){
		$this->paypath = trim(SITE_URL,'/');
		$this->partner = $param['pid'];
		$this->key = $param['key'];
		$this->phone = $param['phone'];
		$this->alipay_config = $this->aliConfig();
	}

  	private function aliConfig(){
  		$alipay_config = array();
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		//作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
		$alipay_config['partner']   = $this->partner;
		//款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
		$alipay_config['seller_id'] = $alipay_config['partner'];
		// MD5密钥，安全检验码，由数字和字母组成的32位字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
		$alipay_config['key']     = $this->key;
		// 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
		$alipay_config['notify_url'] = $this->paypath."/api.php/Payment/alipayNotify";
		// 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
		$alipay_config['return_url'] = $this->paypath."/api.php/Payment/alipayReturn";
		//签名方式
		$alipay_config['sign_type']    = strtoupper('MD5');
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');

		// 退款日期 时间格式 yyyy-MM-dd HH:mm:ss
		//date_default_timezone_set('PRC');//设置当前系统服务器时间为北京时间，PHP5.1以上可使用。
		//$alipay_config['refund_date']=date("Y-m-d H:i:s",time());

		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = $this->paypath.'/apps/Common/PayMent/AliPay/cacert.pem';
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
		// 支付类型 ，无需修改
		$alipay_config['payment_type'] = "1";
		// 产品类型，即时支付：create_direct_pay_by_user;手机网站：alipay.wap.create.direct.pay.by.user
		if($this->phone){
			$alipay_config['service'] = "alipay.wap.create.direct.pay.by.user";
		} else {
			$alipay_config['service'] = "create_direct_pay_by_user";
		}
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		//↓↓↓↓↓↓↓↓↓↓ 请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		// 防钓鱼时间戳  若要使用请调用类文件submit中的query_timestamp函数
		$alipay_config['anti_phishing_key'] = "";
		// 客户端的IP地址 非局域网的外网IP地址，如：221.0.0.1
		$alipay_config['exter_invoke_ip'] = "";
		//↑↑↑↑↑↑↑↑↑↑请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		return $alipay_config;
	}

	/**
	 *  支付宝发送支付请求的数据
	 *	@param array $order 商品信息
	 *	@return array $parameter 拼装了支付宝配置文件和商品信息的数据
	 */
	public function aliPayApi($order=array()){
	    /**************************请求参数**************************/
	        //商户订单号，商户网站订单系统中唯一订单号，必填
	        $out_trade_no = $order['order_sn'];

	        //订单名称，必填
	        $subject = '商品订单'.$order['pay_sn'];

	        //付款金额，必填
	        $total_fee = $order['order_amount'];

	    	//收银台页面上，商品展示的超链接，必填
        	$show_url = SITE_URL .'wap/orderfail.html?order_sn='.$order['order_sn'];

	        //商品描述，可空
	        $body = $order['pay_sn'];
	    /************************************************************/
	    //构造要请求的参数数组，无需改动
		return $parameter = array(
		        "service"       	=> $this->alipay_config['service'],
		        "partner"       	=> $this->alipay_config['partner'],
		        "seller_id" 		=> $this->alipay_config['seller_id'],
		        "payment_type"  	=> $this->alipay_config['payment_type'],
		        "notify_url"  		=> $this->alipay_config['notify_url'],
		        "return_url"  		=> $this->alipay_config['return_url'],
		        "anti_phishing_key"	=>$this->alipay_config['anti_phishing_key'],
		        "exter_invoke_ip"	=>$this->alipay_config['exter_invoke_ip'],
		        "_input_charset"  	=> trim(strtolower($this->alipay_config['input_charset'])),
		        'it_b_pay'			=> '10m',// 支付超时时间
		        'app_pay'			=> 'Y',
		        /*商品详情*/
		        "out_trade_no"  	=> $out_trade_no,
		        "subject" 			=> $subject,
		        "total_fee" 		=> $total_fee,
		        "show_url"			=> $show_url,
		        "body"  			=> $body
		        //其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.kiX33I&treeId=62&articleId=103740&docType=1
		            //如"参数名"=>"参数值"
		    );
	}

	/**
	 *  支付宝发送退款请求的数据
	 *	@param array $order 商品信息
	 *	@return array $parameter 拼装了支付宝配置文件和商品信息的数据
	 */
	public function aliRefundApi($order=array()){
	    /**************************请求参数**************************/
		    //批次号，必填，格式：当天日期[8位]+序列号[3至24位]，如：201603081000001
		        $batch_no = $_POST['WIDbatch_no'];
		    //退款笔数，必填，参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）
		        $batch_num = $_POST['WIDbatch_num'];
		    //款详细数据，必填，格式（支付宝交易号^退款金额^备注），多笔请用#隔开
		        $detail_data = $_POST['WIDdetail_data'];
		/************************************************************/
		//构造要请求的参数数组，无需改动
		return $parameter = array(
				"service" 			=> trim($this->alipay_config['service']),
				"partner" 			=> trim($this->alipay_config['partner']),
				"notify_url"		=> trim($this->alipay_config['notify_url']),
				"seller_user_id"	=> trim($this->alipay_config['seller_user_id']),
				"refund_date"		=> trim($this->alipay_config['refund_date']),
				"_input_charset"	=> trim(strtolower($this->alipay_config['input_charset'])),
				"batch_no"			=> $batch_no,
				"batch_num"			=> $batch_num,
				"detail_data"		=> $detail_data
		);
	}

	/**
	 *  支付宝建立请求
	 *	@param array $order 商品信息
	 *	@return string $html 建立请求的html页面代码
	 */
	public function aliRequest($order=array(), $status = 'pay'){
		$parameter = array();
		/*需要传参做判断是支付还是退款*/
		if($status = 'pay'){
        	$parameter = $this->aliPayApi($order);
        }
        $alipaySubmit = new \Common\PayMent\AliPay\AlipaySubmit($this->alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        $html_head = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>支付宝支付</title></head>';
        $html_foot = '</body></html>';
        return $html_head.$html_text.$html_foot;
	}

	/**
	 * 服务器端通知
	 * @return array 返回验证结果 和 支付宝返回的数据
	 */
	public function alipayNotify(&$data){
		$data = array('order_sn'=>'','trade_no'=>'');
        //计算得出通知验证结果
        $alipayNotify = new \Common\PayMent\AliPay\AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyNotify($_POST);
        if($verify_result) {//验证成功
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];

            if($_POST['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序
            	
            	$code = 2;//表示交易已经完成

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
            //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
                //如果有做过处理，不执行商户的业务程序

                $code = 1;//表示交易成功
                $data['order_sn'] = $out_trade_no;
        		$data['trade_no'] = $trade_no;
                  
            //注意：
            //付款完成后，支付宝系统发送该交易状态通知

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";     //请不要修改或删除
        } else {
	        //验证失败
	        $code = 0;
	        echo "fail";
	        //调试用，写文本函数记录程序运行情况是否正常
	        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
	}

	/**
	 * 客户端通知
	 * @return array 返回验证结果 和 支付宝返回的数据
	 */
	public function alipayReturn($succeedurl, $defeatedurl){
		//计算得出通知验证结果
            $alipayNotify = new \Common\PayMent\AliPay\AlipayNotify($this->alipay_config);
            $verify_result = $alipayNotify->verifyReturn($_GET);
        if($verify_result) {//验证成功
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号

            $out_trade_no = $_GET['out_trade_no'];

            //支付宝交易号

            $trade_no = $_GET['trade_no'];

            //交易状态
            $trade_status = $_GET['trade_status'];

            $order['order_sn'] = $out_trade_no;
            $order['trade_no']= $trade_no;
            $order['status']   = $trade_status;

            if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //如果有做过处理，不执行商户的业务程序
            } else {
              echo "trade_status=".$_GET['trade_status'];
            }
            echo "<script type='text/javascript'>";
            echo "window.location.href = '{$succeedurl}?order_sn={$out_trade_no}'";
			echo "</script>";
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "<script type='text/javascript'>";
            echo "window.location.href = '{$defeatedurl}?order_sn={$out_trade_no}'";
			echo "</script>";
        }
	}
		

}





?>