<?php
/**
 * TeacherStatAction
 * 教师审核
 *
 */
class TeacherStatAction extends CommonAction{

    public function index() {

        $this->display();
    }

    // 列表
    public function lists() {

        // 获取申请教师
        $res = getListByPage('ApplyAuth', 'aa_id DESC', array('s_id' => $this->authInfo['s_id'], 'aa_type' => 2),'' , 1, intval($_POST['p']));

        // 获取用户信息
        $auth = getDataByArray('Auth', $res['list'], 'a_id', 'a_id,a_account,a_nickname');

        // 获取班级信息
        $class = getDataByArray('Class', $res['list'], 'c_id', 'c_id,s_id,c_type,c_grade,c_title,c_is_graduation');

        // 整理数据
        foreach ($res['list'] as $key => $value) {

            $res['list'][$key]['a_nickname'] = $auth[$value['a_id']]['a_nickname'] ? $auth[$value['a_id']]['a_nickname'] : $auth[$value['a_id']]['a_account'];

            $res['list'][$key]['c_name'] = replaceClassTitle($class[$value['c_id']]['s_id'], $class[$value['c_id']]['c_type'], $class[$value['c_id']]['c_grade'], $class[$value['c_id']]['c_title'], $class[$value['c_id']]['c_is_graduation']);

            $res['list'][$key]['created'] = date('Y-m-d H:i', $value['aa_created']);
        }

        echo json_encode($res);
    }

    // 删除
    public function delete() {
        $res = M('ApplyAuth')->where(array('aa_id' => array('IN', $_POST['id']), 's_id' => $this->authInfo['s_id'], 'aa_type' => 2))->delete();

        if ($res) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }

        echo json_encode($data);
    }

    // 通过
    public function pass() {

        $a_id = M('ApplyAuth')->where(array('aa_id' => array('IN', $_POST['id']), 's_id' => $this->authInfo['s_id'], 'aa_type' => 2))->getField('a_id', TRUE);

        if (!$a_id) {
            echo 0;exit;
        }

        // 删除原数据
        M('ApplyAuth')->where(array('aa_id' => array('IN', $_POST['id']), 's_id' => $this->authInfo['s_id'], 'aa_type' => 2))->delete();

        M('ApplyAuth')->where(array('aa_a_id' => array('IN', $a_id), 'aa_type' => 1))->delete();

        // 修改用户状态
        M('Auth')->where(array('a_id' => array('IN', $a_id)))->save(array('s_id' => $this->authInfo['s_id'], 'a_type' => 2, 'a_year' => date('Y', time())));

        $c_id = M('ClassStudent')->where(array('a_id' => array('IN', $a_id), 's_id' => $this->authInfo['s_id']))->getField('c_id', TRUE);
        M('ClassStudent')->where(array('a_id' => array('IN', $a_id), 's_id' => $this->authInfo['s_id']))->delete();

        M('Class')->where(array('c_id' => array('IN', $c_id), 's_id' => $class['s_id']))->setDec('c_peoples');

        echo 1;
    }
}
?>