<?php
/**
 * SchoolYearModel
 * 学年设置
 *
 */
class SchoolYearModel extends Model{

    protected $_validate = array(
        array('s_id', 'require', '学校ID不可为空'),
        array('a_id', 'require', '管理员ID不可为空'),
        array('sy_year', 'require', '学年不可为空'),
        array('sy_up_start', 'require', '上学期开始时间不可为空'),
        array('sy_up_end', 'require', '上学期结束时间不可为空'),
        array('sy_down_start', 'require', '下学期开始时间不可为空'),
        array('sy_down_end', 'require', '下学期结束时间不可为空'),
        );

    protected $_auto = array(
        array('sy_created', 'time', self::MODEL_INSERT, 'function'),
        array('sy_updated', 'time', self::MODEL_UPDATE, 'function'),
        array('sy_up_start', 'strtotime', self::MODEL_BOTH, 'function'),
        array('sy_up_end', 'strtotime', self::MODEL_BOTH, 'function'),
        array('sy_down_start', 'strtotime', self::MODEL_BOTH, 'function'),
        array('sy_down_end', 'strtotime', self::MODEL_BOTH, 'function'),
    );
}

?>