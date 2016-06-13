<?php
/**
 * StudentStatAction
 * 学生审核
 *
 */
class StudentStatAction extends CommonAction{

    public function index() {
        $this->display();
    }

    // 列表
    public function lists() {

        // 获取学生申请
        $res = getListByPage('ApplyAuth', 'aa_id DESC', array('s_id' => $this->authInfo['s_id'], 'aa_type' => 1, 'c_id' => array('neq', 0)),'' , 1, intval($_POST['p']));

        // 获取用户信息
        $auth = M('Auth')->where(array('a_id' => array('IN', implode(',', getValueByField($res['list'], 'aa_a_id'))), 'a_type' => 1))->field('a_id,a_nickname,a_account')->select();
        $auth = setArrayByField($auth, 'a_id');

        // 获取班级信息
        $class = getDataByArray('Class', $res['list'], 'c_id', 'c_id,s_id,c_type,c_grade,c_title,c_is_graduation');

        // 整理数据
        foreach ($res['list'] as $key => $value) {

            if ($value['aa_a_id']) {
                if ($auth[$value['aa_a_id']]) {
                    $res['list'][$key]['a_nickname'] = $auth[$value['aa_a_id']]['a_nickname'] ? $auth[$value['aa_a_id']]['a_nickname'] : $auth[$value['aa_a_id']]['a_account'];
                } else {
                    unset($res['list'][$key]);
                    continue;
                }
            } else {
                $res['list'][$key]['a_nickname'] = $value['aa_nickname'];
            }

            $res['list'][$key]['c_name'] = replaceClassTitle($class[$value['c_id']]['s_id'], $class[$value['c_id']]['c_type'], $class[$value['c_id']]['c_grade'], $class[$value['c_id']]['c_title'], $class[$value['c_id']]['c_is_graduation']);

            $res['list'][$key]['created'] = date('Y-m-d H:i', $value['aa_created']);
        }

        echo json_encode($res);
    }

    // 删除
    public function delete() {
        $res = M('ApplyAuth')->where(array('aa_id' => array('IN', $_POST['id']), 's_id' => $this->authInfo['s_id'], 'aa_type' => 1))->delete();

        if ($res) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }

        echo json_encode($data);
    }

    // 通过
    public function pass() {

        $applyAuth = M('ApplyAuth')->where(array('aa_id' => array('IN', $_POST['id']), 's_id' => $this->authInfo['s_id'], 'aa_type' => 1))->select();

        if (!$applyAuth) {
            echo 0;exit;
        }

        // 给该用户添加默认导航数据
        $navigations = C('NAVIGATION');

        foreach ($applyAuth as $ak => $av) {

            $grade = GradeToYear($av['aa_grade'], $av['s_id']);

            if (!$av['aa_a_id']) {

                // 生成账号
                $str = $grade . getStrLast($av['s_id'], 4) . $av['aa_schoolType'] . $av['aa_grade'] . $av['c_id'];
                $data['a_account'] = generateAccount($str);
                $data['a_nickname'] = $av['aa_nickname'];
                $password = rand(100000, 999999);
                $data['a_password'] = md5($password);
                $data['a_created'] = time();
                $data['a_applications'] = C('DEFAULT_APP');
                $data['s_id'] = $av['s_id'];
                $data['a_type'] = 1;
                $data['a_year'] = $grade;
                $data['a_status'] = 1;
                $av['aa_a_id'] = M('Auth')->add($data);

                $nav_data['a_id'] = $av['aa_a_id'];
                $nav_data['na_created'] = time();

                foreach ($navigations as $v) {
                    $nav_data['na_title'] = $v['title'];
                    $nav_data['na_url'] = $v['url'];
                    M('Navigation')->add($nav_data);
                }

                // 账号生成消息体
                $message = '申请人' . $data['a_nickname'] . '账号已生成，账号：' . $data['a_account'] . '，密码：' . $password . '，请妥善保管账号密码。';
            } else {
                $data['a_nickname'] = '您';
                M('Auth')->where(array('a_id' => $av['aa_a_id']))->save(array('s_id' => $av['s_id'], 'a_school_type' => $av['aa_schoolType'], 'a_year' => $grade));
            }

            $class = M('Class')->where(array('c_id' => $av['c_id'], 's_id' => $av['s_id']))->field('s_id,c_type,c_grade,c_title,c_is_graduation,c_id')->find();

            // 加入班级消息体
            $message .= '申请加入班级已成功，' . $data['a_nickname'] . '已成为' . getSchoolNameById($class['s_id']) . replaceClassTitle($class['s_id'], $class['c_type'], $class['c_grade'], $class['c_title'], $class['c_is_graduation']) . '的学生';

            // 将学生加入到班级
            D('Auth')->addStudent($av['aa_a_id'], $class);
            M('Auth')->where(array('a_id' => $av['aa_a_id']))->save(array('s_id' => $av['s_id']));
            // 向申请人发送申请成功消息
            $res['me_identity'] = $school[$class['s_id']]['s_title'] . '管理员';
            $res['me_a_id'] = $this->authInfo['a_id'];
            $res['me_content'] = $message;
            $res['a_id'] = $av['a_id'];
            $res['me_created'] = time();

            M('Message')->add($res);

            // 删除申请
            M('ApplyAuth')->where(array('aa_id' => $av['aa_id'], 's_id' => $this->authInfo['s_id'], 'aa_type' => 1))->delete();

        }

        echo 1;
    }
}
?>