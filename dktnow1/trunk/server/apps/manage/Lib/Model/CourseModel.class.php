<?php
/**
 * CourseModel
 * 课程接口模型
 *
 * 作者:  徐少龙
 * 创建时间: 2012-7-2
 *
 */
class CourseModel extends CommonModel {

    // 获取绑定班级和群组
    public function getBindClass($data) {

        // 循环数组，把班级ID群组ID给抽出来，放入一个数组中
        foreach ($data as $key => $value) {
            if ($value['c_id']) {
                $arr[] = explode(',', trim($value['c_id'], ','));
            }
            if ($value['cro_id']) {
                $cro[] = explode(',', trim($value['cro_id'], ','));
            }
        }

        // 引用二维数组转化为一维的方法，并查询班级表群组表，得到相关的字段
        $arr = $this->twoToOne($arr);
        $cro = $this->twoToOne($cro);
        $bindClass = setArrayByField(M('Class')->where(array('c_id' => array('IN', $arr)))->field('s_id,c_type,c_grade,c_title,c_is_graduation,c_id')->select(), 'c_id');

        // 得到班级名称
        foreach ($bindClass as $key => &$value) {
            $value['c_title'] = replaceClassTitle($value['s_id'], $value['c_type'], YearToGrade($value['c_grade'], $value['s_id']), $value['c_title'], $value['c_is_graduation']);
        }

        // 得到群组的名称
        $bindGroup = setArrayByField(M('Crowd')->where(array('cro_id' => array('IN', $cro)))->field('cro_id, cro_title')->select(), 'cro_id');

        $temp = array();
        $unsetKey = array();
        $count = 0;

        // 把班级名称群组名称分别绑定到c_id、cro_id字段上
        foreach ($data as $key => &$value) {

            if ($value['c_id']) {
                $arr = explode(',', $value['c_id']);
                $tmp = '';
                foreach ($arr as $k => $v) {
                    if (array_key_exists($v, $bindClass)) {
                        $value['c_name'] = $bindClass[$v]['c_title'];
                        $temp[$count] = $value;
                        $temp[$count]['c_id'] = $v;
                        $temp[$count]['cro_id'] = '';
                        $unsetKey[$key] = $key;
                        $count++;
                    }
                }
            }

            if ($value['cro_id']) {
                $arr = explode(',', $value['cro_id']);
                $tmp = '';
                foreach ($arr as $k => $v) {
                    if (array_key_exists($v, $bindGroup)) {
                        $value['cro_name'] = $bindGroup[$v]['cro_title'];
                        $unsetKey[$key] = $key;
                        $temp[$count] = $value;
                        $temp[$count]['cro_id'] = $v;
                        $temp[$count]['c_id'] = '';
                        $count++;
                    }
                }
            }
        }
        return $temp;
    }

    // 二维数组变一维
    public function twoToOne($data) {
        $arr = array();
        foreach ($data as $key => $value) {
            foreach ($value as $k => $v) {
                $arr[] = $v;
            }
        }
        return $arr;
    }

}