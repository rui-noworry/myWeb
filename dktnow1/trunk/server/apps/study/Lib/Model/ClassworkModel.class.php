<?php
/**
 * ClassworkModel
 * 练习模型
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-27
 *
 */
class ClassworkModel extends CommonModel {

    // 学生提交练习
    public function studentCommit($c_id, $s_id, $a_id, $ap_id, $ad_answer, $ad_persent = 0, $ad_storage = 0) {

        // 组织数据
        $data['a_id'] = $a_id;
        $data['ap_id'] = $ap_id;

        $classwork = M('ActivityPublish')->where(array('ap_id' => $ap_id, 's_id' => $s_id))->find();

        if ($classwork['c_id']) {
            // 验证班级学生
            if (!M('ClassStudent')->where(array('c_id' => $classwork['c_id'], 'a_id' => $a_id, 's_id' => $s_id))->find()) {
                $result['status'] = 0;
                $result['message'] = '您没有此练习';
            }
        }

        if ($classwork['cro_id']) {
            // 验证群组学生
            if (!M('AuthCrowd')->where(array('cro_id' => $classwork['cro_id'], 'a_id' => $a_id, 's_id' => $s_id))->find()) {
                $result['status'] = 0;
                $result['message'] = '您没有此练习';
            }
        }

        $data['ad_answer'] = stripslashes($ad_answer);
        $data['ad_created'] = time();
        $data['ad_status'] = 1;
        $data['cl_id'] = $classwork['cl_id'];
        $data['co_id'] = $classwork['co_id'];
        $data['l_id'] = $classwork['l_id'];
        $data['ap_id'] = $ap_id;
        $data['ad_use_time'] = intval($_POST['ad_use_time']);
        $data['ad_storage'] = $ad_storage;

        if (intval($_POST['ad_out_time'])) {
            $data['ad_out_time'] = intval($_POST['ad_out_time']);

            if (intval($_POST['ad_out_time']) < 60) {
                $result['message'] = '您已超时'.intval($_POST['ad_out_time']).'秒';
            } else {
                $result['message'] = '您已超时'.ceil(intval($_POST['ad_out_time'])/60).'分钟';
            }

        }

        // 提交
        M('ActivityPublish')->where(array('ap_id' => $ap_id))->setInc('ap_count');

        // 如果提交练习，不是存为草稿
        if ($ad_storage) {

            $ad_stat = $homeworkStat = D('Homework')->stats(stripslashes($ad_answer));

            // 统计ActivityPublish, Activity, Topic 表
            $ad_stat = get_object_vars(json_decode($ad_stat));

            // 活动发布表
            // 如果还没有被统计
            if (!$classwork['ap_stat'] || is_null($classwork['ap_stat'])) {

                $topic = explode(',', $classwork['to_id']);

                foreach ($topic as $key => $value) {
                    $stat[$value] = 0;
                }

            } else {
                $stat = json_decode($classwork['ap_stat'], TRUE);
            }

            // 为活动表处理做准备
            $tmp = $stat;

            // 正确的题目加1
            foreach ($stat as $key => $value) {
                if ($ad_stat[$key] == 1) {
                    $stat[$key] = $value + 1;
                }
            }

            $actPublish['ap_id'] = $ap_id;
            $actPublish['ap_stat'] = json_encode($stat);

            // 更新活动发布表
            M('ActivityPublish')->save($actPublish);

            // 活动表
            $activity = M('Activity')->where(array('act_id' => $classwork['act_id']))->field('act_id, act_stat')->find();

            // 如果没有被统计过
            if (!$activity['act_stat'] || is_null($actvity['act_stat'])) {
                $activityStat = $tmp;
            } else {
                $activityStat = get_object_vars(json_decode($activity['act_stat'], TRUE));
            }

            // 正确的题目加1
            foreach ($activityStat as $key => $value) {
                if ($ad_stat[$key] == 1) {
                    $activityStat[$key] = $value + 1;
                }
            }

            $actStat['act_id'] = $activity['act_id'];
            $actStat['act_stat'] = json_encode($activityStat);

            // 更新活动表
            M('Activity')->save($actStat);

            // 题目表
            $topics = M('Topic')->where(array('to_id' => array('IN', implode(',', array_keys($ad_stat)))))->field('to_id, to_right_peoples')->select();
            $topics = setArrayByField($topics, 'to_id');

            foreach ($ad_stat as $key => $value) {
                if ($value == 1) {
                    M('Topic')->where(array('to_id' => $key))->setInc('to_right_peoples');
                }
            }

        }

        $data['ad_stat'] = $homeworkStat;

        // 获取作业答案信息
        $classworkData = M('ActivityData')->where($data)->find();

        if ($classworkData['ad_id']) {

            $data['ad_id'] = $classworkData['ad_id'];
            M('ActivityData')->save($data);

            // 添加动态
            addTrend($a_id, $s_id, $c_id, 4, 2, 0, $classwork['ap_course'], $classwork['act_title'], $classwork['act_id']);
        } else {
            M('ActivityData')->add($data);

            // 添加动态
            addTrend($a_id, $s_id, $c_id, 1, 2, 0, $classwork['ap_course'], $classwork['act_title'], $classwork['act_id']);
        }

        // 操作成功后，还需要把刚刚添加的作业附件给转移到相应的活动目录去
        $classworkPath = C('ACTIVITY_PATH') . 'ActivityData/' . ($ap_id % C('MAX_FILES_NUM')) . '/' . $ap_id . '/' . $a_id . '/';

        if (!is_dir($classworkPath)) {
            mkdir($classworkPath, 0700, TRUE);
        }

        if (intval($_POST['uploader_count']) > 0) {
            $authPath = C('ACTIVITY_PATH') . 'ActivityData/tmp/' . $a_id . '/';
            $files = getFiles($authPath);
            if ($files) {
                foreach ($files as $key => $value) {
                    rename($value, $classworkPath . ltrim(str_replace(dirname($value), '', $value), '/'));
                }
            }
        }


        return $result;
    }


