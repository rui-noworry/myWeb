<?php
/**
 * ClasshourAction
 * 课时接口
 *
 * 作者:  徐少龙
 * 创建时间: 2013-7-2
 *
 */
class ClasshourAction extends OpenAction {

    // 获取课时列表
    public function lists() {

        // 拆分接收的参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id) || (!intval($l_id) && !intval($co_id)) || (!intval($c_id) && !intval($cro_id))) {
            $this->ajaxReturn($this->errCode[2]);
        }

        // 验证课文是否存在
        $lesson = M('Lesson')->where(array('l_id' => $l_id))->getField('co_id');
        if (!$lesson) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 判断班级或群组是否在已发布的课程内
        if ($c_id) {
            $flag = M('Course')->where(array('co_id' => $lesson, 'c_id' => array('like', "%,$c_id,%")))->getField('c_id');
            if (!$flag) {
                $this->ajaxReturn($this->errCode[6]);
            }
        }
        if ($cro_id) {
            $flag = M('Course')->where(array('co_id' => $lesson, 'cro_id' => array('like', "%,$cro_id,%")))->getField('c_id');
            if (!$flag) {
                $this->ajaxReturn($this->errCode[6]);
            }
        }

        // 获取课文下或课程下的课时
        $classhour = M('Classhour')->where(array('l_id' => $l_id, 'cl_status' => 1))->field('cl_id,co_id,l_id,a_id,s_id,cl_title,cl_sort,c_id,cro_id,cl_is_published,cl_created')->select();

        // 如果是学生身份，需要把没有绑定到该班级或群组的课时给过滤掉
        if ($this->auth['a_type'] == 1) {
            $data = array();
            foreach ($classhour as $key => $value) {
                if ($c_id && $value['c_id'] && strpos($value['c_id'], ',' . $c_id . ',') !== FALSE) {
                    $data[] = $value;
                }
                if ($cro_id && $value['cro_id'] && strpos($value['cro_id'], ',' . $cro_id . ',') !== FALSE) {
                    $data[] = $value;
                }
            }
            $classhour = array();
            $classhour = $data;
        }

        if (!$classhour) {
            $array['status'] = 0;
            $array['info'] = '无匹配数据';
        } else {
            $array['status'] = 1;
            $array['info'] = array('list' => $classhour);
        }

