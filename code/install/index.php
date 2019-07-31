<?php
/**
 *      [ShopDZ] (C)2001-2099 Comsenz-service Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: index.php 2016/8/10 15:00  seeley $
 */
define('CHARSET', 'UTF-8');
header("Content-type: text/html; charset=utf-8");
error_reporting(E_ERROR | E_WARNING | E_PARSE);
@set_time_limit(1000);
if(function_exists('set_magic_quotes_runtime')){
	@set_magic_quotes_runtime(0);
}

define('IN_SHOPDZ', TRUE);
define('IN_COMSENZ', TRUE);
define('ROOT_PATH', dirname(__FILE__).'/../');

require ROOT_PATH.'./source/shopdz_version.php';
require ROOT_PATH.'./install/include/install_var.php';
if(function_exists('mysql_connect')) {
	require ROOT_PATH.'./install/include/install_mysql.php';
} else {
	require ROOT_PATH.'./install/include/install_mysqli.php';
}
require ROOT_PATH.'./install/include/install_function.php';
require ROOT_PATH.'./install/include/install_lang.php';
$php_self = htmlspecialchars(get_script_url());
$sitepath = str_replace('install','',trim(substr($php_self, 0, strrpos($php_self, '/')),'/'));
$sitepath && $sitepath = '/'.$sitepath;
$scheme = strtolower($_SERVER['HTTPS']) == "on" ? 'https' : 'http';
$siteurl = htmlspecialchars($scheme.'://'.$_SERVER['HTTP_HOST'].$sitepath.'/');
define('SITE_URL', $siteurl);
$allow_method = array('show_license', 'env_check', 'db_init', 'ext_info', 'install_check', 'tablepre_check');

$step = intval(getgpc('step', 'R')) ? intval(getgpc('step', 'R')) : 0;
$method = getgpc('method');

if(empty($method) || !in_array($method, $allow_method)) {
	$method = isset($allow_method[$step]) ? $allow_method[$step] : '';
}

if(empty($method)) {
	show_msg('method_undefined', $method, 0);
}

if(file_exists($lockfile) && $method != 'ext_info') {
	show_msg('install_locked', '', 0);
} elseif(!class_exists('dbstuff')) {
	show_msg('database_nonexistence', '', 0);
}

timezone_set();

if(in_array($method, array('app_reg', 'ext_info'))) {
	$isHTTPS = ($_SERVER['HTTPS'] && strtolower($_SERVER['HTTPS']) != 'off') ? true : false;
	$PHP_SELF = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$default_appurl = 'http'.($isHTTPS ? 's' : '').'://'.preg_replace("/\:\d+/", '', $_SERVER['HTTP_HOST']).($_SERVER['SERVER_PORT'] && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443 ? ':'.$_SERVER['SERVER_PORT'] : '');
}

