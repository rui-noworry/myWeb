<?php
/**
 * NoticeAction
 * 通知
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-18
 *
 */
class NoticeAction extends BaseAction{

    public function insert() {

        // 验证
        if (!in_array(intval($_POST['c_id']), $this->authInfo['class_manager'])) {
            $this->error('非法操作');
        }

        // 整理数据
        $_POST['a_id'] = $this->authInfo['a_id'];
        $_POST['no_status'] = intval($_POST['no_status']);
        $_POST['s_id'] = $this->authInfo['s_id'];
        $_POST['no_created'] = time();

        if (!$_POST['no_content']) {
            $this->error('通知内容不可为空');
        }

        // 写入通知
        $no_id = M('Notice')->add($_POST);

        if ($_POST['no_status']) {

            unset($_POST);
            $_POST['no_id'] = $no_id;

            $this->published();
        }

        $return['status'] = $no_id;

        echo json_encode($return);
    }

    // 发布
    public function published() {

        if (!$peoples = M('Notice')->where(array('no_id' => intval($_POST['no_id']), 'a_id' => $this->authInfo['a_id']))->field('no_peoples,c_id')->find()) {
            exit;
        }

        M('Notice')->where(array('no_id' => $_POST['no_id']))->save(array('no_status' => 1, 'no_published' => time()));
        $people = explode(',', $peoples['no_peoples']);

        $res['no_id'] = $_POST['no_id'];
        $res['np_created'] = time();
        $res['s_id'] = $this->authInfo['s_id'];
        $res['c_id'] = $peoples['c_id'];

        foreach ($people as $value) {
            $res['a_id'] = $value;
            M('NoticePublish')->add($res);
        }

        if (intval($_POST['is_ajax'])) {
            echo 1;
        }

        return 1;
    }

    // 删除
    public function delete() {

        if (!$no_id = M('Notice')->where(array('no_id' => intval($_POST['no_id']), 'a_id' => $this->authInfo['a_id']))->getField('no_id')) {
            $this->error('非法操作');
        }

        M('Notice')->delete($no_id);
        M('NoticePublish')->where(array('no_id' => $no_id))->delete();

        $this->success('操作成功');

    }
}
?>