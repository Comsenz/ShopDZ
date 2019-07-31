<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Jd\JdClient;
use Org\Jd\request\WaresSearchRequest;
use Org\Jd\request\WaresListGetRequest;
use Common\Service\OtherShopService;

class JdController extends BaseController {
    private $JD;
    private $shop_config;

    public function __construct(){
        parent::__construct();
        $this->JD = new JdClient();
    }
    /**
     * 获取第三方商城配置
     */
    public function getShopConfig($shop_code){
        $this->shop_config = OtherShopService::getShopConfig($shop_code);
        return $this->shop_config;
    }
    /**
     * 实例jd
     */
    private function jdClient(){
        $shop_code = I('get.shop_code');
        if(!empty($shop_code)){
            $config = $this->getShopConfig($shop_code);
            $this->JD->appKey = $config['shop_config']['appKey'];
            $this->JD->appSecret = $config['shop_config']['appSecret'];
            $this->JD->accessToken = $config['shop_config']['access_token'];
            $this->JD->serverUrl = $config['shop_config']['serverUrl'];
            $this->JD->callbackUrl = $config['shop_config']['callbackUrl'];
            return true;
        }
        return false;
    }
    /**
     * 获取授权
     */
    public function getAccredit(){
        $res = $this->jdClient();
        $res && $this->JD->getAccredit();
    }

    /**
     * 获取accessToken
     */
    public function getAccessToken(){
        $res = $this->jdClient();
        if(!$res){
            $this->ajaxReturn(array('code'=>1,'msg'=>'参数有误','data'=>''));
        }
        if($this->verifyToken()){
            $code = I('get.code');
            $result = $this->JD->getAccessToken($code);
            if(empty($result)){
                $this->ajaxReturn(array('code'=>1,'msg'=>'授权失败，请重新授权！','data'=>''));
            }
            $data = array();
            $shop_config = array(
                'appKey'        => $this->shop_config['shop_config']['appKey'],
                'appSecret'     => $this->shop_config['shop_config']['appSecret'],
                'serverUrl'     => $this->shop_config['shop_config']['serverUrl'],
                'callbackUrl'   => $this->shop_config['shop_config']['callbackUrl'],
                'access_token'   => $result['access_token'],
                'validity_time' => intval(intval($result['time'])/1000) + intval($result['expires_in']) /* time 授权时间，expires_in 有效期 */
            );
            $data['shop_config'] = serialize($shop_config);
            
            $res = OtherShopService::setShopConfig($data,$this->shop_config['shop_id']);
            if(!$res){
                $this->ajaxReturn(array('code'=>1,'msg'=>'授权失败，请重新授权！','data'=>''));
            }
        }
        $data = array(
            'access_token'  => $this->shop_config['shop_config']['access_token'],
            'validity_time' => date('Y-m-d H:i:s',$this->shop_config['shop_config']['validity_time'])
        );
        $this->ajaxReturn(array('code'=>0,'msg'=>'授权成功！','data'=>$data));
        
    }

    /**
     * 展示授权选择页面
     */
    public function selectshop(){
        $this->assign('code',I('get.code'));
        $this->display('getaccredit');
    }
    /**
     * 检索商品
     */
    public function searchshop(){
        /* 实例jd基类 */
        $res = $this->jdClient();
        if(!$res){
            $this->ajaxReturn(array('code'=>1,'msg'=>'参数有误','data'=>''));
        }
        /* 验证授权 */
        if($this->verifyToken()){
            $this->ajaxReturn(array('code'=>1,'msg'=>'授权已过期，请重新授权！','data'=>''));
        }
        /* 实例请求参数类 */
        $searchshop = new WaresSearchRequest();
        $page = 1;
        $page_size = 20;
        /* 分页 必填 */
        $page && $searchshop->setPage($page);
        /* 每页多少条（范围是0至100） 必填 */
        $page_size && $searchshop->setPageSize($page_size);

        /* 商品分类ID 选填 */
        $cid && $searchshop->setCid($cid);
        /* 最小价格（分）选填 */
        $start_price && $searchshop->setStartPrice($start_price);
        /* 最大价格（分）选填 */
        $end_price && $searchshop->setEndPrice($end_price);
        /* 商品名称 选填 */
        $title && $searchshop->setTitle($title);
        /* 排序（默认onlineTime ）(offlineTime,onlineTime) 选填 */
        $order_by && $searchshop->setOrderBy($order_by);
        /* 起始创建时间(created) 选填 */
        $start_time && $searchshop->setStartTime($start_time);
        /* 结束创建时间(created) 选填 */
        $end_time && $searchshop->setEndTime($end_time);
        /* 起始的修改时间 选填 */
        $start_modified && $searchshop->setStartModified($start_modified);
        /* 结束的修改时间 选填 */
        $end_modified && $searchshop->setEndModified($end_modified);
        /* 1:在售;2:待售 选填 */
        $ware_status && $searchshop->setWareStatus($ware_status);
        /* 需返回的字段列表。可选值：ware结构体中的所有字段；字段之间用,分隔 选填 */
        $fields && $searchshop->setFields($fields);
        /* 店内分类一级分类 选填 */
        $parentShopCategoryId && $searchshop->setParentShopCategoryId($parentShopCategoryId);
        /* 店内分类二级分类 选填 */
        $shopCategoryId && $searchshop->setShopCategoryId($shopCategoryId);
        /* 商品货号 选填 */
        $itemNum && $searchshop->setItemNum($itemNum);
        /* 发送请求 */
        $resp = $this->JD->execute($searchshop, $this->JD->accessToken);
        if(empty($resp)){
            $this->ajaxReturn(array('code'=>1,'msg'=>'未查询到商品信息！','data'=>''));
        }else{
            $this->ajaxReturn(array('code'=>0,'msg'=>'查询成功','data'=>$resp));
        }
    }

    /**
     * 验证是否有授权或是否过期
     */
    public function verifyToken(){
        /* 如果accessToken 为空 或者 过期 重新拉取授权 */
        if(empty($this->shop_config['shop_config']['access_token']) || $this->shop_config['shop_config']['validity_time'] <= time()){
            return true;
        }
        return false;
    }
}