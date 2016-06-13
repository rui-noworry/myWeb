<?php
/**
 * ResourceCollectAction
 * 收藏类
 *
 * 作者:  徐少龙
 * 创建时间: 2013-6-14
 *
 */
class ResourceCollectAction extends BaseAction {

    // 收藏首页
    public function index() {

        $this->display();
    }

    // 写入
    public function insert() {

        $id = intval($_POST['re_id']);
        if (!$id) {
            $this->error('非法操作');
        }

        // 收藏前，还需查看该资源是否被该用户收藏过
        $data['s_id'] = $this->authInfo['s_id'];
        $data['a_id'] = $this->authInfo['a_id'];
        $data['re_id'] = $id;
        $flag = M('ResourceCollect')->where($data)->find();
        if ($flag) {
            $this->error('该资源已被收藏过');
        }

        // 没有收藏过，便收藏
        $data['rco_created'] = time();
        $result = M('ResourceCollect')->add($data);
        if (!$result) {
            $this->error('收藏失败');
        }
        $this->success('收藏成功');
    }

    // 删除收藏的资源
    public function delete() {

        $id = strval($_POST['id']);
        if (!$id) {
            $this->error('非法操作');
        }

        $result = M('ResourceCollect')->where(array('a_id' => $this->authInfo['a_id'], 're_id' => array('IN', $id)))->delete();
        if (!$result) {
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }

    // 收藏列表
    public function lists() {

        $res = getListByPage('ResourceCollect', 'rco_id DESC', array('a_id' => $this->authInfo['a_id']), 12, 1, intval($_POST['p']));

        $res['list'] = getDataByArray('Resource', $res['list'], 're_id');
        $auth = getDataByArray('Auth', $res['list'], 'a_id');

        foreach ($res['list'] as $key => $value) {
            $res['list'][$key]['a_nickname'] = $auth[$value['a_id']]['a_nickname'];
            $res['list'][$key]['re_img'] = getResourceImg($value, 1);
            $res['list'][$key]['time'] = date('Y-m-d', $value[$time]);
        }

        sort($res['list']);
        echo json_encode($res);
    }

}