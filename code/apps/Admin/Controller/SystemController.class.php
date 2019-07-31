<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\AccessModel;
class SystemController extends BaseController {
	private $prepage = 200;
	//管理员列表
	public function lists(){
		$admin = M('admin');
		$where['uid'] = array('neq',1);
		$count = $admin->where($where)->count();
		$page  =  new \Common\Helper\PageHelper($count,20);
		$lists = $admin->where($where)->limit($page->firstRow.','.$page->listRows)->order('dateline desc')->select();
		$role = $this->_getRole();
		foreach($role as $key=>$v) {
			$roleAll[$v['id']] = $v;
		}
		unset($role);
		$this->assign('lists',$lists);
		$this->assign('roleAll',$roleAll);
		$this->assign('page',$page->show());
		$this->display('memberlist');
    }
	
	private function _getRole() {
		$role = M('rbacRole');
		$roleAll = $role->select(); 
		return $roleAll;
	}
    //个人信息修改
	public function info() {
		if($this->checksubmit()){
			$post['password'] = I("post.password");
			$post['avatar_image'] = I("post.avatar_image");
			$newpassword = I("post.newpassword");
			$confirm_newpassword = I("post.confirm_newpassword");
			if($newpassword != $confirm_newpassword){
				$this->showmessage('error','确认密码不对');
			}
			$name = $this->admin_user['username'];
			$condition['username'] = trim($name);
			$m = M('Admin');
			$result=$m->where($condition)->find();
			$salt = $result['salt'];
			$passwordmd5 = md5($post['password']);
			$password =  \Common\Helper\LoginHelper::passwordMd5($passwordmd5,$salt);
			if($result['password'] != $password) {
				$this->showmessage('error','原始密码不对');
			}
			$post['password'] = \Common\Helper\LoginHelper::passwordMd5(md5($newpassword),$salt);
			$post['uid'] = $this->admin_user['uid'];
			$return = $m->save($post);

			if($return !== false) {
				\Common\Helper\LogHelper::adminLog(array('content'=>('修改密码'),'action'=>'权限-修改密码','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
				 $this->showmessage('success',L('operation_success'));
			 }
			$this->showmessage('error',L('operation_fail'));
		}else{
			$this->display('info');
		}
	}
	
	public function add(){
		$admin = M('admin');
		$id = I("param.id");
		if($this->checksubmit()){
			$checkmust = array(
				'username'=>'账号不能为空',
				'password'=>'密码不能为空',
				'confirm_password'=>'确认密码不能为空或不对',
				'groupid'=>'权限组不能为空',
			);
			$post['username'] = I("post.username");
			$post['password'] = I("post.password");
			$post['confirm_password'] = I("post.confirm_password");
			$post['groupid'] = I("post.groupid");
			foreach( $checkmust as $key => $v ) {
				if(!empty($id)){
					if($post['password'] && $key == 'confirm_password' && $post['password'] != $post[$key]) {
						$this->showmessage('error',$checkmust[$key]);
					}
				} else {
					if(empty( $post[$key] )) {
						$this->showmessage('error',$checkmust[$key]);
					}
					if($key == 'confirm_password' && $post['password'] != $post[$key]) {
						$this->showmessage('error',$checkmust[$key]);
					}
				}
			}
			unset($post['confirm_password']);
			$condition['username'] = trim($post['username']);
			$data = $admin->where($condition)->find();
			if(empty($id)) {
				if($data) {
					$this->showmessage('error',L('member_exist'));
				}
				$post['salt'] = \Common\Helper\LoginHelper::random(6);
				$post['password'] = \Common\Helper\LoginHelper::passwordMd5(md5($post['password']),$post['salt']);
				$post['dateline'] = time();
				$post['statues'] = 'off';
				$result = $admin->add($post);
			} else {
				unset($post['username']);
				if(!empty($post['password'])){
					$passpord = \Common\Helper\LoginHelper::passwordMd5(md5($post['password']),$data['salt']) ;
					$post['password'] = $post['password'] ? $passpord : $data['password'];
				} else{
					unset($post['password']);
				}
				$post['uid'] = $data['uid'];
				$post['statues'] = I('post.statues');
				$result=$admin->save($post);
				$result = true;
			}
            if($result){
                $this->showmessage('success',L('operation_success'),U("System/lists"));
            }else{
                $this->showmessage('error',L('operation_fail'),U("System/lists"));
            }
		}
		if(!empty($id)){
			$info = $admin->find($id);
			if(empty($info)) {
				$this->showmessage('error',L('member_no_exist'));
			}
			$url = SITE_URL .'api.php/BindAdmin/bingAdmin/user/'.$id;
			$ewimg = $this->createQrCode($url);
			$ewimg = explode('Attach/',$ewimg);
			$this->assign('info',$info);
			$this->assign('ewimg',C('TMPL_PARSE_STRING.__ATTACH_HOST__').$ewimg[1]);
		}
		$role = $this->_getRole();
		$this->assign('role',$role);
		if($id){
			$this->assign('type','edit');
		}else{
			$this->assign('type','add');
		}
		$this->display('addmember');
    }
	
	public function del() {
		$uid = I("param.uid");
		$admin = M('admin');
		$info = $admin->find($uid);
		if(!empty($info['isfounder'])) {
			$this->showmessage('error','该账号不能删除');
		}
		$return = $admin->where("uid=$uid")->delete();
		if($return) {
			$this->showmessage('success','删除成功');
		}else{
			$this->showmessage('error','删除失败，请刷新重试');
		}
	}
	//权限组展示
	public function addpermission() {
		  if($this->checksubmit()){
				$groupname = I('post.groupname');
				$nodes = I('post.checkname');
				$access = M('rbacAccess');
				$role = M('rbacRole');
				$roleid = $role->add(array('name'=>$groupname));
				if(empty($roleid)) {
					$this->showmessage('error',L('save_fail'));
				}
				$parents = array();
				$rbacSiblings=D('rbacSiblings');//获取该节点的兄弟节点
				foreach($nodes as $k => $nodeid) {
					list($parent,$nodeid) = explode('_',$nodeid);
					if(empty($parent) || empty($nodeid)) {
						continue;
					}
					$parents[$parent] = $roleid;
					$insert[] = array('node_id'=>$nodeid,'role_id'=>$roleid);
					$siblings = $rbacSiblings->where('lastid=%d',array($nodeid))->find();
					if($siblings){
						$silbling_arr = explode(',',$siblings['nextids']);
						foreach($silbling_arr as $silblingid) {
							$insert[]  = array('node_id'=>$silblingid,'role_id'=>$roleid);
						}
						//var_dump($insert);exit;
					}
				}
				foreach($parents as $parentk=>$parent) {
					$insert[] = array('node_id'=>$parentk,'role_id'=>$roleid);
				}
				/* foreach($nodes as $k => $nodeid) {
					$insert[$k]['node_id'] = $nodeid;
					$insert[$k]['role_id'] = $roleid;
				} */
				//$access->where('role_id='.$roleid)->delete();
				$return = $access->addAll($insert);
			
				if($return){
				   $this->showmessage('success',L('save_success'),U("System/rolelist"));
				}else{
					$this->showmessage('error',L('save_fail'),U("System/rolelist"));
				}
        }else {
			$rbacNode = M('rbacNode');
			$data = $rbacNode->where('asmenu=1')->select();
			foreach($data as $k=>$v) {
				$newdata[$v['controller']][$v['name']] = $v;
			}
			unset($data);
			$this->assign('rbacnode',$newdata);
			$this->display('permission');
		}
	}
	
	public function rolelist() {
		$role = M('rbacRole');
		$admin = M('admin');
		$count = $role->count();
		$page  =  new \Common\Helper\PageHelper($count,20);
		$data_list = $role->limit($page->firstRow.','.$page->listRows)->select();
		foreach($data_list as $k => $v) {
			$data_list[$k]['count'] = $admin->where("groupid={$v['id']}")->count();
		}

        $this->assign('lists',$data_list);
		$this->assign('page',$page->show());
		$this->display('rolelist');
	}
	//添加权限组
	public function editpermission(){
		$id = I("param.id");
        if($this->checksubmit()){
			$role = M('rbacRole');
			$roleid = $role->where("id=$id")->select();
			if(empty($roleid)) {
				$this->showmessage('error','非法操作');
			}
            $groupname = I('post.groupname');
            $nodes = I('post.checkname');
			$access = M('rbacAccess');
			$role->where('id='.$id)->save(array('name'=>$groupname));
			$access->where('role_id='.$id)->delete();
			$parents = array();
			$rbacSiblings=D('rbacSiblings');//获取该节点的兄弟节点
			foreach($nodes as $k => $nodeid) {
				list($parent,$nodeid) = explode('_',$nodeid);
				if(empty($parent) || empty($nodeid)) {
					continue;
				}
				$parents[$parent] = $id;
				$insert[] = array('node_id'=>$nodeid,'role_id'=>$id);
				$siblings = $rbacSiblings->where('lastid=%d',array($nodeid))->find();
				if($siblings){
					$silbling_arr = explode(',',$siblings['nextids']);
					foreach($silbling_arr as $silblingid) {
						$insert[]  = array('node_id'=>$silblingid,'role_id'=>$id);
					}
					//var_dump($insert);exit;
				}
			}
			
			foreach($parents as $parentk=>$parent) {
				$insert[] = array('node_id'=>$parentk,'role_id'=>$id);
			}
			$return = $access->addAll($insert);
            if($return){
               $this->showmessage('success',L('save_success'),U("System/rolelist"));
            }else{
				$this->showmessage('error',L('save_fail'),U("System/rolelist"));
            }
        }else{
			$role = M('rbacRole');
			$access = new AccessModel();
			$roledata = $role->where("id=$id")->find();
			$nodes = $access->getAccessByRoleId($id);
			$node = array();
			foreach($nodes as $k => $v ) {
				foreach($v as $kk => $vv)
					$node[$vv['id']] = $vv;
			}
			unset($nodes);
			$rbacNode = M('rbacNode');
			$data = $rbacNode->where('asmenu=1')->select();
			foreach($data as $k=>$v) {
				$newdata[$v['controller']][$v['name']] = $v;
			}
			unset($data);
			$this->assign('role',$roledata);
			$this->assign('nodelist',$node);
			$this->assign('rbacnode',$newdata);
			$this->assign('menu_name','编辑角色');
            $this->display('editpermission');
        }
    }
	
	public function delrole() {
		$id = I("param.id");
		if(empty($id))
			$this->showmessage('error','删除失败，请刷新重试');
		$admin = M('admin');
		$count = $admin->where("groupid={$id}")->count();
		if(!empty($count))
			$this->showmessage('error','该权限组下用户不为空，不能删除');
		$role = M('rbacRole');
		$return = $role->where("id=$id")->delete();
		if($return) {
			$this->showmessage('success','删除成功');
		}else{
			$this->showmessage('error','删除失败，请刷新重试');
		}
	}
     //log
	public function log(){
		$adminLog = M('AdminLog');
		if(IS_AJAX) {
			 $type = I("get.type");
			if($type == 'createtime') {
				$time = strtotime('-6 months');
				$adminLog->where("createtime<=$time")->delete();
			}else{
				 $ids = I("param.ids");
				 if(empty($ids)) {
					$this->showmessage('error','删除失败，请刷新重试');
				 }
				$ids = is_array($ids) ? $ids : array($ids);
				$ids = implode(",",$ids);
				$adminLog->delete($ids);
			}
			$this->showmessage('success','删除成功');
		}else{
			
			$type_array = array(
				'username' =>'username',
				'action' =>'action',
				'ip' =>'ip',
			);
			$where = array();
			I("get.start") && $where['createtime'] = array('EGT',$start = strtotime(I("get.start")));
			 I("get.end") && $where['createtime'] = array('LT',$end = strtotime(I("get.end")));
			$start && $end && $where['createtime'] = array('between',"$start,$end");
			$type = I("get.type");
			$type = $type_array[$type];
			$search_text = I("get.search_text");
			if(!empty($type) && !empty($search_text)) {
				$where[$type] = array('like',"%$search_text%");
			}
			$count = $adminLog->where($where)->count();
			$page  = new \Common\Helper\PageHelper($count);
			$lists = $adminLog->where($where)->limit($page->firstRow.','.$page->listRows)->order('createtime desc')->select();
 
			$this->assign('page',$page->show());
			$this->assign('lists',$lists);
			$this->display('log');
		}
    }


}