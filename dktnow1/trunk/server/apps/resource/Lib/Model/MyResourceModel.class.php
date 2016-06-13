<?php
/**
 * MyResourceModel
 * 我的资源模型
 *
 * 作者:  黄蕊
 * 创建时间: 2013-7-8
 *
 */
class MyResourceModel extends CommonModel {

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
        $auth['cro_id'] = M('Crowd')->where(array('a_id' => $auth['a_id']))->getField('cro_id', TRUE);

        return $auth;
    }
}