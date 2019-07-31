<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\PaymentService;
use Common\Service\OrdersService;
use  Common\Service\SmsService;
use  Common\Service\IndexService;
use  Common\Service\SpreadOrderCronService;
use Admin\Model\SettingModel;

use Think\Model;
class PaymentwxController extends BaseController {
	protected $type = '';
	protected $isStystem = true;//是否是系统订单，以区分插件订单
	protected $pay_sn = '';
	protected $payment_code = '';
	protected $payment_config = array();
	private $pay_info = array();
	private $pay_class_method = array();
    private $pay_state = array('wx'=>'微信','wxx'=>'微信小程序','alipay'=>'支付宝','newalipay'=>'新支付宝');
    public function __construct() {
		parent::__construct();
		$payment_code = I('payment_code','','htmlspecialchars');
        if(!empty($payment_code)) {
            if(!in_array($payment_code, array('wx','wxx','alipay','newalipay'), true)) {
                $payment_code = 'alipay';
            }
            $this->get_payment($payment_code);
        }
	}
    private function get_payment($pay_code){
        $this->payment_code = $pay_code;
        if($pay_code == 'newalipay'){
            $pay_code = 'alipay';
        }
        $condition = array();
        $condition['payment_code'] = $pay_code;
        $mb_payment_info = PaymentService::getPaymentOpenList($condition);
        $mb_payment_info = current($mb_payment_info);
        if(!$mb_payment_info) { 
            $this->returnJson(1,'pay not open');
        }
        $this->payment_config =$mb_payment_info ? unserialize($mb_payment_info['payment_config']) :'';
        switch($this->payment_code){
            case "newalipay":
                $this->payment_config['private_key_path'] = APP_ROOT . 'data/Attach/' . $this->payment_config['private_key_path'];
            break;
            case "wx":
                $this->payment_config['sslkey_path'] = APP_ROOT . 'data/Attach/' . $this->payment_config['sslkey_path'];
                $this->payment_config['sslcert_path'] = APP_ROOT . 'data/Attach/' . $this->payment_config['sslcert_path'];
            case "wxx":
                $this->payment_config['sslkey_path'] = APP_ROOT . 'data/Attach/' . $this->payment_config['sslkey_path'];
                $this->payment_config['sslcert_path'] = APP_ROOT . 'data/Attach/' . $this->payment_config['sslcert_path'];
            break;
        }
        /* 支付宝支付 */
        $this->payment_config['phone'] = $this->isMobile();
    }

    //获取开启的支付方式列表
    public function get_payment_list(){
        $data = PaymentService::getPaymentOpenList();
        $payment_array = array();
        if(!empty($data)) {
            foreach ($data as $value) {
                $payment_array[] = $value['payment_code'];
            }
        }
        if(!$payment_array){
            $this->returnJson(1,'支付方式未开启');
        }
        $this->returnJson(0,'sucess',array('payment_list' => $payment_array));
    }

