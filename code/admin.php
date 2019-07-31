<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
$root  =   dirname(__FILE__).'/';
define('IN_SHOPDZ', TRUE);
define('RUNTIME_PATH', $root.'data/');
define('APP_ROOT', $root);
$lockfile = RUNTIME_PATH.'./install.lock';
$self = htmlspecialchars(get_script());
$site_path = substr($php_self, 0, strrpos($self, '/'));
$scheme = !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == "on" ? 'https' : 'http';
$siteurl = htmlspecialchars($scheme.'://'.$_SERVER['HTTP_HOST'].$site_path.'/');
function get_script() {
    global $php_self;
    if(!isset($php_self)){
        $scriptName = basename($_SERVER['SCRIPT_FILENAME']);
        if(basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
            $php_self = $_SERVER['SCRIPT_NAME'];
        } else if(basename($_SERVER['PHP_SELF']) === $scriptName) {
            $php_self = $_SERVER['PHP_SELF'];
        } else if(isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
            $php_self = $_SERVER['ORIG_SCRIPT_NAME'];
        } else if(($pos = strpos($_SERVER['PHP_SELF'],'/'.$scriptName)) !== false) {
            $php_self = substr($_SERVER['SCRIPT_NAME'],0,$pos).'/'.$scriptName;
        } else if(isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'],$_SERVER['DOCUMENT_ROOT']) === 0) {
            $php_self = str_replace('\\','/',str_replace($_SERVER['DOCUMENT_ROOT'],'',$_SERVER['SCRIPT_FILENAME']));
            $php_self[0] != '/' && $php_self = '/'.$php_self;
        }
    }
    return $php_self;
}
if(!file_exists($lockfile)){
	@header('Location: '. $site_url.'install');
	exit;
}
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', True);
define('DIR_SECURE_FILENAME', 'index.html');
// 定义应用目录
define('APP_PATH', $root.'apps/');
define('THINK_PATH', $root.'source/');
define('COMMON_PATH', $root.'common/');
define('BIND_MODULE','Admin');
define('TIMESTAMP',time());
define('BUILD_CONTROLLER_LIST','Index,User,Login');
define('PLUGIN_PATH',$root.'plugins/');
// 引入ThinkPHP入口文件
require $root.'source/shopdz_version.php';
require './source/TP.php';
// 亲^_^ 后面不需要任何代码了 就是如此简单