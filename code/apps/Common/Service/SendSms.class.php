<?php
namespace Common\Service;
use Common\Service\IndexService;
class SendSms{
    /**
      * 发送模板短信
      * @param to 手机号码集合,用英文逗号分开
      * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
      * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
      */       
    function sendTemplateSMSTo($to,$text){
        $root  =   dirname(__FILE__).'/';
        define('RUNTIME_PATH', $root.'data/');

        $Filename= RUNTIME_PATH."Logs/smslog.txt"; //日志文件
        $send_info = IndexService::getSetting(array('sms_account','sms_password','shop_name'));
        $url = 'https://dx.ipyy.net/smsJson.aspx';
        $data = array(
            'userid'    => $send_info['shop_name'],
            'account'   => $send_info['sms_account'],
            'password'  => strtoupper(md5($send_info['sms_password'])),
            'mobile'    => $to,
            'content'   => $text,
            'action'    => 'send',
        );
        $result = send_post($url,$data);
        $result = json_decode($result,true);
        $smsdata = array();
        if(strtolower($result['returnstatus']) == 'success' && intval($result['successCounts']) >= 1){
            $smsdata['code'] = 200;
            $smsdata['msg'] = '发送成功！';
        }else{
            $smsdata['code'] = 500;
            $smsdata['msg'] = $result['message'];
        }
        $log = '请求地址：'.$url."\r\n";
        $log .= '发送数据：'.var_export($data,true)."\r\n";
        $log .= '返回数据：'.var_export($result,true)."\r\n\r\n";
        file_put_contents($Filename,$log,FILE_APPEND);
        return $smsdata;
    }
}
