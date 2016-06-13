<?php
/**
 * CommitAction
 * 评论类
 *
 * 作者:  徐少龙
 * 创建时间: 2013-6-9
 *
 */
class CommitAction extends BaseAction {

    // 写入
    public function insert() {

        // 组织数据
        $_POST['s_id'] = $this->authInfo['s_id'];
        $_POST['a_id'] = $this->authInfo['a_id'];

        if (!intval($_POST['com_object_id'])) {
            $this->error('非法操作');
        }

        $res = M('Resource')->where(array('re_id' => $_POST['com_object_id']))->find();
        if (!$res) {
            $this->error('非法操作');
        }

        $_POST['com_owner_id'] = $res['a_id'];
        $_POST['com_object_type'] = $res['m_id'];

        // 写入
        $result = parent::insertData();
        if (!$result) {
            $this->error('写入失败');
        }

        // 如果是第一次评论，还需更新auth表里的积分字段
        $count = M('Commit')->where(array('a_id' => $this->authInfo['a_id']))->count();
        if ($count == 1) {
            M('Auth')->where(array('a_id' => $this->authInfo['a_id']))->save(array('a_points' => array('exp', 'a_points+1')));
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

        if (!intval($_POST['com_id'])) {
            $this->error('非法操作');
        }

        $a_id = M('Commit')->where(array('com_id' => intval($_POST['com_id'])))->getField('a_id');

        if ($a_id != $this->authInfo['a_id']) {
            $this->error('没有权限执行此操作');
        }

        // 组织数据
        $where['com_id'] = intval($_POST['com_id']);

        // 删除评论后，还得删除相应的回复
        $result =   M('Commit')->where($where)->delete();
        M('Reply')->where($where)->delete();
        if (!$result) {
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }

}