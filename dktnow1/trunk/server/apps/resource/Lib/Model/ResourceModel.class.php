<?php
/**
 * ResourceModel
 * 资源模型
 *
 * 作者:  黄蕊
 * 创建时间: 2013-6-13
 *
 */
class ResourceModel extends CommonModel {

    protected $_auto = array(
        array('re_created', 'time', self::MODEL_INSERT, 'function'),
        array('re_updated', 'time', self::MODEL_UPDATE, 'function'),
    );

    // 资源列表
    public function lists($s_id, $is_pass) {

        if (!$s_id) {
            $result['status'] = 0;
            return $result; exit;
        }

        $p = intval($_POST['p']) ? intval($_POST['p']) : 1;

        $where['re_is_pass'] = $is_pass;

        $where['s_id'] = $s_id;

        if ($_POST['re_title'] != '') {
            $where['re_title'] = array('LIKE', '%'.$_POST['re_title'].'%');
        }

        if (intval($_POST['rc_id'])) {
            $where['rc_id'] = intval($_POST['rc_id']);
        }

        $result = getListByPage('Resource', 're_id DESC', $where, C('PAGE_SIZE'), 1, $p);

        if ($result['list']) {
            $result['status'] = 1;
        }

        return $result;

    }


    // 下载资源
    public function downLoad() {

        // 接收参数
        $id = $_REQUEST['id'];

        if (!$id) {
            $this->error('下载失败');
        }

        // 获取资源信息
        $res = M('Resource')->find($id);

        // 获取模型信息
        $model = reloadCache('model');
        $model = setArrayByField($model, 'm_id');

        $time = date(C('RESOURCE_SAVE_RULES'), $res['re_created']);

        $data = getResourceConfigInfo(1);
        $filePath = $data['Path'][0] . $model[$res['m_id']]['m_name'] . '/' . $time . "/";;

        $file = pathinfo($res['re_savename']);

        download($filePath, iconv('utf-8', 'gbk', $file['filename']), $file['extension']);

    }

    // 管理员发布资源
    public function managerPublish($a_id, $re_id, $rc_id, $re_points) {

        $result['status'] = 0;

        if (!$a_id || !$re_id || !$rc_id) {
            return $result;exit;
        }

        // 资源表
        $nowTable = getResourceConfigInfo(1);

        $res = M($nowTable['TableName'])->where(array('re_id' => array('IN', $re_id)))->select();

        if (count($res) < count($re_id)) {
            return $result;exit;
        }

        // 判断是否发布过
        $where['re_id'] = array('IN', $re_id);
        $where['rc_id'] = array('IN', $rc_id);
        $where['a_id'] = $a_id;

        if (M('Resource')->where($where)->select()) {
            $result['status'] = 0;
            $result['message'] = '您已经发过此资源';
            return $result;exit;
        }

        $res = setArrayByField($res, 're_id');

        // 通过栏目ID获取学校ID
        $resCate = M('ResourceCategory')->where(array('rc_id' => array('IN', $rc_id)))->select();
        $resCate = setArrayByField($resCate, 'rc_id');

        // 获取模型信息
        $model = reloadCache('model');
        $model = setArrayByField($model, 'm_id');

        foreach ($re_id as $key => $value) {

            $data['re_updated'] = time();
            $data['a_id'] = $a_id;
            $data['re_id'] = $value;
            $data['re_title'] = $res[$value]['re_title'];
            $data['re_savename'] = $res[$value]['re_savename'];
            $data['m_id'] = $res[$value]['m_id'];
            $data['rc_id'] = $rc_id[$key];
            $data['s_id'] = intval($resCate[$rc_id[$key]]['s_id']);
            $data['re_is_transform'] = $res[$value]['re_is_transform'];
            $data['re_ext'] = $res[$value]['re_ext'];
            $data['re_download_points'] = intval($re_points[$key]);

            $re_id = M($nowTable['TableName'])->where(array('re_id' => $value))->save($data);

            if ($data['re_id']) {

                // 添加资源属性数据
                $attr = $_POST['text'][$key+1];

                foreach ($attr as $ak => $av) {

                    if ($av['are_name'] && $av['are_value']) {
                        $av['re_id'] = $data['re_id'];

                        M('AttributeRecord')->add($av);
                    }
                }

            }

        }

        if ($re_id) {
            $result['status'] = 1;
            return $result;
        }
    }

}