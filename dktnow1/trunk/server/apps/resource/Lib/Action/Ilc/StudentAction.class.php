<?php
/**
 * StudentAction
 * 学生模块
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-9
 *
 */
class StudentAction extends CommonAction{

    // 初始化
    public function _initialize() {

        parent::_initialize();
        // 获取当前学校
        $school = loadCache('school');
        $this->s_id = $this->authInfo['s_id'];

        // 获取配置参数
        $type = C('SCHOOL_TYPE');
        $schoolType = explode(',', $school[$this->authInfo['s_id']]['s_type']);

        // 获取该学校学制类型
        $cc_type = array();
        foreach ($schoolType as $key => $value) {
            $cc_type[$value] = $type[$value];
        }

        // 赋值
        $this->cc_type = $cc_type;
        $this->course = C('COURSE_TYPE');
    }

    public function index(){

        $this->display();
    }

    // 获取当前学校的学制
    public function getSchoolType() {

        // 获取当前学校
        $school = loadCache('school');

        // 获取配置参数
        $type = C('SCHOOL_TYPE');
        $schoolType = explode(',', $school[$this->authInfo['s_id']]['s_type']);

        // 获取该学校学制类型
        $cc_type = array();
        foreach ($schoolType as $key => $value) {
            $tmp['id'] = $value;
            $tmp['name'] = $type[$value];
            $result[] = $tmp;
        }

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
        $s_id = $this->authInfo['s_id'];

        if (!$a_id && !$c_id) {
            $this->error('参数错误');
        }

        $class = M('Class')->where(array('c_id' => $c_id, 's_id' => $s_id))->find();

        if (!$class) {
            $this->error('非法操作');
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
            $result['info'] = replaceClassTitle($s_id, $class['c_type'], $c_grade, $class['c_title'], $class['is_graduation'], $class['ma_id']);
            $result['c_id'] = $class['c_id'];
        }

        echo json_encode($result);
    }

    public function lists() {

        $result = D('Auth')->studentList($this->authInfo['s_id']);
        echo json_encode($result);
    }

    public function insert() {

        // 获取默认密码
        $_POST['a_password'] = getStrLast($_POST['a_account']);

        $_POST['a_type'] = intval($_POST['a_type']);

        if ($_POST['a_type'] != 1) {
            $this->error('操作失败');
        }

        // 获取当前学校
        $_POST['s_id'] = $this->authInfo['s_id'];

        if (!$_POST['c_id']) {
            $this->error('请选择班级');
        }

        $_POST['a_school_type'] = intval($_POST['a_school_type']);
        $_POST['a_year'] = GradeToYear($_POST['a_grade'], $_POST['s_id']);
        $_POST['a_applications'] = C('DEFAULT_APP');
        $_POST['a_birthday'] = strtotime($_POST['a_birthday']);
        $_POST['a_status'] = 1;

        // 是否有上传
        if ($_FILES['a_avatar']['size'] > 0) {

            // 上传头像
            $allowType = C('ALLOW_FILE_TYPE');
            $_POST['a_avatar'] = parent::upload($allowType['image'], C('AUTH_AVATAR'), true, '96,48', '96,48', '96/,48/');
        }

        $model = D('Auth');

        if (false === $model->create()) {
            $this->error($model->getError());
        }

        //保存当前数据对象
        if (false !== $model->add()) {

            $jumpUrl = '__APPURL__/' . GROUP_NAME . '/' . $this->getActionName() . '/index';

            //成功提示
            $this->assign('jumpUrl', $jumpUrl);
            $this->success('成功');
        } else {

            //失败提示
            $this->error('失败');
        }

    }

    // 编辑
    public function edit() {

        // 获取数据
        $vo = M('Auth')->where(array('a_id' => intval($_REQUEST['id']), 's_id' => $this->authInfo['s_id'], 'a_type' => 1))->find();

        if (!$vo) {
            $this->error('非法操作');
        }

        $vo['a_year'] = YearToGrade($vo['a_year'], $vo['s_id']);

        $vo['a_region2'] = '"'.str_replace('###','","',$vo['a_region']).'"';
        $vo['a_avatar'] = getAuthAvatar($vo['a_avatar'], $vo['a_type'], $vo['a_sex']);

        // 获取所在班级
        $c_ids = M('ClassStudent')->where(array('a_id' => $vo['a_id'], 's_id' => $vo['s_id']))->field('c_id')->select();

        $class = getDataByArray('Class', $c_ids, 'c_id', 'c_title, c_id, c_type, s_id, c_grade, c_is_graduation,ma_id');

        foreach ($class as $key => $value) {
            $c_grade = YearToGrade($value['c_grade'], $value['s_id']);

            $classInfo[$key]['c_name'] = replaceClassTitle($value['s_id'], $value['c_type'], $c_grade, $value['c_title'], $value['c_is_graduation'], $value['ma_id']);
            $classInfo[$key]['c_id'] = $value['c_id'];
        }

        $this->classInfo = $classInfo;
        $this->assign('vo', $vo);
        $this->display();
    }

    public function forbid() {
        parent::forbid(D('Auth'));
    }

    public function resume() {
        parent::resume(D('Auth'));
    }

    // 更新
    public function update() {

        $a_id = intval($_REQUEST['a_id']);

        if (!$a_id) {
            $this->error();
        }

        if (!M('Auth')->where(array('a_id' => $a_id, 'a_type' => 1, 's_id' => $this->authInfo['s_id']))->getField('a_id')) {
            $this->error('非法操作');
        }

        $_POST['a_type'] = intval($_POST['a_type']);

        // 是否有上传
        if ($_FILES['a_avatar']['size'] > 0) {

            // 上传封面
            $allowType = C('ALLOW_FILE_TYPE');
            $_POST['a_avatar'] = parent::upload($allowType['image'], C('AUTH_AVATAR'), true, '96,48', '96,48', '96/,48/');
        }

        $_POST['a_birthday'] = strtotime($_POST['a_birthday']);

        $model = D('Auth');

        if (false === $model->create()) {
            $this->error($model->getError());
        }

        //保存当前数据对象
        if (false !== $model->save()) {

            $jumpUrl = '__APPURL__/' . GROUP_NAME . '/' . $this->getActionName() . '/index';
            //成功提示
            $this->assign('jumpUrl', $jumpUrl);
            $this->success('成功');
        } else {

            //失败提示
            $this->error('失败');
        }

    }
}
?>