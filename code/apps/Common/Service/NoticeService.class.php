<?php
namespace Common\Service;
use Think\Model;
use \Common\Model\SmsModel;
class NoticeService {
	public function count_notice($member_id=false,$from_id=false,$status=0,$type=false){
		$sms_model = new SmsModel();
		$count = $sms_model->count_notice($member_id,$from_id,$status,$type);
		return $count;
	}

	public function get_notice($member_id=false,$from_id=false,$status=0,$type=3,$page = 1,$prepage = 20){
		$sms_model = new SmsModel();
		$list = $sms_model->get_notice($member_id,$from_id,$status,$type,$page,$prepage);
		return $list;
	}

	public function update_notice_by_id($up,$update,$member_id){
		if(empty($up) || empty($update)){
			return true;
		}
		$where = array();
		$where['sid'] = array('in',$up);
		$where['to_id'] = $member_id;
		$sms_model = new SmsModel();
		$result = $sms_model->update_notice($where,$update);
		return $result;
	}

	public function del_notice($where){
		if(empty($where)){
			return false;
		}
		$sms_model = new SmsModel();
		$result = $sms_model->del_notice($where);
		return $result;
	}

	
	public function send_template_notice($member_id,$type,$content){
        if(!empty($member_id) && !empty($type)){
            $temp = M('message')->where(array('code'=>$type))->find();
        	$member_info = M('member')->where(array('member_id'=>$member_id))->field('member_id,member_mobile')->find();
        	if($temp && !empty($member_info)){
        		$data = array();
	            $data['to_id'] = $member_info['member_id'];
	            $data['to_name'] = $member_info['member_mobile'];
	            $data['title'] = $temp['web_title'];
	            $data['message'] = $content;
	            $data['from_id'] = 0;
	            $data['from_name'] = 'system';
	            $data['type'] = 1;
	            $data['status'] = 0;
	            $data['del_type'] = 0;
	            $data['dateline'] = time();
	            $data['readtime'] = 0;
	            $data['parent_sid'] = 0;
	            $data['message_ismore'] = 0;
	            M('sms')->add($data);
        	}
            
        }


	}
}



?>