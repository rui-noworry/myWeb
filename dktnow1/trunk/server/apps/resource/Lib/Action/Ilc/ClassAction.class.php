<?php
/**
 * ClassAction
 * 班级模块
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-14
 *
 */
class ClassAction extends CommonAction{

    // 初始化
    public function _initialize() {

        parent::_initialize();

        // 获取配置参数
        $type = C('SCHOOL_TYPE');
        $school = loadCache('school');
        $allowType = explode(',', $school[$this->authInfo['s_id']]['s_type']);

        // 获取该学校学制类型
        $co_type = array();
        foreach ($allowType as $key => $value) {
            if ($value) {
                $co_type[$value] = $type[$value];
            }
        }

        if (!$co_type) {
            $this->error('本学校未设置学制');
        }

        // 获取配置参数
        $start = C('SCHOOL_YEAR_START');
        $end = C('SCHOOL_YEAR_END');

        // 计算学年
        $year = array();
        for ($i = $start; $i <= $end; $i ++) {
            $year[$i] = $i;
        }

        // 赋值
        $this->year = $year;
        $this->co_type = $co_type;
        $this->leftOn = 2;
    }

    public function index() {

        $this->display();
    }

    // 未指定班级列表
    public function chooseClassList() {

        // 接收参数
        $c_grade = intval($_POST['c_grade']);
        $ma_id = intval($_POST['ma_id']);
        $c_type = $where['c_type'] = intval($_POST['c_type']);
        $parm = intval($_POST['parm']);

        if (!$c_grade || !$c_type) {
            $this->error('参数错误');
        }

        if ($parm) {
            $c_title = M('Class')->where(array('c_id' => $parm))->getField('c_title');
            $where['c_title'] = array('neq', $c_title);
        }

        $s_id = $where['s_id'] = $this->authInfo['s_id'];
        $where['c_grade'] = GradeToYear($c_grade, $s_id);

        // 如果是大学，则要加个专业条件
        if ($ma_id) {
            $where['ma_id'] = $ma_id;
        }

        // 获取已经存在的班级名称
        $classTitle = M('Class')->where($where)->getField('c_title', TRUE);

        $classList = $this->setClassList($classTitle);
        echo json_encode($classList);
    }

    // 班级名称列表
    public function setClassList($arr) {
        $titleStr = ','.implode(',', $arr).',';

        for ($i = 0; $i < C('MAX_CLASS_COUNT'); $i ++) {

            $str = ','.($i+1).',';

            if (strstr($titleStr, $str) == FALSE) {

                $tmp['title'] = $i+1;
                $tmp['show'] = '('.($i+1).')班';

                $classList[] = $tmp;
            }
        }
        return $classList;
    }

    // 班级列表
    public function lists() {

        // 获取班级列表
        $result = D('Class')->lists($this->authInfo['s_id'], 0, '', 0);

        $result['status'] = 0;

        if ($result['list']) {
            $result['status'] = 1;
        }

        echo json_encode($result);
    }

