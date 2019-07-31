<?php
namespace Common\Base\Controller;
use Think\Controller;
class BaseController extends Controller {

    public $website;
	public $admin_user = array();
    public function __construct(){
        parent::__construct();
		$this->admin_user = get_admin_info();
        if (empty($this->admin_user)) {
			$url = SITE_URL.('admin.php/Login/index');
			echo "<script>window.parent.location.href= '$url'</script>";
			exit;
			//$this->assign('url', U('Login/index'));
        }
		if(!IS_AJAX)
			layout('iframelayout');
		$getbasedata = getbasedata();
		list($menu,$list,$setting) = $getbasedata;
		$this->_checkauth($list);
		$baseUrl = getBaseUrl();
		$countdata = getcount();
		$this->assign(array('menu'=>$menu));
		$this->assign('license',$setting['license']);
		$this->assign('setting',json_encode($setting));
		$this->assign('admin_user',$this->admin_user);
		$this->assign('countdata',$countdata);
		$this->assign('baseUrl',$baseUrl);
    }
	//初始化时做权限判断
	protected function _checkauth($list){
		if(empty($list)) return;
		$module =strtolower(CONTROLLER_NAME);
		$action = strtolower(ACTION_NAME);
		$no_auth = C('NOT_AUTH_MODULE');
		
		if(array_key_exists($module,$no_auth)) {
			if(in_array($action,$no_auth[$module])) {
				return;
			}
		}
		if(C('USER_AUTH_ON') ){
			$controller_method_list = $list;
			//$controller_method_list = $this->_getModuleAccessList($adminid,CONTROLLER_NAME);
			if(!$controller_method_list[$module][$action]){
				 $this->error('没有权限');
			}
		}
	}
	
    //检查是否是提交表单信息
    public function checksubmit(){
        return $_POST['form_submit']=='submit'?true:false;
    }

    //封装分页
    public function page($count,$pagenum=10){
        $page=new \Think\Page($count,$pagenum);
        $show=$page->show();
        $this->assign('page',$show);
		return $page;
    }


	public function addText($url,$from_type) {

		$im1 = imagecreatefrompng($url);
		$height = imagesy($im1);
		$width = imagesx($im1);
		//这几行必须有，否则原图的阴影层过不来
		$im2 = imagecreatetruecolor($width, $height+55);
		$bg = imagecolorallocate($im2, 255, 255, 255);
		imagefill($im2, 0, 0, $bg);
		imagecopy($im2, $im1, 0, 0, 0, 0, imagesx($im1), imagesy($im1));
		#设置水印字体颜色
		$color = imagecolorallocatealpha($im2,0,0,0,0);
		#设置字体文件路径
		//$fontfile = "msyhbd.ttf";
		$fontfile = "./simhei.ttf";
		#水印文字
		$str = C('ADD_TEXT');
		$str = $this->to_entities($str);
	//	$str = iconv('gbk', 'utf-8', $str);
		#打水印
		imagettftext($im2,40,0,ceil($width/2)-150,$height+25,$color,$fontfile,$str);
		$filedir = './Public/uploads/'.$from_type.'/';
		$filename = $filedir.$from_type.time().rand().'.png';
		imagepng($im2,$filename);
		return $filename;
	}
	
	private function to_entities($string){
		$len = strlen($string);
		$buf = "";
		for($i = 0; $i < $len; $i++){
			if (ord($string[$i]) <= 127){
				$buf .= $string[$i];
			} else if (ord ($string[$i]) <192){
				//unexpected 2nd, 3rd or 4th byte
				$buf .= "&#xfffd";
			} else if (ord ($string[$i]) <224){
				//first byte of 2-byte seq
				$buf .= sprintf("&#%d;",
					((ord($string[$i + 0]) & 31) << 6) +
					(ord($string[$i + 1]) & 63)
				);
				$i += 1;
			} else if (ord ($string[$i]) <240){
				//first byte of 3-byte seq
				$buf .= sprintf("&#%d;",
					((ord($string[$i + 0]) & 15) << 12) +
					((ord($string[$i + 1]) & 63) << 6) +
					(ord($string[$i + 2]) & 63)
				);
				$i += 2;
			} else {
				//first byte of 4-byte seq
				$buf .= sprintf("&#%d;",
					((ord($string[$i + 0]) & 7) << 18) +
					((ord($string[$i + 1]) & 63) << 12) +
					((ord($string[$i + 2]) & 63) << 6) +
					(ord($string[$i + 3]) & 63)
				);
				$i += 3;
			}
		}
		return $buf;
	}
	protected function showmessage($type='success',$message='',$jumpUrl='',$ajax=false){
		 $type =="success" ?  parent::success($message,$jumpUrl,$ajax) : parent::error($message,$jumpUrl,$ajax);
		 exit;
	}
	//生成二维码
	public function createQrCode($url,$path ='qrcode'){
		$filedir = \Common\Helper\ToolsHelper::create_path($path);
		$filename = $filedir.time().rand().'.png';
		\Common\Helper\QRcode::png($url,$filename,'L',100,1);
		return $filename;
	}
}