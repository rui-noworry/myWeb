<?php
/**
 * ClassModel
 * 班级模型
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-12-03
 *
 */
class ClassModel extends CommonModel {

    protected $_auto = array(
        array('c_created', 'time', self::MODEL_INSERT, 'function'),
        array('c_updated', 'time', self::MODEL_UPDATE, 'function'),
    );

    protected $_validate = array(
        array('s_id', 'require', '请选择学校'),
        array('c_type', 'require', '请选择学制'),
        array('c_grade', 'require', '请选择年级'),
        array('c_title', 'require', '请选择班级名称'),
    );

    // 获取学生所在班级及班级学科
    public function listsStudentClassCourse($a_id, $s_id) {

        // 获取学生所在班级
        $cIds = M('ClassStudent')->where(array('a_id' => $a_id, 's_id' => $s_id))->getField('c_id', TRUE);

        // 获取班级数据
        $class = M('Class')->where(array('c_id' => array('IN', $cIds)))->field('c_id,c_type,c_grade,c_title,c_is_graduation')->select();

        // 替换班级名称
        $return = array();
        foreach ($class as $key => $value) {
            $value['c_name'] = replaceClassTitle($s_id, $value['c_type'], $value['c_grade'], $value['c_title'], $value['c_is_graduation']);
            $return[$value['c_id']] = $value;
        }

        // 获取所有班级学科
        $course = M('ClassSubjectTeacher')->where(array('c_id' => array('IN', $cIds)))->field('c_id,cst_course')->select();

        foreach ($course as $cKey => $cValue) {
            $return[$cValue['c_id']]['cst_course'][] = $cValue['cst_course'];
        }

        return $return;
    }
}
?>