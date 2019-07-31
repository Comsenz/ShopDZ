<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\AreaModel;
use Admin\Model\SettingModel;
use Common\Service\ExpressService;
/*
*快递设置
*
*
*/
class LogisticsController extends  BaseController {
	 
	// function companyList() {//物流公司设置
	// 	$company = M('companylist');
	// 	$logistics = C('logistics');
	// 	if(is_string($logistics)) {
	// 		include($logistics);
	// 		$list = $expresses;
	// 	}else{
	// 		E('加载物流公司配置失败');
	// 	}
	
	// 	if(IS_AJAX) {//添加
			
	// 		if($this->checksubmit()){
	// 			$option = I('get.option');
	// 			$code = I('post.code')  ? I('post.code') : (I('get.code') ? I('get.code'):0);
	// 			if(empty($code)) {
	// 				$this->showmessage('error','请选择快递公司');
	// 			}
	// 			$data = array();
	// 			$data = $company->where("code='%s'",array($code))->select();
	// 			$data = current($data);
	// 			if($option =='control') {//修改是否开启
	// 				$data['status'] = !$data['status'];
	// 				$data = $company->save($data);
	// 			}else{//添加物流公司
	// 				if(!empty($data)) {
	// 					$this->showmessage('error','已经添加过该快递公司');
	// 				}
	// 				foreach($list as $k=>$v) {
	// 					if( $k == $code ) {
	// 						$name = $v;
	// 						break;
	// 					}
	// 				}
	// 				$post['code'] = $code;
	// 				$post['name'] = $name;
	// 				$post['dateline'] = TIMESTAMP;
	// 				\Common\Helper\LogHelper::adminLog(array('content'=>var_export($post,true),'action'=>'物流-添加物流公司','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
	// 				$result = $company->add($post);
	// 				if($result)
	// 				 $this->showmessage('success','操作成功',U('Logistics/companyList'));
	// 			 }
	// 		}else {
	// 			layout(false);
	// 			$this->assign('list', $list);
	// 			$this->display('addcompany');
	// 		}
	// 	}else {//展示物流公司
	// 		$count = $company->count();
	// 		$page  = new \Common\Helper\PageHelper($count);
	// 		$list = $company->limit($page->firstRow.','.$page->listRows)->select();
	// 		$statusshow = array(0=>'开启',1=>'关闭');
	// 		$this->assign('statusshow', $statusshow);
	// 		$this->assign('lists', $list);
	// 		$this->assign('page', $page);
	// 		$this->display('companylist');
	// 	}
	// }

