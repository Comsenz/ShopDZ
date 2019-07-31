<?php
/**
 * this file is not freeware
 * User: juan
 * DATE: 2016/4/15
 */
namespace Admin\Model;
use Think\Model;
class AreaModel extends Model{

    protected $_validate = array();

    /**
     * 获取地址列表
     *
     * @return mixed
     */
    public function getAreaList($condition = array(), $fields = '*', $group = '') {
        return $this->where($condition)->field($fields)->group($group)->select();
    }
   
    /**
    * 根据id获取子地区
    **/
    public function getChildArea($area_parent_id = 0){
        return $this -> where("area_parent_id='$area_parent_id'")->select();
    }

     /**
    *   根据条件查询地区数量
    */
    public function getAreaCount($whereArray = array()) {
        return $this -> where($con) -> count();
    }

    /**
     * 获取地址详情
     *
     * @return mixed
     */
    public function getAreaInfo($condition = array(), $fileds = '*') {
        return $this->where($condition)->field($fileds)->find();
    }

    /**
     * 递归取得本地区及所有上级地区名称
     * @return string
     */
    public function getTopAreaName($area_id,$area_name = '') {
        $info_parent = $this->getAreaInfo(array('area_id'=>$area_id),'area_name,area_parent_id');
        if ($info_parent) {
            return $this->getTopAreaName($info_parent['area_parent_id'],$info_parent['area_name']).' '.$info_parent['area_name'];
        }
    }
    /**
     * 递归取得本地区及所有上级地区名称
     * @return string
     */
    public function getTopAreaId($area_id) {
		$area_id = intval($area_id);
		return $info_parent = $this->getAreaInfo(array('area_id'=>$area_id),'area_name,area_parent_id,area_deep,area_id');
    }

    /**
     * 获取用于前端js使用的全部地址数组
     *
     * @return array
     */
    public function getAreaArrayForJson($src = 'cache') {
        if ($src == 'cache') {
            $data = $this->getCache();
        } else {
            $data = $this->_getAllArea();
        }

        $arr = array();
        foreach ($data['children'] as $k => $v) {
            foreach ($v as $vv) {
                $arr[$k][] = array($vv, $data['name'][$vv]);
            }
        }
        return $arr;
    }

    /**
     * 获取地区数组 格式如下
     * array(
     *   'name' => array(
     *     '地区id' => '地区名称',
     *     // ..
     *   ),
     *   'parent' => array(
     *     '子地区id' => '父地区id',
     *     // ..
     *   ),
     *   'children' => array(
     *     '父地区id' => array(
     *       '子地区id 1',
     *       '子地区id 2',
     *       // ..
     *     ),
     *     // ..
     *   ),
     *   'region' => array(array(
     *     '华北区' => array(
     *       '省级id 1',
     *       '省级id 2',
     *       // ..
     *     ),
     *     // ..
     *   ),
     * )
     *
     * @return array
     */
    protected function getCache() {
        // 对象属性中有数据则返回
        if ($this->cachedData !== null)
            return $this->cachedData;

        // 缓存中有数据则返回
        if ($data = F('area')) {
            $this->cachedData = $data;
            return $data;
        }

        // 查库
        $data = $this->_getAllArea();
        F('area', $data);
        $this->cachedData = $data;

        return $data;
    }

     /**
     * 递归取得本地区所有孩子ID
     * @return array
     */
    public function getChildrenIDs($area_id) {
        $result = array();
        $list = $this->getAreaList(array('area_parent_id'=>$area_id),'area_id');
        if ($list) {
            foreach ($list as $v) {
                $result[] = $v['area_id'];
                $result = array_merge($result,$this->getChildrenIDs($v['area_id']));
            }
        }
        return $result;
    }


    protected $cachedData;

    private function _getAllArea() {
        $data = array();
        $area_all_array = $this->select();
        foreach ((array) $area_all_array as $a) {
            $data['name'][$a['area_id']] = $a['area_name'];
            $data['parent'][$a['area_id']] = $a['area_parent_id'];
            $data['children'][$a['area_parent_id']][] = $a['area_id'];
        
            if ($a['area_deep'] == 1 && $a['area_region'])
                $data['region'][$a['area_region']][] = $a['area_id'];
        }
        return $data;
    }


}