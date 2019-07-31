<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\OrdersService;
use Common\Service\MemberService;
use Common\Service\PresalesService;
use Common\Helper\ToolsHelper;
class PresalesController extends BaseController {
	protected $member_id = 0;
	protected $returngood_limit_day = 108000;// 3600*30;
	public function _initialize() {
		parent::_initialize();
		$this->getMember();
		$this->member_id = $this->member_info['member_id'];
	}
	//退款
	function refund() {
		//$refund_amount = I('post.refund_amount');
		$causes  = I('post.causes');
		$refund_images  = I('post.refund_images');
		$causes_id = I('post.causes_id','','intval');
		$order_sn = I('post.order_sn');
		if( empty($order_sn) || empty($causes_id) ) {
			$this -> returnJson(1,'数据错误，请重试');
		}
		$count = count($refund_images);
		if($count > 3) {
			$refund_images = array_splice($refund_images,3);
		}
		$refund_images = ToolsHelper::format_url($refund_images,$path='Refund');
		$order_info = OrdersService::getOrderByOrderSn($order_sn,$this->member_id);
		if(empty($order_info)) {
			$this -> returnJson(1,'订单不存在');
		}
		if($order_info['order_state'] !=20) {//只有付款 没有发货时能够退款
			$this -> returnJson(1,'不能申请退款');
		}
		//不能连续退款
		if(!empty($order_info['lock_state'])) {
			$this -> returnJson(1,'不能申请退款');
		}
		$add = array(
			'order_id'=>$order_info['order_id'],
			'order_sn'=>$order_info['order_sn'],
			'refund_images'=>implode("\t",$refund_images),
			'causes'=>$this->cutstr($causes,500,''),
			'causes_id'=>$causes_id,
			'refund_amount'=>$order_info['order_amount'],
			'member_uid'=>$this->member_info['member_id'],
			'member_name'=>$this->member_info['member_username'],
		);
		$data = PresalesService::saveRefund($add);
		if(empty($data['code']))
			$this -> returnJson(0,'申请成功',$data);
		$this -> returnJson(1,'申请失败，请重试',$data);
	}
	
	function returngood() {
		$return_amount = I('post.return_amount');
		$causes  = I('post.causes');
		$return_images  = I('post.return_images');
		$causes_id = I('post.causes_id','','intval');
		$return_goodsnum = I('post.return_goodsnum','','intval');
		$order_sn = I('post.order_sn');
		$rec_id = I('post.rec_id');
		if(empty($return_amount) || empty($order_sn) || empty($causes_id) || empty($return_goodsnum) ) {
			$this -> returnJson(1,'数据错误，请重试');
		}
		$data = OrdersService::getOrderByOrderSn($order_sn,$this->member_id);
		if(empty($data))
			$this->returnJson(1,'数据错误，请重试');
		$common = OrdersService::getOrderCommon($data['order_id']);
		if($common['shipping_time'] + ($this->returngood_limit_day) < strtotime(date('Y-m-d',TIMESTAMP))){
			$this->returnJson(1,'已超过退货期限，请联系客服');
		}
		$rec_info = OrdersService::getOrderGoodsById($rec_id,'goods_returnnum,rec_id,goods_id,goods_name,goods_price,goods_num,goods_image,buyer_id');
		if($rec_info['buyer_id'] != $this->member_id)
			$this->returnJson(1,'数据错误，请重试');
			
		if($rec_info['goods_returnnum'])
			$this->returnJson(1,'已经申请过退货');
		//$return_total = $return_goodsnum * $rec_info['goods_price'];
		$return_total = $rec_info['goods_num'] * $rec_info['goods_price'];
		if($return_goodsnum > $rec_info['goods_num']) {
			$this->returnJson(1,'退货数量大于购买数量');
		}
		if($return_amount > $return_total ) {
			$this->returnJson(1,'退货金额大于购买金额');
		}
		$count = count($return_images);
		if($count > 3) {
			$return_images = array_splice($return_images,3);
		}
		$return_images = ToolsHelper::format_url($return_images,$path='Refund');
		$add = array(
			'order_id'=>$data['order_id'],
			'order_sn'=>$data['order_sn'],
			'rec_id'=>$rec_info['rec_id'],
			'return_images'=>implode("\t",$return_images),
			'causes'=>$this->cutstr($causes,500,''),
			'goods_id'=>$rec_info['goods_id'],
			'causes_id'=>$causes_id,
			'return_amount'=>$return_amount,
			'user_id'=>0,
			'user_name'=>'默认',
			'remark'=>'默认',
			'return_goodsnum'=>$return_goodsnum,
			'member_uid'=>$this->member_info['member_id'],
			'member_name'=>$this->member_info['member_username'],
		);
		$data = PresalesService::saveReturn($add);
		if(empty($data['code']))
			$this -> returnJson(0,'申请成功',$data);
		$this -> returnJson(1,'申请失败，请重试',$data);
	}
	
	function cause() {
		$where = array('status=1');
		$data = PresalesService::getCause($where,'causes_id,causes_name');
		$this -> returnJson(0,'success',$data);
	}
	