    /* 获取订单类数组 */
    private function _get_order_class($pay_sn=''){
    	/* pay_sn 通order_sn */
    	$this->pay_sn = $pay_sn ? $pay_sn : I('pay_sn','','htmlspecialchars');
    	if(empty($this->pay_sn)){
    		$this->returnJson(1,'订单编号不能为空','');
    	}
		
    	/* 获取订单类型(00:正常，02:退货，03:退款) */
    	$this->type = \Common\Helper\ToolsHelper::getOrderSnType($this->pay_sn);
        // $this->writeLog(var_export($this->type,true));
		if($this->type){//系统订单
    	/* 获取对应订单类型的操作类 */
			$this->pay_class_method = \Common\Helper\ToolsHelper::$pay_class_method[$this->type];
			$this->isStystem = true;
            // $this->writeLog(var_export($pay_class_method,true));
		}else{
			$lastCode = \Common\Helper\ToolsHelper::getOrderSnLastTwoCode($this->pay_sn);
			$plugins = M('plugin')->where(array('order_type_sn'=>$lastCode))->find();//code
			$this->type = $code = $plugins['code'];
		    include_once  APP_ROOT."plugins/{$code}/pay.class.php";
			try{
				$class = new \ReflectionClass('pay');
				$instance = $class->newInstanceArgs();
				$this->pay_class_method = array(
					'class'=>$instance,
					'detailurl'=>$instance->detailurl(),
					'succeeurl'=>$instance->successurl(),
					'failurl'=>$instance->failurl(),
				);
				$this->isStystem = false;
			}catch(\Exception $e){
				echo "plugins/{$code}/pay.class.php ".'</br>'.$e->getMessage();
			}
			
		}
		if(empty($this->pay_class_method)){
			$this->returnJson(1,'特殊错误请联系管理员','');
		}
    }
    /* 订单查询 */
    private function _select_order($pay_sn=''){
    	if(empty($this->pay_class_method)){
    		$this->_get_order_class($pay_sn);
    	}
        // $this->writeLog(var_export($pay_sn,true));
		if($this->isStystem){//系统订单
    	/* 执行对应的订单查询 */
			$order_info = call_user_func(array('\Common\Service\\'.$this->pay_class_method['class'],$this->pay_class_method['select']),$this->pay_sn);
            // $this->writeLog(var_export($order_info,true));
		}else{
			try{
				$order_info =  $this->pay_class_method['class']->selectOrderBySn($this->pay_sn);
			}catch(\Exception $e){
				echo "plugins/{$code}/pay.class.php ".'</br>'.$e->getMessage();
			}
		}
		if(empty($order_info)) {
			$this->returnJson(1,'order is not exists');
        }
	//	$type = \Common\Helper\ToolsHelper::getOrderSnType($this->pay_sn);
        /* 保存该订单的类型，方便后续使用 */
        $order_info['order_type'] = $this->type;
       	/* 保存该订单的数据，方便后续使用 */
        $this->pay_info = $order_info;
    }
    /* 订单修改 */
    private function _save_order($data,$pay_sn=''){
        $text = '支付订单号：'.$data['order_sn'];
        $text .= empty($data['trade_no'])?'  支付失败  ':'  支付成功  ';
        $this->writeLog($text);
        // 如果交易流水号为空则表示支付失败
        if(empty($data['trade_no'])){
            return false;
        }
    	if(empty($this->pay_class_method)){
    		$this->_get_order_class($pay_sn);
    	}
		if($this->isStystem){//系统订单
    	/* 执行对应的订单修改 */
			$result = call_user_func(array('\Common\Service\\'.$this->pay_class_method['class'],$this->pay_class_method['save']),$data);
		}else{
	
			try{
	
				return $this->pay_class_method['class']->saveOrder($data);
			}catch(\Exception $e){
				echo "plugins/{$code}/pay.class.php ".'</br>'.$e->getMessage();
			}
		}
		if($result === false) {
			/* 记录支付回调修改数据库失败 */
    		file_put_contents(__ROOT__.'./pay.txt',var_export(array('支付失败时间：'=>TIMESTAMP,'支付失败参数'=>$data),true),FILE_APPEND);
        }
    }

