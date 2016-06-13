<?php

/**
 * StudentNotesAction
 * 学生笔记
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-12-26
 *
 */

class StudentNotesAction extends OpenAction {

    // 上传笔记
    public function upload() {

        // 接收参数
        extract($_POST['args']);

        if (empty($cl_id) || empty($a_id)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        // 上传笔迹文件
        if(!$_FILES['stu_notes']['size']){
            $this->ajaxReturn($this->errCode[2]);
        }

        if ($this->auth['a_type'] == 2) {
            $this->ajaxReturn($this->errCode[6]);
        }

        // 设置规则
        $config['dir'] = $a_id % 3000;
        $config['rule'] = $a_id . '-' . $cl_id;

        $path = C('STUDENT_NOTES') . $config['dir'] . '/';
        $file = $path . $a_id . '-' . $cl_id . '.db';

        // 删除原笔记
        if (file_exists($file)) {
            unlink($file);
        }

        $allowType = C('ALLOW_MINDMARK_TYPE');
        $res = parent::upload($allowType, $path, TRUE, '', '', '');
        rename($path.$res, $file);
        $result['status'] = 1;

        $this->ajaxReturn($result);
    }

    // 获取笔记
    public function get() {

        // 接收参数
        extract($_POST['args']);

        if (empty($cl_id) || empty($a_id)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        if ($this->auth['a_type'] == 2) {
            $this->ajaxReturn($this->errCode[6]);
        }

        $dir = $a_id % 3000;

        $path = C('STUDENT_NOTES') . $dir . '/' . $a_id . '-' . $cl_id . '.db';

        if (file_exists($path)) {
            $path = turnTpl($path);
        } else {
            $path = '';
        }

        $this->ajaxReturn(array('stu_notes' => $path));
    }
}
?>