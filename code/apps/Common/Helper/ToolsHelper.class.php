<?php
namespace Common\Helper;

class ToolsHelper{
	const  KEY = 'shopdz2016qweasdzxc123098';
	static $order_sn_type = array(
		'order'=>'00',//订单生成标志
		//'group'=>'01',//团购订单生成标志
		'return'=>'02',//退货生成标志
		'refund'=>'03',//退款生成标志
		'otherpay'=>'04'//退款生成标志
	);
	static $pay_class_method = array(
		'order'		=> array(
			'class'		=> 'OrdersService',
			'select'	=> 'getOrderByOrderSn',
			'save'		=> 'saveOrder',
			'payresult' => 'getOrderPayResult',
			'detailurl' => 'orderdetails.html',
			'succeeurl' => '/wap/ordersuccess.html',
			'failurl'	=> '/wap/orderfail.html',
			'other'	=> ''
		),
		'group'		=> array(
			'class'		=> 'GroupService',
			'select'	=> 'getGroupJoinBySnByPay',
			'save'		=> 'opGroupJion',
			'payresult' => 'getGroupPayResult',
			'detailurl' => 'groupdetails.html',
			'succeeurl' => '/wap/groupsuccess.html',
			'failurl'	=> '/wap/groupfail.html',
			'other'	=> ''
		),
		'return'		=> array(
			'class'		=> '',
			'select'	=> '',
			'save'		=> ''
		),
		'refund'		=> array(
			'class'		=> '',
			'select'	=> '',
			'save'		=> ''
		),
		'otherpay'		=> array(
			'class'		=> 'OtherPayService',
			'select'	=> 'getOtherPaySn',
			'add'		=> 'addOtherPayData',
			'save'		=> 'saveOtherPayData',
			'succeeurl' => '/wap/otherpaysuccess.html',
			'failurl'	=> '/wap/otherpayfail.html'
		)
	);
	static $return_class_method = array(
		'order'		=> array(
			'table'		=> 'orders',
			'class'		=> 'PresalesService',
			'select'	=> '',
			'save'		=> ''
		),
		'group'		=> array(
			'table'		=> '',
			'class'		=> 'GroupService',
			'select'	=> 'getGroupJoinByReturnSn',
			'save'		=> 'refund_pay_callback'
		),
		'return'		=> array(
			'table'		=> 'returngoods',
			'class'		=> 'PresalesService',
			'select'	=> 'get_return',
			'save'		=> 'save_return'
		),
		'refund'		=> array(
			'table'		=> 'refund',
			'class'		=> 'PresalesService',
			'select'	=> 'get_return',
			'save'		=> 'save_refund'
		)
	);
	static function base64_to_img( $base64_string, $savename ,$path='Refund'){
		//localResizeIMG压缩后的图片都是jpeg格式
		$_path = self::create_path($path);
		$savepath = $_path.$savename;
		$ifp = fopen( $savepath, "wb" ); 
		fwrite( $ifp, base64_decode( $base64_string) ); 
		fclose( $ifp ); 
		return $savepath; 
	}
	
	static function create_path($path) {
		$_path = C('TMPL_PARSE_STRING.__UPLOAD__')."/$path/".date('Y-m-d').'/';
		if(!is_dir($_path))
			mkdir($_path,0777,true);
		return $_path;
	}
	
	
	static function format_url($refund_images,$path = 'Refund') {
		$count = count($refund_images);
		$filter = array_fill(0,$count,$path);
		$refund_images = array_map(
		function($url,$path) {
			list(,$real_url) = explode($path,$url);
			return $real_url ? $path.$real_url :'';
		},$refund_images,$filter);
		return $refund_images;
	}
	
	static function exists_seach_key($seach,$field,array $array) {
		foreach($array as $k => $v ) {
			if($seach == $v[$field])
			 return $k;
		}
		return false;
	}