    /* 查询定单是否支付 */
    public function selectPay(){
        $this->_select_order();
        $data = array(
        	'order_sn' => $this->pay_info['order_sn'],
        	'order_amount' => $this->pay_info['order_amount'],
            'order_state' => $this->pay_info['order_state'],
        	'trade_no' => $this->pay_info['trade_no']
        );
        if($this->pay_info['order_state'] > 10){
            $this->returnJson(1,'该订单已支付',$data);
        }else{
            $this->returnJson(0,'请支付',$data);
        }
    }
    /**
     * 路由支付方式
     * @param payment_code  支付方式
     * @param pay_sn		订单号
     */
    public function routepay(){
    	$openId = null;
    	$url = trim(SITE_URL,'/');
        $param = $this->payment_config;

        if(empty($this->pay_class_method) && !empty($_GET['pay_sn'])){
    		$this->_get_order_class($_GET['pay_sn']);
    	}
        
        /* 传成功的url地址，失败的url地址 */
        $return_url = array(
            'finishUrl'    => $url.$this->pay_class_method['succeeurl'],
            'undoneUrl'    => $url.$this->pay_class_method['failurl']
        );

        if($_GET['payment_code'] == 'wx'){
            if($this->isMobile()){ // 是手机访问
                /* 微信 手机支付 */
                $WxPay = new \Common\PayMent\WxPay\WxPay($param);
                $tools = new \Common\PayMent\WxPay\JsApiPay();
                $openId = $tools->GetOpenid();
                
                if(!empty($_GET['pay_sn'])){
	                /* 商城订单 */
	                $url .= '/wap/mainpay.html';
	                $url .= '?payment_code=' . $_GET['payment_code'];
	                $url .= '&pay_sn=' . $_GET['pay_sn'];
	                $url .= '&oid=' . $openId;
	                $url .= '&phone=' . $this->isMobile();
	                $url .= '&returnSeUrl=' . urlencode($return_url['finishUrl']);
	                $url .= '&returnErUrl=' . urlencode($return_url['undoneUrl']);
	            }else{
	                /* 一码付 */
	                $url .= '/wap/otherpay.html';
	                $url .= '?payment_code=' . $_GET['payment_code'];
	                $url .= '&oid=' . $openId;
	            }
            }else{
            	// 电脑支付
            	$this->pay();exit;
            }
        }else{
            if($this->isMobile()){ // 是手机访问
                /* 新版支付宝支付 获取token */
                // $AlipayParam = new \Org\Alipay\AlipayMakeParam($param);
                // $AliPay = new \Org\Alipay\Alipay();
                // $result = $AliPay->getToken($AlipayParam);
            	/* 支付宝 */
            	if(!empty($_GET['pay_sn'])){
                    $notifyUrl = SITE_URL ."api.php/Payment/newalipayNotify";
            		/* 商城订单 */
        			$url .= '/wap/mainpay.html';
	                $url .= '?payment_code=' . $_GET['payment_code'];
	                $url .= '&pay_sn=' . $_GET['pay_sn'];
	                $url .= '&phone=' . $this->isMobile();
	                $url .= '&returnSeUrl=' . urlencode($return_url['finishUrl']);
	                $url .= '&returnErUrl=' . urlencode($return_url['undoneUrl']);
            		
            	}else{
            		/* 一码付 */
    	            $url .= '/wap/otherpay.html';
                    $url .= '?payment_code=' . $_GET['payment_code'];
    	            //$url .= '&token=' . $result['app_auth_token'];
    	        }
            }else{
                // 电脑支付
                $this->pay();exit;
            }
        }
        Header("Location: $url");exit();
    }
    
    // 订单支付
    public function pay(){
    	$this->_select_order();
    	/* 历史支付参数记录 */
    	// file_put_contents(__ROOT__.'./pay.txt',var_export($_GET,true),FILE_APPEND);
        // $phone = I('get.phone',null,'htmlspecialchars');
        /* 更新正常订单的支付时间 */
        if($this->pay_info['order_type'] == 'order'){
        	$result = call_user_func(array('\Common\Service\\'.$this->pay_class_method['class'],'upPayStartTime'),$this->pay_info['order_id']);
        	if($result === false) {
				$this->returnJson(1,'请重新支付');
	        }
        }
        $param = $this->payment_config;
		switch($this->payment_code) {
			case 'wx':
    			$WxPay = new \Common\PayMent\WxPay\WxPay($param);
                $WxPay->setOrderInfo($this->pay_info);
                if($this->type == 'group'){
                    $time_expire = 300;
                }else{
                    $time_expire = 3600;
                }
                $WxPay->timeExpire = $time_expire;
                if($this->isMobile()){
                    /* 微信手机支付 */
                    $this->returnJson(0,'sucess',$WxPay->JsApi());
                }else{
                    /* 微信二维码支付 */
                    $this->returnJson(0,'sucess',$WxPay->nativepay()); 
                }
			break;
            case 'wxx':
                $WxPay = new \Common\PayMent\WxxPay\WxPay($param);
                // $this->writeLog(var_export($WxPay,true));
                $WxPay->setOrderInfo($this->pay_info);
                if($this->type == 'group'){
                    $time_expire = 300;
                }else{
                    $time_expire = 3600;
                }

                $WxPay->timeExpire = $time_expire;
                if($this->isMobile()){ 
                    /* 微信手机支付 */
                    $this->returnJson(0,'sucess',$WxPay->JsApi());
                }else{
                    /* 微信二维码支付 */
                    $this->returnJson(0,'sucess',$WxPay->nativepay()); 
                }
            break;
            case 'alipay':
            	// /* 支付宝支付 */
            	// $param['phone'] = $this->isMobile();
                /*实例化支付宝类*/
                $alipay = new \Common\PayMent\AliPay\Alipays($param);
                /*渲染建立请求的页面*/
                echo $alipay->aliRequest($this->pay_info);
            break;
            case 'newalipay':
                try{
                    $data = array(
                        'subject' =>$this->pay_info['order_sn'],
                        'total_amount'=>$this->pay_info['order_amount'],
                        'body'=>$this->pay_info['order_sn'],
                        'out_trade_no'=>$this->pay_info['order_sn'],
                    );
                    
                    $returnUrl = SITE_URL ."api.php/Payment/newalipayReturn";
                    $notifyUrl = SITE_URL ."api.php/Payment/newalipayNotify";
                    /* 新版支付宝支付 */
                    $AlipayParam = new \Org\Alipay\AlipayMakeParam($param);
                    // 调用对应的业务方法 插入公共参数
                    $AlipayParam->AlipayWapPayMainParam($returnUrl,$notifyUrl);

                    // 传入业务参数
                    $AlipayParam->setWapBizParas($data);
                    
                    $wapPay = new \Org\Alipay\Alipay();
                    echo $wapPay->wapPay($AlipayParam);
                }catch(Exception $e){
                    echo $e->getMessage();
                }
            break;
		}
    }

