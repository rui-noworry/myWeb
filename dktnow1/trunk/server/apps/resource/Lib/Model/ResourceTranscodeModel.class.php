<?php
/**
 * ResourceTranscodeModel
 * 资源转模型
 *
 * 作者:  徐少龙
 * 创建时间: 2013-6-7
 *
 */
class ResourceTranscodeModel extends CommonModel {

    protected $_validate = array(
        array('rt_title', 'require', '请填写模板名称'),
    );

    protected $_auto = array(
        array('rt_created', 'time', self::MODEL_INSERT, 'function'),
        array('rt_updated', 'time', self::MODEL_UPDATE, 'function'),
    );
}