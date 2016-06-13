<?php
/**
 * SchoolNodeModel
 * 学校节点模型
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-05-07
 *
 */
class SchoolNodeModel extends CommonModel {

    protected $_validate = array(
        array('sn_name', 'require', '标题有误'),
        array('sn_title', 'require', '显示名不可为空'),
        array('sn_name', 'checkNode', '节点已经存在', 0, 'callback'),
    );

    protected $_auto = array(
        array('sn_created', 'time', self::MODEL_INSERT, 'function'),
        array('sn_sort', '255', self::MODEL_INSERT, 'function'),
    );
}
?>