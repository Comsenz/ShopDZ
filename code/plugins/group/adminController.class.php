<?php
/**
 * 插件需要执行的方法 逻辑定义  
 */
namespace plugins\group;
use Admin\Controller\BaseController;
use Admin\Model\OrdersModel;
use Common\Model\GoodsModel;
use Common\Model\GoodsCategoryModel;
use plugins\group\GroupModel;
class adminController extends BaseController
{
	public function _initialize() {
		$root  =  dirname(__FILE__);
		list(,$module) = explode('plugins',$root);
		define('THEME_PATH', 'plugins/'.trim($module,'/').'/');
	}

	public function add() {
		$group_id  = I('param.id');
		$goods_model = new GoodsModel();
		$group_model = new GroupModel();
		$GoodsCategory_model = new GoodsCategoryModel();
		if($this->checksubmit()){
			$post = I('post.');
			$must = array(
					'goods_id'=> array('tips'=>L('select_goods'),'check'=>'intval'),
					'group_name'=>array('tips'=>L('group_group_name'),'check'=>'htmlspecialchars'),
					'goods_name'=>array('tips'=>L('goods_name_text'),'check'=>'htmlspecialchars'),
					'price'=>array('tips'=>L('group_submit_price'),'check'=>'is_numeric'),
					'group_content'=>array('tips'=>L('group-content_text'),'check'=>'htmlspecialchars'),
					'group_person_num'=>array('tips'=>L('group_submit_group_person_num'),'check'=>'intval'),
				//'max_ok_group'=>array('tips'=>L('group_submit_max_ok_group'),'check'=>'intval'),
				//	'max_group'=>array('tips'=>L('group_submit_max_group'),'check'=>'intval'),
				//'add_num'=>array('tips'=>L('group_submit_add_num'),'check'=>'intval'),
					'group_hour'=>array('tips'=>L('group_submit_group_hour'),'check'=>'intval'),
					'starttime'=>array('tips'=>L('group_submit_starttime'),'check'=>'htmlspecialchars'),
					'endtime'=>array('tips'=>L('group_submit_endtime'),'check'=>'htmlspecialchars'),
					'group_image'=>array('tips'=>L('group_submit_group_image'),'check'=>'htmlspecialchars')
			);

			foreach($post as $k=>$v) {
				if(array_key_exists ($k,$must)) {
					$v = $must[$k]['check']($v);
					if(empty($v)){
						$this->showmessage('error',$must[$k]['tips']);
					}
				}
			}

			$good = $goods_model->getGoodById($post['goods_id']);

			if(empty($good)){
				$this->showmessage('error','goods_not_exits');
			}
			if(empty($good['goods_storage'])){
				$this->showmessage('error','goods_storage_is_null');
			}
			/*
            if($post['max_ok_group'] > ($good['goods_storage'] /$post['group_person_num']))  {
                $this->showmessage('error',L('group_base_group_max_tips'));
            }
            */
			$post['endtime'] = strtotime($post['endtime']) + 86400-1;
			$good = $goods_model->getGoodById($post['goods_id']);
			$goods_images = $goods_model->getGoodSImages($good['goods_common_id']);
			$data = array(
					'group_name'=>$post['group_name'],
					'goods_name'=>$post['goods_name'],
					'goods_id'=>$post['goods_id'],
					'group_price'=>$post['price'],
					'group_content'=>$post['group_content'],
					'group_person_num'=>$post['group_person_num'],
					'max_ok_group'=>0,
					'max_group'=>0,
					'add_num'=>$post['add_num'],
					'group_hour'=>$post['group_hour'],
					'starttime'=>strtotime($post['starttime']),
					'endtime'=>$post['endtime'],
					'group_image'=>!empty($post['group_image']) ? $post['group_image'] : $goods_images[0],
					'is_shipping'=>$post['is_shipping'],
					'active_end_time'=>$post['endtime']+($post['group_hour']*3600),
					'head_welfare'=>$post['head_welfare_show'],
					'head_welfare_type'=>$post['head_welfare'],
					'head_num'=>$post['head_welfare'] == 'none' ? 0 : $post[$post['head_welfare']],
			);
			if(empty($group_id)){
				$data['add_time']= TIMESTAMP;
				$result = D('group')->add($data);
				\Common\Helper\LogHelper::adminLog(array('content'=>var_export($data,true),'action'=>'拼团-添加活动','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			}else{
				$data['id'] = $group_id;
				$result = D('group')->save($data);
				\Common\Helper\LogHelper::adminLog(array('content'=>var_export($data,true),'action'=>'拼团-修改活动','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			}
			if(false !== $result){
				$this->showmessage('success',L('operation_success'),U('Plugin/admin',array('module'=>'group','controller'=>'admin','method'=>'group')));
			}else{
				$this->showmessage('error',L('operation_fail'),U('group/activelist'));
			}

		}else{
			$allcategory = $GoodsCategory_model->getAllCategoryTree();

			$category = array();
			foreach($allcategory as $k=>$v) {
				$category[$v['gc_id']] = $v;
			}
			unset($allcategory);
			$data = $group_model->findById($group_id);
			$good = $goods_model->getGoodById($data['goods_id']);
			unset($good['goods_name']);
			$goods_images = $goods_model->getGoodSImages($good['goods_common_id']);
			$good['goods_images'] = $goods_images;
			$data = array_merge($data,$good);

			$this->assign('category',json_encode($category));
			$this->assign('data',$data);
			$this->display('view/add');
		}

	}
	//参团活动
	public function grouplist() {
		$id  = I('param.active_id');
		$group_model = new GroupModel();
		$goods_model = new GoodsModel();
		$search_where = $this->_search();
		$id && $search_where['active_id'] = $id;
		$count = $group_model->getAdminGroupGroupListCount($search_where);
		$page  = new \Common\Helper\PageHelper($count);
		$lists = $group_model->getAdminGroupGroupList($search_where,$page);
		foreach( $lists as $k => $v ) {
			$where = $where_join = array();
			$pay_count = 0;
			$goodInfo = $goods_model->getGoodById($v['goods_id']);
			$where_join['group_id'] = $v['group_id'];
			$where_join['trade_no'] = array('NEQ','is null');
			$pay_count = $group_model->getGroupJoinCount($where_join);
			$lists[$k]['goods_storage'] = $goodInfo['goods_storage'];
			$lists[$k]['goods_price'] = $goodInfo['goods_price'];
			$lists[$k]['pay_count'] = $pay_count;
		}
		$this->assign('page',$page->show());
		$this->assign('lists',$lists);
		$this->display('view/grouplist');
	}
	//活动列表
	public function group() {
		$search_where = $this->_search();
		$group_model = new GroupModel();
		$goods_model = new GoodsModel();
		$count = $group_model->getGroupListCount($search_where);
		$page  = new \Common\Helper\PageHelper($count);
		$lists = $group_model->getAdminGroupList($search_where,$page);
		foreach( $lists as $k => $v ) {
			$where = $where_join = array();
			$pay_count = 0;
			$goodInfo = $goods_model->getGoodById($v['goods_id']);
			$where['active_id'] = $v['id'];
			$getGroupGroups = $group_model->getGroupGroups($where);
			$count = count($getGroupGroups);
			$where_join['active_id'] = $v['id'];
			$where_join['trade_no'] = array('NEQ','is null');
			$pay_count = $group_model->getGroupJoinCount($where_join);
			$lists[$k]['goods_storage'] = $goodInfo['goods_storage'];
			$lists[$k]['goods_price'] = $goodInfo['goods_price'];
			$lists[$k]['group_count'] = $count;
			$lists[$k]['pay_count'] = $pay_count;
		}
		$this->assign('page',$page->show());
		$this->assign('lists',$lists);
		$this->display('view/group');
	}
//参团列表
	function joinlist() {
		$group_model = new GroupModel();
		$goods_model = new GoodsModel();
		$id  = I('param.active_id');
		$group_id  = I('param.group_id');
		$search_where = $this->_search();
		$group_id && $search_where['group_id'] = $group_id;
		$id && $search_where['active_id'] = $id;
		$count = $group_model->getAdminGroupJoinListCount($search_where);
		$page  = new \Common\Helper\PageHelper($count);
		$lists = $group_model->getAdminGroupJoinList($search_where,$page);
		foreach( $lists as $k => $v ) {
			$where = array();
			$goodInfo = $goods_model->getGoodById($v['goods_id']);
			$lists[$k]['goods_storage'] = $goodInfo['goods_storage'];
			$lists[$k]['goods_price'] = $goodInfo['goods_price'];
			$where['id'] = $v['group_id'];
			$getGroupGroup = $group_model->getGroupGroup($where);
			$lists[$k]['status_text'] = $getGroupGroup['status_text'];
		}
		$this->assign('page',$page->show());
		$this->assign('lists',$lists);
		$this->display('view/joinlists');
	}


	function _search() {
		$search_where = array();
		$field = I('get.field');
		$q = I('get.q','','htmlspecialchars');
		switch ($field) {
			case 'goods_name':
				$q && $search_where["goods_name"] = array('like','%'.$q.'%');
				break;
			case 'group_name':
				$q && $search_where["group_name"] = array('like','%'.$q.'%');
				break;
			case 'sku':
				$q && $search_where["goods_id"] = intval($q);
				break;
			case 'buyer_name':
				$q && $search_where["buyer_name"] = array('like','%'.$q.'%');
				break;
			case 'status':
				if( false !== mb_strpos($q, '成功', 0, 'UTF-8') ){
					$q = 1;
					$search_where["status"] = ($q);
				}
				if(false !== mb_strpos($q, '失败', 0, 'UTF-8')) {
					$q = -1;
					$search_where["status"] = ($q);
				}
				if(false !== mb_strpos($q, '进行', 0, 'UTF-8')) {
					$q = 0;
					$search_where["status"] = ($q);
				}
				break;
			case 'orderstatus':
				if(false !== mb_strpos($q, '付款', 0, 'UTF-8') ){
					$q = '2';
					$search_where["refund_status"] = array('in',$q);
				}
				if(false !== mb_strpos($q, '退款', 0, 'UTF-8')) {
					$q = '1,-1';
					$search_where["refund_status"] = array('in',$q);
				}
				if(false !== mb_strpos($q, '取消', 0, 'UTF-8')) {
					$q = '0';
					$search_where["refund_status"] = array('in',$q);
				}

				break;
		}
		return $search_where;
	}
	//详情
	function detail() {
		$order_sn = I('param.order_sn');
		$order_model = new OrdersModel();
		$goods_model = new GoodsModel();
		$group_model = new GroupModel();

		$join = $group_model->getGroupJoinBySn($order_sn);
		$group = $group_model->getGroup($join['active_id']);
		$common = $order_model->getOrderCommon($join['id']);
		$goodInfo = $goods_model->getGoodById($group['goods_id']);
		$group_group = $group_model->getGroupGroup(array('id'=>$join['group_id']));
		$group_walf = '';
		if($group_group['buyer_id'] == $join['buyer_id']){
			$group_walf = $group['head_welfare'].$group['head_num'];
		}

		$this->assign('order_detail',$join);
		$this->assign('group_walf',$group_walf);
		$this->assign('orderCommon',$common);
		$this->assign('goodInfo',$goodInfo);
		$this->assign('group',$group);
		$this->display('view/detail');
	}
	//删除活动
	function delgroup() {
		$id  = I('param.id');
		if(empty($id)) {
			$this->showmessage('error', L('param_error'));
		}
		$group_model = new GroupModel();
		$group = $group_model->findById($id);
		if(empty($group)) {
			$this->showmessage('error', L('group_no_exits'));
		}
		$where['active_id'] = $id;
		$getGroupGroups = $group_model->getGroupGroups($where);
		if(!empty($getGroupGroups)){
			$this->showmessage('error', L('group_delete_has_group'));
		}
		$res = $group_model->delById($id);
		\Common\Helper\LogHelper::adminLog(array('content'=>var_export($group,true),'action'=>'拼团-删除活动','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
		if($res)
			$this->showmessage('success', L('operation_success'));

		$this->showmessage('error', L('delete_fail'));
	}

	//关闭进行中的活动
	function closegroup() {
		$group_model = new GroupModel();
		$group_id  = I('param.group_id');
		$active_id  = I('param.active_id');
		$search_where['active_id'] = $active_id;
		$search_where['id'] = $group_id;
		$search_where['status'] = 0;//只能关闭正在进行中的
		$info = $group_model->getGroupGroupCount($search_where);
		if(empty($info)) {
			$this->showmessage('error', L('close_fail'));
		}
		$where['id'] = $group_id;
		$data['status'] = -1;//手动关闭的标志
		$result = $group_model->updateGroupgroup($where,$data);
		\Common\Helper\LogHelper::adminLog(array('content'=>var_export($info,true),'action'=>'拼团-关闭团','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
		if($result){
			$this->showmessage('success', L('operation_success'));
		}
		$this->showmessage('error', L('close_fail'));
	}
	public function getGoodsDetail(){
		$goodsid = I('post.goods_common_id');
		$mode = new GoodsModel();
		$goods = $mode->getSpuById($goodsid);
		if(!empty($goods)){
			$this -> ajaxReturn(array('code'=>1,'data'=>$goods));
		}else{
			$this -> ajaxReturn(array('code'=>0,'data'=>array()));
		}
	}
	public function groupset(){
		if($this->checksubmit()){
			$group_img = I('post.shop_logo');
			$group_content = I('post.article_content');
			$array = array('group_img'=>$group_img,'group_content'=>$group_content);
			M('group_set')->where('1=1')->delete();
			if(M('group_set')->add($array)){
				$this->showmessage('success', '操作成功');
			}else{
				$this->showmessage('error','操作失败');
			}
		}else{
			$group_set = M('group_set')->find();
			$this->assign('group_set',$group_set);
			$this->display('view/groupset');
		}
	}
	public function upLogoFile(){
		$post = array();
		if($_FILES){

			$file = uploadfile($_FILES);
			if($file['file']){
				$this -> ajaxReturn(array('status'=>1,'data'=>$file['file']),'json');
			}
		}
	}
}