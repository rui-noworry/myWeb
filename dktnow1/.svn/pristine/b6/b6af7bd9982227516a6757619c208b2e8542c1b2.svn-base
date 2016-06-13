<?php
/**
 * ResourceAccessStatModel
 * 资源访问模型
 * 创建时间: 2013-6-3
 *
 */
class ResourceAccessStatModel extends CommonModel{

    // 添加访问日志
    public function insert($re_id, $s_id, $ras_from = 0, $a_id = 0) {

        // 接收参数
        $data['re_id'] = $re_id;
        $data['ras_created'] = time();
        $data['s_id'] = $s_id;

        if (!$a_id) {
            $a_id = intval(isLogin());
        }

        $ip = ip2long(getClientIp());

        $time = M('ResourceAccessStat')->where(array('re_id' => $re_id, 'ras_ip' => $ip))->order('ras_id DESC')->getField('ras_created');

        if ($data['ras_created'] - $time < 10 * 60) {
            return ;
        }

        $data['a_id'] = $a_id;
        $data['ras_from'] = $ras_from;
        $data['ras_ip'] = $ip;

        M('ResourceAccessStat')->add($data);

        // 更新资源表
        M('Resource')->where(array('re_id' => $re_id))->save(array('re_hits' => array('exp', 're_hits+1'), 're_updated' => time()));
    }
}