<?php

class ClassAction extends CommonAction{

    // 初始化
    public function _initialize() {

        parent::_initialize();

        $this->course = C('COURSE_TYPE');

        $this->school = loadCache('school');

        $this->schoolType = C('SCHOOL_TYPE');
    }

    // 展示列表
    public function index(){

        // 定义查询条件
        $where = array();

        if (isset($_REQUEST['c_title'])) {

            $where['c_title'] = array('LIKE', "%" . $_REQUEST['c_title'] . "%");
        }

        $schoolType = $this-> schoolType;

        $c_grade = C('GRADE_TYPE');

        // 得到分页数据
        $class = getListByPage('class', 'c_id',$where);

        $list = $class['list'];

        $school = $this->school;

        foreach ($list as $key => $value){

            // 加入学校名
            $list[$key]['schoolName'] = $school[$value['s_id']]['s_name'];

            $value['c_grade'] = YearToGrade($value['c_grade']);

            $list[$key]['c_type'] = $schoolType[$list[$key]['c_type']];

            // 加入班级名
            $list[$key]['c_title'] = replaceClassTitle($value['s_id'], $value[c_type], $value['c_grade'], $value['c_title'], $value['c_is_graduation'], $value['ma_id']);

            $list[$key]['c_is_graduation_name'] = $value['c_is_graduation'] ? '毕业' : '未毕业';
        }

        $this-> assign('list', $list);

        $this-> assign('page', $class['page']);

        $this-> display();

    }

    // 更新班级
    public function update(){

        // 接收数据
        $_POST['c_grade'] = GradeToYear($_POST['c_grade'], $_POST['s_id']);
        $_POST['c_title'] = $_POST['c_id'];
        $_POST['c_id'] = intval($_POST['cId']);
        if ($_POST['c_type'] != 4) {
            $_POST['ma_id'] = 0;
        }
        parent::update();

    }

    // 师生管理
    public function user(){

        $c_id=intval($_REQUEST['id']);

        // 查class_subject_teacher数据
        $coInfo = M('ClassSubjectTeacher')->where(array('c_id' => $c_id))->field('cst_id, cst_course, a_id, s_id')->select();

        // 得到a_id对应教师名
        $nickName = getDataByArray('Auth', $coInfo, 'a_id');

        // 将课程和老师加入$coInfo数组中
        $course = $this->course;
        foreach ($coInfo as $key => $value){

            $coInfo[$key]['co_title'] = $course[$value['cst_course']];

            $coInfo[$key]['a_nickname'] = $nickName[$value['a_id']]['a_nickname'] ;

        }

        $this->assign('course',$coInfo);

        // 得到学校名及班级名
        $classInfo = M('Class')->find($c_id);

        // 获取当前这个班级所属学校的学制
        $schoolType = $this-> schoolType;

        // 获取该学校学制类型
        $school = loadCache('school');

        $c_type = array();
        foreach (explode(',', $school[$classInfo['s_id']]['s_type']) as $key => $value) {
            $c_type[$value] = $schoolType[$value];
        }

        $this->c_type = $c_type;

        $school = $this->school;

        $schoolName = $school[$classInfo['s_id']]['s_name'];

        $className = replaceClassTitle($classInfo['s_id'], $classInfo['c_type'], YearToGrade($classInfo['c_grade']),$classInfo['c_title']);

        $classData['c_id'] = $c_id;

        $classData['c_title'] = $schoolName.'&nbsp&nbsp&nbsp'.$className;

        $classData['s_id'] = $classInfo['s_id'];

        $this->classData = $classData;

        // 得到学生信息
        $aId = M('ClassStudent')->where(array('c_id' => $_REQUEST['id']))->getField('a_id', true);

        $student = M('Auth')->where(array('a_id'=>array('IN', $aId)))->select();

        $this->assign('student',$student);

        $this->display();

    }

    //学生列表
    public function listStudents() {

        //接收参数
        $where['s_id'] = $_POST['s_id'];
        $where['a_school_type'] = intval($_POST['c_type']);
        $where['a_year'] = GradeToYear(intval($_POST['c_grade']));

        $c_id = intval($_POST['c_id']);
        if ($c_id) {

            $cs = M('ClassStudent')->where(array('c_id'))->field('a_id')->select();
            $where['a_id'] = array('IN', implode(',', getValueByField($cs, 'a_id')));
        }

        if ($_POST['a_nickname']) {
            $where['a_nickname'] = $_POST['a_nickname'];
        }

        // 获取数据
        $result = M("Auth")->where($where)->field('a_id,a_nickname')->select();

        // 返回列表
        echo json_encode($result);
    }

    // 给班级添加学生
    public function setAllStudents(){

        $aIdArr = explode(',', $_POST['a_id']);
        $cId = intval($_POST['c_id']);
        $s_id = intval($_POST['s_id']);

        $class = M('Class')->where(array('c_id' => $cId, 's_id' => $s_id))->find();

        foreach($aIdArr as $key => $value) {
            D('Auth')->addStudent($value, $class);
        }

        $result['status'] = 1;
        $result['info'] = '添加成功';

        echo json_encode($result);
    }


