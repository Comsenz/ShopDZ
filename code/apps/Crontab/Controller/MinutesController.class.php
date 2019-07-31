<?php
namespace Crontab\Controller;
use Think\Controller;
use  Common\Service\OrdersService;
use  Common\Service\SmsService;
use  Common\Service\NoticeService;
use  Common\Service\SendSms;
use  Common\Wechat\WxapiController;
use Admin\Model\SettingModel;
use Common\Model\ReturnModel;
use Think\Model;
set_time_limit(0);
class MinutesController extends Controller {
    public function __construct(){
        parent::__construct();
        if(!IS_CLI){
            die('permisson dined');
        }
        define('NO_PAY_ORDER_TIME_OUT_TIME',3600); //订单超时未支付取消时间  1小时
    }
    public function index(){
        $this->on_pay_order_time_out_cancel();   //未支付订单超时取消
        // $this->deleteGroupJoinPerson();//拼团未支付过期
        // $this->groupRetfundBack();	//拼团失败 退款
        //   $this->groupBackStorage();	//拼团失败 退库存
        //   $this->groupBackPay();	//拼团失败 退款
        //   $this->groupRetfundFail();//拼团失败继续退款
        $this->sendCronMessage();
        $this->send_coupon_sms();
        $this->set_coupon_expire();
        $this->send_sms_notice();
        $this->send_order_sms_notice();
        $this->sendSystemCronMessage();
        $this->auto_affirm_goods();
    }

    /**
     * 支付七天自动确认收货
     */
    public function auto_affirm_goods($status = 0){
        $limit   =   1000;  //每次处理1000条
        $where = $condition = array();
        /* 查询未确认收货的数据 */
        $condition['order_state'] =  30;//支付成功的状态
        $model =  M('orders');
        $order_id =  $model->field('order_id')->where($condition)->limit($limit)->select();
        if(empty($order_id)){
            return true;
        }
        foreach($order_id as $k=>$v) {
            $order_ids[]=$v['order_id'];
        }
        if(empty($order_ids)) {
            return true;
        }
        /* 拼装订单表查询条件 */
        $where['order_id'] = array('in',implode(',',$order_ids));//订单的ID集
        //$where['shiping_time'] =  array('lt',time()-7*24*60*60);
        $endtime = time()-7*24*60*60;
        $where['shipping_time'] = array('between',"1,$endtime");
        /* 查询七天前发货未确认收货的数据 */
        $neworder_id = M('order_common')->where($where)->field('order_id')->select();
        if(empty($neworder_id)){
            return true;
        }
        foreach($neworder_id as $kk=>$vv) {
            $save = array(
                'order_id'=>$vv['order_id'],
                'order_state'=>40
            );
            $result =  $model->save($save);
        }
        return false;
    }

    /**
     * 未支付的订单超时之后自动取消
     */
    public  function   on_pay_order_time_out_cancel(){
        $limit   =   1000;  //每次处理1000条
        $condition = array();
        $condition['order_state'] =  10;
        $condition['add_time'] =  array('lt',time()-NO_PAY_ORDER_TIME_OUT_TIME);
        $order_list =  M('orders')->where($condition)->order('order_id asc')->limit($limit)->select();
        if(empty($order_list)){
            return   true;
        }
        foreach($order_list  as  $order_info){
            //发起支付不到1小时
            if($order_info['payment_starttime']>0  &&  (time()-$order_info['payment_starttime']<3600)){
                continue;
            }
            $state  = false;
            $state = OrdersService::changeOrderStateCancel($order_info['order_id'],'system');
        }
    }


    /** 站内信
     *  消息发送队列
     */
    public function sendCronMessage(){
        $num = 1000;
        $where = array(
            'cron_status'   => 0,
            'crontime'      => 0,
            'type'          => 1,
        );
        $smscron_list = M('sms_cron')->where($where)->limit($num)->select();
        if(!empty($smscron_list)){
            foreach($smscron_list as $k => $v){
                $member_ids =unserialize($v['mids']);
                foreach($member_ids as $mk => $mv){

                    $member_info = M('member')->where(array('member_id' => $mv))->field('member_mobile')->find();
                    $data = array();
                    $data['to_id'] = $mv;
                    $data['to_name'] = $mv == 0 ? '' : $member_info['member_mobile'];

                    $data['title'] = $v['title'];
                    $data['message'] = $v['message'];
                    $data['from_id'] = $v['from_id'];
                    $data['from_name'] = $v['from_name'];
                    $data['type'] = $v['type'];
                    $data['status'] = 0;
                    $data['del_type'] = 0;
                    $data['dateline'] = $v['dateline'];
                    $data['readtime'] = 0;
                    $data['parent_sid'] = $v['parent_sid'];
                    $data['message_ismore'] = $v['message_ismore'];
                    $result = M('sms')->add($data);
                }
                M('sms_cron')->where(array('cid' => $v['cid']))->save(array('cron_status' => 1,'crontime'=>time()));
            }
        }
    }

