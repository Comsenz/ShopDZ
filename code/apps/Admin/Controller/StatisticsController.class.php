<?php
namespace Admin\Controller;
use Think\Controller;
use  Common\Service\StatisticsService;
class StatisticsController extends BaseController {

     
	public function order(){ //订单统计
	    $search_arr = I('request.');
        if(!$search_arr['search_type']){
            $search_arr['search_type'] = 'day';
        }
        if(!$search_arr['search_key']){
            $search_arr['search_key'] = 'amount';
        }
	    $search_time = StatisticsService::init_date($search_arr);
        if($search_arr['search_type'] == 'diydate'){
            $search_arr['diydate'] = $search_time;
        }
        $this->assign('search_arr',$search_arr);
	    $condition = $curr_arr = $up_arr = array();
        $count = array(
            'order_num'     => 0,
            'order_amount'  => 0,
        );
	    $condition['add_time'] = array('between',array($search_time['stime'],$search_time['etime']));
        $condition['order_state'] = array('gt',10);
	    $field = ' count(*) as order_num, sum(order_amount) as order_amount';
	    if($search_arr['search_type'] == 'day'){
            for($i=0; $i<24; $i++){
                //统计图数据
                $curr_arr['order_num'][$i] = 0;//今天
                $up_arr['order_num'][$i] = 0;//昨天
                $curr_arr['order_amount'][$i] = 0;//今天
                $up_arr['order_amount'][$i] = 0;//昨天
                //横轴
                $stat_arr['order_num']['xAxis']['categories'][] = "$i";
                $stat_arr['order_amount']['xAxis']['categories'][] = "$i";
            }
            $yesterday_day = @date('d', $search_time['stime']);//前一天日期
            $today_day = @date('d', $search_time['etime']);//前两天日期
	    	$field .= ' ,DAY(FROM_UNIXTIME(add_time)) as dayval,HOUR(FROM_UNIXTIME(add_time)) as hourval';
	    	$_group = 'dayval,hourval';
	    	$list = StatisticsService::get_order_count($condition,$field,'',$_group);
            foreach((array)$list as $k => $v){
                if($today_day == $v['dayval']){
                    $curr_arr['order_num'][$v['hourval']] = intval($v['order_num']);
                    $curr_arr['order_amount'][$v['hourval']] = price_format($v['order_amount']);
                    $count['order_num'] = intval($count['order_num']) + intval($v['order_num']);
                    $count['order_amount'] = price_format($count['order_amount']) + price_format($v['order_amount']);
                }
                if($yesterday_day == $v['dayval']){
                    $up_arr['order_num'][$v['hourval']] = intval($v['order_num']);
                    $up_arr['order_amount'][$v['hourval']] = price_format($v['order_amount']);
                }
            }
            $stat_arr['order_num']['series'][0]['name'] = '昨天';
            $stat_arr['order_num']['series'][0]['data'] = array_values($up_arr['order_num']);
            $stat_arr['order_num']['series'][1]['name'] = '今天';
            $stat_arr['order_num']['series'][1]['data'] = array_values($curr_arr['order_num']);

            $stat_arr['order_amount']['series'][0]['name'] = '昨天';
            $stat_arr['order_amount']['series'][0]['data'] = array_values($up_arr['order_amount']);
            $stat_arr['order_amount']['series'][1]['name'] = '今天';
            $stat_arr['order_amount']['series'][1]['data'] = array_values($curr_arr['order_amount']);
	    }elseif(in_array($search_arr['search_type'],array('week','month','diydate'))){
	    	if($search_arr['search_type'] == 'week'){
	            $d_day = 7;
	    	}elseif($search_arr['search_type'] == 'month'){
	            $d_day = 30;
	    	}elseif($search_arr['search_type'] == 'diydate'){
	            $d_day = ceil(($search_time['etime'] - $search_time['stime']) / 86400);
	            if($d_day > 60){
	                $stat_arr['order_num']['xAxis']['tickInterval'] = 10;//X轴坐标间隔
	                $stat_arr['order_amount']['xAxis']['tickInterval'] = 10;
	            }
        	}
            for($i=0; $i<$d_day; $i++){
                //统计图数据
                $_dtime = date('Ymd',$search_time['stime']+86400*$i);
                $curr_arr['order_num'][$_dtime] = 0;
                $curr_arr['order_amount'][$_dtime] = 0;
                //横轴
                $_time = date('m-d',$search_time['stime']+86400*$i);
                $stat_arr['order_num']['xAxis']['categories'][] = $_time;
                $stat_arr['order_amount']['xAxis']['categories'][] = $_time;
            }
	    	$field .= ' ,FROM_UNIXTIME(add_time,"%Y%m%d") as dayval';
	    	$_group = 'dayval';
	    	$list = StatisticsService::get_order_count($condition,$field,'',$_group);
            foreach((array)$list as $k => $v){
                $curr_arr['order_num'][$v['dayval']] = intval($v['order_num']);
                $curr_arr['order_amount'][$v['dayval']] = price_format($v['order_amount']);
                $count['order_num'] = intval($count['order_num']) + intval($v['order_num']);
                $count['order_amount'] = price_format($count['order_amount']) + price_format($v['order_amount']);
            }
            $stat_arr['order_num']['series'][0]['name'] = '下单量';
            $stat_arr['order_num']['series'][0]['data'] = array_values($curr_arr['order_num']);
            $stat_arr['order_amount']['series'][0]['name'] = '下单金额';
            $stat_arr['order_amount']['series'][0]['data'] = array_values($curr_arr['order_amount']);
        }
        $stat_arr['order_num']['title'] = '下单量统计';
        $stat_arr['order_num']['yAxis'] = '下单量';
        $stat_arr['order_amount']['title'] = '下单金额统计';
        $stat_arr['order_amount']['yAxis'] = '下单金额';
        $stat_json['order_num'] = StatisticsService::getStatData_LineLabels($stat_arr['order_num']);
        $stat_json['order_amount'] = StatisticsService::getStatData_LineLabels($stat_arr['order_amount']);
        $this->assign('count',$count);
	    $this->assign('stat_json',$stat_json);
	    $this->display();
    }

