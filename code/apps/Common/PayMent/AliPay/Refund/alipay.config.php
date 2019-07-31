<?php
/* *
 * 配置文件
 * 版本：3.4
 * 日期：2016-03-08
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
	
 * 安全校验码查看时，输入支付密码后，页面呈灰色的现象，怎么办？
 * 解决方法：
 * 1、检查浏览器配置，不让浏览器做弹框屏蔽设置
 * 2、更换浏览器或电脑，重新登录查询。
 */
 
//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
// 合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
$alipay_info =  M('payment')->where(array('payment_code'=>'alipay','payment_state'=>1))->getField('payment_config');
if(!$alipay_info){
    die('<meta charset="utf-8" />支付宝支付设置错误 ');
}
$alipay_info  = unserialize($alipay_info);
if(empty($alipay_info)){
    die('<meta charset="utf-8" />支付宝支付设置错误 ');
}
$alipay_config['partner']		= $alipay_info['pid'];

// 卖家支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
$alipay_config['seller_user_id']=$alipay_info['seller_id'];

// MD5密钥，安全检验码，由数字和字母组成的32位字符串，查看地址：https://b.alipay.com/order/pidAndKey.htm
$alipay_config['key']			= trim($alipay_info['key']);

// 服务器异步通知页面路径，需http://格式的完整路径，不能加?id=123这类自定义参数,必须外网可以正常访问
$alipay_config['notify_url']= SITE_URL .U('Notify/alipay_return_notify');
// 签名方式
$alipay_config['sign_type']    = strtoupper('MD5');

// 退款日期 时间格式 yyyy-MM-dd HH:mm:ss
//date_default_timezone_set('PRC');//设置当前系统服务器时间为北京时间，PHP5.1以上可使用。
$alipay_config['refund_date']=date("Y-m-d H:i:s",time());;

// 调用的接口名，无需修改
$alipay_config['service']='refund_fastpay_by_platform_pwd';

//字符编码格式 目前支持 gbk 或 utf-8
$alipay_config['input_charset']= strtolower('utf-8');

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
$alipay_config['cacert']    = APP_PATH.'Common/PayMent/AliPay/Refund/cacert.pem';
//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_config['transport']    = 'http';

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
?>