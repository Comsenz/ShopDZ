<?php
namespace Admin\Controller;
use Think\Controller;
class NoticeController extends BaseController {
	public function template(){
        $con = array();
        $count = M("message") -> where('web_content !=1') -> count();
        $page  = new \Common\Helper\PageHelper($count,20);
        $this->assign('page',$page->show());
        $notice = M("message") -> where('web_content !=1') -> limit($page->firstRow.','.$page->listRows)->select();
        $this ->assign("notice",$notice);
        $this->display('lists');
    }

    public function noticeedit(){
        $model = M('message');
        if(IS_POST){
            $content = I('post.content');
            $title = I('post.title');
            $id = I('post.id');
        
            $status =  I('post.status') == 'on' ? '1' : '2';
            $in_array = array('web_content'=>$content,'web_states'=>$status,'web_title'=>$title);
          //  p($in_array);exit;
            if( $model->where(array('id'=>$id))->save($in_array) === false){
                    $this->showmessage('error','更新失败',U("Admin/Notice/template"));
            }else{
                    $this->showmessage('success','更新成功',U("Admin/Notice/template"));
            }
        }else{
            $id = I('get.id');
            $id = intval($id);
            if(empty($id)){
                $this->showmessage('error','参数错误！',U("Admin/Notice/template"));
            }
            $notice_data = $model->where(array('id'=>$id))->find();

            $this -> assign("notice",$notice_data);
            $this->display();
        }
    }

    public function send_notice(){
        $model = M('sms_cron');
        if(IS_POST){
            $content = I('post.content');
            $title = I('post.title');
            $members= I('post.members');
            $send_type= 'member_username';//I('post.send_type');
            $type =  I('post.type') == 'on' ? 3 : 1;
            $mids = array();
            if($type == 1 && in_array($send_type,array('member_id','member_username'))){
                $member_arr = array_unique(explode(',',$members));
                foreach($member_arr as $v){
                    $member_info = M('member')->where(array($send_type => $v))->find();
                    if($member_info)
                        $mids[] = $member_info['member_id'];
                }
            }elseif($type == 3){
                $mids = array();
            }
            $message_ismore = 0;
            if($type == 3 || count($member_arr) > 1){
                $message_ismore = 1;
            }
            $in_array = array(
                    'mids'=>serialize($mids),
                    'title'=>$title,
                    'message'=>$content,
                    'from_id'=> 0,
                    'from_name'=>'',
                    'dateline'=>time(),
                    'type'=>$type,
                    'cron_status'=>0,
                    'crontime'=>0,
                    'parent_sid'=>0,
                    'message_ismore'=>$message_ismore
                );
            if( $model->add($in_array) === false){
                $this->showmessage('error','发送失败！',U("Admin/Notice/send_notice"));
            }else{
                $this->showmessage('success','发送成功',U("Admin/Notice/send_notice"));
            }
        }else{
            $this->display();
        }
    }

}