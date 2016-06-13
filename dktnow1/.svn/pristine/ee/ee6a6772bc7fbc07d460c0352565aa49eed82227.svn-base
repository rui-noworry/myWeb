<?php

class ActivityModel extends CommonModel {

    protected $_validate = array(

    );

    protected $_auto = array(
        array('act_created', 'time', self::MODEL_INSERT, 'function'),
        array('act_updated', 'time', self::MODEL_UPDATE, 'function')
    );

    // 下载资源
    public function download($id) {

        if (!$id) {
            return 0;
        }

        // 验证
        $res = M('AuthResource')->where(array('ar_id' => $id))->find();

        if (!$res) {
            return 0;
        }

        // 组织数据，准备下载
        $table = getResourceConfigInfo(0);
        reloadCache('model');
        $model = loadCache('model');
        $model = setArrayByField($model, 'm_id');
        $res['ar_is_transform'] = $res['ar_is_transform'] != 1 ? 0 : 1;

        $path = $table['Path'][$res['ar_is_transform']] . $model[$res['m_id']]['m_name'] . '/' . date(C('RESOURCE_SAVE_RULES'), $res['ar_created']) . '/' . substr($res['ar_savename'], 0, strrpos($res['ar_savename'], '.')) . '.' . $res['ar_ext'];

        $fileName = $res['ar_title'];

        download($path, iconv("utf-8", "gb2312", $fileName), $res['ar_ext'], false);
    }

    // 获取文件目录下的所有文件名
    public function file_lists($folder) {

        //打开目录
        $fp = opendir($folder);

         //阅读目录
        while(false != $file = readdir($fp)) {

            //列出所有文件并去掉'.'和'..'
            if($file != '.' && $file != '..') {

                $file = "$file";

                //赋值给数组
                $arr_file[] = $folder.$file;

            }
        }

        return $arr_file;
    }

}