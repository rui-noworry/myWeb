<?php
/**
 * AuthResourceAction
 * 用户资源
 *
 * 作者:  黄蕊
 * 创建时间: 2013-6-27
 *
 */
class AuthResourceAction extends BaseAction {

    // 用户资源列表
    public function lists() {

        $p = intval($_POST['p']) ? intval($_POST['p']) : 1;

        // 接收搜索条件
        $where = 'a_id = ' . $this->authInfo['a_id'];

        // 类型
        if (intval($_POST['m_id'])) {
            $where .= ' and m_id='.intval($_POST['m_id']);
        }

        // 资源标题
        if ($_POST['ar_title']) {
            $where .= ' and ar_title LIKE "%' . $_POST['ar_title'] . '%"';
        }

        // 标签
        if ($_POST['rta_id']) {

            foreach (explode(',', $_POST['rta_id']) as $key => $value) {
                $id = ','.$value.',';
                $tag .= 'OR rta_id LIKE "'.$id.'"';
            }

            $tag  = substr($tag, 3);

            $where .= ' and ('.$tag.')';

        }

        $result = getListByPage('AuthResource', 'ar_id DESC', $where, 12, 1, $p);
        foreach ($result['list'] as $key => $value) {
            $result['list'][$key]['ar_upload'] = getResourceImg($value);
        }

        if ($result) {
            echo json_encode($result);
        } else {
            echo 0;
        }
    }

    // 资源预览
    public function show() {

        // 接收参数
        $id = intval($_REQUEST['ar_id']);

        if (!$id) {
            $this->error('没有此资源');
        }

        $data = M('AuthResource')->find($id);

        if ($data['ar_is_transform'] == 0) {
            $this->error('审核处理中');
        }

        // 模型信息
        $model = loadCache('model');
        $model = setArrayByField($model, 'm_id');

        // 获取文档路径
        $time = date(C('RESOURCE_SAVE_RULES'), $data['ar_created']);
        $filePath = getResourceConfigInfo(0);

        if ($data['m_id'] == 1) {
            $data['filePath'] = $filePath['Path'][$data['ar_is_transform']].$model[$data['m_id']]['m_name'].'/'.$time.'/600/'.$data['ar_savename'];
        } elseif ($data['m_id'] == 4) {
            $data['filePath'] = $filePath['Path'][$data['ar_is_transform']].$model[$data['m_id']]['m_name'].'/'.$time.'/'. getFileName($data['ar_savename'], 'swf');
        } else {
            $data['filePath'] = $filePath['Path'][$data['ar_is_transform']].$model[$data['m_id']]['m_name'].'/'.$time.'/'.$data['ar_savename'];
        }

        $data['filePath'] = turnTpl($data['filePath']);

        $this->assign('data', $data);

        $this->display();
    }

    // 下载
    public function download() {

        $id = intval($_REQUEST['id']);

        if (!$id) {
            $this->error('下载资源不存在');
        }

        // 验证
        $res = M('AuthResource')->find($id);

        if (!$res) {
            $this->error('数据不存在');
        }

        // 组织数据，准备下载
        $table = getResourceConfigInfo(0);
        $model = loadCache('model');
        $model = setArrayByField($model, 'm_id');

        $res['ar_is_transform'] = $res['ar_is_transform'] != 1 ? 0 : 1;

        $path = $table['Path'][$res['ar_is_transform']] . $model[$res['m_id']]['m_name'] . '/' . date(C('RESOURCE_SAVE_RULES'), $res['ar_created']) . '/' . substr($res['ar_savename'], 0, strrpos($res['ar_savename'], '.')) . '.' . $res['ar_ext'];

        $fileName = $res['ar_title'];

        download($path, iconv("utf-8", "gb2312", $fileName), $res['ar_ext'], false);
    }
}