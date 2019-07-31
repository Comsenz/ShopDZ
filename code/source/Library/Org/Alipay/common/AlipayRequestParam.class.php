<?php
nameSpace Org\Alipay\common;
/**
 * 请求业务参数
 */


class AlipayRequestParam
{   
    public $outTradeNo;
    public $appAuthToken;
    public $apiParas = array();
    public $apiMethodName;
    public $prodCode;
    public $apiVersion="1.0";
    public $notifyUrl;
    public $returnUrl;
    public $terminalInfo;
    public $terminalType;
    public $needEncrypt=false;
    //业务参数 数组
    public $bizParas = array();

    //业务参数 字符串 (json)
    public $bizContent;

    public function setOutTradeNo($outTradeNo){
        $this->outTradeNo = $outTradeNo;
        $this->bizParas['out_trade_no'] = $outTradeNo;
    }
    public function getOutTradeNo(){
        return $this->outTradeNo;
    }
    
    public function setAppAuthToken($appAuthToken)
    {
        $this->appAuthToken = $appAuthToken;
        $this->bizParas['app_auth_token'] = $appAuthToken;
    }
    public function getAppAuthToken()
    {
        return $this->appAuthToken;
    }

    public function getApiParas()
    {
        return $this->apiParas;
    }

    public function getApiMethodName()
    {
        return $this->apiMethodName;
    }
    public function setApiMethodName($apiMethodName)
    {
        $this->apiMethodName = $apiMethodName;
    }

    public function getProdCode()
    {
        return $this->prodCode;
    }
    public function setProdCode($prodCode)
    {
        $this->prodCode = $prodCode;
    }

    public function getApiVersion()
    {
        return $this->apiVersion;
    }
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
    }

    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }
    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }


    public function getReturnUrl()
    {
        return $this->returnUrl;
    }
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    public function getTerminalInfo()
    {
        return $this->terminalInfo;
    }
    public function setTerminalInfo($terminalInfo)
    {
        $this->terminalInfo = $terminalInfo;
    }

    public function getTerminalType()
    {
        return $this->terminalType;
    }
    public function setTerminalType($terminalType)
    {
        $this->terminalType = $terminalType;
    }

    public function setNeedEncrypt($needEncrypt)
    {
        $this->needEncrypt=$needEncrypt;
    }
    public function getNeedEncrypt()
    {
        return $this->needEncrypt;
    }

    /**
     * 获取业务参数 字符串(json)
     */
    public function getBizContent(){
        $this->setBizContent();
        return $this->bizContent;
    }
    /**
     * 获取业务参数 字符串(json)
     */
    public function setBizContent(){
        if(!empty($this->bizParas) && empty($this->bizContent)){
            if (version_compare(PHP_VERSION,'5.4.0','<'))
            {
                $str = json_encode($this->bizParas);
                $str = preg_replace_callback(
                    "#\\\u([0-9a-f]{4})#i",
                    function($matchs)
                    {
                        return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
                    },
                    $str
                );
                $this->bizContent = $str;
            }
            else
            {
                $this->bizContent = json_encode($this->bizParas, JSON_UNESCAPED_UNICODE);
            }
        }
        $this->apiParas["biz_content"] = $this->bizContent;
    }

    /**
     * 获取业务参数 数组
     */
    public function getBizParas(){
        return $this->bizParas;
    }
}

?>