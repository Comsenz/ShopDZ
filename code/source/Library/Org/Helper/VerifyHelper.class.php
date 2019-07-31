<?php
namespace Org\Helper;

class VerifyHelper{

    public $verify;

    public function __construct(){
        $config = array(
            'expire' => 300,
            'length' => 4,
            'fontSize' => 35,
            'codeSet'=>'1234567890',
             'reset'     =>  false,
        );
        $this->verify = new \Think\Verify($config);
    }

    public function getVerifyCode($id=''){
		//ob_clean();
        $this->verify->entry($id);
    }

    public function checkCode($code,$id=''){
        return $this->verify->check($code,$id);
    }

}   