        $this->ajaxReturn($array);
    }

    // 课时发布
    public function publish() {

        // 拆分接收的参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id) || !intval($cl_id) || (!intval($c_id) && !intval($cro_id))) {
            $this->ajaxReturn($this->errCode[2]);
        }

        // 获取用户信息
        $this->auth = getAuthInfo($this->auth);

        // 仅限于教师可以发布
        if($this->auth['a_type'] != 2) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 验证课时是否存在
        $classhour = M('Classhour')->where(array('a_id' => $a_id, 'cl_id' => $cl_id))->find();
        if (!$classhour) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 获取课时所属课程的c_id、cro_id，co_subject字段
        $course = M('Course')->where(array('co_id' => $classhour['co_id']))->field('c_id,cro_id,co_subject')->find();

        // 需要绑定的班级或群组是否已绑定
        if ($c_id && $this->check($c_id, $classhour['c_id'], 'c_id', $course)) {
            $this->ajaxReturn($this->errCode[6]);
        }
        if ($cro_id && $this->check($cro_id, $classhour['cro_id'], 'cro_id', $course)) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 发布
        $this->confirmPublish($c_id, $cro_id, $classhour, $course);

    }

    // 检测绑定的班级或群组是否已绑定
    public function check($id, $string, $code, $course) {

        $id = explode(',', $id);

        // 判断班级获群组是否在所属课程已绑定好的班级群组内
        if ($code == 'c_id') {
            foreach ($id as $key => $value) {
                if (strpos($course['c_id'], ',' . $value . ',') === FALSE) {
                    $data = array('status' => 0, 'info' => '该班级不在绑定的课程内');
                    $this->ajaxReturn($data);
                }
            }
        }
        if ($code == 'cro_id') {
            foreach ($id as $key => $value) {
                if (strpos($course['cro_id'], ',' . $value . ',') === FALSE) {
                    $data = array('status' => 0, 'info' => '该群组不在已绑定的课程内');
                    $this->ajaxReturn($data);
                }
            }
        }

        // 如果有值，需判断是否已绑定
        if ($string) {
            $bindId = explode(',', trim($string, ','));
            $tmp = array_diff($id, $bindId);
            if (count($tmp) != count($id)) {
                $data = array('status' => 0, 'info' => ($code == 'c_id' ? '班级已绑定' : '群组已绑定'));
                $this->ajaxReturn($data);
            }
        }

        return 0;
    }

    // 发布
    public function confirmPublish($c_id, $cro_id, $classhour, $course) {

        // 去掉接口传值
        unset($_POST);

        $_POST['c_id'] = $c_id;
        $_POST['cro_id'] = $cro_id;
        $_POST['co_id'] = $classhour['co_id'];
        $_POST['l_id'] = $classhour['l_id'];
        $_POST['cl_id'] = $classhour['cl_id'];
        $_POST['ap_course'] = $course['co_subject'];

        // 组织数据
        $_POST['a_id'] = $this->auth['a_id'];
        $_POST['s_id'] = $this->auth['s_id'];

        // 指定班级和指定群组是分条处理的，先处理班级，再处理群组
        if ($_POST['c_id']) {
            $data['c_id'] = explode(',', $_POST['c_id']);
            $tmp['c_id'] = $_POST['c_id'];

            // 获取班级人数
            $classInfo = M('Class')->where(array('c_id' => array('IN', $_POST['c_id']), 's_id' =>$this->auth['s_id']))->field('c_id, c_peoples')->select();
            $classInfo = setArrayByField($classInfo, 'c_id');
        }

        if ($_POST['cro_id']) {
            $data['cro_id'] = explode(',', $_POST['cro_id']);
            $tmp['cro_id'] = $_POST['cro_id'];

            // 获取群组信息
            $crowdAuth = M('AuthCrowd')->where(array('cro_id' => array('IN', $data['cro_id'])))->select();

            $crowdInfo = array();
            foreach ($crowdAuth as $key => $value) {
                if (in_array($value['cro_id'], $data['cro_id'])) {
                    $crowdInfo[$value['cro_id']]['num'] += 1;
                }
            }
        }

        unset($_POST['c_id']);
        unset($_POST['cro_id']);

        // 查找该课时下所有的环节中所有自动发布的活动及其所属的作业
        $_POST['act_is_auto_publish'] = 1;
        $activity = setArrayByField(M('Activity')->where($_POST)->field('c_id,cro_id,ta_id,act_title,act_id,act_rel,act_type')->select(), 'act_id');

        // 如果有发布活动的话
        if ($activity) {
            $_POST['act_id'] = implode(',', getValueByField($activity, 'act_id'));
            $where['act_id'] = array('IN', $_POST['act_id']);
            $act_id = $_POST['act_id'];

            // 同时，把活动表中，已自动发布的活动那条数据中的是否发布过置为1
            $save['act_is_published'] = 1;
            M('Activity')->where($where)->save($save);
        }

        // 更新课时表里的绑定班级和绑定群组字段
        $where['cl_id'] = $_POST['cl_id'];
        $tmp['cl_updated'] = time();
        $tmp['cl_is_published'] = 1;
        $tmp['c_id'] = $classhour['c_id'] ? ',' . trim($classhour['c_id'] . ',' . $tmp['c_id'], ',') . ',' : (trim($tmp['c_id'], ',') ? ','. trim($tmp['c_id'], ',') . ',' : '');
        $tmp['cro_id'] = $classhour['cro_id'] ? ',' . trim($classhour['cro_id'] . ',' . $tmp['cro_id'], ',') . ',' : (trim($tmp['cro_id'], ',') ? ',' . trim($tmp['cro_id'], ',') . ',' : '');
        M('Classhour')->where($where)->save($tmp);

        unset($_POST['c_id']);
        unset($_POST['cro_id']);
        unset($tmp);

        // 活动发布对象总人数
        $peoples['act_peoples'] = 0;

        // 如果有指定班级，则循环写入表，最后得清掉c_id，否则会影响下面群组的写入
        if (!empty($data['c_id'])) {

            foreach ($data['c_id'] as $key => $value) {
                $_POST['c_id'] = $value;
                $_POST['ap_peoples'] = $classInfo[$value]['c_peoples'];
                $peoples['act_peoples'] += $classInfo[$value]['c_peoples'];
                $_POST['act_id'] = $act_id ? $act_id: '';
                $_POST['cp_created'] = time();
                $result = M('ClasshourPublish')->add($_POST);

                // 向活动发布表添加数据
                foreach ($activity as $key => &$valuec) {
                    $_POST['ta_id'] = $valuec['ta_id'];
                    $_POST['to_id'] = $valuec['act_rel'];
                    $_POST['act_id'] = $valuec['act_id'];
                    $_POST['act_type'] = $valuec['act_type'];
                    $_POST['act_title'] = $valuec['act_title'];
                    $_POST['ap_complete_time'] = strtotime('+1 days');
                    $_POST['ap_created'] = time();
                    M('ActivityPublish')->add($_POST);

                    // 更新发布对象的人数和c_id字段
                    if ($valuec['c_id']) {
                        $peoples['c_id'] = strpos($valuec['c_id'], ',' . $_POST['c_id'] . ',') === FALSE ? $valuec['c_id'] . $_POST['c_id'] . ',' : $valuec['c_id'];
                        $valuec['c_id'] = $peoples['c_id'];
                    } else {
                        $valuec['c_id'] = ',' . $_POST['c_id'] . ',';
                        $peoples['c_id'] = $valuec['c_id'];
                    }
                    M('Activity')->where(array('act_id' => $valuec['act_id']))->save($peoples);
                }
            }

            unset($_POST['c_id']);
        }

        if (!empty($data['cro_id'])) {

            foreach ($data['cro_id'] as $key => $value) {
                $_POST['cro_id'] = $value;
                $_POST['ap_peoples'] = $crowdInfo[$value]['num'];
                $peoples['act_peoples'] += $crowdInfo[$value]['num'];
                $_POST['act_id'] = $act_id ? $act_id : '';
                $_POST['cp_created'] = time();
                $result = M('ClasshourPublish')->add($_POST);

                // 向活动发布表添加数据
                foreach ($activity as $key => &$valuea) {
                    $_POST['ta_id'] = $valuea['ta_id'];
                    $_POST['to_id'] = $valuea['act_rel'];
                    $_POST['act_id'] = $valuea['act_id'];
                    $_POST['act_type'] = $valuea['act_type'];
                    $_POST['act_title'] = $valuea['act_title'];
                    $_POST['ap_complete_time'] = strtotime('+1 days');
                    $_POST['ap_created'] = time();
                    M('ActivityPublish')->add($_POST);

                    // 更新发布对象的人数和cro_id字段
                    if ($valuea['cro_id']) {
                        $peoples['cro_id'] = strpos($valuea['cro_id'], ',' . $_POST['cro_id'] . ',') === FALSE ? $valuea['cro_id'] . $_POST['cro_id'] . ',' : $valuea['cro_id'];
                        $valuea['cro_id'] = $peoples['cro_id'];
                    } else {
                        $valuea['cro_id'] = ',' . $_POST['cro_id'] . ',';
                        $peoples['cro_id'] = $valuea['cro_id'];
                    }
                    M('Activity')->where(array('act_id' => $valuea['act_id']))->save($peoples);
                }
            }
        }

        $data['status'] = 1;
        $data['info'] = '课时发布成功';
        $this->ajaxReturn($data);
    }
}