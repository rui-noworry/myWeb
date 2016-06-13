<?php
/**
 * ResourceTagModel
 * 资源标签模型
 *
 */
class ResourceTagModel extends CommonModel{

    protected $_validate = array(
        array('rta_title', 'require', '请输入标签名称'),
    );

    protected $_auto = array(
        array('rta_created', 'time', self::MODEL_INSERT, 'function'),
        array('rta_updated', 'time', self::MODEL_UPDATE, 'function'),
    );
}