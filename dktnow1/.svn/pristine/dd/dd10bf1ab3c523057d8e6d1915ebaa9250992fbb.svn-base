<?php
/**
 * TrendModel
 * 动态模型
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-05-14
 *
 */
class TrendModel extends CommonModel {

    // 动态列表
    public function lists($a_id, $a_type, $c_id = 0, $subject = 0, $is_ajax = 0, $p = 1) {

        // 班主任或学生查看任课教师的动态
        if ($a_id && $c_id && $subject && !$a_type) {

            $where['a_id'] = $a_id;
            $where['c_id'] = $c_id;
            $where['tr_course'] = $subject;

        } else if ($a_id && !$a_type && !$c_id && !$subject) {

            // 与我相关的动态
            $where['tr_to_id'] = $a_id;

        } else {

            // 教师班级动态
            if ($a_type == 2) {

                $cstWhere['c_id'] = $c_id;
                if ($subject) {
                    $cstWhere['cst_course'] = $subject;
                }

                if (M('Class')->where(array('c_id' => $c_id, 'a_id' => $a_id))->getField('c_id')) {

                    // 班主任获取本班所有教师及学生动态
                    $teachers = M('ClassSubjectTeacher')->where($cstWhere)->getField('a_id', TRUE);
                    $students = M('ClassStudent')->where(array('c_id' => $c_id))->getField('a_id', TRUE);

                    $where['a_id'] = array('IN', implode(',', array_merge($teachers, $students)));

                } else {
                    // 任课教师只获取本班学生与本科目相关的动态
                    $students = M('ClassStudent')->where(array('c_id' => $c_id))->getField('a_id', TRUE);
                    $where['a_id'] = array('IN', implode(',', $students));

                    if ($subject) {
                        $where['tr_course'] = $subject;
                    }

                }
            }

            // 学生班级动态
            if ($a_type == 1) {

                // $subject为科目ID
                $where = $this->studentTrend($a_id, $subject);
            }

        }


        // 获取*天之内的动态数据
        $where['tr_created'] = array('gt', time() - C('TREND_WITH_IN_TIME') * 3600 *24);

        // 获取动态数据
        $result = getListByPage('Trend', 'tr_id DESC', $where, C('PAGE_SIZE'), $is_ajax, $p);

        if ($result['list']) {

            // 获取配置常量
            if ($a_id && !$a_type && !$c_id && !$subject) {
                // 我的动态
                $trendType = C('MY_TREND');
            } else {
                $trendType = C('TREND_TYPE');
            }

            $auth = getDataByArray('Auth', $result['list'], 'a_id', 'a_id,a_nickname');

            foreach ($result['list'] as $key => $value) {
                $result['list'][$key]['a_nickname'] = $auth[$value['a_id']]['a_nickname'];
                $result['list'][$key]['tr_action'] = $trendType[$value['tr_obj']]['action'][$value['tr_action']];
                $result['list'][$key]['tr_obj'] = $trendType[$value['tr_obj']]['name'];
                $result['list'][$key]['tr_title'] = $value['tr_title'];
                $result['list'][$key]['tr_created'] = date('Y-m-d H:i:s', $value['tr_created']);
                $result['list'][$key]['a_avatar'] = getAuthAvatar($auth[$value['a_id']]['a_avatar'], $auth[$value['a_id']]['a_type'], $auth[$value['a_id']]['a_sex'], 48);
            }

        }

        if ($is_ajax) {

            $result['page'] = $p;
            $result = json_encode($result);
        }

        return $result;
    }

    // 学生课程动态
    public function studentTrend($a_id, $subject) {

        $cIds = M('ClassStudent')->where(array('a_id' => $a_id))->getField('c_id', TRUE);

        // 获取所在班级的该课程的教师
        if ($subject) {
            $where['cst_course'] = $subject;
            $res['tr_course'] = $subject;
        }

        $where['c_id'] = array('IN', implode(',', $cIds));
        $teachers  = M('ClassSubjectTeacher')->where($where)->getField('a_id', TRUE);

        // 获取我的同班同学(除了自己)
        $students = M('ClassStudent')->where(array('c_id' => array('IN', $cIds), 'a_id' => array('neq', $a_id)))->getField('a_id', TRUE);

        $res['a_id'] = array('IN', implode(',', array_merge($teachers, $students)));

        return $res;
    }

    // 群组动态
    public function crowdTrend($cro_id, $s_id, $is_ajax, $p = 1) {

        // 组织条件
        $map['cro_id'] = $cro_id ? $cro_id : intval($_POST['id']);
        $map['s_id'] = $s_id ? $s_id : intval($_POST['s_id']);
        $map['a_id'] = array('IN', M('AuthCrowd')->where($map)->getField('a_id', TRUE));

        // 把群主信息也加进去
        array_unshift($map['a_id'][1], M('Crowd')->where(array('cro_id' => $map['cro_id'], 's_id' => $map['s_id']))->getField('a_id'));

        $result = getListByPage('Trend', 'tr_id DESC', $map, C('PAGE_SIZE'), $is_ajax, $p);
        if ($result['list']) {

            // 获取配置
            $trendType = C('TREND_TYPE');

            // 群组中成员信息
            $auth = getDataByArray('Auth', $result['list'], 'a_id', 'a_id,a_nickname');

            foreach ($result['list'] as $key => $value) {
                $result['list'][$key]['a_nickname'] = $auth[$value['a_id']]['a_nickname'];
                $result['list'][$key]['tr_action'] = $trendType[$value['tr_obj']]['action'][$value['tr_action']];
                $result['list'][$key]['tr_obj'] = $trendType[$value['tr_obj']]['name'];
                $result['list'][$key]['tr_title'] = $value['tr_title'];
                $result['list'][$key]['tr_created'] = date('Y-m-d H:i:s', $value['tr_created']);
                $result['list'][$key]['a_avatar'] = getAuthAvatar($auth[$value['a_id']]['a_avatar'], $auth[$value['a_id']]['a_type'], $auth[$value['a_id']]['a_sex'], 48);
            }
        }

        if ($is_ajax) {
            $result['page'] = $p;
            $result = json_encode($result);
        }

        return $result;
    }
}