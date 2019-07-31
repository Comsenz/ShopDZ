<?php
namespace Common\Service;
use Common\Service\SMS;
class YiMeiSMS implements SMS{
    //发送即时短信
    private $sendSmsUrl = "http://sdk4report.eucp.b2m.cn:8080/sdkproxy/sendsms.action";
    //发送定时短信
    private $timingSmsUrl = "http://sdk4report.eucp.b2m.cn:8080/sdkproxy/sendtimesms.action";
    //获取上行短信
    private $getUpSmsUrl = "http://sdk4report.eucp.b2m.cn:8080/sdkproxy/getmo.action";
    //查询短信余额
    private $selectSelSumUrl = "http://sdk4report.eucp.b2m.cn:8080/sdkproxy/querybalance.action";
    //更改密码
    private $updatePwdUrl = "http://sdk4report.eucp.b2m.cn:8080/sdkproxy/changepassword.action";
    //注册企业信息
    private $registerRegistInfoUrl = "http://sdk4report.eucp.b2m.cn:8080/sdkproxy/registdetailinfo.action";
    //序列号注册
    private $cdkeyRegisterUrl = "http://sdk4report.eucp.b2m.cn:8080/sdkproxy/regist.action";
    //注销序列号
    private $cdkeylogoutUrl = "http://sdk4report.eucp.b2m.cn:8080/sdkproxy/logout.action";
    //充值操作的HTTP
    private $paySmsUrl = "http://sdk4report.eucp.b2m.cn:8080/sdkproxy/chargeup.action";
    //获取状态报告
    private $getStutasReportUrl = "http://sdk4report.eucp.b2m.cn:8080/sdkproxy/getreport.action";
    //用户序列号
    private $uName = "6SDK-EMY-XXXXXXXXXX-JCULL";
    //用户密码
    private $uPwd = "877313";//TODO 密码加密和解密，让账号更安全
    //消息ID
    const SEQID = "11111111111";
    /**
     * 发送请求
     * @param string $url    请求的地址
     * @param array  $fields 要发送的参数
     * @return xml 请求的结果
     */
    private function execPostRequest($url, $fields)
    {
        if (empty($url))
        {
            return false;
        }
        //$fields_string =http_build_query($post_array);
        $fields_string = '';
        foreach ($fields as $key => $value)
        {
            $fields_string .= $key . '=' . $value . '&';
        }
        $fields_string = rtrim($fields_string, '&');

        $linkurl = $url."?".$fields_string;

        //初始化
        $ch = curl_init();

        $timeout = 5;
        curl_setopt ($ch, CURLOPT_URL, $linkurl);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    /**
     * 解析错误信息
     * @param unknown $rs
     * @return array:number string unknown |Ambigous <multitype:, multitype:number string >
     */
    private function analysisResult($rs)
    {
        $arr = $this-> xmlParse($rs);
        if ($arr['error'] == 0)
        {
            $arr = ["status" => 1, "info" => "操作成功！","data" => $arr['message']];
            return $arr;
        }

        $array = [];
        switch ($arr['error'])
        {
            case -1:
                $array = ["status" => 0, "info" => "系统异常!"];
                break;
            case -2:
                $array = ["status" => 0, "info" => "客户端异常!"];
                break;
            case 304:
                $array = ["status" => 0, "info" => "客户端发送三次失败!"];
                break;
            case 305:
                $array = ["status" => 0, "info" => "服务器返回了错误的数据，原因可能是通讯过程中有数据丢失!"];
                break;
            case 307:
                $array = ["status" => 0, "info" => "发送短信目标号码不符合规则，手机号码必须是以0、1开头!"];
                break;
            case 308:
                $array = ["status" => 0, "info" => "非数字错误，修改密码时如果新密码不是数字那么会报308错误!"];
                break;
            case -110:
                $array = ["status" => 0, "info" => "号码注册激活失败!"];
                break;
            case -101:
                $array = ["status" => 0, "info" => "命令不被支持!"];
                break;
            case -102:
                $array = ["status" => 0, "info" => "RegistryTransInfo删除信息失败（转接）!"];
                break;
            case -103:
                $array = ["status" => 0, "info" => "RegistryInfo更新信息失败（序列号相关注册）!"];
                break;
            case -104:
                $array = ["status" => 0, "info" => "请求超过限制!"];
                break;
            case -111:
                $array = ["status" => 0, "info" => "企业注册失败!"];
                break;
            case -117:
                $array = ["status" => 0, "info" => "发送短信失败!"];
                break;
            case -118:
                $array = ["status" => 0, "info" => "接收MO失败!"];
                break;
            case -119:
                $array = ["status" => 0, "info" => "接收Report失败!"];
                break;
            case -120:
                $array = ["status" => 0, "info" => "修改密码失败!"];
                break;
            case -122:
                $array = ["status" => 0, "info" => "号码注销失败!"];
                break;
            case -123:
                $array = ["status" => 0, "info" => "查询单价失败!"];
                break;
            case -124:
                $array = ["status" => 0, "info" => "查询余额失败!"];
                break;
            case -125:
                $array = ["status" => 0, "info" => "设置MO转发失败!"];
                break;
            case -126:
                $array = ["status" => 0, "info" => "路由信息失败!"];
                break;
            case -127:
                $array = ["status" => 0, "info" => "计费失败0余额!"];
                break;
            case -128:
                $array = ["status" => 0, "info" => "计费失败余额不足!"];
                break;
            case -1100:
                $array = ["status" => 0, "info" => "序列号错误,序列号不存在内存中,或尝试攻击的用户!"];
                break;
            case -1102:
                $array = ["status" => 0, "info" => "序列号密码错误!"];
                break;
            case -1103:
                $array = ["status" => 0, "info" => "序列号Key错误!"];
                break;
            case -1104:
                $array = ["status" => 0, "info" => "路由失败，请联系系统管理员!"];
                break;
            case -1105:
                $array = ["status" => 0, "info" => "注册号状态异常, 未用 1!"];
                break;
            case -1107:
                $array = ["status" => 0, "info" => "注册号状态异常, 停用 3!"];
                break;
            case -1108:
                $array = ["status" => 0, "info" => "注册号状态异常, 停止 5!"];
                break;
            case -113:
                $array = ["status" => 0, "info" => "充值失败!"];
                break;
            case -1131:
                $array = ["status" => 0, "info" => "充值卡无效!"];
                break;
            case -1132:
                $array = ["status" => 0, "info" => "充值密码无效!"];
                break;
            case -1133:
                $array = ["status" => 0, "info" => "充值卡绑定异常!"];
                break;
            case -1134:
                $array = ["status" => 0, "info" => "充值状态无效!"];
                break;
            case -1135:
                $array = ["status" => 0, "info" => "充值金额无效!"];
                break;
            case -190:
                $array = ["status" => 0, "info" => "数据操作失败!"];
                break;
            case -1901:
                $array = ["status" => 0, "info" => "数据库插入操作失败!"];
                break;
            case -1902:
                $array = ["status" => 0, "info" => "数据库更新操作失败!"];
                break;
            case -1903:
                $array = ["status" => 0, "info" => "数据库删除操作失败!"];
                break;
            case -9001:
                $array = ["status" => 0, "info" => "序列号格式错误!"];
                break;
            case -9002:
                $array = ["status" => 0, "info" => "密码格式错误!"];
                break;
            case -9003:
                $array = ["status" => 0, "info" => "客户端Key格式错误!"];
                break;
            case -9004:
                $array = ["status" => 0, "info" => "设置转发格式错误!"];
                break;
            case -9005:
                $array = ["status" => 0, "info" => "公司地址格式错误!"];
                break;
            case -9006:
                $array = ["status" => 0, "info" => "企业中文名格式错误!"];
                break;
            case -9007:
                $array = ["status" => 0, "info" => "企业中文名简称格式错误!"];
                break;
            case -9008:
                $array = ["status" => 0, "info" => "邮件地址格式错误!"];
                break;
            case -9009:
                $array = ["status" => 0, "info" => "企业英文名格式错误!"];
                break;
            case -9010:
                $array = ["status" => 0, "info" => "企业英文名简称格式错误!"];
                break;
            case -9011:
                $array = ["status" => 0, "info" => "传真格式错误!"];
                break;
            case -9012:
                $array = ["status" => 0, "info" => "联系人格式错误!"];
                break;
            case -9013:
                $array = ["status" => 0, "info" => "联系电话!"];
                break;
            case -9014:
                $array = ["status" => 0, "info" => "邮编格式错误!"];
                break;
            case -9015:
                $array = ["status" => 0, "info" => "新密码格式错误!"];
                break;
            case -9016:
                $array = ["status" => 0, "info" => "发送短信包大小超出范围!"];
                break;
            case -9017:
                $array = ["status" => 0, "info" => "发送短信内容格式错误!"];
                break;
            case -9018:
                $array = ["status" => 0, "info" => "发送短信扩展号格式错误!"];
                break;
            case -9019:
                $array = ["status" => 0, "info" => "发送短信优先级格式错误!"];
                break;
            case -9020:
                $array = ["status" => 0, "info" => "发送短信手机号格式错误!"];
                break;
            case -9021:
                $array = ["status" => 0, "info" => "发送短信定时时间格式错误!"];
                break;
            case -9022:
                $array = ["status" => 0, "info" => "发送短信唯一序列值错误!"];
                break;
            case -9023:
                $array = ["status" => 0, "info" => "充值卡号格式错误!"];
                break;
            case -9024:
                $array = ["status" => 0, "info" => "充值密码格式错误!"];
                break;
            case -9025:
                $array = ["status" => 0, "info" => "客户端请求sdk5超时（需确认）!"];
                break;
            default:
                $array = ["status" => 0, "info" => "未知错误，短信提供方为提供相关信息!"];
                break;
        }
        return $array;
    }

    /**
     * 更改密码
     * @param string $newPwd 新密码
     * @return array 更改密码的情况
     */
    public function updatePassword($newPwd)
    {
        $fields = [
            'cdkey'=>urlencode($this -> uName),
            'password'=>urlencode($this -> uPwd),
            'newPassword'=>urlencode($newPwd)
        ];

        $result = $this -> execPostRequest($this -> updatePwdUrl, $fields);
        $rs = $this -> analysisResult($result);
        return $rs;
    }

    /**
     * 注册企业信息
     * @param string $cdkey	用户序列号。
     * @param string $password	用户密码
     * @param string $ename	企业名称(最多60字节)，必须输入
     * @param string $linkman	联系人姓名(最多20字节)，必须输入
     * @param string $phonenum	联系电话(最多20字节)，必须输入
     * @param string $mobile	联系手机(最多15字节)，必须输入
     * @param string $email	电子邮件(最多60字节)，必须输入
     * @param string $fax	联系传真(最多20字节)，必须输入
     * @param string $address	公司地址(最多60字节)，必须输入
     * @param string $postcode	邮政编码(最多6字节)，必须输入
     * @return array 企业注册情况
     */
     public function addRegisterInfo($cdkey,$password,$ename,$linkman,$phonenum,$mobile,$email,$fax,$address,$postcode)
     {}

    /**
     * 序列号注册
     * @return array 注册的情况
     */
    public function addCdkey()
    {
        $fields = [
            'cdkey'=>urlencode($this -> uName),
            'password'=>urlencode($this -> uPwd)
        ];

        $result = $this -> execPostRequest($this -> cdkeyRegisterUrl, $fields);
        $rs = $this -> analysisResult($result);
        return $rs;
    }

    /**
     * 注销序列号
     * @return array 注销的情况
     */
    public function stopCdkey()
    {
        $fields = [
            'cdkey'=>urlencode($this -> uName),
            'password'=>urlencode($this -> uPwd)
        ];

        $result = $this -> execPostRequest($this -> cdkeylogoutUrl, $fields);
        $rs = $this -> analysisResult($result);
        return $rs;
    }

    /**
     * 获取状态报告
     * @return array 返回获取的情况
     */
    public function getStutasReport()
    {
        $fields = [
            'cdkey'=>urlencode($this -> uName),
            'password'=>urlencode($this -> uPwd)
        ];

        $result = $this -> execPostRequest($this -> getStutasReportUrl, $fields);
        $rs = $this -> analysisResult($result);
        return $rs;
    }

    /**
     * 查询短信余额
     * @return array 信息的发送情况
     */
    public function selectSelNum()
    {
        $fields = [
            'cdkey'=>urlencode($this -> uName),
            'password'=>urlencode($this -> uPwd)
        ];

        $result = $this -> execPostRequest($this -> selectSelSumUrl, $fields);
        $rs = $this -> analysisResult($result);
        return $rs;
    }

    /*
     * 获取上行信息
     * @return array 信息的发送情况
     */
    public function getUpSms()
    {
        $fields = [
            'cdkey'=>urlencode($this -> uName),
            'password'=>urlencode($this -> uPwd)
        ];

        $result = $this -> execPostRequest($this -> getUpSmsUrl, $fields);
        $rs = $this -> analysisResult($result);
        return $rs;
    }

    /*
     * 充值操作
     * @param string $cardno 充值卡卡号
     * @param string $cardpass 充值卡密码
     * @return array 信息的发送情况
     */
    public function paySms($cardno,$cardpass)
    {
        $fields = [
            'cdkey'=>urlencode($this -> uName),
            'password'=>urlencode($this -> uPwd),
            'cardno'=>urlencode($cardno),
            'cardpass'=>urlencode($cardpass)
        ];

        $result = $this -> execPostRequest($this -> paySmsUrl, $fields);
        $rs = $this -> analysisResult($result);
        return $rs;
    }

    /**
     * 发送定时短信
     * @param string $phone 要发送的电话号码
     * @param string $content 要发送的短信内容
     * @param string $seqid 消息Id
     * @param string $sendtime 预定发送时间(格式为：yyyymmddhhnnss)
     * @return array 信息的发送情况
     */
    public function timingSms($phones, $content, $sendtime)
    {
        $content = iconv("UTF-8", "gbk", $content);
        $fields = [
            'cdkey'=>urlencode($this -> uName),
            'password'=>urlencode($this -> uPwd),
            'phone'=>urlencode($phones),
            'message'=>urlencode($content),
            'seqid'=>urldecode(self::SEQID),
            'sendtime'=>urldecode($sendtime),
            'addserial'=>''
        ];

        $result = $this -> execPostRequest($this -> timingSmsUrl, $fields);
        $rs = $this -> analysisResult($result);
        return $rs;
    }

    /**
     * 发送即时短信
     * @param string $phone 要发送的电话号码
     * @param string $content 要发送的短信内容
     * @param string $seqid 消息Id 获取状态报告时需要
     * @return array 信息的发送情况
     */
    public function sendSMS($phones, $content)
    {
        $fields = [
            'cdkey'=>urlencode($this -> uName),
            'password'=>urlencode($this -> uPwd),
            'phone'=>urlencode($phones),
            'message'=>urlencode($content),
            'seqid'=>urlencode(self::SEQID),
            'addserial'=>''
        ];

        $result = $this -> execPostRequest($this -> sendSmsUrl, $fields);
        $rs = $this -> analysisResult($result);
        return $rs;
    }

    //@TODO::日后记录发送次数和发送的具体内容
    public function logSms(){
    }

    /*
     * 解析XML
     * @param xml $simple 要解析的xml
     * @return array 返回的结果
     */
    public function xmlParse($simple)
    {
        if(empty($simple))
        {
            return false;
        }
        $temp =  str_replace("\r\n","",$simple);
        $xml = \Org\Helper\XML2Array::createArray($temp);
        $temp = "";
        foreach($xml['response'] as $k => $val)
        {
            if($k == 'error'){
                $temp['error'] = $val;
            }
            if($k == 'message'){
                $temp['message'] = $val;
            }
            continue;
        }

        return $temp;
    }
}