    // 面对面扫码支付
    public function addotherpay(){
        $order_amount = I('order_amount','','float');
        $payment_code = I('payment_code','','htmlspecialchars');
        $order_sn = \Common\Helper\ToolsHelper::getOrderSn('otherpay');
        if(empty($this->pay_class_method)){
            $this->_get_order_class($order_sn);
        }
        $pay_info = array(
            'order_sn'        => $order_sn,
            'payment_code'    => $payment_code,
            'order_amount'    => $order_amount,
        );

        /* 插入扫码的支付数据 */
        $result = call_user_func(array('\Common\Service\\'.$this->pay_class_method['class'],$this->pay_class_method['add']),$pay_info);
        if($result) {
            /* 插入失败返回提示 */
            $this->returnJson(0,'添加订单成功',$pay_info);
        }else{
            /* 插入失败返回提示 */
            $this->returnJson(1,'请重新支付');
        }
    }
    
    /* 支付宝通知回调 */
    public function alipayNotify(){
        $this->get_payment('alipay');
        /* 功能：支付宝服务器异步通知页面 */
        $alipay = new \Common\PayMent\AliPay\Alipays($this->payment_config);
        $data = array();
        $alipay->alipayNotify($data);
        $up = array(
            'order_sn'=>$data['order_sn'],
            'payment_code'=>'alipay',
            'payment_time'=>TIMESTAMP,
            'order_state'=>20,
            'trade_no'=>$data['trade_no']
        );
		$this->_select_order($data['order_sn']);
		if($this->pay_info['trade_no']) {
			return true;
		}
        $this->_save_order($up,$data['order_sn']);
        //OrdersService::saveOrder($up);

        //支付成功发送支付成功短信
        if(!empty($data['trade_no'])){
        	//赠送积分
		/* 获取订单类型(00:正常，01:拼团，02:退货，03:退款) */
			$this->_notifyAfter($data);
        }
    }
    
    /* 微信通知回调 */
    public function wxpayNotify(){
		$this->get_payment('wx');
		$wx = new \Common\PayMent\WxPay\WxPayNotify($this->payment_config);
        $data = array();
		$data = $wx->wxpayNotify();
        $up = array(
            'order_sn'=>$data['order_sn'],
            'payment_code'=>'wx',
            'payment_time'=>TIMESTAMP,
            'order_state'=>20,
            'trade_no'=>$data['trade_no']
        );
		$this->_select_order($data['order_sn']);
		if($this->pay_info['trade_no']) {
			return true;
		}
        $this->_save_order($up,$data['order_sn']);
        //OrdersService::saveOrder($up);
        //支付成功发送支付成功短信
        if(!empty($data['trade_no'])){
        	//赠送积分
		/* 获取订单类型(00:正常，01:拼团，02:退货，03:退款) */
					/* 获取订单类型(00:正常，01:拼团，02:退货，03:退款) */
			$this->_notifyAfter($data);
        }
    }
    /* 微信通知回调 */
    public function wxxpayNotify(){
        $this->get_payment('wxx');
        $wx = new \Common\PayMent\WxxPay\WxPayNotify($this->payment_config);
        $data = array();
        $data = $wx->wxpayNotify();
        $up = array(
            'order_sn'=>$data['order_sn'],
            'payment_code'=>'wxx',
            'payment_time'=>TIMESTAMP,
            'order_state'=>20,
            'trade_no'=>$data['trade_no']
        );
        $this->_select_order($data['order_sn']);
        if($this->pay_info['trade_no']) {
            return true;
        }
        $this->_save_order($up,$data['order_sn']);
        //OrdersService::saveOrder($up);
        //支付成功发送支付成功短信
        if(!empty($data['trade_no'])){
            //赠送积分
        /* 获取订单类型(00:正常，01:拼团，02:退货，03:退款) */
                    /* 获取订单类型(00:正常，01:拼团，02:退货，03:退款) */
            $this->_notifyAfter($data);
        }
    }

