<?php
/*
 * getAuthInfo
 * 获取用户所在的班级和群组信息
 * @param array $auth 存放接口用户信息数组
 *
 */
function getAuthInfo($auth) {

    // 教师
    if ($auth['a_type'] == 2) {

        $c_id = M('Class')->where(array('a_id' => $auth['a_id'], 's_id' => $auth['s_id']))->getField('c_id', TRUE);

        $auth['class_manager'] = $c_id;
        $c_ids = M('ClassSubjectTeacher')->where(array('a_id' => $auth['a_id'], 's_id' => $auth['s_id']))->getField('c_id', TRUE);
        $auth['subject_teacher_class'] = $c_ids;
        $auth['c_id'] = array_merge((array)$c_id, (array)$c_ids);

    // 学生
    } elseif ($auth['a_type'] == 1) {
        $auth['c_id'] = M('ClassStudent')->where(array('a_id' => $auth['a_id']))->getField('c_id', TRUE);
    }

    // 群组
    $auth['cro_id'] = M('AuthCrowd')->where(array('a_id' => $auth['a_id']))->getField('cro_id', TRUE);

    return $auth;
}

/*
 * addTrend
 * 添加动态
 * @param int $a_id 操作者
 * @param int $s_id 所在学校
 * @param int $c_id 所在班级
 * @param int $tr_action 动作 TREND_TYPE
 * @param int $tr_obj 操作对象 作业、练习等 TREND_TYPE
 * @param int $tr_to_id 对象ID，当对象是操作者自己时，为0
 * @param int $tr_course 学科ID，没有的话，为0
 * @param string $tr_title 标题
 * @param int $tr_obj_id ID号
 *
 */
function addTrend($a_id, $s_id, $c_id, $tr_action, $tr_obj, $tr_to_id = 0, $tr_course = 0, $tr_title = '', $tr_obj_id) {

    $data['a_id'] = $a_id;
    $data['s_id'] = $s_id;
    $data['c_id'] = $c_id;
    $data['tr_action'] = $tr_action;
    $data['tr_obj'] = $tr_obj;
    $data['tr_to_id'] = $tr_to_id;
    $data['tr_course'] = $tr_course;
    $data['tr_title'] = $tr_title;
    $data['tr_obj_id'] = $tr_obj_id;
    $data['tr_created'] = time();

    M('Trend')->add($data);

}

function mk_dir($dir, $mode = 0755) {
    if (is_dir($dir) || @mkdir($dir,$mode)) return true;
    if (!mk_dir(dirname($dir),$mode)) return false;
    return @mkdir($dir,$mode);
} 
?>