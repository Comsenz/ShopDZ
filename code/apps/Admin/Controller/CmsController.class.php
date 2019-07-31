<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\SettingModel;
use Common\PayMent\WxPay\WxRefundUser;
use Common\Helper\CacheHelper;
use  Common\Helper\LoginHelper;

class CmsController extends BaseController {
	/*
	* 文章分类添加、编辑
	*
	*/
	public function article_class_add(){
		$class_id = intval(I('param.id'));
		$article_class = M('cms_article_class');
        if ($this->checksubmit()){
            $insert_array = array();
            $insert_array['class_name'] = I('post.class_name');
            $insert_array['class_sort'] = I('post.class_sort');
            if($insert_array['class_name'] == '' || $insert_array['class_sort'] == ''){
               $this->showmessage('error','请完整填写分类信息',U("Admin/Cms/article_class_list")); 
            }
            
            if($class_id){
            	$result = $article_class->where(array('class_id'=>$class_id))->save($insert_array);
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($insert_array,true),'action'=>'资讯分类编辑','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            }else{
            	$result = $article_class->add($insert_array);
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($insert_array,true),'action'=>'资讯分类添加','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            }
            // var_dump($result);die;
            if($result !== false){
                $this->showmessage('success','操作成功',U("Admin/Cms/article_class_list"));
            }else {
                $this->showmessage('error','操作失败',U("Admin/Cms/article_class_list"));
            }
        }else{
        	if($class_id){
        		$class_array = $article_class->where(array('class_id'=>$class_id))->find();
        		if($class_array['class_code'] != 0){
        			$this->showmessage('error','此分类为系统专用，不可编辑修改！',U("Admin/Cms/article_class_list"));
        		}else{
        			$this -> assign("class_array",$class_array);
        		}
        	}
        		        	
			$this->display('article_class_add');
        }
		
	}

	/**
     * 文章分类列表
     */
	public function article_class_list(){
		$article_class = M('cms_article_class');
        $count = $article_class->count();
        $page  = new \Common\Helper\PageHelper($count,20);
        $this->assign('page',$page->show());
        $list = $article_class->limit($page->firstRow.','.$page->listRows)->order('class_sort desc')->select();
		$this -> assign("list",$list);
		$this->display('article_class_list');
	}


    /**
     * 文章分类删除
     */
    public function article_class_del(){
    	$class_id = intval(I('get.id'));
    	if(empty($class_id)){
			$this->showmessage('error','参数错误');
    	}
		if(IS_AJAX){
			$article_class = M('cms_article_class');
            $article = M('article');
            $class_array = $article_class->where(array('class_id'=>$class_id))->find();
            $article_list = $article->where(array('class_id'=>$class_id))->select();

            if($class_array['class_code'] != 0){
    			$this->showmessage('error','此分类为系统专用，不可删除！',U("Admin/Cms/article_class_list"));
    		}else{
    			if($article_list && is_array($article_list)){
	                $this->showmessage('error','请先删除分类下的文章，才能删除此分类！');
	            }else{
	                $return = $article_class->where(array('class_id'=>$class_id))->delete();
	                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($class_id,true),'action'=>'资讯分类删除','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
	            }
    		}
            

			if(!$return)
				$this->showmessage('error','删除失败请重试！');
			$this->showmessage('success','操作成功！');
		}
		$this->showmessage('error','非法操作！');

    }

    /**
     * 文章添加、编辑
     */
    public function article_add(){
    	$article = M('article');

    	$article_id = intval(I('param.id'));

    	//分类列表
        $article_class = M('cms_article_class');
        $list = $article_class ->order('class_sort desc')->select();
        $this -> assign("list",$list);
        if ($this->checksubmit()){
            $insert_array = array();
            $insert_array['article_title'] = I('post.article_title');
            $insert_array['class_id'] = intval(I('post.class_id')) ? intval(I('post.class_id')) : '';
            $insert_array['article_show'] = I('post.article_show') ? 1:0;
            $insert_array['article_sort'] = I('post.article_sort');
            $insert_array['article_content'] = I('post.article_content');
            $insert_array['article_time'] = TIMESTAMP;
            $must = array(
                'article_title'=>'文章标题不能为空',
                'class_id'=>'分类id不能为空',
                'article_content'=>'文章内容不能为空'
                );
            foreach ($must as $k => $v) {
                $val = $insert_array[$k];
                if(empty($val)){
                    $this->showmessage('error',$v);
                }
            }
            if($article_id){
            	$insert_array['article_id'] = $article_id;
            	$result = $article->save($insert_array);
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($insert_array,true),'action'=>'文章编辑','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            }else{
            	$result = $article->add($insert_array);
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($insert_array,true),'action'=>'文章添加','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            }
            if($result !== false){
                $this->showmessage('success','操作成功',U("Admin/Cms/article_list"));
            }else {
                $this->showmessage('error','操作失败',U("Admin/Cms/article_list"));
            }
        }else{
        	if($article_id){
        		$data = $article -> where(array('article_id'=>$article_id)) ->find();
        		$this->assign('data',$data); 
        	}
        	$this->display('article_add');
        }
    	

    }

    /**
     * 文章删除
     */
    public function article_del(){

    	$article_id = intval(I('get.id'));
    	if(empty($article_id)){
			$this->showmessage('error','参数错误');
    	}
		if(IS_AJAX){
			$article = M('article');
			$return = $article->where(array('article_id'=>$article_id))->delete();
            \Common\Helper\LogHelper::adminLog(array('content'=>var_export($article_id,true),'action'=>'文章删除','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			if(!$return)
				$this->showmessage('error','删除失败请重试');
			$this->showmessage('success','操作成功');
		}
		$this->showmessage('error','非法操作');

    }

    /**
     * 文章列表
     */
    public function article_list(){
    	$article = M('article');
        $where = array();
        $article_title = I("get.value",'','htmlspecialchars');
        if($article_title){
            $where = 'article_title like "%'.$article_title.'%"';
        }
        $count = $article->where($where)->count();
        $page  = new \Common\Helper\PageHelper($count,20);
        $this->assign('page',$page->show());
        $list = $article->limit($page->firstRow.','.$page->listRows)->where($where)->order('article_sort desc,article_time desc')->select();
		$class_array = $this->_getArticleClass();
		$this ->assign("class_array",$class_array);
		$this -> assign("list",$list);
		$this->display('article_list');
    }

    private function _getArticleClass(){
    	$data = $list = array();
    	$article_class = M('cms_article_class');
    	$list = $article_class ->select();

    	foreach ($list as $k => $v) {
    		$data[$v['class_id']] = $v['class_name'];
    	}
    	unset($list,$article_class);
    	return $data;

    }
	/*
	*推广设置
	*/

	public function bank() {
		$id = I('param.id');
		if($this->checksubmit()){
			$bank_name = I('post.bank_name');
			$data['name'] = $bank_name;
			if(empty($bank_name)) 
				$this->showmessage('error','银行名称不能为空');

			if(empty($id)){
				$data['add_time'] = TIMESTAMP;
				$res = D('Bank')->add($data);
			}else{
				$data['id'] = $id;
 				$res = D('Bank')->save($data);
			}
			if(false !=$res) 
				$this->showmessage('success','操作成功',U('Cms/banklist'));
			else
				$this->showmessage('error','操作失败请重试');
		}else{
			if(!empty($id)){
				$data = D('Bank')->find($id);
				$this->assign('data', $data);
			}
			$this->display('bank');
		}
	}
	
	public function banklist() {
		$list = D('Bank')->getList();
		$this->assign('list', $list);
		$this->display('banklist');
	}
	
	public function delbank() {
		$id = I('param.id');
		$res = D('Bank')->where('id=%d',array($id))->delete();
		if($res) 
			$this->showmessage('success','操作成功');
		else
			$this->showmessage('error','操作失败请重试');
	}
	/*
	*推广设置
	*/
	public function spread() {
		$model = new SettingModel();
		if($this->checksubmit()){
			$maxprice =  I('post.maxprice');
			$minprice =  I('post.minprice');
			$content =  I('post.article_content');
			$wx_withdraw_status = isset($_POST['wx_withdraw_status'])?1:0;
			$wx_withdraw_audit_status = isset($_POST['wx_withdraw_audit_status'])?1:0;
			if(empty($maxprice) || empty($minprice) ||  !is_numeric($maxprice) ||  !is_numeric($minprice) )
				$this->showmessage('error','提现额度只能是数值');
			if(empty($content)) {
				$this->showmessage('error','提现说明不能为空');
			}
			if($minprice < 1) {
				$this->showmessage('error','提现最低额度是1元');
			}

				$update_array['spread'] = serialize(array(
					'wx_withdraw_status'=>$wx_withdraw_status,
					'wx_withdraw_audit_status'=>$wx_withdraw_audit_status,
					'maxprice'=>$maxprice,
					'minprice'=>$minprice,
					'content'=>$content,
				));
			$model->Settings($update_array);
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($update_array,true),'action'=>'推广设置-提现设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			$this->showmessage('success','操作成功');
		}else{
			$spread = $model->getSetting('spread');
			$spread = unserialize($spread);
		
			$this->assign('data', $spread);
		}
		$this->display('spread');
	}
	/*
	*奖励设置
	*/
	function reward() {
		$model = new SettingModel();
		if($this->checksubmit()){
			$reward_1 =  I('post.reward_1',0,'intval,abs');
			$reward_2 =  I('post.reward_2',0,'intval,abs');
			$reward_3 =  I('post.reward_3',0,'intval,abs');
			$all_reward = $reward_1 + $reward_2 + $reward_3;
			if($all_reward > 50){
				$this->showmessage('error','三级合计奖励比例不得超过50%');
			}
			$update_array['spread_reward'] = serialize(array(
					'reward_1'=>$reward_1,
					'reward_2'=>$reward_2,
					'reward_3'=>$reward_3,
				));
			$model->Settings($update_array);
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($update_array,true),'action'=>'推广设置-奖励设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			$this->showmessage('success','操作成功');
		}else{
			$spread_reward = $model->getSetting('spread_reward');
			$spread_reward = unserialize($spread_reward);
			$this->assign('data', $spread_reward);
			$this->display('reward');
		}
	}
	/*
	*会员奖励明细
	*/
	function presales() {
		$type_array = array(
			'member_truename'=>'member_truename',
			'member_uid'=>'member_uid',
			'order_sn'=>'order_sn',
		);
		$type = I('get.type');
		$text = I('get.search_text');
		$where = array();
		if($type && in_array($type,$type_array)) {
			$where["$type_array[$type]"] = array("like", "%".$text."%");
		}
		
		$model = D('SpreadWithdrawCash');
		$count = $model ->getPresalesCount($where);
		$page  = new \Common\Helper\PageHelper($count);
		$list = $model ->presales($where,$page);
		$this->assign('type', $type);
		$this->assign('search_text', $text);
		$this->assign('list', $list);
		$this->assign('page',$page->show());
		$this->display('presales');
	}
	/*
	*后台提现记录
	*/
	function withdraw() {
		$type_array = array(
			'cash_sn'=>'cash_sn',
			'member_name'=>'member_name',
			'member_uid'=>'member_uid',
			'user_name'=>'user_name',
		);
		$type = I('get.type');
		$status = I('get.status');
		$text = I('get.search_text');
		$where = array();
		if($type && in_array($type,$type_array)) {
			$where["$type_array[$type]"] = array("like", "%".$text."%");
		}
		$status && $where['status'] = $status;
		if($status == 1){
			$where['status'] = array(array('eq',1),array('eq',4), 'or');
		}
		$model = D('SpreadWithdrawCash');
		$count = $model ->getAllWithdrawCount($where);
		$page  = new \Common\Helper\PageHelper($count);
		$list = $model ->getAllWithdraw($where,$page);
		$this->assign('page',$page->show());
		$this->assign('type', $type);
		$this->assign('status', $status);
		$this->assign('search_text', $text);
		$this->assign('list', $list);
		$this->display('withdraw');
	}
	
	function edit() {
	
		$id = I('param.id');
		$model = D('SpreadWithdrawCash');
		$data = $model->withdrawById($id,array(array('eq',1),array('eq',4), 'or'));
		if(empty($data)){
			$this->showmessage('error','提现数据不存在或已经审核过');
		}
		$status_array = array(1=>3,2=>2);
		if($this->checksubmit()){
			$status = I('post.agree');
			$remark = I('post.remark');
			if(empty($status_array[$status])){
				$this->showmessage('error','请选择同意或者拒绝');
			}
			$data['user_id'] = $this->admin_user['uid'];
			$data['user_name'] = $this->admin_user['username'];
			$data['status'] = $status_array[$status];
			$data['remark'] = $remark;
			$data['enddate'] = TIMESTAMP;
			$data = array_filter($data);
			unset($data['status_text']);
			$data['cash_amount'] = floatval($data['cash_amount']);
			if($data['status'] != 2 && $data['type'] == 2){
				/* 微信提现打款 */
				$wxpay = new WxRefundUser($data['bank_no'],$data['cash_amount'],$data['cash_sn']);
				$result = $wxpay->UserRefund();
				if($result['result_code'] != 'SUCCESS'){
					$data['notify_mark'] = $result['return_msg'];
					$data['status'] = 4;
				}
			}elseif($data['status'] != 2 && $data['type'] == 3){
				/* 微信小程序提现打款 */
				$wxpay = new \Common\PayMent\WxxPay\WxRefundUser($data['bank_no'],$data['cash_amount'],$data['cash_sn']);
				$result = $wxpay->UserRefund();
				// $this->writeLog($result);
				if($result['result_code'] != 'SUCCESS'){
					$data['notify_mark'] = $result['return_msg'];
					$data['status'] = 4;
				}
			}
			$res = $model->optionWithdraw($data['member_uid'],$data);
			if(false != $res) {
				\Common\Helper\LogHelper::adminLog(array('content'=>var_export($data,true),'action'=>'推广设置-奖励设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
				if($data['status'] == 4){
					$this->showmessage('error','操作失败'.$result['return_msg'],U('Cms/withdraw',array('status'=>1)));
				}else{
					$this->showmessage('success','操作成功',U('Cms/withdraw',array('status'=>1)));
				}
			}else{
				$this->showmessage('error','操作失败请重试');
			}
		}else{
			$data['bank_no'] = $model->_hide_middle_str($data['bank_no']);
			$this->assign('data', $data);
			$this->display('edit');
		}
	}
	
	function show() {
		$id = I('param.id');
		$model = D('SpreadWithdrawCash');
		$data = $model->withdrawById($id);
		if(empty($data)){
			$this->showmessage('error','提现数据不存在或已经审核过');
		}
		$data['bank_no'] = $model->_hide_middle_str($data['bank_no']);
		$this->assign('data', $data);
		$this->display('show');
	}

	/**
	 * 扫码支付
	 */
	public function otherpay(){
		$status = I('status','','intval');
		$key = I('type','','htmlspecialchars');
		$value = I('search_text','','htmlspecialchars');
		if(!empty($status)){
			$model = D('OtherPay');
			$where = array();
			if($key != 'trade_no'){
				$where[$key] = array('eq',$value);
				$where['trade_no'] = array('neq','');
			}else{
				$where['trade_no'] = array('eq',$value);
			}
			$count = $model->getAllOtherPayCount($where);
			$page  = new \Common\Helper\PageHelper($count);
			$list = $model->getAllOtherPay($where,$page);
			$this->assign('page',$page->show());
			$this->assign('status',$status);
			$this->assign('type',$key);
			$this->assign('search_text',$value);
			$this->assign('list', $list);
		}
		$model = new SettingModel();
		//获取配置
        $logo = $model -> getSetting('shop_logo');
		$this->assign('qrcode_url',SITE_URL .'wap/yimapay.html');
		$this->assign('logo_url',SITE_URL .'data/Attach/'.$logo);
		$this->display('otherpay');
	}
	
	/**
	 * 数据调用列表
	 *
	 */
	public function UserGetDateList(){
		$modename = I('post.modename');
		$where = array();
		if(!empty($modename)){
			$where['modename'] = array('like',"%$modename%");
		}
		//P($where);exit;
		$count = M("user_getdata") -> where($where) -> count();
		$page  = new \Common\Helper\PageHelper($count,20);
		$this->assign('page',$page->show());
		$getdatalist = M("user_getdata") -> where($where) -> limit($page->firstRow.','.$page->listRows) -> order('dataline DESC') ->select();
		//P($getdatalist);exit;

		$this -> assign("modename",$modename);

		$this -> assign("getdatalist",$getdatalist);
		$this->display('usergetdatalist');
	}
	/**
	 * 数据调用添加
	 */
	public function UserGetDate(){
		if(IS_POST){//添加商品
			$id = I('post.goods_id');
			$updateid = I('post.id');
			$modename = I('post.modename');
			if(empty($modename)){
				$this->showmessage('error','模块名称不能为空！');
			}
			$old_img = I('post.old_img');
			$new_img = I('post.new_img');
			$imgorder = I('post.imgorder');
			$state = I('post.state');
			$displayorder = I('post.displayorder');
			$data = array();
			$data['modename'] = $modename;
			$shopdate = array();
			if(!empty($id)){
				$i = 0;
				foreach($id as $k=>$v){
					$shopdate[$k]['goodsid'] = $v;
					$shopdate[$k]['imgorder'] = $imgorder[$k];
					$shopdate[$k]['url'] = !empty($new_img[$k]) ? $new_img[$k] : str_replace(C('TMPL_PARSE_STRING.__ATTACH_HOST__'),'',$old_img[$k]);
					$i++;
				}
				$data['shopdata'] = serialize($shopdate);
				$data['goodsnum'] = $i;
				$data['dataline'] = time();

				$data['state'] = !empty($state) && $state == 'on' ? 1 :0;
				$data['displayorder'] = !empty($displayorder) ? $displayorder :0;
				if(!empty($updateid)){
					$where = array('id'=>$updateid);
					if(M('user_getdata')->where($where)->save($data)){
						$this->showmessage('success','更新成功！',U('Cms/UserGetDateList'));
					}else{
						$this->showmessage('error','更新失败！');
					}
				}else{
					$data['getcode'] = LoginHelper::random(10);;
					$data['usergetdata'] = '/api.php/UserGetData/getData/getcode/'.$data['getcode'];
					if(M('user_getdata')->add($data)){
						$this->showmessage('success','操作成功！',U('Cms/UserGetDateList'));
					}else{
						$this->showmessage('error','操作失败！');
					}
				}
			}else{
				$this->showmessage('error','请选择商品！');
			}
		}else{//页面显示
			$allcategory = D('GoodsCategory')->getAllCategoryTree();
			$category = array();
			foreach($allcategory as $k=>$v) {
				$category[$v['gc_id']] = $v;
			}
			unset($allcategory);
			$this->assign('category',json_encode($category));
			$this->display('usergetdata');
		}
	}
	public function UserGetDateEdit(){
			$modeid  = I('get.modeid');
			$dateinfo = M('user_getdata')->where(array('id'=>$modeid))->find();
			$datalist = unserialize($dateinfo['shopdata']);
			if(!empty($dateinfo)){
				$goodsdata = array();
				foreach($datalist as $k =>$v){
					$goodsinfo = M('goods_common')->where(array('goods_common_id'=>$v['goodsid']))->find();
					$goodsdata[$k]['goodsid'] = $v['goodsid'];
					$goodsdata[$k]['new_img'] = C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['url'];
					$goodsdata[$k]['imgorder'] = $v['imgorder'];
					$goodsdata[$k]['price'] = $goodsinfo['goods_price'];
					$goodsdata[$k]['goods_name'] = $goodsinfo['goods_name'];
					$goodsdata[$k]['storage'] = M('goods')->where(array('goods_common_id'=>$v['goodsid']))->sum('goods_storage');
				}
			}

			$allcategory = D('GoodsCategory')->getAllCategoryTree();
			$category = array();
			foreach($allcategory as $k=>$v) {
				$category[$v['gc_id']] = $v;
			}
			unset($allcategory);
			$this->assign('category',json_encode($category));
			$this->assign('goodsdata',$goodsdata);
			$this->assign('dateinfo',$dateinfo);
			$this->display('usergetdataedit');

	}
	public function deldata(){
		$id = I('post.id');
		if(!empty($id)){
			if(M('user_getdata')->where(array('id'=>$id))->delete()){
				$data =array('code'=>0,'message'=>'删除成功');
				$this->ajaxReturn($data);
			}else{
					$data =array('code'=>1,'message'=>'删除失败');
					$this->ajaxReturn($data);

			}
		}
	}
	public function updateCash(){
		$id = I('post.id');
		if($id == 0){
			$datainfo = M('user_getdata')->select();

			foreach($datainfo as $k=>$v){
				$preCash = CacheHelper::getCachePre('user_getdata').$v['getcode'];

				S($preCash,NULL);
			}
			$data =array('code'=>0,'message'=>'更新成功');
			$this->ajaxReturn($data);
		}else{
			$data =array('code'=>2,'message'=>'参数错误');
			$this->ajaxReturn($data);
		}
	}
	/*
     * 上传文件、、
     * */
	public function upLogoFile(){
		$post = array();
		if($_FILES){

			$file = uploadfile($_FILES,'avatar');
			if($file['file']){
				$allurl =C('TMPL_PARSE_STRING.__ATTACH_HOST__').$file['file'];
				$this -> ajaxReturn(array('status'=>1,'data'=>$file['file'],'allurl'=>$allurl),'json');
			}
		}
	}
	//应用管理
	public function plugins() {
		$module = I('param.module');
		if($module) {//店铺概况，进入路由，主要是兼顾左侧导航问题
			$plugin = A('Admin/Plugin');
			$plugin->admin();
		}else{
			$this->redirect('Plugin/index');
		}
	}

}