    //会员统计
    public function members(){
	    $search_arr = I('request.');
        if(!$search_arr['search_type']){
            $search_arr['search_type'] = 'day';
        }
        if(!$search_arr['search_key']){
            $search_arr['search_key'] = 'new';
        }
	    $search_time = StatisticsService::init_date($search_arr);
        if($search_arr['search_type'] == 'diydate'){
            $search_arr['diydate'] = $search_time;
        }
        $this->assign('search_arr',$search_arr);
	    $condition = $curr_arr = $up_arr = $order_condition = array();
        $count = array(
            'new_member'     => 0,
            'order_member'  => 0,
        );
	    $condition['member_time'] = array('between',array($search_time['stime'],$search_time['etime']));
	    $order_condition['add_time'] = array('between',array($search_time['stime'],$search_time['etime']));
        $order_condition['order_state'] = array('gt',10);
	    $field = ' COUNT(*) as new_member';
	    $order_field = 'count(distinct(buyer_id)) as order_member';
	    if($search_arr['search_type'] == 'day'){
            for($i=0; $i<24; $i++){
                //统计图数据
                $curr_arr['new_member'][$i] = 0;//今天
                $up_arr['new_member'][$i] = 0;//昨天
                $curr_arr['order_member'][$i] = 0;//今天
                $up_arr['order_member'][$i] = 0;//昨天
                //横轴
                $stat_arr['new_member']['xAxis']['categories'][] = "$i";
                $stat_arr['order_member']['xAxis']['categories'][] = "$i";
            }
            $yesterday_day = @date('d', $search_time['stime']);//前一天日期
            $today_day = @date('d', $search_time['etime']);//前两天日期
	    	$field .= ' ,DAY(FROM_UNIXTIME(member_time)) as dayval,HOUR(FROM_UNIXTIME(member_time)) as hourval';
	    	$order_field .= ' ,DAY(FROM_UNIXTIME(add_time)) as dayval,HOUR(FROM_UNIXTIME(add_time)) as hourval';
	    	$_group = 'dayval,hourval';
	    	$list = StatisticsService::get_new_member($condition,$field,'',$_group);
	    	$order_list = StatisticsService::get_order_count($order_condition,$order_field,'',$_group);
            foreach((array)$list as $k => $v){
                if($today_day == $v['dayval']){
                    $curr_arr['new_member'][$v['hourval']] = intval($v['new_member']);
                    $count['new_member'] = intval($count['new_member']) + intval($v['new_member']);
                }
                if($yesterday_day == $v['dayval']){
                    $up_arr['new_member'][$v['hourval']] = intval($v['new_member']);
                }
            }
            foreach((array)$order_list as $ok => $ov){
                if($today_day == $ov['dayval']){
                    $curr_arr['order_member'][$ov['hourval']] = intval($ov['order_member']);
                    $count['order_member'] = intval($count['order_member']) + intval($ov['order_member']);
                }
                if($yesterday_day == $ov['dayval']){
                    $up_arr['order_member'][$ov['hourval']] = intval($ov['order_member']);
                }
            }
            $order_member_info = StatisticsService::get_order_count($order_condition,'count(distinct(buyer_id)) as order_member');
            $count['order_member'] = intval($order_member_info[0]['order_member']);

            $stat_arr['new_member']['series'][0]['name'] = '昨天';
            $stat_arr['new_member']['series'][0]['data'] = array_values($up_arr['new_member']);
            $stat_arr['new_member']['series'][1]['name'] = '今天';
            $stat_arr['new_member']['series'][1]['data'] = array_values($curr_arr['new_member']);

            $stat_arr['order_member']['series'][0]['name'] = '昨天';
            $stat_arr['order_member']['series'][0]['data'] = array_values($up_arr['order_member']);
            $stat_arr['order_member']['series'][1]['name'] = '今天';
            $stat_arr['order_member']['series'][1]['data'] = array_values($curr_arr['order_member']);
	    }elseif(in_array($search_arr['search_type'],array('week','month','diydate'))){
	    	if($search_arr['search_type'] == 'week'){
	            $d_day = 7;
	    	}elseif($search_arr['search_type'] == 'month'){
	            $d_day = 30;
	    	}elseif($search_arr['search_type'] == 'diydate'){
	            $d_day = ceil(($search_time['etime'] - $search_time['stime']) / 86400);
	            if($d_day > 60){
	                $stat_arr['new_member']['xAxis']['tickInterval'] = 10;//X轴坐标间隔
	                $stat_arr['order_member']['xAxis']['tickInterval'] = 10;
	            }
        	}
            for($i=0; $i<$d_day; $i++){
                //统计图数据
                $_dtime = date('Ymd',$search_time['stime']+86400*$i);
                $curr_arr['new_member'][$_dtime] = 0;
                $curr_arr['order_member'][$_dtime] = 0;
                //横轴
                $_time = date('m-d',$search_time['stime']+86400*$i);
                $stat_arr['new_member']['xAxis']['categories'][] = $_time;
                $stat_arr['order_member']['xAxis']['categories'][] = $_time;
            }
	    	$field .= ' ,FROM_UNIXTIME(member_time,"%Y%m%d") as dayval';
	    	$_group = 'dayval';
	    	$order_field .= ' ,FROM_UNIXTIME(add_time,"%Y%m%d") as dayval';
	    	$order_list = StatisticsService::get_order_count($order_condition,$order_field,'',$_group);
	    	$list = StatisticsService::get_new_member($condition,$field,'',$_group);
            foreach((array)$list as $k => $v){
                $curr_arr['new_member'][$v['dayval']] = intval($v['new_member']);
                $count['new_member'] = intval($count['new_member']) + intval($v['new_member']);
            }
            foreach((array)$order_list as $ok => $ov){
                $curr_arr['order_member'][$ov['dayval']] = intval($ov['order_member']);
            }
            $order_member_info = StatisticsService::get_order_count($order_condition,'count(distinct(buyer_id)) as order_member');
            $count['order_member'] = intval($order_member_info[0]['order_member']);
            $stat_arr['new_member']['series'][0]['name'] = '新增会员';
            $stat_arr['new_member']['series'][0]['data'] = array_values($curr_arr['new_member']);
            $stat_arr['order_member']['series'][0]['name'] = '下单会员';
            $stat_arr['order_member']['series'][0]['data'] = array_values($curr_arr['order_member']);
        }
        $stat_arr['new_member']['title'] = '新增会员统计';
        $stat_arr['new_member']['yAxis'] = '人数';
        $stat_arr['order_member']['title'] = '下单会员统计';
        $stat_arr['order_member']['yAxis'] = '人数';
        $stat_json['new_member'] = StatisticsService::getStatData_LineLabels($stat_arr['new_member']);
        $stat_json['order_member'] = StatisticsService::getStatData_LineLabels($stat_arr['order_member']);
        $this->assign('count',$count);
	    $this->assign('stat_json',$stat_json);
	    $this->display();
    }

