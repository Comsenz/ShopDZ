<?php

/**
 *      [ShopDZ] (C)2001-2099 Comsenz-service Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: install_var.php 2016/8/10 15:00  seeley $
 */


if(!defined('IN_COMSENZ')) {
	exit('Access Denied');
}

define('SOFT_NAME', 'SHOPDZ');

define('INSTALL_LANG', 'SC_UTF8');

define('CONFIG', './common/Conf/config.php');

$sqlfile = ROOT_PATH.((file_exists(ROOT_PATH.'./install/data/install_dev.sql')) ? './install/data/install_dev.sql' : './install/data/install.sql');
$lockfile = ROOT_PATH.'./data/install.lock';

@include ROOT_PATH.CONFIG;

define('CHARSET', 'utf-8');
define('DBCHARSET', 'utf8');

define('ORIG_TABLEPRE', 'pre_');

define('METHOD_UNDEFINED', 255);
define('ENV_CHECK_RIGHT', 0);
define('ERROR_CONFIG_VARS', 1);
define('SHORT_OPEN_TAG_INVALID', 2);
define('INSTALL_LOCKED', 3);
define('DATABASE_NONEXISTENCE', 4);
define('PHP_VERSION_TOO_LOW', 5);
define('MYSQL_VERSION_TOO_LOW', 6);
define('DBNAME_INVALID', 15);
define('DATABASE_ERRNO_2003', 16);
define('DATABASE_ERRNO_1044', 17);
define('DATABASE_ERRNO_1045', 18);
define('DATABASE_CONNECT_ERROR', 19);
define('TABLEPRE_INVALID', 20);
define('CONFIG_UNWRITEABLE', 21);
define('ADMIN_USERNAME_INVALID', 22);
define('ADMIN_EMAIL_INVALID', 25);
define('ADMIN_EXIST_PASSWORD_ERROR', 26);
define('ADMININFO_INVALID', 27);
define('LOCKFILE_NO_EXISTS', 28);
define('TABLEPRE_EXISTS', 29);
define('ERROR_UNKNOW_TYPE', 30);
define('ENV_CHECK_ERROR', 31);
define('UNDEFINE_FUNC', 32);
define('MISSING_PARAMETER', 33);
define('LOCK_FILE_NOT_TOUCH', 34);

$func_items = array(function_exists('mysql_connect') ? 'mysql_connect' : 'mysqli_connect', 'gethostbyname', 'file_get_contents', 'xml_parser_create');

$filesock_items = array('fsockopen', 'pfsockopen', 'stream_socket_client', 'curl_init');

$env_items = array
(
	'os' => array('c' => 'PHP_OS', 'r' => 'notset', 'b' => 'unix'),
	'php' => array('c' => 'PHP_VERSION', 'r' => '5.1', 'b' => '5.3'),
	'attachmentupload' => array('r' => 'notset', 'b' => '2M'),
	'gdversion' => array('r' => '1.0', 'b' => '2.0'),
	'diskspace' => array('r' => '10M', 'b' => 'notset'),
);

$dirfile_items = array
(

	'config' => array('type' => 'file', 'path' => CONFIG),
	'config_dir' => array('type' => 'dir', 'path' => './common/Conf'),
	'data' => array('type' => 'dir', 'path' => './data'),
	'cache' => array('type' => 'dir', 'path' => './data/Cache'),
	'avatar' => array('type' => 'dir', 'path' => './data/avatar'),
	'attach' => array('type' => 'dir', 'path' => './data/Attach'),
	'data' => array('type' => 'dir', 'path' => './data/Data'),
	'temp' => array('type' => 'dir', 'path' => './data/Temp'),
	'attach_common' => array('type' => 'dir', 'path' => './data/Attach/common'),
	'logs' => array('type' => 'dir', 'path' => './data/Logs'),
);


$form_db_init_items = array
(
	'dbinfo' => array
	(
		'dbhost' => array('type' => 'text', 'required' => 1, 'reg' => '/^.+$/', 'value' => array('type' => 'var', 'var' => 'dbhost')),
		'dbname' => array('type' => 'text', 'required' => 1, 'reg' => '/^.+$/', 'value' => array('type' => 'var', 'var' => 'dbname')),
		'dbuser' => array('type' => 'text', 'required' => 0, 'reg' => '/^.*$/', 'value' => array('type' => 'var', 'var' => 'dbuser')),
		'dbpw' => array('type' => 'text', 'required' => 0, 'reg' => '/^.*$/', 'value' => array('type' => 'var', 'var' => 'dbpw')),
		'tablepre' => array('type' => 'text', 'required' => 0, 'reg' => '/^.*+/', 'value' => array('type' => 'var', 'var' => 'tablepre')),
		'adminemail' => array('type' => 'text', 'required' => 1, 'reg' => '/@/', 'value' => array('type' => 'var', 'var' => 'adminemail')),
	),
	'admininfo' => array
	(
		'username' => array('type' => 'text', 'required' => 1, 'reg' => '/^.*$/', 'value' => array('type' => 'constant', 'var' => 'admin')),
		'password' => array('type' => 'password', 'required' => 1, 'reg' => '/^.*$/'),
		'password2' => array('type' => 'password', 'required' => 1, 'reg' => '/^.*$/'),
		'email' => array('type' => 'text', 'required' => 1, 'reg' => '/@/', 'value' => array('type' => 'var', 'var' => 'adminemail')),
	)
);

?>