<?php
/**
 * CourseModel
 * 课程模型
 *
 */
class CourseModel extends CommonModel {

    protected $_validate = array(
        array('a_id', 'require', '所属人不能为空'),
        array('co_title', 'require', '请输入课程名'),
        array('co_count', 'require', '请输入课时'),
        array('co_type', 'require', '请选择所属学制'),
        array('co_grade', 'require', '请选择所属年级'),
        array('co_semester', 'require', '请选择所属学期'),
        array('co_course', 'require', '请选择所属学科'),
        array('co_version', 'require', '请选择所属版本'),
    );
    protected $_auto = array(
        array('co_created', 'time', self::MODEL_INSERT, 'function'),
        array('co_updated', 'time', self::MODEL_UPDATE, 'function'),
    );

}

?>