    // 课程教师
    public function courseTeacher() {

        $c_id = intval($_POST['c_id']);
        $s_id = $this->authInfo['s_id'];

        if (!$c_id) {
            $this->error('非法操作');
        }

        // 班级数据
        $class = M('Class')->where(array('s_id' => $s_id, 'c_id' => $c_id))->find();

        if (!$class) {
            $this-> error('无班级');
        }

        // 班级课程
        $getXq = getXq($s_id);
        $where['c_id'] = $class['c_id'];
        $where['cst_year'] = $getXq['cc_year'];
        $where['cst_xq'] = $getXq['cc_xq'];
        $where['s_id'] = $s_id;

        // 查询班级课程
        $classCourseList = M("ClassSubjectTeacher")->where($where)->select();

        // 获取任课教师信息
        $teacherInfo = getDataByArray('Auth', $classCourseList, 'a_id');

        $result['courseList'] = $classCourseList;

        // 追加班主任ID到教师信息
        $headerAid[]['a_id'] = $class['a_id'];

        // 获取课程信息
        $course = C('COURSE_TYPE');

        // 获取班主任信息
        $headerInfo = getDataByArray('Auth', $headerAid, 'a_id');

        // 若无班级课程
        if (!$result['courseList']) {

            $conditions['s_id'] = $class['s_id'];
            $conditions['gc_type'] = $class['c_type'];
            $conditions['gc_grade'] = YearToGrade($class['c_grade']);
            $conditions['gc_status'] = 1;

            // 查询年级课程配置
            $gradeCourseList = M("GradeCourse")->where($conditions)->field('gc_course')->select();

            if (is_array($gradeCourseList)) {

                // 写入班级课程
                foreach ($gradeCourseList as $value) {

                    $where['cst_course'] = $value['gc_course'];
                    $where['cst_created'] = time();

                    unset($_POST);
                    $_POST = $where;

                    $_POST['cst_id'] = M("ClassSubjectTeacher")->add($_POST);

                    $classCourseList[] = $_POST;

                }
            }

            $result['courseList'] = $classCourseList;

        }

        // 获取班级学生ID
        $student = M("ClassStudent")->where(array('c_id' => $class['c_id'], 's_id' => $s_id))->select();

        // 获取学生信息
        $studentInfo = getDataByArray('Auth', $student, 'a_id');

        $result['studentList'] = $student;

        // 课程处理数据
        foreach ($result['courseList'] as $key => $value) {
            $result['courseList'][$key]['cst_course_name'] = $course[$value['cst_course']];
            $result['courseList'][$key]['a_nickname'] = $teacherInfo[$value['a_id']]['a_nickname'];
        }


        // 学生数据处理
        foreach ($result['studentList'] as $key => $value) {
            $result['studentList'][$key]['a_nickname'] = $studentInfo[$value['a_id']]['a_nickname'];
        }

        // 获取班主任信息
        $result['headerTeacher']['a_id'] = $class['a_id'];
        $result['headerTeacher']['a_nickname'] = $headerInfo[$class['a_id']]['a_nickname'];

        echo json_encode($result);

    }

    // 获取当前学校的老师列表
    public function getTeacherListBySid() {

       $result = D('Auth')->lists($this->authInfo['s_id']);

       echo json_encode($result);
    }

    // 指定班主任
    public function assignHeader() {

        // 接收参数
        $a_id = intval($_POST['a_id']);
        $c_id = intval($_POST['c_id']);
        $_POST['s_id'] = $this->authInfo['s_id'];

        if (!$c_id) {
            $this->error('参数错误');
        }

        if ($a_id) {

            // 判断此人是否为本学校的老师
            $auth = M('Auth')->where(array('a_id' => $a_id, 's_id' => $this->authInfo['s_id'], 'a_type' => 2))->find();

            if (!$auth) {
                $this->error('非法操作');
            }

            M('Auth')->where(array('a_id' => $a_id, 's_id' => $this->authInfo['s_id']))->save(array('a_has_class' => 1));

        }

        $old = M('Class')->where(array('c_id' => $c_id))->getField('a_id');

        $class = M('Class')->where($_POST)->find();

        if ($old) {

            $oldCst = M('ClassSubjectTeacher')->where(array('a_id' => $old, 's_id' => $this->authInfo['s_id']))->getField('c_id');
            $oldC = M('Class')->where(array('a_id' => $old, 's_id' => $this->authInfo['s_id']))->getField('c_id');

            if (!$oldCst && !$oldC) {
                M('Auth')->where(array('a_id' => $old, 's_id' => $this->authInfo['s_id']))->save(array('a_has_class' => 0));
            }
        }

        if ($class) {
            $result['status'] = 0;
            $result['info'] = '此人已经是该班级的班主任';
        } else {
            $res = M('Class')->save($_POST);
            $result['status'] = 1;
        }

        echo json_encode($result);

    }

    // 课程教师列表
    public function listTeachers() {

        $result = D('Class')->listTeachers($this->authInfo['s_id']);

        echo json_encode($result);
    }