	/** 
	 * 生成缩略图函数（支持图片格式：gif、jpeg、png和bmp） 
	 * @author yangyong
	 * @param  string $src      源图片路径 
	 * @param  int    $width    缩略图宽度（只指定高度时进行等比缩放）
	 * @param  int    $width    缩略图高度（只指定宽度时进行等比缩放）
	 * @param  string $filename 保存路径（不指定时直接输出到浏览器）
	 * @return bool 
	 */  
	static function mkThumbnail($src, $width = null, $height = null, $filename = null) {  
	    if (empty($width) && empty($height)) {
	        return false;  
	    }
	    $size = getimagesize($src);  
	    if (!$size){
	        return false;  
	    }
	    list($src_w, $src_h, $src_type) = $size;
		$width = $width ? $width : $src_w;
		$height = $height ? $height : $src_h;
		$width = $src_w < $width ? $src_w : $width;
		$height = $src_h < $height ? $src_h : $height;
	    $src_mime = $size['mime'];  
	    switch($src_type) {
	        case 1 :  
	            $img_type = 'gif';  
	            break;  
	        case 2 :  
	            $img_type = 'jpeg';  
	            break;  
	        case 3 :  
	            $img_type = 'png';  
	            break;  
	        case 15 :  
	            $img_type = 'wbmp';  
	            break;  
	        default :  
	            return false;  
	    }  
		 $doc_width =  $src_w/$width;
		 $doc_height = $src_h/$height;
		 if($doc_width > $doc_height)  {
			$height = $src_h/$doc_width;
		 }else{
			$width = $src_w/$doc_height;
		 }
	    $imagecreatefunc = 'imagecreatefrom' . $img_type;  
	    $src_img = $imagecreatefunc($src);  
	    $dest_img = imagecreatetruecolor($width, $height);  
	    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);  
	    $imagefunc = 'image' . $img_type;  
	    if ($filename) {  
	        $imagefunc($dest_img, $filename);  
	    } else {  
	        header('Content-Type: ' . $src_mime);  
	        $imagefunc($dest_img);  
	    }
	    imagedestroy($src_img);  
	    imagedestroy($dest_img);  
	    return true;  
	}
	
	function GetMonth($sign=0)  {  
        //得到系统的年月  
        $tmp_date=date("Ym");  
        //切割出年份  
        $tmp_year=substr($tmp_date,0,4);  
        //切割出月份  
        $tmp_mon =substr($tmp_date,4,2);  
        $tmp_nextmonth=mktime(0,0,0,$tmp_mon+1,1,$tmp_year);  
        $tmp_forwardmonth=mktime(0,0,0,$tmp_mon-1,1,$tmp_year);  
        if($sign==0){
            //得到当前月的下一个月   
            return $fm_next_month=date("Ym",$tmp_nextmonth);          
        }else{
            //得到当前月的上一个月   
            return $fm_forward_month=date("Ym",$tmp_forwardmonth);           
        }  
    }
	
	function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
		$ckey_length = 4;
		$key = md5($key != '' ? $key : self::KEY);
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}
	
	function simpleShow($str,$len=4) {
		return preg_replace('/([\w-@\.]{'.$len.'})([\w-@\.]{1,})([\w-@\.]{'.$len.'})/i','$1****$3',$str);
	}
	
	function getOrderSn($type = 'order'){
		$dot = self::$order_sn_type[$type];
		$order_sn = '';
		if(!empty($dot)){
			$random = \Common\Helper\LoginHelper::random(2,$numeric=1);
			$order_sn = date('YmdHis').$random.$dot;
		}
		return $order_sn;
	}
	
	function getOrderSnType($orderSn) {
		$doc = substr($orderSn,0-2);
		return array_search($doc,self::$order_sn_type);
	}
	
	function getOrderSnLastTwoCode($orderSn) {
		return $doc = substr($orderSn,0-2);
	}
	
	function group_same_key($arr,$key){
		$new_arr = array();
		foreach($arr as $k=>$v ){
			$new_arr[$v[$key]]= $v;
		}
		unset($arr);
		return $new_arr;
	}
}