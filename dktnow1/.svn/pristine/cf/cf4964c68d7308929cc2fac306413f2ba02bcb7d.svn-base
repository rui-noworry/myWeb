<?php
/**
 * TrendAction
 * 动态
 *
 * 创建时间: 2013-5-13
 *
 */
class TrendAction extends BaseAction {

    public function lists() {

        // 接收参数
        $c_id = intval($_POST['c_id']);
        $subject = intval($_POST['subject']);
        $is_ajax = intval($_POST['is_ajax']);
        $p = intval($_POST['p']) ? intval($_POST['p']) : 1;

        echo D('Trend')->lists($this->authInfo['a_id'], $this->authInfo['a_type'], $c_id, $subject, $is_ajax, $p);
    }
}

?>