    /* 新支付宝支付成功之后的跳转验证签名 */
    public function newalipayReturn(){
        try{
            $this->get_payment('newalipay');
            $this->_get_order_class($_GET['out_trade_no']);
            /* 新版支付宝支付 */
            $AlipayParam = new \Org\Alipay\AlipayMakeParam($this->payment_config);
            // 调用对应的业务方法 插入公共参数
            $AlipayParam->AlipayWapPayMainParam();
            /* 传成功的url地址，失败的url地址 */
            $succeeurl = SITE_URL .'wap/gotopay.html?order_sn='.$_GET['out_trade_no'];// http://'.$_SERVER['HTTP_HOST'].$this->pay_class_method['succeeurl'];
            $failurl = '';// http://'.$_SERVER['HTTP_HOST'].$this->pay_class_method['failurl'];
            /* 功能：支付宝服务器异步通知页面 */
            $alipay = new \Org\Alipay\Alipay();
            $res = $alipay->wapPayReturn($AlipayParam,$succeeurl,$failurl);
            echo $res;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
	
	private function _notifyAfter($data) {
        $this->writeLog('获取订单类型');
		/* 获取订单类型(00:正常，01:拼团，02:退货，03:退款) */
			if(in_array($this->type,array('order','return','refund'))){
				if(in_array($this->type,array('order'))){
                    $this->writeLog('正常订单类型');
					$insert_arr['pl_memberid'] = $this->pay_info['buyer_id'];
					$insert_arr['pl_membername'] = $this->pay_info['buyer_name'];
					$insert_arr['order_sn'] = $data['order_sn'];
					$insert_arr['orderprice'] = $this->pay_info['order_amount'];
					$result  = \Common\Helper\PointsHelper::addPoints($insert_arr,'order_rate');
					$smsdata = array('order_sn' => $data['order_sn']);
					SmsService::sendWxAdmin($smsdata);
					SmsService::insert_sms_notice($this->pay_info['buyer_id'],$this->pay_info['buyer_phone'],$smsdata,'paymentsuccess');
                    $settingmodel = new SettingModel();
                    $sms_mobile = $settingmodel->getSetting('sms_mobile');
                    if(!empty($sms_mobile)){
                        if (strpos($sms_mobile,',')){
                            $admin_mobile = array_filter(explode(',',$sms_mobile));
                        } else {
                            $admin_mobile[] = $sms_mobile;
                        }
                        foreach ($admin_mobile as $mobile) {
                            SmsService::insert_sms_notice($this->pay_info['buyer_id'],$mobile,$smsdata,'delivergoods');
                        }
                    }
				}
				$insert['order_id'] = $this->pay_info['order_id'];
				$insert['order_add_time'] = $this->pay_info['add_time'];
				$insert['add_time'] = TIMESTAMP;
				$insert['cron_type'] = 0;
                $insert['cron_time'] = 0;
				SpreadOrderCronService::addCron($this->pay_info['buyer_id'],$insert,new Model());
                $this->writeLog('更新状态');
			}
			$data['is_stytem_order'] = in_array($this->type,array('order','return','refund'));
			tag('PayAfter',$data); 
	}
    /* 新支付宝支付成功之后的回调 */
    public function newalipayNotify(){
        try{
            $order_sn = $_POST['out_trade_no'];
            $this->_select_order($order_sn);
			if($this->pay_info['trade_no']) {
				return true;
			}
            $this->get_payment('newalipay');
            
            /* 新版支付宝支付 */
            $AlipayParam = new \Org\Alipay\AlipayMakeParam($this->payment_config);
            // 调用对应的业务方法 插入公共参数
            $AlipayParam->AlipayWapPayMainParam($this->pay_info);
            $param = array(
                'subject' =>$this->pay_info['order_sn'],
                'total_amount'=>$this->pay_info['order_amount'],
                'body'=>$this->pay_info['order_sn'],
                'out_trade_no'=>$this->pay_info['order_sn'],
            );
            $AlipayParam->setWapBizParas($param);
            /* 功能：支付宝服务器异步通知页面 */
            $alipay = new \Org\Alipay\Alipay();

            $result = $alipay->wapPayNotify($AlipayParam);
            if($result == 'success'){
                $data = array(
                    'order_sn'=>$_POST['out_trade_no'],
                    'payment_code'=>'newalipay',
                    'payment_time'=>TIMESTAMP,
                    'order_state'=>20,
                    'trade_no'=>$_POST['trade_no']
                );
                $this->_save_order($data,$data['order_sn']);
                // 交易成功
                echo 'success';
                if($this->type == 'otherpay'){
                    /* 扫码支付，不需要执行之后的操作 */
                    die;
                }
                //支付成功发送支付成功短信
                if(!empty($data['trade_no'])){
                    //赠送积分
						/* 获取订单类型(00:正常，01:拼团，02:退货，03:退款) */
					$this->_notifyAfter($data);
				}
            }elseif($result == 'fail'){
                // 取消交易
                echo 'fail';
            }else{
                // 非法的交易

            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
        
    }
   	/* 支付宝支付成功之后的跳转 */
    public function alipayReturn(){
        $order_sn = $_GET['out_trade_no'];
        if(empty($this->pay_class_method)){
            $this->_get_order_class($order_sn);
        }
        $this->get_payment('alipay');
        /* 功能：支付宝服务器异步通知页面 */
        $alipay = new \Common\PayMent\AliPay\Alipays($this->payment_config);
        /* 传成功的url地址，失败的url地址 */
        $succeeurl = trim(SITE_URL,'/') .$this->pay_class_method['succeeurl'];
        $failurl = trim(SITE_URL,'/') .$this->pay_class_method['failurl'];
        $result = $alipay->alipayReturn($succeeurl, $failurl);
    }

    /* 支付成功返回的结束 */
    public function payResult(){
        $order_sn = I('param.order_sn');
        $this->_get_order_class($order_sn);
		if($this->isStystem){//系统订单
			$data = call_user_func(array('\Common\Service\\'.$this->pay_class_method['class'],$this->pay_class_method['payresult']),$order_sn);
		}else{
			$data =  $this->pay_class_method['class']->payresult($order_sn);
		}
		
        if(!empty($data)){
            $data['detailurl'] = $this->pay_class_method['detailurl'].'?order_sn='.$order_sn;
            $data['payment_code_text'] = $this->pay_state[$data['payment_code']];
        }else{
            $this->returnJson(1,'订单错误，请重试');
        }
        $this->returnJson(0,'success',$data);
    }

    /**
     * 写日志
     */
    public function writeLog($text) {
        $month = date('Y-m');
        file_put_contents ( APP_ROOT."/data/Logs/pay/".$month.".txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
    }

    /**
     * 写日志
     */
    public function writeLogs($text) {
        $month = date('Y-m');
        file_put_contents ( APP_ROOT."/data/Logs/admin_pay/".$month.".txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
    }

    public function isMobile(){ 
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) { 
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        } 
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array ('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'); 
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            } 
        } 
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) { 
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            } 
        } 
        return false;
    }
    // 订单支付
    public function aliqrPay(){
    	$this->_select_order();
    	/* 历史支付参数记录 */
    	file_put_contents(__ROOT__.'./pay.txt',var_export($_GET,true),FILE_APPEND);
        // $phone = I('get.phone',null,'htmlspecialchars');
        /* 更新正常订单的支付时间 */
        if($this->pay_info['order_type'] == 'order'){
        	$result = call_user_func(array('\Common\Service\\'.$this->pay_class_method['class'],'upPayStartTime'),$this->pay_info['order_id']);
        	if($result === false) {
				$this->returnJson(1,'请重新支付');
	        }
        }
        $param = $this->payment_config;
        try{
            $data = array(
                'subject' =>$this->pay_info['order_sn'],
                'total_amount'=>$this->pay_info['order_amount'],
                'body'=>$this->pay_info['order_sn'],
                'out_trade_no'=>$this->pay_info['order_sn'],
            );
            $returnUrl = SITE_URL ."api.php/Payment/newalipayReturn";
            $notifyUrl = SITE_URL ."api.php/Payment/newalipayNotify";

            /* 新版支付宝支付 */
            $AlipayParam = new \Org\Alipay\AlipayMakeParam($param);

            // 沙箱环境
            // $AlipayParam->appid = '2016072900117409';
            // $AlipayParam->sellerId = '2088102168994587';
            // $AlipayParam->mainParas['app_id'] = '2016072900117409';
            // $AlipayParam->gateway_url = 'https://openapi.alipaydev.com/gateway.do';

            // 扫码支付的公共参数
            $AlipayParam->AlipayScanPayMainParam($notifyUrl);

            //$AlipayParam->setAppAuthToken($_GET['token']);
            // 传入业务参数
            $AlipayParam->setBizParas($data,$goods_info);

            // 调用barPay方法获取当面付应答
            $wapPay = new \Org\Alipay\Alipay();
            $result = $wapPay->qrPay($AlipayParam);
            $Status = $result->getTradeStatus();
            $res = $result->getResponse();
            $this->returnJson(0,'请支付',$res);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    // public function checkmobile() { 
    //     global $_G;
    //     $mobile = array(); 

    //     // 各个触控浏览器中 $_SERVER['HTTP_USER_AGENT'] 所包含的字符串数组 
    //     static $touchbrowser_list = array ('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini', 'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung', 'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser', 'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource', 'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone', 'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop', 'benq', 'haier', '^lct', '320x320', '240x320', '176x220'); 

    //     //window 手机浏览器数组【猜的】 
    //     static $mobilebrowser_list = array('windows phone'); 
    //     //wap 浏览器中 $_SERVER['HTTP_USER_AGENT'] 所包含的字符串数组
    //     static $wmlbrowser_list = array('cect', 'compal', 'ctl', 'lg', 'nec', 'tcl', 'alcatel', 'ericsson', 'bird', 'daxian', 'dbtel', 'eastcom', 'pantech', 'dopod', 'philips', 'haier', 'konka', 'kejian', 'lenovo', 'benq', 'mot', 'soutec', 'nokia', 'sagem', 'sgh', 'sed', 'capitel', 'panasonic', 'sonyericsson', 'sharp', 'amoi', 'panda', 'zte');
    //     $pad_list = array('pad', 'gt-p1000');
    //     $useragent = strtolower($_SERVER['HTTP_USER_AGENT']); 
    //     if($this->dstrpos($useragent, $pad_list)) {
    //         return false;
    //     }
    //     if(($v = $this->dstrpos($useragent, $mobilebrowser_list, true))){ 
    //         $_G['mobile'] = $v;
    //         return '1'; 
    //     }
    //     if(($v = $this->dstrpos($useragent, $touchbrowser_list, true))){ 
    //         $_G['mobile'] = $v; 
    //         return '2'; 
    //     } 
    //     if(($v = $this->dstrpos($useragent, $wmlbrowser_list))) {
    //         $_G['mobile'] = $v;
    //         return '3'; 
    //         //wml版
    //     } 
    //     $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop'); 

    //     if($this->dstrpos($useragent, $brower)) 
    //         return false;
    //     $_G['mobile'] = 'unknown'; 
    //     // 对于未知类型的浏览器，通过 $_GET['mobile'] 参数来决定是否是手机浏览器
    //     if(isset($_G['mobiletpl'][$_GET['mobile']])) {
    //         return true;
    //     }else{
    //         return false;
    //     } 
    // }
    // function dstrpos($string, $arr, $returnvalue = false) {
    //     if(empty($string))
    //         return false; 
    //     foreach((array)$arr as $v) {
    //         if(strpos($string,$v) !== false) {
    //             $return = $returnvalue?$v:true;
    //             return $return;
    //         }
    //     }
    //     return false;
    // }
    // public function isMobile(){  
    //     $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';  
    //     $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';        
    //     function CheckSubstrs($substrs,$text){  
    //         foreach($substrs as $substr)  
    //             if(false!==strpos($text,$substr)){  
    //                 return true;  
    //             }  
    //             return false;  
    //     }
    //     $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
    //     $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');  
              
    //     $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||  
    //               CheckSubstrs($mobile_token_list,$useragent);  
              
    //     if ($found_mobile){  
    //         return true;  
    //     }else{  
    //         return false;  
    //     }  
    // }
}