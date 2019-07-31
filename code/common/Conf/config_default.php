<?php
return array(
	//'配置项'=>'配置值'
	//数据库相关配置
		'LAYOUT_ON'=>false,
		'DB_TYPE'               =>  'mysql',     // 数据库类型
		'DB_HOST'               =>  '', // 服务器地址
		'DB_NAME'               =>  '',          // 数据库名
		'DB_USER'               =>  '',      // 用户名
		'DB_PWD'                =>  '',          // 密码
		'DB_PORT'               =>  '',        // 端口
		'DB_PREFIX'             =>  'pre_',    // 数据库表前缀
		'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
		'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
		'DB_DEBUG'              =>  false,  // 数据库调试模式 3.2.3新增
		'SYS_LOG'				=> true,
		'TMPL_ACTION_ERROR' => 'Public:error',
		'TMPL_ACTION_SUCCESS' => 'Public:success',
		'SYS_LOG' =>true,
		'DB_RW_SEPARATE'=>true,// 数据库读写是否分离 主从式有效
		'DB_DEPLOY_TYPE'        => 1,// 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
	'LAYOUT_PATH'=>'./apps/Admin/View/',
	'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'layout',
	'SESSION_AUTO_START'    =>  true, 
	'TMPL_TEMPLATE_SUFFIX'  =>  '.php',     // 默认模板文件后缀
	'TMPL_DENY_PHP'         =>  false, // 默认模板引擎是否禁用PHP原生代码
	'HTML_CACHE_ON'     =>    true, // 开启静态缓存
	'URL_MODEL'=>1,  
	'VAR_FILTERS'=>'htmlspecialchars,trim',
		'TMPL_PARSE_STRING'  =>array(
				'__PUBLIC__' => '/static', // 更改默认的/Public 替换规则
				'__UPLOAD__' =>RUNTIME_PATH.'Attach/', //附件路径前缀
				'__ATTACH_HOST__' => 'http://'.$_SERVER['HTTP_HOST'].'/data/Attach/',
				'DEFAULT_LOGIN_IMAGE'   => '/static/img/login.jpg',
				'DEFAULT_GOODS_IMAGE'   => '/static/img/default_goods_image.gif',
				'DEFAULT_MEMBER_IMAGE'   => '/static/img/default_user_image.png',
				'DEFAULT_LOGO_IMAGE'   => '/static/img/logo.png',
		),
	// 
		'logistics'=>dirname(__FILE__)."/logistics.php",
		'ORDER_TIME'=>3600,//一个小时

);