<?php
/**
 * ActivityAction
 * 活动接口
 *
 * 作者:  徐少龙
 * 创建时间: 2013-7-3
 *
 */
class ActivityAction extends OpenAction {

    // 获取环节下的活动列表
    public function lists() {

        // 拆分接收的参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id) || (!intval($ta_id) && !intval($cl_id)) || (!intval($c_id) && !intval($cro_id))) {
            $this->ajaxReturn($this->errCode[2]);
        }

        if ($cl_id) {
            $tmpWhere['cl_id'] = $cl_id;
        }

        if ($ta_id) {
            $tmpWhere['ta_id'] = $ta_id;
        }

        // 验证环节是否存在
        $tache = M('Tache')->where($tmpWhere)->getField('co_id');
        if (!$tache) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 判断班级或群组是否在已发布的课程内
        if ($c_id) {
            $flag = M('Course')->where(array('co_id' => $tache, 'c_id' => array('like', "%,$c_id,%")))->getField('c_id');
            if (!$flag) {
                $this->ajaxReturn($this->errCode[6]);
            }
        }
        if ($cro_id) {
            $flag = M('Course')->where(array('co_id' => $tache, 'cro_id' => array('like', "%,$cro_id,%")))->getField('c_id');
            if (!$flag) {
                $this->ajaxReturn($this->errCode[6]);
            }
        }

        if ($cl_id) {
            $where['cl_id'] = $cl_id;
        }

        if ($ta_id) {
            $where['ta_id'] = $ta_id;
        }

        if ($act_type) {
            $where['act_type'] = $act_type;
        }

        // 学生,获取已发布的活动
        if ($this->auth['a_type'] == 1) {
            $ids = setArrayByField(M('ActivityPublish')->where($where)->field('ap_id,act_id')->select(), 'act_id');
            if (!$ids) {
                $this->ajaxReturn(array('status' => 0, 'info' => '无匹配数据'));
            }
            $where['act_id'] = array('IN', getValueByField($ids, 'act_id'));
        }

        $activity = M('Activity')->where($where)->field('act_id,ta_id,act_title,c_id,cro_id,act_rel,act_type,act_sort,act_is_published')->order('act_sort ASC')->select();

        if (!$activity) {
            $array['status'] = 0;
            $array['info'] = '无匹配数据';
        } else {

            if ($c_id) {
                $where['c_id'] = $c_id;
            }
            if ($cro_id) {
                $where['cro_id'] = $cro_id;
            }
            $where['a_id'] = $this->auth['a_id'];
            $ap = setArrayByField(M('ActivityPublish')->where($where)->field('ap_id,act_id')->select(), 'act_id');

            foreach ($activity as $key => &$value) {

                // 学生
                if ($ids) {
                    $value['act_is_published'] = 1;
                    $value['ap_id'] = $ids[$value['act_id']]['ap_id'];

                    if ($value['act_type'] == 1) {
                        $activityData = M('ActivityData')->where(array('ap_id' => $value['ap_id'], 'a_id' => $a_id))->field('ad_status, ad_score')->find();

                        if ($activityData) {

                            $value['ad_status'] = $activityData['ad_status'];
                            $value['ad_persent'] = $activityData['ad_score'];
                        } else {
                            $value['ad_status'] = 0;
                            $value['ad_persent'] = 0;
                        }
                    }

                // 教师
                } else {
                    if ($c_id) {
                        $tmpCid = ',' . $c_id . ',';
                        if (strpos($value['c_id'], $tmpCid) !== FALSE) {
                            $value['act_is_published'] = 1;
                        } else {
                            $value['act_is_published'] = 0;
                        }
                        if ($ap && array_key_exists($value['act_id'], $ap)) {
                            $value['ap_id'] = $ap[$value['act_id']]['ap_id'];
                        } elseif (!$ap) {
                            $value['ap_id'] = 0;
                        } elseif ($ap && !array_key_exists($value['act_id'], $ap)) {
                            $value['ap_id'] = 0;
                        }
                    }
                    if ($cro_id) {
                        $tmpCroId = ',' . $cro_id . ',';
                        if (strpos($value['cro_id'], $tmpCroId) !== FALSE) {
                            $value['act_is_published'] = 1;
                        } else {
                            $value['act_is_published'] = 0;
                        }
                        if ($ap && array_key_exists($value['act_id'], $ap)) {
                            $value['ap_id'] = $ap[$value['act_id']]['ap_id'];
                        } elseif (!$ap) {
                            $value['ap_id'] = 0;
                        } elseif ($ap && !array_key_exists($value['act_id'], $ap)) {
                            $value['ap_id'] = 0;
                        }
                    }
                }

            }

            $array['status'] = 1;
            $array['info'] = array('list' => $activity);
        }

