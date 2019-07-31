<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Service\MemberService;
class MemberController extends BaseController {
	public function lists(){

        $con = array();
        $type = I("get.type");
        $search_text = I("get.search_text");
        $time_start = I("get.time_start");
        $time_end = I("get.time_end");
        if(!empty($search_text)) {
            $con[$type] = array('like',"%$search_text%");
            $this -> assign("search_text",$search_text);
        }

        if(!empty($time_start) || !empty($time_end)) {
            $this -> assign("time_start",$time_start);
            $this -> assign("time_end",$time_end);
	        $time_start = empty($time_start)?time():strtotime($time_start.' 00:00:00');
	        $time_end = empty($time_end)?time():strtotime($time_end.' 23:59:59');
	        $con['member_time']  = array('BETWEEN',array($time_start,$time_end));
        }

        if(I('get.export')) {

        	$memberlist = M("member")->field('member_mobile,member_truename,member_points') -> where($con) -> order('member_id DESC')  ->select();
        	$obj  = new \Common\Helper\ExcelHelper();
	    	$title = array(L('member_name'),L('nick_name'), L('points'));
	        $obj->exportExcel($title, $memberlist,L('member_list'));
        }

        $count = M("member") -> where($con) -> count();
        $page  = new \Common\Helper\PageHelper($count,20);
        $this->assign('page',$page->show());

        $memberlist = M("member") -> where($con) -> limit($page->firstRow.','.$page->listRows) -> order('member_id DESC') ->select();
        $this -> assign("memberlist",$memberlist);

		$member_state_arr = $this->getDataDictionary('member_state');
		$this->assign('state_arr', $member_state_arr);
		$this->display('member_list');
    }
     
	public function add(){
		$model = D('member');
		$id = I("get.id");
		if($this->checksubmit()){
			$checkmust = array(
				'member_username'=> L('member_name_null'),
				'member_passwd'=> L('member_psw_null'),
				'confirm_password'=> L('member_psw_2_null'),
			);
			$post['member_username'] = I("post.member_username");
			$post['member_mobile'] = I("post.member_mobile");
			$post['member_passwd'] = I("post.member_passwd");
			$post['confirm_password'] = I("post.confirm_password");
			//$post['groupid'] = I("post.groupid");
			$id = I("post.member_id");
			$memberinfo = $model ->getMemberByUid($id);
		
			foreach( $checkmust as $key => $v ) {
				if(!empty($memberinfo)){
					if($post['member_passwd'] && $key == 'confirm_password' && $post['member_passwd'] != $post[$key]) {
						$this->showmessage('error',$checkmust[$key]);
					}
				} else {
					if(empty( $post[$key] )) {
						$this->showmessage('error',$checkmust[$key]);
					}
					if($key == 'confirm_password' && $post['member_passwd'] != $post[$key]) {
						$this->showmessage('error',$checkmust[$key]);
					}
				}
			}
			
			$post['member_state'] = I("post.member_state")=='on'?1:0;
			$post['member_truename'] = I("post.member_truename");
			$post['member_avatar'] = I("post.member_avatar");
			$post['member_sex'] = I("post.member_sex");
            if($post['member_sex'] ==''){
                $post['member_sex'] = 0;
            }
			if(empty($memberinfo)) {
				if($post['member_mobile']){
					$condition['member_mobile'] = trim($post['member_mobile']);
					$data = $model->where($condition)->find();
					if($data) {
						$this->showmessage('error', L('member_mobile_exist'));
					}
				}
				$result = $model->addmember($post);
			} else {
				$condition['member_mobile'] = trim($post['member_mobile']);
				$condition['member_id'] = array('neq',$memberinfo['member_id']);
				$data = $model->where($condition)->find();
				if($data) {
					$this->showmessage('error', L('member_mobile_exist'));
				}
				unset($post['member_username']);
				if(empty($post['member_passwd'])){
					unset($post['member_passwd']);
				}
				$post['member_id'] = $memberinfo['member_id'];
				$result=$model->savemember($post);
				$result = $memberinfo['member_id'];
			}
            if($result['member_id']){
                $this->showmessage('success', L('operation_success'), U('member/lists'));
            }else{
                $this->showmessage('error', L('operation_fail'));
            }
		}
		//编辑
		if(!empty($id)){
			$info = $model->find($id);
			if(empty($info)) {
				$this->showmessage('error', L('member_no_exist'));
			}
			$this->assign('info',$info);
		}else{
			$name = \Common\Helper\LoginHelper::random(9,1);
			$user_name = 'am'.$name;
			$member = array('member_username'=> $user_name);
			$this->assign('info',$member);
		}
		$dataDisction = $this->getDataDictionary();
		$this->assign('dataDisction', $dataDisction);
		$this->display('member_add');
    }


