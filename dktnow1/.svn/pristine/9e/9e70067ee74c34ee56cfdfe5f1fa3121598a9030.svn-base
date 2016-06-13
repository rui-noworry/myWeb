<?php

/**
 * StudentlAction
 * 学生管理
 *
 * 作者:  翟一江
 * 创建时间: 2013-5-15
 *
 */
class StudentAction extends CommonAction {

    private $studentModel;

    //  初始化
    public function _initialize() {

        parent::_initialize();
        $this->studentModel = D('Student');

        $this->course = C('COURSE_TYPE');
        $this->school = loadCache('school');
        $this->schoolType = C('SCHOOL_TYPE');
    }


    // 列表展示
    public function index() {

        // 接收数据
        $sId = intval($_REQUEST['s_id']);
        $cId = intval($_REQUEST['c_id']);
        $cType = intval($_REQUEST['c_type']);
        $cGrade = intval($_REQUEST['c_grade']);
        $ma_id = $cType == 4 ? intval($_POST['c_major']) : 0;

        // 返回上次搜索条件
        $this->prevsId = $sId;
        $this->prevcId = $cId;
        $this->prevcType = $cType;
        $this->prevcGrade = $cGrade;
        $this->prevMajor = $ma_id;

        $csType = $this-> schoolType;
        $allSchool = $this-> school;
        if ($ma_id) {
            $this->major = M('Major')->where(array('s_id' => $sId))->field('ma_id,ma_title')->select();
        }

        // 上次搜索班级信息
        $classInfo = $this->studentModel->getClassInfo($sId, $cId, $cType, $ma_id, $cGrade, $csType, $allSchool);
        $this->csType = $classInfo['csType'];
        $this->grade = $classInfo['grade'];
        $this->class = $classInfo['class'];

        // 查询条件
        if ($cId) {
            $aIds = M('ClassStudent')->where(array('c_id'=> $cId))->getField('a_id', true);
        } else {
            if ($sId && !$cType) {
                $where['s_id'] = $sId;
            } elseif ($cType) {
                if($sId) {
                    $whereClass['s_id'] = $sId;
                }
                if($cType) {
                    $whereClass['c_type'] = $cType;
                }
                if($cGrade) {
                    $whereClass['c_grade'] = GradeToYear($cGrade, $sId);
                }
                if($ma_id) {
                    $whereClass['ma_id'] = $ma_id;
                }
                $c_id = M('Class')->where($whereClass)->getField('c_id', true);
                $aIds = M('ClassStudent')->where(array('c_id' => array('IN', $c_id)))->getField('a_id', true);
            }
        }

        if ($aIds) {
            $where['a_id'] = array('IN', $aIds);
        } elseif(!$aIds && $cType) {
            $this->display();
            exit;
        }

        if (!empty($_REQUEST['a_nickname'])) {
            $where['a_nickname'] = array('LIKE', "%".$_REQUEST['a_nickname']."%");
        }

        $where['a_type'] = 1;

        // 分页获取学生数据
        $student =  getListByPage('Auth', 'a_id DESC', $where);

        // 获取所在班级
        $classStudent = M('ClassStudent')->where(array('a_id' => array('IN', implode(',', getValueByField($student['list'], 'a_id')))))->select();

        // 获取班级数据
        $class = getDataByArray('Class', $classStudent, 'c_id');

        // 整理数据
        $tmp = array();
        foreach ($classStudent as $csKey => $csValue) {
            $tmp[$csValue['a_id']][] = replaceClassTitle($class[$csValue['c_id']]['s_id'], $class[$csValue['c_id']]['c_type'], GradeToYear($class[$csValue['c_id']]['c_grade'], $class[$csValue['c_id']]['s_id']), $class[$csValue['c_id']]['c_title'], $class[$csValue['c_id']]['c_is_graduation'], $class[$csValue['c_id']]['ma_id']);
        }
        foreach ($student['list'] as $key => $value) {
            $student['list'][$key]['s_name'] = $this->school[$value['s_id']]['s_name'];
            $student['list'][$key]['c_title'] = implode(',', $tmp[$value['a_id']]);
        }

        $this->assign('list', $student['list']);
        $this->assign('page', $student['page']);
        $this->display();
    }

    //  插入
    public function insert() {

        if ($_FILES['a_avatar']['size'] > 0) {

            // 上传封面
            $allowType = C('ALLOW_FILE_TYPE');
            $_POST['a_avatar'] = parent::upload($allowType['image'], C('AUTH_AVATAR'), true, '96,48', '96,48', '96/,48/');
        }

        $c_grade=GradeToYear($_POST['c_grade'], $_POST['s_id']);

        // 将学生插入到用户表并记录a_id
        $_POST['a_password'] = md5(getStrLast($_POST['a_account']));
        $_POST['a_birthday'] = strtotime($_POST['a_birthday']);
        $_POST['a_school_type'] = $_POST['c_type'];
        $_POST['a_applications'] = C('DEFAULT_APP');
        $_POST['a_year'] = GradeToYear($_POST['c_grade'], $_POST['s_id']);
        $_POST['ma_id'] = $_POST['c_grade'] == 4 ? $_POST['ma_id'] : 0;

        $a_id = parent::insertData();

        // 将a_id和c_id插入到class_student表
        $student = D('Student');
        $student->addClassStudent($a_id);

        // class表中班级人数字段自增一
        M('Class')->where(array('c_id' => $_REQUEST['c_id']))-> save(array('c_peoples' => array('exp', 'c_peoples + 1')));

        // 加入auth_school表
        $result = $student->addAuthSchool($a_id);

        $this->show($result);
    }


