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
//禁止引用外部xml实体
libxml_disable_entity_loader(true);
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
$root  =   dirname(__FILE__).'/';
define('APP_DEBUG', True);
define('DIR_SECURE_FILENAME', 'index.html');
// 定义应用目录
define('APP_ROOT', $root);
define('APP_PATH', './apps/');
define('THINK_PATH', './source/');
define('RUNTIME_PATH', './data/');
define('COMMON_PATH', './common/');
define('PLUGIN_PATH',$root.'plugins/');
define('BIND_MODULE','App');
define('TIMESTAMP',time());
define('BUILD_CONTROLLER_LIST','Index,User,Login');
// 引入ThinkPHP入口文件
require './source/TP.php';
// 亲^_^ 后面不需要任何代码了 就是如此简单