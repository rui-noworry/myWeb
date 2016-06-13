<?php
/**
 * TopicTermModel
 * 题库模型
 *
 */
class TopicTermModel extends CommonModel{

    protected $_validate = array(
        array('tt_title', 'require', '请输入标签名称'),
    );

    protected $_auto = array(
        array('tt_created', 'time', self::MODEL_INSERT, 'function'),
        array('tt_updated', 'time', self::MODEL_UPDATE, 'function'),
    );
}