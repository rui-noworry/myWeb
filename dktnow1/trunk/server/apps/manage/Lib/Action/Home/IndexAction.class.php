<?php
/**
 * IndexAction
 * 首页
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-2
 *
 */
class IndexAction extends BaseAction{

    public function index() {
        echo '';
    }

    public function upload() {
        $file = time() . '_' . md5(microtime()) . '.jpg';
        $path = '../Uploads/Ueditor/';
        file_put_contents($path . $file, file_get_contents("php://input"));
        echo json_encode(array('url' => substr($path, 1) . $file));
    }
}
?>