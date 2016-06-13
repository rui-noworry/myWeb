<?php
/**
 * ResourceAction
 * 资源
 *
 *
 */
class ResourceAction extends OpenAction {

    // 登录
    public function listByClasshour() {

        extract($_POST['args']);

        // 接收参数
        if (empty($cl_id) || empty($a_id) || (!intval($c_id) && !intval($cro_id))) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        $this->auth = getAuthInfo($this->auth);

        if (!in_array($c_id, $this->auth['c_id']) && !in_array($cro_id, $this->auth['cro_id'])) {
            $this->ajaxReturn($this->errCode[2]);
            exit;
        }

        if ($this->auth['a_type'] == 1) {

            if ($c_id) {
                $map['c_id'] = $c_id;
            }

            if ($cro_id) {
                $map['cro_id'] = $cro_id;
            }

            $map['act_type'] = 5;
            $map['cl_id'] = $cl_id;

            $res = M('ActivityPublish')->where($map)->getField('to_id', TRUE);
        }

        if ($this->auth['a_type'] == 2) {

            $where['a_id'] = $a_id;
            $where['cl_id'] = $cl_id;
            $where['act_type'] = 5;

            $res = M('Activity')->where($where)->getField('act_rel', TRUE);
        }

        if (!$res) {
            $this->ajaxReturn($res);
        }

        $res = implode(',', $res);

        // 获取模型列表
        $model = loadCache('model');
        $model = setArrayByField($model, 'm_id');

        // 已添加的资源
        $resource = M('AuthResource')->where(array('ar_id' => array('IN', $res)))->order('ar_id DESC')->select();

        $filePath = getResourceConfigInfo(0);

        foreach ($resource as $key => $value) {
            $result[$key]['ar_id'] = $value['ar_id'];
            $result[$key]['ar_image'] = getResourceImg($value, 0, 100, 2);
            $result[$key]['ar_title'] = $value['ar_title'];

            $time = date(C('RESOURCE_SAVE_RULES'), $value['ar_created']);
            $trans = $value['ar_is_transform'] == 1 ? 1 : 0;
            $file = $filePath['Path'][$trans].$model[$value['m_id']]['m_name'].'/'.$time;

            if ($value['m_id'] == 1) {
                $value['filePath'] = $file.'/600/'.$value['ar_savename'];
            } elseif ($value['m_id'] == 4) {
                $value['filePath'] = $file.'/'. getFileName($value['ar_savename'], 'pdf');
            } else {
                $value['filePath'] = $file.'/'.$value['ar_savename'];
            }

            $result[$key]['m_id'] = $value['m_id'];
            $result[$key]['ar_savename'] = turnTpl($value['filePath']);
        }

        $this->ajaxReturn($result);

    }
}