    // 编辑显示
    public function edit() {

        // 接收数据
        $aId = $_REQUEST['id'];

        //  获取学生数据
        $vo = $this->studentModel->getStudentInfo($aId);

        // 获取学生班级Id
        $classId=M('ClassStudent')->where(array('a_id' => $aId))->getField('c_id', true);

        if ($classId) {
            $classInfo=M('Class')->select(implode(',', $classId));
        }

        // 在classInfo中加入班级名称,所有学制，年级，班级
        foreach ($classInfo as $key => $value){

            $csType = $this-> schoolType;
            $allSchool = $this-> school;
            $gradeType = C('GRADE_TYPE');

            // 加入班级名称
            $classInfo[$key]['className'] =  replaceClassTitle($value['s_id'], $value['c_type'], YearToGrade($value['c_grade'], $value['s_id']), $value['c_title'], $value['c_is_graduation'], $value['ma_id']);

            // 加入学制类型
            $types = $allSchool[$value['s_id']]['s_type'];
            $types = explode(',', $types);
            foreach ($csType as $csKey => $csVa){
                if (!in_array($csKey, $types)) {
                    unset($csType[$csKey]);
                }
            }

            $classInfo[$key]['scType'] = $csType ;

            // 加入年级
            $grade = YearToGrade($value['c_grade'], $value['s_id']);
            $classInfo[$key]['course'] = $gradeType[$value['c_type']] ;
            $classInfo[$key]['c_grade'] = $grade ;

            // 加入班级
            $classWhere = array();
            $classWhere['s_id'] = $value['s_id'];
            $classWhere['c_type'] = $value['c_type'];
            $classWhere['c_grade'] = $value['c_grade'];
            $classWhere['ma_id'] = $value['ma_id'];
            $c_title = M('Class')->where($classWhere)->field('c_title,c_id')->select();//print_r($c_title);
            foreach ($c_title as  $oneClass){
                $classInfo[$key]['class'][$oneClass['c_id']] = $oneClass['c_title'];
            }
            $classInfo[$key]['major'] = M('Major')->where(array('s_id' => $value['s_id']))->field('ma_id,ma_title')->select();
         }

        $this->assign('classInfo',$classInfo);
        $this->assign('vo', $vo);
        $this->display();
    }

    // 更新
    public function update(){

        $_POST['a_birthday'] = strtotime($_POST['a_birthday']);

        parent::update();
    }

    // 删除
    public function delete(){

        // 接收删除a_id
        $id = strval($_REQUEST['id']);

        // 删除用户表数据
        M('Auth')->delete($id);

        // 记录学生班级c_id
        $cId = M('ClassStudent')->where(array('a_id' => $id))->getField('c_id', true);

        // 删除class_student表数据
         $result = M('ClassStudent')->where(array('a_id'=>array('IN',$id)))->delete();

         // class表中学生数量自减一
         M('Class')->where(array('c_id' => array('IN', $cId)))-> save(array('c_peoples' => array('exp', 'c_peoples - 1')));

         $this->show($result);

    }


    // 检查用户是否已填加过
    public function check(){

        // 接收数据
        $account = $_REQUEST['a_account'];

        // 查看是否存在
        $result = M('Auth')->where(array('a_account' => $account))->find();

        // 输出结果
        echo json_encode($result);
    }

    // 修改学生所在班级
    public function modifyStudentToClass() {

        // 接收参数

        // 添加学生的ID
        $a_id = intval($_POST['a_id']);

        // 选择的班级
        $c_id = intval($_POST['c_id']);

        // 之前的班级ID
        $curCid = intval($_POST['curCid']);

        // 当前学校ID
        $s_id = intval($_POST['s_id']);

        if (!$a_id && !$c_id) {
            $result['status'] = 0;
            echo json_encode($result);exit;
        }

        $class = M('Class')->where(array('c_id' => $c_id, 's_id' => $s_id))->find();

        if (!$class) {
            $result['status'] = 0;
            echo json_encode($result);exit;
        }

        $oldsid = M('Auth')->where(array('a_id' => $a_id))->getField('s_id');
        if (!$oldsid) {
            M('Auth')->where(array('a_id' => $a_id))->setField('s_id', $s_id);
        }

        // 判断该学生是否已经存在于该班级
        $res = M('ClassStudent')->where(array('c_id' => $c_id, 's_id' => $s_id, 'a_id' => $a_id))->find();

        if ($res) {

            $result['status'] = 0;
            $result['info'] = '您已经是这个班级的学生';

        } else {

            D('Auth')->addStudent($a_id, $class, $curCid);
            $result['status'] = 1;
            $c_grade = YearToGrade($class['c_grade'], $s_id);
            $result['info'] = replaceClassTitle($s_id, $class['c_type'], $c_grade, $class['c_title'], $class['c_is_graduation'], $class['ma_id']);
            $result['c_id'] = $class['c_id'];
        }

        echo json_encode($result);
    }

    // 删除班级
    public function deleteClass(){

        // 接收数据
        $data['a_id'] = intval($_REQUEST['a_id']);
        $data['c_id'] = intval($_REQUEST['c_id']);

        // 判断是否是唯一的
        $isAlone = M('ClassStudent')->where(array('a_id' => $data['a_id']))->select();

        if (count($isAlone) <= 1) {
            echo '0';    // 唯一返回0
            exit;
        }

        // 不唯一删除
        M('ClassStudent')->where($data)->delete();

        // class表中记录自减一
        M('Class')->where(array('c_id' => $data['c_id']))-> save(array('c_peoples' => array('exp', 'c_peoples - 1')));

        // 更改AuthSchool中记录状态
        $result = M('AuthSchool')->where($data)->save(array('as_status' => 5));

        // 返回删除结果，成功返回1，失败返回2
        if ($result) {
            echo '1';
        }else {
            echo '2';
        }
    }

}