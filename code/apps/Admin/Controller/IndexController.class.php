<?php
namespace Admin\Controller;
use Think\Controller;
use  Common\Service\StatisticsService;
class IndexController extends BaseController {
	public function main() {//主页

		/*********************统计表格 start*****************/
	    // $search_arr = I('request.');
     //    if(!$search_arr['search_type']){
     //        $search_arr['search_type'] = 'day';
     //    }
     //    $this->assign('search_arr',$search_arr);
		$search_arr['search_type'] = 'month';
	    $search_time = StatisticsService::init_date($search_arr);
	    // var_dump(date('Y-m-d H:i:s',$search_time['stime']),date('Y-m-d H:i:s',$search_time['etime']));die;
	    $condition = $curr_arr = $member_condition = array();
	    $condition['add_time'] = array('between',array($search_time['stime'],$search_time['etime']));
	    $field = ' count(*) as order_num, sum(order_amount) as order_amount,count(distinct(buyer_id)) as order_member';

	    $member_condition['member_time'] = array('between',array($search_time['stime'],$search_time['etime']));
	    $member_field = ' COUNT(*) as new_member';

        for($i=0; $i<30; $i++){
            //统计图数据
            $_dtime = date('Ymd',$search_time['stime']+86400*$i);
            $curr_arr['order_num'][$_dtime] = 0;
            $curr_arr['order_amount'][$_dtime] = 0;
            $curr_arr['order_member'][$_dtime] = 0;
            $curr_arr['new_member'][$_dtime] = 0;
            //横轴
            $_time = date('m-d',$search_time['stime']+86400*$i);
            $stat_arr['order_num']['xAxis']['categories'][] = $_time;
            $stat_arr['order_amount']['xAxis']['categories'][] = $_time;
            $stat_arr['order_member']['xAxis']['categories'][] = $_time;
            $stat_arr['new_member']['xAxis']['categories'][] = $_time;
        }
    	$field .= ' ,FROM_UNIXTIME(add_time,"%Y%m%d") as dayval';
    	$member_field .= ' ,FROM_UNIXTIME(member_time,"%Y%m%d") as dayval';
    	$_group = 'dayval';
    	$list = StatisticsService::get_order_count($condition,$field,'',$_group);
    	$member_list = StatisticsService::get_new_member($member_condition,$member_field,'',$_group);

    	$num30 = $amount30 = $omember30 = $amember30 = 0;
        foreach((array)$list as $k => $v){
            $curr_arr['order_num'][$v['dayval']] = intval($v['order_num']);
            $curr_arr['order_amount'][$v['dayval']] = price_format($v['order_amount']);
            $curr_arr['order_member'][$v['dayval']] = intval($v['order_member']);
            $num30 = intval($num30 + intval($v['order_num']));
            $amount30 = price_format($amount30 + price_format($v['order_amount']));
            $omember30 =intval($omember30 + intval($v['order_member']));
        }
        foreach((array)$member_list as $mk => $mv){
            $curr_arr['new_member'][$mv['dayval']] = intval($mv['new_member']);
            $amember30 = intval($amember30 + intval($mv['new_member']));
        }

        $stat_arr['order_num']['series'][0]['name'] = '下单量';
        $stat_arr['order_num']['series'][0]['data'] = array_values($curr_arr['order_num']);
        $stat_arr['order_amount']['series'][0]['name'] = '下单金额';
        $stat_arr['order_amount']['series'][0]['data'] = array_values($curr_arr['order_amount']);
        $stat_arr['order_member']['series'][0]['name'] = '下单会员';
        $stat_arr['order_member']['series'][0]['data'] = array_values($curr_arr['order_amount']);
        $stat_arr['new_member']['series'][0]['name'] = '新会员';
        $stat_arr['new_member']['series'][0]['data'] = array_values($curr_arr['new_member']);

        $stat_arr['order_num']['title'] = '下单量统计';
        $stat_arr['order_num']['subtitle']['text'] = '近30天下单量统计';
        $stat_arr['order_num']['subtitle']['align'] = 'right';
        $stat_arr['order_num']['yAxis'] = '下单量';

        $stat_arr['order_amount']['title'] = '下单金额统计';
        $stat_arr['order_amount']['yAxis'] = '下单金额';
        $stat_arr['order_amount']['subtitle']['text'] = '近30天下单金额统计';
        $stat_arr['order_amount']['subtitle']['align'] = 'right';

        $stat_arr['order_member']['title'] = '下单会员统计';
        $stat_arr['order_member']['yAxis'] = '下单会员';
        $stat_arr['order_member']['subtitle']['text'] = '近30天下单会员统计';
        $stat_arr['order_member']['subtitle']['align'] = 'right';

        $stat_arr['new_member']['title'] = '新会员统计';
        $stat_arr['new_member']['yAxis'] = '新会员';
        $stat_arr['new_member']['subtitle']['text'] = '近30天新会员统计';
        $stat_arr['new_member']['subtitle']['align'] = 'right';

        $stat_json['order_num'] = StatisticsService::getStatData_LineLabels($stat_arr['order_num']);
        $stat_json['order_amount'] = StatisticsService::getStatData_LineLabels($stat_arr['order_amount']);
        $stat_json['order_member'] = StatisticsService::getStatData_LineLabels($stat_arr['order_member']);
        $stat_json['new_member'] = StatisticsService::getStatData_LineLabels($stat_arr['new_member']);
	    $this->assign('stat_json',$stat_json);
	    /*********************统计表格 end*****************/

	    /*********************数据求和 start*****************/
	    $all_condition = array();
	    $field = ' count(*) as order_num, sum(order_amount) as order_amount,count(distinct(buyer_id)) as order_member';
	    $member_condition['member_time'] = array('between',array($search_time['stime'],$search_time['etime']));
	    $member_field = ' COUNT(*) as all_member';
    	$count_list = StatisticsService::get_order_count($all_condition,$field);
    	$all_member_count = StatisticsService::get_new_member($all_condition,$member_field);
    	$count = array();
    	$count['num'] = array(
    			'num30'	=> $num30,
    			'all_num'	=> $count_list[0]['order_num'],
    		);
    	$count['amount'] = array(
    			'amount30'	=> $amount30,
    			'all_amount'	=> $count_list[0]['order_amount'],
    		);
    	$count['omember'] = array(
    			'omember30'	=> $omember30,
    			'order_member'	=> $count_list[0]['order_member'],
    		);
    	$count['amember'] = array(
    			'amember30'	=> $amember30,
    			'all_member'	=> $all_member_count[0]['all_member'],
    		);
    	$this->assign('count',$count);
    	/*********************数据求和 end*****************/

    	/*********************热销商品 start*****************/
	    $hot_condition = array();
	    $hot_condition['add_time'] = array('between',array($search_time['stime'],$search_time['etime']));
	    $hotfield = 'sum(goods_num) as goods_num, sum(goods_price) as goods_price,min(goods_name) as goods_name,goods_id';
    	$hot_group = 'goods_id';
    	$order = 'goods_num desc';
    	$limit = '10';
    	$hotgoods = array();
    	$hotgoods = StatisticsService::get_hotgoods($hot_condition,$hotfield,$order,$hot_group,$limit);
    	$this->assign('hotgoods',$hotgoods);
    	/*********************热销商品 end*****************/

		/*********************热销商品 start*****************/
			//暂无商品浏览量排行
    	/*********************热销商品 end*****************/

	    $this->display();
	}
    public function index(){
		layout('layout');
		$this->display('Index');
    }
	
	public function add() {
		
		$this->display('Add');
	}
	
	public function edit() {
		$this->display('Add');
	}
}