<?php
/**
 * TrendModel
 * 动态模型
 *
 * 作者:  徐少龙
 * 创建时间: 2013-06-18
 *
 */
class TrendModel extends CommonModel {

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