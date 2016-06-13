<?php
/**
 * UserAction
 * 后台用户管理
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-12-03
 *
 */
class UserAction extends CommonAction {

    // 过滤
    public function _filter(&$map) {

        $map['u_id'] = array('neq', 1);
    }

    // 验证用户名
    public function checkAccount() {

        if(!preg_match('/^[a-z]\w{4,}$/i',$_POST['u_account'])) {
            $this->error( '用户名必须是以字母打头，且5位以上！');
        }

        $User = D("User");

        // 检测用户名是否冲突
        $result = $User->where(array('u_account' => $_REQUEST['u_account']))->find();

        if($result) {
            $this->error('该用户名已经存在！');
        }else {
            $this->success('该用户名可以使用！');
        }
    }

    // 添加
    public function insert() {

        if(!preg_match('/^[a-z]\w{4,}$/i',$_POST['u_account'])) {
            $this->error( '用户名必须是以字母打头，且5位以上！');
        }

        $User = D("User");
        
        // 检测用户名是否冲突
        $name = $_REQUEST['u_account'];
        $result = $User->where(array('u_account' => $_REQUEST['u_account']))->find();

        if($result) {
            $this->error('该用户名已经存在！');
        }

        parent::insert();
    }

    //重置密码
    public function resetPwd() {

        $id = $_POST['u_id'];
        $password = $_POST['u_password'];

        if(''== trim($password)) {
            $this->error('密码不能为空！');
        }

        parent::update();
    }
}
?>