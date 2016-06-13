<?php
/**
 * ResourceModel
 * 资源模型
 *
 * 作者:  黄蕊
 * 创建时间: 2013-6-5
 *
 */
class ResourceModel extends CommonModel {

    protected $_auto = array(
        array('re_created', 'time', self::MODEL_INSERT, 'function'),
        array('re_updated', 'time', self::MODEL_UPDATE, 'function'),
    );

    // 批量同步数据到缓存表
    public function syncList($lists, $models, $force = false){
        foreach($lists as $value){
            $this->sync($value, $models, $force);
        }
    }

    // 同步数据到缓存表
    public function sync($resource, $models, $force = false){

        if($force || $resource['re_updated'] > $resource['re_publish']) {
            // 需要同步
            N('sync', 1);
            foreach ($resource as $key => $value){
                if(is_array($value)) {
                    $resource[$key] = serialize($value);
                }
            }

            // 获取扩展属性
            $attributeRecord = M('AttributeRecord')->where(array('re_id' => $resource['re_id']))->select();
            foreach ($attributeRecord as $aKey => $aValue) {
                $resource[$aValue['ar_name']] = $aValue['ar_value'];
            }

            // 获取模型名
            $model = $models[$resource['re_type']]['m_name'];

            // 写入缓存表
            return M('Cache'.ucfirst($model))->add($resource);
        }

        return false;
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
        $model = loadCache('model');
        $model = setArrayByField($model, 'm_id');

        $time = date(C('RESOURCE_SAVE_RULES'), $res['re_created']);

        $data = getResourceConfigInfo(1);
        $filePath = $data['Path'][0] . $model[$res['m_id']]['m_name'] . '/' . $time . "/";;


        $file = pathinfo($res['re_savename']);

        download($filePath, iconv('utf-8', 'gbk', $file['filename']), $file['extension']);

    }

}
?>