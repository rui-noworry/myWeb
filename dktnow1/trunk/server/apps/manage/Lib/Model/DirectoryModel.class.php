<?php
/**
 * DirectoryModel
 * 课文目录管理模型
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-20
 *
 */
class DirectoryModel extends CommonModel {

    protected $_auto = array(

        array('d_created', 'time', self::MODEL_INSERT, 'function'),
        array('d_updated', 'time', self::MODEL_UPDATE, 'function'),

    );

    protected $_validate = array(

        array('d_name', 'require', '名称必须'),
        array('d_type', 'require', '所属学段必须'),
        array('d_grade', 'require', '所属年级必须'),
        array('d_semester', 'require', '所属学期必须'),
        array('d_version', 'require', '所属版本必须'),
        array('d_subject', 'require', '所属学科必须'),
    );
}
?>