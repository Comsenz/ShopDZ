<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\FeedBackService;
use Common\Service\MemberService;
class FeedBackController extends BaseController {

	function getMember() {
		$key = I('param.key');
		if(empty($key))
			return;
        $user_token_info = MemberService::getUserTokenInfoByToken($key);

        if(!empty($user_token_info)) {
           $this->member_info = $user_token_info;
        }
	}
	 public function feedback() {
		$message = I('param.message');
		$phone = I('param.phone');
		if(empty($message) || empty($phone)) {
			$this->returnJson(1,'请你填写反馈意见和联系电话');
		}
		if(!preg_match("/^1[34578]{1}\d{9}$/",$phone)) {
			$this->returnJson(1,'请你填写正确的联系电话');
		}
		$this->getMember();
		$member_info = MemberService::getMemberInfo(array('member_id' => $this->member_info['member_id']));
		if(!empty($member_info)){
			$content['member_id'] = $member_info['member_id'];
			$content['member_name'] = $member_info['member_username'];
		}
		 $content['message'] = $this->cutstr($message,500,'');
		 $content['phone'] = $phone;
		 $result = FeedBackService::addFeedBack($content);
		if($result)
			$this->returnJson(0,'success');
		else
			$this->returnJson(1,'请重试');
	 }
}