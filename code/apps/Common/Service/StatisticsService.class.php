<?php
namespace Common\Service;
class StatisticsService {

	static function init_date($search_arr){
		$time = time();
		$search_time = array();
        //计算昨天和今天时间
        if($search_arr['search_type'] == 'day'){
            // $search_time['stime'] = strtotime(date('Y-m-d',$time)) - 86400*2;//昨天0点
            // $search_time['etime'] = strtotime(date('Y-m-d',$time)) - 1;//今天24点
            // $search_time['curr_time'] = strtotime(date('Y-m-d',$time)) - 86400;//今天0点
            $search_time['stime'] = strtotime(date('Y-m-d',$time)) - 86400*1;//昨天0点
            $search_time['etime'] = strtotime(date('Y-m-d',$time)) + 86400 - 1;//今天24点
            $search_time['curr_time'] = strtotime(date('Y-m-d',$time)) - 1;//今天0点
        } elseif ($search_arr['search_type'] == 'week'){
            $search_time['stime'] = strtotime(date('Y-m-d', $time-86400*7));
            $search_time['etime'] = strtotime(date('Y-m-d', $time))-1;
        } elseif ($search_arr['search_type'] == 'month'){
            $search_time['stime'] = strtotime(date('Y-m-d', $time-86400*30));
            $search_time['etime'] = strtotime(date('Y-m-d', $time))-1;
        } elseif ($search_arr['search_type'] == 'diydate'){
            $search_time['stime'] = strtotime($search_arr['stime']);
            $search_time['etime'] = strtotime($search_arr['etime'])+86400-1;
        }
        return $search_time;
	}

	//图表数据
	static function getStatData_LineLabels($stat_arr){
	    //图表区、图形区和通用图表配置选项
	    $stat_arr['chart']['type'] = 'line';
	    //图表序列颜色数组
	    $stat_arr['colors']?'':$stat_arr['colors'] = array('#058DC7', '#ED561B', '#8bbc21', '#0d233a');
	    //去除版权信息
	    $stat_arr['credits']['enabled'] = false;
	    //图例说明
	    // $stat_arr['legend']['layout'] = 'vertical';
	    // $stat_arr['legend']['align'] = 'center';
	    // $stat_arr['legend']['verticalAlign'] = 'middle';
	    // $stat_arr['legend']['borderWidth'] = 0;
	    //导出功能选项
	    $stat_arr['exporting']['enabled'] = true;
	    $stat_arr['exporting']['text'] = 'shopdz';
	    //标题如果为字符串则使用默认样式
	    is_string($stat_arr['title'])?$stat_arr['title'] = array('text'=>"<b>{$stat_arr['title']}</b>",'x'=>-20):'';
	    //子标题如果为字符串则使用默认样式
	    is_string($stat_arr['subtitle'])?$stat_arr['subtitle'] = array('text'=>"<b>{$stat_arr['subtitle']}</b>",'x'=>-20):'';
	    //Y轴如果为字符串则使用默认样式
	    if(is_string($stat_arr['yAxis'])){
	        $text = $stat_arr['yAxis'];
	        unset($stat_arr['yAxis']);
	        $stat_arr['yAxis']['title']['text'] = $text;
	    }
	    return json_encode($stat_arr);
	}
	static function getStatData_Column2D($stat_arr){
	    //图表区、图形区和通用图表配置选项
	    $stat_arr['chart']['type'] = 'column';
	    //去除版权信息
	    $stat_arr['credits']['enabled'] = false;
	    //导出功能选项
	    $stat_arr['exporting']['enabled'] = false;
	    //标题如果为字符串则使用默认样式
	    is_string($stat_arr['title'])?$stat_arr['title'] = array('text'=>"<b>{$stat_arr['title']}</b>",'x'=>-20):'';
	    //子标题如果为字符串则使用默认样式
	    is_string($stat_arr['subtitle'])?$stat_arr['subtitle'] = array('text'=>"<b>{$stat_arr['subtitle']}</b>",'x'=>-20):'';
	    //Y轴如果为字符串则使用默认样式
	    if(is_string($stat_arr['yAxis'])){
	        $text = $stat_arr['yAxis'];
	        unset($stat_arr['yAxis']);
	        $stat_arr['yAxis']['title']['text'] = $text;
	    }
	    //柱形的颜色数组
	    $color = array('#7a96a4','#cba952','#667b16','#a26642','#349898','#c04f51','#5c315e','#445a2b','#adae50','#14638a','#b56367','#a399bb','#070dfa','#47ff07','#f809b7');

	    foreach ($stat_arr['series'] as $series_k=>$series_v){
	        foreach ($series_v['data'] as $data_k=>$data_v){
	            $data_v['color'] = $color[$data_k];
	            $series_v['data'][$data_k] = $data_v;
	        }
	        $stat_arr['series'][$series_k]['data'] = $series_v['data'];
	    }
	    //print_r($stat_arr); die;
	    return json_encode($stat_arr);
	}

	static function get_new_member($where, $field = '*' ,$order = '', $group = ''){
		return M('member')->field($field)->where($where)->group($group)->order($order)->select();
	}

	static function get_order_count($where, $field = '*' ,$order = '', $group = ''){
		return M('orders')->field($field)->where($where)->group($group)->order($order)->select();
	}

	static function get_return_count($where, $field = '*' ,$order = '', $group = ''){
		return M('returngoods')->field($field)->where($where)->group($group)->order($order)->select();
	}

	static function get_refund_count($where, $field = '*' ,$order = '', $group = ''){
		return M('refund')->field($field)->where($where)->group($group)->order($order)->select();
	}
	static function get_hotgoods($where, $field = '*' ,$order = '', $group = '',$limit = ''){
		$table_pre =  C('DB_PREFIX');
		return M('order_goods')->join("{$table_pre}orders on {$table_pre}orders.order_id={$table_pre}order_goods.order_id")->field($field)->where($where)->group($group)->order($order)->limit($limit)->select();
	}
	static function get_viewgoods($where, $field = '*' ,$order = '', $group = '',$limit = ''){
		return M('goods_common')->field($field)->where($where)->group($group)->order($order)->limit($limit)->select();
	}
	static function get_spread($where, $field = '*' ,$order = '', $group = '',$limit = ''){
		$table_pre =  C('DB_PREFIX');
		return M('spread')->field($field)->where($where)->group($group)->order($order)->limit($limit)->select();
	}

}