    // 学生列表
    public function studentList() {

        $result = D('Auth')->studentList($this->authInfo['s_id'], 1);

        echo json_encode($result['list']);
    }

    // 指定课程教师
    public function assignCourseTeacher() {

        // 接收参数
        $a_id = intval($_POST['a_id']);
        $cst_id = intval($_POST['cst_id']);
        $old_a_id = intval($_POST['old_a_id']);
        $c_id = intval($_POST['c_id']);
        $cst_course = intval($_POST['cst_course']);

        $_POST['s_id'] = $this->authInfo['s_id'];

        if (!$cst_id || !$c_id || !$cst_course) {
            $this->error('参数错误');
        }

        if ($a_id) {
            // 验证
            if (!M('Auth')->where(array('a_id' => $a_id, 's_id' => $this->authInfo['s_id']))->getField('a_id')) {
                $this->error('非法操作');
            }

            M('Auth')->where(array('a_id' => $a_id, 's_id' => $this->authInfo['s_id']))->save(array('a_has_class' => 1));
        }

        // 更新班级课程教师
        M('ClassSubjectTeacher')->save($_POST);

        // 获取班级数据
        $class = M('Class')->where(array('c_id' => $_POST['c_id'], 's_id' => $_POST['s_id']))->find();

        // 写入教师课程历史
        $_POST['tc_type'] = $class['c_type'];
        $where = getXq($this->authInfo['s_id']);

        $_POST['tc_year'] = $where['cc_year'];
        $_POST['tc_xq'] = $where['cc_xq'];
        $_POST['tc_subject'] = $_POST['cst_course'];
        $_POST['tc_start_time'] = time();

        M('TeacherCourseLog')->add($_POST);

        unset($_POST);

        // 更新原有教师课程历史
        if ($old_a_id) {

            $_POST['tc_id'] = M('TeacherCourseLog')->where(array('a_id' => $old_a_id, 'c_id' => $class['c_id'], 'tc_end_time' => 0, 'tc_subject' => $cst_course))->getField('tc_id');
            $_POST['tc_end_time'] = time();

            M('TeacherCourseLog')->save($_POST);

            $oldCst = M('ClassSubjectTeacher')->where(array('a_id' => $old_a_id, 's_id' => $this->authInfo['s_id']))->getField('c_id');
            $oldC = M('Class')->where(array('a_id' => $old_a_id, 's_id' => $this->authInfo['s_id']))->getField('c_id');

            if (!$oldCst && !$oldC) {
                M('Auth')->where(array('a_id' => $old_a_id, 's_id' => $this->authInfo['s_id']))->save(array('a_has_class' => 0));
            }

            // 取消课程所绑定的班级
            $course = M('Course')->where(array('a_id' => $old_a_id))->field('co_id, c_id')->select();

            foreach ($course as $ck => $cv) {

                $strings = explode(',', trim($cv['c_id'], ','));

                foreach ($strings as $key => &$value) {
                    if ($value == $c_id) {
                        unset($strings[array_search($c_id, $strings)]);
                        break;
                    }
                }

                if (implode(',', $strings)) {
                    $data['c_id'] = ','.implode(',', $strings).',';
                } else {
                    $data['c_id'] = '';
                }

                $data['co_id'] = $cv['co_id'];

                M('Course')->save($data);
            }
        }

        $result['status'] = 1;
        echo json_encode($result);
    }

    // 批量添加学生
    public function insertStudents() {

        // 接收参数
        $aIdArr = explode(',', $_POST['a_id']);
        $cId = intval($_POST['c_id']);

        // 查找班级数据
        $class = M('Class')->where(array('c_id' => $cId, 's_id' => $this->authInfo['s_id']))->find();
        foreach($aIdArr as $key => $value) {
            D('Auth')->addStudent($value, $class);
        }

        $result['status'] = 1;

        echo json_encode($result);
    }

