<?php
namespace Common\Helper;

class MessageHelper{
    /**
     *  发送短消息给平台
     *  @param  $title : 短消息标题
     *          $message : 短消息内容
     *          $type：消息属性    0：系统提示（系统对系统）     1：系统提示（单）     2：会员间站内信($from_id、$from_name 必填)      3：系统消息(群发)
     *          $member_ids：接收人用户ID，如面向所有用户参数为空
     *          $identity : 发送人身份  1：系统    2：用户
     *          $parent_sid：回复短消息消息ID
     **/
    static function sendMessage($title="",$message,$type=0,$member_ids='',$parent_sid=0,$from_id=0,$from_name="") {
        $data = array();
        $identity = 2;
        if(in_array($type,array(0,1,3))){
            $identity = 1;
        }
        if(is_array($member_ids)){
            $data['mids'] = serialize($member_ids);
            $data['message_ismore'] = count($member_ids) == 1 ? 0 : 1;
        }elseif($member_ids == ''){
            $data['mids'] = array('admin');
            $data['message_ismore'] = 1;
        }
        $data['title'] = $title;
        $data['message'] = $message;
        $data['from_id'] = $identity == 1 ? 0 : $from_id;
        $data['from_name'] = $identity == 1 ? '' : $from_name;
        $data['dateline'] = TIMESTAMP;
        $data['type'] = $type;
        $data['cron_status'] = 0;
        $data['crontime'] = 0;
        $data['parent_sid'] = $parent_sid;
        $cron = M('sms_cron')->add($data);
        return $cron;
    }


    /**
     * 一对多消息已读
     *  @param  $sid : 消息ID
     *          $read_userid : 阅读用户ID
     */
    static function readMessage($sid,$read_userid){
    	$data = array();
    	$data['sid'] = $sid;
    	$data['read_userid'] = $read_userid;
    	$data['readtime'] = TIMESTAMP;
    	$result = M('sms_receipt')->add($read);
    	return $result;
    }

}