    //发送优惠券过期提醒通知（短信）
    public function send_coupon_sms(){
        $num = 1000;
        $m = M('redpacket');
        $one_time = strtotime(date('Y-m-d'))+3600*24;//time()-(3600*24);
        $two_time = strtotime(date('Y-m-d'))+3600*24*2;//time()-(3600*24*2);
        $where = "rpacket_end_date>".$one_time." AND rpacket_end_date<".$two_time." AND rpacket_state=1 AND rpacket_sms_type=0";
        $redlist = $m->where($where)->limit($num)->select();
        if(!empty($redlist)){
            foreach($redlist as $k => $v){
                $smsdata = array('coupn_sn' => date('Y-m-d',$v['rpacket_end_date']));

                $sms = SmsService::insert_sms_notice($v['rpacket_owner_id'],$v['rpacket_owner_name'],$smsdata,'couponexpires');
                if($sms == true){
                    $send = array(
                        'rpacket_sms_type' => 1,
                    );
                    $sendresult = $m->where('rpacket_id='.$v['rpacket_id'])->save($send);
                }
            }
        }
    }


    //优惠券过期
    public function set_coupon_expire(){
        $m = M('redpacket');
        $where = "rpacket_end_date <".time()." AND rpacket_state=1";
        $m_list = $m->where($where)->limit(2000)->select();
        if (empty($m_list)){
            foreach ($m_list as $info) {
                $condition = array();
                $condition['rpacket_id'] = $info['rpacket_id'];
                $update = array(
                    'rpacket_used_date' => time(),
                    'rpacket_state'     => 3,
                );
                $m->where($condition)->save($update);
            }
        }
        
        $tm = M('redpacket_template');
        $twhere = "rpacket_t_end_date <".time()." AND rpacket_t_state=1";
        $tm_list = $tm->where($twhere)->limit(2000)->select();
        if (empty($tm_list)){
            foreach ($tm_list as $info) {
                $condition = array();
                $condition['rpacket_t_id'] = $info['rpacket_t_id'];
                $update = array(
                    'rpacket_t_updatetime' => time(),
                    'rpacket_t_state'     => 2,
                );
                $tm->where($condition)->save($update);
            }
        }
    }

    //发送短信
    public function send_sms_notice($type=array()){
        $num = 1000;
        $m = M('send_msg');
        $where = array();
        if(!empty($type)) {
            $typestr = implode(',',$type);
            $where['send_type'] = " in (".$typestr.")";
        }
        $sms_data = $m->where('is_send in (0,2)')->where($where)->limit($num)->select();

        if(!empty($sms_data)){
            foreach($sms_data as $v){
                if($v['send_phone'] != 'wx'){
                    $result = SendSms::sendTemplateSMSTo($v['send_phone'],$v['send_text']);
                }
                if($result['code'] == 200){
                    $data = array(
                        'is_send'   => 1,
                        'send_time' => time()
                    );
                }else{
                    if($v['is_send'] == 2){
                        $data = array(
                            'is_send'   => 3,
                            'send_time' => time()
                        );
                    }else{
                        $data = array(
                            'is_send'   => 2,
                            'send_time' => time()
                        );
                    }
                }
                if($v['send_phone'] == 'wx'){
                    $data = array(
                        'is_send'   => 1,
                        'send_time' => time()
                    );
                }
                $m->where(array('send_id' => $v['send_id']))->save($data);
                $this->send_wx_message($v['member_id'],$v['send_wxtext']);
                $this->send_notice_message($v['member_id'],$v['send_type'],$v['send_webtext']);
                
            }
        }
    }


