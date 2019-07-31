<?php
define('IN_SHOPDZ',true);
/**
 */
use Think\Model;
/**
 * 插件需要执行的方法 逻辑定义  
 */

class plugins extends Model{
    public $tableName = 'plugin'; // 插件表            
    /**
     * 析构流函数
     */
    public function  __construct() {
        parent::__construct();
    }
    /**
     * 安装          
     */
    function install(){
        $tpshop_version = include(APP_ROOT.'/source/shopdz_version'); // TPshop 版本
        $config = include APP_ROOT.'plugins/group/config.php'; // 当前插件适合哪些版本
        $config['version'] = explode(',', $config['version']);

        if(!in_array(SHOPDZ_VERSION, $config['version']))
        {
            $info['status'] = 1;
            $info['msg'] = '版本不兼容';
            return $info;                   
        }
    }
    
    /**
     *  卸载插件
     */
    function uninstall(){
       // 执行卸载代码  比如删除文件  将安装时 复制好的 插件文件  一个个删除掉   
    }
 
    /**
     * 安装 sql 语句
     * 这里的sql 可以的文件导入 也可以直接写死 插件要用到的新表 数据等
     */
    function install_sql(){
        $sql = file_get_contents(APP_ROOT.'plugins/group/install.sql'); 
        return $sql;
    }
    /**
     * 卸载 sql 语句
     * 把插件相关的数据删除掉.
     */
    function uninstall_sql(){
        $sql = file_get_contents(APP_ROOT.'plugins/group/uninstall.sql'); 
        return $sql;
    }
}