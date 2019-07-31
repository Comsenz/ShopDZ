<?php
namespace App\Controller;
use Think\Controller;
class PluginController extends BaseController {
    public function index(){
        $code = I('get.code');
        $action = I('get.action');
		$data = M('Plugin')->where("code='{$code}'")->find();
	   if(!$data)
            $this->returnJson(1,$code.'插件不存在');
		$data['config_value'] = unserialize($data['config_value']); // 配置反序列化
        include_once  "plugins/{$code}/{$code}.class.php";
		try{
			$class = new \ReflectionClass($code);
			$instance = $class->newInstanceArgs(array($data));
			return $instance->$action();
		}catch(\Exception $e){
			echo "plugins/{$code}/{$code}.class.php ".'</br>'.$e->getMessage();
		}
    }
}