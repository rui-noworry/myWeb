<?php
/**
 * GroupAction
 * 分组管理
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-11-26
 *
 */
class GroupAction extends CommonAction {

    // 排序
    public function sort() {
        $where = array();

        if (!empty($_GET['sortId'])) {
            $where['g_id'] = array('IN', $_GET['sortId']);
        }

        // 条件
        $where['g_status'] = 1;

        // 排序
        $order = 'g_sort ASC';

        $sortList = M('Group') -> where($where) -> order($order) -> select();

        // 模板赋值
        $this->assign("sortList", $sortList);
        $this->display();
    }
}
?>