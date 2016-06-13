<?php
/**
 * GradeCourseAction
 * 年级课程配置
 *
 * 作者:  翟一江
 * 创建时间: 2013-5-30
 *
 */
class GradeCourseAction extends CommonAction {

    // 初始化
    public function _initialize() {

        parent::_initialize();

        // 获取配置参数
        $this->schoolType = C('SCHOOL_TYPE');
        $allowType = C('ALLOW_SCHOOL_TYPE');
        $this->school = loadCache('school');

        // 获取该学校学制类型
        $cc_type = array();
        foreach ($allowType as $key => $value) {
            $cc_type[$value] = $type[$value];
        }

        // 赋值
        $this->gc_type = $cc_type;
        $this->gc_course = C('COURSE_TYPE');
        $this->s_id = C('SCHOOL_ID');

    }

    // 列表
    public function index() {

        // 接收数据
        $sId = intval($_POST['s_id']);
        $gcType = intval($_POST['gc_type']);
        $gcGrade = intval($_POST['gc_grade']);
        $courseName = $_POST['courseName'];
        $ma_id = $gcType == 4 ? intval($_POST['gc_major']) : 0;

        if ($sId) {

            // 搜索条件回传
            $this->prevsId = $sId;
            $this->prevgcType = $gcType;
            $this->prevGrade = $gcGrade;
            $this->prevMajor = $ma_id;

            // 取年级信息供固定搜索下拉框使用
            $studentModel = D('GradeCourse');
            $csType = $this-> schoolType;
            $allSchool = $this-> school;
            if ($ma_id) {
                $this->major = M('Major')->where(array('s_id' => $sId))->field('ma_id,ma_title')->select();
            }

            $classInfo = $studentModel->getClassInfo($sId, $gcType, $gcGrade, $csType, $allSchool);
            $this->csType = $classInfo['csType'];
            $this->grade = $classInfo['grade'];
        }


        // 组装搜索条件
        if ($sId) {
            $where['s_id'] = $sId;
        }
        if ($gcType) {
            $where['gc_type'] = $gcType;
        }
        if ($gcGrade) {
            $where['gc_grade'] = $gcGrade;
        }
        if ($courseName) {
            $where['gc_course'] = array_keys($this->gc_course, $courseName);
        }
        if ($ma_id) {
            $where['ma_id'] = $ma_id;
        }

        //  分页获取数据
        $gradeCourse =  getListByPage('GradeCourse', 'gc_id DESC', $where);

        // 加入学校名、学制名、学科名
        $gradeType = C('GRADE_TYPE');
        foreach ($gradeCourse['list'] as $likey => $livalue) {
            $gradeCourse['list'][$likey]['schoolName'] = $this->school[$livalue['s_id']]['s_name'];
            $gradeCourse['list'][$likey]['schoolType'] = $this->schoolType[$livalue['gc_type']];
            $gradeCourse['list'][$likey]['grade'] = $gradeType[$livalue['gc_type']][$livalue['gc_grade']];
            $gradeCourse['list'][$likey]['course'] = $this->gc_course[$livalue['gc_course']];
        }


        $this->page = $gradeCourse['page'];
        $this->list = $gradeCourse['list'];
        $this->display();
    }

    // 插入
    public function insert() {

        $data['s_id'] = intval($_POST['s_id']);
        $data['gc_type'] = intval($_POST['gc_type']);
        $data['gc_grade'] = GradeToYear(intval($_POST['gc_grade']), $data['s_id']);
        $_POST['ma_id'] = $data['gc_type'] == 4 ? intval($_POST['gc_major']) : 0;
        $_POST['a_id'] = $_SESSION['authId'];
        $data['ma_id'] = $_POST['ma_id'];

        $class = M('Class')->where($data)->field('c_id')->select();

        // 获取学科
        $gc_course_arr = $_POST['gc_course'];

        if ($gc_course_arr) {
            foreach ($gc_course_arr as $gc_course) {

                if ($class) {

                    foreach ($class as $key => $value) {
                        $getXq = getXq($data['s_id']);
                        $where['c_id'] = $value['c_id'];
                        $where['s_id'] = $data['s_id'];
                        $where['cst_course'] = intval($gc_course);
                        $where['cst_year'] = $getXq['cc_year'];
                        $where['cst_xq'] = $getXq['cc_xq'];
                        $where['cst_created'] = time();
                        M('ClassSubjectTeacher')->add($where);
                    }
                }

                $_POST['gc_course'] = intval($gc_course);
                parent::insert();
            }
        } else {
            $this->success('没有新增');
        }

    }


    // 显示编辑页面
    public function edit(){

        $gcId = intval($_REQUEST['id']);

        // 查出grade_course数据
        $gradeCourse = M('GradeCourse')->find($gcId);

        // 加入学制类型
        $csType = $this->schoolType;
        $allSchool = $this->school;
        $types = $allSchool[$gradeCourse['s_id']]['s_type'];
        $types = explode(',', $types);
        foreach($csType as $csKey => $csVa){
            if (!in_array($csKey, $types)) {
                unset($csType[$csKey]);
            }
        }

        if ($gradeCourse['ma_id']) {
            $this->major = M('Major')->where(array('s_id' => $gradeCourse['s_id']))->field('ma_id,ma_title')->select();
        }

        // 加入年级
        $gradeType = C('GRADE_TYPE');
        $grade = $gradeType[$gradeCourse['gc_type']];

        $this->grade = $grade;
        $this->csType = $csType;
        $this->vo = $gradeCourse;
        $this->display();
    }

    // 更新
    public function update() {

        $where['s_id'] = intval($_REQUEST['s_id']);
        $where['gc_type'] = intval($_REQUEST['gc_type']);
        $where['gc_grade'] = intval($_REQUEST['gc_grade']);
        $where['gc_course'] = intval($_REQUEST['gc_course']);
        $where['ma_id'] = $where['gc_type'] == 4 ? intval($_POST['gc_major']) : 0;
        $_POST['ma_id'] = $where['ma_id'];

        // 判断是否已存在
        $exists = M('GradeCourse')->where($where)->find();
        if ($exists) {
            $this->error('此年级已有该课程');
        }

        $data['s_id'] = intval($_POST['s_id']);
        $data['c_type'] = intval($_POST['gc_type']);
        $data['c_grade'] = GradeToYear(intval($_POST['gc_grade']));
        $data['ma_id'] = $where['ma_id'];

        $class = M('Class')->where($data)->field('c_id')->select();

        if ($class) {

            $this->error('年级下已存在班级，不可更改学科');
        }
        parent::update();
    }

    // 根据学制年级获取学科
    public function getSubjects(){

        // 课程
        $courseType = $this->gc_course;

        $where['s_id'] = $_POST['s_id'];
        $where['gc_type'] = $_POST['gc_type'];
        $where['gc_grade'] = $_POST['gc_grade'];
        $where['ma_id'] = $_POST['ma_id'];

        // 获取已选择课程
        $gradeCourse = M('GradeCourse')->where($where)->select();

        $str = '';
        foreach ($gradeCourse as $gv) {
            $str .= ','.$gv['gc_course'];
        }
        $str .= ',';

        foreach ($courseType as $ck=>$cv) {
            $cks = ','.$ck.',';

            if (strstr($str, $cks)) {
                $course[$ck]['checked'] = 1;
            } else {
                $course[$ck]['checked'] = 0;
            }

            $course[$ck][] = $cv;
            $course[$ck]['id'] = $ck;
        }

        foreach ($course as $rv) {
            $result[] = $rv;
        }

        echo json_encode($result);

    }
}
?>