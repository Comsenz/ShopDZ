<?php
/**
 * this file is not freeware
 * User: chenpeiping
 * DATE: 2016/4/21
 */
namespace Admin\Model;
use Think\Model;
class NotesModel extends Model{

    protected $trueTableName = 'pre_member_msg_tpl'; 

    /**
     * 用户消息模板列表
     * @param array $condition
     * @param string $field
     * @param string $order
     */
    public function getMemberMsgTplList($condition, $field = '*', $order = 'mmt_code asc') {
        return $this->field($field)->where($condition)->order($order)->select();
    }

    /**
     * 用户消息模板详细信息
     * @param array $condition
     * @param string $field
     */
    public function getMemberMsgTplInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 编辑用户消息模板
     * @param array $condition
     * @param unknown $update
     */
    public function editMemberMsgTpl($condition, $update) {
        return $this->where($condition)->save($update);
    }
}