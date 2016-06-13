<?php
/**
 * SchoolRoleMode
 * 学校管理组模型
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2012-12-03
 *
 */

class SchoolRoleModel extends CommonModel {
    protected $_auto = array(
        array('sr_created', 'time', 'function', self::MODEL_INSERT),
    );

    protected $_validate = array(
        array('sr_name', 'require', '组名不可为空'),
    );
}
?>