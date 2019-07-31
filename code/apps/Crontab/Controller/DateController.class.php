<?php
namespace Crontab\Controller;
use Think\Controller;
use  Common\Service\OrdersService;
class DateController extends Controller {
    public function __construct(){
        parent::__construct();
        define('ORDER_AUTO_RECEIVE_DAY',15); //订单发货之后自动确认收货时间 默认15天
    }
    public function index(){
        $this->order_auto_complete();//15天之后自动确认收货
        $this->day_count(); //每日统计    
    }
    
    
    /**
     * 每天的统计信息
     */
    public function day_count(){
        $stime =    strtotime(date('Y-m-d 00:00:00',time()-86400));
        $etime =    strtotime(date('Y-m-d 23:59:59',time()-86400));
        $date =      date('Ymd',$stime);
        $insert  = array();
        $new_member_condition = array();
        $new_member_condition['member_time'] = array('between',array($stime,$etime));
        //会员注册人数
        $insert['new_member_num']  = M('member')->where($new_member_condition)->count();
        $new_order_condition = array();
        $new_order_condition['add_time'] = array('between',array($stime,$etime));
        $new_order_condition['order_state'] = array('gt',0);
        
      
        
        //订单下单数
        $insert['order_num']  = intval(M('orders')->where($new_order_condition)->count()); 
        //订单下单总额
        $insert['order_amount']  =price_format(M('orders')->where($new_order_condition)->sum('order_amount'));
       
        
        //商品销量
        $order_ids = M('orders')->field('order_id')->where($new_order_condition)->limit(false)->select();
        if(!empty($order_ids)){
            $goods_count_order_ids = array();
            foreach($order_ids as $v){
                $goods_count_order_ids[]  = $v['order_id'];
            }
            $insert['sell_goods_num'] =    intval(M('order_goods')->where(array('order_id'=>array('in',$goods_count_order_ids)))->sum('goods_num'));
        }else{
            $insert['sell_goods_num'] =  0;
        }
        $pay_order_condition = array();
        $pay_order_condition['payment_time'] = array('between',array($stime,$etime));
        //订单支付笔数
        $insert['order_pay_num']  = intval(M('orders')->where($pay_order_condition)->count());
        //订单支付总额
        $insert['order_pay_amount']  =price_format(M('orders')->where($pay_order_condition)->sum('order_amount'));
        //订单支付人数
        $insert['pay_member_num']  = intval(M('orders')->where($pay_order_condition)->count('distinct(buyer_id)'));
        //客单价
        $insert['per_member_pay']  =   $insert['pay_member_num'] > 0 ? price_format($insert['order_pay_amount']/ $insert['pay_member_num']) :  0; 
        
        
        $insert['count_date']  = $date;
        $insert['count_year']  =  date('Y',$stime);
        $insert['count_month']  = date('n',$stime);
        $insert['stime']  = $stime;
        $insert['etime']  = $etime;
        $check =   M('day_count')->where(array('count_date'=>$date))->find();
        if(empty($check)){
            M('day_count')->add($insert);
        }else{
            M('day_count')->where(array('id'=>$check['id']))->save($insert);
        }
    }
    /**
     * 已发货订单15天之后自动确认收货
     */
    public function   order_auto_complete(){
        $condition = array();
        $condition['order_state'] = 30;
        $condition['lock_state'] = 0;
        $condition['delay_time'] = array('lt',TIMESTAMP - ORDER_AUTO_RECEIVE_DAY * 86400);
        $limit   =   1000;  //每次处理1000条
        $order_list =  M('orders')->where($condition)->order('order_id asc')->limit($limit)->select();
        if(empty($order_list)){
            return   true;
        }
        foreach($order_list  as  $order_info){
            $state = OrdersService::changeOrderStateReceive($order_info['order_id'],'system');
        }
    }
    
    public function   test(){  //造假的统计数据
        for($i=0;$i<=3650;$i++){
            $stime =    strtotime(date('Y-m-d 00:00:00',time()-86400)) - 86400*$i;
            $etime =    strtotime(date('Y-m-d 23:59:59',time()-86400)) - 86400*$i;
            $date =      date('Ymd',$stime);
            $insert  = array();
            $insert['new_member_num']  = rand(1,500);
            //订单下单数
            $insert['order_num']  = rand(200,500);
            //订单下单总额
            $insert['order_amount']  =rand(30000,50000);
            $insert['sell_goods_num'] =  rand(500,1000);
            $insert['order_pay_num']  =  rand(100,200);
            //订单支付总额
            $insert['order_pay_amount']  = rand(20000,30000);
            //订单支付人数
            $insert['pay_member_num']  =  rand(80,100);
            //客单价
            $insert['per_member_pay']  =   rand(500,5000);
            $insert['count_date']  = $date;
            $insert['stime']  = $stime;
            $insert['etime']  = $etime;
            $insert['count_year']  =  date('Y',$stime);
            $insert['count_month']  = date('n',$stime);
            $check =   M('day_count')->where(array('count_date'=>$date))->find();
            if(empty($check)){
                M('day_count')->add($insert);
            }else{
                M('day_count')->where(array('id'=>$check['id']))->save($insert);
            }
        }
    }
}