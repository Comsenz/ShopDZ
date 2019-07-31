<?php
/**
 * this file is not freeware
 * User: al_bat
 * DATE: 2016/4/15
 */
namespace Admin\Model;
use Think\Model;
class SettingModel extends Model{

    protected $_validate = array();

    public function Settings($data){
        if(empty($data)){
            return true;
        }
        $names = array_keys($data);
        $values = array_values($data);
        $condtion = array();
        $condtion['name'] = array("in",$names);
        $insert = array();
        foreach($data as $k => $d){
            $tmp = array();
            $tmp['name'] = $k;
            $tmp['value'] = $d;
            $insert[] = $tmp;
        }
        $this -> where($condtion) -> delete();
        return $this -> addAll($insert);
    }

    public function getSetting($key){
        if(is_array($key)){
            $config = $this -> select();
            $res = array();
            if($config && !empty($config)){
                foreach($config as $v){
                    $res[$v['name']] = $v['value'];
                }
            }
            $return = array();
            foreach($key as $v){
                $return[$v] = $res[$v];
            }
            return $return;
        }else {
            return $this->where("name='{$key}'")->getField("value");
        }
    }

    public function getSettings($condition = array()){
       $config = $this ->where($condition)-> select();
        $res = array();
        if($config && !empty($config)){
            foreach($config as $v){
                $res[$v['name']] = $v['value'];
            }
        }
        return $res;
    }
    public function getSettingsField($field){

    }
}