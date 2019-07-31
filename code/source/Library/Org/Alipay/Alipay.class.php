<?php
nameSpace Org\Alipay;

use Org\Alipay\common\AopClient;
use Org\Alipay\common\AlipayRequestParam;

use Org\Alipay\result\AlipayF2FPayResult;
use Org\Alipay\result\AlipayF2FPrecreateResult;
use Org\Alipay\result\AlipayF2FQueryResult;
use Org\Alipay\result\AlipayF2FRefundResult;

class Alipay{

	public function getToken($request){
		//通过code获得token
		if (!isset($_GET['app_auth_code'])){
			//触发返回code码
			$str = $_SERVER['REQUEST_URI'];
			$str = str_replace('?','/',$str);
			$str = str_replace('&','/',$str);
			$str = str_replace('=','/',$str);
			$scheme = strtolower($_SERVER['HTTPS']) == "on" ? 'https' : 'http';
			$baseUrl = $scheme.'://'.$_SERVER['HTTP_HOST'].$str;

			$urlObj["app_id"] = $request->appid;;
			$urlObj["redirect_uri"] = "$baseUrl";

			foreach ($urlObj as $key => $value) {
				$buff .= $key . "=" . $value . "&";
			}
			$buff = trim($buff, "&");
			$url = "https://openauth.alipay.com/oauth2/appToAppAuth.htm?".$buff;
			Header("Location: $url");
			exit();
		} else {
			//获取code码，以获取token
		    $code = $_GET['app_auth_code'];
		    $request->getAlipayToken($code);
		    $response = $this->aopclientRequestExecute($request);
			return $result;
		}
	}
	
	// 手机网站支付
	public function wapPay($request) {
		$aop = new AopClient();
		$aop->gatewayUrl = $request->gateway_url;
		$aop->appId = $request->appid;
		$aop->rsaPrivateKeyFilePath = $request->private_key_path;
		//$aop->alipayPublicKey = $this->alipay_public_key;
		$aop->alipayPublicKey = $request->alipay_public_key_path;
		$aop->apiVersion = "1.0";
		$aop->postCharset = $request->charset;

		$aop->format=$request->format;
		// 开启页面信息输出
		$aop->debugInfo=true;

		$response = $aop->pageExecute($request);
		$this->writeLog(' 发起支付，支付订单号： '.$request->bizParas['out_trade_no']);
		return $response;
	}
	// 回跳验证
	public function wapPayReturn($request, $succeeurl, $failurl){
		// 写日志
		$get = $_GET;
		$get['app_id'] = $request->appid;
		$get['seller_id'] = $request->sellerId;
		$aop = new AopClient();
		$result = $aop->rsaCheckV1($get,'');
		// 判断成功失败
		$html = '';
		if($result){
			$html .= "<script type='text/javascript'>";
			$html .= "window.parent.location.href = '{$succeeurl}'";
			$html .= "</script>";
		}else{
			$html .= "<script type='text/javascript'>";
			$html .= "window.parent.location.href = '{$failurl}'";
			$html .= "</script>";
		}
		return $html;
	}
	// 支付成功回调通知

