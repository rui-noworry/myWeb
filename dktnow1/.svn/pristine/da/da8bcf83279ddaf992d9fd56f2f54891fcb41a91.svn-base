<?php
/**
 * SchoolRoleAction
 * 学校管理员权限管理
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-9
 *
 */
class SchoolRoleAction extends CommonAction{

    public function _filter(&$map) {

        $map['s_id'] = $this->authInfo['s_id'];
        $_REQUEST['_sort'] = 'sr_id ASC';
    }

    // 插入
    public function insert() {

        $_POST['a_id'] = $this->authInfo['a_id'];
        $_POST['s_id'] = $this->authInfo['s_id'];
        echo parent::insertData();
    }

    // 更新
    public function update() {
        echo parent::updateData();
    }

    // 用户列表
    public function user() {

        // 接收参数
        $sr_id = intval($_GET['id']);

        if (!$sr_id) {
            $this->redirect('index');
        }

        // 验证
        $sr_name = M('SchoolRole')->where(array('sr_id' => $sr_id, 's_id' => $this->authInfo['s_id']))->getField('sr_name');

        if (!$sr_name) {
            $this->redirect('index');
        }

        // 获取组用户列表
        $SchoolRoleUser = M('SchoolRoleUser')->where(array('sr_id' => $sr_id, 's_id' => $this->authInfo['s_id']))->field('a_id')->select();

        // 获取用户详细信息
        $this->userlists = getDataByArray('Auth', $SchoolRoleUser, 'a_id', 'a_id,a_nickname');
        $this->sr_name = $sr_name;
        $this->sr_id = $sr_id;
        $this->display();
    }

    // 保存组用户
    public function saveSchoolRoleUser() {

        // 接收参数
        $sr_id = intval($_POST['sr_id']);
        $a_id = $_POST['a_id'];

        if (!$a_id) {
            echo 1;exit;
        }

        if (!$sr_id) {
            echo 0;exit;
        }

        // 验证是否有此组的操作权限
        $sr_id = M('SchoolRole')->where(array('sr_id' => $sr_id, 's_id' => $this->authInfo['s_id']))->getField('sr_id');

        if (!$sr_id) {
            echo 0;exit;
        }

        // 验证是否所有人都符合条件
        $num = M('Auth')->where(array('a_id' => array('IN', $a_id), 's_id' => $this->authInfo['s_id'], 'a_type' => 2))->count();
        $a_id = explode(',', $_POST['a_id']);

        if ($num != count($a_id)) {
            echo 0;exit;
        }

        // 获取原有组人员
        // $old = M('SchoolRoleUser')->where(array('sr_id' => $sr_id, 's_id' => $this->authInfo['s_id']))->getField('a_id', TRUE);

        // 解除原组员管理员状态
        // M('SchoolRoleUser')->where(array('sr_id' => $sr_id, 's_id' => $this->authInfo['s_id']))->delete();
        // $oldAid = M('SchoolRoleUser')->where(array('a_id' => array('NOT IN', $old), 's_id' => $this->authInfo['s_id']))->getField('a_id', TRUE);
        // M('Auth')->where(array('a_id' => array('IN', $oldAid)))->save(array('a_is_manager' => 0));

        // 组员设置为学校管理员
        M('Auth')->where(array('a_id' => array('IN', $a_id)))->save(array('a_is_manager' => 1));
        // 循环写入数据
        foreach ($a_id as $value) {
            if ($value) {
                $data['a_id'] = $value;
                $data['s_id'] = $this->authInfo['s_id'];
                $data['sr_id'] = $sr_id;

                if (!M('SchoolRoleUser')->add($data)) {
                    echo 0;exit;
                }
            }
        }

        echo 1;
    }

    // 删除组用户
    public function delSchoolRoleUser() {

        $sr_id = intval($_POST['sr_id']);
        $a_id = intval($_POST['a_id']);

        if (!$sr_id || !$a_id) {
            echo 0;exit;
        }

        $where['a_id'] = $a_id;
        $where['s_id'] = $this->authInfo['s_id'];
        $where['sr_id'] = $sr_id;

        M('SchoolRoleUser')->where($where)->delete();
        $flag = M('SchoolRoleUser')->where(array('a_id' => $a_id, 's_id' => $this->authInfo['s_id']))->getField('a_id');

        if (!$flag) {
            M('Auth')->where(array('a_id' => $a_id))->save(array('a_is_manager' => 0));
        }
        echo 1;
    }

    // 授权
    public function commision() {

        // 接收参数
        $sr_id = intval($_GET['id']);

        if (!$sr_id) {
            $this->redirect('index');
        }

        // 验证
        $sr_name = M('SchoolRole')->where(array('sr_id' => $sr_id, 's_id' => $this->authInfo['s_id']))->getField('sr_name');

        if (!$sr_name) {
            $this->redirect('index');
        }

        $schoolAccess = M('SchoolAccess')->where(array('sr_id' => $sr_id, 's_id' => $this->authInfo['s_id']))->getField('sn_id', true);

        // 整理权限数据
        $schoolNode = loadCache('schoolNode');
        foreach ($schoolNode as $key => $value) {

            if ($value['sn_pid'] == 0) {
                $node[$value['sn_id']]['self'] = $value;
            } else {
                $node[$value['sn_pid']]['lists'][] = $value;
            }
        }

        $this->schoolNode = $node;
        $this->schoolAccess = $schoolAccess;
        $this->sr_name = $sr_name;
        $this->sr_id = $sr_id;
        $this->display();
    }

    // 保存授权
    public function saveCommision() {

        // 接收参数
        $sr_id = intval($_POST['sr_id']);
        $sn_id = strval($_POST['sn_id']);

        if (!$sr_id) {
            echo 0;exit;
        }

        // 验证
        $sr_name = M('SchoolRole')->where(array('sr_id' => $sr_id, 's_id' => $this->authInfo['s_id']))->getField('sr_name');

        if (!$sr_name) {
            echo 0;exit;
        }

        // 删除此组原有节点
        M('SchoolAccess')->where(array('sr_id' => $sr_id))->delete();

        // 获取学校所有可操作节点
        $schoolNode = loadCache('schoolNode');

        $sn_id = explode(',', $sn_id);

        // 循环写入
        foreach ($sn_id as $value) {
            if ($value) {
                $data = $schoolNode[$value];
                $data['sr_id'] = $sr_id;
                $data['s_id'] = $this->authInfo['s_id'];
                if (!M('SchoolAccess')->add($data)) {
                    echo 0;
                    exit;
                }
            }
        }

        echo 1;
    }
}
?>