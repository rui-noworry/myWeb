<?php
/**
 * SchoolNodeModel
 * 学校管理
 *
 * 作者:  赵鹏 (zhaop@mink.com.cn)
 * 创建时间: 2013-5-02
 *
 */
class SchoolNodeModel extends CommonModel {

    protected $_validate = array(
        array('sn_name', 'require', '标题有误'),
        array('sn_title', 'require', '显示名不可为空'),
        array('sn_name', 'checkNode', '节点已经存在', 0, 'callback'),
    );

    protected $_auto = array(
        array('sn_created', 'time', self::MODEL_INSERT, 'function'),
        array('sn_updated', 'time', self::MODEL_UPDATE, 'function'),
        );

    public function checkNode() {
        if(is_string($_POST['sn_name'])) {
            $map['sn_name']   = $_POST['sn_name'];
            $map['sn_pid']    = isset($_POST['sn_pid'])? $_POST['sn_pid']: 0;
            $map['sn_status'] = 1;

            if(!empty($_POST['sn_id'])) {
                $map['sn_id'] = array('neq',$_POST['sn_id']);
            }

            $result = $this->where($map)->getField('sn_id');

            if($result) {
                return false;
            }else{
                return true;
            }
        }
        return true;
    }
}
?>