    //售后统计

    public function customer(){
	    $search_arr = I('request.');
        if(!$search_arr['search_type']){
            $search_arr['search_type'] = 'day';
        }
        if(!$search_arr['search_key']){
            $search_arr['search_key'] = 'amount';
        }
	    $search_time = StatisticsService::init_date($search_arr);
        if($search_arr['search_type'] == 'diydate'){
            $search_arr['diydate'] = $search_time;
        }
        $this->assign('search_arr',$search_arr);
	    $search_time['stime'] = $search_time['curr_time'] ? $search_time['curr_time'] : $search_time['stime'];
	    $condition = $curr_arr = array();
        $count = array(
            'return_num'     => 0,
            'return_amount'  => 0,
            'refund_num'  => 0,
            'refund_amount'  => 0,
        );
	    $condition['dateline'] = array('between',array($search_time['stime'],$search_time['etime']));
        $condition['status'] = array('in',array(2,3));
	    $return_field = ' count(*) as return_num, sum(return_amount) as return_amount';
	    $refund_field = ' count(*) as refund_num, sum(refund_amount) as refund_amount';
	    if($search_arr['search_type'] == 'day'){
            for($i=0; $i<24; $i++){
                //统计图数据
                $curr_arr['return_num'][$i] = 0;
                $curr_arr['refund_num'][$i] = 0;
                $curr_arr['return_amount'][$i] = 0;
                $curr_arr['refund_amount'][$i] = 0;
                //横轴
                $stat_arr['num']['xAxis']['categories'][] = "$i";
                $stat_arr['amount']['xAxis']['categories'][] = "$i";
            }
            $yesterday_day = @date('d', $search_time['stime']);//前一天日期
            $today_day = @date('d', $search_time['etime']);//前两天日期
	    	$return_field .= ' ,DAY(FROM_UNIXTIME(dateline)) as dayval,HOUR(FROM_UNIXTIME(dateline)) as hourval';
	    	$refund_field .= ' ,DAY(FROM_UNIXTIME(dateline)) as dayval,HOUR(FROM_UNIXTIME(dateline)) as hourval';
	    	$_group = 'dayval,hourval';
	    	$refund_list = StatisticsService::get_refund_count($condition,$refund_field,'',$_group);
	    	$return_list = StatisticsService::get_return_count($condition,$return_field,'',$_group);
            foreach((array)$return_list as $k => $v){
                if($today_day == $v['dayval']){
                    $curr_arr['return_num'][$v['hourval']] = intval($v['return_num']);
                    $curr_arr['return_amount'][$v['hourval']] = price_format($v['return_amount']);
                    $count['return_num'] = intval($count['return_num']) + intval($v['return_num']);
                    $count['return_amount'] = price_format($count['return_amount']) + price_format($v['return_amount']);
                }
            }
            foreach((array)$refund_list as $dk => $dv){
                if($today_day == $dv['dayval']){
                    $curr_arr['refund_num'][$dv['hourval']] = intval($dv['refund_num']);
                    $curr_arr['refund_amount'][$dv['hourval']] = price_format($dv['refund_amount']);
                    $count['refund_num'] = intval($count['refund_num']) + intval($dv['refund_num']);
                    $count['refund_amount'] = price_format($count['refund_amount']) + price_format($dv['refund_amount']);
                }
            }
	    }elseif(in_array($search_arr['search_type'],array('week','month','diydate'))){
	    	if($search_arr['search_type'] == 'week'){
	            $d_day = 7;
	    	}elseif($search_arr['search_type'] == 'month'){
	            $d_day = 30;
	    	}elseif($search_arr['search_type'] == 'diydate'){
	            $d_day = ceil(($search_time['etime'] - $search_time['stime']) / 86400);
	            if($d_day > 60){
	                $stat_arr['num']['xAxis']['tickInterval'] = 10;//X轴坐标间隔
	                $stat_arr['amount']['xAxis']['tickInterval'] = 10;
	            }
        	}
            for($i=0; $i<$d_day; $i++){
                $_dtime = date('Ymd',$search_time['stime']+86400*$i);
                $_time = date('m-d',$search_time['stime']+86400*$i);
                $curr_arr['return_num'][$_dtime] = 0;
                $curr_arr['refund_num'][$_dtime] = 0;
                $curr_arr['return_amount'][$_dtime] = 0;
                $curr_arr['refund_amount'][$_dtime] = 0;
                //横轴
                $stat_arr['num']['xAxis']['categories'][] = $_time;
                $stat_arr['amount']['xAxis']['categories'][] = $_time;
            }
	    	$return_field .= '  ,FROM_UNIXTIME(dateline,"%Y%m%d") as dayval';
	    	$refund_field .= '  ,FROM_UNIXTIME(dateline,"%Y%m%d") as dayval';
	    	$_group = 'dayval';
	    	$refund_list = StatisticsService::get_refund_count($condition,$refund_field,'',$_group);
	    	$return_list = StatisticsService::get_return_count($condition,$return_field,'',$_group);
            foreach((array)$return_list as $k => $v){
                $curr_arr['return_num'][$v['dayval']] = intval($v['return_num']);
                $curr_arr['return_amount'][$v['dayval']] = price_format($v['return_amount']);
                $count['return_num'] = intval($count['return_num']) + intval($v['return_num']);
                $count['return_amount'] = price_format($count['return_amount']) + price_format($v['return_amount']);
            }
            foreach((array)$refund_list as $dk => $dv){
                $curr_arr['refund_num'][$dv['dayval']] = intval($dv['refund_num']);
                $curr_arr['refund_amount'][$dv['dayval']] = price_format($dv['refund_amount']);
                $count['refund_num'] = intval($count['refund_num']) + intval($dv['refund_num']);
                $count['refund_amount'] = price_format($count['refund_amount']) + price_format($dv['refund_amount']);
            }
        }

        $stat_arr['num']['series'][0]['name'] = '退款';
        $stat_arr['num']['series'][0]['data'] = array_values($curr_arr['refund_num']);
        $stat_arr['num']['series'][1]['name'] = '退货';
        $stat_arr['num']['series'][1]['data'] = array_values($curr_arr['return_num']);

        $stat_arr['amount']['series'][0]['name'] = '退款';
        $stat_arr['amount']['series'][0]['data'] = array_values($curr_arr['refund_amount']);
        $stat_arr['amount']['series'][1]['name'] = '退货';
        $stat_arr['amount']['series'][1]['data'] = array_values($curr_arr['return_amount']);

        $stat_arr['num']['title'] = '退款退货统计';
        $stat_arr['num']['yAxis'] = '下单数';
        $stat_arr['amount']['title'] = '退款退货金额统计';
        $stat_arr['amount']['yAxis'] = '下单金额';
        $stat_json['num'] = StatisticsService::getStatData_LineLabels($stat_arr['num']);
        $stat_json['amount'] = StatisticsService::getStatData_LineLabels($stat_arr['amount']);
        $this->assign('count',$count);
	    $this->assign('stat_json',$stat_json);
	    $this->display();
    }

