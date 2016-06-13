<?php
/**
 * MessageAction
 * 消息
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-2
 *
 */
class MessageAction extends BaseAction{

    public function index() {

        $res = getListByPage('Message', 'me_id DESC', array('a_id' => $this->authInfo['a_id']));

        // 获取me_a_id的用户信息
        $auth = M('Auth')->where(array('a_id' => array('IN', implode(',', getValueByField($res['list'], 'me_a_id')))))->field('a_id,a_nickname,a_account')->select();
        $auth = setArrayByField($auth, 'a_id');

        // 获取a_id的用户信息
        $meAuth = M('Auth')->where(array('a_id' => array('IN', implode(',', getValueByField($res['list'], 'a_id')))))->field('a_id,a_nickname,a_account')->select();
        $meAuth = setArrayByField($meAuth, 'a_id');

        // 整理数据
        foreach ($res['list'] as $key => $value) {
            $res['list'][$key]['me_a_nickname'] = $auth[$value['me_a_id']]['a_nickname'] ? $auth[$value['me_a_id']]['a_nickname'] : $auth[$value['me_a_id']]['a_account'];
            $res['list'][$key]['a_a_nickname'] = $meAuth[$value['a_id']]['a_nickname'] ? $meAuth[$value['a_id']]['a_nickname'] : $meAuth[$value['a_id']]['a_account'];
        }

        $this->assign('message', $res);
        $this->display();
    }

}
?>