<?php
/**
 * TermAction
 * 标签类
 *
 * 作者:  徐少龙 (xusl@mink.com.cn)
 * 创建时间: 2013-5-13
 *
 */
class TermAction extends BaseAction {

    // 添加
    public function insert() {

        // 权限控制
        if ($this->authInfo['a_type'] !=2)  {
            $this->error('非法操作');
        }

        // 对于新添加的标签，事先要到标签库里查询一下，如果有的话，就在使用量+1，否则就直接添加
        $term = M('Term')->where(array('te_title' => $_POST['te_title']))->getField('te_id');
        if ($term) {
            $data['te_count'] = array('exp','te_count+1');
            $result = M('Term')->where(array('te_id' => $term['te_id']))->save($data);
            $this->success($term);
            exit;
        }

        // 接收参数
        $_POST['te_count'] = intval($_POST['te_count']);
        $_POST['te_created'] = time();

        $result = $this->insertData();
        if (!$result) {
            $this-error('操作失败');
        }
        $this->success($result);

    }

    // 更新
    public function update() {

        // 权限控制
        if ($this->authInfo['a_type'] !=2)  {
            $this->error('非法操作');
        }

        // 接收参数
        $where['te_id'] = intval($_POST['te_id']);

        // 如果有flag参数，说明是删除，则使用次数减1
        if (intval($_POST['flag'])) {
            $data['te_count'] = array('exp','te_count-1');
        } else {
            $data['te_count'] = array('exp','te_count+1');
        }

        $result = M('Term')->where($where)->save($data);
        if (!$result) {
            $this-error('操作失败');
        }
        $this->success('操作成功');

    }
}