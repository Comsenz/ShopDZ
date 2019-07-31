<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Wechat\WxapiController;
use Common\Wechat\WxRefundUser;
use Admin\Model\SettingModel;
use Common\Helper\CacheHelper;
use  Common\Service\SmsService;
use Think\View;
class WxController extends BaseController {
    public function __construct(){
        parent::__construct();

    }

   
    public function  wxloginOp(){
        $model = new SettingModel();
        $post = I("post.");
        $url = I("post.url")  ? U("Admin/Wx/".I('post.url'),array('active'=>I('get.active'))) : U("Admin/Wx/wxloginOp");
        if(I("post.sub")){
            unset($post['sub']);
			$wx_AppID = $post['wx_AppID'];
			$wx_AppSecret = $post['wx_AppSecret'];
			if( strstr($wx_AppID,'****'))
				unset($post['wx_AppID']);
			if( strstr($wx_AppSecret,'****'))
				unset($post['wx_AppSecret']);

            $post['wx_login'] = isset($post['wx_login']) && $post['wx_login'] == 'on' ? 'on' : 'off';

            unserizepost($post);
            $result = $model -> Settings($post);
            \Common\Helper\LogHelper::adminLog(array('content'=>var_export($post,true),'action'=>'第三方登录','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            if ($result){
                $this->showmessage('success','保存成功',$url);
            }else {
                $this->showmessage('error','保存失败',$url);
            }
        }
        //获取配置
        $config = $model -> getSettings();
		$config['wx_AppID'] = \Common\Helper\ToolsHelper::simpleShow($config['wx_AppID']);
		$config['wx_AppSecret'] = \Common\Helper\ToolsHelper::simpleShow($config['wx_AppSecret']);
        $this -> assign("config",$config);
        $this->display('third');

    }

    /*
     * 生成微信菜单
     */
    public function menuSend(){
        $setingMoudel = D('setting');
        $wx_AppID = $setingMoudel->getSetting('wx_AppID');
        $wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
        $wxapi = new WxapiController($wx_AppID,$wx_AppSecret);
        $wx_menu = M('wx_menu');
        $menu = $wx_menu->where(array('lid'=>0))->limit(3)->order("displayorder DESC")->select();
        $array = array();
        foreach($menu as $k=>$v){
            $count = $wx_menu->where(array('lid'=>$v['id']))->count();
            if($count >0){
                $array[$k]['name'] = $v['name'];
                $tmp = array();
                $menu_child = $wx_menu->where(array('lid'=>$v['id']))->limit(5)->order("displayorder DESC")->select();
                foreach ($menu_child as $ck=>$cv){
                    $tmp[$ck]['type'] = $cv['type'] == 'click' ? 'click' : 'view';
                    $tmp[$ck]['name'] =$cv['name'];
                    if($cv['type'] == 'click') {
                        $tmp[$ck]['key'] = $cv['keywords'];
                    }else{
                        $tmp[$ck]['url'] = trim($cv['url']);
                    }
                }
                $array[$k]['sub_button'] =  $tmp;
            }else{
                $array[$k]['type'] = $v['type'] == 'click' ? 'click' : 'view';;
                $array[$k]['name'] = $v['name'];;
                if($v['type'] == 'click') {
                    $array[$k]['key'] = $v['keywords'];
                }else{
                    $array[$k]['url'] = trim($v['url']);
                }
            }
        }
        $data['button'] = $array;
       
        $rs = $wxapi->createMenu($data);
        if($rs['errcode'] == 0){
            $this->showmessage('success','生成菜单成功',U("Admin/Wx/menuOp"));
        }else{
            $this->showmessage('error','生成菜单失败！',U("Admin/Wx/menuOp"));
        }
    }
    /*
     * 微信菜单列表
     */
    public function  menuOp(){
        $wx_menu = M('wx_menu');
        $data = array();
        $menu = $wx_menu->where(array('lid'=>'0'))->limit(3)->order("displayorder DESC")->select();
        $n = 0;
        if(!empty($menu)){
            foreach($menu as $k=>$v){
                $data[$n]['name'] = $v['name'];
                $data[$n]['id'] = $v['id'];
                $data[$n]['lid'] = $v['lid'];
                $data[$n]['order']= $v['displayorder'];
                $data[$n]['type']= $v['type'] == 'view' ? '链接' : '关键词';
                $data[$n]['url'] = $v['url'];
                $isset = substr($v['url'],0,7);
                if($isset != 'http://' ){
                    $scheme = strtolower($_SERVER['HTTPS']) == "on" ? 'https' : 'http';
                    $v['url'] = $scheme.'://'.$v['url'];
                }
                $data[$n]['keywords'] = $v['type'] == 'view' ? $v['url'] : $v['keywords'];
                $data[$n]['level'] = 0;
                $menu_next =  $wx_menu->where(array('lid'=>$v['id']))->order("displayorder DESC")->limit(5)->select();
                $counmu = $n +  $wx_menu->where(array('lid'=>$v['id']))->count();
                $n ++;
                foreach($menu_next as $key=>$value){
                    $data[$n]['name'] = $value['name'];
                    $data[$n]['id'] = $value['id'];
                    $data[$n]['lid'] = $value['lid'];
                    $data[$n]['order'] = $value['displayorder'];
                    $data[$n]['type']  =  $value['type']  == 'view' ? '链接' : '关键词';
                    $data[$n]['url'] = $value['url'];
                    $data[$n]['keywords']= $value['type'] == 'view' ? $value['url'] : $value['keywords'];
                    $data[$n]['level'] = 1;
                    if($n == $counmu ){
                        $data[$n]['last'] = 1;
                    }
                    $n++;
                }
            }
        }
        $scheme = strtolower($_SERVER['HTTPS']) == "on" ? 'https' : 'http';
        $wxloginUrl = $scheme.'://'.$_SERVER['HTTP_HOST'].'/api.php/Member/wxlogin?type=menu';

        $this -> assign("menu",$data);
        $this -> assign("wxlogin",$wxloginUrl);
        $this->display('menulist');
    }
    /*
     * 微信菜单添加/编辑
     */
    public function  menuedit(){
        $wx_menu = M('wx_menu');
        if(IS_POST){
            $id = I('post.id') ? I('post.id') : '';
            $lid = I('post.lid') ? I('post.lid') : 0;
            $order = I('post.order') ? I('post.order') : 0;
            $name = I('post.name') ? I('post.name') : $this->showmessage('error','菜单名称不能为空',U("Admin/Wx/menuedit",array('id'=>$id)));
            $type = I('post.type');
            $url =  I('post.url');
            $keywords =  I('post.keywords');
            if($type == 'click' && empty($keywords)){
                $this->showmessage('error','关键词不能为空！',U("Admin/Wx/menuedit",array('id'=>$id)));
            }
            if($type == 'view' && empty($url)){

                $this->showmessage('error','链接不能为空！',U("Admin/Wx/menuedit",array('id'=>$id)));
            }else{
                $isset = substr($url,0,7);
                if($isset != 'http://' ){
                    $scheme = strtolower($_SERVER['HTTPS']) == "on" ? 'https' : 'http';
                    $url = $scheme.'://'.$url;
                }
            }

            if(empty($id)) {
                if ($lid == 0) {
                    $levelone = $wx_menu->where(array('lid' => 0))->count();
                    if ($levelone >= 3) {
                        $this->showmessage('error', '一级菜单不能超过三个！', U("Admin/Wx/menuedit",array('id'=>$id)));
                    }
                } else {
                    $leveltwo = $wx_menu->where(array('lid' => $lid))->count();;
                    if ($leveltwo >= 5) {
                        $this->showmessage('error', '二级菜单不能超过五个！', U("Admin/Wx/menuedit",array('id'=>$id)));
                    }
                }
            }
            $in_array = array('id'=>$id,'lid'=>$lid,'name'=>$name,'type'=>$type,'url'=>htmlspecialchars_decode($url),'keywords'=>$keywords,'displayorder'=>$order);
            if(empty($id)){//添加菜单
                array_splice($in_array,0,1);
                if($wx_menu->add($in_array)){
                    $this->showmessage('success','保存成功',U("Admin/Wx/menuOp"));
                }else{
                    $this->showmessage('error','保存失败',U("Admin/Wx/menuOp"));
                }
            }else{//更新菜单
                if( $wx_menu->where(array('id'=>$id))->save($in_array) === false){
                    $this->showmessage('error','更新失败',U("Admin/Wx/menuOp"));
                }else{
                    $this->showmessage('success','更新成功',U("Admin/Wx/menuOp"));

                }
            }

        }else{
            $menu = array('type'=>'view');
            $id = I('get.id');
            $type = I('get.type') == 'edit' ?  'edit' : 'add';
            if(!empty($id) && $type == 'add'){
                $menu['lid'] = $id;
            }
            $menu_main = $wx_menu->where(array('lid'=>'0'))->order("'order' DESC")->limit(3)->select();
            if(!empty($id) && $type !='add'){
                $menu = $wx_menu->where(array('id'=>$id))->find();
            }
            $scheme = strtolower($_SERVER['HTTPS']) == "on" ? 'https' : 'http';
            $wxloginUrl = $scheme.'://'.$_SERVER['HTTP_HOST'].'/api.php/Member/wxlogin?type=menu';
            $this -> assign("wxlogin",$wxloginUrl);
            $this -> assign("menu",$menu);
            $this -> assign("type",$type);
            $this -> assign("menu_main",$menu_main);
            $this->display('menuedit');
        }
    }

    public function delmenu(){
        $id = I('post.id');
        $lid = I('post.lid');
        $wx_menu = M('wx_menu');
        if(!empty($id)){
            if($lid == 0){
                $del_one = $wx_menu->where(array('id'=>$id))->delete();
                $del_two = $wx_menu->where(array('lid'=>$id))->delete();
                if($del_one){
                    $data =array('code'=>0,'message'=>'删除成功');
                    $this->ajaxReturn($data);
                }else{
                    $data =array('code'=>1,'message'=>'删除失败');
                    $this->ajaxReturn($data);
                }
            }else{
                if($wx_menu->where(array('id'=>$id))->delete()){
                    $data =array('code'=>0,'message'=>'删除成功');
                    $this->ajaxReturn($data);
                }else{
                    $data =array('code'=>1,'message'=>'删除失败');
                    $this->ajaxReturn($data);
                }
            }
        }else{
            $data =array('code'=>1,'message'=>'参数错误');
            $this->ajaxReturn($data);
        }
    }

    public function wxShare(){
        $model = new SettingModel();
        if($this->checksubmit()){
            $post = I("post.");
            unset($post['form_submit']);
            $post['wx_share'] = isset($post['wx_share']) && $post['wx_share'] == 'on' ? 'on' : 'off';
            $post['wx_cardimg'] = ''; //清空微信卡券缓存图片
            unserizepost($post);
            $result = $model -> Settings($post);
            \Common\Helper\LogHelper::adminLog(array('content'=>var_export($post,true),'action'=>'商城基本信息设置','username'=>$this->admin_user['username'],'uid'=>$this->admin_user['uid']));
            if ($result){
                $this->showmessage('success','保存成功',U("Admin/Wx/wxShare"));
            }else {
                $this->showmessage('error','保存失败',U("Admin/Wx/wxShare"));
            }
            $this -> ajaxReturn(array("status"=>1,'data'=>1,'info'=>'设置成功'));
        }
        //获取配置
        $config = $model -> getSettings();
       // P($config);
        $this -> assign("config",$config);
        $this->display('wxshare');
    }

    public function wxRespons(){
        //获取配置
        $model = new SettingModel();
        $config = $model -> getSettings();
        $this -> assign("config",$config);
        $this->display('response');
    }
    public function wxTextImg(){

    }


    public function keywordsOp()
    {
        $con = array();
        $search_text = I('post.search_text');
        if(!empty($search_text)){
            $con['keyword'] = array('like',"%$search_text%");
            $this ->assign("search_text",$search_text);
        }
        $count = M("wx_keywords") -> where($con) -> count();
        $page  = new \Common\Helper\PageHelper($count,20);
        $this->assign('page',$page->show());
        $keywordslist = M("wx_keywords") -> where($con) -> limit($page->firstRow.','.$page->listRows) ->order('id DESC')->select();
        foreach($keywordslist as $k=>$v){
            $keywordslist[$k]['isimg'] = $v['isimg'] == 0 ? '文本回复': '图文回复';
            $keywordslist[$k]['content'] =htmlspecialchars_decode($v);
        }

        $this ->assign("keywords",$keywordslist);
        $this->display('keywordslist');

    }

    public function keywordsedit(){
        $keyword = M('wx_keywords');
        if(IS_POST){
            $id = I('post.id') ? I('post.id') : '';
            $type = I('post.isimg') ? 1 : 0;
            $tid = I('post.tid') && $type ? I('post.tid') : 0;
            $keywords =  I('post.keywords');
            $content = I('post.content');
            $states =  I('post.states') == 'on' ? '1' : '0';
            if(empty($keywords)){
                $this->showmessage('error','关键词不能为空！');
            }
            if($type == '0' && empty($content)){
                $this->showmessage('error','回复内容不能为空！',U("Admin/Wx/keywordsedit",array('id'=>$id)));
            }
            if($type == '1' && !$tid){
                $this->showmessage('error','素材不能为空！',U("Admin/Wx/keywordsedit",array('id'=>$id)));
            }
            $in_array = array('id'=>$id,'tid'=>$tid,'content'=>htmlspecialchars_decode($content),'isimg'=>$type,'keyword'=>$keywords,'states'=>$states);

            if(empty($id)){//添加关键词
                $is_ext = $keyword->where(array('keyword'=>$keywords))->find();
                if(!$is_ext){
                    array_splice($in_array,0,1);
                    if($keyword->add($in_array)){
                        $this->showmessage('success','保存成功',U("Admin/Wx/keywordsOp"));
                    }else{
                        $this->showmessage('error','保存失败',U("Admin/Wx/keywordsOp"));
                    }
                }else{
                    $this->showmessage('error','关键词已存在！请重新添加。');
                }
            }else{//更新关键词
                if( $keyword->where(array('id'=>$id))->save($in_array) === false){
                    $this->showmessage('error','更新失败',U("Admin/Wx/keywordsOp"));
                }else{
                    $this->showmessage('success','更新成功',U("Admin/Wx/keywordsOp"));
                }
            }

        }else{
            $keywords_data= array('isimg'=>'0','states'=>1);
            $id = I('get.id');
            $type = I('get.type') == 'edit' ?  'edit' : 'add';
            if(!empty($id) && $type == 'add'){
                $menu['lid'] = $id;
            }
           // $keywords = $keyword->where(array('lid'=>'0'))->order("'order' DESC")->limit(3)->select();
            if(!empty($id)){
                $keywords_data = $keyword->where(array('id'=>$id))->find();
            }
            $img_text = M('wx_imgtext')->select();
            $this -> assign("keywords",$keywords_data);
            $this -> assign("type",$type);
            $this -> assign("img_text",$img_text);
          //$this -> assign("menu_main",$menu_main);
            $this->display('keywordsedit');
        }

    }
    public  function delkeywords(){
        $id = I('post.id');
        $wx_menu = M('wx_keywords');
        if(!empty($id)){
            if($wx_menu->where(array('id'=>$id))->delete()){
                $data =array('code'=>0,'message'=>'删除成功');
                $this->ajaxReturn($data);
            }else{
                $data =array('code'=>1,'message'=>'删除失败');
                $this->ajaxReturn($data);
            }
        }else{
            $data =array('code'=>1,'message'=>'参数错误');
            $this->ajaxReturn($data);
        }
    }
    public function message(){
      
        $con = array();
        $count = M("message") -> where('wx_content !=1') -> count();
        $page  = new \Common\Helper\PageHelper($count,20);
        $this->assign('page',$page->show());
        $wx_message = M("message") -> where('wx_content !=1') -> limit($page->firstRow.','.$page->listRows)->select();
        
        $this ->assign("message",$wx_message);

        $this->display('messagelist');
    }
    public function messageedit(){
        $message_model = M('message');
        if(IS_POST){
            $content = I('post.content');
            $id = I('post.id');
            $states =  I('post.states') == 'on' ? '1' : '0';
            $in_array = array('wx_content'=>$content,'wx_states'=>$states);
            if( $message_model->where(array('id'=>$id))->save($in_array) === false){
                    $this->showmessage('error','更新失败',U("Admin/Wx/message"));
            }else{
                    $this->showmessage('success','更新成功',U("Admin/Wx/message"));
            }
        }else{
            $id = I('get.id');
            $message_data = $message_model->where(array('id'=>$id))->find();
            $this -> assign("message",$message_data);
            $this->display('messageedit');
        }

    }
    public function sendmss(){
        $setingMoudel = D('setting');
        $wx_AppID = $setingMoudel->getSetting('wx_AppID');
        $wx_AppSecret = $setingMoudel->getSetting('wx_AppSecret');
        $wxapi = new WxapiController($wx_AppID,$wx_AppSecret);
        $wxapi->send_wx_notice('108','payment_success');
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

    public function imgText(){
        $modename = I('post.modename');
        $where = array();
        if(!empty($modename)){
            $where['modename'] = array('like',"%$modename%");
        }
        $count = M("user_getdata") -> where($where) -> count();
        $page  = new \Common\Helper\PageHelper($count,20);
        $this->assign('page',$page->show());
        $getdatalist = M("wx_imgtext") -> where($where) -> limit($page->firstRow.','.$page->listRows) -> order('dataline DESC') ->select();
        $this -> assign("modename",$modename);
        $this -> assign("getdatalist",$getdatalist);
        $this->display('imgtextlist');

    }
    public function imgTextAdd(){
        if(IS_POST){
            $updateid = I('post.id');
            $modename = I('post.modename');
            if(empty($modename)){
                $this->showmessage('error','模块名称不能为空！');
            }
            $url= I('post.url');
            $new_img = I('post.new_img');
            $order = I('post.order');
            $title = I('post.title');
            $data = array();
            $data['modename'] = $modename;
            $shopdate = array();
            if(!empty($title)){
                $i = 0;
                foreach($title as $k=>$v){
                    $shopdate[$k]['Title'] = htmlspecialchars_decode($v);
                    $shopdate[$k]['Description'] = '';
                    $shopdate[$k]['order'] = $order[$k];
                    $http_url = $url[$k];
                    $isset = substr($url[$k],0,7);
                    if($isset != 'http://' ){
                        $scheme = strtolower($_SERVER['HTTPS']) == "on" ? 'https' : 'http';
                        $http_url = $scheme.'://'.$url[$k];
                    }
                    $shopdate[$k]['Url'] = htmlspecialchars_decode($http_url);
                    $shopdate[$k]['PicUrl'] =  str_replace(C('TMPL_PARSE_STRING.__ATTACH_HOST__'),'',$new_img[$k]);
                    $i++;
                }
                $shopdate = $this->multi_array_sort($shopdate,'order');
                $data['content'] = serialize($shopdate);
                $data['imgnum'] = $i;
                $data['dataline'] = time();
                if(!empty($updateid)){
                    $where = array('tid'=>$updateid);
                    if(M('wx_imgtext')->where($where)->save($data)){
                        $this->showmessage('success','更新成功！',U('Wx/imgText'));
                    }else{
                        $this->showmessage('error','更新失败！');
                    }
                }else{
                    if(M('wx_imgtext')->add($data)){
                        $this->showmessage('success','操作成功！',U('Wx/imgText'));
                    }else{
                        $this->showmessage('error','操作失败！');
                    }
                }
            }else{
                $this->showmessage('error','请选择商品！');
            }
        }else{
            $this->display('imgtextadd');
        }

    }
    public function delImgText(){
        $id = I('post.id');
        $wx_menu = M('wx_imgtext');
        if(!empty($id)){
            if($wx_menu->where(array('tid'=>$id))->delete()){
                $data =array('code'=>0,'message'=>'删除成功');
                $this->ajaxReturn($data);
            }else{
                $data =array('code'=>1,'message'=>'删除失败');
                $this->ajaxReturn($data);
            }
        }else{
            $data =array('code'=>1,'message'=>'参数错误');
            $this->ajaxReturn($data);
        }
    }
    public function imgTextEdit(){
        $tid = I('get.id');
        $wx_imgtext = M('wx_imgtext');
        $info = $wx_imgtext->where(array('tid'=>$tid))->find();

        $imginfo = array();
        $textimg = '';
        if($info){
            $imginfo['modename'] = $info['modename'];
            $imginfo['imgnum'] = $info['imgnum'];
            $imginfo['id'] = $info['tid'];
            $textimg = unserialize($info['content']);
            foreach($textimg as $k=>$v){
                $textimg[$k]['PicUrl'] =  C('TMPL_PARSE_STRING.__ATTACH_HOST__').$v['PicUrl'];
            }
        }
        $this->assign('imginfo',$imginfo);
        $this->assign('textimg',$textimg);
        $this->display('imgtextedit');
    }

    /*
 * 上传文件、、
 * */
    public function upLogoFiletext(){
        $post = array();
        if($_FILES){

            $file = uploadfile($_FILES,'avatar');
            if($file['file']){
                $allurl =C('TMPL_PARSE_STRING.__ATTACH_HOST__').$file['file'];
                $this -> ajaxReturn(array('status'=>1,'data'=>$file['file'],'allurl'=>$allurl),'json');
            }
        }
    }
    function multi_array_sort($multi_array, $sort_key, $sort = SORT_DESC) {
        if (is_array($multi_array)) {
            foreach ($multi_array as $row_array) {
                if (is_array($row_array)) {
                    $key_array[] = $row_array[$sort_key];
                } else {
                    return FALSE;
                }
            }
        } else {
            return FALSE;
        }
        array_multisort($key_array, $sort, $multi_array);
        return $multi_array;
    }
}