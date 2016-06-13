<?php
/**
 * TeacherModel
 * 
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-05-14
 *
 */
class TeacherModel extends CommonModel {

    public function lists($stu_id = 0, $s_id = 0, $c_id = 0, $teaName = '') {

        if (!$c_id) {
            return false;
        }

        if (is_array($c_id)) {
            $c_id = implode(',', $c_id);
        }

        // 组织条件
        $where['c_id'] = array('IN', $c_id);

        // 获取老师ID
        $a_ids = M('ClassSubjectTeacher')->where($where)->getField('a_id', TRUE);

        $map['a_id'] = array('IN', $a_ids);

        if ($teaName) {
            $map['a_nickname'] = array('LIKE', '%'.$teaName.'%');
        }

        // 获取老师昵称
        $authName = M('Auth')->where($map)->field('a_id,a_nickname,a_type')->select();

        return $authName;
    }
}
?>