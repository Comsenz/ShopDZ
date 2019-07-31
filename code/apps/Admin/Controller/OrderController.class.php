<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\AreaModel;
use Common\Service\ExpressService;
use  Common\Service\SmsService;
use Admin\Model\SettingModel;
use Common\Service\OrdersService;
class OrderController extends BaseController {
	public function lists(){
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
		$list = $model->getList($where,$page);
		$this->assign('lists', $list);
		$this->assign('page',$page->show());
		$this->assign('type', $type);
		$this->display('lists');
    }
	public function  detail() {
		$order_id  = I("get.id",'','intval');
		$orders = D("orders");
		$order_detail = $orders->getOrderDetial($order_id);
		if(!empty($order_detail)) {
			$ordersGoods = $orders->getOrdersGoodsList($order_id);
			$orderCommon = $orders->getOrderCommon($order_id);
			if(!empty($orderCommon)) {
				$storage = D("storage");
				$company = D('companylist');
				$express = $company->find($orderCommon['shipping_express_id']);
				$daddress = $storage->find($orderCommon['daddress_id']);
				$AreaModel =new AreaModel();
				$arealist = $AreaModel ->getAreaList();
				foreach($arealist as $k=>$v) {
					$area[$v['area_id']] = $v['area_name'];
				}
				$orderCommon['daddress'] = $daddress;
				$orderCommon['company'] = $express;
			}
		}

		$this->assign('order_detail', $order_detail);
		$this->assign('ordersGoods', $ordersGoods);
		$this->assign('orderCommon', $orderCommon);
		$this->assign('area', $area);

		$this->display('detail');
	}
	
