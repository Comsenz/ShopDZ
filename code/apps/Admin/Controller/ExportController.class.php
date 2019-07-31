<?php
namespace Admin\Controller;
use Think\Controller;
class ExportController extends BaseController {
	private $order_from = array('1'=>'WEB','2'=>'MOBILE');

	//到出订单
	public function orderlists(){
		$field = I("get.field",'','htmlspecialchars');
		$where = array();
		$table_pre =  C('DB_PREFIX');
		switch ($field) {
			case 'order_sn':
				$order_sn = I("get.value",'','htmlspecialchars');
				$order_sn = $order_sn;
				$where["{$table_pre}orders.order_sn"] = array('like','%'.$order_sn.'%');
				break;
			case 'buyer_name':
				$buyer_name = I("get.value",'','htmlspecialchars');
				$where["{$table_pre}orders.buyer_name"] = array('like','%'.$buyer_name.'%');
				break;
			case 'trade_no':
				$trade_no = I("get.value",'','htmlspecialchars');
				$trade_no = $trade_no;
				$where["{$table_pre}orders.trade_no"] = array('like','%'.$trade_no.'%');
				break;
			case 'buyer_phone':
				$buyer_name = I("get.value",'','htmlspecialchars');
				$buyer_name = $buyer_name;
				$where["{$table_pre}orders.buyer_phone"] = array('like','%'.$buyer_name.'%');
				break;
			case 'reciver_name':
				$reciver_name = I("get.value",'','htmlspecialchars');
				$reciver_name = $reciver_name;
				$where["{$table_pre}order_common.reciver_name"] = array('like','%'.$reciver_name.'%');
				break;
		}
		I("get.start") && $where['add_time'] = array('EGT',$start = strtotime(I("get.start")));
		I("get.end") && $where['add_time'] = array('LT',$end = strtotime(I("get.end")));
		$start && $end && $where['add_time'] = array('between',"$start,$end");
		$type = I("get.type",'','htmlspecialchars');
		if($type != null){
			$where['order_state'] = $type;
		}
		$model = D('orders');
		$count = $model->getListCount($where);
		$page  = new \Common\Helper\PageHelper($count);
		$page->firstRow = 0;
		$page->listRows = 100000;
		$list = $model->getList($where,$page);
        $obj  = new \Common\Helper\ExcelHelper();
	    	$title = array('订单ID','订单编号','买家账号','收货人','下单时间','订单状态', '订单金额','商品金额','运费','支付方式','交易流水号','支付时间','退款金额','买家联系方式','收货人联系方式','收货地址','发货时间','快递公司','物流单号','订单来源');
			$export = array();
			$company = D('companylist');
			foreach($list as $k =>$v) {
				$order_id = $v['order_id'];
				$orderCommon = $model->getOrderCommon($order_id);
				$express = $company->find($orderCommon['shipping_express_id']);
				$export[$k]['order_id'] = $order_id;
				$export[$k]['order_sn'] = $v['order_sn'].' ';
				$export[$k]['buyer_name'] = $v['buyer_name'];
				$export[$k]['reciver_name'] = $v['reciver_name'];
				$export[$k]['add_time_text'] = $v['add_time_text'];
				$export[$k]['order_state_text'] = $v['order_state_text'].' ';
				$export[$k]['order_amount'] = $v['order_amount'];
				$export[$k]['goods_amount'] = $v['goods_amount'];
				$export[$k]['shipping_fee'] = $v['shipping_fee'];
				$export[$k]['payment_code'] = $v['payment_code'].' ';
					$export[$k]['trade_no'] = $v['trade_no'].' ';
				$export[$k]['payment_time_text'] = $v['payment_time_text'];
				$export[$k]['refund_amount'] = $v['refund_amount'];
				$export[$k]['buyer_phone'] = $v['buyer_phone'];
				$export[$k]['tel_phone'] = $orderCommon['reciver_info']['tel_phone'];
				$export[$k]['reciver_info_area_info'] = $orderCommon['reciver_info']['area_info'].' '.$orderCommon['reciver_info']['address'];
				$export[$k]['reciver_info_shipping_time'] = $orderCommon['shipping_time_text'];
				$export[$k]['company'] = $express['name'];
				$export[$k]['shipping_code'] = $express['shipping_code'];
				$export[$k]['order_from'] = $this->order_from[$v['order_from']];
			}
			$name ='订到列表导出'.date("Y-m-d");
	        $obj->exportExcel($title, $export,$name,'订单列表');
			unset($export);
    }
	/*
	*后台提现记录
	*/
	public function withdraw() {
		$type_array = array(
			'cash_sn'=>'cash_sn',
			'member_name'=>'member_name',
			'member_uid'=>'member_uid',
			'user_name'=>'user_name',
		);
		$type = I('get.type');
		$status = I('get.status');
		$text = I('get.search_text');
		$where = array();
		if($type && in_array($type,$type_array)) {
			$where["$type_array[$type]"] = array("like", "%".$text."%");
		}
		$status && $where['status'] = $status;
		if($status == 1){
			$where['status'] = array(array('eq',1),array('eq',4), 'or');
		}
		$model = D('SpreadWithdrawCash');
		$list = $model ->exportAllWithdraw($where,$limit = 100000);
		$obj  = new \Common\Helper\ExcelHelper();
		$title = array(
			'申请编号','会员ID','会员账号','提现金额','申请时间','收款银行', '收款账号','开户姓名','管理员','操作时间'
			);
			$export = array();
			foreach($list as $k =>$v) {
				$export[$k]['cash_sn'] = $v['cash_sn'].' ';
				$export[$k]['member_uid'] = $v['member_uid'].' ';
				$export[$k]['member_name'] = $v['member_name'];
				$export[$k]['cash_amount'] = $v['cash_amount'].' ';
				$export[$k]['add_time_text'] = $v['add_time_text'];
				$export[$k]['bank'] = $v['bank'];
				$export[$k]['bank_no'] = $v['bank_no'].' ';
				$export[$k]['bank_name'] = $v['bank_name'];
				$export[$k]['user_name'] = $v['user_name'];
				$export[$k]['enddate_text'] = $v['enddate_text'];
			}
			$name ='会员奖励明细导出'.date("Y-m-d");
	        $obj->exportExcel($title, $export,$name,'订单列表');
			unset($export);
	}
	/*
	*会员奖励明细
	*/
	function presales() {
		$type_array = array(
			'member_truename'=>'member_truename',
			'member_uid'=>'member_uid',
			'order_sn'=>'order_sn',
		);
		$type = I('get.type');
		$text = I('get.search_text');
		$where = array();
		if($type && in_array($type,$type_array)) {
			$where["$type_array[$type]"] = array("like", "%".$text."%");
		}
		
		$model = D('SpreadWithdrawCash');
		$list = $model ->getPresalesList($where,$limit = 100000);
		$obj  = new \Common\Helper\ExcelHelper();
		$title = array(
			'会员ID','会员账号','奖励','订单号','级别','下单时间', '状态','退款扣款'
			);
			$export = array();
			foreach($list as $k =>$v) {
				$export[$k]['member_uid'] = $v['member_uid'];
				$export[$k]['member_truename'] = $v['member_truename'].' ';
				$export[$k]['reward_amount'] = $v['reward_amount'];
				$export[$k]['order_sn'] = $v['order_sn'].' ';
				$export[$k]['spread_level_text'] = $v['spread_level_text'];
				$export[$k]['order_add_time_text'] = $v['order_add_time_text'].' ';
				$export[$k]['spread_state_text'] = $v['spread_state_text'];
				$export[$k]['refund_amount'] = $v['refund_amount'];
			}
			$name ='会员奖励明细导出'.date("Y-m-d");
	        $obj->exportExcel($title, $export,$name,'订单列表');
			unset($export);
	}
}