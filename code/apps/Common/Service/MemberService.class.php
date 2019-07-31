<?php
namespace Common\Service;
use \Admin\Model\MemberModel;
class MemberService {
    
	public  function  getMemberInfo($condition = array(), $fields = '*'){;
		$member_info = D('member')->where($condition)->field($fields)->find();
		if($member_info){
			$avatar_img = IndexService::getSetting('shop_member');
			$member_info['member_avatar'] = $member_info['member_avatar']?C('TMPL_PARSE_STRING.__ATTACH_HOST__').$member_info['member_avatar']:$avatar_img;
		}
		return $member_info;
	}

	static public function getMemberList($condition = array(), $fields = '*', $group = '') {
        return D('member')->where($condition)->field($fields)->group($group)->select();
    }

    static public function editMember($member_info){
    	$res['error'] = '';
    	$condition = array();
    	if($member_info['member_id']){
    		$condition = array('member_id' => $member_info['member_id']);
    	}
    	/*if($member_info['member_mobile']) {
    		$condition = array('member_mobile' => $member_info['member_mobile']);
    	}*/
    	if(!count($condition)){
			$res['error'] = '参数错误';
			return $res;
    	}
    	$info = self::getMemberInfo($condition);
    	if(!$info) {
    		$res['error'] = '用户不存在';
    		return $res;
    	}
    	$m_model =new MemberModel();
		$member_info['member_id'] =  $info['member_id'];
		$m_model->savemember($member_info);
		return $res;
    }

    //记录登录信息
    static public function loginMember($member_id) {
    	$info = D('member')->where('member_id = '.$member_id)->field('member_login_time,member_login_ip')->find();
    	$user['member_login_num'] = $info['member_login_num'] + 1;
    	$user['member_login_time'] = TIMESTAMP;
    	$user['member_old_login_time'] = $info['member_login_time'];
    	$user['member_login_ip'] = get_client_ip();
    	$user['member_old_login_ip'] = $info['member_login_ip'];
    	D('member')->where('member_id ='.$member_id)->setField($user); 
    }

    static public function addMember($member_info){
    	$res['member_id'] = 0;
    	$res['error'] = '';
		$checkmust = array(
			'member_mobile'=>'手机号不能为空',
			'member_passwd'=>'密码不能为空',
			'confirm_password'=>'确认密码不能为空或不对',
		);
		foreach( $checkmust as $key => $v ) {
		
			if(empty( $member_info[$key] )) {
				$res['error'] = $checkmust[$key];
			}
			if($key == 'confirm_password' && $member_info['member_passwd'] != $member_info[$key]) {
				$res['error'] = $checkmust[$key];
			}
		}
		$info = self::getMemberInfo(array('member_mobile' => $member_info['member_mobile']));
		if($info){$res['error'] = '用户已存在';}
		if($res['error']) return $res;

		$m_model =new MemberModel();
		$member_info['member_username'] = $member_info['member_mobile'];
		$member_id = $m_model->addmember($member_info);
		if($member_id) {
			$res['member_id'] = $member_id;
		} else {
			$res['error'] = '注册失败';
		}
		return $res;

    }
	//微信注册注册用户
	static public function wxaddMember($member_info){
		$res['member_id'] = 0;
		$res['error'] = '';
		$info = self::getMemberInfo(array('member_username' => $member_info['member_username']));
		if($info){$res['error'] = '用户已存在';}
		$m_model =new MemberModel();
		$member_id = $m_model->wxaddmember($member_info);
		if($member_id) {
			$res['member_id'] = $member_id;
		} else {
			$res['error'] = '注册失败';
		}
		return $res;

	}

