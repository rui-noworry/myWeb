<?php
/**
 * LessonAction
 * 课文接口
 *
 * 作者:  徐少龙
 * 创建时间: 2013-7-2
 *
 */
class LessonAction extends OpenAction {

    // 获取课文列表(注：略过单元)
    public function lists() {

        // 拆分参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id) || !intval($co_id) || (!intval($c_id) && !intval($cro_id))) {
            $this->ajaxReturn($this->errCode[2]);
        }

        // 判断班级或群组是否在已发布的课程内
        if ($c_id) {
            $flag = M('Course')->where(array('co_id' => $co_id, 'c_id' => array('like', "%,$c_id,%")))->getField('c_id');
            if (!$flag) {
                $this->ajaxReturn($this->errCode[6]);
            }
        }
        if ($cro_id) {
            $flag = M('Course')->where(array('co_id' => $co_id, 'cro_id' => array('like', "%,$cro_id,%")))->getField('c_id');
            if (!$flag) {
                $this->ajaxReturn($this->errCode[6]);
            }
        }

        // 查询课程下的所有课文
        $lesson = M('Lesson')->where(array('co_id' => $co_id))->field('l_id,co_id,a_id,l_pid,l_sort,l_title')->order('l_sort ASC')->select();
        $data = array();

        if ($lesson) {
            // 忽略单元
            foreach ($lesson as $key => $value) {
                if ($value['l_pid'] == 0) {
                    $tmp[$value['l_id']] = $value;
                }
            }
            foreach ($lesson as $k => $v) {
                if ($v['l_pid'] != 0) {
                    $tmp[$v['l_pid']]['list'][] = $v;
                }
            }
            foreach ($tmp as $ak => $av) {
                foreach ($av['list'] as $aak => $aav) {
                    unset($aav['l_pid']);
                    $data[]= $aav;
                }
            }

            if ($data) {
                $array['status'] = 1;
                $array['info'] = array('list' => $data);
            }

        } else {
            $array['status'] = 0;
            $array['info'] = '无匹配数据';
        }

        $this->ajaxReturn($array);

    }
}