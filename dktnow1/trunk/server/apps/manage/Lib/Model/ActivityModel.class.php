<?php
/**
 * ActivityModel
 * 活动接口模型
 *
 * 作者:  徐少龙
 * 创建时间: 2012-7-8
 *
 */
class ActivityModel extends CommonModel {

    /**
     * detail
     * 活动详情
     *
     * @param int $a_id 用户ID
     * @param int $act_id 活动ID
     * @param int $c_id 班级ID
     * @param int $cro_id 群组ID
     * @param int $type 用户类型 1学生 2教师
     *
     * 返回值
     * status 值为2，表示参数没有传递完全
     * status 值为6，非法操作
     * status 值为0，无匹配数据
     * status 值为1，返回正确数据
     */
    public function detail($a_id, $act_id, $c_id=0, $cro_id=0, $type) {

        // 校验
        if (!intval($a_id) || !intval($act_id) || (!intval($c_id) && !intval($cro_id))) {
            return array('status' => 2);
        }

        // 验证活动是否存在
        $activity = M('Activity')->where(array('act_id' => $act_id))->field('act_id,act_title,act_rel,act_type,c_id,cro_id,act_is_published,co_id,act_note')->find();
        if (!$activity) {
            return array('status' => 6);
        }

        // 判断班级或群组是否在已发布的课程内
        if ($c_id) {
            $flag = M('Course')->where(array('co_id' => $activity['co_id'], 'c_id' => array('like', "%,$c_id,%")))->getField('c_id');
            if (!$flag) {
                return array('status' => 6);
            }
        }
        if ($cro_id) {
            $flag = M('Course')->where(array('co_id' => $activity['co_id'], 'cro_id' => array('like', "%,$cro_id,%")))->getField('c_id');
            if (!$flag) {
                return array('status' => 6);
            }
        }

        // 教师
        if ($type == 2) {
            $hRel = $activity['act_rel'];

        // 学生
        } else {
            $where['act_id'] = $act_id;
            if ($c_id) {
                $where['c_id'] = $c_id;
            } elseif ($cro_id) {
                $where['cro_id'] = $cro_id;
            }
            $activityPublish = M('ActivityPublish')->where($where)->field('act_id,to_id')->find();
            if (!$activityPublish) {
                return array('status' => 0, 'info' => '无匹配值');
            } else {
                $hRel = $activityPublish['to_id'];
            }
        }

        // 1 2 为作业和练习
        if ($activity['act_type'] == 1 || $activity['act_type'] == 2) {

            // 还需查询该活动下是否有附件
            $ar_id = trim(M('ActivityAttachment')->where(array('act_id' => $act_id))->field('act_id,ar_id')->getField('ar_id'), ',');

            $img = array();

            if ($ar_id) {
                $arMap['ar_id'] = array('IN', $ar_id);
                $authResource = setArrayByField(M('AuthResource')->where($arMap)->select(), 'ar_id');

                foreach ($authResource as $kkk => $vvv) {
                    $vvv['img_path'] = getResourceImg($vvv, 0, 100, 2);
                    $img[] = $vvv;
                }
            }

            if ($hRel) {
                $path = C('TOPIC_TMP_PATH') . 'Image/';
                $topic = M('Topic')->where(array('to_id' => array('IN', $hRel)))->select();
                foreach ($topic as $tk => &$tv) {
                    $tv['to_title'] = urlencode($tv['to_title']);
                    $tv['path'] = turnTpl($path . $tv['to_id'] . '.png');
                }
            }

            $activity['attachment'] = $img;
            $activity['topic'] = $topic;

        // 3为文本
        } elseif ($activity['act_type'] == 3) {
            $path = C('ACTIVITY_TMP_PATH') . 'Image/';
            $activity['act_note'] = turnTpl($path . $activity['act_id'] . '.png');
        // 4为链接，去链接表里查询相关数据
        }  elseif ($activity['act_type'] == 4) {
            $link = M('Link')->where(array('li_id' => array('IN', $hRel)))->field('li_id,li_title,li_url')->select();
            $activity['link'] = $link;
         // 5为扩展阅读
        } elseif ($activity['act_type'] == 5) {
            $data = M('AuthResource')->where(array('ar_id' => array('IN', $hRel)))->select();

            $model = loadCache('model');
            $model = setArrayByField($model, 'm_id');

            $filePath = getResourceConfigInfo(0);

            foreach ($data as $key => &$value) {
                $value['img_path'] = getResourceImg($value, 0, 100, 2);
                $time = date(C('RESOURCE_SAVE_RULES'), $value['ar_created']);

                if ($value['m_id'] == 1) {
                    $value['filePath'] = $filePath['Path'][$value['ar_is_transform']].$model[$value['m_id']]['m_name'].'/'.$time.'/600/'.$value['ar_savename'];
                } elseif ($value['m_id'] == 4) {
                    $value['filePath'] = $filePath['Path'][$value['ar_is_transform']].$model[$value['m_id']]['m_name'].'/'.$time.'/'. getFileName($value['ar_savename'], 'pdf');
                } else {
                    $value['filePath'] = $filePath['Path'][$value['ar_is_transform']].$model[$value['m_id']]['m_name'].'/'.$time.'/'.$value['ar_savename'];
                }
                $value['ar_savename'] = turnTpl($value['filePath']);
            }
            $activity['resource'] = $data;
        }

        $result['status'] = 1;
        $result['info'] = array('list' => $activity);

        return $result;
    }

    // 学生提交作业和练习的统计
    public function stats($hd_answer) {

        $stuAnswer = json_decode($hd_answer, TRUE);
        $toIds = array_keys($stuAnswer);

        $topicArr = M('Topic')->where(array('to_id' => array('IN', implode(',', $toIds))))->field('to_id, to_answer, to_type')->select();
        $topicArr = setArrayByField($topicArr, 'to_id');

        foreach ($stuAnswer as $sk => $sv) {

            if ($topicArr[$sk]['to_type'] != 3 && $topicArr[$sk]['to_type'] != 5) {

                $to_answer = json_decode($topicArr[$sk]['to_answer']);
                $to_answer = $to_answer[0];

            } else if ($topicArr[$sk]['to_type'] == 3) {

                $to_answer = $topicArr[$sk]['to_answer'];

            } else if ($topicArr[$sk]['to_type'] == 5) {

                $to_answer = json_decode($topicArr[$sk]['to_answer']);
                $to_answer = '"'.$to_answer[0].'"';
            }

            if ($topicArr[$sk]['to_type'] != 5) {

                if ($sv == $to_answer) {
                    $stat[$sk] = 1;
                } else {
                    $stat[$sk] = 0;
                }

            } else {

                $stat[$sk] = 0;

             }

        }

        $ad_stat = json_encode($stat);

        return $ad_stat;
    }

    // 老师查看作业或练习的统计
    public function teacherStats($stuIds, $res) {

        $activityData = M('ActivityData')->where(array('a_id' => array('IN', $stuIds), 'ap_id' => $res['ap_id']))->field('ad_stat')->select();

        foreach ($activityData as $key => $value) {

            $statArr = json_decode($value['ad_stat'], TRUE);

            foreach ($statArr as $sk => $sv) {

                if ($sv == 1) {
                    $data[$sk] += 1;
                } else {
                    $data[$sk] += 0;
                }
            }

        }

        return $data;
    }
}