if($method == 'show_license') {


	show_license();

} elseif($method == 'env_check') {

	function_check($func_items);

	env_check($env_items);

	dirfile_check($dirfile_items);

	show_env_result($env_items, $dirfile_items, $func_items, $filesock_items);


} elseif($method == 'db_init') {
	

	$submit = true;

	$default_config = $_config = array();
	$default_configfile = './common/Conf/config_default.php';

	if(!file_exists(ROOT_PATH.$default_configfile)) {
		exit('config_default.php was lost, please reupload this  file.');
	} else {
		$_config = load_config(ROOT_PATH.$default_configfile);
		$default_config = $_config;
	}

	if(file_exists(ROOT_PATH.CONFIG)) {
		include ROOT_PATH.CONFIG;
	} else {
		$_config = $default_config;
	}

	$dbhost = $_config['DB_HOST'];
	$dbname = $_config['DB_NAME'];
	$dbpw = $_config['DB_PWD'];
	$dbuser = $_config['DB_USER'];
	$tablepre = $_config['DB_PREFIX'];

	$adminemail = 'admin@admin.com';

	$error_msg = array();
	if(isset($form_db_init_items) && is_array($form_db_init_items)) {
		foreach($form_db_init_items as $key => $items) {
			$$key = getgpc($key, 'p');
			if(!isset($$key) || !is_array($$key)) {
				$submit = false;
				break;
			}
			foreach($items as $k => $v) {
				$tmp = $$key;
				$$k = $tmp[$k];
				if(empty($$k) || !preg_match($v['reg'], $$k)) {
					$error_msg[$key][$k] = 1;
					if(empty($$k) && !$v['required']) {
						continue;
					}
					$submit = false;

				}
			}
		}
	} else {
		$submit = false;
	}

	if($submit && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if($password != $password2) {
			$error_msg['admininfo']['password2'] = 1;
			$submit = false;
		}
		$forceinstall = isset($_POST['dbinfo']['forceinstall']) ? $_POST['dbinfo']['forceinstall'] : '';
		$dbname_not_exists = true;
		if(!empty($dbhost) && empty($forceinstall)) {
			$dbname_not_exists = check_db($dbhost, $dbuser, $dbpw, $dbname, $tablepre);
			if(!$dbname_not_exists) {
				$form_db_init_items['dbinfo']['forceinstall'] = array('type' => 'checkbox', 'required' => 0, 'reg' => '/^.*+/');
				$error_msg['dbinfo']['forceinstall'] = 1;
				$submit = false;
				$dbname_not_exists = false;
			}
		}
	}

	if($submit) {

		$step = $step + 1;
		if(empty($dbname)) {
			show_msg('dbname_invalid', $dbname, 0);
		} else {
			$mysqlmode = function_exists("mysql_connect") ? 'mysql' : 'mysqli';
			$link = ($mysqlmode == 'mysql') ? @mysql_connect($dbhost, $dbuser, $dbpw) : new mysqli($dbhost, $dbuser, $dbpw);
			if(!$link) {
				$errno = ($mysqlmode == 'mysql') ? mysql_errno($link) : $link->errno;
				$error = ($mysqlmode == 'mysql') ? mysql_error($link) : $link->error;
				if($errno == 1045) {
					show_msg('database_errno_1045', $error, 0);
				} elseif($errno == 2003) {
					show_msg('database_errno_2003', $error, 0);
				} else {
					show_msg('database_connect_error', $error, 0);
				}
			}
			$mysql_version = ($mysqlmode == 'mysql') ? mysql_get_server_info() : $link->server_info;
			if($mysql_version > '4.1') {
				if($mysqlmode == 'mysql') {
					mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET ".DBCHARSET, $link);
				} else {
					$link->query("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET ".DBCHARSET);
				}
			} else {
				if($mysqlmode == 'mysql') {
					mysql_query("CREATE DATABASE IF NOT EXISTS `$dbname`", $link);
				} else {
					$link->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
				}
			}

			if(($mysqlmode == 'mysql') ? mysql_errno($link) : $link->errno) {
				show_msg('database_errno_1044', ($mysqlmode == 'mysql') ? mysql_error($link) : $link->error, 0);
			}
			if($mysqlmode == 'mysql') {
				mysql_close($link);
			} else {
				$link->close();
			}
		}

		if(strpos($tablepre, '.') !== false || intval($tablepre{0})) {
			show_msg('tablepre_invalid', $tablepre, 0);
		}
		$_config = '<?php
return array (
			\'DB_TYPE\' => \'mysql\',
			\'DB_HOST\' => \''.$dbhost.'\',
			\'DB_NAME\' => \''.$dbname.'\',
			\'DB_USER\' => \''.$dbuser.'\',
			\'DB_PWD\' => \''.$dbpw.'\',
			\'DB_PORT\' => \'\',
			\'DB_PREFIX\' => \''.$tablepre.'\',
			\'DB_FIELDS_CACHE\' => true,
			\'DB_CHARSET\' => \'utf8\',
			\'DB_DEBUG\' => false,
			\'SYS_LOG\' => true,
			\'LAYOUT_ON\' => true,
			\'SESSION_AUTO_START\' => true,
			\'URL_MODEL\' => 1,
			\'HTML_CACHE_ON\' => true,
			\'TMPL_DENY_PHP\' => false,
			\'TMPL_TEMPLATE_SUFFIX\' => \'.php\',
			\'LAYOUT_NAME\' => \'layout\',
			\'PAYMENT_URL\' => \''.SITE_URL.'api.php/Payment/wxpayNotify\',
			\'WXXPAYMENT_URL\' => \''.SITE_URL.'api.php/Payment/wxxpayNotify\',
			\'VAR_FILTERS\' => \'htmlspecialchars,trim\',
			\'LAYOUT_PATH\' => \'./apps/Admin/View/\',
			\'TMPL_ACTION_ERROR\' => \'Public:error\',
			\'TMPL_ACTION_SUCCESS\' => \'Public:success\',
			\'DB_RW_SEPARATE\' => true,
			\'DB_DEPLOY_TYPE\' => 1,
			\'TMPL_PARSE_STRING\' => array (
					\'__PUBLIC__\' => \''.$sitepath.'/static\',
					\'__UPLOAD__\' => RUNTIME_PATH.\'Attach/\',
					\'__CATEGORY_ICON__\' => RUNTIME_PATH.\'Attach/CategoryIcon/\',
					\'__ATTACH_HOST__\' => \''.SITE_URL .'data/Attach/\',
					\'DEFAULT_LOGIN_IMAGE\' => \''.$sitepath.'/static/img/login.jpg\',
					\'DEFAULT_GOODS_IMAGE\' => \''.$sitepath.'/static/img/default_goods_image.gif\',
					\'DEFAULT_MEMBER_IMAGE\' => \''.$sitepath.'/static/img/default_user_image.png\',
					\'DEFAULT_LOGO_IMAGE\' => \''.$sitepath.'/static/img/logo.png\' 
			),
			\'ORDER_TIME\' => 3600,
			\'LANG_SWITCH_ON\' => true,
			\'LANG_AUTO_DETECT\' => true,
			\'LANG_LIST\' => \'zh-cn\',
			\'VAR_LANGUAGE\' => \'l\',
			\'DEFAULT_FILTER\' => \'htmlspecialchars,trim\',
			\'AUTH_COOKIE_TIME\' =>  30*24*3600,
			\'USER_AUTH_ON\' => true,
			\'WAP_URL\' => \'' . SITE_URL . 'wap/\',
			\'NOT_AUTH_MODULE\' => array (
					\'index\' => array (
							0 => \'index\' 
					),
					\'setting\' => array (
							0 => \'clean_cache\' 
					),
					\'system\' => array (
							0 => \'info\' 
					)
			),
			\'AUTOLOAD_NAMESPACE\'=> array(
				\'plugins\'=>PLUGIN_PATH,
			),
	);
	?>';
		save_config_file(ROOT_PATH.CONFIG, $_config, $default_config);
		$db = new dbstuff;
		$db->connect($dbhost, $dbuser, $dbpw, $dbname, DBCHARSET);
		$sql = file_get_contents(ROOT_PATH.'./install/data/install_admin.sql');
		$sql = str_replace("\r\n", "\n", $sql);
		runquery($sql);
		if($username && $email && $password) {
			if(strlen($username) > 30 || preg_match("/^$|^c:\\con\\con$|　|[,\"\s\t\<\>&]|^Guest/is", $username)) {
				show_msg('admin_username_invalid', $username, 0);
			} elseif(!strstr($email, '@') || $email != stripslashes($email) || $email != dhtmlspecialchars($email)) {
				show_msg('admin_email_invalid', $email, 0);
			} else {

				$adminuser = check_adminuser($username, $password, $email);
				if($adminuser['uid'] < 1) {
					show_msg($adminuser['error'], '', 0);
				}

			}
		} else {
			show_msg('admininfo_invalid', '', 0);
		}


		$uid = $adminuser['uid'];
		show_header();
		show_install();

		//$sql = file_get_contents($sqlfile);
		//$sql = str_replace("\r\n", "\n", $sql);

		//runquery($sql);
		//runquery($extrasql);
	
		$sql = file_get_contents(ROOT_PATH.'./install/data/install_data.sql');
		$sql = str_replace("\r\n", "\n", $sql);
		runquery($sql);

		install_data($username, $uid);

		$testdata = $portalstatus = 1;


		if($testdata) {
			install_testdata($username, $uid);
		}

		dir_clear(ROOT_PATH.'./data/Data');
		dir_clear(ROOT_PATH.'./data/Cache');
		dir_clear(ROOT_PATH.'./data/avatar');
		dir_clear(ROOT_PATH.'./data/Attach');
		dir_clear(ROOT_PATH.'./data/Data');
		dir_clear(ROOT_PATH.'./data/Temp');
		dir_clear(ROOT_PATH.'./data/Attach/Common');

		echo '<script type="text/javascript">function setlaststep() {document.getElementById("laststep").disabled=false;window.location=\'index.php?method=ext_info\';}</script><script type="text/javascript">setTimeout(function(){window.location=\'index.php?method=ext_info\'}, 30000);</script><iframe src="../misc.php?mod=initsys" style="display:none;" onload="setlaststep()"></iframe>'."\r\n";
		show_footer();
	}

	show_form($form_db_init_items, $error_msg);

} elseif($method == 'ext_info') {
	@touch($lockfile);

	show_header();
	echo '
	<div class="setup step4">
		<h2>安装完成</h2>
		<p>完成安装ShopDZ商城</p>
	</div>
	<div class="stepstat">
		<ul>
			<li class="current">检查安装环境</li>
			<li class="current">设置运行环境</li>
			<li class="current">创建数据库</li>
			<li class="current last">安装完成</li>
		</ul>
		<div class="stepstatbg stepstat4"></div>
	</div>
	</div><div class="main" style="padding-left:30px"><span id="platformIntro"></span>';
	echo '<p align="right">
	<input type="button" class="btn"  value="点击访问" style="margin-right: 270px;margin-top:30px;" onclick="location.href=\''. SITE_URL .'\'" /></p><br />';
	echo '</div>';
	echo '<script type="text/javascript" src="../static/admin/js/jquery-1.9.1.min.js"></script>';
	$get_onlineip = get_onlineip();
	echo '<script type="text/javascript"> 
		var url ="http://saas.shopdz.cn/api.php/index/install";
		$.ajax({	
				url:url,
				jsonp: "jsoncallback",
				dataType: "jsonp",
				data:{ip:"'.$get_onlineip.'",host:"'.$_SERVER['HTTP_HOST'].'"},
				});
	</script>';
	show_footer();


} elseif($method == 'install_check') {

	if(file_exists($lockfile)) {
		show_msg('installstate_succ');
	} else {
		show_msg('lock_file_not_touch', $lockfile, 0);
	}

} elseif($method == 'tablepre_check') {

	$dbinfo = getgpc('dbinfo');
	extract($dbinfo);
	if(check_db($dbhost, $dbuser, $dbpw, $dbname, $tablepre)) {
		show_msg('tablepre_not_exists', 0);
	} else {
		show_msg('tablepre_exists', $tablepre, 0);
	}
}