        $this->ajaxReturn($array);
    }

    // 获取活动详情
    public function detail() {

        // 拆分数组
        extract($_POST['args']);

        $result = D('Activity')->detail($a_id, $act_id, $c_id, $cro_id, $this->auth['a_type']);

        if ($result['status'] != 1 && !$result['info']) {
            $this->ajaxReturn($result['status']);
        }

        $this->ajaxReturn($result);
    }

    // 发布除了练习，作业以外的活动
    public function publish () {

        extract($_POST['args']);

        // 接收参数
        if (empty($act_id) || empty($a_id) || empty($act_type) || (empty($c_id) && empty($cro_id))) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 权限验证
        if (!$activity = M('Activity')->where(array('a_id' => $a_id, 'act_type' => $act_type, 'act_id' => $act_id))->find()) {
            $this->ajaxReturn($this->errCode[7]);
        }

        // 处理数据
        $activity['to_id'] = $activity['act_rel'] ? $activity['act_rel'] : '';
        $activity['ap_created'] = time();
        $activity['ap_course'] = M('Course')->where(array('a_id' => $a_id, 'co_id' => $activity['co_id']))->getField('co_subject');

        // 如果有绑定班级或是群组的话，刚刚发布的活动记录给添加到活动发布表里去
        if (strval($c_id) != '' || strval($cro_id) != '') {

            // 更新课时发布表相关的c_id和cro_id
            $where['cl_id'] = $activity['cl_id'];
            $where['a_id'] = $this->auth['a_id'];
            $where['s_id'] = $this->auth['s_id'];
            $res = M('ClasshourPublish')->where($where)->field('cp_id,act_id,cl_id')->select();

            // 活动发布对象总人数
            $peoples['act_peoples'] = 0;

            if ($c_id) {

                $c_id = explode(',', strval(trim($c_id, ',')));

                // 获取班级人数
                $classInfo = M('Class')->where(array('c_id' => array('IN', $c_id), 's_id' => $this->auth['s_id']))->field('c_id, c_peoples')->select();
                $classInfo = setArrayByField($classInfo, 'c_id');

                foreach ($c_id as $key => $value) {

                    if (strstr($activity['c_id'], ','.$value.',')) {
                        $data['message'] = '您已经发布过该活动';
                        break;
                    }

                    $peoples['c_id'] .= ','.$value;
                    $activity['c_id'] = $value;
                    $activity['ap_peoples'] = intval($classInfo[$value]['c_peoples']);
                    $peoples['act_peoples'] += $classInfo[$value]['c_peoples'];
                    $where['c_id'] = $value;


                    $data['status'] = M('ActivityPublish')->add($activity);

                    foreach ($res as $k => $v) {
                        $save['act_id'] = $v['act_id'] ? $v['act_id'] . ',' . $activity['act_id'] : $activity['act_id'];
                        $save['cp_updated'] = time();
                        $where['cp_id'] = $v['cp_id'];
                        M('ClasshourPublish')->where($where)->save($save);

                     }
                }

            }

            if ($cro_id) {

                $cro_id = explode(',', strval(trim($cro_id, ',')));

                // 获取群组信息
                $crowdAuth = M('AuthCrowd')->where(array('cro_id' => array('IN', $cro_id)))->select();

                $crowdInfo = array();
                foreach ($crowdAuth as $key => $value) {
                    if (in_array($value['cro_id'], $cro_id)) {
                        $crowdInfo[$value['cro_id']]['num'] += 1;
                    }
                }

                foreach ($cro_id as $key => $value) {

                    if (strstr($activity['cro_id'], ','.$value.',')) {
                        $data['message'] = '您已经发布过该活动';
                        break;
                    }

                    $peoples['cro_id'] .= ','.$value;

                    $activity['cro_id'] = $value;
                    $activity['ap_peoples'] = $crowdInfo[$value]['num'];
                    $peoples['act_peoples'] += $crowdInfo[$value]['num'];
                    $where['cro_id'] = $value;
                    $data['status'] = M('ActivityPublish')->add($activity);
                    foreach ($res as $k => $v) {
                        $save['act_id'] = $v['act_id'] ? $v['act_id'] . ',' . $activity['act_id'] : $activity['act_id'];
                        $save['cp_updated'] = time();
                        $where['cp_id'] = $v['cp_id'];
                        M('ClasshourPublish')->where($where)->save($save);
                     }
                }

            }
        }

        if ($data['status']) {
            // 更新发布对象的人数
            $peoples['act_is_published'] = 1;

            if ($c_ids) {
                $peoples['c_id'] .= $c_ids;
            } else {
                $peoples['c_id'] .= ',';
            }

            if ($cro_ids) {
                $peoples['cro_id'] .= $cro_ids;
            } else {
                $peoples['cro_id'] .= ',';
            }

            M('Activity')->where(array('act_id' => $act_id))->save($peoples);
        }

        $data['status'] = $data['status'] ? $data['status'] : 0;
        $this->ajaxReturn($data);
    }

    // 活动讨论接口
    public function activityTalk () {

        extract($_POST['args']);

        // 接收参数
        if (empty($a_id) || empty($ap_id) || empty($at_content)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 获取活动
        $activityPublish = M('ActivityPublish')->where(array('ap_id' => $ap_id))->find();

        if (!$activityPublish) {
            $this->ajaxReturn($this->errCode[7]);
            exit;
        }

        if ($this->auth['a_type'] == 1) {

            if ($activityPublish['c_id']) {
                // 验证班级学生
                if (!M('ClassStudent')->where(array('c_id' => $activityPublish['c_id'], 'a_id' => $a_id, 's_id' => $activityPublish['s_id']))->find()) {
                    $this->ajaxReturn($this->errCode[4]);
                    exit;
                }
            }

            if ($activityPublish['cro_id']) {
                // 验证群组学生
                if (!M('AuthCrowd')->where(array('cro_id' => $activityPublish['cro_id'], 'a_id' => $a_id, 's_id' => $activityPublish['s_id']))->find()) {
                    $this->ajaxReturn($this->errCode[4]);
                    exit;
                }
            }

        }

        if ($this->auth['a_type'] == 2) {
            if ($a_id != $activityPublish['a_id']) {
                $this->ajaxReturn($this->errCode[4]);
                exit;
            }
        }

        $data['a_id'] = $a_id;
        $data['at_content'] = $at_content;
        $data['ap_id'] = $ap_id;
        $data['s_id'] = $activityPublish['s_id'];
        $data['co_id'] = $activityPublish['co_id'];
        $data['cl_id'] = $activityPublish['cl_id'];
        $data['l_id'] = $activityPublish['l_id'];
        $data['act_id'] = $activityPublish['act_id'];
        $data['at_created'] = time();

        $result['status'] = M('ActivityTalk')->add($data);

        $this->ajaxReturn($result);

    }

    // 获取讨论列表
    public function talks() {

        extract($_POST['args']);

        // 接收参数
        if (empty($a_id) || (empty($act_id) && empty($ap_id))) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        if ($this->auth['a_type'] == 1) {

            if (!$activity = M('ActivityPublish')->where(array('ap_id' => intval($ap_id), 's_id' => $this->auth['s_id']))->find()) {
                $this->ajaxReturn($this->errCode[7]);
                exit;
            }

            if ($activity['c_id']) {

                // 验证学生所在班级
                if (!M('ClassStudent')->where(array('a_id' => $a_id, 'c_id' => $activity['c_id'], 's_id' => $this->auth['s_id']))->find()) {
                    $this->ajaxReturn($this->errCode[4]);
                    exit;
                }
            }

            if ($activity['cro_id']) {

                // 验证学生所在群组
                if (!M('AuthCrowd')->where(array('a_id' => $a_id, 'cro_id' => $activity['cro_id'], 's_id' => $this->auth['s_id']))->find()) {
                    $this->ajaxReturn($this->errCode[4]);
                    exit;
                }
            }
        }

        if ($this->auth['a_type'] == 2) {

            if (!$activity = M('Activity')->where(array('act_id' => $act_id, 'a_id' => $a_id))->find()) {
                $this->ajaxReturn($this->errCode[7]);
                exit;
            }
        }

        $map['act_id'] = $activity['act_id'];

        $map['s_id'] = $this->auth['s_id'];

        $result = M('ActivityTalk')->where($map)->order('at_id DESC')->select();

        $authInfo = getDataByArray('Auth', $result, 'a_id', 'a_id, a_nickname');

        foreach ($result as $key => $value) {
            $result[$key]['a_nickname'] = $authInfo[$value['a_id']]['a_nickname'];
            $result[$key]['a_avatar'] = turnTpl(getAuthAvatar($this->auth['a_avatar'], $this->auth['a_type'], $this->auth['a_sex']));
        }

        $array['info'] = array('list' => $result);

        $this->ajaxReturn($array);
    }
}