<?php
/**
 * NodeAction
 * 节点管理
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-11-26
 *
 */
class NodeAction extends CommonAction {

    public function _filter(&$map) {

        if (empty($_POST['search']) && !isset($map['n_pid']) ) {
            $map['n_pid'] = intval($map['n_pid']);
        }
        
        $_SESSION['currentNodeId'] = $map['n_pid'];

        //获取上级节点
        $node  = D("Node");
        if (isset($map['n_pid'])) {
            if($node->find($map['n_pid'])) {
                $this->assign('n_level', $node->n_level+1);
                $this->assign('n_name', $node->n_name);
            }else {
                $this->assign('n_level',1);
            }
        }
    }

    public function _before_index() {
        $this->groupList = loadCache('group');
    }

    public function fleshMenu() {
        unset($_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]]);
        $this->success('刷新菜单数据完成');
    }

    // 获取配置类型
    public function _before_add() {
        $this->list = loadCache('group');
        $node       = D("Node");
        $node->find($_SESSION['currentNodeId']);
        $this->assign('n_pid', $node->n_id);
        $this->assign('n_level', $node->n_level+1);
    }

    public function _before_patch() {
        $this->list = loadCache('group');
        $node       = D("Node");
        $node->find($_SESSION['currentNodeId']);
        $this->assign('n_pid',$node->n_id);
        $this->assign('n_level',$node->n_level+1);
    }

    public function _before_edit() {
        $this->list = loadCache('group');
    }

    // 排序
    public function sort() {

        $node = D('Node');

        if (!empty($_GET['sortId'])) {
            $map = array();
            $map['n_status'] = 1;
            $map['n_id'] = array('IN', $_GET['sortId']);
            $sortList = $node->where($map)->order('n_sort ASC')->select();
        } else {
            if (!empty($_GET['n_pid'])) {
                $pid = $_GET['n_pid'];
            } else {
                $pid = $_SESSION['currentNodeId'];
            }
            if ($node->find($pid)) {
                $level = $node->n_level+1;
            } else {
                $level = 1;
            }
            $this->assign('n_level', $level);

            $where['n_status'] = 1;
            $where['n_pid'] = $pid;
            $where['n_level'] = $level;
            $sortList = $node->where($where)->order('n_sort ASC')->select();
        }

        $this->assign("sortList", $sortList);
        $this->display();
        return ;
    }
}
?>