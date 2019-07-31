<?php
namespace App\Controller;
use Think\Controller;
use Common\Service\NoticeService;

class NoticeController extends BaseController {
	public function __construct() {
		parent::__construct();
		$this->getMember();
	}

	//获取未读站内信
	public function only_notice_count(){
		$count = NoticeService::count_notice($this->member_info['member_id']);
		if($count > 99){
			$count = '99+';
		}
		$data['count'] = $count;
		$this->returnJson(0,'success',$data);
	}

	public function lists(){
		$type = I('request.type',3,'intval');
		$status = I('request.status',false,'intval');
		$page = I('request.page',1,'intval');
		$limit = 20;
		$list = NoticeService::get_notice($this->member_info['member_id'],false,$status,$type,$page,$limit);
		$up = array();
		foreach($list as $k => $v){
			if($v['status'] == 0){
				$up[] = $v['sid'];
			}
			$list[$k]['message'] = htmlspecialchars_decode($v['message']);
			$list[$k]['dateline'] = date('Y-m-d H:i:s',$v['dateline']);
		}
		$update = array(
				'status' => 1,
				'readtime' => time(),
			);
		NoticeService::update_notice_by_id($up,$update,$this->member_info['member_id']);
		if(empty($list)){
			$data['list'] = array();
		}else{
			$data['list'] = $list;
		}
		$this->returnJson(0,'success',$data);
	}

	public function del_notice(){
		$id = I('request.id',0);
		if(empty($id))
			$this->returnJson(1,'参数错误！');
		$where = array();
		$where['to_id'] = $this->member_info['member_id'];
		if($id != 'all'){
			$where['sid'] = intval($id);
		}
		$result = NoticeService::del_notice($where);
		if(!$result){
			$this->returnJson(1,'参数错误！');
		}
		$this->returnJson(0,'删除成功！');
	}


}
?>