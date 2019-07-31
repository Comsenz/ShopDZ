<?php
namespace Admin\Controller;
use Admin\Model\RefundModel;
use Think\Controller;
use Think\Model;
use  \AlipaySubmit;
use Common\PayMent\WxPay\WxPayRefund;
use Common\PayMent\WxPay\WxPayApi;
use Common\Service\SmsService;
use Common\Service\SpreadOrderCronService;
class PresalestableController extends BaseController {
    protected $mod;
    public function __construct(){
        parent::__construct();
        $this->mod = new RefundModel();
    }
    protected function _parameter(){
        $param = I('post.',false,'');
        $data = array();
        $data['sortname'] = $param['sortname'];
        $data['sortorder'] = $param['sortorder'];
        $data['page'] = $param['page'];
        $data['rp'] = $param['rp'];
        unset($param['sortname']);
        unset($param['sortorder']);
        unset($param['page']);
        unset($param['rp']);
        $data['where'] = $param;
        return $data;
    }
    /**
     * 退款列表
     */
    public function refunds(){
        if(IS_AJAX){
            $param = $this->_parameter();
            /* 拼接搜索条件 */
            $where = array();
            foreach ($param['where'] as $k => $v) {
                if(empty($v)) {
                    unset($param['where'][$k]);
                }elseif($k == 'status'){
                    $where[C('DB_PREFIX').'refund.status'] = $v;
                }elseif($k == 'member_mobile') {
                    $where[C('DB_PREFIX').'refund.member_uid'] = M('member')->where(array('member_username=%d',$v))->getField('member_id');
                }else{
                    $where[C('DB_PREFIX').'refund.'.$k] = $v;
                }
            }
            switch($param['sortname']){
                case 'causes_name':
                    $param['sortname'] = 'causes_id';
                break;
                case 'member_mobile':
                    $param['sortname'] = 'member_uid';
                break;
                case 'status_code':
                    $param['sortname'] = 'status';
                break;
                default:
                    $param['sortname'] = 'refund_id';
                break;
            }
            $refund = D('Refund');
            /* 获取数据总条数 */
            $count = $refund->_getRefundsCount($where);
            /* 获取数据 */
            $res = $refund->_getRefunds( $where, $param['page'], $param['rp'], $param['sortname'].' '.$param['sortorder'] );
            /* 处理数据 */
            $data = $refund->_makeData($res);
            /* 拼装返回的数据 */
            $jsonData = array(
                'page'      =>$param['page'],//当前页
                'total'     =>$count,//数据总数
                'params' =>$param['where'],//参数（例：status)
                'rows'      =>$data//返回的数据
                );
            $this->ajaxReturn($jsonData);
        }else{
            $status = I('get.status','0','intval');//退款状态
            $this->assign('status',$status);
            $this->display('refunds');
        }
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
		                $smsdata = array('order_sn' => $post['order_sn'],'refund_sn' => $post['refund_sn']);
		                SmsService::insert_sms_notice($order_info['buyer_id'],$order_info['buyer_phone'],$smsdata,'orderrefund');
		            }
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
        $this->assign('list',$data);
        if(I('get.status') == 'return'){
            $this->display('refundtrue');
        }else{
           $this->display('refunddetail');
        } 
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
                $member['member_mobile'] = $data['value'];
                $member['member_mobile'] && $where['member_uid'] = M('member')->where($member)->getField('member_id');
                break;
        }
        return $where;
    }
}