	//热卖商品
	public function hotgoods(){
	    $search_arr = I('request.');
        if(!$search_arr['search_type']){
            $search_arr['search_type'] = 'day';
        }
        if(!$search_arr['search_key']){
            $search_arr['search_key'] = 'num';
        }
	    $search_time = StatisticsService::init_date($search_arr);
        if($search_arr['search_type'] == 'diydate'){
            $search_arr['diydate'] = $search_time;
        }
        if($search_arr['search_type'] == 'day'){
            $search_time['stime'] = $search_time['curr_time'];
        }
        $this->assign('search_arr',$search_arr);
        $table_pre =  C('DB_PREFIX');
	    $condition = $curr_arr = $up_arr = array();
	    $condition["{$table_pre}order_goods.add_time"] = array('between',array($search_time['stime'],$search_time['etime']));
        $condition["{$table_pre}orders.order_state"] = array('gt',10);
	    $field = "sum({$table_pre}order_goods.goods_num) as goods_num, sum({$table_pre}order_goods.goods_price) as goods_price,min({$table_pre}order_goods.goods_name) as goods_name";
        for($i=0; $i<30; $i++){
            //统计图数据
            $stat_arr['goods_num']['series'][0]['data'][] = array('name'=>'','y'=>0);
            $stat_arr['goods_price']['series'][0]['data'][] = array('name'=>'','y'=>0);
            //横轴
            $stat_arr['goods_num']['xAxis']['categories'][] = $i+1;
            $stat_arr['goods_price']['xAxis']['categories'][] = $i+1;
        }
    	$_group = "{$table_pre}order_goods.goods_common_id";
    	$num_order = "goods_num desc";
    	$amount_order = "goods_price desc";
    	$limit = '30';
    	$list = StatisticsService::get_hotgoods($condition,$field,$num_order,$_group,$limit);
    	$amount_list = StatisticsService::get_hotgoods($condition,$field,$amount_order,$_group,$limit);
        foreach((array)$list as $k => $v){
            $stat_arr['goods_num']['series'][0]['data'][$k] = array('name'=>$v['goods_name'],'y'=>intval($v['goods_num']));
        }
        foreach((array)$amount_list as $k => $v){
            $stat_arr['goods_price']['series'][0]['data'][$k] = array('name'=>$v['goods_name'],'y'=>price_format($v['goods_price']));
        }
        $stat_arr['goods_num']['series'][0]['name'] = '下单量';
        $stat_arr['goods_num']['legend']['enabled'] = false;
		$stat_arr['goods_price']['series'][0]['name'] = '下单金额';
        $stat_arr['goods_price']['legend']['enabled'] = false;

        $stat_arr['goods_num']['title'] = '热卖商品TOP30';
        $stat_arr['goods_num']['yAxis'] = '下单量';
        $stat_arr['goods_price']['title'] = '热卖商品TOP30';
        $stat_arr['goods_price']['yAxis'] = '下单金额';
        $stat_json['goods_num'] = StatisticsService::getStatData_Column2D($stat_arr['goods_num']);
        $stat_json['goods_price'] = StatisticsService::getStatData_Column2D($stat_arr['goods_price']);
	    $this->assign('stat_json',$stat_json);
	    $this->display();
    }

