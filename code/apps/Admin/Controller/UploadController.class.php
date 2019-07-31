<?php
namespace Admin\Controller;
use Think\Controller;
class UploadController extends Controller {

    public function common(){
        $post = array();
        if($_FILES){
            $file = uploadfile($_FILES);
			
            if($file['file']){
                $this -> ajaxReturn(array('status'=>1,'data'=>$file['file']),'json');
            }
        }
    }
    
    
    /**
     * 百度编辑器使用的上传
     */
    public function   ueditor_upload(){
        $post = array();
        if(!empty($_FILES['upfile'])){
            $file = uploadfile($_FILES);
            $return =  array(
                         "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
                         "url" => "",            //返回的地址
                         "title" => "",          //新文件名
                         "original" => "",       //原始文件名
                         "type" => "",            //文件类型
                         "size" => "",         //文件大小
                    );
           
                    
            if($file['upfile']){
                 $return =  array(
                         "state" => "SUCCESS",          //上传状态，上传成功时必须返回"SUCCESS"
                         "url" => C('TMPL_PARSE_STRING.__ATTACH_HOST__').$file['upfile'],            //返回的地址
                         "title" => "",          //新文件名
                         "original" => "",       //原始文件名
                         "type" => "",            //文件类型
                         "size" => "",         //文件大小
                    );
                 exit(json_encode($return));
            }else{
                $return =  array(
                        "state" =>$file,          //上传状态，上传成功时必须返回"SUCCESS"
                );
                exit(json_encode($return));
            }
        }else{
            $return =  array(
                    "state" => "没选择任何文件",          //上传状态，上传成功时必须返回"SUCCESS"
                    "url" => "",            //返回的地址
                    "title" => "",          //新文件名
                    "original" => "",       //原始文件名
                    "type" => "",            //文件类型
                    "size" => "",         //文件大小
            );
            exit(json_encode($return));   
        }    
    }

}