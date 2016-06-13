<?php
/**
 * CommonModel
 * 公共模型
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-11-26
 *
 */
class CommonModel extends Model {

    // 获取当前会员的ID
    public function getMemberId() {
        return isset($_SESSION[C('MEMBER_AUTH_KEY')])? $_SESSION[C('MEMBER_AUTH_KEY')]: 0;
    }

    // 获取后台用户的ID
    protected function getUserId() {
        return $_SESSION[C('USER_AUTH_KEY')];
    }

    /**
     * pass
     * 审核通过
     * @access public
     * @param array $where 条件
     * @param string $field 字段
     * @return boolean
     */
    public function pass($where, $field){

        if(FALSE === $this->where($where)->setField($field, SELF::STATUS_NORMAL)){

            $this->error = L('_OPERATION_WRONG_');
            return false;
        }else {

            return True;
        }
    }

    /**
     * forbid
     * 根据条件禁用表数据
     * @access public
     * @param array $where 条件
     * @param string $field 字段
     * @return boolean
     */
    public function forbid($where, $field){

        if(FALSE === $this->where($where)->setField($field, 9)){

            $this->error = L('_OPERATION_WRONG_');
            return false;
        }else {

            return True;
        }
    }

    /**
     * resume
     * 根据条件恢复表数据
     * @access public
     * @param array $where 条件
     * @param string $field 字段
     * @return boolean
     */
    public function resume($where, $field){

        if (FALSE === $this->where($where)->setField($field, 1)) {

            $this->error = L('_OPERATION_WRONG_');
            return false;
        } else {
            return True;
        }
    }

    /**
     * recycle
     * 根据条件回收表数据
     * @access public
     * @param array $where 条件
     * @param string $field 字段
     * @return boolean
     */
    public function recycle($where, $field = 'status'){
        if (FALSE === $this->where($where)->setField($field, 0)) {
            $this->error = L('_OPERATION_WRONG_');
            return false;
        } else {
            return True;
        }
    }

    public function recommend($where, $field = 'is_recommend'){
        if (FALSE === $this->where($where)->setField($field, 1)) {
            $this->error = L('_OPERATION_WRONG_');
            return false;
        } else {
            return True;
        }
    }

    public function unrecommend($where, $field = 'is_recommend'){
        if (FALSE === $this->where($where)->setField($field, 0)) {
            $this->error = L('_OPERATION_WRONG_');
            return false;
        } else {
            return True;
        }
    }

}
?>