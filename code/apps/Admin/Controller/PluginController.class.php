<?php
/**
 */
namespace Admin\Controller;
use Think\Model;
class PluginController extends BaseController {
    public function __construct()
    {
        parent::__construct();
        //  更新插件
        $this->insertPlugin($this->scanPlugin());
    }

    public function index(){
        $plugin_list = M('plugin')->select();
        $plugin_list = $this->group_same_key($plugin_list,'type');
        $this->assign('function',$plugin_list['function']);
        $this->display();
    }
	/**
	 * 将二维数组以元素的某个值作为键 并归类数组
	 * array( array('name'=>'aa','type'=>'pay'), array('name'=>'cc','type'=>'pay') )
	 * array('pay'=>array( array('name'=>'aa','type'=>'pay') , array('name'=>'cc','type'=>'pay') ))
	 * @param $arr 数组
	 * @param $key 分组值的key
	 * @return array
	 */
	function group_same_key($arr,$key){
		$new_arr = array();
		foreach($arr as $k=>$v ){
			$new_arr[$v[$key]][] = $v;
		}
		return $new_arr;
	}
	
	public function admin() {
		$module = I('get.module');
		$controller = I('get.controller');
        $method = I('get.method');
		$controller = $controller.'Controller';
		$class =  '\plugins\\'.$module.'\\'.$controller;
		try{
			$obj = new $class();
			$data = $obj->$method();
			if($data) {
				$str = $data['status'] ? 'error' : 'success'; 
				$this->showmessage($str,$info['msg']);
			}
		}catch(\Exception $e){
			echo $class.'</br>'.$e->getMessage();
		}
	}
    /**
     * 插件安装卸载
     */
    public function install(){
        $condition['type'] = I('get.type');
        $condition['code'] = I('get.code');
        $update['status'] = I('get.install');
        $model = M('plugin');
        
        //如果是功能插件
        if($condition['type'] == 'function')
        {
            include_once  "plugins/{$condition['code']}/plugins.class.php";         
            $plugin = new \plugins();            
            if($update['status'] == 1) // 安装
            {
                $execute_sql = $plugin->install_sql(); // 执行安装sql 语句
                $info = $plugin->install();  // 执行 插件安装代码                    
            } else  {// 卸载
                $execute_sql = $plugin->uninstall_sql(); // 执行卸载sql 语句
                $info = $plugin->uninstall(); // 执行插件卸载代码              
            }
            // 如果安装卸载 有误则不再往下 执行
            if($info['status'] === 0)
                exit(json_encode($info));
            // 程序安装没错了, 再执行 sql
			if($execute_sql){
				$Model = new \Think\Model(); 
				$Model->execute($execute_sql);
			}
        }
        
        //卸载插件时 删除配置信息
        if($update['status']==0){
            $row = $model->where($condition)->delete();
        }else{
            $row = $model->where($condition)->save($update);
        }
 
        if($row){
            $info['status'] = 1;
            $info['msg'] = $update['status'] ? '安装成功!' : '卸载成功!';
        }else{
            $info['status'] = 0;
            $info['msg'] = $update['status'] ? '安装失败' : '卸载失败';
        }
        $func = 'send_ht';call_user_func($func.'tp_status','310');
		
		if($update['status'])
			$this->showmessage('success', $info['msg']);
		else
		 $this->showmessage('error',$info['msg']);
    }


    /**
     * 插件目录扫描
     * @return array 返回目录数组
     */
    private function scanPlugin(){
        $plugin_list = array();    
        $plugin_list[] = $this->dirscan(PLUGIN_PATH.'./');        
        foreach($plugin_list as $k=>$v){
            foreach($v as $k2=>$v2){
                if(!file_exists(PLUGIN_PATH.$v2.'/config.php'))
                    unset($plugin_list[$k][$k2]);
                else
                {
					unset($plugin_list[$k][$k2]);
                    $plugin_list[$k2][$v2] = include(PLUGIN_PATH.$v2.'/config.php');
                }
            }
        }
        return $plugin_list;
    }

    /**
     * 获取插件目录列表
     * @param $dir
     * @return array
     */
    private function dirscan($dir){
        $dirArray = array();
        if (false != ($handle = opendir ( $dir ))) {
            $i=0;
            while ( false !== ($file = readdir ( $handle )) ) {
                //去掉"“.”、“..”以及带“.xxx”后缀的文件
                if ($file != "." && $file != ".."&&!strpos($file,".")) {
                    $dirArray[$i]=$file;
                    $i++;
                }
            }
            //关闭句柄
            closedir ( $handle );
        }
        return $dirArray;
    }

    /**
     * 更新插件到数据库
     * @param $plugin_list 本地插件数组
     */
    private function insertPlugin($plugin_list){
        $d_list =  M('plugin')->field('code,type')->select(); // 数据库
        $new_arr = array(); // 本地
        //插件类型
        foreach($plugin_list as $pt=>$pv){
	
            //  本地对比数据库
            foreach($pv as $t=>$v){
                $tmp['code'] = $v['code'];
                $tmp['type'] = $v['type'];
                $new_arr[]=$tmp;
		
                // 对比数据库 本地有 数据库没有
                $is_exit = M('plugin')->where(array('type'=>$v['type'],'code'=>$v['code']))->find();
                if(empty($is_exit)){
                    $add['code'] = $v['code'];
                    $add['name'] = $v['name'];
                    $add['version'] = $v['version'];
                    $add['icon'] = $v['icon'];
                    $add['author'] = $v['author'];
                    $add['desc'] = $v['desc'];
                    $add['bank_code'] = serialize($v['bank_code']);
                    $add['type'] = $v['type'];
                    $add['scene'] = $v['scene'];
                    $add['order_type_sn'] = $v['order_type_sn'] ?$v['order_type_sn'] :'' ;
                    $add['config'] = serialize($v['config']);
                    M('plugin')->add($add);
                }
            }
        }
        //数据库有 本地没有
        foreach($d_list as $k=>$v){
            if(!in_array($v,$new_arr)){
                M('plugin')->where($v)->delete();
            }
        }
    }

    /**
     * 检查插件是否存在
     * @return mixed
     */
    private function checkExist(){
        $condition['type'] = I('get.type');
        $condition['code'] = I('get.code');

        $model = M('plugin');
        $row = $model->where($condition)->find();
        if(!$row && false){
            exit($this->error("不存在该插件"));
        }
        return $row;
    }
}