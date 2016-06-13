<?php
class CrowdCheckAction extends BaseAction {

    // 写入数据
    public function insert() {

        // 检测
        if (!intval($_POST['a_id']) || !intval($_POST['cro_id'])) {
            $this->error('非法操作');
        }

        $_POST['cc_a_id'] = intval($_POST['a_id']);
        unset($_POST['a_id']);
        $_POST['a_id'] = $this->authInfo['a_id'];
        $_POST['s_id'] = $this->authInfo['s_id'];
        $_POST['cc_created'] = time();

        $result = parent::insertData();
        if (!$result) {
            $this->error('写入失败');
        }

        // 向动态表里添加数据
        //addTrend(intval($_POST['a_id']), $this->authInfo['s_id'], );
        $this->success($result);
    }

    // 删除
    public function delete() {

        // 检测
        if (!intval($_POST['a_id']) || !intval($_POST['cro_id']) || !intval($_POST['type'])) {
            $this->error('非法操作');
        }

        // 删除
        $result = M('CrowdCheck')->where(array('a_id' => intval($_POST['a_id']), 's_id' => $this->authInfo['s_id'], 'cc_a_id' => $this->authInfo['a_id']))->delete();
        if (!$result) {
            $this->error('操作失败');
        }

        $cro_title = M('Crowd')->where(array('cro_id' => intval($_POST['cro_id'])))->getField('cro_title');

        // 1为允许申请，2为拒绝申请，当为允许申请时，还需把该成员添加到AuthCrowd表，并更新Crowd的cro_peoples字段
        if (intval($_POST['type']) == 1) {

            M('AuthCrowd')->add(array('cro_id' => intval($_POST['cro_id']), 'a_id' => intval($_POST['a_id']), 's_id' => $this->authInfo['s_id']));

            M('Crowd')->where(array('cro_id' => intval($_POST['cro_id'])))->save(array('cro_peoples' => array('exp', 'cro_peoples+1'), 'cro_updated' => time()));

            $me_content = '允许了你的' . $cro_title . '的群组申请';
        } else {
            $me_content = '拒绝了你的' . $cro_title . '的群组申请';
        }

        // 最后向消息表里添加数据
        $a_type = M('Auth')->where(array('a_id' => intval($_POST['a_id'])))->field('a_type,a_is_manager')->find();
        if ($a_type['a_is_manager']) {
            $me_identity = '管理员';
        } elseif ($a_type['a_type'] == 2) {
            $me_identity = '教师';
        } else {
            $me_identity = '学生';
        }
        M('Message')->add(array('me_identity' => $me_identity, 'me_content' => $me_content, 'me_a_id' => $this->authInfo['a_id'], 'a_id' => intval($_POST['a_id']), 'me_created' => time()));

        $this->success('操作成功');

    }
}