<?php
/**
 * ClassAction
 * 班级
 *
 * 作者:  黄蕊
 * 创建时间: 2013-7-4
 *
 */
class ClassAction extends OpenAction {

	// 根据班级ID获得学生列表
    public function students() {

        // 接收参数
        extract($_POST['args']);

        if (empty($c_id) || empty($a_id)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

         // 判断是否为班主任
        if (!M('Class')->where(array('a_id' => $a_id, 'c_id' => $c_id))->find()) {

            // 检查是否为任课教师
            if (!M('ClassSubjectTeacher')->where(array('c_id' => $c_id, 'a_id' => $a_id))->getField('cst_id')) {
                $this->ajaxReturn($this->errCode[4]);
                exit;
            }
        }

        // 获取学生ID
        $aIds = M('ClassStudent')->where(array('c_id' => $c_id))->select();

        // 获取学生信息
        $student = getDataByArray('Auth', $aIds, 'a_id', 'a_id, a_account, a_nickname, a_avatar, a_type, a_sex');

        foreach ($student as $key => $value) {
            $student[$key]['a_avatar'] = turnTpl(getAuthAvatar($value['a_avatar'], $value['a_type'], $value['a_sex']));
        }

        sort($student);

        $this->ajaxReturn($student);
    }

    // 班级列表
    public function lists() {

        // 接收参数
        extract($_POST['args']);

        $auth = $this->auth;

        if ($auth['a_type'] != 2 || $auth['a_id'] != $a_id) {
            $this->ajaxReturn($this->errCode[4]);
            exit;
        }

        $xq = getXq($auth['s_id']);

        $where['a_id'] = $auth['a_id'];
        $where['cst_year'] = $xq['cc_year'];
        $where['cst_xq'] = $xq['cc_xq'];

        // 获取教师本学期教授的班级学科
        $classCourse = M('ClassSubjectTeacher')->where($where)->select();

        // 获取班级信息
        $class = getDataByArray('Class', $classCourse, 'c_id', 'c_id,c_type,c_grade,c_title,c_is_graduation');

        foreach ($class as $key => $value) {
            $class[$key]['c_name'] = replaceClassTitle($auth['s_id'], $value['c_type'], GradeToYear($value['c_grade']), $value['c_title'], $value['c_is_graduation']);
        }

        $this->ajaxReturn($class);

    }

}
?>