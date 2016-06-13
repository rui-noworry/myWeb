<?php
/**
 * LessonModel
 * 课文模型
 *
 */
class LessonModel extends CommonModel{

    // 获取该课程指定的班级和群组
    public function getBindClassAndGroup($a_id, $s_id, $c_id = 0, $cro_id = 0) {

        $data = array();

        // 如果都为空返回个空数组
        if (!$c_id && !$cro_id) {
            return $data;
        }

        $c_id = M('ClassSubjectTeacher')->where(array('a_id' => $a_id, 'c_id' => array('IN', $c_id)))->getField('c_id', TRUE);

        $where['s_id'] = $s_id;

        // 如果有绑定的班级
        if ($c_id) {
            $where['c_id'] = array('IN', $c_id);
            $class = M('Class')->where($where)->field('s_id,c_type,c_grade,c_title,c_is_graduation,c_id')->select();

            // 获取班级名称
            foreach ($class as $key => &$value) {
                $value['c_title'] = replaceClassTitle($value['s_id'], $value['c_type'], YearToGrade($value['c_grade'], $value['s_id']), $value['c_title'], $value['c_is_graduation']);
            }
        }

        // 如果有群组
        if ($cro_id) {
            $where['cro_id'] = array('IN', $cro_id);
            $group = M('Crowd')->where($where)->field('cro_id, cro_title')->select();
        }

        $data['class'] = $class ? $class : array();
        $data['group'] = $group ? $group : array();

        return $data;
    }
}