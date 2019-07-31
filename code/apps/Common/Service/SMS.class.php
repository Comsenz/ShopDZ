<?php
namespace Common\Service;
interface SMS{
    /*
     * 统一的发信息的接口
     * @phone 号码列表，逗号隔开
     * @content 发送内容
     * */
    public function sendSMS($phones, $content);

    /*
     * 日志记录用于防止盗用和判断
     * */
    public function logSms();
}