    // 教师设置学生重做或通过
    public function teacherSetStatus($a_id, $s_id) {

        $ad_id = intval($_POST['ad_id']);
        $ad_persent = intval($_POST['ad_persent']);
        $ad_score = intval($_POST['ad_score']);
        $ad_remark = $_POST['ad_remark'];
        $ad_status = intval($_POST['ad_status']);
        $stu_id = intval($_POST['a_id']);
        $ad_stat = stripslashes(stripslashes(htmlspecialchars_decode($_POST['ad_stat'])));
        $ad_shortanswer = stripslashes(htmlspecialchars_decode($_POST['short_answer']));
        $shortAnswer = json_decode($ad_shortanswer, TRUE);

        if (intval($ad_status) != 2 && intval($ad_status) != 4) {
            return 0;exit;
        }

        if (!$ad_id) {
            return 0;exit;
        }

        // 验证是否为此学生作业
        $activityData = M('ActivityData')->where(array('ad_id' => $ad_id, 'a_id' => $stu_id))->find();
        $ap_id = $activityData['ap_id'];

        if (!$ap_id) {
            return 0;exit;
        }

        // 验证是否为该老师发布的
        $activityPublish = M('ActivityPublish')->where(array('ap_id' => $ap_id, 'a_id' => $a_id))->field('act_id, act_title, to_id, ap_stat, ap_course')->find();

        if (!$activityPublish) {
            return 0;exit;
        }

        $ad_stat = get_object_vars(json_decode($ad_stat));

        // 发回重做
        if (intval($ad_status) == 2) {

            $ad_stat = get_object_vars(json_decode($activityData['ad_stat']));

            // activityPublish
            $ap_stat = get_object_vars(json_decode($activityPublish['ap_stat']));

            foreach ($ap_stat as $apk => $apv) {
                if ($ad_stat[$apk] == 1) {
                    $ap_stat[$apk] -= 1;
                }
            }

            $actPublish['ap_id'] = $ap_id;
            $actPublish['ap_stat'] = json_encode($ap_stat);

            // 更新活动发布表
            //M('ActivityPublish')->save($actPublish);

            // activity表
            // 活动表
            $activity = M('Activity')->where(array('act_id' => $activityPublish['act_id']))->field('act_id, act_stat')->find();

            $activityStat = json_decode($activity['act_stat'], TRUE);

            // 正确的题目加1
            foreach ($activityStat as $key => $value) {
                if ($ad_stat[$key] == 1) {
                    $activityStat[$key] -= 1;
                }
            }

            $actStat['act_id'] = $activity['act_id'];
            $actStat['act_stat'] = json_encode($activityStat);

            // 更新活动表
            //M('Activity')->save($actStat);

            // 题目表
            $topics = M('Topic')->where(array('to_id' => array('IN', implode(',', array_keys($ad_stat)))))->field('to_id, to_right_peoples')->select();
            $topics = setArrayByField($topics, 'to_id');


            foreach ($ad_stat as $key => $value) {
                if ($value == 1) {

                    M('Topic')->where(array('to_id' => $key))->setDec('to_right_peoples');
                    $ad_stat[$key] -= 1;
                }
            }

            // activityData表
            $data['ad_stat'] = json_encode($ad_stat);
            $data['ad_storage'] = 0;

        }

        // 如果批阅完成
        if (intval($ad_status) == 4) {

            // 统计简答题
            foreach ($ad_stat as $ak => $av) {
                if (in_array($ak, array_keys($shortAnswer))) {
                    if ($shortAnswer[$ak] == 1) {
                        $ad_stat[$ak] += 1;
                    }
                }
            }

            // 题目对错统计
            $data['ad_stat'] = json_encode($ad_stat);

            // 活动发布表
            // 如果还没有被统计
            if (!$activityPublish['ap_stat'] || is_null($activityPublish['ap_stat'])) {

                $topic = explode(',', $activityPublish['to_id']);

                foreach ($topic as $key => $value) {
                    $stat[$value] = 0;
                }

            } else {
                $stat = json_decode($activityPublish['ap_stat'], TRUE);
            }

            // 为活动表处理做准备
            $tmp = $stat;

            // 正确的题目加1
            foreach ($stat as $key => $value) {
                if ($ad_stat[$key] == 1) {
                    $stat[$key] = $value + 1;
                }
            }

            $actPublish['ap_id'] = $ap_id;
            $actPublish['ap_stat'] = json_encode($stat);

            // 更新活动发布表
            M('ActivityPublish')->save($actPublish);

            // 活动表
            $activity = M('Activity')->where(array('act_id' => $activityPublish['act_id']))->field('act_id, act_stat')->find();

            // 如果没有被统计过
            if (!$activity['act_stat'] || is_null($actvity['act_stat'])) {
                $activityStat = $tmp;
            } else {
                $activityStat = get_object_vars(json_decode($activity['act_stat'], TRUE));
            }

            // 正确的题目加1
            foreach ($activityStat as $key => $value) {
                if ($ad_stat[$key] == 1) {
                    $activityStat[$key] = $value + 1;
                }
            }

            $actStat['act_id'] = $activity['act_id'];
            $actStat['act_stat'] = json_encode($activityStat);

            // 更新活动表
            M('Activity')->save($actStat);

            // 题目表
            $topics = M('Topic')->where(array('to_id' => array('IN', implode(',', array_keys($ad_stat)))))->field('to_id, to_type, to_right_peoples')->select();
            $topics = setArrayByField($topics, 'to_id');

            foreach ($ad_stat as $key => $value) {
                if ($value == 1 && $topics[$key]['to_type'] == 5) {
                    M('Topic')->where(array('to_id' => $key))->setInc('to_right_peoples');
                }
            }

            // 作业分数
            $data['ad_score'] = $ad_score;

            // 根据分数给学生加智慧豆
            $score_bean = C('HOMEWORK_SCORE_BEAN');

            foreach ($score_bean as $key => $value) {
                $scoreBean = explode('-', $key);
                if (in_array($ad_score, range($scoreBean[0], $scoreBean[1]))) {
                    $bean['b_num']  = $value;
                }
            }

            // 获取学生所在班级
            $class = M('ClassStudent')->where(array('a_id' => $stu_id, 's_id' => $s_id))->select();

            // 学生可能在多个班级, 获取第一个
            $c_id = $class[0]['c_id'];

            // 增加智慧豆
            if ($bean['b_num']) {
                addBean($stu_id, $s_id, $c_id, 1, $activityPublish['act_id'], $bean['b_num'], $activityPublish['act_title'], 1, 0);
            }

            // 添加动态
            addTrend($a_id, $s_id, $c_id, 3, 2, $stu_id, $activityPublish['ap_course'], $activityPublish['act_title'], $activityPublish['act_id']);
        }

        // 条件
        $where = array('ad_id' => $ad_id, 'h_id' => $h_id, 'a_id' => $stu_id);
        $data['ad_persent'] = $ad_persent;
        $data['ad_remark'] = $ad_remark;
        $data['ad_status'] = $ad_status;

        if ($ad_shortanswer != undefined) {
            $data['ad_shortanswer'] = $ad_shortanswer;
        }

        $res = M('ActivityData')->where($where)->save($data);

        if ($res !== false) {
            return 1;
        } else {
            return 0;
        }
    }


}