    // 删除学生
    public function deleteStudent(){

        // 接收参数
        $a_id = intval($_POST['a_id']);
        $c_id = intval($_POST['c_id']);

        if (!$a_id || !$c_id) {
            $this->error('参数错误');
        }

        $s_id = $this->authInfo['s_id'];

        // 班级人数减少
        M('Class')->where(array('c_id' => $c_id, 's_id' => $s_id))->setDec('c_peoples');

        // 班级学生关系
        M('ClassStudent')->where(array('a_id' => $a_id, 'c_id' => $c_id, 's_id' => $s_id))->delete();

        // 条件
        $where['s_id'] = $s_id;
        $where['as_end_time'] = 0;
        $where['c_id'] = $c_id;

        unset($_POST);

        // 学生在校记录
        $data['as_end_time'] = time();
        $data['as_status'] = 5;

        M("AuthSchool")->where($where)->save($data);

        $result['status'] = 1;

        echo json_encode($result);
    }


    // 插入
    public function insert() {

        if (!intval($_POST['c_grade']) || !intval($_POST['c_type']) || !$_POST['c_title']) {
            $result['status'] = 0;
            $result['info'] = '请将信息填写完整';
            echo json_encode($result);
            exit;
        }

        // 毕业年份
        $_POST['c_graduation'] = intval(count(getGradeByType($_POST['c_type'], $this->authInfo['s_id']))) - intval($_POST['c_grade']) + intval(GradeToYear($_POST['c_grade'])) + 1;

        // 当前学校
        $where['s_id'] = $_POST['s_id'] = $this->authInfo['s_id'];

        // 年级
        $c_grade = intval($_POST['c_grade']);

        // 年级转为年份
        $where['c_grade'] = $_POST['c_grade'] = intval(GradeToYear($c_grade, $this->authInfo['s_id']));

        // 创建时间
        $_POST['c_created'] = time();

        // 学段
        $where['c_type'] = intval($_POST['c_type']);

        // 专业
        $where['ma_id'] = intval($_POST['ma_id']);


        // 班级名称
        $where['c_title'] = trim($_POST['c_title']);

        // 判断添加的班级是否已经存在
        $class = M('Class')->where($where)->find();

        if ($class) {

            $result['status'] = 0;
            $result['info'] = '该班级已经存在';

        } else {

            $res = M('Class')->add($_POST);

            if ($res) {
                $result['status'] = 1;

                $result['c_replace_title'] = replaceClassTitle($this->authInfo['s_id'], intval($_POST['c_type']), $c_grade, trim($_POST['c_title']), 0, $where['ma_id']);
                $result['c_id'] = $res;
                $result['c_note'] = $_POST['c_note'];
                $result['s_name'] = getSchoolNameById($this->authInfo['s_id']);
                $result['classLogo'] = getClassLogo();
            }

        }

        echo json_encode($result);
    }

    // 课程表
    public function syllabus() {

        // 验证
        $id = intval($_REQUEST['id']);

        // 获取班级信息
        $class = M('Class')->where(array('c_id' => $id, 's_id' => $this->authInfo['s_id']))->find();

        // 获取课程表
        $lists = M('Syllabus')->where(array('s_id' => $this->authInfo['s_id'], 'c_id' => $class['c_id']))->select();
        $courseType = C('COURSE_TYPE');

        // 整理数据
        $tmp = array();
        foreach ($lists as $key => $value) {
            $tmp[$value['sy_num']][$value['sy_day']]['course'] = $courseType[$value['sy_subject']];
            $tmp[$value['sy_num']][$value['sy_day']]['sy_id'] = $value['sy_id'];
        }

        for ($i = 1; $i < 9; $i ++) {
            for ($j = 1; $j < 8; $j ++) {
                $res[$i][$j]['course'] = $tmp[$i][$j]['course'];
                $res[$i][$j]['sy_id'] = $tmp[$i][$j]['sy_id'];
            }
        }

        // 赋值
        $this->assign('lists', $res);
        $this->assign('courseType', $courseType);
        $this->assign('class', $class);
        $this->display();
    }

