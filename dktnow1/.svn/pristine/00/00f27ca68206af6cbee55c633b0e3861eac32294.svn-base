<?php
/**
 * SystemAction
 * 系统配置
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-9
 *
 */
class SystemAction extends CommonAction{

    // 初始化
    public function _initialize() {

        parent::_initialize();

        // 获取配置参数
        $type = C('SCHOOL_TYPE');
        $school = loadCache('school');
        $allowType = explode(',', $school[$this->authInfo['s_id']]['s_type']);
        // 获取该学校学制类型
        $gc_type = array();
        foreach ($allowType as $key => $value) {
            if ($value) {
                $gc_type[$value] = $type[$value];
            }
        }

        if (!$gc_type) {
            $this->error('本学校未设置学制');
        }

        // 赋值
        $this->gc_type = $gc_type;
        $this->gc_course = C('COURSE_TYPE');
        $this->school = $school;

    }

    // 首页
    public function index() {

        $this->display();
    }

    // 列表
    public function lists() {

        // 条件
        $where['s_id'] = $this->authInfo['s_id'];

        $gc_type = intval($_POST['gc_type']);
        $gc_grade = intval($_POST['gc_grade']);
        $ma_id = intval($_POST['ma_id']);

        if ($gc_type) {
            $where['gc_type'] = $gc_type;
        }

        if ($gc_grade) {
            $where['gc_grade'] = $gc_grade;
        }

        if ($gc_grade) {
            $where['ma_id'] = $ma_id;
        }

        // 获取数据
        $result = getListByPage('GradeCourse', 'gc_type ASC,gc_grade ASC', $where, 10, 1, intval($_POST['p']));
        $auth = getDataByArray('Auth', $result['list'], 'a_id', 'a_id,a_nickname');

        // 数据整理
        foreach($result['list'] as $key => $value) {
            $result['list'][$key]['course'] = getTypeNameById($value['gc_course'], 'COURSE_TYPE');
            $result['list'][$key]['type'] = getTypeNameById($value['gc_type'], 'SCHOOL_TYPE');
            $result['list'][$key]['grade'] = getGradeByType($value['gc_type'], $this->authInfo['s_id'], $value['gc_grade']);
            $result['list'][$key]['a_nickname'] = $auth[$value['a_id']]['a_nickname'];
            $result['list'][$key]['gc_created'] = date('Y-m-d', $value['gc_created']);
        }

        echo json_encode($result);
    }

    // 添加
    public function add() {
        $this->display();
    }

