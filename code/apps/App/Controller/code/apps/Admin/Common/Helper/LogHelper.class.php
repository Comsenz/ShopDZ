<?php
namespace Common\Helper;

class LogHelper{
	  
     public static function  adminLog(array $content ){
        if (!C('SYS_LOG'))
			return ;
			
		if(empty($content['content'])) {
			E('缺少content 参数');
		}
		$adminInfo = get_admin_info();
		$username = $content['username'] ? $content['username'] : $adminInfo['username'];
		$uid = $content['uid'] ? $content['uid'] : $adminInfo['uid'];
        $data = array();
        $state = !$content['state'] ? '' : 'fail';
        $data['action']    = $content['action'] ? $content['action'] : $content['content'];
        $data['content']    = $content['content'].$state;
        $data['username'] = $username;
        $data['createtime'] = TIMESTAMP;
        $data['uid']   = $uid;
        $data['ip']         = get_client_ip();
        $data['url']        =  CONTROLLER_NAME."/".ACTION_NAME;
        $return = M('AdminLog')->add($data);
		return $return;
    }
}