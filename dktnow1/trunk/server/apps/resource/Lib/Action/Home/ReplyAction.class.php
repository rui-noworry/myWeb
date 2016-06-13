<?php
/**
 * ReplyAction
 * 回复类
 *
 * 作者:  徐少龙
 * 创建时间: 2013-6-13
 *
 */
class ReplyAction extends BaseAction {

    public function _initialize() {

        parent::_initialize();

        // 判断是否登录
        if (!isLogin()) {
            $this->redirect('/Index');
        }
    }

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

        // 写入成功后，更新commit表的com_reply_count字段
        M('Commit')->where(array('com_id' => intval($_POST['com_id'])))->save(array('com_reply_count' => array('exp', 'com_reply_count+1')));

        // 回复时，还需先查看评论表，没有评论，且是第一次回复，则更新auth表里的积分字段
        $count = M('Commit')->where(array('a_id' => $this->authInfo['a_id']))->count();
        if ($count == 0) {
            $count = M('Reply')->where(array('a_id' => $this->authInfo['a_id']))->count();
            if ($count == 1) {
                M('Auth')->where(array('a_id' => $this->authInfo['a_id']))->save(array('a_points' => array('exp', 'a_points+1')));
            }
        }

        $this->success($result);
    }

    // 删除
    public function delete() {

        if (!intval($_POST['rep_id'])) {
            $this->error('非法操作');
        }

        $a_id = M('Reply')->where(array('rep_id' => intval($_POST['rep_id'])))->getField('a_id');

        if ($a_id != $this->authInfo['a_id']) {
            $this->error('没有权限执行此操作');
        }

        // 组织数据
        $where['rep_id'] = intval($_POST['rep_id']);

        // 同时在评论表里把com_reply_count减去一
        $result = M('Reply')->where($where)->delete();
        M('Commit')->where(array('com_id' => intval($_POST['com_id'])))->save(array('com_reply_count' => array('exp', 'com_reply_count-1')));
        if (!$result) {
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }

}