<?php
// 应用入口文件
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
$root  =   dirname(__FILE__).'/';
define('RUNTIME_PATH', $root.'data/');
$php_self = htmlspecialchars(get_script_url());
$sitepath = substr($php_self, 0, strrpos($php_self, '/'));
$scheme = strtolower($_SERVER['HTTPS']) == "on" ? 'https' : 'http';
$siteurl = htmlspecialchars($scheme.'://'.$_SERVER['HTTP_HOST'].$sitepath.'/');
define('SITE_URL', $siteurl);
function get_script_url() {
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
$lockfile = RUNTIME_PATH.'./install.lock';
if(!file_exists($lockfile)){
	$url = SITE_URL .'install';
	@header('Location: '.$url);
	exit;
}
$url = SITE_URL .'wap';
@header('Location: '.$url);