    public function reward(){ //订单统计
        $search_arr = I('request.');
        if(!$search_arr['search_type']){
            $search_arr['search_type'] = 'day';
        }
        if(!$search_arr['search_key']){
            $search_arr['search_key'] = 'member';
        }
        $search_time = StatisticsService::init_date($search_arr);
        if($search_arr['search_type'] == 'diydate'){
            $search_arr['diydate'] = $search_time;
        }
        $this->assign('search_arr',$search_arr);
        $condition = $curr_arr = $up_arr = array();
        $count = array(
            'reward_member'     => 0,
            'reward_amount'     => 0,
            'reward_order'     => 0,
        );
        $condition['member_time'] = array('between',array($search_time['stime'],$search_time['etime']));
        $condition['fromid'] = array('neq',0);
        $field = ' count(*) as reward_member';

        $order_condition['add_time'] = array('between',array($search_time['stime'],$search_time['etime']));
        $order_field = 'sum(reward_amount) as reward_amount';

        if($search_arr['search_type'] == 'day'){
            for($i=0; $i<24; $i++){
                //统计图数据
                $curr_arr['reward_member'][$i] = 0;//今天
                $up_arr['reward_member'][$i] = 0;//昨天
                $curr_arr['reward_amount'][$i] = 0;//今天
                $up_arr['reward_amount'][$i] = 0;//昨天
                //横轴
                $stat_arr['reward_member']['xAxis']['categories'][] = "$i";
                $stat_arr['reward_amount']['xAxis']['categories'][] = "$i";
            }
            $yesterday_day = @date('d', $search_time['stime']);//前一天日期
            $today_day = @date('d', $search_time['etime']);//前两天日期
            $field .= ' ,DAY(FROM_UNIXTIME(member_time)) as dayval,HOUR(FROM_UNIXTIME(member_time)) as hourval';
            $_group = 'dayval,hourval';
            $list = StatisticsService::get_new_member($condition,$field,'',$_group);

            $order_field .= ',DAY(FROM_UNIXTIME(add_time)) as dayval,HOUR(FROM_UNIXTIME(add_time)) as hourval';
            $order_group = 'dayval,hourval';
            $hotlist = StatisticsService::get_spread($order_condition,$order_field,'',$order_group);

            foreach((array)$list as $k => $v){
                if($today_day == $v['dayval']){
                    $curr_arr['reward_member'][$v['hourval']] = intval($v['reward_member']);
                    $count['reward_member'] = intval($count['reward_member']) + intval($v['reward_member']);
                }
                if($yesterday_day == $v['dayval']){
                    $up_arr['reward_member'][$v['hourval']] = intval($v['order_num']);
                }
            }

            foreach((array)$hotlist as $hk => $hv){
                if($today_day == $hv['dayval']){
                    $curr_arr['reward_amount'][$hv['hourval']] = price_format($hv['reward_amount']);
                    $count['reward_amount'] = price_format($count['reward_amount']) + price_format($hv['reward_amount']);
                }
                if($yesterday_day == $hv['dayval']){
                    $up_arr['reward_amount'][$hv['hourval']] = price_format($hv['reward_amount']);
                }
            }

            $stat_arr['reward_member']['series'][0]['name'] = '昨天';
            $stat_arr['reward_member']['series'][0]['data'] = array_values($up_arr['reward_member']);
            $stat_arr['reward_member']['series'][1]['name'] = '今天';
            $stat_arr['reward_member']['series'][1]['data'] = array_values($curr_arr['reward_member']);

            $stat_arr['reward_amount']['series'][0]['name'] = '昨天';
            $stat_arr['reward_amount']['series'][0]['data'] = array_values($up_arr['reward_amount']);
            $stat_arr['reward_amount']['series'][1]['name'] = '今天';
            $stat_arr['reward_amount']['series'][1]['data'] = array_values($curr_arr['reward_amount']);
        }elseif(in_array($search_arr['search_type'],array('week','month','diydate'))){
            if($search_arr['search_type'] == 'week'){
                $d_day = 7;
            }elseif($search_arr['search_type'] == 'month'){
                $d_day = 30;
            }elseif($search_arr['search_type'] == 'diydate'){
                $d_day = ceil(($search_time['etime'] - $search_time['stime']) / 86400);
                if($d_day > 60){
                    $stat_arr['reward_member']['xAxis']['tickInterval'] = 10;//X轴坐标间隔
                }
            }
            for($i=0; $i<$d_day; $i++){
                //统计图数据
                $_dtime = date('Ymd',$search_time['stime']+86400*$i);
                $curr_arr['reward_member'][$_dtime] = 0;
                $curr_arr['reward_amount'][$_dtime] = 0;
                //横轴
                $_time = date('m-d',$search_time['stime']+86400*$i);
                $stat_arr['reward_member']['xAxis']['categories'][] = $_time;
                $stat_arr['reward_amount']['xAxis']['categories'][] = $_time;
            }
            $field .= ' ,FROM_UNIXTIME(member_time,"%Y%m%d") as dayval';
            $_group = 'dayval';
            $list = StatisticsService::get_new_member($condition,$field,'',$_group);

            $order_field .= ',FROM_UNIXTIME(add_time,"%Y%m%d") as dayval';
            $order_group = 'dayval';
            $hotlist = StatisticsService::get_spread($order_condition,$order_field,'',$order_group);

            foreach((array)$list as $k => $v){
                $curr_arr['reward_member'][$v['dayval']] = intval($v['reward_member']);
                $count['reward_member'] = intval($count['reward_member']) + intval($v['reward_member']);
            }
            foreach((array)$hotlist as $hk => $hv){
                $curr_arr['reward_amount'][$hv['dayval']] = price_format($hv['reward_amount']);
                $count['reward_amount'] = price_format($count['reward_amount']) + price_format($hv['reward_amount']);
            }

            $stat_arr['reward_member']['series'][0]['name'] = '会员人数';
            $stat_arr['reward_member']['series'][0]['data'] = array_values($curr_arr['reward_member']);
            $stat_arr['reward_amount']['series'][0]['name'] = '奖励累积金额';
            $stat_arr['reward_amount']['series'][0]['data'] = array_values($curr_arr['reward_amount']);
        }
        $stat_arr['reward_member']['title'] = '推广会员统计';
        $stat_arr['reward_member']['yAxis'] = '会员人数';
        $stat_arr['reward_amount']['title'] = '推广奖励统计';
        $stat_arr['reward_amount']['yAxis'] = '奖励累积金额';
        $stat_json['reward_member'] = StatisticsService::getStatData_LineLabels($stat_arr['reward_member']);
        $stat_json['reward_amount'] = StatisticsService::getStatData_LineLabels($stat_arr['reward_amount']);

        //推广奖励排行统计
        if($search_arr['search_type'] == 'day'){
            $hotstime = $search_time['curr_time'];
        }else{
            $hotstime = $search_time['stime'];
        }
        $hot_condition["add_time"] = array('between',array($hotstime,$search_time['etime']));
        $hot_field = "sum(reward_amount) as reward_hot,max(member_truename)";
        for($i=0; $i<30; $i++){
            //统计图数据
            $stat_arr['reward_hot']['series'][0]['data'][] = array('name'=>'','y'=>0);
            //横轴
            $stat_arr['reward_hot']['xAxis']['categories'][] = $i+1;
        }
        $hot_group = "member_uid";
        $hot_order = "reward_hot desc";
        $limit = '30';
        $hotlist = StatisticsService::get_spread($hot_condition,$hot_field,$hot_order,$hot_group,$limit);
        foreach((array)$hotlist as $k => $v){
            $stat_arr['reward_hot']['series'][0]['data'][$k] = array('name'=>'会员：'.$v['member_truename'],'y'=>price_format($v['reward_hot']));
            $count['reward_hot'] = price_format($count['reward_hot']) + price_format($v['reward_hot']);
        }
        $stat_arr['reward_hot']['series'][0]['name'] = '奖励累积金额';
        $stat_arr['reward_hot']['legend']['enabled'] = false;

        $stat_arr['reward_hot']['title'] = '推广奖励TOP30';
        $stat_arr['reward_hot']['yAxis'] = '奖励累积金额';
        $stat_json['reward_hot'] = StatisticsService::getStatData_Column2D($stat_arr['reward_hot']);

        $this->assign('count',$count);
        $this->assign('stat_json',$stat_json);
        $this->display();
    }




    public function loadajax(){
        $json_str = I('request.json_str');
        $stattype = I('request.stattype');
        layout(false);
        $this->assign('json_str',json_encode($json_str));
        $this->assign('stattype',$stattype);
        $this->display('stat_data');
    }

}