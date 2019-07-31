<?php
namespace Common\Service;
use Think\Model;
class PresalesService {
static $status = array(1=>'审核中',2=>'退款中',3=>'已完成',4=>'已拒绝',5=>'退款失败');
static  function getCause($where = array(),$field='*') {
	$mod = M('causes');
	$data =  $mod->field($field)->where($where)->select();
	return $data ? $data : array();
}

static function saveReturn($data) {

	$model = new Model();
	$model->master(true);
	$model->startTrans();
	$flag = 1;
	$data['dateline'] = TIMESTAMP;
	
	$return_sn = \Common\Helper\ToolsHelper::getOrderSn('return');
	$data['return_sn'] = $return_sn;
	$causesResult = $model->table(C('DB_PREFIX').'returngoods')->master(true)->add($data);
	if($causesResult) {
		$order = array('return_state'=>1,'lock_state'=>1);//有退货
		$ordersResult = $model->table(C('DB_PREFIX').'orders')->master(true)->where("order_id=%d",$data['order_id'])->save($order);
		$order_good = array('goods_returnnum'=>$data['return_goodsnum'],'rg_id'=>$causesResult);
		$order_goodsResult = $model->table(C('DB_PREFIX').'order_goods')->master(true)->where("rec_id=%d",$data['rec_id'])->save($order_good);
		if(false !== $ordersResult  && false !==  $order_goodsResult) {
			F($data['order_id'],null);
			$model->commit();
			$flag = 0;
		}else{
			$flag = 1;
			$model->rollback();
		}
	}else{
		$flag = 1;
		$model->rollback();
	}
	return array('code'=>$flag,'return_sn'=>$return_sn);
}

static function saveRefund($data) {
	$model = new Model();
	$model->master(true);
	$model->startTrans();
	$flag = 1;
	$data['dateline'] = TIMESTAMP;
	$refund_sn = \Common\Helper\ToolsHelper::getOrderSn('refund');
	$data['refund_sn'] = $refund_sn;
	$causesResult = $model->table(C('DB_PREFIX').'refund')->master(true)->add($data);
	if($causesResult) {
		$order = array('refund_state'=>1,'refund_amount'=>$data['refund_amount'],'lock_state'=>1,'refund_sn'=>$refund_sn);//全部退款
		$ordersResult = $model->table(C('DB_PREFIX').'orders')->master(true)->where("order_id=%d",$data['order_id'])->save($order);
		if(false !== $ordersResult) {
			$model->commit();
			$flag = 0;
		}else{
			$flag = 1;
			$model->rollback();
		}
	}else{
		$flag = 1;
		$model->rollback();
	}
	return array('code'=>$flag,'refund_sn'=>$refund_sn);
}

static function getRefundDetail($member_uid,$refund_sn) {
	if(empty($member_uid)) return;
	$refund = M('refund');
	$table_pre =  C('DB_PREFIX');
	$where['buyer_id'] = $member_uid;
	$where["`{$table_pre}refund`.".'refund_sn'] = "$refund_sn";
	$lists =  $refund->join(" {$table_pre}orders on {$table_pre}orders.order_id={$table_pre}refund.order_id ")->field("{$table_pre}orders.order_sn,{$table_pre}orders.refund_amount,{$table_pre}orders.lock_state,{$table_pre}orders.refund_state,{$table_pre}refund.*")->where($where)->order("{$table_pre}orders.order_id desc")->find();
	if(!empty($lists)) {
		$lists['dateline'] = date("Y-m-d H:i:s",$lists['dateline']);
		$lists['status_text'] = self::$status[$lists['status']];
		if(!empty($lists['refund_images'])) {
				$images = array();
				$images_arr = explode("\t",$lists['refund_images']);
				foreach($images_arr as $ik=> $image) {
					$image && $images[$ik] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$image;
				}
				$lists['refund_images'] = $images;
			}
		}
	return $lists ? $lists : array();
}

static function getRefundList($member_uid,$page=1,$prepage=20) {
	if(empty($member_uid)) return;
	$refund = M('refund');
	$table_pre =  C('DB_PREFIX');
	$start =( $page-1 ) * $prepage;
	$where['buyer_id'] = $member_uid;
	$lists =  $refund->join(" {$table_pre}orders on {$table_pre}orders.order_id={$table_pre}refund.order_id ")->field("{$table_pre}orders.order_sn,{$table_pre}orders.refund_amount,{$table_pre}refund.status,{$table_pre}refund.dateline,{$table_pre}refund.refund_sn")->where($where)->order("{$table_pre}refund.dateline desc")->limit($start.','.$prepage)->select();

	foreach($lists as $k =>$v) {
		$lists[$k]['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
		$lists[$k]['status_text'] = self::$status[$v['status']];
	}
	return $lists ? $lists : array();
}

static function getRefundListCount($member_uid) {
	if(empty($member_uid)) return 0;
	$refund = M('refund');
	$table_pre =  C('DB_PREFIX');
	$where['buyer_id'] = $member_uid;
	return $count =  $refund->join(" {$table_pre}orders on {$table_pre}orders.order_id={$table_pre}refund.order_id ")->where($where)->field("{$table_pre}orders.order_sn")->count();
}

static  function getReturnList($member_uid,$page=1,$prepage=20) {
	if(empty($member_uid)) return;
	$refund = M('returngoods');
	$table_pre =  C('DB_PREFIX');
	$start =( $page-1 ) * $prepage;
	$where['member_uid'] = $member_uid;
	$lists =  $refund->join(" {$table_pre}order_goods on {$table_pre}order_goods.rec_id={$table_pre}returngoods.rec_id ")->field("{$table_pre}returngoods.order_sn,{$table_pre}returngoods.return_amount,{$table_pre}returngoods.status,{$table_pre}returngoods.dateline,{$table_pre}returngoods.return_sn,{$table_pre}returngoods.rec_id")->where($where)->order("{$table_pre}returngoods.dateline desc")->limit($start.','.$prepage)->select();
	foreach($lists as $k =>$v) {
		$lists[$k]['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
		$lists[$k]['status_text'] = self::$status[$v['status']];
	}
	return $lists ? $lists : array();
}

static function getReturnListCount($member_uid) {
if(empty($member_uid)) return 0;
	$refund = M('returngoods');
	$table_pre =  C('DB_PREFIX');
	$start =( $page-1 ) * $prepage;
	$where['member_uid'] = $member_uid;
	$count =  $refund->join(" {$table_pre}order_goods on {$table_pre}order_goods.rec_id={$table_pre}returngoods.rec_id ")->field("{$table_pre}returngoods.order_sn")->where($where)->count();
	return $count;
}

static function getReturnDetail($member_uid,$return_sn) {
	if(empty($member_uid) || empty($return_sn)) return;
	$refund = M('returngoods');
	$table_pre =  C('DB_PREFIX');
	$where['member_uid'] = $member_uid;
	$where['return_sn'] = $return_sn;
	$lists =  $refund->join(" {$table_pre}order_goods on {$table_pre}returngoods.rec_id={$table_pre}order_goods.rec_id ")->field("{$table_pre}order_goods.*,{$table_pre}returngoods.*")->where($where)->order("{$table_pre}returngoods.dateline desc")->find();
	if(!empty($lists)) {
		$lists['dateline'] = date("Y-m-d H:i:s",$lists['dateline']);
		$lists['goods_image'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$lists['goods_image'];
		$lists['status_text'] = self::$status[$lists['status']];
		if(!empty($lists['return_images'])) {
				$images_arr = explode("\t",$lists['return_images']);
				foreach($images_arr as $ik=> $image) {
					$image && $images[$ik] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$image;
				}
				$lists['return_images'] = $images;
			}
		}
	return $lists ? $lists : array();
}
	/**
     * 获取退款数据
     * @param $data 修改数据的参数
     * @param $type 退款的状态（refund:退款，return退货）
     * @param $refund_info 返回值
     * @param $order_info 返回值
     */
    static function get_return($sn, $type){
  		if($type == 'return'){//外部交易号不够长 发起的时候无法写全
            $table = 'returngoods';
            $condition['return_sn'] = $sn;
        }elseif($type == 'refund'){
            $table = 'refund';
            $condition['refund_sn'] = $sn;
        }
        $refund_info = M($table)->where($condition)->find();
        if(!empty($refund_info)){
        	$order_info = M('orders')->getby_order_id(intval($refund_info['order_id']));
        }
        return array('refund_info'=>$refund_info,'order_info'=>$order_info?$order_info:'');
    }
    /**
     * 写日志
     */
    static function WriteLog($text) {
        $month = date('Y-m');
        file_put_contents ( APP_ROOT."/data/Logs/admin_pay/".$month.".txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
    }
	/**
     * 退货成功修改数据库
     * @param $data 修改数据的参数
     * @param $type 退款的状态（true成功，false失败）
     */
    static function save_return($data, $type){
    	$condition['status'] = array('in',array('2','5'));
        $condition['return_sn'] = $data['batch_no'];
        if($type){
            $update = array();
            $update['status'] = '3';
            $update['remark'] = $data['remark'];
            $update_status = M('returngoods')->where($condition)->save($update);
            if($update_status){
                $insert['order_id'] = $data['order_id'];
                $insert['order_add_time'] = $data['order_add_time'];
                $insert['add_time'] = TIMESTAMP;
                $insert['cron_type'] = 2;//退货订单
                $insert['cron_time'] = 0;
                SpreadOrderCronService::addCron($data['buyer_id'],$insert,new Model());
                //退货成功发送短信
                $smsdata = array('return_sn' => $data['batch_no'] , 'total' => $data['total_fee']);
                SmsService::insert_sms_notice($data['buyer_id'],$data['buyer_phone'],$smsdata,'returngoodssuccess');
                if($data['payment_code'] == 'wx'){
                	return array('code'=>0,'msg'=>'处理成功','data'=>'Presales/returns');
	            }elseif($data['payment_code'] == 'alipay'){
	            	return array('code'=>0,'msg'=>'处理成功','data'=>'Presales/returns');
	            }elseif($data['payment_code'] == 'wxx'){
                    return array('code'=>0,'msg'=>'处理成功','data'=>'Presales/returns');
                }else{
                	return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/returns');
                }
            }else{
            	if($data['payment_code'] == 'wx'){
            		return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/returns');
	            }elseif($data['payment_code'] == 'alipay'){
	            	return array('code'=>1,'msg'=>'处理成功','data'=>'Presales/returns');
	            }elseif($data['payment_code'] == 'wxx'){
                    return array('code'=>0,'msg'=>'处理成功','data'=>'Presales/returns');
                }else{
                	return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/returns');
                }
            }
        }else{
            $update = array();
            //$update['status'] =  '5';
            if($data['payment_code'] == 'wx'){
	            $update['notify_mark'] = '_微信_'.date('Y-m-d H:i:s').'_发起原路退款失败';
	        }elseif($data['payment_code'] == 'alipay'){
	        	$update['notify_mark'] = '_支付宝_'.date('Y-m-d H:i:s').'_发起原路退款失败';
	        }elseif($data['payment_code'] == 'wxx'){
                $update['notify_mark'] = '_微信小程序_'.date('Y-m-d H:i:s').'_发起原路退款失败';
            }
	        $update['remark'] = $update['notify_mark'];
            $a =  M('returngoods')->where($condition)->save($update);
            
            if($data['payment_code'] == 'wx'){
            	return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/returns');
            }elseif($data['payment_code'] == 'alipay'){
            	return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/returns');
            }elseif($data['payment_code'] == 'wxx'){
                return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/returns');
            }else{
            	return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/returns');
            }
        }
    }
    /**
     * 退款成功修改数据库
     * @param $data 修改数据的参数
     * @param $type 退款的状态（true成功，false失败）
     */
    static function save_refund($data, $type){
    	$condition['status'] = array('in',array('2','5'));
        $condition['refund_sn'] = $data['batch_no'];
        if($type){
            try{
                $model = new Model(); 
                $model->master(true);
                $model->startTrans();
                $update = array();
                $update['status'] =  '3';
                $update['remark'] = $data['remark'];
                $update_status = $model->table(C('DB_PREFIX').'refund')->where($condition)->save($update);

                if(!$update_status){
                    $model->rollback();
                    throw  new  \Exception('退款表状态修改失败');
                }

                $order_update = array();
                $order_update['order_state'] =  0;
                $order_update['refund_state'] = 3;
                $order_update['refund_sn']  = $data['batch_no'];
                $order_update['lock_state'] = 0;
                $order_update['finnshed_time']  =  time();
                
                $order_update_status = $model->table(C('DB_PREFIX').'orders')->where(array('order_id'=>$data['order_id']))->save($order_update);
                
                if(!$order_update_status){
                    $model->rollback();
                    throw  new  \Exception('退款后修改订单表表状态修改失败');
                }

                $insert['order_id'] = $data['order_id'];
                $insert['order_add_time'] = $data['order_add_time'];
                $insert['add_time'] = TIMESTAMP;
                $insert['cron_type'] = 1;//退款订单
                $insert['cron_time'] = 0;
                SpreadOrderCronService::addCron($data['buyer_id'],$insert,$model);
                $model->commit();      
                // //退款成功发送短信
                $smsdata = array('refund_sn' => $data['batch_no'],'total' => $data['total_fee']);
                SmsService::insert_sms_notice($data['buyer_id'],$data['buyer_phone'],$smsdata,'refundsuccess');
                if($data['payment_code'] == 'wx'){
                	return array('code'=>0,'msg'=>'处理成功','data'=>'Presales/refunds');
                }elseif($data['payment_code'] == 'alipay'){
                	return array('code'=>0,'msg'=>'处理成功','data'=>'Presales/refunds');
                }elseif($data['payment_code'] == 'wxx'){
                	return array('code'=>0,'msg'=>'处理成功','data'=>'Presales/refunds');
                }else{
                	return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/refunds');
                }
            }catch (\Exception $e){
                $model->rollback();
                order_log($data['order_id'],'order_id', "订单id:{$data['order_id']},退款单号:{$data['batch_no']},{$data['payment_code']}退款成功之后修状态失败,".$e->getMessage());
                if($data['payment_code'] == 'wx'){
                	return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/refunds');
                }elseif($data['payment_code'] == 'alipay'){
                	return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/refunds');
                }elseif($data['payment_code'] == 'wxx'){
                	return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/refunds');
                }else{
                	return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/refunds');;
                }
                
            }
        }else{
            $update = array();
            //$update['status'] =  '5';
            if($data['payment_code'] == 'wx'){
	            $update['notify_mark'] = '_微信_'.date('Y-m-d H:i:s').'_发起原路退款失败';
	        }elseif($data['payment_code'] == 'alipay'){
	        	$update['notify_mark'] = '_支付宝_'.date('Y-m-d H:i:s').'_发起原路退款失败';
	        }elseif($data['payment_code'] == 'wxx'){
                $update['notify_mark'] = '_微信小程序_'.date('Y-m-d H:i:s').'_发起原路退款失败';
            }
            $update['remark'] = $update['notify_mark'];
            $a =  M('refund')->where($condition)->save($update);

            if($data['payment_code'] == 'wx'){
            	return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/refunds');
            }elseif($data['payment_code'] == 'alipay'){
            	return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/refunds');
            }else{
                return array('code'=>1,'msg'=>'处理失败','data'=>'Presales/refunds');
            }
        }
    }

}