<?php
/**
 * ApplyClassAction
 * 申请班级模块
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-9
 *
 */
class ApplyClassAction extends BaseAction{

    public function index() {

        // 接收参数
        $as_id = intval($_REQUEST['as_id']);

        if ($this->authInfo['s_id']) {
            $s_id = $this->authInfo['s_id'];
        } else {
            $s_id = intval($_REQUEST['s_id']);
        }

        if (!$as_id && !$s_id) {
            $this->error('请选择学校');
        }

        // 获取申请学校的学制
        if ($as_id) {
            $as_type_str = M('ApplySchool')->where(array('as_id' => $as_id))->getField('as_type');
        } else {
            $as_type_str = M('School')->where(array('s_id' => $s_id))->getField('s_type');
        }

        $type_arr= explode(',', $as_type_str);

        $schoolType = C('SCHOOL_TYPE');

        foreach ($type_arr as $type) {

            $types[] = array('title' => $schoolType[$type], 'id' => $type);

        }

        $major = M('Major')->where(array('s_id' => $this->authInfo['s_id']))->field('ma_id,ma_title')->select();

        // 获取班级名称
        for ($i = 0; $i < C('MAX_CLASS_COUNT'); $i ++) {

            $str = ','.($i+1).',';

            if (strstr($titleStr, $str) == FALSE) {

                $tmp['title'] = $i+1;
                $tmp['show'] = '('.($i+1).')班';

                $classList[] = $tmp;
            }
        }

        // 页面赋值
        $this->assign('classList', $classList);
        $this->assign('grade', C('GRADE_TYPE'));
        $this->assign('types', $types);
        $this->assign('as_id', $as_id);
        $this->assign('s_id', $s_id);
        $this->assign('major', $major);
        $this->display();
    }

    // 获取班级列表
    public function lists() {

        $as_id = intval($_POST['as_id']);

        if ($this->authInfo['s_id']) {
            $s_id = $this->authInfo['s_id'];
        } else {
            $s_id = intval($_REQUEST['s_id']);
        }

        if (!$as_id && !$s_id) {
            $this->error('参数错误');
        }

        // 获取页码
        $p = $_POST['p'] ? intval($_POST['p']) : 1;

        // 条件
        if ($as_id) {
            $where['as_id'] = $as_id;
        } else {
            $where['s_id'] = $s_id;
        }
        $where['a_id'] = $this->authInfo['a_id'];

        // 获取班级申请数据
        $result = getListByPage('ApplyClass', 'ac_id ASC', $where, C('PAGE_SIZE'), 1, $p);//echo M()->getlastsql();
        $major = setArrayByField(M('Major')->where(array('s_id' => $this->authInfo['s_id']))->field('ma_id,ma_title')->select(), 'ma_id');

        $grade_type = C('GRADE_TYPE');
        $school_type = C('SCHOOL_TYPE');

        // 数据处理
        foreach ($result['list'] as $key => $cv) {

            // 转换年级，学段名称
            $cv['grade'] = YearToGrade($cv['ac_grade']);
            $result['list'][$key]['ac_grade'] = $grade_type[$cv['ac_type']][$cv['grade']];
            $result['list'][$key]['ac_type'] = $school_type[$cv['ac_type']];
            $result['list'][$key]['ma_title'] = $major[$cv['ma_id']]['ma_title'] ? $major[$cv['ma_id']]['ma_title'] : '';
        }

        echo json_encode($result);

    }


    public function insert() {

        // 接收参数
        $as_id = intval($_POST['as_id']);
        $ma_id = intval($_POST['ma_id']);
        $ac_type = trim($_POST['type']);
        $ac_grade = trim($_POST['grade']);
        $ac_title = trim($_POST['title']);

        if (!$as_id) {

            if ($this->authInfo['s_id']) {
                $s_id = $this->authInfo['s_id'];
            } else {
                $s_id = intval($_REQUEST['s_id']);
            }
        }


        if (!$ac_type && !$ac_grade && !$ac_title && !$as_id && !$s_id) {
            $this->error('参数错误');
        }

        // 判断班级是否重复
        if ($as_id && !$s_id) {
            $data['as_id'] = $as_id;
        } else {
            $data['s_id'] = $s_id;
        }

        $data['ac_type'] = $ac_type;

        if ($as_id) {
            $data['ac_grade'] = GradeToYear($ac_grade);
        } else {
            $data['ac_grade'] = GradeToYear($ac_grade, $s_id);
        }

        $data['ac_title'] = $ac_title;
        $data['a_id'] = $this->authInfo['a_id'];

        $count = M('ApplyClass')->where($data)->count();

        if ($count > 0) {
            $result['status'] = 0;
            echo json_encode($result);
            exit;
        }

        $data['ac_created'] = time();
        $data['ma_id'] = $ma_id;
        $result['status'] = M('ApplyClass')->add($data);

        echo json_encode($result);

    }
}
?>