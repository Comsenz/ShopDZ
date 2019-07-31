<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Wechat\WxapiController;
use Common\Wechat\WxcardController;
use Admin\Model\SettingModel;
use Common\Helper\CacheHelper;
class CouponController extends BaseController {

    public function __construct(){
        parent::__construct();
        $setting = S('web_setting');
        $site_name = $setting['shop_name'];
        $this->assign('site_name',$site_name);
    }
	public function lists(){
        if(I('get.q')){
            $where = 'rpacket_t_title like "%'.I('get.q').'%"';
            $this->assign('q',I('get.q'));
        }
        $count = M('redpacket_template')->where($where)->count();
        // $page = $this -> page($count,10);
        $page  = new \Common\Helper\PageHelper($count,20);
        $this->assign('page',$page->show());
        $list = M('redpacket_template')->where($where)->limit($page->firstRow.','.$page->listRows)->order('rpacket_t_id DESC')->select();
        $data = array();
        foreach ($list as $val) {
        	$i['id'] = $val['rpacket_t_id'];
            $i['rpacket_t_title'] = $val['rpacket_t_title'];
            $i['rpacket_t_points'] = $val['rpacket_t_points'] ? $val['rpacket_t_points'] : 0;
            $i['rpacket_t_price'] = $val['rpacket_t_price'];
            $i['rpacket_t_limit'] = $val['rpacket_t_limit'];
            $i['rpacket_t_end_datetext'] = date('Y-m-d', $val['rpacket_t_end_date']);
            $i['state'] = $val['rpacket_t_state'];// == 1 ? "有效" : ($val['rpacket_t_state'] ==2 ? "失效" : "");
			$i['wx'] = !empty($val['rpacket_t_wx_card_id']) ? '微信': '商城';
            $data[$val['rpacket_t_id']] = $i;
        }
        $this -> assign("list",$data);
		$this->display('lists');
    }

   

