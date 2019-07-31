<?php
namespace Common\Model;
use Think\Model;
class SpreadOrderCronModel extends Model {
	public function addCron($uid,$data,$model=null) {
		
		if(empty($data) || empty($uid)) return false;
		$model = new model();
		$member =$memberlist = $model->table(C('DB_PREFIX').'member') -> where("member_id=$uid") -> find();
		if($member['fromid'] &&($member['fromid'] !=$uid) ) {//存在但不是自己
			
			$list = $model->table(C('DB_PREFIX').'spread_order_cron')->add($data);
			// $this->WriteLog(var_export($list,true));
			// return  $model->table(C('DB_PREFIX').'spread_order_cron')->add($data);
		}
		// $this->WriteLog(var_export(777777777,true));
		return false;
	} 
	
	/**
     * 写日志
     */
    public function WriteLog($text) {
        $month = date('Y-m');
        file_put_contents ( APP_ROOT."/data/Logs/admin_pay/".$month.".txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
    }
	public function getList($where = array() , $limit = 50) {
		$model = new model();
		return $model->table(C('DB_PREFIX').'spread_order_cron') ->where($where)->order('order_add_time asc')->limit($limit)->select();
	}
	
	public function getPresalesList($where = array() , $limit = 50) {
		$model = new model();
		$list = $data = array();
		$list =  $model->table(C('DB_PREFIX').'spread') ->where($where)->order('order_add_time asc')->limit($limit)->select();
	/* 	foreach($list as $k =>$v ) {
			$data[$v['order_id']] = $v;
		} */
		return $list;
	}
	
	public function upPresaleState($where,$cron_state) {
		if(empty($where)) return false;
		$model = new model();
		$cron_state['cron_time'] = TIMESTAMP;
		return $model->table(C('DB_PREFIX').'spread')->where($where)->save($cron_state);
	}
	
	
	
	public function upState($id,$cron_state) {
		$data['id'] = $id;
		$data['cron_state'] = $cron_state;
		$data['cron_time'] = TIMESTAMP;
		return $this->save($data);
	}
}
?>