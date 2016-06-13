<?php
/**
 * AuthAction
 * 用户模块
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-9
 *
 */
class AuthAction extends CommonAction{

    // 初始化
    public function _initialize() {

        parent::_initialize();

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
        $this->course = C('COURSE_TYPE');
        $this->leftOn = 4;
    }

    public function index(){

       $this->display();
    }

    public function lists() {

        $p = intval($_POST['p']) ? intval($_POST['p']) : 1;

        $map['s_id'] = $this->authInfo['s_id'];
        $map['a_type'] = 2;
        $order = trim($_POST['order']);

        if ($_POST['a_nickname']) {
            $map['a_nickname'] = array('LIKE', '%'.trim($_POST['a_nickname']).'%');
        }

        $result = getListByPage('Auth', $order, $map, C('PAGE_SIZE'), 1, $p);

        // 获取教师授课课程
        $res = M("Teacher")->where(array('s_id' => $map['s_id']))->field('t_subject, a_id')->select();

        foreach ($res as $key => $value) {
            $course[$value['a_id']][] = getTypeNameById($value['t_subject'], 'COURSE_TYPE');
        }

        // 重新组织数据
        $result['status'] = 0;
        foreach ($result['list'] as $key => $value) {
            $result['list'][$key]['a_last_login_ip'] = long2ip($value['a_last_login_ip']);
            $result['list'][$key]['t_subject'] = str_replace('null', '', implode(',', $course[$value['a_id']]));

        }

        if ($result['list']) {
            $result['status'] = 1;
        }

        echo json_encode($result);
    }

    public function listTeacher() {

        // 接收参数
        $is_ajax = intval($_POST['is_ajax']);
        unset($_POST['is_ajax']);

        // 整理条件
        foreach ($_POST as $key => $value) {
            if ($value) {
                if ($key == 'a_nickname' || $key == 'a_tel') {
                    $where[$key] = array('LIKE', '%'.$value.'%');
                } else {
                    $where[$key] = $value;
                }
            }
        }

        // 若未指定学校，默认查询本学校
        if (!$where['s_id']) {
            $where['s_id'] = $this->authInfo['s_id'];
        }

        $where['a_id'] = array('neq', $this->authInfo['a_id']);
        $where['a_type'] = 2;
        $where['a_status'] = 1;

        // 获取数据
        $auth = M('Auth')->where($where)->field('a_id,a_nickname,s_id,a_type,a_year,a_sex,a_tel,a_email,a_is_manager,a_status')->select();

        // 返回数据
        if ($is_ajax) {
            echo json_encode($auth);
        }

    }

    public function insert() {

        // 获取默认密码
        $_POST['a_password'] = getStrLast($_POST['a_account']);

        $_POST['a_type'] = intval($_POST['a_type']);

        // 获取当前学校
        $_POST['s_id'] = $this->authInfo['s_id'];

        if ($_POST['a_type'] != 2) {
            $this->error('操作失败');
        }

        if (empty($_POST['teacher'])) {
            $this->error('请选择教师教授课程');
        }

        $_POST['a_year'] = intval($_POST['a_year']);
        $_POST['a_applications'] = C('DEFAULT_APP');
        $_POST['a_birthday'] = strtotime($_POST['a_birthday']);
        $_POST['a_status'] = 1;

        // 是否有上传
        if ($_FILES['a_avatar']['size'] > 0) {

            // 上传头像
            $allowType = C('ALLOW_FILE_TYPE');
            $_POST['a_avatar'] = parent::upload($allowType['image'], C('AUTH_AVATAR'), true, '96,48', '96,48', '96/,48/');
        }

        parent::insert();
    }

    // 编辑
    public function edit() {

        // 获取数据
        $vo = M('Auth')->where(array('a_id' => intval($_REQUEST['id']), 'a_type' => 2, 's_id' => $this->authInfo['s_id']))->find();

        if (!$vo) {
            $this->error('非法操作');
        }
        $vo['a_region2'] = '"'.str_replace('###','","',$vo['a_region']).'"';
        $vo['a_avatar'] = getAuthAvatar($vo['a_avatar'], $vo['a_type'], $vo['a_sex']);

        $res = M("Teacher")->where(array('a_id' => intval($_REQUEST['id']), 's_id' => $this->authInfo['s_id']))->field('t_subject')->select();
        $this->chooseCourse = getValueByField($res, 't_subject');

        $this->assign('vo', $vo);
        $this->display();
    }

    // 更新
    public function update() {

        $a_id = intval($_REQUEST['a_id']);

        if (!$a_id) {
            $this->error();
        }

        if (!M('Auth')->where(array('a_id' => $a_id, 'a_type' => 2, 's_id' => $this->authInfo['s_id']))->getField('a_id')) {
            $this->error('非法操作');
        }

        $_POST['a_type'] = intval($_POST['a_type']);

        // 获取当前学校
        $_POST['s_id'] = $this->authInfo['s_id'];
        if (empty($_POST['teacher'])) {
            $this->error('请选择教师教授课程');
        }

        $_POST['a_year'] = intval($_POST['a_year']);

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

        // 更新数据
        $this->show($model->save());
    }

    // 删除教师
    public function delete() {

        // 接收参数
        $ids = $_POST['id'];

        if (!$ids) {
            $this->error('参数错误');
        }

        $data['s_id'] = 0;
        $data['a_deleted'] = time();
        $data['a_is_manager'] = 0;
        $data['a_has_class'] = 0;
        $data['a_type'] = 0;

        // 更新用户表
        M('Auth')->where(array('a_id' => array('IN', $ids), 's_id' => $this->authInfo['s_id']))->save($data);

        M('SchoolRoleUser')->where(array('a_id' => array('IN', $ids), 's_id' => $this->authInfo['s_id']))->delete();

        // 更新教师授课表
        M('ClassSubjectTeacher')->where(array('a_id' => array('IN', $ids), 's_id' => $this->authInfo['s_id']))->setField('a_id', 0);

        // 更新教师授课日志表
        M('TeacherCourseLog')->where(array('a_id' => array('IN', $ids), 's_id' => $this->authInfo['s_id']))->setField('tc_end_time', time());

        // 删除教师授课数据
        M('Teacher')->where(array('a_id' => array('IN', $ids), 's_id' => $this->authInfo['s_id']))->delete();

        $result['status'] = 1;

        echo json_encode($result);

    }


}
?>