    // 根据学制年级获取学科
    public function getSubjects() {

        // 课程
        $courseType = $this->gc_course;

        $where['s_id'] = $this->authInfo['s_id'];
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

    // 插入
    public function insert() {

        $data['s_id'] = $_POST['s_id'] = $this->authInfo['s_id'];
        $data['c_type'] = intval($_POST['gc_type']);
        $data['ma_id'] = intval($_POST['ma_id']);
        $data['c_grade'] = GradeToYear(intval($_POST['gc_grade']), $this->authInfo['s_id']);

        if (!$_POST['gc_course']) {
            $this->success('成功');
        }

        // 获取学科
        $course = explode(',', $_POST['gc_course']);

        // 验证是否已存在此学科
        $gc = M('GradeCourse')->where(array('s_id' => $this->authInfo['s_id'], 'gc_type' => $data['c_type'], 'gc_grade' => intval($_POST['gc_grade']), 'gc_course' => array('IN', $_POST['gc_course']), 'ma_id' => $data['ma_id']))->getField('gc_course', TRUE);

        $course = array_diff($course, (array)$gc);

        if (!$course) {
            $this->error('已存在此学科，不要重复添加');
        }

        // 获取所有该年级下班级ID
        $class = M('Class')->where($data)->getField('c_id', TRUE);

        $_POST['gc_status'] = 1;
        $_POST['a_id'] = $this->authInfo['a_id'];

        foreach ($course as $gc_course) {

            foreach ($class as $key => $value) {
                $getXq = getXq($this->authInfo['s_id']);
                $where['s_id'] = $data['s_id'];
                $where['c_id'] = $value;
                $where['cst_course'] = intval($gc_course);
                $where['cst_year'] = $getXq['cc_year'];
                $where['cst_xq'] = $getXq['cc_xq'];
                $where['cst_created'] = time();
                M('ClassSubjectTeacher')->add($where);
            }

            $_POST['gc_course'] = intval($gc_course);
            $model = D('GradeCourse');

            if (false === $model->create()) {
                $this->error($model->getError());
            }

            $model->add();
        }

        $this->success('成功');

    }

    // 默认禁用操作
    public function forbid($model = '') {

        $id = strval($_REQUEST['id']);

        $gc = M('GradeCourse')->where(array('gc_id' => array('IN', $id), 's_id' => $this->authInfo['s_id']))->select();

        $where = 's_id =' . $this->authInfo['s_id'] . ' AND ( 1 != 1 ';
        foreach ($gc as $gValue) {
            $where .= 'OR ( c_type = ' . $gValue['gc_type'] . ' AND c_grade = ' . GradeToYear($gValue['gc_grade'], $this->authInfo['s_id']) . ') ';
        }

        $where .= ' )';
        $c_id = M('Class')->where($where)->getField('c_id', TRUE);

        $cst = M('ClassSubjectTeacher')->where(array('c_id' => array('IN', $c_id)))->find();

        if ($cst) {
            $this->error('此年级下已存在班级，请不要更改学科状态');
        }

        $res = D('GradeCourse')->forbid(array('gc_id' => array('IN', $id)), 'gc_status');

        if ($res) {

            $this->assign('jumpUrl', '/Ilc/GradeCourse/index');
            $this->success('状态禁用成功！');
        } else {

            $this->error('状态禁用失败！');
        }
    }

    // 默认启用操作
    public function resume($model = '') {

        $id = strval($_REQUEST['id']);

        $gc = M('GradeCourse')->where(array('gc_id' => array('IN', $id), 's_id' => $this->authInfo['s_id']))->select();

        $where = 's_id =' . $this->authInfo['s_id'] . ' AND ( 1 != 1 ';
        foreach ($gc as $gValue) {
            $where .= 'OR ( c_type = ' . $gValue['gc_type'] . ' AND c_grade = ' . GradeToYear($gValue['gc_grade'], $this->authInfo['s_id']) . ') ';
        }

        $where .= ' )';
        $c_id = M('Class')->where($where)->getField('c_id', TRUE);

        $cst = M('ClassSubjectTeacher')->where(array('c_id' => array('IN', $c_id)))->find();

        if ($cst) {
            $this->error('此年级下已存在班级，请不要更改学科状态');
        }

        $res = D('GradeCourse')->resume(array('gc_id' => array('IN', $id)), 'gc_status');

        if ($res) {

            $this->assign('jumpUrl', '/Ilc/GradeCourse/index');
            $this->success('启用成功！');
        } else {

            $this->error('启用失败！');
        }
    }

    // 编辑
    public function edit() {
        $this->vo = M('GradeCourse')->where(array('gc_id' => intval($_GET['id']), 's_id' => $this->authInfo['s_id']))->find();
        if (!$this->vo) {
            $this->error('操作失败');
        }
        if ($this->vo['ma_id']) {
            $this->major = M('Major')->where(array('ma_id' => $this->vo['ma_id']))->field('ma_id,ma_title')->find();
        }
        $this->display();
    }

    // 更新
    public function update() {

        $check['s_id'] = $data['s_id'] = $this->authInfo['s_id'];
        $check['gc_type'] = $data['c_type'] = intval($_POST['gc_type']);
        $data['c_grade'] = GradeToYear(intval($_POST['gc_grade']), $this->authInfo['s_id']);

        $class = M('Class')->where($data)->getField('c_id');

        if ($class) {

            $this->error('此年级下已存在班级，请不要更改学科');
        }

        $check['gc_grade'] =  intval($_POST['gc_grade']);
        $check['gc_course'] = intval($_POST['gc_course']);
        $check['gc_id'] = array('neq', intval($_POST['gc_id']));

        if (M('GradeCourse')->where($check)->getField('gc_id')) {
            $this->error('该年级下此学科已存在');
        }

        $model = D('GradeCourse');

        if (false === $model->create()) {
            $this->error($model->getError());
        }

        $model->save();
        $this->success('成功');
    }
}
?>