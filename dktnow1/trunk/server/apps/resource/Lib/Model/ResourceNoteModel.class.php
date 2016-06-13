<?php
/**
 * ResourceNoteAction
 * 资源笔记模型
 *
 * 作者:  徐少龙
 * 创建时间: 2013-6-9
 *
 */
class ResourceNoteModel extends CommonModel {

    protected $_validate = array(
        array('a_id', 'require', '请选择用户'),
        array('s_id', 'require', '请选择学校'),
        array('re_id', 'require', '请选择资源'),
        array('rn_time_point', 'require', '请选择时间点'),
        array('rn_content', 'require', '请输入内容')
    );

    protected $_auto = array(
        array('rn_created', 'time', self::MODEL_INSERT, 'function'),
        array('rn_updated', 'time', self::MODEL_UPDATE, 'function')
    );
}