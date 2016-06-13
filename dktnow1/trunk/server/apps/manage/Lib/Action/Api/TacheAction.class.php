<?php
/**
 * TacheAction
 * 环节接口
 *
 * 作者:  徐少龙
 * 创建时间: 2013-7-3
 *
 */
class TacheAction extends OpenAction {

    // 获取环节列表
    public function lists() {

        // 拆分接收的参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id) || !intval($cl_id) || (!intval($c_id) && !intval($cro_id))) {
            $this->ajaxReturn($this->errCode[2]);
        }

        // 验证课时是否存在
        $classhour = M('Classhour')->where(array('cl_id' => $cl_id))->getField('co_id');
        if (!$classhour) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 判断班级或群组是否在已发布的课程内
        if ($c_id) {
            $flag = M('Course')->where(array('co_id' => $classhour, 'c_id' => array('like', "%,$c_id,%")))->getField('c_id');
            if (!$flag) {
                $this->ajaxReturn($this->errCode[6]);
            }
        }
        if ($cro_id) {
            $flag = M('Course')->where(array('co_id' => $classhour, 'cro_id' => array('like', "%,$cro_id,%")))->getField('c_id');
            if (!$flag) {
                $this->ajaxReturn($this->errCode[6]);
            }
        }

        // 获取环节数据
        $tache = M('Tache')->where(array('cl_id' => $cl_id))->field('ta_id,a_id,co_id,l_id,cl_id,ta_title,act_id,ta_sort')->select();

        if (!$tache) {
            $array['status'] = 0;
            $array['info'] = '无匹配数据';
        } else {
            $array['status'] = 1;
            $array['info'] = array('list' => $tache);
        }

        $this->ajaxReturn($array);
    }
}