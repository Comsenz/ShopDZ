<?php
/**
 * this file is not freeware
 * User: al_bat
 * DATE: 2016/4/15
 */
namespace Admin\Model;
use Think\Model;
class SpecialModel extends Model{
    protected $tableName = "special_item";
    protected $_validate = array();

    public function addItem($data){
        return $this -> add($data);
    }
    public function getItemList($con,$field = "*",$start = 0,$limit = 40 ,$order = " item_sort asc "){
        return $this -> where($con) -> field($field)-> limit($start,$limit) -> order($order) -> select();
    }

    public function itemSort($update, $item_id, $special_id){
        if(isset($update['item_data'])) {
            $update['item_data'] = serialize($update['item_data']);
        }
        $condition = array();
        $condition['item_id'] = $item_id;
        return $this ->where($condition)->save($update);
    }

    public function itemStatus($usable, $item_id, $special_id){
        $update = array();
        if($usable == 'usable') {
            $update['item_usable'] = 1;
        } else {
            $update['item_usable'] = 0;
        }
        return $this -> where(array('item_id'=>$item_id)) -> save($update);
    }

    public function itemDel($condition){
        return $this->where($condition)->delete();
    }

    /**
     * 检查专题项目是否存在
     * @param array $condition
     *
     */
    public function isSpecialItemExist($condition) {
        $item_list = $this->where($condition)->select();
        if($item_list) {
            return true;
        } else {
            return false;
        }
    }
}