    // 更新课程表
    public function updateSyllabus() {

        // 验证
        $c_id = M('Class')->where(array('c_id' => intval($_POST['c_id']), 's_id' => $this->authInfo['s_id']))->getField('c_id');

        if (!$c_id) {
            echo 0;exit;
        }

        $sy_id = intval($_POST['sy_id']);
        if ($sy_id && $id = M('Syllabus')->where(array('sy_id' => $sy_id, 's_id' => $this->authInfo['s_id'], 'c_id' => intval($_POST['c_id'])))) {
            M('Syllabus')->where(array('sy_id' => $sy_id))->save(array('sy_subject' => intval($_POST['sy_subject']), 'sy_updated' => time()));
        } else {
            $_POST['s_id'] = $this->authInfo['s_id'];
            $_POST['a_id'] = $this->authInfo['a_id'];
            $_POST['sy_created'] = time();

            M('Syllabus')->add($_POST);
        }

        echo 1;
    }

    public function edit() {

        // 接收参数
        $c_id = intval($_POST['c_id']);

        if (!$c_id) {
            $this->error('参数错误');
        }

        $class = M('Class')->where(array('c_id' => $c_id, 's_id' => $this->authInfo['s_id']))->find();
        $class['grade'] = YearToGrade($class['c_grade'], $this->authInfo['s_id']);
        $class['major'] = $class['ma_id'];
        $classInfo['info'] = $class;

        // 获取配置参数
        $type = C('SCHOOL_TYPE');
        $school = loadCache('school');
        $allowType = explode(',', $school[$this->authInfo['a_id']]['s_type']);

        // 获取该学校学制类型
        $co_type = array();
        foreach ($allowType as $key => $value) {
            if ($value) {
                $tmp['id'] = $value;
                $tmp['title'] = $type[$value];
                $classInfo['cType'][] = $tmp;
            }
        }

        $classTitle = M('Class')->where(array('c_id' => array('neq', $class['c_id']), 's_id' => $this->authInfo['s_id'], 'c_type' => $class['c_type'], 'c_grade' => $class['c_grade'], 'ma_id' => array('eq', $class['ma_id'])))->getField('c_title', TRUE);

        $classInfo['grade'] = getGradeByType($class['c_type'], $class['s_id']);
        if ($class['major']) {
            $classInfo['major'] = M('Major')->where(array('s_id' => $this->authInfo['s_id']))->field('ma_id,ma_title')->select();
            $tmp = array();
            foreach ($classInfo['major'] as $k => $v) {
                $tmp[$v['ma_id']] = $v['ma_title'];
            }
            $classInfo['major'] = array();
            $classInfo['major'] = $tmp;
        }
        $classInfo['list'] = $this->setClassList($classTitle);

        echo json_encode($classInfo);

    }

    public function update() {

        // 接收参数
        $c_id = intval($_POST['c_id']);

        if (!$c_id) {
            $this->error('参数错误');
        }

        // 获取班级信息
        $class = M('Class')->where(array('c_id' => $c_id, 's_id' => $this->authInfo['s_id']))->find();

        $c_grade = $_POST['c_grade'];
        $_POST['c_grade'] = GradeToYear($c_grade, $this->authInfo['s_id']);

        $check = M('ClassSubjectTeacher')->where(array('c_id' => $c_id, 's_id' => $this->authInfo['s_id']))->getField('cst_id');

        if ($check && (intval($_POST['c_type']) != $class['c_type'] || intval($_POST['c_grade']) != $class['c_grade'])) {
            $this->error('此班级已有课程，请不要修改学制年级');
        }

        $res = M('Class')->save($_POST);

        if ($res !== false) {

            // 返回页面数据
            $class['c_title'] = replaceClassTitle($this->authInfo['s_id'], $_POST['c_type'], $c_grade, $_POST['c_title'], 0, $_POST['ma_id']);
        }
        echo json_encode($class);


    }
}
?>