    //发送订单付款短信
    public function send_order_sms_notice($type=array()){
        $num = 1000;
        $m = M('send_msg');
        $where = array();
        if(!empty($type)) {
            $typestr = implode(',',$type);
            $where['send_type'] = " in (".$typestr.")";
        }
        $oldtime = time()-1800;
        $sms_data = $m->where('is_send=4 AND send_type="orderpayment" AND add_time<'.$oldtime)->where($where)->limit($num)->select();
        if(!empty($sms_data)){
            foreach($sms_data as $v){
                $send_data = unserialize($v['send_data']);
                $order_info = OrdersService::getOrderByOrderSn($send_data['order_sn']);
                if($order_info['order_state'] != 10){
                    $data = array(
                        'is_send'   => 5
                    );
                }else{
                    if($v['send_phone'] != 'wx'){
                        $result = SendSms::sendTemplateSMSTo($v['send_phone'],$v['send_text']);
                    }
                    if($result['code'] == 200){
                        $data = array(
                            'is_send'   => 1,
                            'send_time' => time()
                        );
                    }else{
                        if($v['is_send'] == 2){
                            $data = array(
                                'is_send'   => 3,
                                'send_time' => time()
                            );
                        }else{
                            $data = array(
                                'is_send'   => 2,
                                'send_time' => time()
                            );
                        }
                    }
                }
                if($v['send_phone'] == 'wx'){
                    $data = array(
                        'is_send'   => 1,
                        'send_time' => time()
                    );
                }
                $m->where(array('send_id' => $v['send_id']))->save($data);
                if($order_info['order_state'] == 10){
                    $this->send_wx_message($v['member_id'],$v['send_wxtext']);
                    $this->send_notice_message($v['member_id'],$v['send_type'],$v['send_webtext']);
                }
            }
        }
    }
    public function send_wx_message($mobile,$content){
        if($mobile && !empty($content)){
            $setingMoudel = new SettingModel();
            $wx_AppID = $setingMoudel->getSetting('wx_AppID');
            $wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
            if ($wx_AppID && $wx_AppSecret){
                $wxapi = new WxapiController($wx_AppID,$wx_AppSecret);
                $wxapi->send_wx_notice($mobile,$content); 
            }
        }
    }

    /**
     * 发送站内信模板
     **/
    public function send_notice_message($mobile,$type,$content){
        if($mobile && !empty($content)){
            $not = new NoticeService();
            $not->send_template_notice($mobile,$type,$content);

        }
    }

