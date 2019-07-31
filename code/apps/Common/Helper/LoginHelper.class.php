<?php
namespace Common\Helper;

class LoginHelper{
	static $nologin_cookie_key ="user_unique_id";
	static function passwordMd5($string,$key="qwer!@#$"){
		return md5($string.$key);
	}
	
	static function random($length, $numeric = 0) {
		$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
		$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
		if($numeric) {
			$hash = '';
		} else {
			$hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
			$length--;
		}
		$max = strlen($seed) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $seed{mt_rand(0, $max)};
		}
		return $hash;
	}
	
	static function setNOloginSid() {
		$unique_key = self::$nologin_cookie_key;
		$unique_id = cookie($unique_key);
		if(empty($unique_id)){
			$random =  self::random(8);
			cookie($unique_key,$random);
		}
	}
	
	static function getNOLoginSid() {
		$unique_key = self::$nologin_cookie_key;
		return $unique_id = cookie($unique_key);
	}
}