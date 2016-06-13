<?php
/**
 * ClasshourPackageAction
 * 课程包
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-12-26
 *
 */
class ClasshourPackageAction extends OpenAction {

    // 添加课时包
    public function insert() {

        // 接收参数
        extract($_POST['args']);

        if (empty($cl_id) || empty($co_id) || empty($a_id) || empty($cpa_title) || empty($cpa_status)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 上传笔迹文件
        if($_FILES['cpa_file']['size']){

            $allowType = C('ALLOW_MINDMARK_TYPE');
            $data['cpa_path'] = parent::upload($allowType, C('CLASSHOUR_PACKAGE'), TRUE, '', '', '');
        } else {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 参数处理
        $data['cl_id'] = $cl_id;
        $data['co_id'] = $co_id;
        $data['a_id'] = $a_id;
        $data['cpa_title'] = urldecode($cpa_title);
        $cpa_status = intval($cpa_status);
        $data['cpa_status'] = $cpa_status ? $cpa_status : 1;
        $data['cpa_created'] = time();

        $res = M('ClasshourPackage')->add($data);

        if ($res) {
            $result['status'] = 1;
        } else {
            $result['status'] = 0;
        }

        $this->ajaxReturn($result);
    }

    // 课时包列表
    public function lists() {

        // 接收参数
        extract($_POST['args']);

        if (empty($cl_id)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 获取数据
        $result = M("ClasshourPackage")->where(array('cl_id' => $cl_id))->order('cpa_status DESC, cpa_id DESC')->select();
        $auth = getDataByArray('Auth', $result, 'a_id', 'a_id,a_nickname');

        foreach ($result as $key => $value) {
            $result[$key]['cpa_path'] = turnTpl(C('CLASSHOUR_PACKAGE') . $value['cpa_path']);
            $result[$key]['a_nickname'] = $auth[$value['a_id']]['a_nickname'];
        }

        $this->ajaxReturn($result);
    }

    // 删除课时包
    public function remove() {

        // 接收参数
        extract($_POST['args']);

        if (empty($cpa_id) || empty($a_id)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 条件整理
        $where['a_id'] = intval($a_id);
        $where['cpa_id'] = intval($cpa_id);

        // 删除文件
        $cpa_path = M("ClasshourPackage")->where($where)->getField('cpa_path');
        @unlink(C('CLASSHOUR_PACKAGE') . $cpa_path);

        // 删除数据
        $res = M("ClasshourPackage")->where($where)->delete();

        if ($res) {
            $result['status'] = 1;
        } else {
            $result['status'] = 0;
        }

        $this->ajaxReturn($result);
    }

    // 更新课时包
    public function update() {

        // 接收参数
        extract($_POST['args']);

        if (empty($cpa_id) || empty($a_id)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 上传笔迹文件
        if($_FILES['cpa_file']['size']){

            $allowType = C('ALLOW_FILE_TYPE');
            $data['cpa_path'] = parent::upload($allowType['mindmark'], C('CLASSHOUR_PACKAGE'), TRUE, '', '', '');
        }

        // 参数处理
        $data['a_id'] = $a_id;

        if ($cpa_title) {
            $data['cpa_title'] = $cpa_title;
        }

        $cpa_status = intval($cpa_status);
        if ($cpa_status) {
            $data['cpa_status'] = $cpa_status;
        }

        $data['cpa_updated'] = time();

        // 条件
        $where['cpa_id'] = intval($cpa_id);

        $res = M("ClasshourPackage")->where($where)->save($data);

        if ($res) {
            $result['status'] = 1;
        } else {
            $result['status'] = 0;
        }

        $this->ajaxReturn($result);
    }
}
?>