	public function wapPayNotify($request){
		$post = $_POST;
		$post['app_id'] = $request->appid;
		$post['seller_id'] = $request->sellerId;
		$post['total_amount'] = $request->bizParas['total_amount'];
		$status = $post['trade_status'];
		$aop = new AopClient();
		$result = $aop->rsaCheckV1($post,'');
		if(!empty($result) && $result != '0'){
			// 判断成功失败
			switch ($status){
				case "TRADE_SUCCESS":
					// 写日志
					$this->writeLog(" 支付成功，支付订单号： " . $post['out_trade_no']);
					return 'success';
					break;
				case "TRADE_CLOSED":
					// 写日志
					$this->writeLog(" 支付失败，支付订单号： " . $post['out_trade_no']);
					return "fail";
					break;
			}
		}else{
			return false;
		}
	}
	// 当面付2.0条码支付(带轮询逻辑)
	public function barPay($request) {
		$mainParas = $request->getAlipayMainParas();//公共参数
		$bizParas = $request->getBizParas();//业务参数 数组
		$bizContent = $request->getBizContent();//业务参数 字符串(json)
		$outTradeNo = $bizParas['out_trade_no'];
		$appAuthToken = $bizParas['auth_code'];

		$this->writeLog($bizContent);//写日志

		$response = $this->aopclientRequestExecute ( $request , NULL , $appAuthToken);

		//获取alipay_trade_pay_response对象数据,方便后续处理
		$response = $response->alipay_trade_pay_response;

		$result = new AlipayF2FPayResult($response);

		if (!empty($response)&&("10000" == $response->code)) {
			// 支付交易明确成功
			$result->setTradeStatus("SUCCESS");

		} elseif (!empty($response)&&("10003" == $response->code)) {
			// 返回用户处理中，则轮询查询交易是否成功，如果查询超时，则调用撤销
			$queryContentBuilder = new AlipayRequestParam();
			$queryContentBuilder->setOutTradeNo($outTradeNo);
			$queryContentBuilder->setAppAuthToken($appAuthToken);

			$loopQueryResponse = $this->loopQueryResult($queryContentBuilder);
			return $this->checkQueryAndCancel($outTradeNo, $appAuthToken, $result, $loopQueryResponse);

		} elseif ($this->tradeError($response)) {
			// 系统错误或者网络异常未响应，则查询一次交易，如果交易没有支付成功，则调用撤销
			$queryContentBuilder = new AlipayRequestParam();
			$queryContentBuilder->setOutTradeNo($outTradeNo);
			$queryContentBuilder->setAppAuthToken($appAuthToken);

			$queryResponse = $this->query($queryContentBuilder);

			return $this->checkQueryAndCancel($outTradeNo, $appAuthToken, $result, $queryResponse);

		} else {
			// 其他情况表明该订单支付明确失败
			$result->setTradeStatus("FAILED");
		}

		return $result;

	}
	// 当面付2.0消费查询
	public function queryTradeResult($req){
		$response = $this->query($req);
		$result = new AlipayF2FQueryResult($response);

		if($this->querySuccess($response)){
			// 查询返回该订单交易支付成功
			$result->setTradeStatus("SUCCESS");

		} elseif ($this->tradeError($response)){
			//查询发生异常或无返回，交易状态未知
			$result->setTradeStatus("UNKNOWN");
		} else {
			//其他情况均表明该订单号交易失败
			$result->setTradeStatus("FAILED");
		}
		return $result;

	}
	// 当面付2.0消费退款,$req为对象变量
	public function refund($request) {
		$bizContent = $request->getBizContent();

		$this->writeLog($bizContent);
		
		$response = $this->aopclientRequestExecute ( $request , NULL ,$req->getAppAuthToken());

		$response = $response->alipay_trade_refund_response;

		$result = new AlipayF2FRefundResult($response);
		if(!empty($response)&&("10000"==$response->code)){
			$result->setTradeStatus("SUCCESS");
		} elseif ($this->tradeError($response)){
			$result->setTradeStatus("UNKNOWN");
		} else {
			$result->setTradeStatus("FAILED");
		}

		return $result;
	}
	//当面付2.0预下单(生成二维码,带轮询)
	public function qrPay($request) {

		$bizContent = $request->getBizContent();

		// 首先调用支付api
		$response = $this->aopclientRequestExecute ( $request, NULL ,$request->getAppAuthToken() );
		$response = $response->alipay_trade_precreate_response;

		$result = new AlipayF2FPrecreateResult($response);
		if(!empty($response)&&("10000"==$response->code)){
			$result->setTradeStatus("SUCCESS");
		} elseif($this->tradeError($response)){
			$result->setTradeStatus("UNKNOWN");
		} else {
			$result->setTradeStatus("FAILED");
		}

		return $result;

	}
	/**
	 *
	 */
	public function query($request) {
		$biz_content = $request->getBizContent();
		$this->writeLog($biz_content);
		$response = $this->aopclientRequestExecute ( $request , NULL, $request->getAppAuthToken() );


		return $response->alipay_trade_query_response;
	}

	// 轮询查询订单支付结果
	protected function loopQueryResult($queryContentBuilder){
		$queryResult = NULL;
		for ($i=1;$i<$this->MaxQueryRetry;$i++){
			try{
				sleep($this->QueryDuration);
			}catch (Exception $e){
				print $e->getMessage();
				exit();
			}

			$queryResponse = $this->query($queryContentBuilder);
			if(!empty($queryResponse)){
				if($this->stopQuery($queryResponse)){
					return $queryResponse;
				}
				$queryResult = $queryResponse;
			}
		}
		return $queryResult;
	}

	// 判断是否停止查询
	protected function stopQuery($response){
		if("10000"==$response->code){
			if("TRADE_FINISHED"==$response->trade_status||
				"TRADE_SUCCESS"==$response->trade_status||
				"TRADE_CLOSED"==$response->trade_status){
				return true;
			}
		}
		return false;
	}

