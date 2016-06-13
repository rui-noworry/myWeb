<?php
/**
 * CommitModel
 * 评论模型
 *
 * 作者:  徐少龙
 * 创建时间: 2013-6-9
 *
 */
class CommitModel extends Model {

    protected $_validate = array(
        array('a_id', 'require', '请选择用户'),
        array('s_id', 'require', '请选择学校'),
        array('com_owner_id', 'require', '请选择资源所属人'),
        array('com_object_type', 'require', '请选择资源所属对象类型'),
        array('com_object_id', 'require', '请选择资源所属对象ID'),
        array('com_content', 'require', '请填写评论内容')
    );

    protected $_auto = array(
        array('com_created', 'time', self::MODEL_INSERT, 'function'),
        array('com_updated', 'time', self::MODEL_UPDATE, 'function')
    );
}