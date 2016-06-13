<?php
/**
 * ReplyModel
 * 回复模型
 *
 * 作者:  徐少龙
 * 创建时间: 2013-6-13
 *
 */
class ReplyModel extends Model {

    protected $_validate = array(
        array('com_id', 'require', '请选择回复的资源ID'),
        array('rep_content', 'require', '请填写回复内容')
    );

    protected $_auto = array(
        array('rep_created', 'time', self::MODEL_INSERT, 'function'),
        array('rep_updated', 'time', self::MODEL_UPDATE, 'function')
    );
}