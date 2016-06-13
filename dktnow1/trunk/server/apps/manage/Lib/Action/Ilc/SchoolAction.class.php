<?php
/**
 * SchoolAction
 * 学校管理
 *
 * 作者:  赵鹏 (zhaop@mink.com.cn)
 * 创建时间: 2013-5-02
 *
 */
class SchoolAction extends CommonAction {

    public function _initialize() {

        parent::_initialize();

        // 赋值
        $this->appList = C('APP_LIST');
    }


    public function index() {

        if ($_REQUEST['s_name']) {
            $_REQUEST['s_name'] = array('LIKE', '%' . $_REQUEST['s_name'] . '%');
        }

        $map = $this->_search();

        // 条件过滤
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }

        $model = D('School');

        // 获取列表数据
        if (!empty($model)) {
            $this->_list($model,'', $map);
        }

        Cookie('__forward__', $_SERVER['REQUEST_URI']);

        $this->display();
    }

    // 插入
    public function insert() {

        if ($_POST['s_type']) {
            $_POST['s_type'] = implode(',', $_POST['s_type']);
        }

        if ($_POST['s_apps']) {
            $_POST['s_apps'] = implode(',', $_POST['s_apps']);
        }

        // 是否有上传
        if ($_FILES['s_logo']['size'] > 0) {

            // 允许上传图片的类型
            $allowType = C('ALLOW_FILE_TYPE');
            // 上传生成200*200的LOGO
            $_POST['s_logo'] = parent::upload($allowType['image'], C('SCHOOL_LOGO'), true, '200', '200', '200/');
        }

        parent::insert();
        A('Ilc/Cache')->study();
        A('Ilc/Cache')->config();
    }

    // 编辑
    public function edit() {
        // 获取数据
        $vo = M('School')->find(intval($_REQUEST['id']));

        $vo['s_types'] = C("SCHOOL_TYPE");

        // 学校地区
        $vo['s_region2'] = '"' . str_replace('###', '","', $vo['s_region']) . '"';

        // 学校LOGO URL处理
        $vo['s_logo'] = getSchoolLogo($vo['s_logo'], 200);

        $this->assign('vo', $vo);
        $this->display();
    }

    // 添加
    public function _before_add() {

        $this->assign('vo', C("SCHOOL_TYPE"));
    }

    // 更新
    public function update() {

        // 是否有上传
        if ($_FILES['s_logo']['size'] > 0) {

            // 允许上传图片的类型
            $allowType = C('ALLOW_FILE_TYPE');
            // 上传生成200*200的LOGO
            $_POST['s_logo'] = parent::upload($allowType['image'], C('SCHOOL_LOGO') . "200/", true, '200', '200');
        }

        if ($_POST['s_type']) {
            $_POST['s_type'] = implode(',', $_POST['s_type']);
        }

        if ($_POST['s_apps']) {
            $_POST['s_apps'] = implode(',', $_POST['s_apps']);
        }

        parent::update();
        A('Ilc/Cache')->study();
        A('Ilc/Cache')->config();
    }


    // 学校管理员
    public function user() {

        //得到所有学校组
        $group = M('SchoolRole')->where(array('s_id' => $_REQUEST['id']))->field('sr_id, sr_name')->select();
        //得到所有管理员a_id
        $role = M('SchoolRoleUser')->where(array('sr_id' => array('IN', implode(',', getValueByField($group, 'sr_id')))))->select();
        //由a_id查出管理员信息
        $user = getDataByArray('Auth', $role, 'a_id');

        //整理数据
        $tem = array();
        foreach ($role as $key => $value){
            $tem[$value['sr_id']][] = $user[$value['a_id']]['a_nickname'] ;
        }

        foreach ($group as $key => $value){
            $group[$key]['user'] = implode(',', $tem[$value['sr_id']]);
        }

        $this->group = $group;

        // 获取学校信息
        $school = loadCache('school');
        $school = $school[$_REQUEST['id']];

        $this->school = $school;
        $this->display();
    }

    // 设置管理员
    public function setManager() {

        $where['s_id'] = intval($_POST['s_id']);
        $where['sr_id'] = 0;
        $a_id = intval($_POST['a_id']);

        $_POST['old_id'] = M('SchoolRoleUser')->where($where)->getField('a_id');
        $result = parent::updateData();

        // 有学校超管
        if (intval($_POST['old_id'])) {
            // 学校管理组员表数据更新
            M('Auth')->where(array('a_id' => intval($_POST['old_id'])))->save(array('a_is_manager' => 0));
            M('SchoolRoleUser')->where($where)->save(array('a_id' => intval($_POST['a_id'])));
        } else {

            $where['a_id'] = $a_id;
            M('SchoolRoleUser')->add($where);
        }

        M('Teacher')->where(array('a_id' => $a_id))->save(array('s_id' => $where['s_id']));
        M('Auth')->where(array('a_id' => intval($_POST['a_id'])))->save(array('a_is_manager' => 1, 's_id' => intval($_POST['s_id'])));

        A('Ilc/Cache')->study();
        A('Ilc/Cache')->config();

        // 清空之前管理员所授课程已指定的班级和群组
        M('Course')->where(array('a_id' => intval($_POST['old_id'])))->save(array('c_id' => '', 'cro_id' => ''));

        echo intval($result);
    }
}
?>
