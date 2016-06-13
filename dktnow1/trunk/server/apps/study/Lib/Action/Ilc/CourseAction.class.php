<?php
/**
 * CourseAction
 * 课程管理
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-9
 *
 */
class CourseAction extends CommonAction{

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

        // 赋值
        $this->co_type = $co_type;
        $this->co_course = C('COURSE_TYPE');
        $this->school = $school;

    }

    public function index() {

        $this->display();
    }

    // 列表
    public function lists() {

        // 条件
        $where['s_id'] = $this->authInfo['s_id'];

        $co_type = intval($_POST['co_type']);
        $ma_id = intval($_POST['ma_id']);
        $co_grade = intval($_POST['co_grade']);
        $co_subject = intval($_POST['co_subject']);
        $keywords = $_POST['keywords'];

        if ($co_type) {
            $where['co_type'] = $co_type;
        }

        if ($ma_id) {
            $where['ma_id'] = $ma_id;
        }

        if ($co_grade) {
            $where['co_grade'] = $co_grade;
        }

        if ($co_subject) {
            $where['co_subject'] = $co_subject;
        }

        if ($keywords) {
            $auth = M('Auth')->where(array('s_id' => $this->authInfo['a_id'], 'a_type' => 2, 'a_nickname' => array('LIKE' , '%'.$keywords.'%')))->field('a_id,a_nickname')->select();

            $auth = setArrayByField($auth, 'a_id');
            $where['a_id'] = array('IN', implode(',', getValueByField($auth, 'a_id')));
        }

        // 获取数据
        $result = getListByPage('Course', 'co_id ASC', $where, 10, 1, intval($_POST['p']));

        if (!$auth) {
            $auth = getDataByArray('Auth', $result['list'], 'a_id', 'a_id,a_nickname');
        }

        // 数据整理
        foreach($result['list'] as $key => $value) {
            $list[$key]['id'] = $value['co_id'];
            $list[$key]['type'] = getTypeNameById($value['co_type'], 'SCHOOL_TYPE');
            $list[$key]['grade'] = getGradeByType($value['co_type'], $this->authInfo['s_id'], $value['co_grade']);
            $list[$key]['semester'] = getTypeNameById($value['co_semester'], 'SEMESTER_TYPE');
            $list[$key]['subject'] = getTypeNameById($value['co_subject'], 'COURSE_TYPE');
            $list[$key]['name'] = $value['co_title'];
            $list[$key]['teacher'] = $auth[$value['a_id']]['a_nickname'];
        }
        $result['list'] = $list;
        echo json_encode($result);
    }
}
?>