    public function points(){
    
        $con = array();
        //搜索
        $type = I("get.type");
        $search_text = I("get.search_text");
        $member_id = I("get.member_id");
        $time_start = I("get.time_start");
        $time_end = I("get.time_end");
        if(!empty($search_text)) {
        	if($type == 'pl_stage') {//根据行为搜索
				$type = 'pl_desc';
        	}
            $con[$type] = array('like',"%$search_text%");
            $this -> assign("search_text",$search_text);
        }
        if(!empty($time_start) || !empty($time_end)) {
            $this -> assign("time_start",$time_start);
            $this -> assign("time_end",$time_end);
	        $time_start = empty($time_start)?time():strtotime($time_start.' 00:00:00');
	        $time_end = empty($time_end)?time():strtotime($time_end.' 23:59:59');
	        $con['pl_addtime']  = array('BETWEEN',array($time_start,$time_end));
        }
        if($member_id) {$con['pl_memberid'] = $member_id;}
		$count_data = M("points_log")->where($con)->field(array("count(*)"=>"count", "max(pl_addtime)"=>'pl_addtime'))->order('pl_addtime') -> select();
		$count = $count_data['count'];
        $page  = new \Common\Helper\PageHelper($count,20);
        $this->assign('page',$page->show());
        $points_log_list = M("points_log") -> where($con)->order('pl_addtime DESC') -> limit($page->firstRow.','.$page->listRows) ->select();
        $this -> assign("points_log_list",$points_log_list);
		$points_stage = $this->getDataDictionary('points_stage');
		$this->assign('points_stage', $points_stage);
    	$this->display('points_log');
    }
    public function points_add(){
    	if($this->checksubmit()){
    		//查询会员信息
            $obj_member = D('member');
            $pl_membername =  I('post.pl_membername','');
            $operatetype = I('post.operatetype');
            if(empty($pl_membername)) {
            	$this->showmessage('error', L('member_name_null'));
            }
            $pointsnum =  abs(I('post.pl_points',0,'intval'));
            if($pointsnum <= 0) {
            	$this->showmessage('error', L('points_greate_ran_0'));
            } 
			$where_search = array('member_username' => $pl_membername);
			$member_info = $obj_member->getMemberInfo($where_search);
            if (!is_array($member_info) || count($member_info)<=0){
                $this->showmessage('error', L('member_no_exist'));
            }

            if ($operatetype == 2 && $pointsnum > intval($member_info['member_points'])){
                $this->showmessage('error', L('points_insufficient', array('points' => $member_info['member_points'])));

            }
            $insert_arr['pl_memberid'] = $member_info['member_id'];
            $insert_arr['pl_membername'] = $member_info['member_username'];
            $admininfo = get_admin_info();
            $insert_arr['pl_adminid'] = $admininfo['uid'];
            $insert_arr['pl_adminname'] = $admininfo['username'];
            $pl_desc = trim(I('post.pl_desc'));
            if(mb_strlen($pl_desc,"utf-8") > 100){
                $pl_desc = mb_substr($pl_desc,0,100,"utf-8");
            }
            $insert_arr['pl_desc'] = $pl_desc; 
            if ($operatetype == 2){
                $insert_arr['pl_points'] = -$pointsnum;
            }else {
                $insert_arr['pl_points'] = $pointsnum;
            }
          	$result  = \Common\Helper\PointsHelper::addPoints($insert_arr);
            if ($result){
            	\Common\Helper\LogHelper::adminLog(array('content'=>'积分增减：'.$member_info['member_username'].'['.(($operatetype == 2)?'':'+').strval($insert_arr['pl_points']).']'));
            	$this->showmessage('success', L('save_success'));
            }else {
                $this->showmessage('error', L('save_fail'));
            }
    	} else {
    		$this->display('points_add');
    	}
    }
    public function points_setting(){
    	$model = D('setting');
        if(I("post.subval")){
            $post = I("post.");
            unset($post['sub']);
            unserizepost($post);
            $model -> Settings($post);

			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($post,true),'action'=>'会员积分规则设置'));
            $this->showmessage('success', L('save_success'));
        }
        //获取配置
         $config = $model -> getSettings();
        $this -> assign("config",$config);
    	$this->display('points_setting');
    }
    
    //会员模块数据字典
	public function getDataDictionary($value = ''){
		$result  = \Common\Helper\PointsHelper::getDataDictionary($value);       
        return $result;
    }

    public function address_list(){
    	$id = I("get.member_id");
    	$info = M('member')->find($id);
		if(empty($info)) {
			$this->showmessage('error', L('member_no_exist'));
		}
		$this->assign('info',$info);
    	$address = M('address')->where('member_id = '.$id) -> select();
		$this->assign('address_list',$address);
		$this->display('member_address_list');
    }
    //弹出添加和编辑收货地址窗体
    public function addressShow(){
    	
    	$member_id =  I('get.member_id',0,'intval');
    	$address_id =  I('address_id',0,'intval');
    	if($address_id) {//编辑
    		$info = MemberService::getAddressInfo(array('address_id' => $address_id));
    		$this->assign('info',$info);
    	}
    	$this->assign('member_id',$member_id);
    	$this->display('member_address');
	
    }

    //添加编辑收货地址
    public function member_address() {
    	$data = I("post.");
    	$info = M("area")->field('area_parent_id')->find($data['area_id']);
    	$info1 = M("area")->field('area_parent_id')->find($info['area_parent_id']);
    	$data['city_id'] = $info['area_parent_id'];
    	$data['province_id'] = $info1['area_parent_id'];
    	if($data['is_default'] == 'on') {$data['is_default'] = 1;}

    	//添加编辑收货地址
	    $res = MemberService::saveAddress($data);
    	if(!$res['address_id']){
            $this->error($res['error']);
        }

        $this->showmessage('success', L('save_success'),U('member/address_list',array('member_id' => $data['member_id'])));

    }

    public function set_default() {
    	$member_id = I("post.member_id");
    	$address_id = I("post.address_id");

    	$res = MemberService::address_default($member_id, $address_id);
    	$data['status'] = 1;
        if(!$res) {
            $data['status'] = 0;
            $data['info'] = L('data_fail_opration');
        }
        $this->ajaxReturn($data);
    }

    public function address_del() {
      
        $address_id = I('address_id',0,'intval');
	    $info = MemberService::getAddressInfo(array('address_id' => $address_id));
	    if(!count($info)) {
            $data['info'] = L('data_no_exist');
            $this->ajaxReturn($data);
	    }
	    if($info['is_default']) {
            $data['info'] = '默认地址不能进行删除操作';
            $this->ajaxReturn($data);
	    }
        $res = MemberService::delAddress($address_id);
        if(!$res) {
            $data['info'] = L('data_fail_opration');
        	$this->ajaxReturn($data);
        }
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }
//上传头像
     public function upload_avatar(){
        $post = array();
        if($_FILES){
            $file = uploadfile($_FILES, 'Avatar');
			
            if($file['file']){
                $this -> ajaxReturn(array('status'=>1,'data'=>$file['file']),'json');
            }
        }
    }
	
	public function notice() {
		 $this->redirect('Notice/template');
	}

}