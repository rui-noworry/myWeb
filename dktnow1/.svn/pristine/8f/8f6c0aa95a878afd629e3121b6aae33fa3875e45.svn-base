<?php
/**
 * ResourceExamineAction
 * 资源审核
 *
 * 作者:  黄蕊
 * 创建时间: 2013-6-13
 *
 */
class ResourceExamineAction extends CommonAction{

    public function index() {

        // 搜索条件
        if (intval($_POST['rc_id'])) {
            $where['rc_id'] = intval($_POST['rc_id']);
            $this->assign('rc_id', intval($_POST['rc_id']));
        }

        if ($_POST['re_title']) {
            $where['re_title'] = array('LIKE', '%'.$_POST['re_title'].'%');
            $this->assign('re_title', $_POST['re_title']);
        }

        // 获取栏目信息
        $category = M('ResourceCategory')->select();
        $category = setArrayByField($category, 'rc_id');

        // 审核通过的
        $where['re_is_pass'] = 0;

        // 获取数据
        $result = getListByPage('Resource', 're_id DESC', $where, C('PAGE_SIZE'));

        // 处理数据
        foreach ($result['list'] as $key => $value) {
            $result['list'][$key]['rc_title'] = $category[$value['rc_id']]['rc_title'];
        }

        $this->assign('list', $result['list']);
        $this->assign('page', $result['page']);
        $this->display();
    }

    /*
     * 获取下级栏目信息
     * $id 栏目ID
     * $level 层级
     */
    public function findSub() {

        // 返回值
        $result = array();

        $id = intval($_POST['id']);
        $where = 'rc_pid = ' . $id . ' AND s_id = 0';

        // 获取栏目
        $resourceCategory = M('ResourceCategory')->where($where)->select();

        echo json_encode($resourceCategory);
    }

    // 资源审核
    public function resPass() {

        // 接收参数
        $ids = $_REQUEST['id'];
        $type = intval($_REQUEST['type']);

        if (!$ids) {
            $this->error('操作失败');
        }

        if ($type) {
            $data['re_is_pass'] = $type;
            $res = M('Resource')->where(array('re_id' => array('IN', $ids)))->save($data);
        } else {
            $res = M('Resource')->where(array('re_id' => array('IN', $ids)))->delete();
        }

        if ($res) {
            $this->success('成功');
        }
    }

    // 编辑
    public function edit() {

        // 接收参数
        $id = intval($_REQUEST['id']);

        if (!$id) {
            $this->error('请选择编辑项');
        }

        $data = M('Resource')->where(array('re_id' => $id))->find();

        // 模型信息
        $model = loadCache('model');
        $model = setArrayByField($model, 'm_id');

        // 获取文档路径
        $time = date(C('RESOURCE_SAVE_RULES'), $data['re_created']);
        $filePath = getResourceConfigInfo(1);

        if ($data['m_id'] == 1) {
            $data['filePath'] = getResourceImg($data, 1, 600);
        } else {
            $data['filePath'] = $filePath['Path'][$data['re_is_transform']].$model[$data['m_id']]['m_name'].'/'.$time.'/'.$data['re_savename'];
        }

        $this->assign('data', $data);
        $this->display();
    }

    public function update() {

        if (!isset($_POST)) {
            $this->error('操作失败');
        }

        if ($_POST['re_is_pass']) {
            M('Resource')->save($_POST);
        } else {
            M('Resource')->where(array('re_id' => $_POST['re_id']))->delete();
        }

        $this->redirect('/Ilc/ResourceExamine/');
    }

    // 下载资源
    public function downLoad() {

        D('Resource')->download();
    }

}
?>