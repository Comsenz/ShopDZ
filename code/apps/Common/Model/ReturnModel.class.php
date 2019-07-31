<?php
namespace Common\Model;
use Think\Model;
use  \AlipaySubmit;
use Common\PayMent\WxPay\WxPayRefund;
use Common\PayMent\WxPay\WxPayApi;
class ReturnModel extends Model {
	public $type = '';//订单类型
	public $return_class_method = array();//订单类型类操作数据
	public $isStystem = true;//是否是系统订单，以区分插件订单
	public function __construct(){}

	/* 获取订单类数组 */
    public function _get_order_class($return_sn){
    	/* 获取订单类型(00:正常，01:拼团，02:退货，03:退款) */
    	$this->type = \Common\Helper\ToolsHelper::getOrderSnType($return_sn);
		if($this->type){
    	/* 获取对应订单类型的操作类 */
			$this->return_class_method = \Common\Helper\ToolsHelper::$return_class_method[$this->type];
			$this->isStystem = true;
		}else{
			$lastCode = \Common\Helper\ToolsHelper::getOrderSnLastTwoCode($return_sn);
			$plugins = M('plugin')->where(array('order_type_sn'=>$lastCode))->find();//code
			$this->type = $code = $plugins['code'];
		    include_once  APP_ROOT."plugins/{$code}/pay.class.php";
			try{
				$class = new \ReflectionClass('pay');
				$instance = $class->newInstanceArgs();
				$this->return_class_method = array(
					'class'=>$instance,
				);
				$this->isStystem = false;
			}catch(\Exception $e){
				echo "plugins/{$code}/pay.class.php ".'</br>'.$e->getMessage();
			}
		}
		if(empty($this->return_class_method)){
			echo "fail";die;
		}
    }
	/**
  	 *	退货退款入口
	 *	@param	$data   : 退款的参数数组
	 *				array(
     *					'payment_code'	//订单支付方式		(必填)
     *					'order_id'		//订单ID
     *					'buyer_id'		//用户ID
     *					'buyer_phone'	//用户手机号
     *					'trade_no'		//交易流水号		(必填)
     *					'total_fee'		//退款金额			(必填)
     *					'order_amount'	//订单总金额		(必填)
     *					'order_add_time'//订单创建时间
     *					'batch_no'		//退款单号			(必填)
     *					'remark'		//退货处理意见
     *					'mark'			//'退款说明',		(必填)
     *					'returnurl'		//提交表单之后跳转的链接
     *					'return_type' 	//退款方式(1:原路退款，2:人工退款)		(必填)
     *				) 
	 **/
	public function router($data){
		if($data['return_type'] == '1'){
			switch ($data['payment_code']){
				case 'alipay':
					/* 支付宝支付 */
					$this->_alipay_return_pay($data);
					//return array('code'=>0,'msg'=>'请在新页面完成退款','data'=>'Presales/'.$this->type.'s');
				break;
				case 'wx':
					/* 微信支付 */
					$res = $this->_wx_return_pay($data);
					// $this->WriteLog(var_export($res,true));
					return $this->_callbackRouter($data, $res);
				break;
				case 'wxx':
					/* 微信支付 */
					$res = $this->_wxx_return_pay($data);
					return $this->_callbackRouter($data, $res);
				break;
				case 'pay':
					/* 后续其它扩展支付 */
				break;
			}
		}elseif($data['return_type'] == '2'){
			/* 人工退款 */
			$data['remark'] = '人工退款:'.$data['remark'];
			return $this->_callbackRouter($data, true);
		}else{
			echo '退款方式不能为空';die;
		}
	}

