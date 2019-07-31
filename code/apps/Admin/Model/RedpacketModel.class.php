<?php
/**
 * coding by: yangyong
 * date: 2016/05/13
 */
namespace Admin\Model;
use Think\Model;
class RedpacketModel extends Model{
    protected $tableName = "redpacket";

    //发放优惠券
    public function give_redpacket($member,$temp){
    	$code = $this->get_rpt_code($member['member_id']);
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
    			'rpacket_owner_name'	=> $member['member_mobile'],
                'rpacket_color'       => $temp['rpacket_t_color'],
    		);
        $temp = M('redpacket_template')->where(array('rpacket_t_id' => $temp['rpacket_t_id']))->setInc('rpacket_t_giveout');
    	$result = M($this->tableName)->add($data);
    	return $result;
    }

    //生成code
    public function get_rpt_code($member_id = 0){
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

    public function get_eachlimit($member_id,$temp_id){
    	$where = array(
    			'rpacket_owner_id'	=> $member_id,
    			'rpacket_t_id'		=> $temp_id,
    		);
    	$num = M($this->tableName)->where($where)->count();
    	return $num;
    }


}