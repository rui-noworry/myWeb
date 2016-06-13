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
        $this->display();
    }

    // 获取资源列表
    public function lists() {

        // 获取模型信息
        $model = reloadCache('model');
        $model = setArrayByField($model, 'm_id');

        $result = D('Resource')->lists($this->authInfo['s_id'], 0);

        // 获取发布资源人的信息
        $auth = getDataByArray('Auth', $result['list'], 'a_id', 'a_id, a_nickname');

        foreach ($result['list'] as $key => $value) {
            $result['list'][$key]['m_title'] = $model[$value['m_id']]['m_title'];
            $result['list'][$key]['a_nickname'] = $auth[$value['a_id']]['a_nickname'];
        }

        echo json_encode($result);
    }

    // 资源审核
    public function resPass() {

        // 接收参数
        $ids = $_POST['id'];
        $type = intval($_POST['type']);

        if (!$ids) {
            echo 0;
        }

        if ($type) {
            $data['re_is_pass'] = $type;
            $res = M('Resource')->where(array('re_id' => array('IN', $ids)))->save($data);
        } else {
            $res = M('Resource')->where(array('re_id' => array('IN', $ids)))->delete();
        }

        if ($res) {
            echo 1;
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

        // 获取所属栏目
        $data['rc_title'] = M('ResourceCategory')->where(array('rc_id' => $data['rc_id']))->getField('rc_title');

        // 学校信息
        $school = loadCache('school');

        $data['s_name'] = $school[$data['s_id']]['s_name'];

        // 用户信息
        $auth = getDataByArray('Auth', $data, 'a_id', 'a_id, a_nickname');
        $data['a_nickname'] = $auth[$data['a_id']]['a_nickname'];

        // 模型信息
        $model = reloadCache('model');
        $model = setArrayByField($model, 'm_id');

        // 获取文件路径
        $time = date(C('RESOURCE_SAVE_RULES'), $data['re_created']);
        $filePath = getResourceConfigInfo(1);

        if ($data['m_id'] == 1) {
            $data['filePath'] =
            $filePath['Path'][$data['re_is_transform']].$model[$data['m_id']]['m_name'].'/'.$time.'/600/'.$data['re_savename'];
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