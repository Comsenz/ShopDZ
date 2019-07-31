<?php
namespace Admin\Controller;
use Admin\Model\RefundModel;
use Think\Controller;
use Think\Model;
use Common\Model\ReturnModel;
use Common\Service\SmsService;
use Common\Service\SpreadOrderCronService;
class PresalesController extends BaseController {
    public $condition = array();
    public $refund_info = array();
    public $return_info = array();
    public $order_info = array();
    public $remark = '';
    protected $mod;
    public function __construct(){
        parent::__construct();
        $this->mod = new RefundModel();
    }
    /**
     * 评论列表
     */
    public function evaluate(){
        $e = M('evaluateGoods'); 
        $field = I("get.field",'','htmlspecialchars');
        $where = array();
        switch ($field) {
            case 'geval_frommembername':
                $geval_frommembername = I("get.value",'','htmlspecialchars');
				$geval_frommembername && $where['geval_frommembername'] = array('like','%'.$geval_frommembername.'%');
                break;
            case 'geval_goodsname':
                $geval_goodsname = I("get.value",'','htmlspecialchars');
                $geval_goodsname && $where['geval_goodsname'] = array('like','%'.$geval_goodsname.'%');
                break;
            case 'geval_orderid':
                $geval_orderid = I('get.value','','intval');
                $geval_orderid && $where['geval_orderid'] = $geval_orderid;
                break;
        }
        I("get.start") && $where['geval_addtime'] = array('EGT',$start = strtotime(I("get.start")));
        I("get.end") && $where['geval_addtime'] = array('LT',$end = strtotime(I("get.end")));
        $start && $end && $where['geval_addtime'] = array('between',"$start,$end");
        $count = $e->where($where)->count();
        $page = new \Common\Helper\PageHelper($count,20);
        $lists = $e->where($where)->order('geval_addtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach($lists as $k => $v) {
            $images = explode("\t",$v['geval_image']);

            $geval_image = array();
            foreach($images as $ik=> $image) {
                $image && $geval_image[$ik] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$image;
            }
            $lists[$k]['geval_image'] = $geval_image;
            $lists[$k]['geval_goodsimage'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$lists[$k]['geval_goodsimage'];
            $lists[$k]['member_mobile'] = M('member')->where(array('member_id' => $v['geval_frommemberid']))->getField('member_mobile');
        }
        //p($lists);die;
        $this->assign('lists',$lists);
        $this->assign('page',$page->show());
        $this->display('evaluate');
    }
    /**
     * 评论详情
     */
    public function evaluatedetails(){
        $e = M('evaluateGoods'); 
        $id = I("get.id",'','intval');
        $lists = $e->where(array("geval_id"=>$id))->find();
        $images = explode("\t",$lists['geval_image']);
        $lists['geval_image'] = array();
        foreach($images as $ik=> $image) {
            $image && $lists['geval_image'][] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$image;
        }
        $lists['geval_goodsimage'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$lists['geval_goodsimage'];
        $lists['member_mobile'] = M('member')->where(array('member_id' => $lists['geval_frommemberid']))->getField('member_mobile');
        $this->assign('lists',$lists);
        $this->display('evaluatedetails');
    }
    /**
     * 删除评论
     */
    public function delevaluate(){
        $geval_id  = I("get.id",'','intval');
        if(empty($geval_id))
            $this->showmessage('error','参数错误');
        if(IS_AJAX){
            $model = M("evaluate_goods");
            $where = array();
            if(is_array($geval_id))
                $where['geval_id'] = array('in',implode(',',$geval_id));
            else
                $where['geval_id'] = $geval_id;
            $return = $model->where($where)->delete();
            if(!$return)
                $this->showmessage('error','删除失败请重试');
			
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($geval_id,true),'action'=>'售后-删除评论','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            $this->showmessage('success','操作成功');
        }
        $this->showmessage('error','非法操作');
    }
    /**
     * 退款列表
     */
    public function refunds(){
        $where = array();
        $get = I('get.');
        if(!empty($get)){
            $where = $this->_search($get);
            // var_dump($where);die();
            if($where['status']){
                $where[C('DB_PREFIX').'refund.status'] = $where['status'];
                unset($where['status']);
            }
        }
        
        $count = $this->mod->where($where)->count();
        $page = new \Common\Helper\PageHelper($count,20);
        
		$data = $this->mod->_getRefunds($where); 
        $data = $this->mod->_makeData($data);
        // $this->assign('page',$data['page']);
        $this->assign('page',$page->show());
        // unset($data['page']);
        $this->assign('list',$data);
		$this->assign('status',$where[C('DB_PREFIX').'refund.status']);
        $this->display('refunds');
    }
    /**
     * 处理退款
     */
    public function editrefund(){
    	if($this->checksubmit()){
    		unset($_POST['form_submit']);
    		$mod = D('refund');
			$id = intval($_POST['refund_id']);
			$post = I('post.');
			unset($post['causes_name']);
			if(is_null($post['status']))
				$this->error('请选择处理意见！'); 
			if(empty($post['remark']))
                $this->error('请填写处理说明！');
		    if(!empty($id)){
                if(mb_strlen($post['remark'],"utf-8") > 100){
                    $post['remark'] = mb_substr($post['remark'],0,100,"utf-8");
                } 
				$data = array(
					'refund_id'=>$post['refund_id'],
					'user_id'=>$post['user_id'],
					'user_name'=>$post['user_name'],
					'status'=>$post['status'],
					'order_id'=>$post['order_id'],
					'remark'=>$post['remark'],
					'enddate'=>TIMESTAMP,
				);
		    	$result=$mod->optionRefund($data);
		    	if($result){
		            if($post['status'] == 2){
		                $order = D('Orders');
		                $order_info = $order->getOrderDetial($post['order_id'],'buyer_id,buyer_phone');
		                $smsdata = array('refund_sn' => $post['refund_sn']);
		                SmsService::insert_sms_notice($order_info['buyer_id'],$order_info['buyer_phone'],$smsdata,'orderrefund');
		            }
					
					\Common\Helper\LogHelper::adminLog(array('content'=>var_export($data,true),'action'=>'售后-退款管理','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
		    	    $this->success('处理成功',U('Presales/refunds'));
		    	}         
		    }else{
		   		$this->error('处理失败'); 
		    }
    	} else {
    		$data = $this->mod->_getFind();

	        $this->assign('list',$data);
	    	$this->display('editrefund');
    	}
    }
    /**
     * 退款详情
     */
    public function refunddetail(){
		$data = $this->mod->_getFind();


        $order_model = M('orders');
        if($data){
            $orders = $order_model->where(array('order_id'=>$data['order_id']))->find();
            $paymentcode = $orders['payment_code'] == 'wx' ? '微信退款' : '支付宝退款';
            $this->assign('paymentcode',$paymentcode);
        }
        $this->assign('list',$data);

        if(I('get.status') == 'return'){
            $this->display('refundtrue');
        }else{
           $this->display('refunddetail');
        } 
    }
    	
    /**
     * 退货列表
     */
	public function returns(){
        $data = array();
        $where = array();
        $get = I('get.');
        if(!empty($get)){
            $where = $this->_search($get);
            if($where['status']){
                $where[C('DB_PREFIX').'returngoods.status'] = $where['status'];
                unset($where['status']);
            }
        }
        $data = $this->mod->_getReturns($where);
        $data = $this->mod->_makeData($data);
        //p($data);
        $this->assign('page',$data['page']);
        unset($data['page']);
        $this->assign('list',$data);
        $this->assign('status',$where[C('DB_PREFIX').'returngoods.status']);
        $this->display('returns');
    }
    /**
     * 处理退货
     */
    public function editreturn(){
    	if($this->checksubmit()){
    		$mod = D('refund');
	        $id = intval($_POST['rg_id']);
	        $post = I('post.');
	        $post['enddate'] = time();
	        unset($post['causes_name']);
			if(is_null($post['status']))
				$this->error('请选择处理意见'); 
            if(empty($post['remark']))
                $this->error('请填写处理说明！');
	        if(!empty($id)){
                    if(mb_strlen($post['remark'],"utf-8") > 100){
                        $remark = mb_substr($post['remark'],0,100,"utf-8");
                    }
					$data = array(
						'rg_id'=>$post['rg_id'],
						'rec_id'=>$post['rec_id'],
						'user_id'=>$post['user_id'],
						'user_name'=>$post['user_name'],
						'status'=>$post['status'], 
						'order_id'=>$post['order_id'],
						'remark'=>$remark,
						'enddate'=>TIMESTAMP,
					);
	            $result=$mod->optionReturnGood($data);
	            if($result){
	                if($post['status'] == 2){
	                    $order = D('Orders');
	                    $order_info = $order->getOrderDetial($post['order_id'],'buyer_id,buyer_phone');
	                    $smsdata = array('return_sn' => $post['return_sn']);
	                    SmsService::insert_sms_notice($order_info['buyer_id'],$order_info['buyer_phone'],$smsdata,'returngoods');
	                }
					\Common\Helper\LogHelper::adminLog(array('content'=>var_export($data,true),'action'=>'售后-退货管理','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
	                $this->success('处理成功','returns');
	            }else{
	                $this->error('处理失败'); 
	            }
	        }else{
	            $this->error('处理失败'); 
	        }
    	}else{
			$returnstatus = array(
				0=>'未退货',
				1=>'待处理',
				2=>'退款中',
				3=>'已退款',
				4=>'拒绝退款',
				5=>'待支付平台处理',
				6=>'支付平台处理失败',
			);
	        $rg_id = I("get.id",'','intval');
	        if(empty($rg_id)){
	           $this->error('缺少参数'); 
	        }
	        $model = D('refund');
	        $lists =  $model->getReturn($rg_id);
	        if($lists){
	            $causes_id = $lists['causes_id'];
	            $result = M('causes')->field('causes_name')->where("causes_id=$causes_id")->find();
	            $lists['causes_name'] = $result['causes_name'];
				
				//订单详情处理
				$order_id = $lists['order_id'];
				$orders = D("orders");
				$order_detail = $orders->getOrderDetial($order_id);
				if(!empty($order_detail)) {
					$ordersGoods = $orders->getOrdersGoodsList($order_id);
					
					foreach($ordersGoods as $k => $v) {
						if($v['rg_id']){
							 $return = $this->mod->getReturnGoodsByRgId($v['rg_id']);
							$ordersGoods[$k]['return'] = $returnstatus[$return['status']];
						}else{
							$ordersGoods[$k]['return'] = $returnstatus[0];
						}
					}
				}
	        }
	        $this->assign('list',$lists);
	        $this->assign('ordersGoods',$ordersGoods);
	        $this->assign('order_detail',$order_detail);
	        $this->display('editreturn'); 
	    }
    }
	/**
     * 退货详情
     */
    public function returndetail(){
        $rg_id = I("get.id",'','intval');
        if(empty($rg_id)){
           $this->error('缺少参数'); 
        }
        $model = D('refund');
        $lists =  $model->getReturn($rg_id);
        if($lists){
            $causes_id = $lists['causes_id'];
            $result = M('causes')->field('causes_name')->where("causes_id={$causes_id}")->find();
            $lists['causes_name'] = $result['causes_name'];
        }
        $this->assign('list',$lists);
        if(I('get.status') == 'return'){
            $this->display('returntrue');
        }else{
           $this->display('returndetail');
        }
    }
	/**
     * 退款原因列表
     */
    public function causes(){
    	$mod = M('causes');
        $causes_name = I('get.search_text');
        !empty($causes_name) && $where['causes_name'] = array('like','%'.$causes_name.'%');
		$count = $mod->where($where)
				->count();
		$page = new \Common\Helper\PageHelper($count,20);
		$data = $mod->where($where)
                ->order('status, causes_id desc')
				->select();
		$this->assign('page',$page->show());
        $this->assign('list',$data);
		$this->assign('search_text',$causes_name);
        $this->display('causes');
    }
    /**
     * 添加修改退款原因
     */
    public function addcauses(){
        $mod = M('causes');
        $id = intval(I('get.causes_id'));
        if(empty($id)){
            $post = I('post.');
            if(mb_strlen($post['causes_name'],"utf-8") > 10){
                $post['causes_name'] = mb_substr($post['causes_name'],0,10,"utf-8");
            }
            if(empty($post)){
                $this->assign('causes','新增原因');
                $this->assign('status',1);
                $this->display();
            }elseif(empty($post['causes_id'])){
                $post['clicknum'] = 0;
                //$post['status'] = 1;
                if(empty($post['causes_name']))
                    $this->error('请填写退款退货原因！'); 
                $post['status'] = isset($post['status'])?1:2;
                $result=$mod->add($post);
                if($result){
                    $this->success('添加成功','causes');
                }else{
                    $this->error('保存失败','causes');
                }
            }else{
                if(empty($post['causes_name']))
                    $this->error('请填写退款退货原因！'); 
                $post['causes_id'] = intval($post['causes_id']);
                $post['status'] = isset($post['status'])?1:2;
                $result = $mod->save($post);
				\Common\Helper\LogHelper::adminLog(array('content'=>var_export($post,true),'action'=>'售后-添加修改退款原因','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                if(IS_AJAX){
					 $this->success('修改成功','causes');
                  //  $this->ajaxReturn($result,'成功',$result);
                }else{
                    if(false !== $result){
                        $this->success('修改成功','causes');
                    }else{
                        $this->error('修改失败','causes');
                    }
                }
            }
        }else{
            $this->assign('causes','修改原因');
            $result=$mod->field('causes_id,causes_name,status')
                    ->where(array('causes_id'=>$id))
                    ->find();
            $this->assign('causes_id',$result['causes_id']);
            $this->assign('causes_name',$result['causes_name']);
            $this->assign('status',$result['status']);
            $this->display();
        }
    }
    /**
     * 删除退款原因
     */
    public function delcauses(){
        $id = I('get.causes_id','','intval');
        if(!empty($id)){
            if(is_array($id))
                $where['causes_id'] = array('in',implode(',',$id));
            else
                $where['causes_id'] = $id;
        	$res = M('causes')->where($where)->delete();
        	if($res){
				\Common\Helper\LogHelper::adminLog(array('content'=>var_export($id,true),'action'=>'售后-删除退款原因','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
        		$this->ajaxReturn(array("status"=>1,'data'=>1,'info'=>'删除成功！'));
        	}else{
            	$this->ajaxReturn(array("status"=>0,'data'=>0,'info'=>'删除失败！'));
        	}
        }
    }
    /**
     * 意见反馈列表
     */
	public function feedbacks(){
        //$this->assign('status','-1');
        $mod = M('feedback');
        $get = I('get.');
        if(!empty($get['min_date']) or !empty($get['max_date'])){
            $where['ftime'] = array(
                            'between',
                            strtotime($get['min_date']).','.strtotime($get['max_date']));
        }
        if(!empty($get['value'])){
            $where[$get['field']] = $get['value'];
        }
        if(isset($get['status']) && $get['status'] != -1){
            $where['status'] = $get['status'];
        }
        $this->assign('status',isset($get['status'])?$get['status']:-1);
        $count = $mod->where($where)
                ->count();
        $page = new \Common\Helper\PageHelper($count,20);
        $res = $mod->where($where)
                ->limit($page->firstRow.','.$page->listRows)
                ->order('ftime desc')
                ->select();
        foreach($res as $k=>$v){
            $res[$k]['status'] =  $v['status'] ? '已处理' : '待处理';
        }
        $this->assign('feedback_list',$res);
        $this->assign('page',$page->show());
        $this->display('feedbacks');
    }
    /**
     * 删除意见反馈
     */
    public function delfeedback(){
        $id  = I("get.id",'','intval');
        if(empty($id))
            $this->showmessage('error','参数错误');
        if(IS_AJAX){
            $model = M("feedback");
            $where = array();
            if(is_array($id))
                $where['id'] = array('in',implode(',',$id));
            else
                $where['id'] = $id;
            $return = $model->where($where)->delete();
            if(!$return)
                $this->showmessage('error','删除失败请重试');
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($id,true),'action'=>'售后-删除意见反馈','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            $this->showmessage('success','操作成功');
        }
        $this->showmessage('error','非法操作');
    }
    /**
     * 处理意见反馈
     */
    public function editfeedback(){
        $id = intval(I('get.id'));
        if(!empty($id)){
            
            $res = M('feedback')->find($id);
            if($res){
                $this->assign('list',$res);
            }else{
                $this->errot('数据出错!');
            }
            $this->display('editfeedback');
        }elseif(!empty($_POST)){
            $post = I('post.');
            
            if(empty($post['id']))
                $this->error('数据出错！'); 
            if(empty($post['instruction']))
                $this->error('请输入处理意见！');
            $data['id'] = intval($post['id']);
            $data['status'] = intval($post['status']);
            $data['instruction'] = I('post.instruction','','htmlspecialchars'); 
            $data['username'] = $this->admin_user['username'];
            $data['ctime'] = time();
            $res = M('feedback')->save($data);
            if($res){
				\Common\Helper\LogHelper::adminLog(array('content'=>var_export($data,true),'action'=>'售后-处理意见反馈','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
                $this->success('处理成功','feedbacks');
            }else{
                $this->error('处理失败!');
            }
        }else{
            $this->redirect('Presales/feedbacks');
        }
    }
    /**
     * 意见反馈详情
     */
    public function feedbackdetail(){
        $id = intval(I('get.id'));
        if(!empty($id)){
            $res = M('feedback')->find($id);
            if($res){
                $this->assign('list',$res);
            }else{
                $this->errot('数据出错！');
            }
        }
        $this->display('feedbackdetail');
    }
    /**
     * 处理搜索条件
     */
    private function _search($data){
    	//这里是筛选条件
        $where = array();
        $status = $data['status'];
        if($status != null){
            $where['status'] = $status;
        }
        $data['start'] && $where['dateline'] = array('EGT',$start = strtotime(I("get.start")));
        $data['end'] && $where['dateline'] = array('LT',$end = strtotime(I("get.end")));
        $data['start'] && $data['end'] && $where['dateline'] = array('between',"$start,$end");
        // $this->assign('s',empty($where['g.state'])?$where['r.status']:$where['g.state'] + 1);
        $field = $data['where'];
        switch ($field) {
            case 'order_sn':
                $order_sn = $data['value'];
                $order_sn && $where['order_sn'] = $order_sn;
                break;
            case 'return_sn':
                $return_sn = $data['value'];
                $return_sn && $where['return_sn'] = $return_sn;
                break;
            case 'refund_sn':
                $refund_sn = $data['value'];
                $refund_sn && $where['refund_sn'] = $refund_sn;
                break;
            case 'member_mobile':
                $member['member_username'] = $data['value'];
                $member['member_username'] && $where['member_uid'] = M('member')->where($member)->getField('member_id');
                break;
        }
        return $where;
    }
    
    /**
     * 退货(单个商品申请退换货) 财务退款
     */
    public function sreturn_pay(){
        $id = I('id',0,'htmlspecialchars');
        $status = I('get.status',0,'intval');
        $remark = I('get.remark','','htmlspecialchars');
        if(!$id)
            $this->error('参数错误');
        if(empty($status))
            $this->error('请选择退款方式！');
        if(empty($remark))
            $this->error('请输入处理说明');
        $condition['status'] = array('in',array('2','5'));
        $condition['return_sn'] = $id;
        $return_info =  M('returngoods')->where($condition)->find(); 
        if(empty($return_info)){
            $this->error('参数错误');
        }
        $order_info = M('orders')->getby_order_id(intval($return_info['order_id']));
        if(empty($order_info)){
            $this->error('系统错误');
        }
        
        $data = array(
            'payment_code'  => $order_info['payment_code'],//订单支付方式
            'order_id'      => $order_info['order_id'],//订单ID
            'buyer_id'      => $order_info['buyer_id'],//用户ID
            'buyer_phone'   => $order_info['buyer_phone'],//用户手机号
            'trade_no'      => $order_info['trade_no'],//交易流水号
            'total_fee'     => $return_info['return_amount'],//退款金额
            'order_amount'  => $order_info['order_amount'],//订单总金额
            'order_add_time'=> $order_info['add_time'],//订单创建时间
            'batch_no'      => $return_info['return_sn'], //退款单号
            'remark'        => $remark,//退货处理意见
            'mark'          => '【'.M('setting')->where(array('name'=>'shop_name'))->getField('value').'】订单编号：'.$order_info['order_sn'].'下rec_id'.$return_info['rec_id'].'退款',
            'returnurl'     => U('Presales/returns'),//提交表单之后跳转的链接
            'return_type'   => $status//退款方式（1：原路退款，2：人工退款）
        );
        /* 处理退款操作 */
        $mod = new ReturnModel();
        $res = $mod->router($data);
        if($res['code'] == 0){
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($data,true),'action'=>'售后-单个商品申请退换货','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            $this->success($res['msg'],U($res['data']));
        }elseif($res['code'] == 1){
            $this->error($res['msg'],U($res['data']));
        }
    }
    /**
     * 退款 （整个订单取消退款）财务退款 
     */
    public function refund_pay(){
        $id = I('get.id',0,'htmlspecialchars');
        $status = I('get.status',0,'intval');
        $remark = I('get.remark','','htmlspecialchars');
        if(!$id)
            $this->error('参数错误');
        if(empty($status))
            $this->error('请选择退款方式！');
        if(empty($remark))
            $this->error('请输入处理说明');
        $condition['status'] = array('in',array('2','5'));
        $condition['refund_sn'] = $id;
        $refund_info =  M('refund')->where($condition)->find();
        if(empty($refund_info)){
            $this->error('参数错误2');
        }
        $order_info = M('orders')->getby_order_id(intval($refund_info['order_id']));
        if(empty($order_info)){
            $this->error('系统错误3');
        }
        
        $data = array(
            'payment_code'  => $order_info['payment_code'],//订单支付方式
            'order_id'      => $order_info['order_id'],//订单ID
            'buyer_id'      => $order_info['buyer_id'],//用户ID
            'buyer_phone'   => $order_info['buyer_phone'],//用户手机号
            'trade_no'      => $order_info['trade_no'],//交易流水号
            'total_fee'     => $refund_info['refund_amount'],//退款金额
            'order_amount'  => $order_info['order_amount'],//订单总金额
            'order_add_time'=> $order_info['add_time'],//订单创建时间
            'batch_no'      => $refund_info['refund_sn'], //退款单号
            'remark'        => $remark,//退货处理意见
            'mark'          => '【'.M('setting')->where(array('name'=>'shop_name'))->getField('value').'】订单编号：'.$order_info['order_sn'].'退款',
            'returnurl'     => U('Presales/refunds'),//提交表单之后跳转的链接
            'return_type'   => $status//退款方式（1：原路退款，2：人工退款）
        );
        $mod = new ReturnModel();
        $res = $mod->router($data);
        if($res['code'] == 0){
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($data,true),'action'=>'售后-整个订单取消退款','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            $this->success($res['msg'],U($res['data']));
        }elseif($res['code'] == 1){
            $this->error($res['msg'],U($res['data']));
        }
    }
    
}