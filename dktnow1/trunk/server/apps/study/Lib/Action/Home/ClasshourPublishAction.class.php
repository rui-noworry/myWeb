<?php
/**
 * ClasshourPublishAction
 * 课时发布类
 *
 * 作者:  徐少龙 (xusl@mink.com.cn)
 * 创建时间: 2013-5-23
 *
 */
class ClasshourPublishAction extends BaseAction {

    // 添加
    public function insert() {

        // 如果班级和群组都为空的话，则不用直接退出
        if (strval($_POST['c_id']) == '' && strval($_POST['cro_id']) == '' ) {
            exit;
        }

        // 组织数据
        $_POST['a_id'] = $this->authInfo['a_id'];
        $_POST['s_id'] = $this->authInfo['s_id'];

        // 指定班级和指定群组是分条处理的，先处理班级，再处理群组
        if ($_POST['c_id']) {
            $data['c_id'] = explode(',', $_POST['c_id']);
            $tmp['c_id'] = $_POST['c_id'];

            // 获取班级人数
            $classInfo = M('Class')->where(array('c_id' => array('IN', $_POST['c_id']), 's_id' => $this->authInfo['s_id']))->field('c_id, c_peoples')->select();
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

        // 验证
        $classhour = $this->checkOwner($_POST, 'Classhour');

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
                $result = $this->insertData();

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

                    // 更新题目发布对象的总人数
                    if ($valuec['act_rel']) {

                        $to_peoples['to_peoples'] = $peoples['act_peoples'];
                        M('Topic')->where(array('to_id' => array('IN', $valuec['act_rel'])))->save($to_peoples);
                    }

                }

                // 动态表
                addTrend($this->authInfo['a_id'], $this->authInfo['s_id'], $value, 3, 7, 0, M('Course')->where(array('co_id' => $classhour['co_id']))->getField('co_subject'), $classhour['cl_title'], $classhour['cl_id']);

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
                $result = $this->insertData();

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

                    // 更新题目发布对象的总人数
                    if ($valuea['act_rel']) {

                        $to_peoples['to_peoples'] = $peoples['act_peoples'];
                        M('Topic')->where(array('to_id' => array('IN', $valuea['act_rel'])))->save($to_peoples);
                    }

                }
            }

        }

        // 更新所属课程时间
        M('Course')->where(array('co_id' => $classhour['co_id']))->save(array('co_updated' => time()));

        unset($_POST);
        $this->success('课时发布成功');
    }

}