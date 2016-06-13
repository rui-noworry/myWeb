<?php
/**
 * AuthAction
 * 用户上传图片
 *
 * 作者:  黄蕊
 * 创建时间: 2013-7-24
 *
 */
class AuthAction extends OpenAction {

    public function picture() {

        // 接收参数
        extract($_POST['args']);

        if (empty($a_id)) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        if ($_FILES['picture']['size']) {

            $allowType = C('ALLOW_FILE_TYPE');

            $path = C('AUTH_PICTURE');

            unlink($path . $_FILES['picture']['name']);

            parent::upload($allowType['image'], $path, FALSE, '', '', '', '', FALSE, '');

            $data['url'] = turnTpl($path . $_FILES['picture']['name']);

            $this->ajaxReturn($data);
        }
    }
}
?>