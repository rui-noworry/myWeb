<?php
/**
 * AuthResourceModel
 * 资源栏目
 *
 * 作者:  黄蕊
 * 创建时间: 2013-6-9
 *
 */
class AuthResourceModel extends CommonModel {

    // 用户资源发布
    public function publish($a_id, $ar_id, $rc_id, $re_points) {

        $result['status'] = 0;

        if (!$a_id || !$ar_id || !$rc_id) {
            $result['message'] = '参数错误';
            return $result;exit;
        }

        // 用户资源表
        $oldTable = getResourceConfigInfo(0);

        // 发布资源表
        $nowTable = getResourceConfigInfo(1);

        $res = M($oldTable['TableName'])->where(array('a_id' => $a_id, 'ar_id' => array('IN', $ar_id)))->select();

        if (count($res) < count($ar_id)) {
            $result['message'] = '';
            return $result;exit;
        }

        // 判断是否发布过
        $where['ar_id'] = array('IN', $ar_id);
        $where['a_id'] = $a_id;

        $check = M('Resource')->where($where)->select();

        $checkTmp = array();

        foreach ($check as $cValue) {
            $checkTmp[$cValue['a_id'].$cValue['ar_id'].$cValue['rc_id']] = 1;
        }

        $res = setArrayByField($res, 'ar_id');

        // 通过栏目ID获取学校ID
        $resCate = M('ResourceCategory')->where(array('rc_id' => array('IN', $rc_id)))->select();
        $resCate = setArrayByField($resCate, 'rc_id');

        // 获取模型信息
        $model = reloadCache('model');
        $model = setArrayByField($model, 'm_id');

        foreach ($ar_id as $key => $value) {

            $data['re_created'] = time();
            $data['a_id'] = $a_id;
            $data['ar_id'] = $value;
            $data['re_title'] = $res[$value]['ar_title'];
            $data['re_savename'] = $res[$value]['ar_savename'];
            $data['m_id'] = $res[$value]['m_id'];
            $data['rc_id'] = $rc_id[$key];
            $data['s_id'] = intval($resCate[$rc_id[$key]]['s_id']);
            $data['re_is_transform'] = $res[$value]['ar_is_transform'];
            $data['re_ext'] = $res[$value]['ar_ext'];
            $data['re_download_points'] = intval($re_points[$key]);

            if ($checkTmp[$data['a_id'].$data['ar_id'].$data['rc_id']] == 1) {
                $result['message'] = '栏目下已有相同资源';
                return $result;
            }

            $re_id = M($nowTable['TableName'])->add($data);

            if ($re_id) {

                // 添加资源属性数据
                $attr = $_POST['text'][$key+1];

                foreach ($attr as $ak => $av) {

                    if ($av['are_name'] && $av['are_value']) {
                        $av['re_id'] = $re_id;

                        M('AttributeRecord')->add($av);
                    }
                }

                // 源文件路径
                $sourceFile = $oldTable['Path'][$res[$value]['ar_is_transform']].$model[$res[$value]['m_id']]['m_name'].'/'.date(C('RESOURCE_SAVE_RULES'), $res[$value]['ar_created']).'/';

                // 现文件路径
                $nowFile = $nowTable['Path'][$res[$value]['ar_is_transform']].$model[$res[$value]['m_id']]['m_name'].'/';

                if (!is_dir($nowFile)) {
                    @mkdir($nowFile, 0755);
                }

                $nowFile .= date(C('RESOURCE_SAVE_RULES'), $res[$value]['ar_created']).'/';

                if (!is_dir($nowFile)) {
                    @mkdir($nowFile, 0755);
                }

                if ($res[$value]['m_id'] == 1) {

                    $thumbBigNowFile = $nowFile . '600/';
                    $thumbSmallNowFile = $nowFile . '100/';

                    if(!is_dir($thumbBigNowFile) || !is_dir($thumbSmallNowFile)) {

                        $big = mkdir($thumbBigNowFile, 0755);
                        $small = mkdir($thumbSmallNowFile, 0755);

                        if(!$big || !$small) {
                            $result['status'] = 0;
                            $result['message'] = '您没有创建目录的权限';
                            return $result;exit;
                        }
                    }
                }

                // 复制文件到resource目录
                copy($sourceFile.'600/'.$res[$value]['ar_savename'], $thumbBigNowFile.$res[$value]['ar_savename']);
                copy($sourceFile.'100/'.$res[$value]['ar_savename'], $thumbSmallNowFile.$res[$value]['ar_savename']);
                $query = copy($sourceFile.$res[$value]['ar_savename'], $nowFile.$res[$value]['ar_savename']);
            }
        }

        if ($re_id) {
            $result['status'] = 1;
        } else {
            $result['message'] = '发布失败';
        }

        return $result;
    }

}