	// 根据查询结果queryResponse判断交易是否支付成功，如果支付成功则更新result并返回，如果不成功则调用撤销
	private function checkQueryAndCancel($outTradeNo, $appAuthToken, $result, $queryResponse){
		if($this->querySuccess($queryResponse)){
			// 如果查询返回支付成功，则返回相应结果
			$result->setTradeStatus("SUCCESS");
			$result->setResponse($queryResponse);
			return $result;
		}elseif($this->queryClose($queryResponse)){
			// 如果查询返回交易关闭，标记交易失败
			$result->setTradeStatus("FAILED");
			return $result;
		}

		// 如果查询结果不为成功，则调用撤销
		
		$cancelContentBuilder->setAppAuthToken($appAuthToken);
		$cancelContentBuilder->setOutTradeNo($outTradeNo);
		$cancelResponse = $this->cancel($cancelContentBuilder);
		if($this->tradeError($cancelResponse)){
			// 如果第一次同步撤销返回异常，则标记支付交易为未知状态
			$result->setTradeStatus("UNKNOWN");
		}else{
			// 标记支付为失败，如果撤销未能成功，产生的单边帐由人工处理
			$result->setTradeStatus("FAILED");
		}
		return $result;
	}

	// 查询返回“支付成功”
	protected function querySuccess($queryResponse){
		return !empty($queryResponse)&&
				$queryResponse->code == "10000"&&
				($queryResponse->trade_status == "TRADE_SUCCESS"||
					$queryResponse->trade_status == "TRADE_FINISHED");
	}

	// 查询返回“交易关闭”
	protected function queryClose($queryResponse){
		return !empty($queryResponse)&&
		$queryResponse->code == "10000"&&
		$queryResponse->trade_status == "TRADE_CLOSED";
	}

	// 交易异常，或发生系统错误
	protected function tradeError($response){
		return empty($response)||
					$response->code == "20000";
	}
	
	/**
	 * 
	 */
	public function cancel($request) {
		$biz_content= $request->getBizContent();
		$this->writeLog($biz_content);
		
		$response = $this->aopclientRequestExecute ( $request ,NULL ,$request->getAppAuthToken() );
		return $response->alipay_trade_cancel_response;
	}

	/**
	 * 使用SDK执行提交页面接口请求
	 * @param unknown $request
	 * @param string $token
	 * @param string $appAuthToken
	 * @return string $$result
	 *
	 */
	private function aopclientRequestExecute($request, $token = NULL, $appAuthToken = NULL) {

		$aop = new AopClient();
		$aop->gatewayUrl = $request->gateway_url;
		$aop->appId = $request->appid;
		$aop->rsaPrivateKeyFilePath = $request->private_key_path;
		//$aop->alipayPublicKey = $this->alipay_public_key;
		$aop->alipayPublicKey = $request->alipay_public_key_path;
		// $aop->rsaPrivateKey = $request->private_key;
		// //$aop->alipayPublicKey = $this->alipay_public_key;
		// $aop->alipayrsaPublicKey = $request->alipay_public_key;
		$aop->apiVersion = "1.0";
		$aop->postCharset = $request->charset;


		$aop->format=$request->format;
		// 开启页面信息输出
		$aop->debugInfo=true;
		$result = $aop->execute($request,$token,$appAuthToken);

		//打开后，将url形式请求报文写入log文件
		$this->writeLog("response: ".var_export($result,true));
		return $result;
	}
	/**
	 * 写日志
	 */
	public function writeLog($text) {
		// $text=iconv("GBK", "UTF-8//IGNORE", $text);
		//$text = characet ( $text );
		$fileName = date('Y-m').'-pay';
		file_put_contents ( APP_ROOT."/source/Library/Org/Alipay/log/".$fileName.".txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
	}
	/** *利用google api生成二维码图片
	 * $content：二维码内容参数
	 * $size：生成二维码的尺寸，宽度和高度的值
	 * $lev：可选参数，纠错等级
	 * $margin：生成的二维码离边框的距离
	 */
	function create_erweima($content, $size = '200', $lev = 'L', $margin= '0') {
		$content = urlencode($content);
		$image = '<img src="http://chart.apis.google.com/chart?chs='.$size.'x'.$size.'&amp;cht=qr&chld='.$lev.'|'.$margin.'&amp;chl='.$content.'"  widht="'.$size.'" height="'.$size.'" />';
		return $image;
	}
}