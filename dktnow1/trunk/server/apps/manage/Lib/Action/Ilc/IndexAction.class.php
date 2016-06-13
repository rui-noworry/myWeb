<?php
/**
 * IndexAction
 * 后台首页
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-11-26
 *
 */

class IndexAction extends CommonAction {
	
    // 首页
    public function index() {

        if (!isset($_SESSION[C('USER_AUTH_KEY')])) {
            $this->redirect('/Ilc/Public/login');
        }
        $this->display();
    }

    // 首页
    public function main() {
        // 统计数据
        $this->display();
    }
}

?>