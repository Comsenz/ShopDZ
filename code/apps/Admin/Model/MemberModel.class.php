<?php
/**
 * this file is not freeware
 * User: juan
 * DATE: 2016/6/15
 */
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model{


    /**
     * 注册
     */
    public function addmember($member_info) {
		unset($member_info['confirm_password']);
        $member_info['member_time']   = time();
    	$member_info['salt'] = \Common\Helper\LoginHelper::random(6);
        $passwordmd5 = preg_match('/^\w{32}$/', $member_info['member_passwd']) ? $member_info['member_passwd'] : md5($member_info['member_passwd']);
    	$member_info['member_passwd'] = \Common\Helper\LoginHelper::passwordMd5($passwordmd5,$member_info['salt']);
    	$member_info['member_login_time'] = TIMESTAMP;
        $member_info['member_old_login_time'] = TIMESTAMP;
		$model = new Model();
		$model->master(true);
		$model->startTrans();
        $insert_id  = $model->table(C('DB_PREFIX').'member')->add($member_info);
		if($insert_id ){
			$spread_account = array(
				'member_uid'=>$insert_id,
				'member_name'=>'',
				'all_price'=>0,
				'settlement_price'=>0,
				'no_settlement_price'=>0,
				'frozen_price'=>0,
				'qrcode'=>'',
				'add_time'=>TIMESTAMP,
			);
			$spread_account_id  = $model->table(C('DB_PREFIX').'spread_account')->add($spread_account);
			if($spread_account_id) {
				$model->commit();
			}else{
				$model->rollback();
				$insert_id = 0;
			}
		}else{
			$insert_id = 0;
			$model->rollback();
		}
        return $insert_id;
    }
    public function wxaddmember($member_info) {

        $member_info['member_time']   = TIMESTAMP;
        $member_info['salt'] = \Common\Helper\LoginHelper::random(6);
        $member_info['member_passwd'] = NULL;
        $member_info['member_login_time'] = TIMESTAMP;
        $member_info['member_old_login_time'] = TIMESTAMP;
		$model = new Model();
		$model->master(true);
		$model->startTrans();
        $insert_id  = $model->table(C('DB_PREFIX').'member')->add($member_info);
		if($insert_id){
			$spread_account = array(
				'member_uid'=>$insert_id,
				'member_name'=>'',
				'all_price'=>0,
				'settlement_price'=>0,
				'no_settlement_price'=>0,
				'frozen_price'=>0,
				'qrcode'=>'',
				'add_time'=>TIMESTAMP,
			);
			$spread_account_id  = $model->table(C('DB_PREFIX').'spread_account')->add($spread_account);
			if($spread_account_id) {
				$model->commit();
			}else{
				$model->rollback();
				$insert_id = 0;
			}
		}else{
			$insert_id = 0;
			$model->rollback();
		}
        return $insert_id;
    }
//编辑
    public function savemember($member_info) {
      	if(!empty($member_info['member_passwd'])){
        	$member_info['salt'] = \Common\Helper\LoginHelper::random(6);
			$member_info['member_passwd'] = \Common\Helper\LoginHelper::passwordMd5(md5($member_info['member_passwd']),$member_info['salt']);

		}
		return $this->save($member_info);
    }
	
	public function getMemberByUid($uid) {
		if(empty($uid)) return array();
		return   $this -> where("member_id=$uid") -> find();
	}

	public  function  getMemberInfo($condition = array(), $fields = '*'){;
		$member_info = $this->where($condition)->field($fields)->find();
		return $member_info;
	}
}