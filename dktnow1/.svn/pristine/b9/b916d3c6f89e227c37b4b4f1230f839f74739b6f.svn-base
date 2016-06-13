<?php
/**
 * AttributeModel
 * 属性模型
 *
 * 作者:  黄蕊
 * 创建时间: 2012-6-5
 *
 */
class AttributeModel extends CommonModel {

    protected $_auto = array(
        array('at_created', 'time', self::MODEL_INSERT, 'function'),
        array('at_updated', 'time', self::MODEL_UPDATE, 'function'),
    );

    protected $_validate = array(
        array('at_name', 'require', '属性名称必须'),
        array('at_title', 'require', '属性说明必须'),
        array('at_type', 'require', '属性类型必须'),
    );
}
?>