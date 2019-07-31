<?php
namespace Common\Service;
class FeedBackService {
	static function addFeedBack(array $messageinfo) {
		$m = M('feedback');
		$data = array(
			'member_id'=>$messageinfo['member_id'] ? $messageinfo['member_id'] : '',
			'member_name'=>$messageinfo['member_name'] ? $messageinfo['member_name'] : '',
			'member_phone'=>$messageinfo['phone'],
			'ftime'=>TIMESTAMP,
			'content'=>$messageinfo['message'],
		);
		return $feedbackResult = $m->add($data);
	}
}