<?php
/**
 * ClassModel
 * 班级模型
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-14
 *
 */
class ClassModel extends CommonModel{

    /**
     * lists
     * 数据列表
     * @param int $is_page 是否分页 default 1 是
     * @param int $is_ajax 是否ajax default 0 否
     *
     * @return $result
     *
     */
    public function lists($s_id, $is_ajax = 0, $sortby = '', $is_page = 1) {

        $p = intval($_POST['p']) ? intval($_POST['p']) : 1;

        $Class = M('Class');

        // 条件
        $type = intval($_POST['c_type']);
        $grade = intval($_POST['c_grade']);
        $c_title = trim($_POST['c_title']);
        $ma_id = intval($_POST['ma_id']);

        // 教师名称
        $t_name = trim($_POST['teacher_name']);

        if ($t_name) {

            // 根据教师名称获取教师ID
            $a_ids = M('Auth')->where(array('a_nickname' => array('LIKE', '%'.$t_name.'%'), 's_id' => $s_id, 'a_type' => 2))->getField('a_id', TRUE);

            if (is_array($a_ids)) {

                // 获取当前学校老师授课班级
                $cIdArr = M('ClassSubjectTeacher')->where(array('a_id' => array('IN' , $a_ids), 's_id' => $s_id))->field('c_id')->select();
                $c_ids = setArrayByField($cIdArr, 'c_id');
                $c_ids = implode(',', array_keys($c_ids));

                if ($c_ids) {
                    $where['c_id'] = array('IN', $c_ids);
                }
            }
        }

        if ($type) {
            $where['c_type'] = $type;
        }

        if ($grade) {
            $where['c_grade'] = GradeToYear($grade, $s_id);
        }

        if ($c_title) {
            $where['c_title'] = array('LIKE', '%'.$c_title.'%');
        }

        if ($ma_id) {
            $where['ma_id'] = $ma_id;
        }

        $where['s_id'] = $s_id;
        $where['c_is_graduation'] = 0;

        if ($is_page) {
            $result = getListByPage('Class', 'c_id DESC', $where, 8, 1, $p);
        } else {
            $result['list'] = M('Class')->where($where)->select();
        }

        // 数据整理
        foreach($result['list'] as $key => $value){

            $result['list'][$key]['type'] = getTypeNameById($value['c_type'], 'SCHOOL_TYPE');
            $result['list'][$key]['grade'] = $value['c_grade'] . '届';
            $c_grade = YearToGrade($value['c_grade'], $value['s_id']);
            $result['list'][$key]['c_replace_title'] = replaceClassTitle($value['s_id'], $value['c_type'], $c_grade, $value['c_title'], $value['c_is_graduation'], $value['ma_id']);
            $result['list'][$key]['classLogo'] = getClassLogo($value['c_logo']);
        }

        // 是否ajax
        if ($is_ajax) {
            $result = json_encode($result);
        }

        return $result;
    }

    // 课程教师列表
    public function listTeachers($s_id) {

        if (!$s_id) {
            $this->error('非法操作');
        }

        // 接收参数
        $where['t_subject'] = intval($_POST['t_subject']);
        $where['s_id'] = $s_id;

        $result = M("Teacher")->where($where)->select();

        if ($result) {
            $auth = getDataByArray('Auth', $result, 'a_id');
            foreach ($result as $key => $value) {
                $result[$key]['a_nickname'] = $auth[$value['a_id']]['a_nickname'];
            }
        }

        return $result;
    }


}

?>