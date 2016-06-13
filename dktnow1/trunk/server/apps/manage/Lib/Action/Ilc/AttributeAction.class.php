<?php
/**
 * AttributeAction
 * 属性管理
 *
 * 作者:  黄蕊
 * 创建时间: 2015-6-5
 *
 */
class AttributeAction extends CommonAction {

    // 模型列表
    public function index() {

        // 查询条件
        if ($_REQUEST['at_name']) {
            $where['at_name'] = array('LIKE', "%".$_REQUEST['at_name']."%");
        }

        // 分页获取数据
        $model =  getListByPage('Attribute', 'at_id ASC', $where);

        $this->assign('list', $model['list']);
        $this->assign('page', $model['page']);
        $this->display();
    }

}

?>