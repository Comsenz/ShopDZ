<?php
namespace Common\Service;
use Think\Model;
use Common\Wechat\WxcardController;
use Admin\Model\SettingModel;
class CouponService{
   //  获取可用优惠券模板列表
   static  function getRedpacketTempList($t_id='', $condition = array(),$start=0,$limit=0) {
	    $time = time();
	    $where = "`rpacket_t_start_date` < {$time} AND `rpacket_t_end_date` > {$time} AND `rpacket_t_state`=1 AND `rpacket_t_total` > `rpacket_t_giveout`";
	    if(!empty($t_id)){
	    	$where .= " AND rpacket_t_id={$t_id}";
	    }
      $limitstr = '';
      if($limit != 0){
        $limitstr = $start.','.$limit;
      }
	    if(!empty($condition)){
	    	$list = M('redpacket_template')->where($where)->where($condition)->order('rpacket_t_save_time DESC')->limit($limitstr)->select();
	    }else{
			$list = M('redpacket_template')->where($where)->order('rpacket_t_save_time DESC')->limit($limitstr)->select();
		}
    if(!empty($list)){
      foreach($list as $k => $v){
          if($v['rpacket_t_price'] < 100){
             $list[$k]['rpacket_t_price'] = number_format($v['rpacket_t_price'],2,'.','');
          }
          if($v['rpacket_t_limit'] < 100){
             $list[$k]['rpacket_t_limit'] = number_format($v['rpacket_t_limit'],2,'.','');
          }
      }
    }
		// var_dump(M()->getLastSql());die;
		return $t_id ? $list[0] : $list;
   }

   //获取可用优惠券模板count
   static  function getRedpacketTempCount($t_id='', $condition = array()) {
      $time = time();
      $where = "`rpacket_t_start_date` < {$time} AND `rpacket_t_end_date` > {$time} AND `rpacket_t_state`=1 AND `rpacket_t_total` > `rpacket_t_giveout`";
      if(!empty($t_id)){
        $where .= " AND rpacket_t_id={$t_id}";
      }
      if(!empty($condition)){
        $count = M('redpacket_template')->where($where)->where($condition)->count();
      }else{
        $count = M('redpacket_template')->where($where)->count();
      }
    return empty($count) ? 0 : $count ;
   }

   //获取用户优惠券
   static function getRedpacketList($member_id,$rpacket_id,$rpacket_state="",$condition=array(),$start=0,$limit=0){
   		$where = array();
   		$where['rpacket_owner_id'] = $member_id;
   		if(!empty($rpacket_id)){
   			if(is_array($rpacket_id)){
   				$where['rpacket_id'] = array('in',$rpacket_id);
   			}else{
   				$where['rpacket_id'] = $rpacket_id;
   			}
   		}
   		if(!empty($rpacket_state)){
   			$where['rpacket_state'] = $rpacket_state;
   		}
      $order = '';
      if(in_array($rpacket_state,array(2,3))){
        $order = 'rpacket_used_date DESC';
      }else{
        $order = 'rpacket_active_date DESC';
      }
      $limitstr = '';
      if($limit != 0){
        $limitstr = $start.','.$limit;
      }
   		$data = M('redpacket')->where($where)->order($order)->limit($limitstr)->select();
  		foreach($data as $k =>$v) {
  			$data[$k]['rpacket_start_date'] = date("Y-m-d",$v['rpacket_start_date']);
  			$data[$k]['rpacket_end_date'] = date("Y-m-d",$v['rpacket_end_date']);
  			if( $v['rpacket_start_date'] < TIMESTAMP && TIMESTAMP < $v['rpacket_end_date']) {
  				$data[$k]['rpacket_use_status'] = 1;
  			}else {
  				$data[$k]['rpacket_use_status'] = 0;
  			}
        if($v['rpacket_price'] < 100){
           $data[$k]['rpacket_price'] = number_format($v['rpacket_price'],2,'.','');
        }
        if($v['rpacket_limit'] < 100){
           $data[$k]['rpacket_limit'] = number_format($v['rpacket_limit'],2,'.','');
        }
  		}
    	return empty($data) ? array() : $data ;
   }

