<?php
namespace Admin\Controller;
use Think\Controller;
class PermissionController extends BaseController {

    public function roleManage(){
        $role=M('rbac_role');
        $p=$_GET['p']?intval($_GET['p']):0;
        $data_list=$role->page($p.',10')->select();
        $count=$role->count();
        $this->page($count,10);
        $this->assign('data_list',$data_list);
        $this->assign(array('menu_name'=>'角色管理'));
        $this->display();
    }
    
    public function addRole(){
        if($this->checksubmit()){
            $id = intval($_POST['id']);
            $post = I('post.');
            $role = M('rbacRole');
            if(empty($id)){
                $result=$role->add($post);
            }else{
                $result=$role->save($post);
            }
            if($result){
			
                $this->success('保存成功');
            }else{
                $this->error('保存失败');
            }
        }else{
            
            $id = intval($_GET['id']);
            if(empty($id)){
                $this->assign('menu_name','添加角色');
            }else{
                $role = M('rbacRole');
                $result=$role->where(array('id'=>$id))->find();
                $this->assign($result);
                $this->assign('menu_name','编辑角色');
            }
            $this->display();
        }
    }

    public function changeStatus(){
        $type = intval($_GET['type']);
        $id = intval($_GET['id']);
        $role = M('rbac_role');
        $result = $role->where(array('id'=>$id))->save(array('status'=>$type));
        if($result){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }

    public function nodeManage(){
        $node=new \Common\Helper\ScanNodeHelper();
        $nodelist = $node->getAllNode();
        $rbacNode=D('rbacNode');
		$rbacSiblings=D('rbacSiblings');
        $data = $rbacNode->select();
		foreach($data as $k=>$v) {
			$sli = $rbacSiblings->where("lastid={$v['id']}")->find();
			$v['siblings'] = $sli['nextids'];
			$newdata[$v['controller']][$v['name']] = $v;
		}
		unset($data);
        $this->assign('rbacnode',$newdata);
        $this->assign('data_list',$nodelist);
        $this->assign(array('menu_name'=>'角色管理'));
        $this->display();
    }
		
	public function addmenu() {
		$id =$_POST['id'];
		$rbacNode=D('rbacNode');
		 $findinfo = $rbacNode->where('id='.$id)->getField('asmenu');
		 $asmenu = intval(!$findinfo);
		 $update = array(
						'asmenu'=>$asmenu,
				   );
		$return = $rbacNode->where('id='.$id)->save($update);
		if(!$return) {
			echo json_encode(array('code'=>-1,'msg'=>'错误'));
		}else{
			echo json_encode(array('code'=>0,'msg'=>'ok'));
		}
	}
	
	public function addcontroller () {
		$name = $_POST['name']; 
		$value = $_POST['_name']; 
		$id = $_POST['id']; 
		$rbacNode=D('rbacNode');
		if(empty($id)) {
			
			$insert = array(
				'name'=>$name,
				'name_cn'=>$value,
				'controller'=>$name,
				'level'=>1,
				'asmenu'=>1,
		   );
			$return = $rbacNode->add($insert);
		}else{
				$update = array(
						'name_cn'=>$value,
				   );
				  
				$return = $rbacNode->where('id='.$id)->save($update);
		}

		if(!$return) {
			echo json_encode(array('code'=>-1,'msg'=>'错误'));
		}else{
			echo json_encode(array('code'=>0,'msg'=>'ok','id'=>$return));
		}
	}
	public function addnode(){
		$data = $_POST['rbac']['news'];
		$edit = $_POST['rbac']['edit'];
		$siblings = $_POST['siblings'];
		$sort = $_POST['sort'];
		$classname = $_POST['rbac']['classname'];
		 $rbacNode=D('rbacNode');
		foreach($data as $controller =>$nodes) {
			foreach($nodes as $nodek =>$node) {
				   $insert = array(
						'name'=>$node,
						'name_cn'=>$_POST['rbac']['name'][$controller][$node],
						'controller'=>$controller,
						'level'=>3,
				   );
				
				$return = $rbacNode->add($insert);
			}
		}
		//顺序

		foreach($sort as $k=>$v) {
				$up_sort['sort'] =$v;
				$return = $rbacNode->where("id='$k'")->save($up_sort);
		}
		//class
	 
		foreach($classname as $kk=>$vv) {
				$up_classname['classname'] =$vv;
				$return = $rbacNode->where("name='$kk'")->save($up_classname);
		}
		
		foreach($edit as $key=>$val) {
			foreach($val as $vv=> $id) {
				$update = array(
						'name_cn'=>$_POST['rbac']['name'][$key][$id],
				   );
				 
				$return = $rbacNode->where('id='.$id)->save($update);
			}
		}
		$rbacSiblings=D('rbacSiblings');
		foreach ($siblings as $lastid =>$siblingid) {
			if($siblingid) {
				$result = $rbacSiblings->where("lastid={$lastid}")->find();
				if($result){
					$data = array('id'=>$result['id'],'lastid'=>$lastid,'nextids'=>$siblingid);
					$rbacSiblings->save($data);
				}else{
					$data = array('lastid'=>$lastid,'nextids'=>$siblingid);
					$rbacSiblings->add($data);
				}
			}
		}
		$this->success('操作成功');
	}
	
    public function casting(){
		 if (IS_AJAX){
			$names = $_POST['name'];
			$roleid = $_POST['id'];
			$adminRole = M('rbacAdminRole');
			foreach($names as $k=> $nameid) {
				$insert[$k]['admin_id'] =$nameid;
				$insert[$k]['role_id'] =$roleid;
			}
			$adminRole->where('role_id='.$roleid)->delete();
			$return = $adminRole->addAll($insert);
			if($return)
				$this->success('保存成功');
			else
				$this->success('操作失败');
		 }else{
			layout('pop');
			$id = I('get.id');
			$userModel = M('admin');
			$allusers = $userModel->select();
			$adminRole = M('rbacAdminRole');
			$users = $adminRole->where('role_id='.$id)->select();
			foreach($users as $k=>$v) {
				$data[$v['admin_id']][] = $v;
			}
			unset($users);
			$this->assign('id',$id);
			$this->assign('users',$data);
			$this->assign('allusers',$allusers);
			$this->display('linkuser');
		}
    }

    public function privileges(){
		 if (IS_AJAX){
			$nodes = $_POST['nodes'];
			$roleid = $_POST['id'];
			$access = M('rbacAccess');
			foreach($nodes as $k=> $nodeid) {
				$insert[$k]['node_id'] =$nodeid;
				$insert[$k]['role_id'] =$roleid;
			}
			$access->where('role_id='.$roleid)->delete();
			$return = $access->addAll($insert);
			if($return)
				$this->success('保存成功');
			else
				$this->success('操作失败');
			 }else{
			// layout('pop');
			$rbacNode=D('rbacNode');
			$id = I('get.id');
			$data = $rbacNode->order('level asc')->select();
			foreach($data as $k=>$v) {
				$newdata[$v['controller']][$v['name']] = $v;
			}
	//		var_dump($newdata);exit;
			$access = M('rbacAccess');
			$accesslist= $access->where("role_id=$id")->select();
			foreach($accesslist as $ak=> $access) {
				$list[$access['node_id']] = $access;
			}
			unset($data,$accesslist);
			$this->assign('id',$id);
			$this->assign('list',$list);
			$this->assign('allnodes',$newdata);
			$this->display('privileges');
		}
    }

}