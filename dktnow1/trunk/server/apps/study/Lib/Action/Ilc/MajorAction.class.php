<?php
/**
 * MajorAction
 * 专业设置
 *
 * 作者:  徐少龙
 * 创建时间: 2013-8-2
 *
 */
class MajorAction extends CommonAction {

    public function index() {

        // 如果该校没有大学，则转到首页
        $school = loadCache('school');
        if (substr($school[$this->authInfo['s_id']]['s_type'], -1) != 4) {
            $this->redirect('/Index');
        }

        $this->list = M('Major')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $this->authInfo['s_id']))->field('ma_id,ma_title')->select();
        $this->display();
    }

    // 添加
    public function insert() {

        // 检测
        $arr['ma_title'] = strval($_POST['ma_title']);
        if ($arr['ma_title'] == '' || $this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        $arr['a_id'] = $this->authInfo['a_id'];
        $arr['s_id'] = $this->authInfo['s_id'];
        $arr['ma_created'] = time();
        $result = M('Major')->add($arr);

        if ($result) {
            $this->success($result);
        }

        $this->error('添加失败');
    }

    // 修改
    public function update() {

        // 检测
        $arr['ma_title'] = strval($_POST['ma_title']);
        $where['ma_id'] = intval($_POST['ma_id']);
        if ($arr['ma_title'] == '' || !$where['ma_id'] || $this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        $result = M('Major')->where($where)->save($arr);
        if ($result) {
            $this->success('修改成功');
        }

        $this->error('修改失败');
    }

    // 删除
    public function delete() {

        // 检测
        $ma_id = strval($_POST['ma_id']);
        if (!$ma_id || $this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }
        $where['ma_id'] = array('IN', $ma_id);

        $result = M('Major')->where($where)->delete();
        if ($result) {
            $this->success('删除成功');
        }

        $this->error('删除失败');
    }
}