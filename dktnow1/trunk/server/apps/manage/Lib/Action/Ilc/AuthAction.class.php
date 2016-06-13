<?php
/**
 * IndexAction
 * 后台首页
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-11-26
 *
 */

class AuthAction extends CommonAction {

    // 首页
    public function lists() {

        foreach ($_POST as $key => $value) {
            if ($value) {
                $map[$key] = array('LIKE', '%' . $value . '%');
            }
        }

        $lists = M('Auth')->where($map)->select();

        if (intval($_REQUEST['is_ajax'])) {
            echo json_encode($lists);
        }
    }
}
?>