   //获取用户优惠券count
   static function getRedpacketCount($member_id,$rpacket_id,$rpacket_state=""){
      $where = array();
      $where['rpacket_owner_id'] = $member_id;
      if(!empty($rpacket_id)){
        if(is_array($rpacket_id)){
          $where['rpacket_id'] = array('in',$rpacket_id);
        }else{
          $where['rpacket_id'] = $rpacket_id;
        }
      }
      if(!empty($rpacket_state)){
        $where['rpacket_state'] = $rpacket_state;
      }
      $data = M('redpacket')->where($where)->count();
      return empty($data) ? 0 : $data ;
   }



   //发放优惠券
   static function giveRedpacketToMember($member,$temp){
    	$code = CouponService::get_rpt_code($member['member_id']);
    	$data = array(
    			'rpacket_code'			=> $code,
    			'rpacket_t_id'			=> $temp['rpacket_t_id'],
    			'rpacket_title'			=> $temp['rpacket_t_title'],
    			'rpacket_desc'			=> '',
    			'rpacket_start_date'	=> $temp['rpacket_t_start_date'],
    			'rpacket_end_date'		=> $temp['rpacket_t_end_date'],
    			'rpacket_price'			=> $temp['rpacket_t_price'],
    			'rpacket_limit'			=> $temp['rpacket_t_limit'],
    			'rpacket_state'			=> 1,
    			'rpacket_active_date'	=> time(),
    			'rpacket_owner_id'		=> $member['member_id'],
    			'rpacket_owner_name'	=> $member['member_username'],
          'rpacket_color'       => $temp['rpacket_t_color'],
    		);
      $flag = false;
      $model = new Model();
      $model->master(true);
      $model->startTrans();
      $temp_result = $model->table(C('DB_PREFIX').'redpacket_template')->master(true)->where(array('rpacket_t_id' => $temp['rpacket_t_id']))->setInc('rpacket_t_giveout');
      if($temp['rpacket_t_points'] != 0){
        // $temp_points = M('member')->where(array('member_id' => $member['member_id']))->setDec('member_points',$temp['rpacket_t_points']);
        $insert_arr['pl_memberid'] = $member['member_id'];
        $insert_arr['pl_membername'] = $member['member_username'];
        $insert_arr['pl_points'] = $temp['rpacket_t_points'];
        $temp_points = \Common\Helper\PointsHelper::addPointsTrans($insert_arr,'give_coupon',$model);
      }else{
		$temp_points = true;
	  }

    	$result = $model->table(C('DB_PREFIX').'redpacket')->master(true)->add($data);
      if($temp_result && $result && $temp_points){
        $model->commit();
        $flag = true;
      }else{
        $flag = false;
        $model->rollback();
      }
    	return $flag;
    }
    //微信发放优惠券
    static function giveRedpacketToWx($code,$openid,$temp){

        $data = array(
            'rpacket_code'			=> $code,
            'rpacket_t_id'			=> $temp['rpacket_t_id'],
            'rpacket_title'			=> $temp['rpacket_t_title'],
            'rpacket_color'			=> $temp['rpacket_t_color'],
            'rpacket_desc'			=> '',
            'rpacket_start_date'	=> $temp['rpacket_t_start_date'],
            'rpacket_end_date'		=> $temp['rpacket_t_end_date'],
            'rpacket_price'			=> $temp['rpacket_t_price'],
            'rpacket_limit'			=> $temp['rpacket_t_limit'],
            'rpacket_state'			=> 1,
            'rpacket_active_date'	=> time(),
            'rpacket_t_wx_card_id' => $temp['rpacket_t_wx_card_id'],
            'rpacket_openid' => $openid,
        );
        $member = M('member')->where(array('weixin_openid'=>$openid))->find();
        if($member){
            $data['rpacket_owner_id'] = $member['member_id'];
            $data['rpacket_owner_name'] = $member['member_username'];
        }

        $result = M('redpacket')->add($data);
        $temp_result =  M('redpacket_template')->where(array('rpacket_t_id' => $temp['rpacket_t_id']))->setInc('rpacket_t_giveout');
        return $result;
    }
    //生成优惠券code
    private function get_rpt_code($member_id = 0){
        static $num = 1;
        $sign_arr = array();
        $sign_arr[] = sprintf('%02d',mt_rand(10,99));
        $sign_arr[] = sprintf('%03d', (float) microtime() * 1000);
        $sign_arr[] = sprintf('%010d',time() - 946656000);
        if($member_id){
            $sign_arr[] = sprintf('%03d', (int) $member_id % 1000);
        } else {
            //自增变量
            $tmpnum = 0;
            if ($num > 99){
                $tmpnum = substr($num, -1, 2);
            } else {
                $tmpnum = $num;
            }
            $sign_arr[] = sprintf('%02d',$tmpnum);
            $sign_arr[] = mt_rand(1,9);
        }
        $code = implode('',$sign_arr);
        $num += 1;
        return $code;
    }
    //微信删除优惠券
    static function delRedpacketToWx($code,$openid){
        $where = array('rpacket_code'=>$code,'rpacket_openid'=>$openid);
        $delCoupon = M('redpacket')->where($where)->delete();
        return $delCoupon;

    }
//获取微信已领取的当前优惠券

