<?php
/**
 * ClassGroupAction
 * 班级小组模块
 *
 * 作者: 黄蕊
 * 创建时间: 2013-5-23
 *
 */
class ClassGroupAction extends BaseAction{

    // 添加小组
    public function insert() {

        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        // 接收参数
        $c_id = intval($_POST['c_id']);
        $groupTitleStr = trim($_POST['groupTitleStr']);

        if (!$c_id && !$groupTitleStr) {
            $this->error('参数错误');
        }

        // 组织数据
        $data['c_id'] = $c_id;
        $data['a_id'] = $this->authInfo['a_id'];
        $data['cg_created'] = time();

        $groupArr = explode(',', $groupTitleStr);
        $logoArr = explode(',', trim($_POST['cg_logo']));

        foreach ($groupArr as $key => $value) {

            $data['cg_title'] = $value;
            $data['cg_logo'] = $logoArr[$key];
            $tmp = $data;
            $tmp['cg_id'] = M('ClassGroup')->add($data);
            $result[] = $tmp;
        }

        foreach ($result as $key => $value) {
            $result[$key]['a_nickname'] = $this->authInfo['a_nickname'];
        }

        echo json_encode($result);
    }

    // 小组列表
    public function lists() {

        $c_id = $where['c_id'] = intval($_POST['c_id']);

        if (!$c_id) {
            $this->error('参数错误');
        }

        // 如果是班主任
        if ($this->authInfo['class_manager']) {

            // 获取我所任班级的任课教师的ID
            $a_ids = M('ClassSubjectTeacher')->where(array('c_id' => $c_id, 'a_id' => array('neq', 0)))->getField('a_id', TRUE);
            array_push($a_ids, $this->authInfo['a_id']);
            if ($a_ids) {
                $where['a_id'] = array('IN', $a_ids);
            }
        }

        // 如果是任课教师
        if ($this->authInfo['subject_teacher_class']) {
            $where['a_id'] = $this->authInfo['a_id'];
        }

        // 如果是学生
        if ($this->authInfo['a_type'] == 1) {

            // 获取我所在的班级小组
            $cg_ids = M('ClassGroupStudent')->where(array('c_id' => $c_id, 'a_id' => $this->authInfo['a_id']))->getField('cg_id', TRUE);
            $where['cg_id'] = array('IN', $cg_ids);
        }

        $groups = M('ClassGroup')->where($where)->field('cg_id, cg_title, cg_logo, a_id')->select();

        $getAuthInfo = getDataByArray('Auth', $groups, 'a_id', 'a_id, a_nickname');

        foreach ($groups as $key => $value) {
            $groups[$key]['a_nickname'] = $getAuthInfo[$value['a_id']]['a_nickname'];
        }

        echo json_encode($groups);

    }

    public function update(){

        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        $cg_id = $where['cg_id'] = intval($_POST['cg_id']);
        $cg_title = trim($_POST['cg_title']);
        if (intval($_POST['cg_logo'])) {
            $cg_logo = intval($_POST['cg_logo']);
        }

        if (!$cg_id && !$cg_title) {
            $this->error('参数错误');
        }

        $_POST['cg_updated'] = time();
        $res = M('ClassGroup')->save($_POST);
        echo 1;
    }

     public function del() {

        if ($this->authInfo['a_type'] != 2) {
            $this->error('非法操作');
        }

        $cg_id = intval($_POST['cg_id']);

        if (!$cg_id) {
            $this->error('参数错误');
        }

        // 删除班级小组学生关系
        if (M('ClassGroupStudent')->where(array('cg_id' => $cg_id, 's_id' => $this->authInfo['s_id']))->count()) {
            M('ClassGroupStudent')->where(array('cg_id' => $cg_id, 's_id' => $this->authInfo['s_id']))->delete();
        }

        $res = M('ClassGroup')->where($_POST)->delete();

        if ($res) {
            echo 1;
        }
    }
}
?>