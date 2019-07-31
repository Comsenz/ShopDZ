<?php
/**
 * 微信公众号开发 处理微信被动响应
 */

namespace Common\Wechat;
use Think\Controller;
class WechatController 
{

    private $data = array();

    public function __construct($token){
      $this->auth($token) || die('hahah');
        $xml = file_get_contents('php://input');
        file_put_contents('/data/www/html/shopdz/88888.txt',var_export($xml,true),FILE_APPEND);
        if (!$xml) {
            echo $_GET['echostr'];
            die;
        } else {
            $xml = new \SimpleXMLElement($xml);
            $xml || die('525');
            file_put_contents('/data/www/html/shopdz/88888.txt',var_export($xml,true),FILE_APPEND);
            foreach ($xml as $key => $value) {
                $this->data[$key] = strval($value);
            }
        }

    }

    public function request()
    {
       return $this->data;
    }


    public function response($content, $type = 'text', $flag = 0)

    {

        $this->data = array('ToUserName' => $this->data['FromUserName'], 'FromUserName' => $this->data['ToUserName'], 'CreateTime' => NOW_TIME, 'MsgType' => $type);

        $this->{$type}($content);

        $this->data['FuncFlag'] = $flag;

        $xml = new \SimpleXMLElement('<xml></xml>');

        $this->data2xml($xml, $this->data);

        die($xml->asXML());

    }

    private function text($content)
    {
        $this->data['Content'] = $content;

    }
    private function transfer_customer_service($content)

    {

        $this->data['transfer_customer_service'] = $content;

    }
    private function music($music)
    {
        list($music['Title'], $music['Description'], $music['MusicUrl'], $music['HQMusicUrl']) = $music;
        $this->data['Music'] = $music;

    }

    private function news($news)
    {
        $articles = array();
        foreach ($news as $key => $value) {
            list($articles[$key]['Title'], $articles[$key]['Description'], $articles[$key]['PicUrl'], $articles[$key]['Url']) = array($value['Title'],$value['Description'],$value['PicUrl'],$value['Url']);
            if ($key >= 9) {
                break;
            }
        }
        $this->data['ArticleCount'] = count($articles);
        $this->data['Articles'] = $articles;

    }

    private function data2xml($xml, $data, $item = 'item')
    {
        foreach ($data as $key => $value) {

            is_numeric($key) && ($key = $item);

            if (is_array($value) || is_object($value)) {

                $child = $xml->addChild($key);

                $this->data2xml($child, $value, $item);

            } else {
                if (is_numeric($value)) {

                    $child = $xml->addChild($key, $value);

                } else {

                    $child = $xml->addChild($key);

                    $node = dom_import_simplexml($child);

                    $node->appendChild($node->ownerDocument->createCDATASection($value));

                }

            }

        }

    } 
	/**
     * 微信素材库上传图片
     */

    public function uploadCardLogo($file)
    {
      //  $this->access_token = 'wseWUoFjbrcffGLbkyIJgOZqNuNTtcMxEJrRdwz-X-ggX0pZA06ZLkg1CUD9Ge23KdS-7tSCv7WEiclreAuFSJP4BUyIWzMxOBNB8DtDCQVFaM5HVGg1OaywcyqziRM5BMAdACAKVJ';
        $url =  'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->access_token.'&type=image';
       // $file	=	"01.jpg";

       // $file	=	"@D:\phpStudy\WWW\shopdzin\code/1.jpg";
       // $file	=	"@".file_get_contents('/1.jpg');
        $rootfile =    str_replace('\\','/',realpath(dirname(__FILE__). '/../../../') . '/');
        $file	=	"@".$rootfile."data/Attach/wxuser/1.jpg";
        $fields	=	array("media" => $file);
        $result	= $this->post($url,$fields);
        dump($result);
    }

    private function auth($token)
    {
		return true;
		
    }

}