    /**
     * 新增token表
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    static public function addUserToken($param){
        return D('user_token')->add($param);
    }

    //根据token的key获取单条信息
    static public function delMbUserToken($where) {
    	return D('user_token')->where($where)->delete();
    }

    //根据token的key获取单条信息
    static public function getUserTokenInfoByToken($key) {
    	return D('user_token')->where(array('token'=>$key))->find();
    }

    //用户收货地址添加和编辑
    static public function saveAddress($data){

    	$res['address_id'] = 0;
    	$res['error'] = '';
		$checkmust = array(
			'area_id'=>'请选择完整地区',
			'city_id'=>'请选择完整地区',
			'province_id'=>'请选择完整地区',
			'address'=>'详细地址不能为空',
			'true_name'=>'收件人不能为空',
			'tel_phone'=>'手机号不能为空',
		);

		foreach( $checkmust as $key => $v ) {
			if(empty($data[$key])) {
				$res['error'] = $checkmust[$key];
			}
		}
		if($res['error']) return $res;

    	$data['is_default'] = isset($data['is_default']) && $data['is_default'] == 1?1:0;
    	if($data['address_id']) {
    		$res['address_id'] = $data['address_id'];
    		unset($data['address_id']);
    		M('address')->where('address_id = '.$res['address_id'])->save($data);
    	} else {
			$condition['member_id'] = $data['member_id'];
			$count = self::getMyCountAddress($condition);
			if($count >=10){
				return $res = array('error' =>'地址最多10条','address_id'=>0);
			}
    		$res['address_id'] = M('address')->add($data);
    	}
    	//设默认地址
    	$address_count = M('address')->where('member_id = '.$data['member_id'])->count();
    	 //如果用户没有填写过地址，或者是设置了当前地址为默认
    	if(!$address_count || $data['is_default'])
    	{
    		self::address_default($data['member_id'], $res['address_id']);
    	}
    	return $res;
    }

    //设默认地址
    static public function address_default($member_id, $address_id) {
    	$r = false;
    	$res = M('address')->where('member_id = '.$member_id)->save(array('is_default'=>0));
		if($res){
    		$r = M('address')->where('address_id = '.$address_id)->save(array('is_default'=>1));
		}
		return $r;
    }
	
	static public function getMyCountAddress($condition) {
		 return D('address')->where($condition)->count();
	}

    //用户收货地址列表
    static public function getAddressList($condition = array(), $fields = '*', $group = '',$orderby = 'is_default',$desc ='desc') {
        $data =  D('address')->where($condition)->field($fields)->group($group)->order($orderby.' '.$desc)->select();
		foreach($data as $key=>$v) {
			$data[$key]['area_info'] = preg_replace("/\s+/i","",$v['area_info']);
		}
		return $data ? $data : array();
    }

    //单条收货地址
    static public  function  getAddressInfo($condition = array(), $fields = '*'){
		return D('address')->where($condition)->field($fields)->find();
	}

	//删除收货地址
	static public function delAddress($address_id) {
		
		$res = M("address")->delete($address_id);
		if(!$res) {
		    return false;
		}
		return true;
	}

//获取地区列表
	static public function getAreaList($condition = array(), $fields = '*') {
		return D('area')->where($condition)->field($fields)->select();
	}

	/*
	** 今日是否签到
	** return  true(未签到)  false(已签到)
	*/
	static public function is_sign_in_today($member_id) {
		$t = strtotime(date('Y-m-d'));
		$where = array(
			'pl_memberid' =>$member_id, 
			'pl_stage' => 'sign_in',
			'pl_addtime' => array('egt',$t)
		);
		$count = D('points_log')->where($where)->count();

    	if($count) {
    		return false;
    	}
    	return true;
	}

	//首次登陆送签到积分
	static public function sign_in($member_id) {
		
    	if(self::is_sign_in_today($member_id)) {
    		$info = D('member')->where('member_id = '.$member_id)->field('member_username')->find();
    		$insert_arr['pl_memberid'] = $member_id;
			$insert_arr['pl_membername'] = $info['member_username'];
			$result  = \Common\Helper\PointsHelper::addPoints($insert_arr,'sign_in');
			return $result;
    	}
    	return false;
	}
	
	static public function getAvatars(array $uids ) {
		$data = array();
		if(empty($uids)) return $data;
		$getids = implode(',', $uids);
		$where['member_id'] =  array ('in',$getids);
   		$info = D('member')->where($where)->field('member_id,member_avatar')->select();
		$avatar_img = IndexService::getSetting('shop_member');
		foreach( $info as $k => $v ) {
			$data[$v['member_id']] = $v['member_avatar'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['member_avatar'] : $avatar_img;
		}
		unset($info,$avatar_img);
		return $data;
	}
}