	function companyList() {
		$company = M('companylist');
		if($this->checksubmit()){
			$update_company = I('post.companys','','htmlspecialchars');
			if(!empty($update_company) && is_array($update_company)){
	            $ids=array_keys($update_company,'on',false);
	            $where['id'] = array('in',$ids);
	            $data['status'] = 1;
	            \Common\Helper\LogHelper::adminLog(array('content'=>var_export($ids,true),'action'=>'修改物流的开启状态','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
	            $aa = $company->where(true)->data(array('status' => 0))->save();
	            $result = $company->where($where)->data($data)->save();
	            if($result){
					$this->showmessage('success','操作成功',U('Logistics/query_setting'));
				 }else{
				 	$this->showmessage('error','操作失败',U('Logistics/query_setting'));
				 }
            }else{
            	$result = $company->where(true)->data(array('status' => 0))->save();
            	if($result){
					$this->showmessage('success','操作成功',U('Logistics/query_setting'));
				 }else{
				 	$this->showmessage('error','操作失败',U('Logistics/query_setting'));
				 }
            }

		}
		
		// $list = $company->order('id ASC')->select();
		// $this->assign('companylist', $list);
		// $this->display('query_setting');

	}
	
	function storageList() {
		$storage = M("storage");
		$count = $storage->count();
		$type_array = array(
			'name' =>'name',
			'person' =>'person',
			'telphone' =>'telphone',
		);
		$where = array();
		$type = I("get.type");
		$type = $type_array[$type];
		$search_text = I("get.search_text");
		if(!empty($type) && !empty($search_text)) {
			$where[$type] = array('like',"%$search_text%");
		}

		$AreaModel =new AreaModel();
		$arealist = $AreaModel ->getAreaList();
		foreach($arealist as $k=>$v) {
			$area[$v['area_id']] = $v['area_name'];
		}
		unset($arealist);
		$page  = new \Common\Helper\PageHelper($count);
		$list = $storage->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('area', $area);
		$this->assign('lists', $list);
		$this->assign('page', $page);
		$this->display('storagelist');
	}
	
	function setstorage() {
		$storage = M("storage");
		$id = I('get.id');
		if($this->checksubmit()){
			$AreaModel =new AreaModel();
			$data1 = $AreaModel->getTopAreaId(I('post.parent_id'));
			$data2 = $AreaModel->getTopAreaId($data1['area_parent_id']);
			$data3 = $AreaModel->getTopAreaId($data2['area_parent_id']);
			$area = array();
			if(!($data1 && $data2 && $data3)) {
				$this->showmessage('error','请选择所在所在地区');
			}
			$area[$data1['area_deep']] = $data1['area_id'];
			$area[$data2['area_deep']] = $data2['area_id'];
			$area[$data3['area_deep']] = $data3['area_id'];
 
			$post['name'] = I('post.name');
			$post['person'] = I('post.person');
			$post['add_community'] = I('post.add_community');
			$post['ext_info'] = I('post.ext_info');
			$post['status'] = I('post.status') ? 1 : 0;
			$post['telphone'] = I('post.telphone');
			$post['add_province'] = $area[1];
			$post['add_city'] = $area[2];
			$post['add_dist'] = $area[3];
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($post,true),'action'=>'物流-仓库设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			if(empty($id)) {
				$post['dateline'] = TIMESTAMP;
				$result = $storage->add($post);
				if($result)
				 $this->showmessage('success','操作成功');
			}else {
				$post['id'] = $id;
				$result = $storage ->save($post);
				if($result)
				$this->showmessage('success','操作成功');
			}
		}else {
			if(!empty($id)){
				$info = $storage->find($id);
				if(empty($info)) {
					$this->showmessage('error','仓库不存在');
				}
				$AreaModel =new AreaModel();
				$arealist = $AreaModel ->getAreaList();
				foreach($arealist as $k=>$v) {
					$area[$v['area_id']] = $v['area_name'];
				}
				unset($arealist);
				$this->assign('area', $area);
				$this->assign('info', $info);
			}
			
			$this->display('setstorage');
		}
	}
	//运费设置
	public function expense() {
		$model = new SettingModel();
		if($this->checksubmit()){
			$price =  I('post.price');
			if($price &&  !is_numeric($price) )
				$this->showmessage('error','免运费额度只能是数值');
				$update_array['expense'] = serialize(array(
					'price'=>$price,
					'type'=>I('post.secure'),
				));
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($update_array,true),'action'=>'物流-运费设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			$model->Settings($update_array);
			$this->showmessage('success','操作成功');
		}else{
			$expense = $model->getSetting('expense');
			$expense = unserialize($expense);
			$this->assign('expense', $expense);
		}
		$this->display('expense');
	}
	public function del() {
		$id = I("param.id");
		$storage = M("storage");
		$info = $storage->find($id);
		if(empty($info)) {
			$this->showmessage('error','该仓库不存在');
		}
		\Common\Helper\LogHelper::adminLog(array('content'=>var_export($info,true),'action'=>'物流-删除仓库','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
		$return = $storage->where("id=%d",array($id))->delete();
		if($return) {
			$this->showmessage('success','删除成功');
		}else{
			$this->showmessage('error','删除失败，请刷新重试');
		}
	}
	
	public function  query_setting(){
	    $model = new SettingModel();
	    if (I('post.')){
	        $update_array = array();
	        $update_array['express_query_id'] = I('post.express_query_id');
	        $update_array['express_query_key'] = I('post.express_query_key');
	        if(!$update_array['express_query_id'] || !$update_array['express_query_key']){
	        	$this->showmessage('error','请正确填写必选项',U("Admin/Logistics/query_setting"));
	        }
	        \Common\Helper\LogHelper::adminLog(array('action'=>'快递查询设置','content'=>var_export($update_array,true),'username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
	        $result = $model -> Settings($update_array);
	        if ($result){
	            $this->showmessage('success','保存成功',U("Admin/Logistics/query_setting"));
	        }else {
	            $this->showmessage('error','保存失败',U("Admin/Logistics/query_setting"));
	        }
	    }
	    
	    //获取邮件设置参数
	    $config = $model -> getSettings();
	    //获取快递公司列表
	    $company = M('companylist');
	    $list = $company->order('id ASC')->select();
		$this->assign('companylist', $list);

	    //变量分配
	    $this->assign('config',$config);
	    //模板解析
	    $this->display();        
	}
}