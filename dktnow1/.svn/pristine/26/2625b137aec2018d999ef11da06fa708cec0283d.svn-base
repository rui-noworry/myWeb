<?php
/**
 * ResourceCategoryModel
 * 资源栏目
 *
 * 作者:  徐少龙
 * 创建时间: 2013-6-6
 *
 */
class ResourceCategoryModel extends CommonModel {

    // 验证
    protected $_validate = array(
        array('a_id', 'require', '所属人不能为空'),
        array('rc_title', 'require', '请填写栏目名称'),
    );

    // 自动写入
    protected $_auto = array(
        array('rc_created', 'time', self::MODEL_INSERT, 'function'),
        array('rc_updated', 'time', self::MODEL_UPDATE, 'function'),
    );

    public function showParent($id, $s_id) {
        $str = getResourceCategoryParents(intval($id), $s_id, 1);
        $str = explode('</a>', $str);
        $str = array_filter($str);
        if (count($str) == 1) {
            $link = strip_tags($str[0]);
        } else {
            foreach ($str as $value) {
                $link .= strip_tags($value) . ' > ';
            }
            $link = rtrim($link, ' > ');
        }

        return $link;
    }
}