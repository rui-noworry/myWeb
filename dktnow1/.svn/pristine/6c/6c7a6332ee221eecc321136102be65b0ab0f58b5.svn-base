<?php
/**
 * ApplyAuthAction
 * 用户申请模块
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-9
 *
 */
class ApplyAuthAction extends BaseAction{

    // 申请页面
    public function index() {

        // 接收参数
        $ac_id = intval($_REQUEST['ac_id']);
        $as_id = intval($_REQUEST['as_id']);

        if ($this->authInfo['s_id']) {
            $s_id = $this->authInfo['s_id'];
        } else {
            $s_id = intval($_REQUEST['s_id']);
        }

        if (!$ac_id && !$as_id && !$s_id) {
            $this->error('参数错误');
        }

        // 获取申请班级信息
        $classInfo = M('ApplyClass')->where(array('ac_id' => $ac_id))->field('ac_title, ac_type, ac_grade')->find();

        if (!$as_id) {

            $ac_grade = YearToGrade($classInfo['ac_grade'], $s_id);

            // 转换班级名称
            $classTitle = replaceClassTitle($s_id, $classInfo['ac_type'], $ac_grade, $classInfo['ac_title']);

            // 获取学校名称
            $schoolTitle = getSchoolNameById($s_id);

        } else {

            $ac_grade = YearToGrade($classInfo['ac_grade']);

            // 转换班级名称
            $classTitle = replaceClassTitle('', $classInfo['ac_type'], $ac_grade, $classInfo['ac_title']);

            // 获取学校名称
            $schoolTitle = M('ApplySchool')->where(array('as_id' => $as_id))->getField('as_title');

        }

        // 页面赋值
        $this->assign('classTitle', $classTitle);
        $this->assign('schoolTitle', $schoolTitle);
        $this->assign('ac_id', $ac_id);
        $this->assign('as_id', $as_id);
        $this->assign('s_id', $s_id);
        $this->display();
    }

    // 批量添加学生
    public function insert() {

        $result['status'] = 0;
        // 接收参数
        $ac_id = intval($_POST['ac_id']);
        $as_id = intval($_POST['as_id']);
        $s_id  = intval($_POST['s_id']);
        $studentName = trim($_POST['studentName']);

        if (!$ac_id && !$studentName && !$as_id && !$s_id) {
            $result['status'] = 0;
            echo json_encode($result);exit;
        }

        $time = M('ApplyAuth')->where(array('a_id' => $this->authInfo['a_id']))->order('aa_id DESC')->getField('aa_created');

        if (time() - $time < 10) {
            echo json_encode($result);
            exit;
        }

        // 获取学制年级
        $gradeType = M('ApplyClass')->where(array('ac_id' => $ac_id))->field('ac_grade, ac_type, ac_is_pass')->find();

        if (!$as_id && $s_id) {
            $data['s_id'] = $s_id;
        } else {
            $data['as_id'] = $as_id;
        }

        // 组织数据
        $data['ac_id'] = $ac_id;
        $data['a_id'] = $this->authInfo['a_id'];
        $data['aa_grade'] = YearToGrade($gradeType['ac_grade']);
        $data['aa_schoolType'] = $gradeType['ac_type'];

        if ($gradeType['ac_is_pass']) {
            $data['c_id'] = $gradeType['ac_is_pass'];
        }

        $data['aa_created'] = time();

        $studentArr = explode(',', $studentName);

        foreach ($studentArr as $name) {

            $data['aa_nickname'] = $name;
            M('ApplyAuth')->add($data);
        }

        $result['status'] = 1;
        echo json_encode($result);

    }

    // 申请成为学生/老师
    public function applyTo() {

        // 接收参数
        $type = intval($_POST['type']);
        $c_id = intval($_POST['c_id']);
        $s_id = intval($_POST['s_id']);

        if (!$type && !$c_id && !$s_id) {
            $this->error('参数错误');
        }

        // 判断是否已经申请过
        $applyCount = M('ApplyAuth')->where(array('c_id' => $c_id, 's_id' => $s_id, 'a_id' => $this->authInfo['a_id'], 'aa_type' => $type, 'aa_a_id' => $this->authInfo['a_id']))->count();

        if ($applyCount) {
            $result['info'] = '对不起, 您已经申请过';
            $result['status'] = 0;

            echo json_encode($result);exit;
        }

        if ($this->authInfo['s_id']) {

            if ($s_id != $this->authInfo['s_id'] && $this->authInfo['a_type'] == $type) {
                $s_title = getSchoolNameById($this->authInfo['s_id']);

                if ($type == 1) {
                    $result['info'] = '您已经是'.$s_title.'学校的老师';
                }

                if ($type ==2) {
                    $result['info'] = '您已经是'.$s_title.'学校的学生';
                }

                $result['status'] = 0;
                echo json_encode($result);exit;
            }
        }

        // 如果申请成为老师
        if ($type == 2) {

            if ($this->authInfo['s_id']) {

                if ($this->authInfo['s_id'] == $s_id && $this->authInfo['a_type'] == $type) {

                    $result['info'] = '您已经是该学校的老师';
                    $result['status'] = 0;

                    echo json_encode($result);exit;
                }
            }
        }

        // 申请成为学生
        if ($type == 1) {

            // 判断是否已经是该学校该班级的学生
            $count = M('ClassStudent')->where(array('a_id' => $this->authInfo['a_id'], 's_id' => $s_id, 'c_id' => $c_id))->count();

            if ($count) {
                $result['info'] = '您已经是该班级的学生';
                $result['status'] = 0;

                echo json_encode($result);exit;
            }
        }

        // 获取年级
        $gradeArr = M('Class')->where(array('c_id' => $c_id))->field('c_grade, c_type')->find();
        $grade = YearToGrade($gradeArr['c_grade'], $gradeArr['s_id']);
        $gType = $gradeArr['c_type'];

        // 组织数据
        $data['c_id'] = $c_id;
        $data['s_id'] = $s_id;
        $data['a_id'] = $this->authInfo['a_id'];
        $data['aa_grade'] = $grade;
        $data['aa_schoolType'] = $gType;
        $data['aa_type'] = $type;
        $data['aa_nickname'] = $this->authInfo['a_nickname'];
        $data['aa_created'] = time();
        $data['aa_a_id'] = $this->authInfo['a_id'];

        $res = M('ApplyAuth')->add($data);
        if ($res) {
            $result['status'] = 1;
        }

        echo json_encode($result);

    }

    public function applyToTeacher() {

        // 接收参数
        $c_id = intval($_POST['c_id']);
        $s_id = intval($_POST['s_id']);
        $course = trim($_POST['course']);

        if (!$c_id && !$course && !$s_id) {
            $this->error('参数错误');
        }

        // 获取年级
        $gradeArr = M('Class')->where(array('c_id' => $c_id))->field('c_grade, c_type, s_id')->find();
        $grade = YearToGrade($gradeArr['c_grade'], $gradeArr['s_id']);
        $gType = $gradeArr['c_type'];

        // 组织数据
        $data['c_id'] = $c_id;
        $data['s_id'] = $s_id;
        $data['a_id'] = $this->authInfo['a_id'];
        $data['aa_grade'] = $grade;
        $data['aa_type'] = 2;
        $data['aa_nickname'] = $this->authInfo['a_nickname'];
        $data['aa_created'] = time();

        $courseArr = explode(',', $course);

        foreach ($courseArr as $v) {
            $data['aa_subject'] = $v;
            $res = M('ApplyAuth')->add($data);
        }

        if ($res) {
            $result['status'] = 1;
        }

        echo json_encode($result);

    }
}
?>