    /** 系统消息
     *  消息发送队列
     */
    public function sendSystemCronMessage(){
        $num = 10;
        $c = 1000;
        $sms = M('sms_cron');
        $member = M('member');
        $where = array(
            'cron_status'   => 0,
            'crontime'      => 0,
            'type'          => 3,
        );
        $smscron_list = $sms->where($where)->limit($num)->select();
        if(!empty($smscron_list)){
            foreach($smscron_list as $k => $v){
                $member_count = $member->count();
                $for = ceil($member_count/$c);
                for($i=0;$i<$for;$i++){
                    $start = $i*$c;
                    $member_ids = $member->limit($start.','.$c)->field('member_id,member_mobile')->select();
                    foreach($member_ids as $mk => $mv){
                        $data = array();
                        $data['to_id'] = $mv['member_id'];
                        $data['to_name'] = $mv['member_mobile'];
                        $data['title'] = $v['title'];
                        $data['message'] = $v['message'];
                        $data['from_id'] = $v['from_id'];
                        $data['from_name'] = $v['from_name'];
                        $data['type'] = $v['type'];
                        $data['status'] = 0;
                        $data['del_type'] = 0;
                        $data['dateline'] = $v['dateline'];
                        $data['readtime'] = 0;
                        $data['parent_sid'] = $v['parent_sid'];
                        $data['message_ismore'] = $v['message_ismore'];
                        $result = M('sms')->add($data);
                    }
                }
                M('sms_cron')->where(array('cid' => $v['cid']))->save(array('cron_status' => 1,'crontime'=>time()));
            }
        }
    }
    //拼团失败 先更新团的状态为失败，第二部在异步跑退款
    public function groupRetfundBack() {
        $time = TIMESTAMP;
        $where['starttime'] = array('elt',$time);
        $where['active_end_time'] = array('gt',$time);
        $model = D('Group');
        $returnModel =  new ReturnModel();
        $groupLists = $model->getGroupList($where,$page=1,$prepage = 500);
        $startTrans = new Model();
        $startTrans->master(true);
        $startTrans->startTrans();
        foreach( $groupLists as $k => $groupList ) {
            $active_id = $groupList['id'];
            $group_hour = $groupList['group_hour'];
            $group_person_num = $groupList['group_person_num'];
            $group_price = $groupList['group_price'];
            $limit_time = $time - $group_hour*3600;
            $groupGroupWhere['add_time'] = array('lt',$limit_time);
            $groupGroupWhere['status'] = 0;//
            $groupGroupWhere['num'] =array('neq',$group_person_num);//num 人员不满
            $getGroupGroups = $model->getGroupGroups($groupGroupWhere);
            foreach( $getGroupGroups as $gk => $getGroupGroup ) {
                $groupid = $getGroupGroup['id'];
                $group_group_save = array(
                    'id'=>$groupid,
                    'status'=>-1,
                );
                $group_group_res = $startTrans->table(C('DB_PREFIX').'group_group')->save($group_group_save);
                if( $group_group_res ) {
                    $startTrans->commit();
                }else{
                    $startTrans->rollback();
                }
            }
        }
    }
    //status =-1 拼团失败的时候，查出退款失败的用户,退库存
    public function groupBackStorage() {
        $whereJoin['status'] = -1;//拼团活动失败
        $whereJoin['invisible'] = 0;//支付状态
        $whereJoin['refund_status'] = array('in','0,2');
        $joins = D('Group')->getgroupByJoin($whereJoin);
        $startTrans = new Model();
        $startTrans->master(true);
        $startTrans->startTrans();
        $group_id = array();
        foreach( $joins as $gk => $join ) {
            $flag = false;
            $groupInfo = D('Group')-> getGroup($join['active_id']);//获取商品id
            $goods_num = 1;
            $save = $where = array();
            if(empty($join['trade_no'])) {
                $save = array(
                    'id' => $join['id'],
                    'invisible' => -1,//直接取消
                );
                $group_join_res = $startTrans->table(C('DB_PREFIX').'group_join')->save($save);
                $goodResult = $startTrans->table(C('DB_PREFIX').'goods')->master(true)->where('goods_id=%d ',array($groupInfo['goods_id']))->setInc('goods_storage',$goods_num);
                if($group_join_res  && $goodResult) {
                    $flag = true;
                }
            }else{

                $goodResult = $startTrans->table(C('DB_PREFIX').'goods')->master(true)->where('goods_id=%d ',array($groupInfo['goods_id']))->setInc('goods_storage',$goods_num);
                $save = array(
                    'refund_status' => -2,//待退款
                );
                $where['id'] = $join['id'];
                $where['refund_status'] = array('not in','-2,1');//预防group的计划任务字段失败防止重复
                $group_join_res = $startTrans->table(C('DB_PREFIX').'group_join')->where($where)->save($save);
                if($group_join_res  && $goodResult) {
                    $flag = true;
                }
            }
            if($flag) {
                $startTrans->commit();
            }else{
                $startTrans->rollback();
            }
        }
    }
    //status =-1 拼团失败的时候，查出退款失败的用户,退钱
    public function groupBackPay() {
        $startTrans = new Model();
        $startTrans->master(true);
        $startTrans->startTrans();
        $whereJoin['refund_status'] = -2;//待退款的状态
        $whereJoin['trade_no'] =array('EXP','is not null');
        $whereJoin['status'] = -1;//拼团活动失败
        $joins = D('Group')->getgroupByJoin($whereJoin);
        $time = TIMESTAMP;
        $returnModel =  new ReturnModel();
        foreach( $joins as $gk => $join ) {
            $groupInfo = D('Group')-> getGroup($join['active_id']);
            $random = \Common\Helper\LoginHelper::random(2,$numeric=1);
            $doc = substr($join['order_sn'],-2);
            $refund_sn = date('YmdHis').$random.$doc;
            //$refund_sn = \Common\Helper\ToolsHelper::getOrderSn('group');//退款
            $save = array(
                'id' => $join['id'],
                'refund_order_sn' => $refund_sn,
                'refund_time' => $time,
            );
            $group_join_res = $startTrans->table(C('DB_PREFIX').'group_join')->save($save);
            if( $group_join_res ) {
                $startTrans->commit();
                $re_data = array(
                    'batch_no'=>$refund_sn,
                    'trade_no'=>$join['trade_no'],
                    'total_fee'=>$join['order_amount'],
                    'mark'=>'拼团失败自动退款',
                    'remark'=>'拼团失败自动退款',
                    'order_amount'=>$join['order_amount'],
                    'returnurl'=>'',
                    'payment_code'=>$join['payment_code'],
                    'return_type'=>'1',
                    'return_id'=>'',
                    'buyer_id'=>'',
                    'buyer_phone'=>'',
                    'order_add_time'=>$join['add_time'],
                );
                $router_res = $returnModel->router( $re_data );
            }else{
                $startTrans->rollback();
            }
        }
    }