    static function getWxEachLimit($openid,$rpacket_t_wx_card_id,$code){
        $where = array(
            'rpacket_openid'	=> $openid,
            'rpacket_t_wx_card_id'		=> $rpacket_t_wx_card_id,
            'rpacket_code'		=> $code,

        );
        $num = M('redpacket')->where($where)->count();
        return $num;
    }
    //获取已领取的当前优惠券
    static function getEachLimit($member_id,$temp_id){
      	$where = array(
      			'rpacket_owner_id'	=> $member_id,
      			'rpacket_t_id'		=> $temp_id,
      		);
      	$num = M('redpacket')->where($where)->count();
      	return $num;
    }
    /* 获取已领取的优惠券数量 */
    static function getCouponCount($member_id,$temp_id){
        $where = array(
            'rpacket_owner_id'  => $member_id,
            'rpacket_t_id'    => $temp_id,
          );
        $data = M('redpacket')->field('rpacket_t_id,count(rpacket_t_id) number')->where($where)->group('rpacket_t_id')->select();
        return $data;
    }
    /**
     *  使用优惠券
     *  @param  $member_id : 用户ID
     *          $rpacket_id : 优惠券ID
     *          $rpacket_t_id : 优惠券模板ID
     **/
    static function useRedpacket($member_id,$rpacket_id,$rpacket_t_id,$order_id){
      $t_result = M('redpacket_template')->where(array('rpacket_t_id' => $rpacket_t_id))->setInc('rpacket_t_used');
      $where = array(
              'rpacket_owner_id'  => $member_id,
              'rpacket_id'        => $rpacket_id,
        );
      $data = array(
            'rpacket_used_date' =>  TIMESTAMP,
            'rpacket_state'     =>  2,
            'rpacket_order_id'  =>  $order_id,
        );
      $redpacket = M('redpacket')->where($where)->save($data);

    }
    /**
     *  同步微信卡券到网站
     *  @param  $openid : 微信用户openid
     *  @param  $openid : 用户uid
     **/
    static function WxCardSynchroWeb($openid,$uid)
    {
        if (empty($openid) || empty($uid)) {
            return false;
        }
        $member = M('member')->where(array('member_id'=>$uid))->find();
        $data = array();
        $data['rpacket_owner_id'] = $uid;
        $data['rpacket_owner_name'] = $member['member_username'];
        $updateCard = M('redpacket')->where(array('rpacket_openid' => $openid))->save($data);
        if($updateCard !== false){
            return true;
        }
    }
    static function use_card ($code,$card_id){
       $setingMoudel = new SettingModel();
        $wx_AppID = $setingMoudel->getSetting('wx_AppID');
        $wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
        $wxapi = new WxcardController($wx_AppID,$wx_AppSecret);
        $card =   $wxapi-> use_card($code,$card_id);
        if($card){
            return true;
        }else{
            return false;
        }


    }
}