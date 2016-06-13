<?php
/**
 * PublicAction
 * 公共模块
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-11-26
 *
 */

class PublicAction extends CommonAction {

    public function index() {
        //如果通过认证跳转到首页
        redirect(__GROUP__.'/');
    }

    // 用户登录页面
    public function login() {
        if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
            if(!empty($_GET['forward'])) {
                Cookie('__forward__', $_GET['forward']);
            }
            $this->display();
        }else{
            redirect(__GROUP__.'/');
        }
    }

    // 验证码显示
    public function verify() {
        import("@.ORG.Util.Image");
        $length  =  C('VERIFY_CODE_LENGTH');
        if(strpos($length, ',')) {
            $rand   = explode(',', $length);
            $length = floor(mt_rand(intval($rand[0]), intval($rand[1])));
        }
        Image::buildImageVerify($length? $length: 4);
    }

    // 登录检测
    public function checkLogin() {

        if (empty($_POST['u_account'])) {
            $this->error('帐号不能为空！', '');
        } elseif (empty($_POST['u_password'])){
            $this->error('密码必须！', '');
        }elseif ('' === trim($_POST['verify'])){
            $this->error('验证码必须！', '');
        }

        //生成认证条件
        $map['u_account'] = $_POST['u_account'];
        $map["u_status"]  = array('gt',0);

        if($_SESSION['verify'] != md5($_POST['verify'])) {
            $this->error('验证码错误！', '', 'verify');
        }

        import('@.ORG.Util.RBAC');
        $authInfo = RBAC::authenticate($map);

        //使用用户名、密码和状态的方式进行认证
        if (false === $authInfo) {
            $this->error('帐号不存在或已禁用！', '');
        } else {

            if ($authInfo['u_password'] != pwdHash($_POST['u_password'])) {
                $this->error('密码错误！', '');
            }

            $_SESSION[C('USER_AUTH_KEY')]   = $authInfo['u_id'];
            $_SESSION['uNickName']          = $authInfo['u_nickname'];
            $_SESSION['uLastLoginTime']     = $authInfo['u_last_login_time'];
            $_SESSION['uLoginCount']        = $authInfo['u_login_count'];

            if ($authInfo['u_account'] == 'admin') {
                $_SESSION['administrator']  = true;
            } else{
                // 缓存访问权限
                RBAC::saveAccessList();
            }

            //保存登录信息
            $User = M('User');
            $ip   = ip2long(get_client_ip());
            $time = time();
            $data = array();
            $data['u_id']               = $authInfo['u_id'];
            $data['u_last_login_time']  = $time;
            $data['u_login_count']      = array('exp','(u_login_count+1)');
            $data['u_last_login_ip']    = $ip;
            $User->save($data);

            //保存登录日志
            $login  = M("LoginLog");
            $login  -> u_id     = $authInfo['u_id'];
            $login  -> in_time  = $time;
            $login  -> login_ip = $ip;
            $loginId= $login->add();
            $_SESSION['loginId'] = $loginId;
            $this->success('登录成功！');
        }
    }

    // 检查用户是否登录
    protected function checkUser() {
        if (!isset($_SESSION[C('USER_AUTH_KEY')])) {
            $this->assign('jumpUrl', __GROUP__ . '/Public/login');
            $this->error('没有登录');
        }
    }

    // 顶部页面
    public function top() {
        $this->display();
    }

    // 尾部页面
    public function footer() {
        $this->display();
    }

    // 菜单页面
    public function menu() {
        $this->display();
    }

    // 后台首页
    public function main() {

        $this->checkUser();

        // to do 首页逻辑

        $this->display();
    }

    // 用户登出
    public function logout() {
        if (isset($_SESSION[C('USER_AUTH_KEY')])) {
            $loginId = $_SESSION['loginId'];
            unset($_SESSION['loginId']);
            unset($_SESSION[C('USER_AUTH_KEY')]);
            unset($_SESSION);
            session_destroy();
            //保存登出记录
            $loginLog   =   M('LoginLog');
            $loginLog->out_time= time();
            $loginLog->id      = $loginId;
            $loginLog->save();
            $this->assign("jumpUrl",'__APPURL__/Ilc/Public/login/');
            $this->redirect('__APPURL__/Ilc/Public/login');
        } else {
            $this->error('已经登出！');
        }
    }

    // 用户资料
    public function profile() {

        // 检查登录
        $this->checkUser();

        // 获取用户数据
        $vo = M("User")->getByUId($_SESSION[C('USER_AUTH_KEY')]);

        // 赋值显示
        $this->assign('vo',$vo);
        $this->display();
    }

    // 修改资料
    public function change() {

        // 检查登录
        $this->checkUser();

        $User = D("User");
        if (!$User->create()) {
            $this->error($User->getError());
        }

        $_POST['u_updated'] = time();
        $result = $User->save();

        if (false !== $result) {
            $this->success('资料修改成功！');
        } else{
            $this->error('资料修改失败!');
        }
    }

    // 更换密码
    public function changePwd() {
        $this->checkUser();

        //对表单提交处理进行处理或者增加非表单数据
        if (pwdHash($_POST['verify']) != $_SESSION['verify']) {
            $this->error('验证码错误！');
        }

        $map = array();

        $map['u_password']= pwdHash($_POST['oldpassword']);
        $map['u_id'] = $_SESSION[C('USER_AUTH_KEY')];

        //检查用户
        $User = M("User");
        if (!$User->where($map)->getField('u_id')) {
            $this->error('旧密码不符或者用户名错误！');
        } else {
            $data['u_password'] = pwdHash($_POST['u_password']);
            $data['u_updated']  = time();
            $User->where(array('u_id' => $map['u_id']))->save($data);
            $this->assign('jumpUrl',__GROUP__.'/Public/main');
            $this->success('密码修改成功！');
        }
    }

    // 通过学校类型获取年级
    public function getGradeByType() {

        // 接收参数
        $id = intval($_POST['id']);
        $s_id = intval($_POST['s_id']);

        // 判断是否为大学
        if ($id == 4) {
            /*if (!$s_id) {
                $info = member();
                $s_id = $info['s_id'];
            }*/
            // 如果还是没有学校ID，则说明是大后台的课程目录那块过来的，则取消专业显示
            if (!$s_id) {
                $res = getGradeByType($id, $s_id);
            } else {
                $res = M('Major')->where(array('s_id' => $s_id))->field('ma_id,ma_title')->select();
                $tmp = array();
                foreach ($res as $k => $v) {
                    $tmp[$v['ma_id']] = $v['ma_title'];
                }
                $res = $tmp;
            }
        } else {
            if ($_POST['type'] != 'undefined') {
                $id = 4;
            }
            $res = getGradeByType($id, $s_id);
        }

        $result = array();
        foreach ($res as $key => $value) {

            $tmp = array();
            $tmp['key'] = $key;
            $tmp['value'] = $value;

            $result[] = $tmp;
        }

        echo json_encode($result);
    }
}
?>