<?php
/**
 * TopicModel
 * 题目模型
 *
 */
class TopicModel extends CommonModel {

    protected $_validate = array(
        array('a_id', 'require', '创建人不能为空'),
        array('s_id','require', '学校不能为空'),
        array('to_subject','require', '请选择学科'),
        array('to_version','require', '请选择版本'),
        array('to_school','require', '请选择学段'),
        array('to_grade','require', '请选择年级'),
        array('to_semester','require', '请选择学期'),
        array('to_title','require', '请输入题目名称'),
        array('to_option','require', '请选择选项'),
        array('to_answer','require', '请输入答案'),
        array('to_type','require', '请选择题型'),
    );

    protected $_auto = array(
        array('to_created', 'time', self::MODEL_INSERT, 'function'),
        array('to_updated', 'time', self::MODEL_UPDATE, 'function')
    );
}