    // 编辑页面显示
    public function edit(){

        $gradeType = C('GRADE_TYPE');

        $vo=M('Class')->find($_REQUEST['id']);

        // 如果专业ID不为空
        if ($vo['ma_id']) {
            $this->major = M('Major')->where(array('s_id' => $vo['s_id']))->field('ma_id,ma_title')->select();
        }

        $vo['c_grade']=YearToGrade($vo['c_grade'], $vo['s_id']);

        // 得到没添加的班级
        $where['s_id'] = $vo['s_id'] ;

        $where['c_type'] = $vo['c_type'] ;

        $where['c_grade'] = GradeToYear($vo['c_grade'], $vo['s_id']);

        $c_title = M('Class')->where($where)->getField('c_title', true);

        foreach ($c_title as $key => $value){

            if ($value == $vo['c_title']) {

                unset($c_title[$key]);

            }
        }

        $tem = array();

        for ($i = 1 ; $i <= 20 ; $i ++) {

            if (!in_array($i, $c_title)) {

                $tem[] = $i ;
            }
        }

        $this->classNames = $tem ;

        $this->assign('gradeType',$gradeType[$vo['c_grade']]);

        $this->assign('vo',$vo);

        $this->display();
    }


    // 删除班级
    public function delete(){

        if ($_REQUEST['id']){

            $result = M('Class')-> delete($_REQUEST['id']);

            $this-> show($result);

        }else {

            $this->error('失败，请重试');

        }
    }


    // 删除学生
    public function deleteStudents(){

        // 接收数据
        $where['a_id'] = array('IN', $_REQUEST['a_id']);
        $where['c_id'] = intval($_REQUEST['c_id']);

        $result = M('ClassStudent')-> where($where)-> delete();

        if ($result) {

            $arr = array('status'=> 1, 'info'=> '删除成功');

            echo json_encode($arr);

        }else {

            $this->error('删除失败请重试');
        }
    }


    // 插入班级
    public function insert(){

        // 毕业年份
        $_POST['c_graduation'] = intval(count(getGradeByType($_POST['c_type']))) - intval($_POST['c_grade']) + intval(GradeToYear($_POST['c_grade'])) + 1;

        $_POST['ma_id'] = intval($_POST['c_type']) == 4 ? intval($_POST['ma_id']) : 0;

        $_POST['c_grade'] = GradeToYear($_POST['c_grade'], $_POST['s_id']);

        $_POST['c_title'] = $_POST['c_id'];

        unset($_POST['c_id']);

        parent::insert();
    }


     public function listByTypeAndGrade() {

        // 接收数据
        $where['c_type'] = intval($_POST['c_type']);
        $where['s_id'] = intval($_POST['s_id']);
        $where['c_grade'] = GradeToYear(intval($_POST['c_grade']));
        $where['ma_id'] = intval($_POST['ma_id']);
        $ma_id = intval($_POST['ma_id']);
        $is_ajax = intval($_POST['is_ajax']);

        $result = array();

        if (!$where['s_id'] || !$where['c_type'] || !$where['c_grade']) {
            if ($is_ajax) {
                echo json_encode($result);exit;
            } else {
                return $result;
                exit;
            }
        }

        if ($ma_id) {
            $where['ma_id'] = $ma_id;
        }

        $result = M('Class')->where($where)->field('c_id,c_title')->select();

        if ($is_ajax) {
            echo json_encode($result);
        } else {
            return $result;
        }
    }


    public function getClass(){

        // 接收查询条件
        $where['s_id'] = intval($_REQUEST['s_id']) ;
        $where['ma_id'] = intval($_REQUEST['ma_id']) ;
        $where['c_type'] = intval($_REQUEST['c_type']) ;
        $where['c_grade'] = GradeToYear($_REQUEST['c_grade'], $_REQUEST['s_id']);

        $c_title = M('Class')->where($where)->getField('c_title', TRUE);

        $tem = array();

        for ($i = 1 ; $i <= 20 ; $i ++) {
            if (!in_array($i, $c_title)) {
                $tem[] = $i ;
            }
        }

        echo json_encode($tem);
    }


    // 得到老师
    public function listTeachers(){

        // 接收数据
        $where['s_id'] = intval($_REQUEST['s_id']);
        $where['t_subject'] = intval($_REQUEST['cst_course']);

        // 查出学校下教授此课程的所有教师a_id
        $aId = M('Teacher')-> where($where)-> getField('a_id', true);
        if ($aId) {
            $teacher = M('Auth')-> field('a_id, a_nickname')-> select(implode(',', $aId));
        }

        echo json_encode($teacher);
    }


    // 设置授课教师
    public function setClassCourseTeacher(){

        // 接收数据
        $where['cst_id'] = intval($_REQUEST['cst_id']);

        $newTeacher = intval($_REQUEST['a_id']);

        $result = M('ClassSubjectTeacher')->where($where)->save(array('a_id'=>$newTeacher));

        echo $result;
    }
}