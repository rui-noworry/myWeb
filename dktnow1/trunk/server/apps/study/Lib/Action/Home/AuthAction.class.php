<?php
/**
 * AuthAction
 * 用户模块
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-2
 *
 */
class AuthAction extends BaseAction{

    // 个人信息
    public function index() {

        $this->a_region2 = '"'.str_replace('###','","',$this->authInfo['a_region']).'"';

        $this->a_avatar = getAuthAvatar($this->authInfo['a_avatar'], $this->authInfo['a_type'], $this->authInfo['a_sex']);
        $this->assign('authInfo', $this->authInfo);

        $this->display();
    }

    public function password() {

        $this->display();
    }

    // 更新
    public function update() {

        $_POST['a_id'] = $this->authInfo['a_id'];
        if ($_POST['a_birthday']) {
            $_POST['a_birthday'] = strtotime($_POST['a_birthday']);
        }

        if ($_POST['a_password']) {
            $_POST['a_password'] = md5($_POST['a_password']);
        }
        parent::update();
    }

    // 检查密码是否正确
    public function checkPassword() {

        $return['status'] = intval(M('Auth')->where(array('a_id' => $this->authInfo['a_id'], 'a_password' => md5($_GET['old_password'])))->getField('a_id'));

        echo json_encode($return);
    }

    // 用户上传头像
    public function saveavatar(){

        $allowType = C('ALLOW_FILE_TYPE');

        $data['a_id']  = $this->authInfo['a_id'];

        $rs = array();

        switch($_GET['action']){
            //上传临时图片
            case 'uploadtmp':
                $file = 'uploadtmp.jpg';
                @move_uploaded_file($_FILES['Filedata']['tmp_name'], $file);
                $rs['status'] = 0;
                $rs['url'] = C('AUTH_AVATAR') . $file;
                break;
                //上传切头像
            case 'uploadavatar':

                $input = file_get_contents('php://input');

                $data = explode('--------------------', $input);

                $authAvatar = C('AUTH_AVATAR');

                $image_name = 'a' . $this->authInfo['a_id'] . '.jpg';
                //小图
                $file_name_48 = $authAvatar . '48/' . $image_name;

                //大图
                $file_name_96 = $authAvatar . '96/' . $image_name;
                // 默认图片
                $file_name = $authAvatar . $image_name;

                //生成你要的文件路径和名字开始
                @file_put_contents($file_name_48, $data[0]);
                @file_put_contents($file_name_96, $data[0]);
                @file_put_contents($file_name, $data[1]);

                // 插入数据库
                $map['a_id'] = $this->authInfo['a_id'];
                $map['a_avatar'] = $image_name;
                D('Auth')->save($map);

                //返回状态
                $rs['status'] = 1;

                break;
            default:
                $rs['status'] = -1;
        }

        echo json_encode($rs);
    }
}
?>