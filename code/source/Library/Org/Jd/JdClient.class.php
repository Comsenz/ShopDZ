<?php
namespace Org\Jd;

class JdClient{
	public $serverUrl = "http://gw.api.360buy.net/routerjson";

	public $accessToken;

	public $connectTimeout = 0;

	public $readTimeout = 0;

	public $appKey;

	public $appSecret;
	
	public $version = "2.0";
	
	public $format = "json";

	private $charset_utf8 = "UTF-8";

	private $json_param_key = "360buy_param_json";

	public $AccreditUrl = 'https://oauth.jd.com/oauth/authorize?';

	public $callbackUrl = '';
	/**
	 * 拉取授权
	 */
	public function getAccredit(){
		$apiParams = array(
			'response_type'	=> 'code',
			//  必须  此流程下，该值固定为code
			'client_id'		=> $this->appKey,
			//  必须  即创建应用时的Appkey（从JOS控制台->管理应用中获取）
			'redirect_uri'	=> $this->callbackUrl,
			//  必须  即应用的回调地址，必须与创建应用时所填回调页面url一致
			'state'			=> 1,
			//  可选  状态参数，由ISV自定义，颁发授权后会原封不动返回
			'scope'			=> 'read',
			//  可选  权限参数，API组名串。多个组名时，用"，"分隔，目前支持参数值：read
			'view'			=> ''
			//  可选  移动端授权，该值固定为wap；非移动端授权，无需传值
		);

		//发起HTTP请求
		$url = $this->AccreditUrl.$this->makeParam($apiParams);
		header("Location:{$url}");
	}

	/**
	 * 获取accessToken
	 */
	public function getAccessToken($code){
		$url ="https://oauth.jd.com/oauth/token?";
		$apiParam = array(
			'grant_type'	=> 'authorization_code',
			"client_id"		=> $this->appKey,
			"client_secret" => $this->appSecret,
			"scope"			=> 'read',
			"redirect_uri"	=> $this->callbackUrl,
			"code"			=> $code,
			"state"			=> "1234"
		);
		//发起HTTP请求
		try{
			$resp = $this->curl($url, $apiParam);
		}catch (Exception $e){
			$result->code = $e->getCode();
			$result->msg = $e->getMessage();
			return $result;
		}
		$result = json_decode($resp,true);
		$this->accessToken = $result['access_token'];
		return $result;
		//如果app没有发布到服务市场，授权时长为24小时
		//保存到session 或者cookie 或者数据库
		
	}

	/**
	 * 拼装url参数
	 */
	private function makeParam($param){
		$urlstr = array();
		foreach ($param as $key => $value) {
			if(empty($value)){
				continue;
			}
			if($key == 'sign'){
				continue;
			}
			$urlstr[] = $key.'='.$value;
		}
		return implode('&',$urlstr);
	}

	protected function generateSign($params)
	{
		ksort($params);
		$stringToBeSigned = $this->appSecret;
		foreach ($params as $k => $v)
		{
			if("@" != substr($v, 0, 1))
			{
				$stringToBeSigned .= "$k$v";
			}
		}
		unset($k, $v);
		$stringToBeSigned .= $this->appSecret;
		return strtoupper(md5($stringToBeSigned));
	}

