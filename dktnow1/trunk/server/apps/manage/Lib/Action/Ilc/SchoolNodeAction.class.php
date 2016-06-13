<?php
/**
 * SchoolAction
 * 学校管理
 *
 */
class SchoolNodeAction extends CommonAction {

    // 过滤
    public function _filter(&$map) {

        if (empty($_POST['search']) && !isset($map['sn_pid']) ) {
            $map['sn_pid'] = intval($map['sn_pid']);
        }

        $this->sn_pid = $map['sn_pid'];
    }

    // 新增
    public function _before_add() {

        // 获取所有顶级节点
        $this->nodes = M('SchoolNode')->where(array('sn_pid' => 0))->select();
    }

    public function insert() {

        $_POST['sn_level'] = intval($_POST['sn_pid']) ? 2 : 1;
        parent::insert();
    }

    public function _before_edit() {

        // 获取所有顶级节点
        $this->nodes = M('SchoolNode')->where(array('sn_pid' => 0, 'sn_id' => array('neq', $_GET['id'])))->select();
    }

    public function update() {

        $_POST['sn_level'] = intval($_POST['sn_pid']) ? 2 : 1;
        parent::update();
    }
}
?>
