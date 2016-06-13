<?php
/**
 * DirectoryAction
 * 课文目录模块
 *
 * 作者: 黄蕊
 * 创建时间: 2013-5-20
 *
 */
class DirectoryAction extends CommonAction{

    public function _initialize() {

        parent::_initialize();

        // 赋值
        $this->d_type = C('SCHOOL_TYPE');
        $this->d_course = C('COURSE_TYPE');
        $this->d_semester = C('SEMESTER_TYPE');
        $this->d_version = C('VERSION_TYPE');
    }

    public function index(){

        $d_type = intval($_POST['d_type']);
        $d_grade = intval($_POST['d_grade']);
        $d_semester = intval($_POST['d_semester']);

        $this->choose_type = $d_type;
        $this->choose_grade = $d_grade;
        $this->choose_semester = $d_semester;
        $this->choose_subject = intval($_POST['d_subject']);
        $this->choose_version = intval($_POST['d_version']);

        $d_code = $d_type . $d_grade . $d_semester;

        if (intval($d_code)){
            $_REQUEST['d_code'] = $d_code;
        }
        // 列表过滤器，生成查询Map对象
        $map = $this->_search();

        // 条件过滤
        if (method_exists($this,'_filter')) {
            $this->_filter($map);
        }

        $model = D('Directory');

        // 获取列表数据
        if (!empty($model)) {
            $this->_list($model, $map, $_REQUEST['field'], $_REQUEST['sortby']);
        }

        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $ma_id = array_filter(getValueByField($this->list, 'ma_id'));
        $array = $this->list;
        if ($ma_id) {
            $major = setArrayByField(M('Major')->where(array('ma_id' => array('IN', $ma_id)))->field('ma_id,ma_title')->select(), 'ma_id');
            foreach ($array as $key => &$value) {
                if ($value['ma_id']) {
                    $value['ma_title'] = $major[$value['ma_id']]['ma_title'];
                } else {
                    $value['ma_title'] = '无';
                }
            }
        } else {
            foreach ($array as $key => &$value) {
                $value['ma_title'] = '无';
            }
        }
        $this->list = $array;
        $this->display();
    }

    // 获取pid
    public function _filter(&$map) {

        if (empty($_POST['search']) && !isset($map['d_pid']) ) {
            $map['d_pid'] = intval($map['d_pid']);
        }

        if (!empty($_POST['search'])){
            $map['d_pid'] = 0;
        }

        if (!empty($map['d_code'])){
            $tempStr = $map['d_code'];
            if ($tempStr{2} == 0){
                $map['d_code'] = array('in', ''.$tempStr{0}.$tempStr{1}.'0,'.$tempStr{0}.$tempStr{1}.'1,'.$tempStr{0}.$tempStr{1}.'2');
            }

        }

        // 获取上级节点
        $res = M('Directory')->where($map)->select();

        if (isset($map['d_pid'])) {

            if ($res) {

                // 获取当前级别
                if (!$map['d_pid']) {
                    $curLevel = 1;
                } else {
                    $curLevel = M('Directory')->where(array('d_id' => $map['d_pid']))->getField('d_level');
                    $curLevel += 1;
                }

                $this->assign('d_level', $curLevel);
                $this->assign('d_name',  $res['d_name']);

            } else {

                $this->assign('d_level', 1);
            }
        }
    }

    public function _before_add(){

        $d_pid = intval($_REQUEST['d_pid']);

        if ($d_pid) {

            $directory = M('Directory')->find($d_pid);

            $this->assign('d_subject', $directory['d_subject']);
            $this->assign('d_ver', $directory['d_version']);
            $this->assign('d_code', $directory['d_code']);
            $this->assign('d_pid', $directory['d_id']);
            $this->assign('d_level', $directory['d_level']+1);

        } else {

            $this->assign('d_level', 1);
        }

    }

    public function insert(){

        $_POST['d_code'] = $_POST['d_type'] . $_POST['d_grade'] . $_POST['d_semester'];

        parent::insert();
    }

   // 编辑
   public function edit(){

        $id = intval($_REQUEST['id']);

        if (!$id) {
            $this->error('参数错误');
        }

        $directory = M('Directory')->where(array('d_id' => $id))->find();

        // 获取年级学期的组合编码
        $this->assign('d_code', $directory['d_code']);

        // 拆分组合编码
        list($d_type, $d_grade, $d_semester) = $directory['d_code'];

        parent::edit();
    }

    // 更新
    public function update(){

        if (!intval($_POST['d_id'])) {
            $this->error('参数错误');
        }

        $school_type = $_POST['d_type'];

        $grade = $_POST['d_grade'];

        $semter = $_POST['d_semester'];

        $_POST['d_code'] = $_POST['d_type'] . $_POST['d_grade'] . $_POST['d_semester'];

        parent::update();
    }


    public function getGradeByType() {

        // 接收参数
        $id = intval($_POST['id']);

        echo json_encode(getGradeByType($id));
    }


    // 删除课文目录
    public function del() {

        $d_id = $_REQUEST['id'];

        if (!$d_id) {
            $this->error('参数错误');
        }

        // 条件 删除该节点和所有子节点
        $where = 'd_id IN('.$d_id.') OR d_pid IN('.$d_id.')';

        // 获取子节点
        $child = M('Directory')->where(array('d_pid' => array('IN', $d_id)))->getField('d_id', TRUE);

        $tagRelattionCount = M('TagRelation')->where(array('d_id' => array('IN', $child)))->count();

        if ($tagRelattionCount) {
            M('TagRelation')->where(array('d_id' => array('IN', $child)))->delete();
        }

        $res = M('Directory')->where($where)->delete();

        if ($res) {
            $this->success('删除成功');
        }
    }



}
?>