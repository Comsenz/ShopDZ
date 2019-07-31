<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
use  \AlipayNotify;
use Admin\Model\SettingModel;
use Common\Model\ReturnModel;
use Common\Service\SmsService;
use Common\Service\SpreadOrderCronService;

class NotifyController extends Controller {

    public function index(){
        $this->checklogin();
        C('LAYOUT_ON',false);
        $this->display("login");
    }

    /**
     * 支付宝原路退款通知
     */
    public function alipay_return_notify(){
        require_once(APP_PATH.'Common/PayMent/AliPay/Refund'."/alipay.config.php");
        require_once(APP_PATH.'Common/PayMent/AliPay/Refund'."/lib/alipay_notify.class.php");
        
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        
        if($verify_result) {//验证成功
            $batch_no = $_POST['batch_no'];
            $success_num =  intval($_POST['success_num']);
            $result_details = $_POST['result_details'];
           	//请不要修改或删除
            $log = $batch_no.'|'.$success_num.'|'.$result_details."|";
            file_put_contents('/data/a.txt', var_export($_POST,true).$log);
            /* 处理返回的参数 */
            list($trade_no,$total_fee,$refund_state)   =  explode('^',$result_details);

            $mod = new ReturnModel();
            $mod->_get_order_class($batch_no);
			if($mod->isStystem){
				$info = call_user_func_array(array('\Common\Service\\'.$mod->return_class_method['class'],$mod->return_class_method['select']),array($batch_no,$mod->type));
			}else{
				$info = $mod->return_class_method['class']->selectOrderByReturnSn($batch_no);
			}

            $refund_info = $info['refund_info'];
            $order_info = $info['order_info'];

            if(empty($refund_info)){
                echo "fail";die;
            }
            if($refund_info['status']==3){ //已经通知过了 已经是处理成功
                echo "success";die;
            }
            $data = array(
                'payment_code'  => 'alipay',//订单支付方式
                'order_id'      => $order_info['order_id'],//订单ID
                'buyer_id'      => $order_info['buyer_id'],//用户ID
                'buyer_phone'   => $order_info['buyer_phone'],//用户手机号
                'trade_no'      => $trade_no,//交易流水号
                'total_fee'     => $total_fee,//退款金额
                'order_amount'  => $order_info['order_amount'],//订单总金额
                'order_add_time'=> $order_info['add_time'],//订单创建时间
                'batch_no'      => $batch_no, //退款单号
                'remark'        => '支付宝原路退款',//退货处理意见
                'mark'          => $refund_state,
                'return_type'   => '1'//退款方式（1：原路退款，2：人工退款）
            );
            if($refund_state == 'SUCCESS'){
                $type = true;
            }else{
                $type = false;
            }
            file_put_contents('/data/test.txt',var_export($data,true),FILE_APPEND);
            $mod->_callbackRouter($data, $type);
            
        }else {
            //验证失败
            echo "fail";die;
        }    
    }

    
    function old(){
        /* 退款正常后删除 */
        //$mod->_callbackRouter($data, $type);
        
        // $table =  \Common\Helper\ToolsHelper::getOrderSnType($batch_no);/* 获取订单类型(00:正常订单，01:拼团订单，02:退货订单，03:退款订单) */
            // if($table == 'return'){//外部交易号不够长 发起的时候无法写全
            //     $table = 'returngoods';
            //     $condition['return_sn'] = $batch_no;
            // }elseif($table == 'refund'){
            //     $table = 'refund';
            //     $condition['refund_sn'] = $batch_no;
            // }
            
            
      //       $web_return_info = M($table)->where($condition)->find();
      //       if(empty($web_return_info)){
      //           echo "fail";die;
      //       }
      //       if($web_return_info['status']==3){ //已经通知过了 已经是处理成功
      //           echo "success";die;
      //       }
      //       if($refund_state == 'SUCCESS'){
      //           //退款成功
      //           if($table == 'refund'){   //整个订单退款成功 修改退款表和订单表
      //               try{
                        // $model = new Model();
                        // $model->master(true);
                        // $model->startTrans();
      //                   $update = array();
      //                   $update['status'] =  '3';
      //                   $update_status = $model->table(C('DB_PREFIX').'refund')->master(true)->where($condition)->save($update);
      //                   if(!$update_status){
      //                       $model->rollback();
      //                       throw  new  \Exception('退款表状态修改失败');
      //                   }
      //                   $refund_info = $web_return_info;
      //                   $order_info = $model->table(C('DB_PREFIX').'orders')->master(true)->getby_order_id(intval($refund_info['order_id']));
      //                   if(empty($order_info)){
      //                       $model->rollback();
      //                       throw  new  \Exception('没查询到订单');
      //                   }
      //                   $order_update = array();
      //                   $order_update['order_state'] =  0;
      //                   $order_update['refund_state'] = 3;
      //                   $order_update['refund_sn']  = intval($refund_info['refund_sn']);
      //                   $order_update['lock_state'] = 0;
      //                   $order_update['finnshed_time']  =  time();
      //                   $order_update_status = $model->table(C('DB_PREFIX').'orders')->master(true)->where(array('order_id'=>$order_info['order_id']))->save($order_update);
      //                   if(!$order_update_status){
      //                       $model->rollback();
      //                       throw  new  \Exception('退款后修改订单表表状态修改失败');
      //                   }
                        // $insert['order_id'] = $order_info['order_id'];
                        // $insert['order_add_time'] = $order_info['add_time'];
                        // $insert['add_time'] = TIMESTAMP;
                        // $insert['cron_type'] = 1;//退款订单
                        // SpreadOrderCronService::addCron($order_info['buyer_id'],$insert,$model);
                        
      //                   $model->commit();

      //                   //退款成功发送短信
      //                   $smsdata = array($refund_info['refund_sn'],$refund_info['refund_amount']);
      //                   SmsService::insert_sms_notice($order_info['buyer_id'],$order_info['buyer_phone'],$smsdata,'refundsuccess');
                        
      //                   echo "success";die;
      //               }catch (\Exception $e){
                        //  $model->rollback();
      //                   echo "fail";die;
      //                   order_log($order_info['order_id'],'order_id', "订单id:{$order_info['order_id']},退款单号:{$refund_info['refund_sn']},支付宝退款成功之后通知修状态失败,".$e->getMessage());
      //               }    
      //           }else if($table=='returngoods'){
      //               $model = new Model();
      //               $update = array();
      //               $update['status'] =  '3';
      //               $update_status =  M($table)->where($condition)->save($update);
      //               if($update_status !== false){
                        // $refund_info = $web_return_info;
      //                   $order_info = $model->table(C('DB_PREFIX').'orders')->master(true)->getby_order_id(intval($refund_info['order_id']));
                        // $insert['order_id'] = $order_info['order_id'];
                        // $insert['order_add_time'] = $order_info['add_time'];
                        // $insert['add_time'] = TIMESTAMP;
                        // $insert['cron_type'] = 2;//退货订单
                        // SpreadOrderCronService::addCron($order_info['buyer_id'],$insert,new Model());
      //                   //退款成功发送短信
      //                   $smsdata = array($refund_info['return_sn'],$refund_info['return_amount']);
      //                   SmsService::insert_sms_notice($order_info['buyer_id'],$order_info['buyer_phone'],$smsdata,'returngoodssuccess');
      //                   echo "success";die;    
      //               }else{
      //                   echo "fail";die;        
      //               }
      //           }
      //       }else{
      //           file_put_contents(__ROOT__.'./test.txt',var_export('2',true),FILE_APPEND);
      //           if($refund_state){  //退款失败
      //               //$update['status'] =  '5';
      //               $update['notify_mark'] = $refund_state. '___支付宝__'.date('Y-m-d H:i:s').'__'.$result_details;
      //               $update_status = M($table)->where($condition)->save($update);
      //               file_put_contents(__ROOT__.'./test.txt',var_export(array($update_status,M($table)->_sql()),true),FILE_APPEND);
      //               if($update_status){
      //                   echo "success";die;
      //               }else{
      //                   echo "fail";die;    
      //               }
      //           }else{
      //               echo "fail";die;
      //           }
      //       }
      //}else {
            //验证失败
       //     echo "fail";die;
       // }      
    }
    
    /**
     * 快递鸟 订阅物流信息接收
     */
    public function  kuaidiniao(){
        $request_data =  json_decode($_POST['RequestData'],true);
        $express_lists = $request_data['Data'];
        if(!empty($express_lists)){
            $setting_model =  new SettingModel();
            $app_id = $setting_model->getSetting('express_query_id');
            foreach($express_lists as $v){
                if(!$v['Success']){
                    continue;
                }
                $express_info = array();
                $express_info['code'] = $v['ShipperCode'];
                $express_info['no'] = $v['LogisticCode'];
                $express_info['data']  = json_encode($v['Traces']);
                $express_info['dateline'] =  time();
                $check = M('express')->where(array('code'=>$express_info['code'],'no'=>$express_info['no']))->find();
                if(empty($check)){
                    M('express')->add($express_info);
                }else{
                    M('express')->where(array('id'=>$check['id']))->save($express_info);    
                }
            }
        }
        $data = array(
                'EBusinessID'=>$app_id,
                'UpdateTime'=>date('Y-m-d H:i:s'),
                'Success'=>true,
                'Reason'=>'ok',
        );
        echo   json_encode($data);die;
    }
}