<?php
namespace Admin\Controller;
use Think\Controller;
class WechatpublicController extends BaseController {
	public function setting(){
        $this->show('setting');
		//$this->display('setting');
    }
     
	public function member(){
        $this->show('member');
		//$this->display('member');
    }
     
	public function menu(){
        $this->show('menu');
		//$this->display('menu');
    }
     
	public function autoreply(){
        $this->show('autoreply');
		//$this->display('autoreply');
    }
     
	public function push(){
        $this->show('push');
		//$this->display('push');
    }
     
	public function sellgoods(){
        $this->show('sellgoods');
		//$this->display('sellgoods');
    }
     
	public function Order(){
        $this->show('Order');
		//$this->display('Order');
    }
     
	public function interaction(){
        $this->show('interaction');
		//$this->display('interaction');
    }
}