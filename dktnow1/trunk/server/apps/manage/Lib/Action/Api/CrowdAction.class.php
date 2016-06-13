<?php
/**
 * CrowdAction
 * 群组接口
 *
 * 作者:  徐少龙
 * 创建时间: 2013-7-4
 *
 */
class CrowdAction extends OpenAction {

    // 群组成员
    public function authList() {

        // 拆分接收的参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id) || !intval($cro_id)) {
            $this->ajaxReturn($this->errCode[2]);
        }

        $this->auth = getAuthInfo($this->auth);

        // 判断用户是否在该群组内
        if (!in_array($cro_id, $this->auth['cro_id'])) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 获取群组群员
        $list = M('AuthCrowd')->where(array('cro_id' => $cro_id))->select();
        if(!$list) {
            $arr['status'] = 0;
            $arr['info'] = '无匹配数据';
        } else {

            $arr['status'] = 1;
            $arr['info'] = array('list' => $list);
        }

        $this->ajaxReturn($arr);
    }

    // 获取群组列表
    public function lists() {

        // 拆分接收的参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id)) {
            $this->ajaxReturn($this->errCode[2]);
        }

        // type:1该用户创建的群组，2该用户加入的群组
        // 0该用户创建的群组和加入的群组
        if (!$type) {
            $cro_id = M('AuthCrowd')->where(array('a_id' => $a_id, 's_id' => $this->auth['s_id']))->getField('cro_id', TRUE);
            $where['cro_id'] = array('IN', $cro_id);
            $where['a_id'] = $a_id;
            $where['_logic'] = 'OR';
        } elseif ($type == 1) {
            $where['a_id'] = $a_id;
            $where['s_id'] = $this->auth['s_id'];
        } elseif ($type == 2) {
            $cro_id = M('AuthCrowd')->where(array('a_id' => $a_id, 's_id' => $this->auth['s_id']))->getField('cro_id', TRUE);
            $where['cro_id'] = array('IN', $cro_id);
        } else {
            $this->ajaxReturn($this->errCode[6]);
        }

        $crowd = M('Crowd')->where($where)->field('cro_id,cro_title,a_id,cro_logo,cro_peoples')->select();
        if(!$crowd) {
            $arr['status'] = 0;
            $arr['info'] = '无匹配数据';
        } else {
            $arr['status'] = 1;
            $arr['info'] = array('list' => $crowd);
        }

        $this->ajaxReturn($arr);
    }
}