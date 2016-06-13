<?php
/**
 * ResourceCategoryAction
 * 资源栏目管理模块
 *
 * 作者: 黄蕊
 * 创建时间: 2013-6-6
 *
 */
class ResourceCategoryAction extends CommonAction{

    public function _initialize() {

        parent::_initialize();

        // 获取模型
        $this->model = loadCache('model');
    }

    public function index(){

        $map['rc_pid'] = intval($_REQUEST['rc_pid']);

        // 获取上级节点
        $res = M('ResourceCategory')->find($_REQUEST['rc_pid']);

        $this->assign('rc_level', $res['rc_level'] + 1);
        $this->assign('rc_num', $res['rc_num']);

        // 数据分页
        $list = getListByPage('ResourceCategory', 'rc_id DESC', $map, C('PAGE_SIZE'));

        // 处理数据
        foreach ($list['list'] as $key => $value) {
            $list['list'][$key]['is_show'] = $value['rc_is_show'] ? '显示' : '不显示';
            $list['list'][$key]['pid_name'] = $res['rc_title'] ? $res['rc_title'] : '无';
        }

        $this->assign('tag', getResourceCategoryParents($map['rc_pid'], 0, 1));
        $this->assign('page', $list['page']);
        $this->assign('list', $list['list']);
        $this->assign('rc_pid', $map['rc_pid']);
        $this->assign('rc_title', $_REQUEST['rc_title']);
        $this->assign('maxLevel', C('RESOURCE_CATEGORY_MAX_LEVEL'));
        $this->display();
    }

    public function insert() {

        $_POST['a_id'] = $_SESSION['authId'];
        parent::insertData();
        $this->redirect('/Ilc/'.$this->getActionName().'/index/rc_pid/'.$_POST['rc_pid']);
    }

    public function update() {

        parent::updateData();

        $this->redirect('/Ilc/'.$this->getActionName().'/index/rc_pid/'.$_POST['rc_pid']);
    }

    public function _before_add(){

        if (intval($_REQUEST['rc_num'])) {
            $this->error('若要新增栏目, 请先转移该栏目下的资源');
        }

        // 查找数据
        $ResourceCategory = M('ResourceCategory')->find(intval($_REQUEST['rc_id']));

        // 页面赋值
        $this->assign('tag', getResourceCategoryParents(intval($_REQUEST['rc_id']), 0, 1));
        $this->assign('rc_pid', intval($ResourceCategory['rc_id']));
        $this->assign('rc_level', intval($ResourceCategory['rc_level']+1));
    }

    public function _before_edit(){
        $this->assign('tag', getResourceCategoryParents(intval($_REQUEST['id']), 0, 1));
    }

    // 删除栏目
    public function del() {

        $rc_id = $_REQUEST['id'];

        if (!$rc_id) {
            $this->error('参数错误');
        }

        // 查看该栏目下是否有子栏目
        $category = M('ResourceCategory')->where(array('rc_pid' => $rc_id))->select();

        if (is_array($category)) {
            $this->error('请先删除子栏目');
        }

        $res = M('ResourceCategory')->where(array('rc_id' => $rc_id))->delete();

        if ($res) {
            $this->success('删除成功');
        }
    }

}
?>