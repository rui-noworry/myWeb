<?php
/**
 * ModelModel
 * 模型模型
 *
 * 作者:  黄蕊
 * 创建时间: 2013-6-5
 *
 */
class ModelModel extends CommonModel {

    protected $_auto = array(
        array('m_created', 'time', self::MODEL_INSERT, 'function'),
        array('m_updated', 'time', self::MODEL_UPDATE, 'function'),
    );

    protected $_validate = array(
        array('m_name', 'require', '名称必须'),
        array('m_name','','名称必须唯一！',0,'unique',1),
        array('m_title', 'require', '说明必须'),
    );

    // 获取资源模型名称
    public function getModelIdByName($modelName = 'document'){
        if (!$modelName) {
            return false;
        }
        $result = D('Model')->where('m_name=\''.strtolower($modelName).'\'')->getField('m_id');

        if (!$result){
            return false;
        }
        return $result;
    }
}
?>