	/**
     * 写日志
     */
    public function WriteLog($text) {
        $month = date('Y-m');
        file_put_contents ( APP_ROOT."/data/Logs/admin_pay/".$month.".txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
    }
	/**
	 * 发送退款请求后的回调路由
	 * @param unknown $data		修改数据库的参数数组
	 * @param $type 退款的状态（true成功，false失败）
	 */
	public function _callbackRouter($data, $type){
		/* 获取类和方法的数组 */
		if(empty($this->return_class_method))
			$this->_get_order_class($data['batch_no']);
		/* 执行对应的订单修改 */
		file_put_contents(APP_ROOT.'./callback_.txt','data='.var_export($data,true),FILE_APPEND);
		file_put_contents(APP_ROOT.'./callback_.txt','class_method='.var_export($this->return_class_method,true),FILE_APPEND);
		if($this->isStystem){ 
			// $this->WriteLog(var_export($data,true));
			// $this->WriteLog(var_export($type,true));
			$res = call_user_func_array(array('\Common\Service\\'.$this->return_class_method['class'],$this->return_class_method['save']),array($data,$type));
		}else{
			$res = $this->return_class_method['class']->saveReturnOrder($data,$type);
		}
		if(!empty($res)){
			return $res;
		}
	}
	/**
	 * 支付宝原路退款
	 * @param $param trade_no		支付单号
	 * @param $param total_fee		退款金额
	 * @param $param batch_no		退款单号
	 * @param $param mark			退款说明
	 * @param $param returnurl		提交表单之后跳转的链接
	 */
	private function _alipay_return_pay($param){
	    require_once(APP_PATH.'Common/PayMent/AliPay/Refund'."/alipay.config.php");
	    require_once(APP_PATH.'Common/PayMent/AliPay/Refund'."/lib/alipay_submit.class.php");
	   
	    /**************************请求参数**************************/
	   
	    //批次号，必填，格式：当天日期[8位]+序列号[3至24位]，如：201603081000001
	   
	    //退款笔数，必填，参数detail_data的值中，“#”字符出现的数量加1，最大支 持1000笔（即“#”字符出现的数量999个）
	    $param['batch_num'] = 1;
	    //退款详细数据，必填，格式(支付宝交易号^退款金额^备注),多笔请用#隔开
	    $param['detail_data'] = $param['trade_no'].'^'.$param['total_fee'].'^'.$param['mark'];
	    /************************************************************/
	    //构造要请求的参数数组，无需改动
	    $parameter = array(
	           "service" => trim($alipay_config['service']),
	           "partner" => trim($alipay_config['partner']),
	           "notify_url"	=> trim($alipay_config['notify_url']),
	           "seller_user_id"	=> trim($alipay_config['seller_user_id']),
	           "refund_date"	=> trim($alipay_config['refund_date']),
	           "batch_no"	=> $param['batch_no'],
	           "batch_num"	=> $param['batch_num'],
	           "detail_data"	=> $param['detail_data'],
	           "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
	   
	   );
	   //建立请求
	   $alipaySubmit = new AlipaySubmit($alipay_config);
	   $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认",$param['returnurl']);
       echo $html_text;die;
	}
	/*
	*查询退款状态
	*/
	public function refundQuery( $data ) {
		switch ($data['payment_code']){
			case 'alipay':
				/* 支付宝支付 */
				return $this->_alipay_refund_query($data);
			break;
			case 'wx':
				/* 微信支付 */
				return $res = $this->_wx_refund_query($data);
			break;
			case 'wxx':
				/* 微信支付 */
				return $res = $this->_wxx_refund_query($data);
			break;
			case 'pay':
				/* 后续其它扩展支付 */
			break;
		}
	}
	private function _wx_refund_query($param) {
		$input = new WxPayRefund();
		$input->SetTransaction_id($param['trade_no']);
		$result = WxPayApi::refundQuery($input);
		if($result['return_code'] == 'SUCCESS'){
			return $result;
		}
		return array();
	}
	private function _wxx_refund_query($param) {
		$input = new WxPayRefund();
		$input->SetTransaction_id($param['trade_no']);
		$result = \Common\PayMent\WxxPay\WxPayApi::refundQuery($input);
		if($result['return_code'] == 'SUCCESS'){
			return $result;
		}
		return array();
	}
	
	private function _alipay_refund_query() {
		
	}
	/**
	 * 微信原路退款
	 * @param $param trade_no			支付单号
	 * @param $param batch_no			退款单号
	 * @param $param order_amount		订单总金额
	 * @param $param total_fee			退款金额
	 * @return boolean
	 */
	private  function _wx_return_pay($param){
	    ini_set('date.timezone','Asia/Shanghai');
	    try{
	    	$input = new WxPayRefund();
	        $input->SetTransaction_id($param['trade_no']);
	        $input->SetRefund_fee($param['total_fee'] * 100);
	        $input->SetTotal_fee($param['order_amount'] * 100);
	        $input->SetOut_refund_no($param['batch_no']);
	        $input->SetOp_user_id(get_wxpay_config('mchid'));
	        $result = WxPayApi::refund($input);
	        if(empty($result)){
	            return  false;
	        }
	        if($result['result_code'] == 'SUCCESS'){
	            return true ;
	        }else{
	            $a= order_log($param['batch_no'], 'refund_sn', '微信退款原路退款失败'.var_export($result,true),'system',0,'system');
	            return false;
	        }
	    }catch (\Exception $e){
	        order_log($param['batch_no'], 'refund_sn', '微信退款原路退款抛出异常'. $e->getMessage(),'system',0,'system');
	        return false;
	    }
	}
	/**
	 * 微信小程序原路退款
	 * @param $param trade_no			支付单号
	 * @param $param batch_no			退款单号
	 * @param $param order_amount		订单总金额
	 * @param $param total_fee			退款金额
	 * @return boolean
	 */
	private  function _wxx_return_pay($param){
	    ini_set('date.timezone','Asia/Shanghai');
	    try{
	    	$input = new \Common\PayMent\WxxPay\WxPayRefund();
	        $input->SetTransaction_id($param['trade_no']);
	        $input->SetRefund_fee($param['total_fee'] * 100);
	        $input->SetTotal_fee($param['order_amount'] * 100);
	        $input->SetOut_refund_no($param['batch_no']);
	        // $input->SetOp_user_id(get_wxpay_config('mchid'));
	        $input->SetOp_user_id('1530684301');
	        // $this->WriteLog(var_export($input,true));
	        $result = \Common\PayMent\WxxPay\WxPayApi::refund($input);
			// $this->WriteLog(var_export($result,true));
	        if(empty($result)){
	            return  false;
	        }
	        if($result['result_code'] == 'SUCCESS'){
	            return true ;
	        }else{
	            $a= order_log($param['batch_no'], 'refund_sn', '微信退款原路退款失败'.var_export($result,true),'system',0,'system');
	            return false;
	        }
	    }catch (\Exception $e){
	        order_log($param['batch_no'], 'refund_sn', '微信退款原路退款抛出异常'. $e->getMessage(),'system',0,'system');
	        return false;
	    }
	}
}
?>