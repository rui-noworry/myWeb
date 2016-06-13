<?php
/**
 * PublicAction
 * 公共方法
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-6-4
 *
 */
class PublicAction extends OpenAction {

    // 初始化
    public function clientInit() {

        $result = array();

        $client_index_pic = C('CONFIG_TMP_PATH') . 'client_index_pic.jpg';

        if (file_exists($client_index_pic)) {
            $result['client_index_pic'] = turnTpl($client_index_pic);
        }

        $this->ajaxReturn($result);
    }

    // 登录
    public function login() {

        extract($_POST['args']);

        // 接收参数
        if (empty($username) || empty($password) || intval($version) < 0 || empty($type)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 读取数据
        $result = M('Auth')->where(array('a_account' => $username, 'a_type' => $type))->find();

        if (!$result) {
            $this->ajaxReturn($this->errCode[5]);
            exit;
        }

        // 验证
        if ($result && ($result['a_password'] == md5($password))) {

            $data = array();
            if ($result['a_status'] != 1) {

                $this->ajaxReturn($this->errCode[5]);
                exit;
            }

            // 组织数据
            $info['a_id'] = $result['a_id'];
            $info['a_last_login_time'] = time();
            $info['a_last_login_ip'] = ip2long(get_client_ip());
            $info['a_login_count'] = array('exp', 'a_login_count+1');

            M('Auth')->save($info);
            $skey = urlencode(setPassportId($result['a_id']));

            $data['auth_name'] = $result['a_nickname'];
            $data['auth_id'] = $result['a_id'];
            $data['auth_type'] = $result['a_type'];
            $data['skey'] = $skey;
            $data['auth_img'] = turnTpl(getAuthAvatar($result['a_avatar'], $result['a_type'], $result['a_sex']));
            $data['s_id'] = $result['s_id'];

            $checkType = intval($versionType)? $versionType : (intval($clientType) ? $clientType : 1);

            // 检测客户端版本号
            if ($checkType) {

                // ANDROID
                if ($checkType == 1) {

                    if ($type == 1) {
                        if (C('ANDRIOD_STUDENT_VERSION') > intval($version)) {
                            $data['version'] = turnTpl(C('CONFIG_TMP_PATH') . strtolower('ANDRIOD_STUDENT_VERSION') . '.apk');
                        } else {
                            $data['version'] = 0;
                        }
                    }

                    if ($type == 2) {
                        if (C('ANDRIOD_TEACHER_VERSION') > intval($version)) {
                            $data['version'] = turnTpl(C('CONFIG_TMP_PATH') . strtolower('ANDRIOD_TEACHER_VERSION') . '.apk');
                        } else {
                            $data['version'] = 0;
                        }
                    }

                }

                // IOS
                if ($checkType == 2) {

                    $ip = C('NETWORK_IP');
                    $ip = $ip ? $ip : $_SERVER['HTTP_HOST'];
                    if ($type == 1) {
                        if (C('IOS_STUDENT_VERSION') > intval($version)) {
                            $data['version'] = 'itms-services://?action=download-manifest&url=http://' . $ip . '/Client/iosPath/status/1';
                        } else {
                            $data['version'] = 0;
                        }
                    }

                    if ($type == 2) {
                        if (C('IOS_TEACHER_VERSION') > intval($version)) {

                            $data['version'] = 'itms-services://?action=download-manifest&url=http://' . $ip . '/Client/iosPath/';
                        } else {
                            $data['version'] = 0;
                        }
                    }
                }

                // ANDROID_SX
                if ($checkType == 3) {

                    if ($type == 1) {
                        if (C('ANDRIOD_SX_STUDENT') > intval($version)) {
                            $data['version'] = turnTpl(C('CONFIG_TMP_PATH') . strtolower('ANDRIOD_SX_STUDENT') . '.apk');
                        } else {
                            $data['version'] = 0;
                        }
                    }

                    if ($type == 2) {
                        if (C('ANDRIOD_SX_TEACHER') > intval($version)) {
                            $data['version'] = turnTpl(C('CONFIG_TMP_PATH') . strtolower('ANDRIOD_SX_TEACHER') . '.apk');
                        } else {
                            $data['version'] = 0;
                        }
                    }

                }
            }

            $this->ajaxReturn($data);

        } else {

            $this->ajaxReturn($this->errCode[5]);
            exit;
        }
    }
}
?>