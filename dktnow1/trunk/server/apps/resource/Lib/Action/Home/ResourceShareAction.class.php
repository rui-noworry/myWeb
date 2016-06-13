<?php
/**
 * ResourceShareAction
 * 资源分享类
 *
 * 作者:  徐少龙
 * 创建时间: 2013-7-8
 *
 */
class ResourceShareAction extends BaseAction {

    // 写入
    public function insert() {

        // 验证参数
        if (!strval($_POST['ar_id']) || !strval($_POST['a_id'])) {
            $this->error('非法操作');
        }

        $ar_id = explode(',', $_POST['ar_id']);
        $a_id = explode(',', $_POST['a_id']);

        $data['a_id'] = $this->authInfo['a_id'];

        // 写入前，还需过滤已经分享过的资源，以免重复分享
        $where['ar_id'] = array('IN', $ar_id);
        $id = M('ResourceShare')->where($where)->field('ar_id,rs_a_id')->select();
        $tmp = array();
        if ($id) {
            foreach ($id as $k => $v) {
                if (!array_key_exists($v['ar_id'], $tmp)) {
                    $tmp[$v['ar_id']][] = $v['rs_a_id'];
                } else {
                    array_push($tmp[$v['ar_id']], $v['rs_a_id']);
                }
            }
        }

        // 循环写入
        foreach ($a_id as $key => $value) {
            foreach ($ar_id as $k => $v) {
                if (array_key_exists($v, $tmp) && in_array($value, $tmp[$v])) {
                    continue;
                } else {
                    $data['rs_a_id'] = $value;
                    $data['ar_id'] = $v;
                    $data['rs_created'] = time();
                    $result = M('ResourceShare')->add($data);
                }
            }
        }

        // 更新资源的ar_is_shared字段为1
        M('AuthResource')->where(array('ar_id' => array('IN', $ar_id)))->save(array('ar_is_shared' => 1));

        $this->success('分享成功');
    }

    // 删除
    public function delete() {

        // 验证参数
        if (!strval($_POST['ar_id'])) {
            $this->error('非法操作');
        }

        // 传递了flag参数，说明是被分享人删除了，否则就是分享人删除
        if ($_POST['flag']) {
            $where['rs_a_id'] = $this->authInfo['a_id'];
        } else {
            $where['a_id'] = $this->authInfo['a_id'];
        }

        $where['ar_id'] = array('IN', strval($_POST['ar_id']));

        // 检测资源是否存在
        $flags = M('ResourceShare')->where($where)->getField('ar_id', TRUE);
        if (!$flags) {
            $this->error('非法操作');
        }

        // 删除
        $result = M('ResourceShare')->where($where)->delete();
        if (!$result) {
         $this->error('删除失败');
        }

        // 更新资源的ar_is_shared字段为0
        if (!$_POST['flag']) {
            M('AuthResource')->where(array('ar_id' => array('IN', strval($_POST['ar_id']))))->save(array('ar_is_shared' => 0));
        }

        $this->success('删除成功');
    }
}