    //拼团退款失败 继续退款
    public function groupRetfundFail() {
        $time = TIMESTAMP;
        $model = D('Group');
        $returnModel =  new ReturnModel();
        $startTrans = new Model();
        $startTrans->master(true);
        $startTrans->startTrans();

        // $groupWhere['invisible'] = 0;
        $groupWhere['refund_status'] = -1;
        $groupWhere['trade_no'] = array('EXP','is not null');
        $joins = $model->getGroupJoin($groupWhere);

        foreach( $joins as $jk => $join ) {
            // $refund_sn = \Common\Helper\ToolsHelper::getOrderSn('group');//退款
            $re_data = array(
                'batch_no'=>$join['refund_order_sn'],
                'trade_no'=>$join['trade_no'],
                'total_fee'=>$join['order_amount'],
                'mark'=>'拼团失败自动退款',
                'remark'=>'拼团失败自动退款',
                'order_amount'=>$join['order_amount'],
                'returnurl'=>'',
                'payment_code'=>$join['payment_code'],
                'return_type'=>'1',
                'return_id'=>'',
                'buyer_id'=>'',
                'buyer_phone'=>'',
                'order_add_time'=>$join['add_time'],
            );
            $router_res = $returnModel->refundQuery( $re_data );
            if($router_res)  {
                $save = array(
                    'id' => $join['id'],
                    'refund_status' => 1,
                    //'refund_order_sn' => $refund_sn,
                    //'refund_time' => $time,
                );
                $group_join_res = $startTrans->table(C('DB_PREFIX').'group_join')->save($save);
                if( $group_join_res ) {
                    $startTrans->commit();
                }else{
                    $startTrans->rollback();
                }
            }
        }

    }
    //删除用户参与拼团但是没有付款的情况
    function deleteGroupJoinPerson() {
        $time = TIMESTAMP;
        $groupGroupWhere['trade_no'] = array('EXP','is null');//未支付
        $groupGroupWhere['invisible'] = 0;
        $groupGroupWhere['add_time'] = array('ELT',$time-5*60);
        $getGroupJoins = D('Group')->getGroupJoin($groupGroupWhere);
        $model = new Model();
        $model->master(true);
        $model->startTrans();

        foreach( $getGroupJoins as $gk => $join ) {
            $groupInfo = D('Group')-> getGroup($join['active_id']);
            $buyer_id = $join['buyer_id'];
            $group_id = $join['group_id'];
            $groupgroupwhere['id'] = $group_id;
            $groupgroupwhere['buyer_id'] = $buyer_id;
            $groupgroupInfo = D('Group')-> getGroupGroup($groupgroupwhere);

            $save = array(
                'id'=>$join['id'],
                'invisible'=>-1,
            );
            //setField

            $joinres = $model->table(C('DB_PREFIX').'group_join')->master(true)->save($save);
            if($groupgroupInfo){
                $group_save = array(
                    'num'=>0,
                    'status'=>-1,
                );
                $group_group = $model->table(C('DB_PREFIX').'group_group')->master(true)->lock(true)->where('id=%d',array($join['group_id']))->save($group_save);
            }else{
                $group_group = $model->table(C('DB_PREFIX').'group_group')->master(true)->lock(true)->where('id=%d',array($join['group_id']))->setDec('num',1);
            }
            $goods_num = 1;
            $goodResult = $model->table(C('DB_PREFIX').'goods')->master(true)->where('goods_id=%d ',array($groupInfo['goods_id']))->setInc('goods_storage',$goods_num);
            if($joinres && $group_group && $goodResult) {
                $model->commit();
            }else{
                $model->rollback();
            }
        }
    }

}