	public function order_print() {
		$order_id = I('get.order_id');
		$orders = D("orders");
		$order_detail = $orders->getOrderDetial($order_id);
		if(!empty($order_detail)) {
			$ordersGoods = $orders->getOrdersGoodsList($order_id);
			$orderCommon = $orders->getOrderCommon($order_id);
			if(!empty($orderCommon)) {
				$storage = D("storage");
				$company = D('companylist');
				$express = $company->find($orderCommon['shipping_express_id']);
				$daddress = $storage->find($orderCommon['daddress_id']);
				$AreaModel =new AreaModel();
				$arealist = $AreaModel ->getAreaList();
				foreach($arealist as $k=>$v) {
					$area[$v['area_id']] = $v['area_name'];
				}
				$orderCommon['daddress'] = $daddress;
				$orderCommon['company'] = $express;
			}
		}
		$model = new SettingModel();
		$setting = $model->getSetting(array('shop_logo','shop_name'));
		$setting['shop_logo'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$setting['shop_logo'];
		$this->assign('order_detail', $order_detail);
		$this->assign('setting', $setting);
		$this->assign('ordersGoods', $ordersGoods);
		$this->assign('orderCommon', $orderCommon);
		$this->display('order_print');
	}
	
	public function changeorder() {
		$order_id = I("param.id",'','intval');
		$model = D('orders');
		$data = $model->getOrderDetial($order_id);

		if(empty($data)) {
			$this->showmessage('error', L('order_no_exist'));
		}
		$resutl = OrdersService::opOrder($data['order_id'],$data['buyer_id'],0);
		if($resutl){
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($data,true),'action'=>'订单->取消订单','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			$this->showmessage('success', L('operation_success'));
		}
		else
			$this->showmessage('error', L('operation_fail'));
	}
	
    public function delorder(){
		$order_id  = I("get.id",'','intval');
		if(empty($order_id))
			$this->showmessage('error', L('param_error'));
		if(IS_AJAX){
			$orders = D("orders");
			$return = $orders->delOrder($order_id);
			if(!$return)
				$this->showmessage('error', L('delete_fail'));
			
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($order_id,true),'action'=>'订单->删除订单','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			$this->showmessage('success', L('operation_success'));
		}
		$this->showmessage('error', L('operation_illegal'));
    }
	
	public function productlist() {
		$field = I("get.field",'','htmlspecialchars');
		$table_pre =  C('DB_PREFIX');
		$where = array();
		$field_value = I("get.value",'','htmlspecialchars');
		if(!empty($field_value)){
			switch ($field) {
				case 'order_sn':
					$order_sn = I("get.value",'','htmlspecialchars');
					$order_sn = $order_sn;
					$where["{$table_pre}orders.order_sn"] = array('like','%'.$order_sn.'%');
					break;
				case 'trade_no':
					$trade_no = I("get.value",'','htmlspecialchars');
					$trade_no = $trade_no;
					$where["{$table_pre}orders.trade_no"] = array('like','%'.$trade_no.'%');
					break;
				case 'buyer_name':
					$buyer_name = I("get.value",'','htmlspecialchars');
					$buyer_name = $buyer_name;
					$where["{$table_pre}orders.buyer_name"] = array('like','%'.$buyer_name.'%');
					break;
				case 'reciver_name':
					$reciver_name = I("get.value",'','htmlspecialchars');
					$reciver_name = $reciver_name;
					$where["{$table_pre}order_common.reciver_name"] = array('like','%'.$reciver_name.'%');
					break;
			}
		}
		I("get.start") && $where['add_time'] = array('EGT',$start = strtotime(I("get.start")));
		I("get.end") && $where['add_time'] = array('LT',$end = strtotime(I("get.end")));
		$start && $end && $where['add_time'] = array('between',"$start,$end");
		$type = I('get.type',20,'intval');
		$where['order_state'] = $type;
		$model = D('orders');
		$count = $model->getListCount($where);
		$page  = new \Common\Helper\PageHelper($count,10);
		$list = $model->getList($where,$page);
		foreach($list as $k=>$v) {
			$order_id = $v['order_id'];
			$ordersGoods = $model->getOrdersGoodsList($order_id);
			$orderCommon = $model->getOrderCommon($order_id);
			$list[$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
			$list[$k]['goodslist'] = $ordersGoods;
			$list[$k]['ordercommon'] = $orderCommon;
		}
		$this->assign('type', $type);
		$this->assign('list', $list);
		$this->assign('page',$page->show());
		$this->display('productlist');
	}
     
	public function setstorage(){
		//$storage = D("storage");
		$company = D('companylist');
		$order_id = I("param.id",'','intval');
		$model = D('orders');
		$data = $model->getOrderDetial($order_id);
		if(empty($data)) {
			$this->showmessage('error', L('order_no_exist'));
		}
		if($this->checksubmit()){
			$shipping_express_id = 0;
			$shipping_code = '';
			$daddress_id = I("post.daddress_id") ? I("post.daddress_id") : 0;
			$explain = I("post.explain");
			$shipping_type = I("post.shipping_type");
			if(!empty($shipping_type)) {
				$shipping_express_id = I("post.shipping_express_id",0);
				$shipping_code = I("post.shipping_code");
				if(empty($shipping_express_id) || empty($shipping_code)) {
					$this->showmessage('error',L('logistics_infomation_required'));
				}
			}
			
			$uparr = array(
				'order_id'=>$data['order_id'],
				'shipping_time'=>TIMESTAMP,
				'shipping_express_id'=>$shipping_express_id,
				'deliver_explain'=>$explain,
				'daddress_id'=>$daddress_id,
				'shipping_code'=>$shipping_code,
				'order_state'=>30,
			);
			$company_info =   $company->find($shipping_express_id);
			$result = $model->setstorage($uparr);
			if($result) {
				\Common\Helper\LogHelper::adminLog(array('content'=>var_export($uparr,true),'action'=>'订单->设置发货','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			    ExpressService::subscribe($company_info['code'], $shipping_code,$data['order_id']);//订阅物流信息
			    //发送出库提醒短信
			    $smsdata = array('order_sn' => $data['order_sn']);
                SmsService::insert_sms_notice($data['buyer_id'],$data['buyer_phone'],$smsdata,'goodsout');
                
				$this->showmessage('success',L('operation_success'),U('Admin/Order/productlist'));
			}
				$this->showmessage('error',L('operation_fail'));
		}else{
			if(!empty($data)) {
				$ordersGoods = $model->getOrdersGoodsList($order_id);
				$orderCommon = $model->getOrderCommon($order_id);
				$data['goodslist'] = $ordersGoods;
				$data['ordercommon'] = $orderCommon;
			}
			//$storageList = $storage->select();//仓库
			$companyList = $company->where('status=1')->select();//物流公司
			//p($data);die;
		//	$this->assign('storageList', $storageList);
			$this->assign('companyList', $companyList);
			$this->assign('data', $data);
			$this->display('setstorage');
		}
    }

	public function settrade(){
		$order_id = I("param.id",'','intval');
		$orders = D("orders");
		$payment_model  = D('Payment');
		$data = $orders->getOrderDetial($order_id);
		if(empty($data)) {
			$this->showmessage('error', L('order_no_exist'));
		}
		if($data['order_state'] != 10 && $data['payment_starttime'] == 0){
			$this->showmessage('error',L('order_not_allow_pay'));
		}
		if($this->checksubmit()){
			$pay_time = I("param.pay_time");
			$pay_code = I("param.pay_code");
			$trade_no = I("param.trade_no");
			$uparr = array(
				'payment_code'=>$pay_code,
				'payment_time'=>strtotime($pay_time),
				'trade_no'=>$trade_no,
				'order_state'=>20,
			);
			if($uparr['payment_code'] != '' && $uparr['payment_time']){
				$result = $orders->where('order_id=%d',array($order_id))->save($uparr);
				if($result !== false) {
					$data['payment_code'] = $uparr['payment_code'];
					$data['payment_time'] = $uparr['payment_time'];
					$data['trade_no'] = $uparr['trade_no'];
					$data['add_time'] = strtotime($data['add_time']);
					afterNotify($data);
				}
			}else{
				$this->showmessage('error',L('all_not_null'),U('Admin/Order/lists'));
			}
			if($result !== false) {
				\Common\Helper\LogHelper::adminLog(array('content'=>var_export($uparr,true),'action'=>'订单->设置付款','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
				$this->showmessage('success',L('operation_success'),U('Order/lists'));
			}else{
				$this->showmessage('error',L('operation_fail'));
			}
		}else{
			$paylist = $payment_model->where('payment_state=1')->select();
			$this->assign('paylist', $paylist);
			$this->assign('data', $data);
			$this->display('settrade');
		}
    }
	
	public function  query_setting(){
		$company = M('companylist');
		if($this->checksubmit()){
			$update_company = I('post.companys','','htmlspecialchars');
			if(!empty($update_company) && is_array($update_company)){
	            $ids=array_keys($update_company,'on',false);
	            $where['id'] = array('in',$ids);
	            $data['status'] = 1;
	            \Common\Helper\LogHelper::adminLog(array('content'=>var_export($ids,true),'action'=>L('open_state_of_logistics'),'username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
	            $aa = $company->where(true)->data(array('status' => 0))->save();
	            $result = $company->where($where)->data($data)->save();
	            if($result){
					$this->showmessage('success',L('operation_success'),U('Order/query_setting',array('active' => $active)));
				 }else{
				 	$this->showmessage('error',L('operation_fail'),U('Order/query_setting',array('active' => $active)));
				 }
            }else{
            	$result = $company->where(true)->data(array('status' => 0))->save();
            	if($result){
					$this->showmessage('success',L('operation_success'),U('Order/query_setting',array('active' => $active)));
				 }else{
				 	$this->showmessage('error',L('operation_fail'),U('Order/query_setting',array('active' => $active)));
				 }
            }

		}
	    $list = $company->order('id ASC')->select();
		$this->assign('companylist', $list);
	    //模板解析
	    $this->display();        
	}
		//运费设置
	public function expense() {
		$model = new SettingModel();
		if($this->checksubmit()){
			$price =  I('post.price');
			if($price &&  !is_numeric($price) )
				$this->showmessage('error', L('free_shipping_can_numeric'));
				$update_array['expense'] = serialize(array(
					'price'=>$price,
					'type'=>I('post.secure'),
				));
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($update_array,true),'action'=>'物流-运费设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			$model->Settings($update_array);
			$this->showmessage('success',L('operation_success'));
		}else{
			$expense = $model->getSetting('expense');
			$expense = unserialize($expense);
			$this->assign('expense', $expense);
		}
		$this->display('expense');
	}

	public function logistics(){
		$company = D('companylist');
		$order_id = I("param.id",'','intval');
		$model = D('orders');
		$data = $model->getOrderDetial($order_id);
		if(!empty($data)) {
			$ordersGoods = $model->getOrdersGoodsList($order_id);
			$orderCommon = $model->getOrderCommon($order_id);
			$data['goodslist'] = $ordersGoods;
			$data['ordercommon'] = $orderCommon;
			//物流信息
			$express = $company->find($data['ordercommon']['shipping_express_id']);
			// if(empty($express)) 
			// 	$this->returnJson(1,'没有物流信息');
			$express_code =  $express['code'] ;
			$express_sn  =   $data['shipping_code']; 
			$data['express_info']['express_detail'] = ExpressService::query_express($express_code, $express_sn);
			$data['express_info']['express_name'] = $express['name'];
			$data['express_info']['express_code'] =  $express_code;
			$data['express_info']['express_sn'] =   $express_sn;
		}else{
			$this->showmessage('error', L('order_no_exist'));
		}
		$this->assign('data', $data);
		$this->display();
	}

}