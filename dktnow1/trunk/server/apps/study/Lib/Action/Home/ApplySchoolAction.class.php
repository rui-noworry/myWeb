<?php
/**
 * ApplySchoolAction
 * 申请学校模块
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-9
 *
 */
class ApplySchoolAction extends BaseAction{

    // 申请页面
    public function index() {
        $this->display();
    }

    // 添加学校
    public function insert() {

        // 接收参数
        $data['as_title'] = trim($_POST['as_title']);

        if (!$data['as_title']) {
            $this->error('参数错误');
        }

        $lastApplyTime = M('ApplySchool')->where(array('as_title' => $data['as_title']))->getField('as_created');

        if ($lastApplyTime + C('SCHOOL_APPLY_TIME_LIMIT') * 24 * 3600 > time()) {
            $result['status'] = 0;
            $result['info'] = C('SCHOOL_APPLY_TIME_LIMIT');
            echo json_encode($result);
            exit;
        }

        $data['a_id'] = $this->authInfo['a_id'];
        $data['as_region'] = trim($_POST['region']);
        $data['as_header_tel'] = trim($_POST['as_header_tel']);
        $data['as_my_tel'] = trim($_POST['as_my_tel']);
        $data['as_type'] = trim($_POST['as_type']);
        $data['as_created'] = time();

        // 执行
        $result['status'] = M('ApplySchool')->add($data);

        echo json_encode($result);

    }


}
?>