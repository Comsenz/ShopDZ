<?php
namespace Common\Helper;

class PointsHelper{
	 
	 public static function  addPoints(array $insertarr,$stage){

        if(empty($insertarr['pl_memberid']) || empty($insertarr['pl_membername'])) {
        	return false;
        }
	 	$insertarr = self::getPointsArray($insertarr, $stage);

 	  	if($insertarr['pl_points'] != '0'){
	        $result = M('pointsLog')->add($insertarr);
	        if ($result){
	            //更新用户积分
	            $obj_member = M('member');
	            $upmember_array = array();
	            $upmember_array['member_points'] = array('exp','member_points+'.$insertarr['pl_points']);
	            $obj_member->where(array('member_id'=>$insertarr['pl_memberid']))->save($upmember_array);
	            return true;
	        }else {
	            return false;
	        }
        } else {
        	return false;
        }
	 }
//事务添加积分
	 public static function addPointsTrans(array $insertarr,$stage, $model){

        if(empty($insertarr['pl_memberid']) || empty($insertarr['pl_membername'])) {
        	return false;
        }
	 	$insertarr = self::getPointsArray($insertarr, $stage);

 	  	if($insertarr['pl_points'] != '0'){

	        $result = $model->table(C('DB_PREFIX').'points_log')->master(true)->add($insertarr);
	        if ($result){
	            //更新用户积分
	            $upmember_array = array();
	            $upmember_array['member_points'] = array('exp','member_points+'.$insertarr['pl_points']);
	            $model->table(C('DB_PREFIX').'member')->master(true)->where(array('member_id'=>$insertarr['pl_memberid']))->save($upmember_array);
	            return true;
	        }else {
	            return false;
	        }
        } else {
        	return false;
        }
	 }

     public static function  getPointsArray(array $insertarr,$stage){

        $insertarr['pl_addtime'] = TIMESTAMP;
        $insertarr['pl_stage'] = $stage?$stage:'system';

        //记录原因文字
        switch ($stage){
            case 'register':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '注册会员';
                }
        		$insertarr['pl_points'] = M('setting')->where("name='points_register'")->getField("value");
                break;
            case 'recommend_register':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '推荐注册会员';
                }
                $insertarr['pl_points'] = M('setting')->where("name='points_recommend_register'")->getField("value");
                break;
            case 'sign_in':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '每日签到';
                }
                $insertarr['pl_points'] = M('setting')->where("name='points_sign_in'")->getField("value");
                break;
            case 'give_coupon':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '领取优惠券';
                }
                $insertarr['pl_points'] = -$insertarr['pl_points'];
                break;
            case 'order_comments':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '订单评论商品';
                }
                $insertarr['pl_points'] = M('setting')->where("name='points_order_comments'")->getField("value");
                break;
            case 'order_rate':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '订单'.$insertarr['order_sn'].'赠送积分';
                }
                $insertarr['pl_points'] = 0;
                if ($insertarr['orderprice']){
                	$points_orderrate = M('setting')->where("name='points_order_rate'")->getField("value");
                	$points_ordermax = M('setting')->where("name='points_order_max'")->getField("value");

                    $insertarr['pl_points'] = @intval($insertarr['orderprice'])/100*$points_orderrate;
                    if ($insertarr['pl_points'] > intval($points_ordermax)){
                        $insertarr['pl_points'] = intval($points_ordermax);
                    }
                }
                break;
            case 'share_shopping':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '分享订单'.$insertarr['order_sn'].'购物消费';
                }
                $insertarr['pl_points'] = 0;
                if ($insertarr['orderprice']){
                	$points_share_shopping = M('setting')->where("name='points_order_rate'")->getField("value");
                	$points_ordermax = M('setting')->where("name='points_order_max'")->getField("value");


                    $insertarr['pl_points'] = @intval($insertarr['orderprice'])/100*$points_share_shopping;
                    if ($insertarr['pl_points'] > intval($points_ordermax)){
                        $insertarr['pl_points'] = intval($points_ordermax);
                    }
                }
                break;
            case 'system':
                break;
           /* case 'order_max':
                if (!$insertarr['pl_desc']){
                    $insertarr['pl_desc'] = '订单'.$insertarr['order_sn'].'购物消费';
                }
                $insertarr['pl_points'] = M('setting')->where("name='points_order_max'")->getField("value");
                break; */
            case 'other':
                break;
        }
      	return $insertarr;        
    }

    //会员模块数据字典
	public static function getDataDictionary($value = ''){
        $data['member_state'] = array('1' => '开启', '0' => '禁用');
        $data['member_sex'] = array('0' => '保密', '1' => '男' , '2' => '女');
        //积分规则行为
        $data['points_stage'] = array(
        	'register' 			=> '会员注册',
        	'recommend_register'=> '推荐注册',
        	'sign_in' 			=> '每日签到',
        	'order_comments' 	=> '订单评价',
        	'order_rate'		=> '订单赠送',
        	'share_shopping' 	=> '分享购物赠送',
        	'order_max' 		=> '每笔订单赠送',
        	'give_coupon' 		=> '领取优惠券',
        	'system' 			=> '系统积分增减',
        	'plugin' 			=> '插件',
        );
        if($value && isset($data[$value])) {
        	return $data[$value];
        }
        return $data;
    }
}