<?php
/**
 * TagModel
 * 知识点模型
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-20
 *
 */
class TagModel extends CommonModel {

    protected $_auto = array(

        array('t_created', 'time', self::MODEL_INSERT, 'function'),
        array('t_updated', 'time', self::MODEL_UPDATE, 'function'),

    );

    protected $_validate = array(

        array('t_name', 'require', '标签名必须'),
        array('t_subject', 'require', '学科必须'),

    );

}
?>