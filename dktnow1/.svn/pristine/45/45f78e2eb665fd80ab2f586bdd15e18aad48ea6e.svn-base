<?php
/**
 * ResourceNoteAction
 * 资源笔记类
 *
 * 作者:  徐少龙
 * 创建时间: 2013-6-9
 *
 */
class ResourceNoteAction extends BaseAction {

    // 写入
    public function insert() {

        // 组织数据
        $_POST['s_id'] = $this->authInfo['s_id'];
        $_POST['a_id'] = $this->authInfo['a_id'];

        // 写入
        $result = parent::insertData();
        if (!$result) {
            $this->error('写入失败');
        }
        $this->success($result);

    }

    // 更新
    public function update() {

        // 组织数据
        $_POST['s_id'] = $this->authInfo['s_id'];
        $_POST['a_id'] = $this->authInfo['a_id'];

        // 更新
        $result = parent::update();
        if (!$result) {
            $this->error('更新失败');
        }
        $this->success('更新成功');
    }

    // 删除
    public function delete() {

        if (!intval($_POST['rn_id'])) {
            $this->error('非法操作');
        }

        // 组织数据
        $where['s_id'] = $this->authInfo['s_id'];
        $where['a_id'] = $this->authInfo['a_id'];
        $where['rn_id'] = intval($_POST['rn_id']);

        $result =   M('ResourceNote')->where($where)->delete();
        if (!$result) {
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }
}