	public function curl($url, $postFields = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($this->readTimeout) {
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
		}
		if ($this->connectTimeout) {
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
		}
		//https 请求
		if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

		if (is_array($postFields) && 0 < count($postFields))
		{
			$postBodyString = "";
			$postMultipart = false;
			foreach ($postFields as $k => $v)
			{
				if("@" != substr($v, 0, 1))//判断是不是文件上传
				{
					$postBodyString .= "$k=" . urlencode($v) . "&"; 
				}
				else//文件上传用multipart/form-data，否则用www-form-urlencoded
				{
					$postMultipart = true;
				}
			}
			unset($k, $v);
			curl_setopt($ch, CURLOPT_POST, true);
			if ($postMultipart)
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			}
			else
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
			}
		}
		$reponse = curl_exec($ch);
		
		if (curl_errno($ch))
		{
			throw new Exception(curl_error($ch),0);
		}
		else
		{
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode)
			{
				throw new Exception($reponse,$httpStatusCode);
			}
		}
		curl_close($ch);
		return $reponse;
	}

	public function execute($request, $access_token = null)
	{
		//组装系统参数
		$sysParams["app_key"] = $this->appKey;//应用的app_key
		$sysParams["v"] = $this->version;//API协议版本，可选值:2.0.
		$sysParams["method"] = $request->getApiMethodName();//API接口名称
		$sysParams["timestamp"] = date("Y-m-d H:i:s");//时间戳，格式为yyyy-MM-ddHH:mm:ss，例如：2011-06-16 13:23:30。京东API服务端允许客户端请求时间误差为6分钟
		if (null != $access_token)
		{
			$sysParams["access_token"] = $access_token;
		}

		//获取业务参数
		$apiParams = $request->getApiParas();
		$sysParams[$this->json_param_key] = $apiParams;

		//签名
		$sysParams["sign"] = $this->generateSign($sysParams);
		//系统参数放入GET请求串
		$requestUrl = $this->serverUrl;
		foreach ($sysParams as $sysParamKey => $sysParamValue)
		{
			$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
		}
		//发起HTTP请求
		try
		{
			$resp = $this->curl($requestUrl, $apiParams);
		}
		catch (Exception $e)
		{
			$result->code = $e->getCode();
			$result->msg = $e->getMessage();
			return $result;
		}

		//解析JD返回结果
		$respWellFormed = false;
		if ("json" == $this->format)
		{
			$respObject = json_decode($resp);
			if (null !== $respObject)
			{
				$respWellFormed = true;
				foreach ($respObject as $propKey => $propValue)
				{
					$respObject = $propValue;
				}
			}
		}
		else if("xml" == $this->format)
		{
			$respObject = @simplexml_load_string($resp);
			if (false !== $respObject)
			{
				$respWellFormed = true;
			}
		}

		//返回的HTTP文本不是标准JSON或者XML，记下错误日志
		if (false === $respWellFormed)
		{
			$this->logCommunicationError($sysParams["method"],$requestUrl,"HTTP_RESPONSE_NOT_WELL_FORMED",$resp);
			$result->code = 0;
			$result->msg = "HTTP_RESPONSE_NOT_WELL_FORMED";
			return $result;
		}

		//如果JD返回了错误码，记录到业务错误日志中
		if (isset($respObject->code))
		{
			// $logger = new LtLogger;
			// $logger->conf["log_file"] = rtrim(JD_SDK_WORK_DIR, '\\/') . '/' . "logs/top_biz_err_" . $this->appKey . "_" . date("Y-m-d") . ".log";
			// $logger->log(array(
			// 	date("Y-m-d H:i:s"),
			// 	$resp
			// ));
		}
		return $respObject;
	}

	public function exec($paramsArray)
	{
		if (!isset($paramsArray["method"]))
		{
			trigger_error("No api name passed");
		}
		$inflector = new LtInflector;
		$inflector->conf["separator"] = ".";
		$requestClassName = ucfirst($inflector->camelize(substr($paramsArray["method"], 7))) . "Request";
		if (!class_exists($requestClassName))
		{
			trigger_error("No such api: " . $paramsArray["method"]);
		}

		$session = isset($paramsArray["session"]) ? $paramsArray["session"] : null;

		$req = new $requestClassName;
		foreach($paramsArray as $paraKey => $paraValue)
		{
			$inflector->conf["separator"] = "_";
			$setterMethodName = $inflector->camelize($paraKey);
			$inflector->conf["separator"] = ".";
			$setterMethodName = "set" . $inflector->camelize($setterMethodName);
			if (method_exists($req, $setterMethodName))
			{
				$req->$setterMethodName($paraValue);
			}
		}
		return $this->execute($req, $session);
	}
}