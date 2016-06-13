<?php
/**
 * ResourceCategoryModel
 * 资源栏目模型
 *
 * 作者:  黄蕊
 * 创建时间: 2012-6-6
 *
 */
class ResourceCategoryModel extends CommonModel {

    protected $_auto = array(
        array('rc_created', 'time', self::MODEL_INSERT, 'function'),
        array('rc_updated', 'time', self::MODEL_UPDATE, 'function'),
    );

    protected $_validate = array(
        array('rc_title', 'require', '请填写栏目名称'),
        array('m_id', 'require', '请选择栏目模型'),
    );
}
?>