	public function refundimg() {
        $post = array();
		$base64_string = I('post.base64_string');
		$type = I('post.type');
		if(empty($base64_string)) {
			$this -> returnJson(1,'数据错误，请重试');
		}
		$path_arr = array('refund'=>'Refund','returngood'=>'Refund','evaluate'=>'Evaluate','avatar'=>'Avatar');
		if(empty($path_arr[$type])) {
			$this -> returnJson(1,'数据错误，请重试');
		}
		$savename = uniqid().'.jpeg';
		$path = $path_arr[$type];
		$image = ToolsHelper::base64_to_img( $base64_string, $savename,$path );
		if($image){
			list(,$_append) = explode("$path",$image);
			$abs_url = realpath(C('TMPL_PARSE_STRING.__UPLOAD__')).'/';
			$image = ToolsHelper::mkThumbnail($abs_url.$path.$_append,1000,1000,$abs_url.$path.$_append);
			$maxwidth = 100;
			$maxheight = 100;
			if($type == 'avatar'){
				$maxwidth = intval(I('post.bodywidth')*0.85);
				$maxheight = intval(I('post.bodyheight')*0.8);
			}
			$newsavename = $path.'/'.date('Y-m-d',TIMESTAMP).'/'.$savename.'.thumb.jpeg';
			$new_img = ToolsHelper::mkThumbnail($abs_url.$path.$_append,$maxwidth,$maxheight,$abs_url.$newsavename);
			if($new_img == false){
				$this -> returnJson(1,'上传失败');
			}
			list($newimgwidth, $newimgheight, $type, $attr) = getimagesize($abs_url.$newsavename);
			$this -> returnJson(0,'success',array('url'=>C('TMPL_PARSE_STRING.__ATTACH_HOST__').$newsavename,'smallurl'=>$newsavename,'width'=>$newimgwidth,'height'=>$newimgheight));
			// $this -> returnJson(0,'success',array('url'=>C('TMPL_PARSE_STRING.__ATTACH_HOST__').$path.$_append));
		}else{
			$this -> returnJson(1,'上传失败');
		}
	}

    public function refundimg_second() {
    	$abs_url = realpath(C('TMPL_PARSE_STRING.__UPLOAD__')).'/';
    	$img_url = $abs_url.'/'.I('post.abssrc');
    	list($oldimg_w, $oldimg_h, $type, $attr) = getimagesize($img_url);
    	$percent = $oldimg_w/$_POST['width'];
		$targ_w = $oldimg_w;
		$targ_h = $oldimg_h;
		$jpeg_quality = 90;
		$img_r = imagecreatefromjpeg($img_url);
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		imagecopyresampled($dst_r,$img_r,0,0,$_POST['x']*$percent,$_POST['y']*$percent,
		$targ_w,$targ_h,$_POST['w']*$percent,$_POST['h']*$percent);
		$savename = 'Avatar/'.date('Y-m-d',TIMESTAMP).'/'.uniqid().'.jpeg';
		header('Content-type: image/jpeg');
		imagejpeg($dst_r,$abs_url.$savename,$jpeg_quality);

    	$data['member_avatar'] = $savename;
		$this->getMember();
		$data['member_id'] = $this->member_info['member_id'];
	    $res = MemberService::editMember($data);
    	if($res['error']){
    		$this->returnJson(1,$res['error']);
        }

		if(file_exists($abs_url.$savename)){
			$this -> returnJson(0,'success',array('url'=>C('TMPL_PARSE_STRING.__ATTACH_HOST__').$savename));
		}else{
			$this -> returnJson(1,'上传失败');
		}

	 }
	 
	 public function refundlist() {
		$list = $details = array();
		$page = I('param.page',1,'intval');
		$prepage = I('param.prepage',20,'intval');
		$refundlist = PresalesService::getRefundList( $this->member_id ,$page,$prepage=20);
		$count = PresalesService::getRefundListCount( $this->member_id);
		$this -> returnJson(0,'success',array('list'=>$refundlist,'count'=>$count));
	 }
	 
	 public function refunddetail() {
		$refund_sn = I('param.refund_sn');
		if(empty($refund_sn)) {
			$this -> returnJson(1,'数据错误，请重试');
		}
		$detail = PresalesService::getRefundDetail($this->member_id,$refund_sn);
		if(empty($detail)) {
			$this -> returnJson(1,'数据错误，请重试');
		}
		$cause = PresalesService::getCause($where=array('causes_id'=>$detail['causes_id']),'causes_id,causes_name');
		$detail['causes_name'] = $cause[0]['causes_name'];
		$detail['show'] = ($detail['lock_state'] || $detail['refund_state'] ==3 )  ? 1: 0;
		$orders_goods = OrdersService::getOrdersGoodsList($detail['order_id']);
		unset($detail['order_id']);
		$return = array('refund'=>$detail,'goodslist'=>$orders_goods);
		$this -> returnJson(0,'success',$return);
	 }
	 
	 public function returnlist() {
		$page = I('param.page',1,'intval');
		$prepage = I('param.prepage',20,'intval');
		$list = PresalesService::getReturnList($this->member_id,$page,$prepage);
		$count = PresalesService::getReturnListCount( $this->member_id);
		$this -> returnJson(0,'success',array('list'=>$list,'count'=>$count));
	 }
	 
	 public function returndetail() {
		$return = I('param.return_sn');
		$rec_id = I('param.rec_id');
		if(empty($return)) {
			$this -> returnJson(1,'数据错误，请重试');
		}
		$detail = PresalesService::getReturnDetail($this->member_id,$return);
		if(empty($detail)) {
			$this -> returnJson(1,'数据2错误，请重试');
		}
		$rec_info = array();
		/*如果有大于退货中的情况，再查找下是否还有正在退货中的状态*/
		if($detail['status'] > 2){
			$rec_info = OrdersService::getOrderGoodsById($detail['rec_id'],'goods_returnnum');
		}
		$detail['show'] = $rec_info['goods_returnnum'] ? 1 : 0;
		$cause = PresalesService::getCause($where=array('causes_id'=>$detail['causes_id']),'causes_id,causes_name');
		$detail['causes_name'] = $cause[0]['causes_name'];
		//$orders_goods = OrdersService::getOrdersGoodsList($detail['order_id']);
		unset($detail['order_id']);
		//$return = array('refund'=>$detail,'goodslist'=>$orders_goods);
		$this -> returnJson(0,'success',$detail);
	 }

}