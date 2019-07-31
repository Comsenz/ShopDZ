<?php
namespace Common\Helper;

class SecCodeHelper{
	  
	    
	/**
	 * 产生验证码
	 *
	 * @param string $nchash 哈希数
	 * @return string
	 */
	function makeSeccode($nchash){
	    $seccode = self::random(6, 1);
	    self::setNcCookie('seccode', self::encrypt(strtoupper($seccode)."\t".time(),MD5_KEY),60);
	    return $seccode;
	}

	/**
	 * 验证验证码
	 *
	 * @param string $nchash 哈希数
	 * @param string $value 待验证值
	 * @return boolean
	 */
	function checkSMS($nchash,$value){
	    list($checkvalue, $checktime) = explode("\t", self::decrypt(self::cookie('seccode'),MD5_KEY));
	    $return = $checkvalue == strtoupper($value);
	    if (!$return) self::setNcCookie('seccode','',-3600);
	    return $return;
	}


	/**
	 * 设置cookie
	 *
	 * @param string $name cookie 的名称
	 * @param string $value cookie 的值
	 * @param int $expire cookie 有效周期
	 * @param string $path cookie 的服务器路径 默认为 /
	 * @param string $domain cookie 的域名
	 * @param string $secure 是否通过安全的 HTTPS 连接来传输 cookie,默认为false
	 */
	function setNcCookie($name, $value, $expire='3600', $path='', $domain='', $secure=false){
	    if (empty($path)) $path = '/';
	    $name = strtoupper(substr(md5(MD5_KEY),0,4)).'_'.$name;
	    $result = setcookie($name, $value, time()+$expire, $path, $domain, $secure);
	    $_COOKIE[$name] = $value;
	}

	/**
	 * 取得COOKIE的值
	 *
	 * @param string $name
	 * @return unknown
	 */
	function cookie($name= ''){
	    $name = strtoupper(substr(md5(MD5_KEY),0,4)).'_'.$name;
	    return $_COOKIE[$name];
	}

	/**
	 * 取得随机数
	 *
	 * @param int $length 生成随机数的长度
	 * @param int $numeric 是否只产生数字随机数 1是0否
	 * @return string
	 */
	function random($length, $numeric = 0) {
	    $seed = base_convert(md5(microtime()), 16, $numeric ? 10 : 35);
	    $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	    $hash = '';
	    $max = strlen($seed) - 1;
	    for($i = 0; $i < $length; $i++) {
	        $hash .= $seed{mt_rand(0, $max)};
	    }
	    return $hash;
	}

	/**
	 * 加密函数
	 *
	 * @param string $txt 需要加密的字符串
	 * @param string $key 密钥
	 * @return string 返回加密结果
	 */
	function encrypt($txt, $key = ''){
	    if (empty($txt)) return $txt;
	    if (empty($key)) $key = md5(MD5_KEY);
	    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
	    $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
	    $nh1 = rand(0,64);
	    $nh2 = rand(0,64);
	    $nh3 = rand(0,64);
	    $ch1 = $chars{$nh1};
	    $ch2 = $chars{$nh2};
	    $ch3 = $chars{$nh3};
	    $nhnum = $nh1 + $nh2 + $nh3;
	    $knum = 0;$i = 0;
	    while(isset($key{$i})) $knum +=ord($key{$i++});
	    $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
	    $txt = base64_encode(time().'_'.$txt);
	    $txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
	    $tmp = '';
	    $j=0;$k = 0;
	    $tlen = strlen($txt);
	    $klen = strlen($mdKey);
	    for ($i=0; $i<$tlen; $i++) {
	        $k = $k == $klen ? 0 : $k;
	        $j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
	        $tmp .= $chars{$j};
	    }
	    $tmplen = strlen($tmp);
	    $tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
	    $tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
	    $tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
	    return $tmp;
	}

	/**
	 * 解密函数
	 *
	 * @param string $txt 需要解密的字符串
	 * @param string $key 密匙
	 * @return string 字符串类型的返回结果
	 */
	function decrypt($txt, $key = '', $ttl = 0){
	    if (empty($txt)) return $txt;
	    if (empty($key)) $key = md5(MD5_KEY);

	    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
	    $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
	    $knum = 0;$i = 0;
	    $tlen = @strlen($txt);
	    while(isset($key{$i})) $knum +=ord($key{$i++});
	    $ch1 = @$txt{$knum % $tlen};
	    $nh1 = strpos($chars,$ch1);
	    $txt = @substr_replace($txt,'',$knum % $tlen--,1);
	    $ch2 = @$txt{$nh1 % $tlen};
	    $nh2 = @strpos($chars,$ch2);
	    $txt = @substr_replace($txt,'',$nh1 % $tlen--,1);
	    $ch3 = @$txt{$nh2 % $tlen};
	    $nh3 = @strpos($chars,$ch3);
	    $txt = @substr_replace($txt,'',$nh2 % $tlen--,1);
	    $nhnum = $nh1 + $nh2 + $nh3;
	    $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
	    $tmp = '';
	    $j=0; $k = 0;
	    $tlen = @strlen($txt);
	    $klen = @strlen($mdKey);
	    for ($i=0; $i<$tlen; $i++) {
	        $k = $k == $klen ? 0 : $k;
	        $j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
	        while ($j<0) $j+=64;
	        $tmp .= $chars{$j};
	    }
	    $tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
	    $tmp = trim(base64_decode($tmp));

	    if (preg_match("/\d{10}_/s",substr($tmp,0,11))){
	        if ($ttl > 0 && (time() - substr($tmp,0,11) > $ttl)){
	            $tmp = null;
	        }else{
	            $tmp = substr($tmp,11);
	        }
	    }
	    return $tmp;
	}
}