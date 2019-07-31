<?php
namespace Admin\Controller;
use Admin\Model\PaymentModel;
use Admin\Model\SettingModel;
use Common\Service\SmsService;
use Common\Helper\CacheHelper;
use Think\Controller;
use Think\View;
use  Common\Service\StatisticsService;

class SettingController extends BaseController {
    public function main() {//主页
        $search_arr['search_type'] = 'month';
        if(!$search_arr['search_key']){
            $search_arr['search_key'] = 'order_amount';
        }
        $this->assign('search_arr',$search_arr);
        $SCachetime = C('SCachetime') ? C('SCachetime') : 300;
        $main_cache = S('main_cache');
        if($main_cache === false){
            /*********************统计表格 start*****************/
            // $search_arr = I('request.');
         //    if(!$search_arr['search_type']){
         //        $search_arr['search_type'] = 'day';
         //    }
         //    $this->assign('search_arr',$search_arr);
            $search_time = StatisticsService::init_date($search_arr);
            $condition = $curr_arr = $member_condition = array();
            $condition['add_time'] = array('between',array($search_time['stime'],$search_time['etime']));
            $condition['order_state'] = array('gt',10);
            $field = ' count(*) as order_num, sum(order_amount) as order_amount,count(distinct(buyer_id)) as order_member';

            $member_condition['member_time'] = array('between',array($search_time['stime'],$search_time['etime']));
            $member_field = ' COUNT(*) as new_member';

            for($i=0; $i<30; $i++){
                //统计图数据
                $_dtime = date('Ymd',$search_time['stime']+86400*$i);
                $curr_arr['order_num'][$_dtime] = 0;
                $curr_arr['order_amount'][$_dtime] = 0;
                $curr_arr['order_member'][$_dtime] = 0;
                $curr_arr['new_member'][$_dtime] = 0;
                //横轴
                $_time = date('m-d',$search_time['stime']+86400*$i);
                $stat_arr['order_num']['xAxis']['categories'][] = $_time;
                $stat_arr['order_amount']['xAxis']['categories'][] = $_time;
                $stat_arr['order_member']['xAxis']['categories'][] = $_time;
                $stat_arr['new_member']['xAxis']['categories'][] = $_time;
            }
            $field .= ' ,FROM_UNIXTIME(add_time,"%Y%m%d") as dayval';
            $member_field .= ' ,FROM_UNIXTIME(member_time,"%Y%m%d") as dayval';
            $_group = 'dayval';
            $list = StatisticsService::get_order_count($condition,$field,'',$_group);
            $member_list = StatisticsService::get_new_member($member_condition,$member_field,'',$_group);

            $num30 = $amount30 = $omember30 = $amember30 = 0;
            foreach((array)$list as $k => $v){
                $curr_arr['order_num'][$v['dayval']] = intval($v['order_num']);
                $curr_arr['order_amount'][$v['dayval']] = price_format($v['order_amount']);
                $curr_arr['order_member'][$v['dayval']] = intval($v['order_member']);
                $num30 = intval($num30 + intval($v['order_num']));
                $amount30 = price_format($amount30 + price_format($v['order_amount']));
            }
            foreach((array)$member_list as $mk => $mv){
                $curr_arr['new_member'][$mv['dayval']] = intval($mv['new_member']);
                $amember30 = intval($amember30 + intval($mv['new_member']));
            }

            $order_member_info = StatisticsService::get_order_count($condition,'count(distinct(buyer_id)) as order_member');
            $omember30 = intval($order_member_info[0]['order_member']);
            $stat_arr['order_num']['series'][0]['name'] = '下单量';
            $stat_arr['order_num']['series'][0]['data'] = array_values($curr_arr['order_num']);
            $stat_arr['order_amount']['series'][0]['name'] = '下单金额';
            $stat_arr['order_amount']['series'][0]['data'] = array_values($curr_arr['order_amount']);
            $stat_arr['order_member']['series'][0]['name'] = '下单会员';
            $stat_arr['order_member']['series'][0]['data'] = array_values($curr_arr['order_member']);
            $stat_arr['new_member']['series'][0]['name'] = '新会员';
            $stat_arr['new_member']['series'][0]['data'] = array_values($curr_arr['new_member']);

            $stat_arr['order_num']['title'] = '下单量统计';
            $stat_arr['order_num']['subtitle']['text'] = '近30天下单量统计';
            $stat_arr['order_num']['subtitle']['align'] = 'right';
            $stat_arr['order_num']['yAxis'] = '下单量';

            $stat_arr['order_amount']['title'] = '下单金额统计';
            $stat_arr['order_amount']['yAxis'] = '下单金额';
            $stat_arr['order_amount']['subtitle']['text'] = '近30天下单金额统计';
            $stat_arr['order_amount']['subtitle']['align'] = 'right';

            $stat_arr['order_member']['title'] = '下单会员统计';
            $stat_arr['order_member']['yAxis'] = '下单会员';
            $stat_arr['order_member']['subtitle']['text'] = '近30天下单会员统计';
            $stat_arr['order_member']['subtitle']['align'] = 'right';

            $stat_arr['new_member']['title'] = '新会员统计';
            $stat_arr['new_member']['yAxis'] = '新会员';
            $stat_arr['new_member']['subtitle']['text'] = '近30天新会员统计';
            $stat_arr['new_member']['subtitle']['align'] = 'right';

            $stat_json['order_num'] = StatisticsService::getStatData_LineLabels($stat_arr['order_num']);
            $stat_json['order_amount'] = StatisticsService::getStatData_LineLabels($stat_arr['order_amount']);
            $stat_json['order_member'] = StatisticsService::getStatData_LineLabels($stat_arr['order_member']);
            $stat_json['new_member'] = StatisticsService::getStatData_LineLabels($stat_arr['new_member']);
            $this->assign('stat_json',$stat_json);
            /*********************统计表格 end*****************/

            /*********************数据求和 start*****************/
            $all_condition = array();
            $all_condition["order_state"] = array('gt',10);
            $field = ' count(*) as order_num, sum(order_amount) as order_amount,count(distinct(buyer_id)) as order_member';
            $member_condition['member_time'] = array('between',array($search_time['stime'],$search_time['etime']));
            $member_field = ' COUNT(*) as all_member';
            $count_list = StatisticsService::get_order_count($all_condition,$field);
            $all_member_count = StatisticsService::get_new_member(array(),$member_field);
            $count = array();
            $count['num'] = array(
                    '30' => $num30,
                    'all'   => $count_list[0]['order_num'],
                );
            $count['amount'] = array(
                    '30'  => $amount30,
                    'all'    => $count_list[0]['order_amount'],
                );
            $count['omember'] = array(
                    '30' => $omember30,
                    'all'  => $count_list[0]['order_member'],
                );
            $count['amember'] = array(
                    '30' => $amember30,
                    'all'    => $all_member_count[0]['all_member'],
                );
            $this->assign('count',$count);
            /*********************数据求和 end*****************/

            /*********************热销商品 start*****************/
            $hot_condition = array();
            $table_pre =  C('DB_PREFIX');
            $hot_condition["{$table_pre}order_goods.add_time"] = array('between',array($search_time['stime'],$search_time['etime']));
            $hot_condition["{$table_pre}orders.order_state"] = array('gt',10);
            $hotfield = "sum({$table_pre}order_goods.goods_num) as goods_num, sum({$table_pre}order_goods.goods_price) as goods_price,min({$table_pre}order_goods.goods_name) as goods_name,{$table_pre}order_goods.goods_common_id as goods_common_id";
            $hot_group = 'goods_common_id';
            $order = 'goods_num desc';
            $limit = '10';
            $hotgoods = array();
            $hotgoods = StatisticsService::get_hotgoods($hot_condition,$hotfield,$order,$hot_group,$limit);
            foreach($hotgoods as $hk => $hv){
                $img = M('goods_common')->field('goods_common_id,goods_image')->where(array('goods_common_id' => $hv['goods_common_id']))->find();
                $hotgoods[$hk]['url'] = '../../wap/goods_detail.html?id='.$hv['goods_common_id'];
                $hotgoods[$hk]['goods_image'] = $img['goods_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$img['goods_image'] :'';
            }
            $this->assign('hotgoods',$hotgoods);
            /*********************热销商品 end*****************/

            /*********************热销商品 start*****************/
            $view_condition = array();
            $viewfield = ' goods_price,goods_name,goods_common_id,goods_image,view_number';
            $view_group = '';
            $order = 'view_number desc';
            $limit = '10';
            $viewgoods = array();
            $viewgoods = StatisticsService::get_viewgoods($view_condition,$viewfield,$order,$view_group,$limit);
            foreach($viewgoods as $vk => $vv){
                $viewgoods[$vk]['url'] = '../../wap/goods_detail.html?id='.$vv['goods_common_id'];
                $viewgoods[$vk]['goods_image'] = $vv['goods_image'] ? C('TMPL_PARSE_STRING.__ATTACH_HOST__').$vv['goods_image'] :'';
            }
            $this->assign('viewgoods',$viewgoods);
            /*********************热销商品 end*****************/
			/*********/
			$plugin_list = M('plugin')->where('status=1')->select();
			/*********/
            $main_cache = array(
                    'stat_json' => $stat_json,
                    'count'     => $count,
                    'hotgoods'  => $hotgoods,
                    'viewgoods' => $viewgoods,
                    'plugin_list' => $plugin_list,
                );
            S('main_cache',$main_cache,$SCachetime);
        }
            $this->assign('stat_json',json_decode(json_encode($main_cache['stat_json']),true));
            $this->assign('count',$main_cache['count']);
            $this->assign('hotgoods',$main_cache['hotgoods']);
            $this->assign('viewgoods',$main_cache['viewgoods']);
            $this->assign('plugin_list',$main_cache['plugin_list']);
        
        $this->display();
    }


	public function base(){
        $model = new SettingModel();
		$sub = I('param.sub',1);
        if($this->checksubmit()){
            unset($post['form_submit']);
            $post = I("param.");
            if($post['sub'] == 1){
                unset($post['sub']);
                $must = array(
					'shop_name'=>'网站名称不能为空',
				//	'shop_logo'=>'网站logo不能为空',
				//	'shop_login'=>'登录页图片不能为空',
					'footer_info'=>'网站底部信息不能为空',
					'record_number'=>'ICP证书号不能为空',
				//	'enterprise_contact'=>'客服电话不能为空'
					);
                foreach ($must as $k => $v) {
                    $val = $post[$k];
                    if(empty($val)){
                        $this->showmessage('error',$v,U("Admin/Setting/base"));
                    }
                }
                if($post['enterprise_contact'] && is_numeric($post['enterprise_contact'])==false){
                    $this->showmessage('error','客服电话不是数字',U("Admin/Setting/base"));
                }
                $post['web_status'] = isset($post['web_status']) ? 1 : 0;
                unserizepost($post);
                $result = $model -> Settings($post);
            
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($post,true),'action'=>'商城基本信息设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            }elseif($post['sub'] == 2){
                unset($post['sub']);
                $must = array('article_title'=>'协议标题不能为空','article_content'=>'协议内容不能为空');
                foreach ($must as $k => $v) {
                    $val = $post[$k];
                    if(empty($val)){
                        $this->showmessage('error',$v,U("Admin/Setting/base"));
                    }
                }
                unserizepost($post);
                $result = $model -> Settings($post);
            
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($post,true),'action'=>'商城基本信息设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
				$post['sub'] = 2;
            }else if($post['sub'] == 3){
				$post['verify_status'] = isset($post['verify_status']) ? 1 : 0;
                $result = $model -> Settings($post);
                \Common\Helper\LogHelper::adminLog(array('content'=>var_export($post,true),'action'=>'商城基本信息设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			}
			if ($result){
                $this->showmessage('success','保存成功',U("Admin/Setting/base",array('sub'=>$post['sub'])));
            }else {
                $this->showmessage('error','保存失败',U("Admin/Setting/base",array('sub'=>$post['sub'])));
            }
        }
        //获取配置
          $config = $model -> getSettings();
          
        $this -> assign("config",$config);
        $this -> assign("sub",$sub);
		switch($sub) {
			case 1:
			$this->display('base');
			break;
			case 2:
			$this->display('register');
			break;
			case 3:
			$this->display('verify');
			break;
		}
    }


    /*
     * 上传文件
     * */
    public function upLogoFile(){
        $post = array();
        if($_FILES){

            $file = uploadfile($_FILES);
            if($file['file']){
                $this -> ajaxReturn(array('status'=>1,'data'=>$file['file']),'json');
            }
        }
    }
    /*
       * 上传文件证书
       * */
    public function upLogoFilePem(){
        $post = array();
        if($_FILES){
            $file = uploadfile($_FILES,'pem');
            if($file['file']){
                $this -> ajaxReturn(array('status'=>1,'data'=>$file['file']),'json');
            }
        }
    }
    //邮箱设置
	public function email(){
		$model = new SettingModel();
        if (I('post.')){
            $update_array = array();
            $update_array['email_host'] = I('post.email_host');
            $update_array['email_port'] = I('post.email_port');
            $update_array['email_secure'] = I('post.email_secure');
            $update_array['email_addr'] = I('post.email_addr');
            $update_array['email_id'] = I('post.email_id');
            $update_array['email_pass'] = I('post.email_pass');
			\Common\Helper\LogHelper::adminLog(array('action'=>'邮件设置','content'=>var_export($update_array,true),'username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            $result = $model -> Settings($update_array);
            if ($result){
                $this->showmessage('success', L('save_success'),U("Admin/Setting/email"));
            }else {
                $this->showmessage('error', L('save_fail'),U("Admin/Setting/email"));
            }
        }

		//获取邮件设置参数
		$email_config = $model -> getSettings();
		$email_config['email_port']=(empty($email_config['email_port'])) ? '25' : $email_config['email_port'];

		//变量分配
		$this->assign('email_config',$email_config);
		//模板解析
        $this->display('email');
    }

    //测试邮箱
    public function email_testing(){
    	//获取邮件配置	
    	$email_config = array();
        $email_config['email_host'] = I('post.email_host');
        $email_config['email_port'] = I('post.email_port');
        $email_config['email_secure'] = I('post.email_secure');
        $email_config['email_addr'] = I('post.email_addr');
        $email_config['email_id'] = I('post.email_id');
        $email_config['email_pass'] = I('post.email_pass');

        //邮件接收地址
        $email_test = I('post.email_test');
        //邮件主题
        $subject = '测试邮箱';
        //邮件内容
        $content = '恭喜您邮箱测试成功！！！';

        if(sendMail($email_config,$email_test,$subject,$content)){
        	$data['code'] = 200;
        	$data['msg'] = L('send_success');
            }else{
        	$data['code'] = 404;
        	$data['msg'] = L('send_fail');
            }
            echo json_encode($data);
    }
     
	public function message(){
        $model = new SettingModel();
        $post = I("post.");
        if($this->checksubmit()){
            unset($post['form_submit']);
            if($post['sub'] == 1){
                unset($post['sub']);
                $post['smg_login'] = isset($post['smg_login']) ? 1 : 0;
                $result = $model -> Settings($post);
                \Common\Helper\LogHelper::adminLog(array('action'=>'短信平台','content'=>var_export($post,true),'username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            }
            
            if ($result){
                $this->showmessage('success',L('save_success'),U("Admin/Setting/message"));
            }else {
                $this->showmessage('error',L('save_fail'),U("Admin/Setting/message"));
            }
        }
        //获取配置
        $config = $model -> getSettings();
        $message = M('message');
        $sms = $message->select();
        $this -> assign("sms",$sms);

        if(I('get.active') == 2){
            $search_arr = $where = array();
            $search_arr['k'] = $search_key = I("get.k");
            $search_arr['q'] = $q = trim(I("get.q"));
            if(!empty($search_arr['q'])) {
                $where[$search_key] = array('like',"%$q%");
                $this -> assign("search_arr",$search_arr);
            }

            $log_count = SmsService::smslog_count($where);
            $overpage  = new \Common\Helper\PageHelper($log_count,20);
            $limit = $overpage->firstRow.','.$overpage->listRows;
            $this->assign('overpage',$overpage->show());
            $log = SmsService::smslog($where,$limit);
            $this -> assign("log",$log);
        }

        $overage = SmsService::get_overage();
        $config['overage'] = $overage['overage'];
        $config['sendtotal'] = $overage['sendtotal'];
        $this -> assign("config",$config);
		$this->display('smg');
    }
    public function messageedit()
    {
        $message_model = M('message');
        if(IS_POST){
            $content = I('post.content');
            $id = I('post.id');
            $states =  I('post.states') == 'on' ? '1' : '0';
            $in_array = array('message_content'=>$content,'message_states'=>$states);
            if( $message_model->where(array('id'=>$id))->save($in_array) === false){
                    $this->showmessage('error','更新失败',U("Admin/Setting/message"));
            }else{
                    $this->showmessage('success','更新成功',U("Admin/Setting/message"));
            }
        }else{
            $id = I('get.id');
            $message_data = $message_model->where(array('id'=>$id))->find();
            $this -> assign("message",$message_data);
            $this->display('messageedit');
        }
        # code...
    }

    public function del_smslog(){
        $log = M('send_msg');
        $type = I("get.type");
        if($type == 'six') {
            $time = strtotime('-15 day');
            $log->where("send_time<=$time")->delete();
        }else{
             $ids = I("param.ids");
             if(empty($ids)) {
                $this->showmessage('error','删除失败，请刷新重试');
             }
            $ids = is_array($ids) ? $ids : array($ids);
            $ids = implode(",",$ids);
            $log->delete($ids);
        }
        $this->showmessage('success','删除成功');
    }

    //消息模板显示 
	public function notes(){
		$model = D('Notes');
		$mmtpl_list = $model->getMemberMsgTplList(array());
		$this -> assign("mmtpl_list",$mmtpl_list);
		$this -> display('notes');
    }

    //编辑消息模板
    public function notes_edit(){
    	$model = D('Notes');
    	if (I('post.')){
            $code = trim(I("post.code"));
            $type = trim(I("post.type"));
            if (empty($code) || empty($type)) {
                $this->showmessage('error','参数错误！',U("Admin/Setting/notes_edit")); 
            }
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export(I("post."),true),'action'=>'消息模板设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            switch ($type) {
                case 'message':
                    $this->member_tpl_update_message();
                    break;
                case 'short':
                    $this->member_tpl_update_short();
                    break;
                case 'weixin':
                    $this->member_tpl_update_weixin();
                    break;
            }
        }
    	$code = trim(I("get.code"));
        if (empty($code)) {
            $this->showmessage('error','参数错误！',U("Admin/Setting/notes"));
        }

        $where = array();
        $where['mmt_code'] = $code;
		$mmtpl_info = $model->getMemberMsgTplInfo($where);

		//变量分配
		$this->assign('mmtpl_info',$mmtpl_info);

    	$this->display('notes_edit');
    }

    //消息模板修改站内信
    private function member_tpl_update_message(){
    	$model = D('Notes');
    	$message_content = trim(I('post.message_content'));
        if (empty($message_content)) {
            $this->showmessage('error','请填写站内信模板内容。',U("Admin/Setting/notes_edit"));
        }
        // 条件
        $where = array();
        $where['mmt_code'] = trim(I("post.code"));
        // 数据
        $update = array();
        $update['mmt_message_switch'] = empty($_POST['message_switch']) ? 0 : 1;
        $update['mmt_message_content'] = $message_content;
        $result = $model -> editMemberMsgTpl($where, $update);
        $this->member_tpl_update_showmessage($result);

    }
    
    //消息模板修改手机短信
    private function member_tpl_update_short(){
    	$model = D('Notes');
    	$short_content = trim(I('post.short_content'));
        if (empty($short_content)) {
            showMessage('请填写短消息模板内容。');
        }
        // 条件
        $where = array();
        $where['mmt_code'] = trim(I("post.code"));
        // 数据
        $update = array();
        $update['mmt_short_switch'] = empty($_POST['short_switch']) ? 0 : 1;
        $update['mmt_short_content'] = $short_content;
		\Common\Helper\LogHelper::adminLog(array('content'=>var_export($update,true),'action'=>'商城基本信息设置-修改手机短信','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
        $result = $model -> editMemberMsgTpl($where, $update);
        $this->member_tpl_update_showmessage($result);
    }

    //消息模板修改微信
    private function member_tpl_update_weixin(){
    	$model = D('Notes');
    	$wx_content = trim(I('post.wx_content'));
        if (empty($wx_content)) {
            showMessage('请填写短消息模板内容。');
        }
        // 条件
        $where = array();
        $where['mmt_code'] = trim(I("post.code"));
        // 数据
        $update = array();
        $update['mmt_wx_switch'] = empty($_POST['wx_switch']) ? 0 : 1;
        $update['mmt_wx_content'] = $wx_content;
		\Common\Helper\LogHelper::adminLog(array('content'=>var_export($update,true),'action'=>'商城基本信息设置-修改微信模板','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
        $result = $model -> editMemberMsgTpl($where, $update);
        $this->member_tpl_update_showmessage($result);
    	
    }

    //判断消息模板更改是否成功
    private function member_tpl_update_showmessage($result) {
        if ($result){
                $this->showmessage('success','保存成功',U("Admin/Setting/notes"));
            }else {
                $this->showmessage('error','保存失败',U("Admin/Setting/notes"));
            }
    }

    //地区设置 JUAN
	public function district(){
        $area_parent_id =  I('area_parent_id',0,'intval');
        $con  = array();
        $con['area_parent_id'] = $area_parent_id;

        $type = I("get.type");
        $search_text = I("get.search_text");
        if(!empty($search_text)) {
            $con[$type] = array('like',"%$search_text%");
            $this -> assign("search_text",$search_text);
        }

        $count = M("area") -> where($con) -> count();
       // $page = $this -> page($count,10);
        $page  = new \Common\Helper\PageHelper($count,20);
        $this->assign('page',$page->show());
        $arealist = M("area") -> where($con) -> limit($page->firstRow.','.$page->listRows) ->select();
        foreach ($arealist as $key => $value) {
        	 $parent_area_name = M('area')->where(array('area_id'=>$value['area_parent_id']))->getField('area_name');
        	 $arealist[$key]['parent_area_name'] = $parent_area_name?$parent_area_name:'--';
        }
        //取返回上级的id
        if($area_parent_id) {
            $parent_info = M('area')->where(array('area_id'=>$area_parent_id))->field('area_parent_id')->find();
        }
        $this -> assign("area_parent_id",$parent_info['area_parent_id']);
        $this -> assign("this_area_id",$area_parent_id);
        $this -> assign("arealist",$arealist);
        $this->display('district');
    }     
     
    public function payment(){
        $model = new PaymentModel();
		$payments = $model -> getPayment();
        if($this->checksubmit()){
            $post = I("post.");
            $data = array();
            $data['payment_code'] =  $post['paytype'];
            $data['payment_state'] = isset($post['status']) ? 1 : 0;
            $active = I("get.active");
            switch ($data['payment_code']) {
                case 'alipay':
					$alipay = $payments['alipay'];
					$account = I("post.account");
					$pid = I("post.pid");
                    $key = I("post.key");
					$appId = I("post.app_id");
					
					$account = strstr($account,'****') ? $alipay['account'] : $account;
					$pid = strstr($pid,'****') ? $alipay['pid'] : $pid;
                    $key = strstr($key,'****') ? $alipay['key'] : $key;
					$appId = strstr($appId,'****') ? $alipay['app_id'] : $appId;
						
                    $payment_config = array(
                        'account' => $account ,
                        'pid' => $pid,
                        'app_id' => $appId,
                        'seller_id' => $pid,
                        'key' => $key,
                        'private_key_path' => I("post.private_key_path"),
                    );
                    break;
                case 'wx':
					$wx = $payments['wx'];
					$appid =  I("post.appid");
					$appsecret =  I("post.appsecret");
					$mchid =  I("post.mchid");
					$key =  I("post.key");
					
					$key = strstr($key,'****') ? $wx['key'] : $key;
					$appid = strstr($appid,'****') ? $wx['appid'] : $appid;
					$appsecret = strstr($appsecret,'****') ? $wx['appsecret'] : $appsecret;
					$mchid = strstr($mchid,'****') ? $wx['mchid'] : $mchid;
                    $payment_config = array(
                        'appid'       => $appid,
                        'appsecret'   => $appsecret,
                        'mchid'      => $mchid,
                        'key'   => $key,
                        'sslcert_path'   => I("post.sslcert_path"),
                        'sslkey_path'   => I("post.sslkey_path"),
                    );
                    break;
                case 'wxx':
                    $wx = $payments['wxx'];
                    $appid =  I("post.appid");
                    $appsecret =  I("post.appsecret");
                    $mchid =  I("post.mchid");
                    $key =  I("post.key");
                    
                    $key = strstr($key,'****') ? $wx['key'] : $key;
                    $appid = strstr($appid,'****') ? $wx['appid'] : $appid;
                    $appsecret = strstr($appsecret,'****') ? $wx['appsecret'] : $appsecret;
                    $mchid = strstr($mchid,'****') ? $wx['mchid'] : $mchid;
                    $payment_config = array(
                        'appid'       => $appid,
                        'appsecret'   => $appsecret,
                        'mchid'      => $mchid,
                        'key'   => $key,
                        'sslcert_path'   => I("post.sslcert_path"),
                        'sslkey_path'   => I("post.sslkey_path"),
                    );
                    break;
                // case 'wy':
                //     $payment_config = array(
                //         'account' => I("post.account"),
                //         'pid' => I("post.pid"),
                //         'key' => I("post.key")
                //     );
                //     break;
                default:
                    $this->showmessage('error','参数错误！',U("Admin/Setting/payment"));
            }

            $data['payment_config'] = serialize($payment_config);
            switch($data['payment_code']){
                case "alipay":
                    $data['payment_name'] = "支付宝";
                    break; 
                case "wx":
                    $data['payment_name'] = "微信";
                    break;
                case "wxx":
                    $data['payment_name'] = "微信小程序";
                    break;
                // case "wy":
                //     $data['payment_name'] = "网银";
                //     break;
                default:
                    break;
            }
            $result = $model -> addPayment($data);
            \Common\Helper\LogHelper::adminLog(array('action'=>'支付方式','content'=>var_export($data,true),'username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            if ($result){
                $this->showmessage('success','保存成功',U("Admin/Setting/payment",array('active'=>$active)));
            }else {
                $this->showmessage('error','保存失败',U("Admin/Setting/payment",array('active'=>$active)));
            }
        }
        //获取所有支付信息

		foreach($payments as $k => $v) {
			$v['account'] = \Common\Helper\ToolsHelper::simpleShow($v['account']);
			$v['pid'] = \Common\Helper\ToolsHelper::simpleShow($v['pid']);
			$v['key'] = \Common\Helper\ToolsHelper::simpleShow($v['key']);
			$v['appid'] = \Common\Helper\ToolsHelper::simpleShow($v['appid']);
			$v['mchid'] = \Common\Helper\ToolsHelper::simpleShow($v['mchid']);
			//$v['key'] = \Common\Helper\ToolsHelper::simpleShow($v['key']);
            $v['appsecret'] = \Common\Helper\ToolsHelper::simpleShow($v['appsecret']);
			$v['app_id'] = \Common\Helper\ToolsHelper::simpleShow($v['app_id']);
			$payments[$k] = $v;
		}
        $this ->assign("payment",$payments);
        $this->display('payment');
    }

	public function advert(){
        $position = array(
            'top' => '顶部浮层',
            'bottom' => '底部浮层',
            'window' => '弹出浮层',
        );
        $con  = array();
        $con['type'] = array("in",array('layer'));
        $count = M("adv") -> where($con) -> count();
        $page = $this -> page($count,20);
        $advlist = M("adv") -> where($con) -> limit($page->firstRow.','.$page->listRows) -> order("endtime desc ") ->select();
        foreach($advlist as &$a){
            $a['state'] = '0';
            if($a['endtime'] < time()){
                $a['statestr'] = "停用";
            }elseif($a['starttime'] < time() && $a['endtime']  > time()){
            	$a['state'] = '1';
                $a['statestr'] = "启用";
            }else{
                $a['statestr'] = "未开始";
            }
            $a['positionstr'] = $position[$a['position']];
        }
        $this -> assign("advlist",$advlist);
		$this->display('advert');
    }

    public function personnel(){
        $this->redirect('Special/itemList');
    }


    
    /**
     * 清除缓存
     */
    public function  clean_cache(){
        $cleans = I('post.cleans','','htmlspecialchars');
        $model = new SettingModel();
        if(!empty($cleans)){
            foreach($cleans as $v){
                $filename = CacheHelper::getCachePre($v);
                switch ($filename){
					case 'get_special_item':
                        S($filename,null);
						break;
                    case  'good_all_category': 
                        S($filename,null);
                        S('good_show_category',null);
                        //F('all_category_tree',null);
                        break;
                    case 'web_setting':
						flushWebSetingCache();
                        break;
                    case  'spu':
                        $goods_commoin_ids  =M('goods_common')->field('goods_common_id')->limit(false)->select();
                        if(!empty($goods_commoin_ids)){
                            $goods_model = D('Goods');
                            foreach($goods_commoin_ids as $v){
                                $goods_model->delSpuCache($v['goods_common_id']);
                            }
                        }
                        break;
                default:
                        break;
                }
            }
		\Common\Helper\LogHelper::adminLog(array('content'=>var_export($_POST,true),'action'=>'商城基本信息设置-清除缓存','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            $this->success('处理成功',U('Setting/clean_cache'));
        }else{
            $this->display();
        } 
        
    }
}