    public function add(){
		if($this->checksubmit()){
			$start_time = I('post.start') ? strtotime(I('post.start')) : null;
			$end_time = I('post.end') ? strtotime(I('post.end')) : null;
			$price = I('post.rpacket_t_price');
			$limit = I('post.rpacket_t_limit');
			$total = I('post.rpacket_t_total');
			$points = I('post.rpacket_t_points');
            $wx = I('post.rpacket_t_wx',0,'intval');
            $share = I('post.rpacket_t_share',0,'intval');
            $color = I('post.rpacket_t_color','Color010');

			if(empty($start_time) || empty($end_time)){
				$this -> showmessage('error','优惠券过期时间，必须大于启用时间！');
			}
			if($limit <= $price){
				$this -> showmessage('error','订单金额必须大于优惠券面值！');
			}
            if(intval(I('post.rpacket_t_eachlimit')) > 50){
                $this -> showmessage('error','最高不能超过50张！');
            }
            if(intval(I('post.rpacket_t_eachlimit')) <= 0){
                $this -> showmessage('error','每人限领不能为0！');
            }
			if($total<1) $this -> showmessage('error','请正确填写优惠券数量！');
			if($_POST){
				$data['rpacket_t_title'] = I('post.rpacket_t_title');
				$data['rpacket_t_price'] = intval($price);
				$data['rpacket_t_limit'] = intval($limit);
				$data['rpacket_t_total'] = intval($total);
				$data['rpacket_t_points'] = intval($points);
				$data['rpacket_t_eachlimit'] = I('post.rpacket_t_eachlimit') ? I('post.rpacket_t_eachlimit') : '0';
				$data['rpacket_t_state'] = I('post.rpacket_t_state');
				$data['rpacket_t_start_date'] = strtotime(date('Y-m-d',$start_time));
				$data['rpacket_t_end_date'] = strtotime(date('Y-m-d',$end_time)) + 86400 - 1;
				$data['rpacket_t_save_time'] = time();

				$data['rpacket_t_desc'] = '';
				$data['rpacket_t_adminid'] = '';
				$data['rpacket_t_giveout'] = 0;
				$data['rpacket_t_used'] = 0;
				$data['rpacket_t_updatetime'] = time();
				$data['rpacket_t_recommend'] = 0;
				$data['rpacket_t_gettype'] = 0;
				$data['rpacket_t_isbuild'] = 1;
				$data['rpacket_t_mgradelimit'] = 0;
                $data['rpacket_t_wx'] = $wx;
                $data['rpacket_t_share'] = $share;
                $data['rpacket_t_color'] = $color;
			}
			//同步为微信卡券
			if($data['rpacket_t_wx'] == 1){
				if($data['rpacket_t_end_date'] < $data['rpacket_t_save_time']){
					$this -> showmessage('error','过期时间必须大于卡券创建时间，否则无法创建微信卡券！');
				}
				$setingMoudel = D('setting');
				$wx_AppID = $setingMoudel->getSetting('wx_AppID');
				$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
				if(!empty($wx_AppID) && !empty($wx_AppSecret)){
					$wxCardId = $this->sendWxCard($data);
					if($wxCardId ){
						$data['rpacket_t_wx_card_id'] = $wxCardId;
					}
				}else{
					$this -> showmessage('error','请在微信设置中配置微信相关参数！');
				}

			}
			$result = M('redpacket_template')->add($data);
			\Common\Helper\LogHelper::adminLog(array('content'=>var_export($data,true),'action'=>'新增优惠券模板','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
			if($result){
				$this -> showmessage('success','发布成功！',U('Admin/Coupon/lists'));
			}else{
				$this -> showmessage('error','发布失败，请稍候重试！');
			}
		}else{
			$this->display();
		}
    }
	
    public function delete(){
    	$data = array();
    	if(I('request.checksubmit') == 'yes'){
    		if(I('request.redpacket_t_id')){
    			$tid = intval(I('request.redpacket_t_id'));
    			$template = M('redpacket_template');
    			$t_where = array('rpacket_t_id' => $tid);
    			$result = $template->where($t_where)->find();
    			if($result){
    				$del_result = $template->where($t_where)->delete();
    				if($del_result){
						//同步删除微信卡券
						if(!empty($result['rpacket_t_wx_card_id'])){
							$setingMoudel = D('setting');
							$wx_AppID = $setingMoudel->getSetting('wx_AppID');
							$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
							$wxapi = new WxcardController($wx_AppID,$wx_AppSecret);
							 $wxapi->delCard($result['rpacket_t_wx_card_id']);
						}

    					/*
    					 $update['rpacket_state'] = 3;
    					 $where = array(
    							'rpacket_t_id'	=> $tid,
    							'rpacket_state'	=> 1,
    						);
						//删除优惠券是否更新用户未使用优惠券
    					//$rep = M('redpacket')->where($where)->save($update);
    					*/
                        \Common\Helper\LogHelper::adminLog(array('content'=>var_export(I('request.'),true),'action'=>'删除优惠券模板','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
    					$data['status'] = 1;
                        $data['info'] = '删除成功！';
    				}else{
    					$data['status'] = 2;
    					$data['info'] = '删除失败！';
    				}
    			}else{
    				$data['status'] = 2;
    				$data['info'] = '优惠券模板不存在！';
    			}
    		}else{
    			$data['status'] = 2;
    			$data['info'] = '参数错误！';
    		}
    	}else{
    		$data['status'] = 2;
    		$data['info'] = '参数错误！';
    	}
    	$this -> ajaxReturn($data);
    }

    public function edit(){
		$tid = intval(I('param.id'));

    	if($tid ){
			if($this->checksubmit()){
				$start_time = I('post.start') ? strtotime(I('post.start')) : null;
				$end_time = I('post.end') ? strtotime(I('post.end')) : null;
				$price = I('post.rpacket_t_price');
				$limit = I('post.rpacket_t_limit');
				$total = I('post.rpacket_t_total');
				$points = I('post.rpacket_t_points');
                $wx = I('post.rpacket_t_wx');
                $share = I('post.rpacket_t_share',0,'intval');
                $color = I('post.rpacket_t_color','Color010');
				if(empty($start_time) || empty($end_time)){
					$this -> showmessage('error','请正确填写开始时间或结束时间！');
				}
				if($limit <= $price){
					$this -> showmessage('error','请正确填写优惠券面值和使用限额！');
				}
                if(intval(I('post.rpacket_t_eachlimit')) > 50){
                    $this -> showmessage('error','最高不能超过50张！');
                }
                if(intval(I('post.rpacket_t_eachlimit')) <= 0){
                    $this -> showmessage('error','每人限领不能为0！');
                }
				if($total<1) $this -> showmessage('error','请正确填写优惠券数量！');
				if($_POST){
					$data['rpacket_t_title'] = I('post.rpacket_t_title');
					$data['rpacket_t_price'] = intval($price);
					$data['rpacket_t_limit'] = intval($limit);
					$data['rpacket_t_total'] = intval($total);
					$data['rpacket_t_points'] = intval($points);
					$data['rpacket_t_eachlimit'] = I('post.rpacket_t_eachlimit') ? I('post.rpacket_t_eachlimit') : '0';
					$data['rpacket_t_state'] = I('post.rpacket_t_state');
                    $data['rpacket_t_start_date'] = strtotime(date('Y-m-d',$start_time));
                    $data['rpacket_t_end_date'] = strtotime(date('Y-m-d',$end_time)) + 86400 - 1;
					$data['rpacket_t_save_time'] = time();
                    $data['rpacket_t_share'] = $share;
                    $data['rpacket_t_color'] = $color;
				}
				$orderdate = M('redpacket_template')->where( array('rpacket_t_id' => $tid))->find();
				$t_where = array('rpacket_t_id' => $tid);
				if(!empty($orderdate['rpacket_t_wx_card_id'])){
					$data['rpacket_t_wx'] = 1;
				}
				if(!empty($orderdate['rpacket_t_wx_card_id'])){
					if($data['rpacket_t_start_date'] > $orderdate['rpacket_t_start_date'] || $data['rpacket_t_end_date'] < $orderdate['rpacket_t_end_date']){
						$this -> showmessage('error','卡券修改失败！确认开始时间必须小于原卡券时间和结束时间必须大于原卡券结束时间！');
					}
				}
				$result = M('redpacket_template')->where($t_where)->save($data);
				//同步修改微信卡券
				if($result){
					\Common\Helper\LogHelper::adminLog(array('content'=>var_export($data,true),'action'=>'修改优惠券模板','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
					if(!empty($orderdate['rpacket_t_wx_card_id'])){
						$setingMoudel = D('setting');
						$wx_AppID = $setingMoudel->getSetting('wx_AppID');
						$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
						if(!empty($wx_AppID) && !empty($wx_AppSecret)){
							$updateCard = $this->editCardInfo($data,$orderdate['rpacket_t_wx_card_id']);
							if($updateCard ){
								if($data['rpacket_t_total'] != $orderdate['rpacket_t_total']){
									//更新微信优惠券库存
									$total = $data['rpacket_t_total'] - $orderdate['rpacket_t_total'];
									$modify = $this->editCardTotal($orderdate['rpacket_t_wx_card_id'],$total);
									if($modify){
										$this -> showmessage('success','卡券信息同步成功！');
									}else{
										$this -> showmessage('error','卡券库存同步成功！库存同步失败！');
									}
								}else{
									$this -> showmessage('success','卡券信息同步成功！');
								}
							}else{
								$this -> showmessage('error','卡券信息同步失败！');
							}
						}else{
							$this -> showmessage('error','请在微信设置中配置微信相关参数！');
						}
					}else{
						$this -> showmessage('success','修改成功！',U('Admin/Coupon/lists'));
					}
				}else{
					$this -> showmessage('error','修改失败，请稍候重试！');
				}
			}else{
				$template = M('redpacket_template');
				$t_where = array('rpacket_t_id' => $tid);
				$data = $template->where($t_where)->find();
				// $data['start_time'] = date('Y-m-d',$data['rpacket_t_start_date']);
				// $data['end_time'] = date('Y-m-d',$data['rpacket_t_end_date']);
				$this->assign('info',$data);
				$this->display();
			}
    	}else{
    		$this -> showmessage('error','参数错误，请重试！');
    	}
    }

    public function show(){
    	if(I('get.id')){
    		$tid = I('get.id');
			$template = M('redpacket_template');
			$t_where = array('rpacket_t_id' => $tid);
			$data = $template->where($t_where)->find();
			$data['start_time'] = date('Y/m/d H:i:s',$data['rpacket_t_start_date']);
			$data['end_time'] = date('Y/m/d H:i:s',$data['rpacket_t_end_date']);
			$this->assign('info',$data);
    		$this->display();
    	}else{
    		$this -> showmessage('error','参数错误，请重试！');
    	}
    }

    public function grant(){
    	if(I('post.submitcheck') == "yes"){
    		$tel = I('post.tel');
    		$id = intval(I('post.rpacket_t_id'));
    		$member_info = M('member')->where(array('member_mobile' => $tel))->find();
    		$temp = M('redpacket_template')->where(array('rpacket_t_id' => $id))->find();
    		if(!$member_info){
    			$this -> showmessage('error','对不起，用户不存在',U('Admin/Coupon/lists'));
    		}
    		$redpacket = D('redpacket');
    		$number =  $redpacket->get_eachlimit($member_info['member_id'],$id);
    		if($temp['rpacket_t_eachlimit'] != 0 && $number >= $temp['rpacket_t_eachlimit']){
    			$this -> showmessage('error','对不起，该用户领取数量已超过此优惠券限制！',U('Admin/Coupon/lists'));
    		}
    		$result = $redpacket->give_redpacket($member_info,$temp);
    		if(!$result){
    			$this -> showmessage('error','优惠券发放失败，请重试！',U('Admin/Coupon/lists'));
    		}
            \Common\Helper\LogHelper::adminLog(array('content'=>var_export(I('post.'),true),'action'=>'新增优惠券模板','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
    		$this -> showmessage('success','发放成功！',U('Admin/Coupon/lists'));
    	}else{
			$where = I('get.id') ? "rpacket_t_id=".I('get.id')." AND " :'';
    		$template = M('redpacket_template');
    		$now = time();
    		$temp = $template->where($where."rpacket_t_state=1 AND rpacket_t_total > rpacket_t_giveout AND rpacket_t_end_date>$now AND rpacket_t_start_date<$now")->select();
    		if(!empty($temp)){
    			$this->assign('list',$temp);
    			$this->display();
    		}else{
    			$this -> showmessage('error','对不起，此张优惠券已经不能发放了哟~~',U('Admin/Coupon/lists'));
    		}
    	}
    }

    public function rep_list(){
    	if(!in_array(I('get.status'),array('giveout','used')) || !intval(I('get.id'))){
    		$this -> showmessage('error','参数错误！');
    	}
    	$where = array(
    			'rpacket_t_id'	=> intval(I('get.id')),
    		);
		if(I('get.status') == 'used'){
    		$where['rpacket_state'] = 2;
    	}
		$count = M('redpacket')->where($where)->count();
		if($count == 0){
			$this -> showmessage('success','没有符合条件的优惠券哦~~~');
		}
    	//分页
    	$page  = new \Common\Helper\PageHelper($count,20);
        $this->assign('page',$page->show());

    	$list = M('redpacket')->where($where)->limit($page->firstRow.','.$page->listRows)->select();
    	foreach($list as $lk => $lv){
    		$list[$lk]['end_date'] = date('Y/m/d H:i:s',$lv['rpacket_end_date']);
    		$list[$lk]['active_date'] = date('Y/m/d H:i:s',$lv['rpacket_active_date']);
            if($lv['rpacket_state'] == 3){
                $list[$lk]['used_date'] = '已过期';
                $list[$lk]['order_id'] = '已过期';
            }else{
        		$list[$lk]['used_date'] = $lv['rpacket_used_date'] == 0 ? '未使用' : date('Y/m/d H:i:s',$lv['rpacket_used_date']);
        		$list[$lk]['order_id'] = $lv['rpacket_order_id'] == 0 ? '未使用' : $lv['rpacket_order_id'];
            }
    	}

        $template = M('redpacket_template')->where(array('rpacket_t_id'=>intval(I('get.id'))))->find();
        if(I('get.status') == 'giveout'){
        	$template['status'] = '已发放';
        }elseif(I('get.status') == 'used'){
        	$template['status'] = '已使用';
        }
        $this->assign('temp',$template);
        $this->assign('list',$list);
        $this->display();

    }

    public function rep_delete(){
    	$data = array();
    	if(I('request.checksubmit') != 'yes' || !intval(I('request.id')) ){
    		$this -> error('参数错误！');
    	}
    	$info = M('redpacket')->where(array('rpacket_id' => intval(I('request.id'))))->find();
    	if(!$info){
    		$this -> error('没有这张优惠券！');
    	}
    	$del_result = M('redpacket')->where(array('rpacket_id' => intval(I('request.id'))))->delete();
		if(!empty($info['rpacket_t_wx_card_id'])){
			$setingMoudel = D('setting');
			$wx_AppID = $setingMoudel->getSetting('wx_AppID');
			$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
			$wxapi = new WxcardController($wx_AppID,$wx_AppSecret);
			$wxcard = $wxapi->setUserCardUnavailable($info['rpacket_t_wx_card_id'],$info['rpacket_code']);
		}
    	if($del_result){
            if(I('request.point_back') == 1){
                $points_info = M('redpacket_template')->where(array('rpacket_t_id' => $info['rpacket_t_id']))->find();
                if($points_info['rpacket_t_points'] != 0){
                    $member_points = M('member')->where(array('member_id' => $info['rpacket_owner_id']))->setInc('member_points',$points_info['rpacket_t_points']);
                }
            }
            \Common\Helper\LogHelper::adminLog(array('content'=>var_export(I('request.'),true),'action'=>'删除用户优惠券','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
    		$this -> success('删除成功！');
    	}else{

    		$this -> error('删除失败！');
    	}
    }

    //会员优惠券列表
    public function member_packet(){
    	$where = '1=1';
    	$timetype_arr = array('rpacket_end_date','rpacket_active_date','rpacket_used_date');
    	$field_arr = array('rpacket_owner_name','rpacket_title');
    	$timetype = in_array(I('get.timetype'),$timetype_arr) ? I('get.timetype') : '';
    	$field = in_array(I('get.field'),$field_arr) ? I('get.field') : '';

    	//时间类搜索条件
    	if(I('get.min_date') || I('get.max_date')){
    		if(!empty($timetype)){
    			$where .= I('get.min_date') ? " AND $timetype > ".strtotime(I('get.min_date')) : '';
   				$where .= I('get.max_date') ? " AND $timetype < ".strtotime(I('get.max_date')) : ''; 			
    		}
    	}

    	//文字类搜索条件
    	if(I('get.q')){
    		if(!empty($field)){
    			$where .= " AND $field LIKE '%".I('get.q')."%'";
    		}
    	}
        //用户id搜索
        if(intval(I('get.member_id'))){
            $where .=" AND rpacket_owner_id=".intval(I('get.member_id'));
        }

		$count = M('redpacket')->where($where)->count();
    	//分页
    	$page  = new \Common\Helper\PageHelper($count,20);
        $this->assign('page',$page->show());
    	$list = M('redpacket')->where($where)->limit($page->firstRow.','.$page->listRows)->order('rpacket_used_date DESC')->select();
    	foreach($list as $lk => $lv){
    		$list[$lk]['end_date'] = date('Y-m-d',$lv['rpacket_end_date']);
    		$list[$lk]['active_date'] = date('Y-m-d',$lv['rpacket_active_date']);
            if($lv['rpacket_state'] == 3){
                $list[$lk]['used_date'] = '已过期';
                $list[$lk]['order_id'] = '已过期';
            }else{
        		$list[$lk]['used_date'] = $lv['rpacket_used_date'] == 0 ? '未使用' : date('Y/m/d H:i:s',$lv['rpacket_used_date']);
        		$list[$lk]['order_id'] = $lv['rpacket_order_id'] == 0 ? '未使用' : $lv['rpacket_order_id'];
            }
    	}
        $this->assign('list',$list);
        $this->display();

    }
    public function member_packet_info(){
    	if(!I('get.id')){
    		$this -> showmessage('error','参数错误！');
    	}
    	$rpacket_id = intval(I('get.id'));
    	$rpacket_info = M('redpacket')->where(array('rpacket_id' => $rpacket_id))->find();
    	//信息处理
    	$rpacket_info['start_date'] = $rpacket_info['rpacket_start_date'] == 0 ? '':date('Y-m-d',$rpacket_info['rpacket_start_date']);
    	$rpacket_info['end_date'] = $rpacket_info['rpacket_end_date'] == 0 ? '':date('Y-m-d',$rpacket_info['rpacket_end_date']);
        if($rpacket_info['rpacket_state'] == 3){
            $rpacket_info['used_date'] = '已过期';
            $rpacket_info['order_id'] = '已过期';
        }else{
        	$rpacket_info['used_date'] = $rpacket_info['rpacket_used_date'] == 0 ? '未使用' : date('Y-m-d',$rpacket_info['rpacket_used_date']);
        	$rpacket_info['order_id'] = $rpacket_info['rpacket_order_id'] == 0 ? '未使用' : $rpacket_info['rpacket_order_id'];
        }
    	$rpacket_info['active_date'] = $rpacket_info['rpacket_active_date'] == 0 ? '' : date('Y-m-d',$rpacket_info['rpacket_active_date']);
    	$this->assign('info',$rpacket_info);
    	$this->display();
    }
	public function  editCardInfo($data,$rpacket_t_wx_card_id){
		$logo_img = $this->getUploadImg();
		$setingMoudel = D('setting');
		$shop_name =  $setingMoudel->getSetting('shop_name') ? $setingMoudel->getSetting('shop_name') : '商城';
		$get_limit = $data['rpacket_t_eachlimit'] == 0 ? 50 : $data['rpacket_t_eachlimit'];
		$card = array(
				'card_id'=>$rpacket_t_wx_card_id,
       			'general_coupon'=> array(
					'base_info'=> array(
							'logo_url' => $logo_img,
							'code_type' => 'CODE_TYPE_NONE',
							'title' => $data['rpacket_t_title'],
							'color' => $data['rpacket_t_color'],
							'description' => '该卡券仅限'.$shop_name.'的商品使用。',
							'date_info' => array (
									'type' => 'DATE_TYPE_FIX_TIME_RANGE',
									'begin_timestamp' =>  $data['rpacket_t_start_date'],
									'end_timestamp' => $data['rpacket_t_end_date'],
							),
							'get_limit' => $get_limit,
					),
				)
		);
		$wx_AppID = $setingMoudel->getSetting('wx_AppID');
		$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
		$wxapi = new WxcardController($wx_AppID,$wx_AppSecret);
		$wxcard = $wxapi->editCardInfo($card);
		if($wxcard['errcode']==0){
			return true;
		}else{
			return false;
		}


	}
	public function editCardTotal($id,$total){
		if(!empty($id) && !empty($total)){
			$setingMoudel = D('setting');
			$wx_AppID = $setingMoudel->getSetting('wx_AppID');
			$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
			$wxapi = new WxcardController($wx_AppID,$wx_AppSecret);
			$wxcard = $wxapi->editCardTotal($id,$total);
			if($wxcard){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	//同步微信卡券
	public function sendWxCard($data){
		$logo_img = $this->getUploadImg();
		$setingMoudel = D('setting');
		$shop_name =  $setingMoudel->getSetting('shop_name') ? $setingMoudel->getSetting('shop_name') : '商城';
		$get_limit = $data['rpacket_t_eachlimit'] == 0 ? 50 : $data['rpacket_t_eachlimit'];
		$card= array (
				'card' => array (
						'card_type' => 'GENERAL_COUPON',
						'general_coupon' => array (
								'base_info' => array (
										'logo_url' => $logo_img,
										'brand_name' =>$shop_name, 											//商户名字,字数上限为12个汉字
										'code_type' => 'CODE_TYPE_NONE',
										'title' => $data['rpacket_t_title'],    							//卡券名，字数上限为9个汉字
										'sub_title' =>'购买订单满'.$data['rpacket_t_limit'].'元可使用',		//副标题
										'color' => $data['rpacket_t_color'],            					//颜色
										'description' => '该卡券仅限'.$shop_name.'的商品使用。',

										'date_info' => array (
												'type' => 'DATE_TYPE_FIX_TIME_RANGE',
												'begin_timestamp' =>  $data['rpacket_t_start_date'],
												'end_timestamp' => $data['rpacket_t_end_date'],
												),
										'sku' => array ('quantity' => $data['rpacket_t_total'],),
										'get_limit' => $get_limit,											//领取限制
										'use_custom_code' => true,
										'bind_openid' => false,
										'can_share' => true,
										'can_give_friend' => true,
										'location_id_list' => array (
										),

										'center_title' => '立即使用',
										'center_url'=>SITE_URL .'api.php/Member/wxlogin',
										'center_sub_title' =>$shop_name,
										'promotion_url_name' => '更多优惠',
										'promotion_url' =>SITE_URL .'wap/coupon-wxlist.html?type=wx',
										'source' => $shop_name,
								),
								'default_detail' =>'此券只在有效期内使用有效！',
						)
				)
		);
		$wx_AppID = $setingMoudel->getSetting('wx_AppID');
		$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
		$wxapi = new WxcardController($wx_AppID,$wx_AppSecret);
		$wxcard = $wxapi->createCardTemplate($card);
		return $wxcard;

	}

	public function getUploadImg(){
		$setingMoudel = D('setting');
		$wx_cardimg = $setingMoudel->getSetting('wx_cardimg');
		if(empty($wx_cardimg)){
			$shareimg = $setingMoudel->getSetting('wx_shareimg');
			$wx_shareimg = !empty($shareimg) ? $shareimg : $setingMoudel->getSetting('shop_logo');
			$wx_AppID = $setingMoudel->getSetting('wx_AppID');
			$wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
			$wxapi = new WxcardController($wx_AppID,$wx_AppSecret);
			$file = APP_ROOT.'/data/Attach/'.$wx_shareimg;
			$upload_logo = $wxapi->uploadCardLogo($file);

			if($upload_logo){
				$model = new SettingModel();
				$post['wx_cardimg'] = $upload_logo;
				$model -> Settings($post);
				return $upload_logo;
			}else{
				return $wx_shareimg;
			}
		}else{
			return $wx_cardimg;
		}

	}
	//拼团列表
	function activelist() {
		  $this->redirect('Group/activelist');
	}
	
	/**
	 * 礼品管理 列表页
	 */
	public function gift_list(){
	    $where  = array();
	    $where['gift_state'] = 1;
	    $model  = M('gift');
	    $count = $model->where($where)->count();
	    $page = new \Common\Helper\PageHelper($count,20);
	    $lists = $model->where($where)->order('gift_id desc')->limit($page->firstRow.','.$page->listRows)->select();
	    if(!empty($lists)){
    	    foreach($lists as $k => $v) {
    	        $lists[$k]['gift_image']  =   C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['gift_image'];  
    	    }
	    }
	    $this->assign('lists',$lists);
	    $this->assign('page',$page->show());
	    $this->display();    
	}
	
	
	/**
	 * 礼品添加
	 */
	public function gift_add(){
	    if(!empty($_POST)){
	        $insert = array();
	        $insert['gift_name']    =   I('gift_name','','trim,htmlspecialchars,addslashes');
	        $insert['gift_image']    =  I('gift_image','','trim,htmlspecialchars,addslashes');
	        $insert['gift_storage']    =  I('gift_storage',0,'intval,abs');
	        $insert['gift_price']    =  I('gift_price',0.00,'price_format');
	        $insert['gift_state']    =  1;
	        $insert['credits_exchange']    =  $_POST['credits_exchange'] == 'on' ? 1 : 0;
	        if($insert['credits_exchange']){
    	        $insert['gift_points']    =  I('gift_points',0,'intval,abs');
    	        $insert['limit_num']    =  I('limit_num',0,'intval,abs');
    	        $insert['start_time']    =  I('start_time','','trim,htmlspecialchars,addslashes');
    	        $insert['end_time']    =  I('end_time','','trim,htmlspecialchars,addslashes');
    	        if(!$insert['start_time']){
    	            $this->error('请选择开始兑换时间');
    	        }
    	        if(!$insert['end_time']){
    	            $this->error('请选择结束兑换时间');
    	        }
    	        $insert['start_time']   =  strtotime(date('Y-m-d 00:00:00',strtotime($insert['start_time'])));
    	        $insert['end_time']   =   strtotime(date('Y-m-d 23:59:59',strtotime($insert['end_time'])));
    	        if($insert['start_time'] > $insert['end_time']){
    	            $this->error('开始时间应小于结束时间');
    	        }
    	        $insert['goods_common_id']    =  I('goods_common_id',0,'intval,abs');
    	        if(!$insert['goods_common_id']){
    	            $this->error('请选择正确的关联商品');
    	        }
    	        $insert['goods_name']    =   I('goods_name','','trim,htmlspecialchars,addslashes');
	        }else{
	            $insert['gift_points']    =  0;
	            $insert['limit_num']    = 0;
	            $insert['start_time']    =  0;
	            $insert['end_time']    = 0;
	            $insert['goods_common_id']    =  0;
	            $insert['goods_name']    =   '';
	        }
	        $insert['listorder']    =  I('listorder',0,'intval,abs');
	        $insert['gift_description']    = I('gift_description','','trim,htmlspecialchars,addslashes');
	        $insert['add_time']    = time();
	        $insert['update_time']    =  time();
	        $insert_status =   M('gift')->add($insert);
	        if($insert_status){
	            \Common\Helper\LogHelper::adminLog(array('content'=>$insert['gift_name'].'id:'.$insert_status,'action'=>'礼品添加添加','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
	            $this->success('操作成功');
	        }else{
	            $this->error('插入失败');
	        }
	    }else{
	        $allcategory = D('GoodsCategory')->getAllCategoryTree();
	        $category = array();
	        foreach($allcategory as $k=>$v) {
	            $category[$v['gc_id']] = $v;
	        }
	        unset($allcategory);
	        $this->assign('category',json_encode($category));
	        $this->display();
	    }
	}
	
	public function  gift_edit(){
	    $gift_id =   I('gift_id',0,'intval,abs');
	    if(!$gift_id){
	        $this->error('参数错误');
	    }
	    $gift_info  = M('gift')->where(array('gift_id'=>$gift_id))->find();
	    if(empty($gift_info)){
	        $this->error('参数错误');
	    }
	    if(IS_POST){
	    $update = array();
	        $update['gift_name']    =   I('gift_name','','trim,htmlspecialchars,addslashes');
	        $update['gift_image']    =  I('gift_image','','trim,htmlspecialchars,addslashes');
	        $update['gift_storage']    =  I('gift_storage',0,'intval,abs');
	        $update['gift_price']    =  I('gift_price',0.00,'price_format');
	        $update['gift_state']    =  1;
	        $update['credits_exchange']    =  $_POST['credits_exchange'] == 'on' ? 1 : 0;
	        if($update['credits_exchange']){
    	        $update['gift_points']    =  I('gift_points',0,'intval,abs');
    	        $update['limit_num']    =  I('limit_num',0,'intval,abs');
    	        $update['start_time']    =  I('start_time','','trim,htmlspecialchars,addslashes');
    	        $update['end_time']    =  I('end_time','','trim,htmlspecialchars,addslashes');
    	        if(!$update['start_time']){
    	            $this->error('请选择开始兑换时间');
    	        }
    	        if(!$update['end_time']){
    	            $this->error('请选择结束兑换时间');
    	        }
    	        $update['start_time']   =  strtotime(date('Y-m-d 00:00:00',strtotime($update['start_time'])));
    	        $update['end_time']   =   strtotime(date('Y-m-d 23:59:59',strtotime($update['end_time'])));
    	        if($update['start_time'] > $update['end_time']){
    	            $this->error('开始时间应小于结束时间');
    	        }
    	        $update['goods_common_id']    =  I('goods_common_id',0,'intval,abs');
    	        if(!$update['goods_common_id']){
    	            $this->error('请选择正确的关联商品');
    	        }
    	        $update['goods_name']    =   I('goods_name','','trim,htmlspecialchars,addslashes');
	        }else{
	            $update['gift_points']    =  0;
	            $update['limit_num']    = 0;
	            $update['start_time']    =  0;
	            $update['end_time']    = 0;
	            $update['goods_common_id']    =  0;
	            $update['goods_name']    =   '';
	        }
	        $update['listorder']    =  I('listorder',0,'intval,abs');
	        $update['gift_description']    = I('gift_description','','trim,htmlspecialchars,addslashes');
	        $update['update_time']    =  time();
	        $update_status =   M('gift')->where(array('gift_id'=>$gift_id))->save($update);
	        if($update_status){
	            \Common\Helper\LogHelper::adminLog(array('content'=>'id:'.$gift_id,'action'=>'礼品修改','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
	            $this->success('操作成功');
	        }else{
	            $this->error('插入失败');
	        }    
	    }else{
	        $allcategory = D('GoodsCategory')->getAllCategoryTree();
	        $category = array();
	        foreach($allcategory as $k=>$v) {
	            $category[$v['gc_id']] = $v;
	        }
	        unset($allcategory);
	        $this->assign('category',json_encode($category));
	        $this->assign('info',$gift_info);
	        $this->display();  
	    }  
	}

	/**
	 * 礼品删除
	 */
	public function  gift_delete(){
	    $gift_id =   I('gift_id',0,'intval,abs');
	    if(!$gift_id){
	        $this->error('参数错误');
	    }
	    $update = array();
	    $update['gift_state'] =  0;
	    $update['update_time'] = time();
	    $update_status  =  M('gift')->where(array('gift_id'=>$gift_id))->save($update);
	    if($update_status){
	        \Common\Helper\LogHelper::adminLog(array('content'=>'id:'.$gift_id,'action'=>'礼品删除','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
	        $this->success('操作成功');
	    }else{
	        $this->error('操作失败');
	    }
	}

}