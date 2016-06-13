<?php
/**
 * ClickResourceAction
 * 资源点击接口
 *
 * 作者:  徐少龙
 * 创建时间: 2013-7-4
 *
 */
class ClickResourceAction extends OpenAction {

    // 记录点击量
    public function insertNum() {

        // 拆分接收的参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id) || !intval($re_id) || !intval($re_type)) {
            $this->ajaxReturn($this->errCode[2]);
        }

        // 检测资源是否存在，是否已转码，是否为视频
        $resource = M('Resource')->where(array('re_id' => $re_id))->field('re_id,re_is_transform,m_id')->find();
        if (!$resource || $resource['re_is_transform'] != 1 || $resource['m_id'] != 2) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 字段赋值
        $data['a_id'] = $a_id;
        $data['s_id'] = $this->auth['s_id'];
        $data['re_id'] = $re_id;
        $data['re_type'] = $re_type;
        $data['rcl_created'] = time();
        $data['rcl_ip'] = get_client_ip();

        // 查询上次点击的时间
        $result = M('ResourceClick')->where(array('re_id' => $data['re_id'], 'a_id' => $a_id, 'rcl_ip' => $data['rcl_ip']))->max('rcl_created');

        // 判断同一IP是否已过24小时
        if (intval($result)+86400 < intval($data['rcl_created'])) {
            // 保存数据
            $res = M('ResourceClick')->add($data);
        }
        if ($res) {
            // 点击量+1
            $result = M('Resource')->where(array('re_id' => $re_id))->save(array('re_updated' => time(), 're_hits' => array('exp', 're_hits+1')));
            if ($result) {
                $this->ajaxReturn(array('status' => 1, 'info' => '成功'));
            } else {
                $this->ajaxReturn(array('status' => 0, 'info' => '失败'));
            }
        }else {
            $this->ajaxReturn(array('status' => 0, 'info' => '失败'));
        }



    }
}