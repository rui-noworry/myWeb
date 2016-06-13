<?php
/**
 * ClassGroupStudentAction
 * 班级小组学生模块
 *
 * 作者: 黄蕊
 * 创建时间: 2013-5-24
 *
 */
class ClassGroupStudentAction extends BaseAction{

    // 给班级小组添加学生
    public function insert() {

        $a_id = explode(',', $_POST['a_id']);
        $c_id = intval($_POST['c_id']);
        $cg_id = intval($_POST['cg_id']);

        if (!$a_id || !$cg_id || !$c_id) {
            $this->error('参数错误');
        }

        $check = M('ClassGroup')->where(array('cg_id' => $cg_id, 'c_id' => $c_id, 's_id' => $this->authInfo['s_id'], 'a_id' => $this->authInfo['a_id']))->find();

        if (!$check) {
            $this->error('非法操作');
        }

        M('ClassGroupStudent')->where(array('c_id' => $c_id, 'cg_id' => $cg_id, 's_id' => $this->authInfo['s_id']))->delete();

        $_POST['s_id'] = $this->authInfo['s_id'];

        $data['s_id'] = $this->authInfo['s_id'];
        $data['c_id'] = $c_id;
        $data['cg_id'] = $cg_id;

        foreach ($a_id as $value) {
            $data['a_id'] = $value;
            M('ClassGroupStudent')->add($data);
        }
        $this->success('操作成功');
    }

    // 班级小组的学生列表
    public function lists() {

        // 接收参数
        $cg_id = intval($_POST['cg_id']);

        if (!$cg_id) {
            $this->error('参数错误');
        }

        // 获取学生ID
        $res = M('ClassGroupStudent')->where(array('cg_id' => $cg_id, 's_id' => $this->authInfo['s_id']))->getField('a_id', TRUE);

        echo json_encode($res);
    }
}
?>