<?php
/**
 * ResourceCategory
 * 学校栏目
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-9
 *
 */
class ResourceCategoryAction extends CommonAction{

    public function index() {
        $this->assign('maxLevel', C('RESOURCE_CATEGORY_MAX_LEVEL'));
        $this->display();
    }

    // ajax显示
    public function ajaxShow() {

        $id = intval($_POST['rc_pid']);

        $return = array();
        $return['status'] = 0;

        $where['s_id'] = $this->authInfo['s_id'];

        if ($id > 0) {
            $check = M('ResourceCategory')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id'], 'rc_id' => $id))->find();

            if (!$check) {
                $return['message'] = '无权限操作';
                echo json_encode($return);
                exit;
            }

            if ($check['rc_num'] > 0) {
                $return['message'] = '此栏目下有资源，请不要直接添加子栏目';
                echo json_encode($return);
                exit;
            }
        }

        $where['rc_pid'] = $id;

        // 获取栏目列表
        $return = getListByPage('ResourceCategory', 'rc_id DESC', $where, 5, TRUE, intval($_POST['p']));
        $return['status'] = 1;
        $cateList = D('ResourceCategory')->showParent($_POST['rc_pid'], $this->authInfo['s_id']);
        $return['cateList'] = $cateList ? $cateList : '无';
        $return['pid'] = $check['rc_pid'];
        $return['level'] = $check['rc_level'] + 1;
        echo json_encode($return);

    }

    // 写入数据
    public function insert() {

        // 组织数据
        $_POST['s_id'] = $this->authInfo['s_id'];
        $_POST['a_id'] = $this->authInfo['a_id'];

        // 在添加数据前，先查询添加的pid对应的level
        $level = 0;
        if (intval($_POST['rc_pid']) != 0) {
            $level = M('ResourceCategory')->where(array('rc_id' => $_POST['rc_pid']))->getField('rc_level');
            if ($level == C('RESOURCE_CATEGORY_MAX_LEVEL')) {
                $this->error('超过最大子栏目级数');
            }
        }

        $_POST['rc_level'] = $level + 1;

        $result['id'] = parent::insertData();
        $result['level'] = $_POST['rc_level'];

        if (!$result) {
            $this->error('添加栏目失败');
        }
        $this->success($result);
    }

    // 更新
    public function update() {

        // 组织数据
        $_POST['s_id'] = $this->authInfo['s_id'];
        $_POST['a_id'] = $this->authInfo['a_id'];

        $result = parent::updateData();
        if (!$result) {
            $this->error('修改栏目失败');
        }
        $this->success('修改栏目成功');

    }

    // 删除
    public function delete() {

        // 如果删除的是父节点，那么还需查看其下是否有子节点，有的话不允许删除
        $id = strval($_POST['id']);
        if (!$id) {
            $this->error('非法操作');
        }

        $where['rc_pid'] = strlen($id) == 1 ? $id : array('IN', $id);
        $where['a_id'] = $this->authInfo['a_id'];
        $where['s_id'] = $this->authInfo['s_id'];
        $flag = M('ResourceCategory')->where($where)->select();
        if ($flag) {
            $this->error('请清空勾选栏目下的子栏目');
            exit;
        }

        // 删除
        $where['rc_id'] = $where['rc_pid'];
        unset($where['rc_pid']);
        $flag = M('ResourceCategory')->where($where)->delete();
        if (!$flag) {
            $this->error('栏目删除失败');
        }
        $this->success('栏目删除成功');
    }

    public function getInfo() {

        $data = M('ResourceCategory')->select();
        $id = 1;
        $info = findSubTree($data, $id);
        print_r($info);

    }

    // 异步查询栏目添加子栏目时该栏目的rc_num的是否为0
    public function ajaxSearch() {

        $count = M('ResourceCategory')->where(array('rc_id' => intval($_POST['rc_id'])))->getField('rc_num');
        if ($count) {
            $this->error('上级栏目下已经有资源，不能添加子栏目');
        }
        $this->success($count);
    }
}
?>