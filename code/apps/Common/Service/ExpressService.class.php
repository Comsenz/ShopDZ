<?php
/**
 * 快递查询
 * @author yangyong
 */
namespace Common\Service;
use Think\Model;
use Admin\Model\SettingModel;
use Common\Helper\ToolsHelper;
use Common\Service\IndexService;
define('SAAS_API_URL','http://saas.shopdz.cn/api.php/');
class ExpressService {
    /**
     * //订阅物流信息
     * @param 快递公司编码 $company_code
     * @param  物流编号  $express_code
     * @param 订单id $order_id
     */
    static  function   subscribe($company_code,$express_code,$order_id = 0){
        $company_code=trim($company_code);
        $express_code = trim($express_code);
        $order_id = intval($order_id);
        $url = SAAS_API_URL.'express/scribe_exoress';
        $model = new SettingModel();
        $license_code = $model->getSetting('license_code');
        $license_secret = $model->getSetting('license_secret');
        $server_name = SITE_URL;
        $time = time();
        $key = date('Y-m-d',$time);
		$ip = get_client_ip();
        if(stristr($server_name,'localhost') === false && !in_array($ip, array('127.0.0.1'))){
            $code = ToolsHelper::authcode("expcode=$company_code&expsn=$express_code&license_server=$server_name&license_code=$license_code&time=$time&license_secret=$license_secret",'ENCODE',$key);
            $return = json_decode(self::send_post($url,array('code'=>rawurlencode($code))),ture);
            $result = json_decode(ToolsHelper::authcode(rawurldecode($return),'DECODE',$key),true);
            if($result['returnstatus'] == 0){
                //更新授权信息
                $lice_info = array();
                if($result['license_status'] !== NULL)
                    $lice_info['license_status'] = $result['license_status'];
                if($result['license_time'] !== NULL)
                    $lice_info['license_time'] = $result['license_time'];
                if($result['license_secret'] !== NULL)
                    $lice_info['license_secret'] = $result['license_secret'];
                if($result['license_code'] !== NULL)
                    $lice_info['license_code'] = $result['license_code'];
                if(!empty($lice_info)){
                    $setting_model =  new SettingModel();
                    $setting_model->Settings($lice_info);
                }

                if($order_id){
                    M('order_common')->where(array('order_id'=>$order_id))->setField('is_subscribe',1);
                }
                return true;
            }else{
                order_log($order_id, 'order_id', '订单id'.$order_id.'订阅物流信息失败'.var_export($result,true));
                return false;
            }
        }else{
            order_log($order_id, 'order_id', '订单id'.$order_id.'本地订单，无法订阅物流信息。');
            return true;
        }
    }
    
    /**
     * 
     * @param unknown $company_code
     * @param unknown $express_code
     */
    static  function  query_express($company_code,$express_code){
        $company_code=trim($company_code);
        $express_code = trim($express_code);
        $data =  array();  //先去库里查 查不到再去接口查 接口查询有次数限制
        $url = SAAS_API_URL.'express/get_express';
        $model = new SettingModel();
        $license_code = $model->getSetting('license_code');
        $license_secret = $model->getSetting('license_secret');
        $server_name = SITE_URL;
        $time = time();
        $key = date('Y-m-d',$time);
		$ip = get_client_ip();
        if(stristr($server_name,'localhost') === false && !in_array($ip, array('127.0.0.1'))){
            $code = ToolsHelper::authcode("expcode=$company_code&expsn=$express_code&license_server=$server_name&license_code=$license_code&time=$time&license_secret=$license_secret",'ENCODE',$key);
            $return = json_decode(self::send_post($url,array('code'=>rawurlencode($code))),ture);
            $result = json_decode(ToolsHelper::authcode(rawurldecode($return),'DECODE',$key),true);
            if($result['returnstatus'] == 0){
                $data = $result['express_info'];
                //更新授权信息
                $lice_info = array();
                if($result['license_status'] !== NULL)
                    $lice_info['license_status'] = $result['license_status'];
                if($result['license_time'] !== NULL)
                    $lice_info['license_time'] = $result['license_time'];
                if($result['license_secret'] !== NULL)
                    $lice_info['license_secret'] = $result['license_secret'];
                if($result['license_code'] !== NULL)
                    $lice_info['license_code'] = $result['license_code'];
                if(!empty($lice_info)){
                    $setting_model =  new SettingModel();
                    $setting_model->Settings($lice_info);
                }
            }else{
                $data['msg'] = $result['msg'];
            }
        }else{
            $data['msg'] = '本地无法查询物流信息。';
        }
        return $data;    
    }
    
    /**
     * 发生post请求
     * @param unknown $url
     * @param unknown $data
     * @return mixed
     */
    function   send_post($url,$data){
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        return $return;
    }
    
    
    
}