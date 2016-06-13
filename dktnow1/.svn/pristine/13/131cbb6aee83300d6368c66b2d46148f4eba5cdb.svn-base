<?php
/**
 * TeacherAction
 * 教师管理
 *
 * 作者:  翟一江
 * 创建时间: 2013-5-15
 *
 */
class TeacherAction extends CommonAction{

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
        $this->school = loadCache('school');
    }

    // 首页列表
    Public function index(){

        // 查询条件
        if ($_REQUEST['a_nickname']) {
            $where['a_nickname'] = array('LIKE', "%".$_REQUEST['a_nickname']."%");
        }

        $s_id = intval($_REQUEST['s_id']);
        if ($s_id) {
            $where['s_id'] = $s_id;
            $this->s_id = $s_id;
        }

        $where['a_type'] = 2;

        // 分页获取教师数据
        $teacher =  getListByPage('Auth', 'a_id DESC', $where);

        // 获取所有教师教授的学科
        $subjects = M('Teacher')->where(array('a_id' => array('IN', implode(',', getValueByField($teacher['list'], 'a_id')))))->field('a_id,t_subject')->select();

        // 按教师ID整理学科
        $tmp = array();
        foreach ($subjects as $sKey => $sValue) {
            $tmp[$sValue['a_id']][] = $this->course[$sValue['t_subject']];
        }

        // 循环整理教师数据
        foreach ($teacher['list'] as $key => $value) {
            $value['t_subject'] = implode(',', $tmp[$value['a_id']]);
            $value['s_name'] = $this->school[$value['s_id']]['s_name'];
            $value['a_last_login_ip'] = long2ip($value['a_last_login_ip']);
            $teacher['list'][$key] = $value;
        }

        $this->assign('list', $teacher['list']);
        $this->assign('page', $teacher['page']);
        $this->display();

    }

    // 插入
    public function insert(){

        $model = D('Teacher');
        // 开启事务，保证用户表和教师授课表同时添加成功
        $model->startTrans();

        $flag = true;

        // 是否有上传
        if ($_FILES['a_avatar']['size'] > 0) {

            // 上传封面
            $allowType = C('ALLOW_FILE_TYPE');
            $_POST['a_avatar'] = parent::upload($allowType['image'], C('AUTH_AVATAR'), true, '96,48', '96,48', '96/,48/');
        }

        // 教师教授学科
        if (empty($_POST['Courses'])) {
            $this->error('请选择教师教授课程');
        }

        // 接收数据
        $_POST['a_password'] = md5(getStrLast($_POST['a_account']));
        $_POST['a_year'] = intval($_POST['a_year']);
        $_POST['a_birthday'] = strtotime($_POST['a_birthday']);
        $_POST['a_created'] = time();
        $_POST['a_applications'] = C('DEFAULT_APP');
        $_POST['a_status'] = 1;

        $flag = $this->insertData();

        // 添加默认导航
        $navigations = C('NAVIGATION');

        foreach ($navigations as $key => $value) {

            $nav['na_title'] = $value['title'];
            $nav['na_url'] = $value['url'];
            $nav['na_sort'] = $key;
            $nav['a_id'] = $flag;
            $nav['na_created'] = time();

            M('Navigation')->add($nav);
        }

        //重组teacher表要添加的数据
        $dataList=array();
        foreach ($_REQUEST['Courses'] as $key => $value) {
            $dataList[$key]['a_id'] = $flag;
            $dataList[$key]['s_id'] = $_POST['s_id'];
            $dataList[$key]['t_subject'] = $value;
        }

        // 写入教师教授学科
        $flag = M('Teacher')->addAll($dataList);

        // 事务提交或回滚
        if ($flag) {
            $model->commit();
        } else {
            $model->rollback();
        }

        $this->show($flag);
    }

    // 默认编辑操作
    public function edit() {

        // 获取教师数据
        $vo = M('Auth')->where(array('a_id' => intval($_REQUEST['id']), 'a_type' => 2))->find();
        $vo['a_region2'] = '"'.str_replace('###','","',$vo['a_region']).'"';

        // 教师教授科目
        $this->teacher = M('Teacher')->where(array('a_id' => $vo['a_id'], 's_id' => $vo['s_id']))->getField('t_subject', TRUE);
        $this->assign('vo', $vo);
        $this->display();
    }

    // 插入
    public function update(){

         M('Teacher')->where(array('a_id' => $_POST['a_id']))->delete();

        //重组teacher表要添加的数据
        $dataList=array();
        foreach ($_REQUEST['Courses'] as $key => $value) {
            $dataList[$key]['a_id'] = $_POST['a_id'];
            $dataList[$key]['s_id'] = $_POST['s_id'];
            $dataList[$key]['t_subject'] = intval($value);
        }

        // 写入教师教授学科
        M('Teacher')->addAll($dataList);

        // 是否有上传
        if ($_FILES['a_avatar']['size'] > 0) {

            // 上传封面
            $allowType = C('ALLOW_FILE_TYPE');
            $_POST['a_avatar'] = parent::upload($allowType['image'], C('AUTH_AVATAR'), true, '96,48', '96,48', '96/,48/');
        }

        // 教师教授学科
        if (empty($_POST['Courses'])) {
            $this->error('请选择教师教授课程');
        }

        // 接收数据
        $_POST['a_year'] = intval($_POST['a_year']);
        $_POST['a_birthday'] = strtotime($_POST['a_birthday']);
        $_POST['a_update'] = time();

        parent::update();
    }

    public function delete(){

        $model = D('Teacher');

        //开启事务，保证用户表和教师授课表同时添加成功

        $model->startTrans();

        $flag = $this->deleteData();

        $condition = array("a_id" => array('IN', $_REQUEST['id']));

        $flag = M('teacher')->where($condition)->delete();

        if ($flag) {
            $model->commit();
        } else {
            $model->rollback();
        }
        $this->show($flag);
    }

    public function check(){

        $account = $_REQUEST['a_account'];

        $result = M('auth')->where("a_account = '$account'")->select();

        echo json_encode($result);
    }

}