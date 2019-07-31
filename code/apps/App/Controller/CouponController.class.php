<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\CouponService;
use Common\Wechat\WxCardController;

class CouponController extends BaseController {

	public function _initialize() {
		$this->getMember();
	}

	//用户优惠券
	public function getonecoupon(){
		$id = I('request.id') ;

		if(empty($id)){
			$this->returnJson(1,'参数错误！');
		}

		$CouPonInfo = CouponService::getRedpacketList( $this->member_info['member_id'],$id);
		if(!empty($CouPonInfo)){
			$data['info'] = $CouPonInfo[0];
			$this->returnJson(0,'sucess',$data);

		}else{
			$this->returnJson(1,'获取失败');
		}


	}
	public function lists(){
		// 1：未使用  2：已使用  3：已失效  4：全部
		$status = I('request.status') ? intval(I('request.status')) : 4;
		$page = intval(I('post.page')) ? intval(I('post.page')) : 1;
		$limit = 10;
		$start = ($page-1)*$limit;
		$points = $this->member_info['member_points'];
		if($status == 4){
			$num = CouponService::getRedpacketTempCount();
			$havepage = ceil($num/$limit);
			$list = CouponService::getRedpacketTempList('',array(),$start,$limit);
			if($list){
				foreach($list as $k => $v){
					$arr_id[] = $v['rpacket_t_id'];
				}
				$whereid = array('in',implode(',',$arr_id));
				$number = CouponService::getCouponCount($this->member_info['member_id'],$whereid);
				foreach ($number as $key => $value) {
					$newnumber[$value['rpacket_t_id']] = $value['number'];
				}
				foreach($list as $k => $v){
					$list[$k]['number'] = $newnumber[$v['rpacket_t_id']]?$newnumber[$v['rpacket_t_id']]:0;
					$list[$k]['t_start'] = date('Y.m.d',$v['rpacket_t_start_date']);
					$list[$k]['t_end'] = date('Y.m.d',$v['rpacket_t_end_date']);
					if($v['rpacket_t_eachlimit'] != 0){
						$list[$k]['limit_num'] = '限领'.$v['rpacket_t_eachlimit'].'张';
						$list[$k]['status'] = $newnumber[$v['rpacket_t_id']]<$v['rpacket_t_eachlimit']?0:1;//0表示可以领取，1表示不能领取
					}else{
						$list[$k]['limit_num'] = '不限领取';
						$list[$k]['status'] = 0;
					}

				}
				
			}else{
				$list = array();
			}
		}else{
			$num = CouponService::getRedpacketCount($this->member_info['member_id'],'',$status);
			$havepage = ceil($num/$limit);
			$list = CouponService::getRedpacketList($this->member_info['member_id'],'',$status,array(),$start,$limit);
			if($list){
				foreach($list as $k => $v){
					$list[$k]['start'] = date('Y.m.d',strtotime($v['rpacket_start_date']));
					$list[$k]['end'] = date('Y.m.d',strtotime($v['rpacket_end_date']));
				}
			}else{
				$list = array();
			}
		}
		$data['list'] = $list;
		$data['status'] = $status;
		$data['havepage'] = $havepage;
		$data['page'] = $page;
		$data['points'] = $points;
		$this->returnJson(0,'sucess',$data);
	}

	
	//优惠券详情页
	public function getcoupon(){
		$rpacket_t_id = intval(I('get.id'));
		if(!$rpacket_t_id){
			$this->returnJson(1,'参数错误！');
		}

		$info = CouponService::getRedpacketTempList($rpacket_t_id);
		if(!$info){
			$this->returnJson(1,'参数错误！');
		}
		$number = CouponService::getEachLimit($this->member_info['member_id'],$rpacket_t_id);
		$info['number'] = $number;
		$info['start'] =  date('Y.m.d',$info['rpacket_t_start_date']);
		$info['end'] =  date('Y.m.d',$info['rpacket_t_end_date']);
		if($info['rpacket_t_eachlimit'] != 0){
			$info['limit_num'] = '限领'.$info['rpacket_t_eachlimit'].'张';
			$info['status'] = $number<$info['rpacket_t_eachlimit']?0:1;//0表示可以领取，1表示不能领取
		}else{
			$info['limit_num'] = '不限领取';
			$info['status'] = 0;
		}
		$data['info'] = $info;
		$this->returnJson(0,'sucess',$data);
	}

	public function coupon_insert(){
		$t_id = intval(I('get.rpacket_t_id'));
		if(!$t_id){
			$this->returnJson(1,'参数错误，请重试！');
		}
		$temp =  CouponService::getRedpacketTempList($t_id);
		if(!$temp){
			$this->returnJson(1,'没有此张优惠券！');
		}
		if($this->member_info['member_points'] < $temp['rpacket_t_points']){
			$this->returnJson(1,'用户积分不足！');
		}
		if(time() >= $temp['rpacket_t_end_date']){
			$this->returnJson(1,'优惠券已过期！');
		}
		$number = CouponService::getEachLimit($this->member_info['member_id'],$t_id);
		if($temp['rpacket_t_eachlimit'] != 0 && $number >= $temp['rpacket_t_eachlimit']){
			$this->returnJson(1,'领取达到上限，不能再领了！');
		}
		$result = CouponService::giveRedpacketToMember($this->member_info,$temp);
		if(!$result){
			$this->returnJson(1,'优惠券发放失败，请重试！');
		}else{
			if($temp['rpacket_t_eachlimit'] != 0 && $number+1 >= $temp['rpacket_t_eachlimit']){
				$this->returnJson(2,'领取达到上限，不能再领了！');
			}
			$this->returnJson(0,'sucess',$number+1);
		}
	}

	public function  GetCodeSign(){
		$setingMoudel = new SettingModel();
		$wx_AppID = $setingMoudel->getSetting('wx_AppID');
		$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
		$jssdk = new WxCardController($wx_AppID,$wx_AppSecret);
		$id = I('get.id');
		$info = CouponService::getRedpacketTempList($id);
		$cardticket = $jssdk->getcardticket();
		$code = rand(1,100000);
		$time = time();
		$array = array($time,$code,$info['rpacket_t_wx_card_id'],$cardticket);
		$jssign = $jssdk->getCardSign($array);
		$signPackage = array('code'=>$code,'sign'=>$jssign);
		$this->returnJson(0,'sucess',$signPackage);
	}
}